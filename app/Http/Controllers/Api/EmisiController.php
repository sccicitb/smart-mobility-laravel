<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmisiController extends Controller
{
    public function getByDate(Request $request)
    {
        $tanggal = $request->input('tanggal'); // format 'YYYY-MM-DD'

        if (!$tanggal) {
            return response()->json(['error' => 'Tanggal wajib diisi'], 400);
        }

        $result = DB::table('arus as a')
            ->leftJoin('jarak_simpang as j', function ($join) {
                $join->on('a.ID_Simpang', '=', 'j.ID_Simpang')
                     ->on('a.dari_arah', '=', 'j.dari_arah')
                     ->on('a.ke_arah', '=', 'j.ke_arah')
                     ->where('j.status', '=', 'aktif');
            })
            ->selectRaw("
                DATE(a.waktu) as tanggal,
                COUNT(DISTINCT a.ID_Simpang) as jumlah_simpang,
                COUNT(*) as total_record,
                SUM(
                    (a.SM * 80 * j.jarak_km) + 
                    (a.MP * 180 * j.jarak_km) + 
                    (a.AUP * 350 * j.jarak_km) + 
                    (a.TR * 250 * j.jarak_km) + 
                    (a.BS * 800 * j.jarak_km) + 
                    (a.TS * 400 * j.jarak_km) + 
                    (a.TB * 600 * j.jarak_km) + 
                    (a.BB * 1200 * j.jarak_km) + 
                    (a.GANDENG * 900 * j.jarak_km) + 
                    (a.KTB * 0 * j.jarak_km)
                ) / 1000 AS total_emisi_co2_kg_hari_ini
            ")
            ->whereDate('a.waktu', $tanggal)
            ->whereNotNull('j.jarak_km')
            ->groupBy(DB::raw('DATE(a.waktu)'))
            ->get();

        return response()->json($result);
    }
}
