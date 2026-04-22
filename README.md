# SILAB Changelog

Dokumen ini berisi riwayat perubahan aplikasi SILAB dengan format versioning berbasis **Semantic Versioning (SemVer)**.

## Versioning Policy

- `MAJOR` (`X.0.0`): perubahan besar / breaking changes.
- `MINOR` (`0.X.0`): fitur baru tanpa breaking changes.
- `PATCH` (`0.0.X`): perbaikan bug, optimasi kecil, dan polish UI.

Format rilis:
- `## [vX.Y.Z] - YYYY-MM-DD`
- Gunakan kategori: `Added`, `Changed`, `Fixed`, `Removed`.

---

## [Unreleased]

### Planned
- Penyempurnaan pixel-level pada halaman admin agar lebih mendekati referensi.
- QA visual lintas breakpoint (desktop/tablet/mobile) untuk seluruh modul admin.

---

## [v2.6.0] - 2026-04-22

### Added
- Menu admin baru **Download Rekap** dengan filter `client`, `bulan`, dan `tahun`, plus card cover per client untuk generate file rekap langsung dari dashboard admin.
- Flow generate khusus Download Rekap yang memakai template cover dan surat existing (`coverPages.js` + `letterPages.js`) lalu merge dengan urutan khusus untuk kebutuhan admin.
- Validasi request baru untuk generate Download Rekap yang mendukung input `cover_id`, `month`, `year`, dan file PDF hasil render frontend.
- Feature dan unit test untuk flow Download Rekap admin serta merge rekap fallback di `CoverService`.

### Changed
- Flow Download Rekap admin kini memakai **surat terbaru** berdasarkan kombinasi `cover` + `periode` dan menandai ketersediaan surat langsung di card daftar cover.
- Render cover PDF pada Download Rekap disesuaikan ulang agar ukuran teks nama client lebih proporsional untuk nama client yang panjang tanpa merusak layout utama cover.
- Halaman konversi PDF untuk file gambar di Upload Tambahan disederhanakan agar hanya menampilkan isi gambar tanpa header tambahan.

### Fixed
- Perbaikan bug halaman cover dan surat dobel pada hasil Download Rekap akibat file lampiran/signature legacy ikut ter-merge kembali.
- Perbaikan fallback `rekap_foto` di `CoverService` saat file period-specific tidak ditemukan: sistem sekarang membuat PDF fallback dari data nyata `FixedImage` dan user cleaning service bulan berjalan.
- Perbaikan query fallback `FixedImage` agar mengikuti relasi model yang benar dan tidak lagi mengarah ke tabel jabatan yang salah.
- Perbaikan proses copy hasil fallback rekap ke path period agar error path `rekap_foto/{period}-{client}.pdf` tidak lagi muncul.
- Perbaikan komponen toast session agar aman dirender walau `$errors` belum tersedia di view tertentu.

---

## [v2.5.1] - 2026-04-21

### Added
- Menu **Pengaturan** untuk user dengan opsi sederhana: ganti tema website dan toggle splashscreen setelah login.
- Splashscreen setelah login yang hanya tampil sekali berdasarkan preferensi user.
- Pengaturan tema admin sederhana (`light` / `dark`) pada halaman admin settings.

### Changed
- Penyimpanan preferensi user dipusatkan di `user_settings.data_theme` dengan key JSON `theme_mode` dan `splash_on_login`.
- Layout aplikasi membaca tema aktif secara dinamis dari preferensi user/admin agar seluruh halaman user mengikuti theme yang tersimpan.
- Dashboard user diperbarui: chart upload per bulan mengikuti bulan Januari-Desember pada tahun berjalan secara dinamis (`now()`), dan visualisasi diubah menjadi line chart.
- Kartu limit gambar di dashboard user diperbarui agar progress bar terbalik: `0` merah dan limit penuh tetap full bar.

### Fixed
- Perbaikan theme switch yang sebelumnya tidak benar-benar mengubah tampilan halaman.
- Perbaikan dark mode admin/user pada card, tabel, panel, dan teks yang masih tertahan di warna light/slate lama.
- Perbaikan state sidebar settings pada user dan admin agar konsisten dengan route aktif.
- Perbaikan perhitungan limit dashboard agar nilai tidak turun di bawah `0`.

---

## [v2.5.0] - 2026-04-20

### Added
- Modul **Upload Tambahan** end-to-end: upload multi-item (maks 30) dengan struktur header-item, riwayat upload user, halaman check upload, dan monitoring admin.
- Pipeline chunk upload khusus upload tambahan (`init/upload/finalize/cancel`) untuk file PDF dan gambar.
- Sidebar user grouping menu `Upload Tambahan` (dropdown) dengan sub-menu `Tambah File` dan `Riwayat Upload`.
- Filter dan pencarian pada halaman monitoring admin upload tambahan (`mitra`, `bulan`, `tahun`, `nama_lengkap`).

