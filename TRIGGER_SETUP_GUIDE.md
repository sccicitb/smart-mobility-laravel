# Trigger MySQL Setup Guide

## ✅ Setup Trigger Otomatis

Trigger akan **secara otomatis** memasukkan data dari `arus_*_detailed` ke tabel `arus` saat ada INSERT baru.

### Langkah 1: Jalankan Seeder

```bash
php artisan db:seed --class=ArusDetailedToArusTriggerSeeder
```

**Output yang diharapkan:**
```
✅ Triggers created successfully! Data from *_detailed will auto-insert to arus
```

### Langkah 2: Verifikasi Trigger Dibuat

Buka MySQL dan jalankan:
```sql
SHOW TRIGGERS;
```

Harus ada 4 trigger:
```
trg_arus_barat_detailed_insert
trg_arus_selatan_detailed_insert
trg_arus_timur_detailed_insert
trg_arus_utara_detailed_insert
```

---

## 🔄 Cara Kerja Trigger

### Contoh: Insert ke `arus_lalu_lintas_barat_detailed`

```sql
INSERT INTO arus_lalu_lintas_barat_detailed (
    ID_Simpang, Tipe_Pendekat, Arah, SM, MP, AUP, TR, BS, TS, TB, BB, Gandeng, KTB, Waktu
) VALUES (
    1, 'Terlindung', 'Lurus', 100, 200, 150, 50, 30, 20, 10, 5, 15, 5, NOW()
);
```

### Trigger akan otomatis:

1. **Detect INSERT**
2. **Map Arah → ke_arah:**
   - 'Lurus' → 'Timur'
   - 'Belok Kiri' → 'Selatan'
   - 'Belok Kanan' → 'Utara'
   - 'Belok Kiri Jalan Terus' → 'Selatan'

3. **Insert ke tabel `arus`:**
```sql
INSERT INTO arus (
    ID_Simpang, tipe_pendekat, dari_arah, ke_arah,
    SM, MP, AUP, TR, BS, TS, TB, BB, GANDENG, KTB,
    waktu, created_at, updated_at
) VALUES (
    1, 'Terlindung', 'Barat', 'Timur',
    100, 200, 150, 50, 30, 20, 10, 5, 15, 5, NOW(), NOW(), NOW()
);
```

---

## 🗺️ Direction Mapping

### Dari Barat:
- 'Lurus' → 'Timur'
- 'Belok Kiri' → 'Selatan'
- 'Belok Kanan' → 'Utara'
- 'Belok Kiri Jalan Terus' → 'Selatan'

### Dari Selatan:
- 'Lurus' → 'Utara'
- 'Belok Kiri' → 'Timur'
- 'Belok Kanan' → 'Barat'
- 'Belok Kiri Jalan Terus' → 'Timur'

### Dari Timur:
- 'Lurus' → 'Barat'
- 'Belok Kiri' → 'Utara'
- 'Belok Kanan' → 'Selatan'
- 'Belok Kiri Jalan Terus' → 'Utara'

### Dari Utara:
- 'Lurus' → 'Selatan'
- 'Belok Kiri' → 'Barat'
- 'Belok Kanan' → 'Timur'
- 'Belok Kiri Jalan Terus' → 'Barat'

---

## ✨ ON DUPLICATE KEY UPDATE

Trigger menggunakan `ON DUPLICATE KEY UPDATE` untuk:
- Mencegah duplikat data
- Update data jika sudah ada dengan waktu yang sama
- Menjaga consistency

```sql
ON DUPLICATE KEY UPDATE
    SM = NEW.SM, MP = NEW.MP, AUP = NEW.AUP, TR = NEW.TR,
    BS = NEW.BS, TS = NEW.TS, TB = NEW.TB, BB = NEW.BB,
    GANDENG = NEW.Gandeng, KTB = NEW.KTB,
    updated_at = NOW();
```

---

## 🧪 Test Trigger

### 1. Insert data test ke `arus_lalu_lintas_barat_detailed`

```sql
INSERT INTO arus_lalu_lintas_barat_detailed (
    ID_Simpang, Tipe_Pendekat, Arah, SM, MP, AUP, TR, BS, TS, TB, BB, Gandeng, KTB, Waktu
) VALUES (
    1, 'Terlindung', 'Lurus', 50, 100, 75, 25, 15, 10, 5, 2, 8, 2, NOW()
);
```

### 2. Cek data di `arus`

```sql
SELECT * FROM arus 
WHERE ID_Simpang = 1 
  AND dari_arah = 'Barat' 
  AND ke_arah = 'Timur'
ORDER BY waktu DESC 
LIMIT 1;
```

Harus keluar dengan:
- `dari_arah` = 'Barat'
- `ke_arah` = 'Timur'
- `SM` = 50, `MP` = 100, dst

✅ **Test PASSED!**

---

## 🚨 Troubleshooting

### Q: Trigger tidak berjalan
**A:** 
1. Cek trigger sudah ada: `SHOW TRIGGERS;`
2. Cek syntax error: `SHOW CREATE TRIGGER trg_arus_barat_detailed_insert;`
3. Cek MySQL logs: `/var/log/mysql/error.log`

### Q: Error "Duplicate key entry"
**A:** Hapus trigger dan jalankan ulang seeder:
```bash
php artisan db:seed --class=ArusDetailedToArusTriggerSeeder
```

### Q: Data tidak ter-insert ke `arus`
**A:** 
1. Pastikan tabel `arus` sudah ada
2. Cek unique key constraint di tabel `arus`
3. Check logs untuk error detail

---

## 📊 Monitoring

Monitor real-time insert:

```sql
-- Count data di arus
SELECT COUNT(*) as total_arus FROM arus;

-- Count data di detailed
SELECT 
    'Barat' as arah, COUNT(*) as total 
FROM arus_lalu_lintas_barat_detailed
UNION ALL
SELECT 'Selatan', COUNT(*) FROM arus_lalu_lintas_selatan_detailed
UNION ALL
SELECT 'Timur', COUNT(*) FROM arus_lalu_lintas_timur_detailed
UNION ALL
SELECT 'Utara', COUNT(*) FROM arus_lalu_lintas_utara_detailed;

-- Compare (arus harus LEBIH BANYAK atau SAMA)
SELECT 
    (SELECT COUNT(*) FROM arus) as arus_total,
    (SELECT COUNT(*) FROM arus_lalu_lintas_barat_detailed 
     + SELECT COUNT(*) FROM arus_lalu_lintas_selatan_detailed
     + SELECT COUNT(*) FROM arus_lalu_lintas_timur_detailed
     + SELECT COUNT(*) FROM arus_lalu_lintas_utara_detailed) as detailed_total;
```

---

## 💡 Tips

✅ **Best Practice:**
- Trigger berjalan real-time, sangat efisien
- Data otomatis ter-sync ke `arus`
- Dashboard langsung bisa menampilkan data

⚠️ **Note:**
- Trigger hanya berlaku untuk data BARU
- Untuk data lama, gunakan command: `php artisan transform:arus-detailed-to-arus --days=30`
- Jangan jalankan keduanya bersamaan (risiko duplikat)
