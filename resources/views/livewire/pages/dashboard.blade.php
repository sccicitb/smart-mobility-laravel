{{-- DI BACK UP KARNA BELUM KONFIRMASI --}}

<div class="container-fluid p-4">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Total Kendaraan</h6>
                            <h2 class="card-title mb-0 text-info">
                                {{ isset($stats['vehicles']) ? number_format($stats['vehicles']) : 'N/A' }}</h2>
                        </div>
                        <i data-lucide="car" class="text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Total Kemacetan</h6>
                            <h2 class="card-title mb-0 text-danger">
                                {{ isset($stats['congestion']) ? number_format($stats['congestion']) . '%' : 'N/A' }}
                            </h2>
                        </div>
                        <i data-lucide="traffic-cone" class="text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-white-50">Rata-rata Keterlambatan</h6>
                            <h2 class="card-title mb-0 text-warning">
                                {{ isset($stats['average_delay']) ? number_format($stats['average_delay']) . ' menit' : 'N/A' }}
                            </h2>
                        </div>
                        <i data-lucide="clock" class="text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Kemacetan -->
    <div class="card bg-dark text-white mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-light">Grafik Kemacetan</h5>
            <canvas id="congestionChart" style="height: 300px;"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card bg-dark text-white">
        <div class="card-body">
            <h5 class="card-title mb-3 text-light">Aktivitas Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th class="text-light">Aktivitas</th>
                            <th class="text-light">Tanggal</th>
                            <th class="text-light">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pendaftaran pengguna baru</td>
                            <td>2024-02-20</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Pembaruan data kemacetan</td>
                            <td>2024-02-19</td>
                            <td><span class="badge bg-warning">Menunggu</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('congestionChart').getContext('2d');

        // Simulasi data kemacetan yang realistis (persentase per jam)
        const congestionData = Array.from({ length: 24 }, () => Math.floor(Math.random() * (80 - 10 + 1) + 10));

        const congestionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: 24 }, (_, i) => `${i + 1}:00`),
                datasets: [{
                    label: 'Kemacetan (%)',
                    data: congestionData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>