### Changed
- Rule scope check upload tambahan untuk viewer **SPV Pusat** berbasis `jabatan.code_jabatan`:
  - `SPV` menampilkan target `LEADER CS` dan `LEADER` (global lintas mitra).
  - `SPV-W` menampilkan target `DANRU SECURITY` (global lintas mitra).
  - Matching target jabatan menggunakan exact match ignore-case.
- Halaman user/admin upload tambahan di-redesign mengikuti tema SILAB (non-SaaS) dengan perbaikan hierarchy, CTA, dan readability.
- Kolom `MIME` pada modal detail diubah menjadi `Jenis` (extension file) agar lebih mudah dipahami user non-teknis.

### Fixed
- Perbaikan bug footer/layout saat jumlah item upload bertambah (hilangkan bentrok `h-screen` vs scroll internal).
- Perbaikan responsive lintas halaman baru (mobile/tablet/desktop), termasuk tabel, filter bar, tombol aksi, dan ukuran modal.
- Perbaikan modal detail yang “mendelep” di mobile dengan pendekatan centered modal + internal scroll (`max-h`).
- Perbaikan deteksi jabatan alias `SPV` vs `Supervisor` pada akses dan scope check upload tambahan.

---

## [v2.4.1] - 2026-04-20

### Changed
- Halaman admin Photo Progress kini mempertahankan filter aktif saat edit, delete single, delete selected, dan pindah halaman pagination.
- Thumbnail gambar pada tabel Photo Progress menggunakan rasio 1:1, fixed size, lazy loading, async decoding, dan prioritas rendah untuk mengurangi lag saat tabel berisi banyak gambar.
- Marker form global di layout hanya menampilkan tanda `*` untuk field required dan tidak lagi menampilkan teks `(opsional)`.

### Fixed
- Memisahkan endpoint detail edit Photo Progress (`admin.upload.show`) dari endpoint index agar response index selalu berupa paginator.
- Memperbaiki render pagination Photo Progress agar membaca metadata paginator secara konsisten dan tetap dirender meskipun render tabel mengalami error.
- Memperbaiki marker required agar tidak dobel pada label yang sudah memiliki `*` manual dan tidak muncul pada label error tersembunyi.

---

## [v2.4.0] - 2026-04-20

### Added
- Repository dan Service layer pattern untuk domain Media (Cover, Latters, QrCode, ImageRate) dan Monitoring (Dashboard, FixedImage, HandlerCount, SendImageStatus).
- 12 repository interfaces dan implementations baru untuk abstraksi data access: CoverRepository, LattersRepository, QrCodeRepository, ImageRateRepository, MonitoringRepository, SettingsRepository, UserSettingsRepository, RoleScopeRepository, AbsensiUserRepository, dll.
- Request validation classes baru: CalendarModalShowRequest, CoverStorePdfRequest, ImageRateStoreRequest, ImageRateUpdateRequest, QrCodeStoreRequest, SettingsStoreRequest, UserSettingsStoreRequest.
- Shared services: RoleScopeService, PeriodService untuk reusable business logic.
- Session toast component untuk menampilkan flash messages dengan styling konsisten.
- Field marker pada form labels: asterisk (*) untuk required field dan "(opsional)" untuk optional field.
- Dukungan login dengan email atau username pada form login (fleksibel).
- Area field requirement pada upload form dengan validasi yang lebih ketat.
- Unit dan feature tests untuk ImageRateController, QrCodeController, SendImageStatusController, SettingsController, RoleScopeService, SettingsService.
- Rate limiting (throttle:20,1) pada endpoint rating-pekerjaan untuk mencegah abuse.

### Changed
- Refactor semua controller domain Media dan Monitoring ke pola thin controller + service pattern.
- Pemisahan tanggung jawab: controller hanya handle request/response, service handle business logic, repository handle data access.
- Perubahan struktur UploadImageService dengan metode resolveClientIdForUser untuk validasi client lebih ketat.
- CalendarService sekarang menggunakan MonitoringRepository dan RoleScopeService untuk query yang lebih efisien.
- Login request validation mengizinkan field email dan name nullable, mendukung identifier fleksibel.
- File upload validation lebih ketat: mime types spesifik (jpg, jpeg, png, webp) dan max size per file.
- Chunk upload validation dengan batasan ukuran dan tipe chunks yang lebih jelas.
- Penggabungan area dan note pada upload form dengan format yang lebih terstruktur.
- Blade layout (app.blade.php, guest.blade.php) dengan penambahan Notify.js dan auto-marker untuk label fields.
- RatingsImageController dan QrCodeController dengan error handling yang lebih robust (try-catch, QueryException, logging).
- CoverReportControllers dengan CoverService dan CoverStorageService untuk image handling terpusat.
- UserNavigateController dengan injeksi UserSettingsService dan CalendarService yang lebih clean.

