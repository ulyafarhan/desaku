# Deployment Log - Database Normalization & Query Optimizations

## Overview
Migrated primary keys to ULIDs, added database indexes, and solved N+1 query problems using eager loading and event muting.

## Steps
1. Updated migration files to define ULIDs instead of auto-incrementing integers for `pengajuan_surat`, `tracking_pengajuan_surat`, and `mutasi_penduduk`.
2. Updated Eloquent models (`PengajuanSurat`, `TrackingPengajuanSurat`, `MutasiPenduduk`, `Penduduk`) to import `HasUlids` and enforce global eager loading.
3. Updated Filament resources (`PendudukResource`, `KeluargaResource`, `MutasiPendudukResource`) to eager load relationship queries.
4. Muted redundant events using `withoutEvents` inside `PengajuanSuratResource`.
5. **NOTE**: The system schema changes require database re-seeding. Run `php artisan migrate:fresh --seed` to generate the new ULID key fields and constraints.
