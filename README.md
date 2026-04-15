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