### Fixed
- Bug pada LoginRequest: dulunya hanya support login dengan nama, sekarang support email juga.
- Bug pada UImageUserRequest dan UImageUserDraftRequest: area field kini diakomodasi dan divalidasi.
- Bug pada UploadImageService: resolveClientIdForUser memastikan client_id selalu valid.
- Bug pada CalendarService: query yang tadinya loose kini menggunakan repository untuk performa lebih baik.
- Bug pada form upload: validasi gambar Before dan After kini mandatory terpisah (bukan hanya "ada satu gambar").
- Bug pada FixedImageController: logic operasi create/destroy kini melalui service untuk konsistensi.
- Bug pada ImageRateController: form validation dan error handling dengan exception yang lebih spesifik.
- Bug pada QrCodeController: lifecycle create/update/delete kini melalui service untuk reusability.
- Bug pada CoverReportControllers: image upload handling terpusat di CoverStorageService.
- Bug pada UserSettingsController: implementasi update method yang sebelumnya kosong.
- Bug pada SendImageStatusController: modal detail jadi JSON yang lebih informatif.
- Konsistensi exception handling di semua controller: gunakan Throwable bukan Exception generic.

### Removed
- Inline business logic dari controller (dipindahkan ke service).
- Direct model queries dari controller (diganti dengan repository).
- Duplikasi logic di helper methods private controller (centralized ke service/shared services).
- Manual role/scope resolution di setiap controller (centralized ke RoleScopeService).

---

## [v2.3.1] - 2026-04-19

### Changed
- Merapikan layout filter halaman admin Photo Progress agar lebih compact, responsif, dan tidak memanjang penuh.
- Menyempurnakan modal edit Photo Progress dengan layout form dan preview gambar yang lebih mudah dipindai.
- Mengganti konfirmasi hapus browser default dengan modal delete custom untuk delete single dan delete selected.

### Fixed
- Memperbaiki binding pilihan mitra saat edit Photo Progress agar kompatibel dengan field `clients_id`.
- Menambahkan ringkasan jumlah data terpilih dan badge filter aktif untuk memperjelas state halaman.

---

## [v2.3.0] - 2026-04-15

### Changed
- Redesign UI/UX admin ke gaya modern-clean dengan pendekatan high-fidelity (content tetap SILAB).
- Penyelarasan visual global komponen admin: card, table, form controls, button states, info strip, dan panel.
- Penataan layout admin agar area sidebar dan content memiliki pola shell yang konsisten.

### Fixed
- Overlap dan inkonsistensi spacing pada beberapa halaman admin.
- Scroll behavior admin dipisah: sidebar scroll sendiri dan content scroll sendiri.
- Footer identity dikembalikan dan diatur agar tetap tampil rapi pada halaman admin.

---

## [v2.2.0] - 2026-04-15

### Added
- Endpoint detail fixed image menampilkan data yang lebih informatif (nama user/client, note upload, metadata gambar).

### Changed
- Modal detail pada admin check status diperbarui agar tidak hanya menampilkan ID numerik.

### Fixed
- Sanitasi output teks dinamis pada card detail untuk mencegah render HTML tidak aman.

---

## [v2.1.0] - 2026-04-15

### Changed
- Flow FixedImage diperbarui agar save/delete tidak perlu reload penuh halaman.
- Optimasi query FixedImage dengan date-range filter dan logic scope yang lebih terpusat.

### Added
- Respons API save/delete FixedImage menyertakan `counts` dan `fixed_state` untuk update UI ringan.
- Penambahan indeks performa pada tabel upload/fixed image.

### Fixed
- Konsistensi limit harian pada aksi pemilihan gambar.

---

## [v2.0.0] - 2026-04-15

### Changed
- Refactor domain Upload Image menuju pola Service + Repository.
- Pemisahan tanggung jawab controller agar lebih tipis dan terstruktur.

### Added
- Request validation terpisah untuk flow upload/chunk/admin update.
- Service storage/chunk untuk lifecycle temp upload dan file movement.
- Unit/feature tests untuk domain Upload Image (repository/service/storage/controller).

### Fixed
- Perbaikan bug chunk-temp handling dan stabilitas proses save draft/final.

---

## Notes for Next Updates

Saat menambah rilis baru:
1. Pindahkan item dari `Unreleased` ke versi baru.
2. Tambahkan section baru di atas versi terbaru.
3. Gunakan tanggal rilis aktual (`YYYY-MM-DD`).
4. Pastikan perubahan dikelompokkan sesuai kategori (`Added/Changed/Fixed/Removed`).
