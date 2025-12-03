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
        // Drop existing triggers if any
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_barat_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_selatan_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_timur_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_utara_detailed_insert');

        // Trigger untuk arus_lalu_lintas_barat_detailed
        DB::statement("
            CREATE TRIGGER trg_arus_barat_detailed_insert
            AFTER INSERT ON arus_lalu_lintas_barat_detailed
            FOR EACH ROW
            BEGIN
                INSERT INTO arus (
                    ID_Simpang, tipe_pendekat, dari_arah, ke_arah,
                    SM, MP, AUP, TR, BS, TS, TB, BB, GANDENG, KTB,
                    waktu, created_at, updated_at
                )
                VALUES (
                    NEW.ID_Simpang,
                    NEW.Tipe_Pendekat,
                    'Barat',
                    CASE 
                        WHEN NEW.Arah = 'Lurus' THEN 'Timur'
                        WHEN NEW.Arah = 'Belok Kiri' THEN 'Selatan'
                        WHEN NEW.Arah = 'Belok Kanan' THEN 'Utara'
                        WHEN NEW.Arah = 'Belok Kiri Jalan Terus' THEN 'Selatan'
                        ELSE 'Timur'
                    END,
                    NEW.SM, NEW.MP, NEW.AUP, NEW.TR, NEW.BS, NEW.TS, NEW.TB, NEW.BB, NEW.Gandeng, NEW.KTB,
                    NEW.Waktu, NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    SM = NEW.SM, MP = NEW.MP, AUP = NEW.AUP, TR = NEW.TR,
                    BS = NEW.BS, TS = NEW.TS, TB = NEW.TB, BB = NEW.BB,
                    GANDENG = NEW.Gandeng, KTB = NEW.KTB,
                    updated_at = NOW();
            END
        ");

        // Trigger untuk arus_lalu_lintas_selatan_detailed
        DB::statement("
            CREATE TRIGGER trg_arus_selatan_detailed_insert
            AFTER INSERT ON arus_lalu_lintas_selatan_detailed
            FOR EACH ROW
            BEGIN
                INSERT INTO arus (
                    ID_Simpang, tipe_pendekat, dari_arah, ke_arah,
                    SM, MP, AUP, TR, BS, TS, TB, BB, GANDENG, KTB,
                    waktu, created_at, updated_at
                )
                VALUES (
                    NEW.ID_Simpang,
                    NEW.Tipe_Pendekat,
                    'Selatan',
                    CASE 
                        WHEN NEW.Arah = 'Lurus' THEN 'Utara'
                        WHEN NEW.Arah = 'Belok Kiri' THEN 'Timur'
                        WHEN NEW.Arah = 'Belok Kanan' THEN 'Barat'
                        WHEN NEW.Arah = 'Belok Kiri Jalan Terus' THEN 'Timur'
                        ELSE 'Utara'
                    END,
                    NEW.SM, NEW.MP, NEW.AUP, NEW.TR, NEW.BS, NEW.TS, NEW.TB, NEW.BB, NEW.Gandeng, NEW.KTB,
                    NEW.Waktu, NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    SM = NEW.SM, MP = NEW.MP, AUP = NEW.AUP, TR = NEW.TR,
                    BS = NEW.BS, TS = NEW.TS, TB = NEW.TB, BB = NEW.BB,
                    GANDENG = NEW.Gandeng, KTB = NEW.KTB,
                    updated_at = NOW();
            END
        ");

        // Trigger untuk arus_lalu_lintas_timur_detailed
        DB::statement("
            CREATE TRIGGER trg_arus_timur_detailed_insert
            AFTER INSERT ON arus_lalu_lintas_timur_detailed
            FOR EACH ROW
            BEGIN
                INSERT INTO arus (
                    ID_Simpang, tipe_pendekat, dari_arah, ke_arah,
                    SM, MP, AUP, TR, BS, TS, TB, BB, GANDENG, KTB,
                    waktu, created_at, updated_at
                )
                VALUES (
                    NEW.ID_Simpang,
                    NEW.Tipe_Pendekat,
                    'Timur',
                    CASE 
                        WHEN NEW.Arah = 'Lurus' THEN 'Barat'
                        WHEN NEW.Arah = 'Belok Kiri' THEN 'Utara'
                        WHEN NEW.Arah = 'Belok Kanan' THEN 'Selatan'
                        WHEN NEW.Arah = 'Belok Kiri Jalan Terus' THEN 'Utara'
                        ELSE 'Barat'
                    END,
                    NEW.SM, NEW.MP, NEW.AUP, NEW.TR, NEW.BS, NEW.TS, NEW.TB, NEW.BB, NEW.Gandeng, NEW.KTB,
                    NEW.Waktu, NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    SM = NEW.SM, MP = NEW.MP, AUP = NEW.AUP, TR = NEW.TR,
                    BS = NEW.BS, TS = NEW.TS, TB = NEW.TB, BB = NEW.BB,
                    GANDENG = NEW.Gandeng, KTB = NEW.KTB,
                    updated_at = NOW();
            END
        ");

        // Trigger untuk arus_lalu_lintas_utara_detailed
        DB::statement("
            CREATE TRIGGER trg_arus_utara_detailed_insert
            AFTER INSERT ON arus_lalu_lintas_utara_detailed
            FOR EACH ROW
            BEGIN
                INSERT INTO arus (
                    ID_Simpang, tipe_pendekat, dari_arah, ke_arah,
                    SM, MP, AUP, TR, BS, TS, TB, BB, GANDENG, KTB,
                    waktu, created_at, updated_at
                )
                VALUES (
                    NEW.ID_Simpang,
                    NEW.Tipe_Pendekat,
                    'Utara',
                    CASE 
                        WHEN NEW.Arah = 'Lurus' THEN 'Selatan'
                        WHEN NEW.Arah = 'Belok Kiri' THEN 'Barat'
                        WHEN NEW.Arah = 'Belok Kanan' THEN 'Timur'
                        WHEN NEW.Arah = 'Belok Kiri Jalan Terus' THEN 'Barat'
                        ELSE 'Selatan'
                    END,
                    NEW.SM, NEW.MP, NEW.AUP, NEW.TR, NEW.BS, NEW.TS, NEW.TB, NEW.BB, NEW.Gandeng, NEW.KTB,
                    NEW.Waktu, NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    SM = NEW.SM, MP = NEW.MP, AUP = NEW.AUP, TR = NEW.TR,
                    BS = NEW.BS, TS = NEW.TS, TB = NEW.TB, BB = NEW.BB,
                    GANDENG = NEW.Gandeng, KTB = NEW.KTB,
                    updated_at = NOW();
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_barat_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_selatan_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_timur_detailed_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_arus_utara_detailed_insert');
    }
};
