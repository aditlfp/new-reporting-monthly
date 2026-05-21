# SILAB Changelog

Dokumen ini berisi riwayat perubahan aplikasi SILAB dengan format versioning berbasis **Semantic Versioning (SemVer)**.

Current stable version: **v2.6.5** (2026-05-21)

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

## [v2.6.5] - 2026-05-21

### Added
- Desain ulang halaman admin **Finding (Temuan)** dengan filter Mitra, User, Bulan, dan Tahun.
- Kontrol seleksi bulk (Select All, Deselect All, Delete Selected) untuk data temuan.
- Tabel Finding dengan kolom checkbox, ID, Ruangan, Pengguna, Keterangan, Tanggal, Status, dan Aksi.
- Dropdown status (Pending/Process/Done) yang dapat diupdate langsung.
- Tombol Edit dan Hapus dengan desain modern dan responsif.
- Fitur generate PDF untuk data temuan dengan progress indicator.
- Indikator jumlah data terpilih dan summary total data pada halaman admin Finding.
- Pagination untuk tabel Finding dengan filter terintegrasi.
- Validasi filter client dan user relationship di FindingController.

### Changed
- Update UI admin Finding untuk konsisten dengan desain admin Photo Progress dan general design system.
- Menambahkan $clients variable di FindingController untuk dropdown filter mitra.
- Optimasi tampilan tanggal Finding menggunakan format Indonesia (`d M Y`).
- Menambahkan status select field untuk setiap baris Finding.
- Memperbarui tombol Edit/Hapus dengan ukuran yang lebih konsisten (btn-xs md:btn-sm).

### Fixed
- Perbaikan syntax error pada Blade template Finding (double backslash pada Illuminate\Support\Str::limit).
- Perbaikan passing $clients variable ke view admin Finding.index.

---

## [v2.6.4] - 2026-04-29

### Added
- Fitur penilaian foto fixed dengan endpoint baru `fixed.rate` untuk menyimpan nilai `kurang/cukup/baik`, alasan, penilai, dan waktu penilaian.
- Tombol **Nilai Foto Ini** pada modal `/set-image/fixed` untuk mengganti konten modal ke form penilaian langsung.
- Tabel baru `upload_image_ratings` untuk menyimpan rating foto meskipun foto belum dipilih sebagai fixed image.
- Tombol **Detail Penilaian** pada halaman admin Photo Progress untuk foto yang sudah memiliki rating.
- Modal detail penilaian dengan ringkasan nilai, alasan, penilai, dan waktu penilaian.

### Changed
- Payload detail fixed image diperluas agar mengirim data rating (`rating_value`, `rating_reason`, `rated_by`, `rated_at`) ke frontend.
- Aturan otorisasi penilaian diperjelas: hanya evaluator yang berhak memberi nilai, dan edit nilai dibatasi untuk penilai awal atau admin.
- UI form penilaian di modal diperbarui agar lebih modern, responsif, dan tetap konsisten di desktop/tablet/mobile.
- Komponen pilihan rating diubah ke custom dropdown agar tidak memakai tema select bawaan OS.
- Tampilan admin Photo Progress sekarang menampilkan data foto secara menyeluruh meskipun belum dipilih oleh leader/danru.

### Fixed
- Perbaikan alur simpan penilaian agar tidak lagi bersifat sementara; data rating tetap tersimpan walau foto belum dipilih.
- Perbaikan tombol detail penilaian yang sebelumnya belum aktif pada data tertentu.
- Perbaikan state tombol detail agar enabled saat data benar-benar sudah memiliki rating.
- Perbaikan format waktu detail penilaian menjadi `HH:mm` (contoh `12:00`) tanpa detik dan dipaksa memakai separator titik dua (`:`).
- Perbaikan update surat agar field `signature` tidak lagi tersimpan sebagai path file temporary (`php*.tmp`), tetapi disimpan sebagai file final melalui flow storage service.

---

## [v2.6.3] - 2026-04-27

### Changed
- Dashboard user dioptimasi: data chart bulanan sekarang dikirim langsung bersama request halaman utama, tanpa AJAX terpisah saat halaman dibuka.
- Halaman user **Set Fixed** dioptimasi: request count ganda saat filter dihilangkan, lazy-load image memakai `IntersectionObserver` yang direuse, dan render gallery diubah menjadi single HTML inject agar update DOM lebih ringan.
- UI halaman user **Set Fixed** dipoles ulang: pagination dibuat lebih compact, layout filter dirapikan untuk desktop/mobile, tab kategori disederhanakan menjadi `Before`, `Proses`, dan `After`, serta card gallery diperjelas hierarchy visualnya.
- Modal preview **Set Fixed** diperbarui dengan layout info yang lebih ringkas, placeholder gambar kosong berbasis `placehold.co`, dan area preview yang lebih konsisten antar tab gambar.

### Removed
- Query dashboard user yang tidak terpakai (`uploadDraft` dan `allImages`) dari flow render halaman utama.
- Tab filter `All` pada halaman user **Set Fixed** untuk menyederhanakan alur pemilihan kategori.

### Fixed
- Perbaikan interaksi tab filter halaman user **Set Fixed** setelah penyesuaian layout mobile agar event klik tetap terbaca dengan benar.
- Perbaikan konsistensi ukuran frame preview gambar pada modal **Set Fixed** untuk state `Before`, `Proses`, `After`, termasuk fallback saat file gambar kosong.

---

## [v2.6.2] - 2026-04-23

### Added
- Field opsional **Kegiatan** pada form admin QR Code yang menambahkan parameter `keg` ke link redirect QR.
- Autocomplete custom untuk field Kegiatan QR Code dengan daftar kegiatan default dan referensi kegiatan yang pernah tersimpan di database.
- Kolom **Link Redirect** pada tabel QR Code untuk melihat dan membuka URL tujuan QR secara langsung.
- Autofill field **Keterangan Kegiatan** pada form upload gambar user dari parameter URL `keg`.

### Changed
- Penyimpanan data QR tetap memakai kolom `data`, dengan format gabungan `data-kegiatan` saat kegiatan diisi.
- Daftar default kegiatan QR difokuskan ke nama kegiatan seperti `Progres glass cleaning`, `Progres general cleaning`, dan `Progres pembasmian gulma`.
- Halaman admin Photo Progress dioptimasi: payload query diperkecil, relasi dibatasi ke kolom yang dibutuhkan, dan thumbnail tabel dimuat on-demand dengan `IntersectionObserver`.
- Default Photo Progress sekarang menampilkan data yang dibuat hari ini, diurutkan berdasarkan `clients_id`; saat filter aktif, jumlah data per …2359 tokens truncated…d validation lebih ketat: mime types spesifik (jpg, jpeg, png, webp) dan max size per file.
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
