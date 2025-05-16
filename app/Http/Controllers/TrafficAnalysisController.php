<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class TrafficAnalysisController extends Controller
{
    public function index()
    {
        $results = DB::select("
            SELECT waktu_puncak, arah_masuk, SUM(total_IN) AS total_IN
            FROM (
                SELECT
                    CASE
                        WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 6 AND 8 THEN 'Morning'
                        WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 12 AND 13 THEN 'Day'
                        WHEN (
                            HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 16 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) >= 45
                        ) OR (
                            HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 17 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) <= 45
                        ) THEN 'Evening'
                        ELSE NULL
                    END AS waktu_puncak,
                    dari_arah AS arah_masuk,
                    CASE
                        WHEN (ID_Simpang = 5 AND dari_arah = 'north') THEN 1
                        WHEN (ID_Simpang = 2 AND dari_arah = 'east') THEN 1
                        WHEN (ID_Simpang = 4 AND dari_arah = 'east') THEN 1
                        WHEN (ID_Simpang = 3 AND dari_arah = 'west') THEN 1
                        ELSE 0
                    END AS total_IN
                FROM arus
                WHERE DATE(CONVERT_TZ(waktu, '+00:00', '+07:00')) = DATE(CONVERT_TZ(NOW() - INTERVAL 1 DAY, '+00:00', '+07:00'))
                AND (
                    (HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 6 AND 8) OR
                    (HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 12 AND 13) OR
                    (HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 16 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) >= 45) OR
                    (HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 17 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) <= 45)
                )
            ) AS sub
            WHERE waktu_puncak IS NOT NULL
            GROUP BY waktu_puncak, arah_masuk
        ");

        return response()->json($results);
    }

    public function intersection()
    {
        $results = DB::select("
        SELECT
            waktu_puncak,
            dari_arah AS arm,
            SUM(kendaraan) AS `Saturation (vehicle/hour)`,
            ROUND(SUM(kendaraan) / 6000, 3) AS `Flow Ratio`, -- contoh pembagi kapasitas
            120 AS `Cycle time(s)`, -- fixed example
            25 AS `Green Time(s)`,  -- fixed example
            750 AS `Capacity (vehicle/hour)` -- fixed example
        FROM (
            SELECT
            CASE
                WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 6 AND 8 THEN 'Morning (07.00-08.00)'
                WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 12 AND 13 THEN 'Day (12.00-13.00)'
                WHEN (
                HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 16 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) >= 45
                ) OR (
                HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) = 17 AND MINUTE(CONVERT_TZ(waktu, '+00:00', '+07:00')) <= 45
                ) THEN 'Evening (16.45-17.45)'
                ELSE NULL
            END AS waktu_puncak,
            dari_arah,
            (SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) AS kendaraan
            FROM arus
            WHERE DATE(CONVERT_TZ(waktu, '+00:00', '+07:00')) = DATE(CONVERT_TZ(NOW() - INTERVAL 1 DAY, '+00:00', '+07:00'))
        ) AS traffic
        WHERE waktu_puncak IS NOT NULL
        GROUP BY waktu_puncak, dari_arah
        ORDER BY waktu_puncak, dari_arah;
      
        ");

        return response()->json($results);
    }

    public function top5(Request $request)
    {
        $date = $request->query('date');

        $data = DB::table('arus')
            ->select('id', 'ID_Simpang', 'tipe_pendekat', 'dari_arah', 'ke_arah', 'SM', 'MP', 'AUP', 'TR', 'waktu')
            ->whereDate(DB::raw("CONVERT_TZ(waktu, '+00:00', '+07:00')"), $date)
            ->orderBy('waktu', 'asc')
            ->limit(5)
            ->get();

        return response()->json($data);
    }

    public function evaluation()
    {
        $rawData = DB::select("
            SELECT
                CASE
                    WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 7 AND 8 THEN 'Morning (07.00–08.00)'
                    WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 12 AND 13 THEN 'Day (12.00–13.00)'
                    WHEN HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) BETWEEN 16 AND 17 THEN 'Evening (16.45–17.45)'
                    ELSE NULL
                END AS time_slot,
                dari_arah AS arm,
                SUM(BS + TS + TB + BB + GANDENG + KTB) AS stopped_vehicles
            FROM arus
            WHERE waktu >= CURDATE() - INTERVAL 1 DAY
            AND HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) IN (7, 8, 12, 13, 16, 17)
            GROUP BY time_slot, arm
            HAVING time_slot IS NOT NULL
        ");

        $capacity = 750;
        $results = [];

        foreach ($rawData as $row) {
            $saturation = $row->stopped_vehicles / $capacity;
            $queueLength = $row->stopped_vehicles * 0.1;
            $delay = round($row->stopped_vehicles * 0.005, 2); // 5 detik total per kendaraan

            $los = match (true) {
                $saturation < 0.1 => 'A',
                $saturation < 0.2 => 'B',
                $saturation < 0.3 => 'C',
                $saturation < 0.4 => 'D',
                $saturation < 0.5 => 'E',
                default => 'F',
            };

            $results[] = [
                'Time' => $row->time_slot,
                'Arm' => ucfirst($row->arm),
                'Saturation Degree' => round($saturation, 3),
                'Queue Length (m)' => round($queueLength),
                'Stopped Vehicles (vehicle/hour)' => $row->stopped_vehicles,
                'Delay (s/vehicle)' => $delay,
                'LoS' => $los
            ];
        }

        return response()->json($results);
    }

    public function summary()
    {
        // 1. PEAK TRAFFIC TIME
        $peak = DB::table('arus')
            ->selectRaw("HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00')) AS hour_slot")
            ->selectRaw("SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) AS total_vehicles")
            ->where('waktu', '>=', now()->subDay())
            ->groupBy('hour_slot')
            ->orderByDesc('total_vehicles')
            ->limit(1)
            ->first();

        if ($peak) {
            $startHour = str_pad($peak->hour_slot, 2, '0', STR_PAD_LEFT) . ':00';
            $endHour = str_pad($peak->hour_slot + 1, 2, '0', STR_PAD_LEFT) . ':00';
            $peakTrafficTime = $startHour ." - ". $endHour;

            $queued = DB::table('arus')
                ->whereBetween(DB::raw("HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00'))"), [$peak->hour_slot, $peak->hour_slot])
                ->where('waktu', '>=', now()->subDay())
                ->selectRaw("SUM(BS + TS + TB + BB + GANDENG + KTB) AS queued")
                ->value('queued');
        } else {
            $peakTrafficTime = "N/A";
            $queued = 0;
        }


        // 2. CO POLLUTION
        $co = DB::table('arus')
            ->where('waktu', '>=', now()->subDay())
            ->selectRaw("ROUND(SUM(SM + MP + AUP + TR + BS + TS + TB + BB + GANDENG + KTB) * 0.02) AS co")
            ->value('co');

        // 3. LOST ESTIMATION
        $loss = DB::table('arus')
            ->where('waktu', '>=', now()->subDay())
            ->selectRaw("SUM(BS + TS + TB + BB + GANDENG + KTB) * 250 AS loss")
            ->value('loss');

        // 4. VEHICLES QUEUED pada jam puncak
        $queued = DB::table('arus')
            ->whereBetween(DB::raw("HOUR(CONVERT_TZ(waktu, '+00:00', '+07:00'))"), [$peak->hour_slot, $peak->hour_slot])
            ->where('waktu', '>=', now()->subDay())
            ->selectRaw("SUM(BS + TS + TB + BB + GANDENG + KTB) AS queued")
            ->value('queued');

        return response()->json([
            "Peak Traffic Time" => $peakTrafficTime,
            "CO Pollution" => "{$co} µg/m³",
            "Lost Estimation" => "Rp" . number_format($loss, 0, ',', '.'),
            "Vehicles Queued" => (int) $queued
        ]);
    }

}
