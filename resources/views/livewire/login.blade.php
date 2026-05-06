<div>
    <h2 class="form-heading">Selamat Datang</h2>
    <p class="form-subheading">Masuk ke akun Smart Mobility Anda</p>

    @if (session()->has('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="login">
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-input" wire:model="email"
                placeholder="nama@email.com" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-input" wire:model="password"
                placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-submit">Masuk</button>
        <div class="divider">atau</div>
        <button type="button" class="btn-sso"
            onclick="window.location='{{ route('sso.login') }}'">Login dengan SSO</button>
    </form>
</div>
