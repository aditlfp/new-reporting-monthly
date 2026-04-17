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
- Penyempurnaan validasi backend pada flow rating (guard untuk kasus `upload_image_id` tidak ditemukan).
- Penambahan test untuk flow create/store/update/destroy pada modul rating.

---

## [v2.4.0] - 2026-04-17

### Added
- Halaman baru `resources/views/pages/admin/rating_image/index.blade.php` untuk monitoring rating gambar (summary cards, filter, sorting, tabel, pagination).
- Modal **Edit Rating**, **Delete Confirmation**, dan **Upload Image Preview** pada halaman admin rating.
- Halaman publik `resources/views/pages/user/rating_image/create.blade.php` dengan UI guest yang lebih menarik dan form rating pelanggan.
- Preview gambar Before/Progress/After pada form rating pelanggan, termasuk fallback placeholder saat gambar tidak tersedia.

### Changed
- `app/Http/Controllers/ImageRateController.php`:
  - Menambahkan `index()` dengan search/filter/sort/pagination dan summary statistik.
  - Menambahkan eager loading relasi `uploadImage` untuk kebutuhan preview di admin.
  - Menyesuaikan `update()` dan `destroy()` agar menerima ID langsung dari frontend (`findOrFail` berbasis `$id`).
  - Menambahkan lookup `UploadImage` berdasarkan pola `note` (`... - Area xxx`) dengan pencocokan strict pada bagian setelah kata `Area`.
- `resources/views/auth/login.blade.php`:
  - Menambahkan CTA ke form rating pekerjaan dan memindahkan posisinya agar lebih terlihat di atas form login.
- `resources/views/pages/admin/fotoProgres/index.blade.php`:
  - Penyempurnaan layout filter section (lebih responsif).
  - Redesign edit modal (struktur lebih rapi, sticky action bar, preview file baru, helper text).
  - Fallback placeholder image `placehold.co` untuk kasus gambar gagal dimuat.
- `routes/web.php`:
  - Resource route admin rating menggunakan `ImageRateController`.
  - Penambahan route resource user untuk `rating-pekerjaan` (`create`, `store`).

### Fixed
- Aksi hapus/edit rating dari modal admin kini mengarah ke endpoint resource yang benar (template URL route JS diperbaiki).
- Redirect setelah update/hapus rating dikembalikan ke halaman index admin rating (`admin-rating-image.index`), bukan ke root.
- Stabilitas pencarian data upload terkait rating ditingkatkan agar tidak lagi bergantung ke field `name`, tetapi ke field `note` sesuai format area.

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
