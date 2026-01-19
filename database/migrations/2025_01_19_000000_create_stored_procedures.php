<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create get_kendaraan_statistik procedure
        DB::unprepared('
            CREATE PROCEDURE get_kendaraan_statistik(IN periode VARCHAR(50))
            BEGIN
                SELECT 
                    jenis,
                    SUM(masuk) as total_masuk,
                    SUM(keluar) as total_keluar
                FROM traffic_flows
                WHERE 
                    CASE 
                        WHEN periode = "hari" THEN DATE(created_at) = CURDATE()
                        WHEN periode = "minggu" THEN WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                        WHEN periode = "bulan" THEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                        WHEN periode = "tahun" THEN YEAR(created_at) = YEAR(CURDATE())
                        ELSE TRUE
                    END
                GROUP BY jenis
                ORDER BY jenis;
            END
        ');

        // Create get_kendaraan_breakdown procedure
        DB::unprepared('
            CREATE PROCEDURE get_kendaraan_breakdown(IN periode VARCHAR(50), IN arah_id INT)
            BEGIN
                SELECT 
                    jenis_kendaraan,
                    COUNT(*) as total_kendaraan
                FROM traffic_flows
                WHERE 
                    direction_id = arah_id AND
                    CASE 
                        WHEN periode = "hari" THEN DATE(created_at) = CURDATE()
                        WHEN periode = "minggu" THEN WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                        WHEN periode = "bulan" THEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
                        WHEN periode = "tahun" THEN YEAR(created_at) = YEAR(CURDATE())
                        ELSE TRUE
                    END
                GROUP BY jenis_kendaraan
                ORDER BY jenis_kendaraan;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_kendaraan_statistik');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_kendaraan_breakdown');
    }
};
