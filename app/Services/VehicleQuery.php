<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class VehicleQuery
{
    public static function getVehicleData($startDate, $endDate = null)
    {
        $vehicleTypes = ['SM','MP','AUP','TR','BS','TS','TB','BB','GANDENG','KTB'];

        // Rules kendaraan masuk
        $masukRules = [
            "ID_Simpang = 2 AND dari_arah = 'east' AND ke_arah IN ('south','west')",
            "ID_Simpang = 2 AND dari_arah IN ('south','north') AND ke_arah = 'west'",
            "ID_Simpang = 4 AND dari_arah = 'east' AND ke_arah = 'west'",
            "ID_Simpang = 3 AND dari_arah = 'south' AND ke_arah = 'east'",
            "ID_Simpang = 3 AND dari_arah = 'west' AND ke_arah = 'east'",
            "ID_Simpang = 3 AND dari_arah = 'north' AND ke_arah = 'south'",
            "ID_Simpang = 5 AND dari_arah IN ('east','west') AND ke_arah = 'south'"
        ];

        // Rules kendaraan keluar
        $keluarRules = [
            "ID_Simpang = 2 AND dari_arah IN ('south','west','north') AND ke_arah = 'east'",
            "ID_Simpang = 4 AND dari_arah = 'west' AND ke_arah = 'east'",
            "ID_Simpang = 3 AND dari_arah = 'east' AND ke_arah = 'west'",
            "ID_Simpang = 3 AND dari_arah = 'south' AND ke_arah = 'west'",
            "ID_Simpang = 5 AND dari_arah IN ('east','south','west') AND ke_arah = 'north'"
        ];

        $selects = [];
        foreach ($vehicleTypes as $type) {
            $selects[] = "SUM(CASE WHEN (" . implode(' OR ', $masukRules) . ") THEN $type ELSE 0 END) AS {$type}_masuk";
            $selects[] = "SUM(CASE WHEN (" . implode(' OR ', $keluarRules) . ") THEN $type ELSE 0 END) AS {$type}_keluar";
        }

        $query = DB::table('arus')
            ->selectRaw(implode(", ", $selects))
            ->whereIn('ID_Simpang', [2,3,4,5]);

        if ($endDate) {
            $query->whereBetween('waktu', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } else {
            $query->whereDate('waktu', $startDate);
        }

        return $query->first();
    }
}
