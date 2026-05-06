<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArusTwoDaysSeeder extends Seeder
{
    public function run(): void
    {
        $simpangIds = DB::table('simpang')
            ->orderBy('id')
            ->limit(3)
            ->pluck('id')
            ->all();

        if (empty($simpangIds)) {
            $simpangIds = [1, 2, 3];
        }

        $routes = [
            ['tipe_pendekat' => 'Mayor', 'dari_arah' => 'Barat', 'ke_arah' => 'Timur'],
            ['tipe_pendekat' => 'Mayor', 'dari_arah' => 'Timur', 'ke_arah' => 'Barat'],
            ['tipe_pendekat' => 'Minor', 'dari_arah' => 'Utara', 'ke_arah' => 'Selatan'],
            ['tipe_pendekat' => 'Minor', 'dari_arah' => 'Selatan', 'ke_arah' => 'Utara'],
        ];

        $dayOffsets = [1, 0];
        $hourSlots = [7, 12, 17];

        foreach ($dayOffsets as $dayOffset) {
            $date = Carbon::today()->subDays($dayOffset);

            foreach ($hourSlots as $slotIndex => $hour) {
                foreach ($simpangIds as $simpangIndex => $simpangId) {
                    foreach ($routes as $routeIndex => $route) {
                        $waktu = $date->copy()->setTime($hour, ($simpangIndex * 10) + ($routeIndex * 2), 0);
                        $baseVolume = 20 + ($dayOffset * 4) + ($slotIndex * 7) + ($simpangIndex * 5);

                        DB::table('arus')->updateOrInsert(
                            [
                                'ID_Simpang' => $simpangId,
                                'tipe_pendekat' => $route['tipe_pendekat'],
                                'dari_arah' => $route['dari_arah'],
                                'ke_arah' => $route['ke_arah'],
                                'waktu' => $waktu,
                            ],
                            [
                                'SM' => $baseVolume + $routeIndex + 8,
                                'MP' => 4 + $slotIndex,
                                'AUP' => 2 + ($routeIndex % 2),
                                'TR' => 1 + ($simpangIndex % 2),
                                'BS' => 3 + $dayOffset,
                                'TS' => 1,
                                'TB' => 2 + ($slotIndex % 2),
                                'BB' => 1 + ($routeIndex % 3),
                                'GANDENG' => $routeIndex % 2,
                                'KTB' => 1 + ($slotIndex === 2 ? 1 : 0),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }
}