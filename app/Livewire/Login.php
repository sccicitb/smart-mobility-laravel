<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class Login extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('dashboard');
        }

        session()->flash('error', 'Email atau password salah.');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('components.layouts.login');
    }

    //SSOController
    public function loginWithSSO()
    {
        try {
            // Generate state yang aman
            $state = Str::random(40);

            // Simpan state ke session
            session(['state' => $state]);

            // Bangun query parameter
            $query = http_build_query([
                "client_id" => "9dd8b474-1a52-499a-b6c4-8e20f674bd64",
                "redirect_uri" => "http://xsmart-mobility.test/auth/callback",
                "response_type" => "code",
                "scope" => config("auth.sso.scopes"),
                "state" => $state,
                "prompt" => true
            ]);

            // Log redirect details
            \Log::info('SSO Login Redirect', [
                'authorization_url' => config('auth.sso.host') . "/oauth/authorize",
                'query_params' => $query

            ]);

            // Redirect ke halaman otorisasi
            return redirect(config('auth.sso.host') . "/oauth/authorize?" . $query);
        } catch (\Exception $e) {
            \Log::error('SSO Login Initiation Error', [
                'message' => $e->getMessage()
            ]);
            dd($query);

            // dd(Log($e->getMessage()));
            session()->flash('error', 'Terjadi kesalahan saat memulai proses login SSO.');
            return redirect()->route('login');
        }
    }


    public function handleSSOCallback(Request $request)
    {
        try {
            // Logging detail request
            \Log::info('SSO Callback Received', [
                'full_request' => $request->all(),
                'code' => $request->code,
                'state' => $request->state
            ]);

            // Validasi state
            $savedState = session()->pull("state");

            \Log::info('State Validation', [
                'saved_state' => $savedState,
                'received_state' => $request->state
            ]);

            // Validasi state dengan throw exception
            throw_unless(
                strlen($savedState) > 0 && $savedState === $request->state,
                InvalidArgumentException::class,
                'Invalid OAuth state'
            );

            // // Debugging konfigurasi
            // $ssoConfig = [
            //     // "grant_type" => "authorization_code",
            //     "client_id" => "9dd8b474-1a52-499a-b6c4-8e20f674bd64",
            //     "client_secret" => "JSdb7q6V9pHrvTVyP6jI4iiw2g0RNJuknPLjxHd7",
            //     "redirect_uri" => "http://xsmart-mobility.test/auth/callback",
            //     // "code" => $request->code
            // ];

            // \Log::info('SSO Configuration', $ssoConfig);

            // Gunakan Laravel HTTP Client dengan opsi detail
            $response = Http::withOptions([
                'verify' => false,  // Nonaktifkan verifikasi SSL untuk development
                'timeout' => 15,    // Perpanjang timeout
            ])
                ->asForm()
                ->post(
                    config("auth.sso.host") . "/oauth/token",
                    [
                        "grant_type" => "authorization_code",
                        "client_id" => "9dd8b474-1a52-499a-b6c4-8e20f674bd64",
                        "client_secret" => "JSdb7q6V9pHrvTVyP6jI4iiw2g0RNJuknPLjxHd7",
                        "redirect_uri" => "http://xsmart-mobility.test/auth/callback",
                        "code" => $request->code
                    ]
                );

            dd($response->json());
            // Logging response mentah
            \Log::info('SSO Token Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
                'headers' => $response->headers()
            ]);

            // Periksa response
            if ($response->failed()) {
                \Log::error('SSO Token Request Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'error_details' => $response->json()
                ]);
                dd($response->json());
                // Tambahkan pesan error spesifik
                $errorMessage = match ($response->status()) {
                    400 => 'Bad Request: Periksa parameter yang dikirim',
                    401 => 'Unauthorized: Kredensial client tidak valid',
                    403 => 'Forbidden: Akses ditolak',
                    500 => 'Server Error: Terjadi kesalahan di server SSO',
                    default => 'Gagal mengambil token akses dari SSO'
                };

                session()->flash('error', $errorMessage);
                return redirect()->route('login');
            }

            // Parsing response
            $responseData = $response->json();

            // Validasi token
            if (!isset($responseData['access_token'])) {
                \Log::error('No Access Token in Response', [
                    'response_data' => $responseData
                ]);

                session()->flash('error', 'Tidak ada token akses yang diterima.');
                return redirect()->route('login');
            }

            // Ambil token akses
            $accessToken = $responseData['access_token'];

            // Dapatkan informasi pengguna
            $userResponse = Http::withToken($accessToken)
                ->get(config("auth.sso.host") . '/api/user');

            \Log::info('User Info Response', [
                'status' => $userResponse->status(),
                'body' => $userResponse->body()
            ]);

            // Periksa response user
            if ($userResponse->failed()) {
                \Log::error('Failed to fetch user info', [
                    'status' => $userResponse->status(),
                    'body' => $userResponse->body()
                ]);

                session()->flash('error', 'Gagal mengambil informasi pengguna.');
                return redirect()->route('login');
            }

            // Parsing data pengguna
            $userData = $userResponse->json();

            // Buat atau update user lokal
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'] ?? 'SSO User',
                    'password' => bcrypt(Str::random(16))
                ]
            );

            // Login pengguna
            Auth::login($user);

            // Simpan token ke session
            session([
                'sso_access_token' => $accessToken,
                'sso_token_type' => $responseData['token_type'] ?? 'Bearer'
            ]);

            return redirect()->route("dashboard")->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            \Log::error('SSO Authentication Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Terjadi kesalahan saat autentikasi: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }

    private function getUserFromSSO($accessToken)
    {
        try {
            $response = Http::withToken($accessToken)
                ->get(config('auth.sso.host') . '/api/user');

            \Log::info('SSO User Info Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                throw new \Exception('Gagal mengambil informasi pengguna');
            }

            $userData = $response->json();

            // Update atau buat pengguna lokal
            return User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'] ?? 'SSO User',
                    'password' => bcrypt(Str::random(16)) // Acak password
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error in getUserFromSSO', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    public function loginWithSSOKeycloak()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function handleSSOCallbackKeycloak(Request $request)
    {
        $userData = Socialite::driver('keycloak')->user();
        
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'] ?? 'SSO User',
                'password' => bcrypt(Str::random(16))
            ]
        );


        // Login pengguna
        Auth::login($user);

        // Simpan token ke session
        session([
            'sso_access_token' => $userData->token,
            'sso_token_type' => 'Bearer'
        ]);

        return redirect()->route("simulations")->with('success', 'Login berhasil!');
    }
    //END-SSOController
}
