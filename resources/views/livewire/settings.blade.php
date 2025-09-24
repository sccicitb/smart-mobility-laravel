<!DOCTYPE html>
<html lang="en">

<style>
    .nav-tabs {
        border-bottom-color: #9c2524;
    }

    .nav-tabs .nav-link {
        color: #9c2524;
    }

    .nav-tabs .nav-link.active {
        color: white;
        background-color: #9c2524;
        border-color: #9c2524;
    }

    .nav-tabs .nav-link:hover {
        border-color: #9c2524;
        background-color: rgba(219, 0, 0, 0.1);
    }

    .card-title {
        color: #9c2524;
    }

    .title {
        color: #9c2524;
    }
</style>
<div class="container">
    <h2 class="mb-4 text-center title">Pengaturan Sistem</h2>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="myTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button"
                role="tab">Pengaturan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="general-setting-tab" data-bs-toggle="tab" data-bs-target="#general-setting"
                type="button" role="tab">Pengaturan Umum</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="camera-management-tab" data-bs-toggle="tab" data-bs-target="#camera-management"
                type="button" role="tab">Pengelolaan Kamera</button>
        </li>
    </ul>
    <!-- Tab Contents -->
    <div class="mt-3" id="myTabContent">
        <div class="tab-pane fade show active" id="setting" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Halaman Pengaturan</h5>
                    <p class="card-text">Selamat datang di halaman pengaturan. Ini adalah konten dari tab
                        Pengaturan.</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="general-setting" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Halaman Pengaturan Umum</h5>
                    <p class="card-text">Ini adalah halaman pengaturan umum dengan informasi detail tentang
                        pengguna.</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade p-4 overflow-auto" id="camera-management" role="tabpanel" style="max-height: 500px;">
            {{-- @include('livewire.cameras') --}}
            <livewire:cameras />
        </div>
    </div>
</div>
