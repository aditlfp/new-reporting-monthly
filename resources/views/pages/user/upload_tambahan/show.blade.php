<x-app-layout>
    <div class="flex flex-col min-h-screen bg-slate-50">
        <x-user-navbar />
        <div class="flex flex-1">
            <x-user-sidebar />

            <main class="flex-1 min-w-0 p-2 m-1 sm:m-2 xs:p-2 sm:p-4 md:p-6">
                <div class="w-full max-w-6xl mx-auto space-y-6">
                    <section class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="grid gap-0 md:grid-cols-[1.4fr_1fr]">
                            <div class="px-5 py-6 border-b md:px-7 md:py-8 md:border-b-0 md:border-r border-slate-200">
                                <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase">Riwayat Upload Tambahan</p>
                                <h2 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Arsip Dokumen Upload</h2>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Halaman ini menampilkan semua sesi upload tambahan yang pernah Anda kirim.
                                    Klik detail untuk melihat daftar file dan keterangan tiap item.
                                </p>
                            </div>
                            <div class="grid content-between gap-4 px-5 py-6 md:px-6 md:py-8 bg-slate-50">
                                <div class="p-3 border rounded-xl border-blue-200 bg-blue-50">
                                    <p class="text-xs font-semibold tracking-wide text-blue-700 uppercase">Total Riwayat</p>
                                    <p class="mt-1 text-2xl font-bold text-blue-800">{{ $uploads->total() }}</p>
                                </div>
                                <a href="{{ route('upload-tambahan.index') }}"
                                    class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-semibold text-white transition rounded-lg bg-blue-600 hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    + Tambah Upload Baru
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="p-4 md:p-5">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                <p class="text-sm font-semibold text-slate-700">Daftar Sesi Upload</p>
                                <p class="text-xs text-slate-500">Klik <span class="font-semibold text-blue-600">Detail</span> untuk melihat item per sesi</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table w-full min-w-[560px] text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700">
                                            <th>ID</th>
                                            <th>Tanggal Upload</th>
                                            <th>Total Item</th>
                                            <th class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($uploads as $upload)
                                            <tr class="transition hover:bg-blue-50/40">
                                                <td class="font-semibold text-slate-700">{{ $upload->id }}</td>
                                                <td>
                                                    <div class="text-slate-700">{{ $upload->created_at?->format('d M Y') }}</div>
                                                    <div class="text-xs text-slate-500">{{ $upload->created_at?->format('H:i') }}</div>
                                                </td>
                                                <td>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                        {{ $upload->items_count }} item
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <button class="btn btn-sm h-8 min-h-0 px-3 bg-blue-600 hover:bg-blue-700 text-white border-none btn-detail"
                                                        data-id="{{ $upload->id }}">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-6 text-center text-slate-500">Belum ada upload tambahan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $uploads->links() }}
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>

        <dialog id="detailModal" class="modal">
            <div class="w-[96%] sm:w-11/12 max-w-5xl p-0 overflow-hidden border modal-box border-slate-200 max-h-[88vh] flex flex-col">
                <div class="flex items-start justify-between gap-4 px-5 py-4 border-b bg-slate-50 border-slate-200 shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="detailTitle">Detail Upload Tambahan</h3>
                        <p class="text-xs text-slate-500">Daftar file pada sesi upload ini</p>
                    </div>
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">X</button>
                    </form>
                </div>

                <div class="p-3 sm:p-4 overflow-auto grow">
                    <table class="table w-full min-w-[680px] text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700">
                                <th>#</th>
                                <th>Nama File</th>
                                <th>Jenis</th>
                                <th>Ukuran (KB)</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailBody"></tbody>
                    </table>
                </div>
            </div>
        </dialog>
    </div>

    @push('scripts')
        <script>
            (() => {
                const detailModal = document.getElementById('detailModal');
                const detailTitle = document.getElementById('detailTitle');
                const detailBody = document.getElementById('detailBody');

                const inferExtension = (item) => {
                    const mime = String(item?.mime_type || '').toLowerCase();
                    if (mime.includes('/')) {
                        return mime.split('/').pop() || '-';
                    }
                    const fileName = String(item?.file_name || '');
                    const filePath = String(item?.file_path || '');
                    const source = fileName || filePath;
                    if (source.includes('.')) {
                        return source.split('.').pop().toLowerCase();
                    }
                    return '-';
                };

                function bindDetailButtons() {
                    document.querySelectorAll('.btn-detail').forEach((btn) => {
                        btn.addEventListener('click', async () => {
                            const id = btn.dataset.id;
                            const response = await fetch(
                                `{{ url('upload-file-tambahan/detail/show/') }}/${id}`, {
                                    headers: {
                                        Accept: 'application/json'
                                    }
                                });
                            const result = await response.json();
                            if (!response.ok || !result.status) {
                                if (window.Notify) window.Notify(result.message ||
                                    'Gagal memuat detail.', null, null, 'error');
                                return;
                            }

                            const upload = result.upload;
                            detailTitle.textContent = `Detail Upload #${upload.id}`;
                            const items = upload.items || [];
                            detailBody.innerHTML = items.length ? items.map((item, idx) => `
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="font-medium text-slate-700">${idx + 1}</td>
                                    <td>
                                        <p class="font-medium text-slate-700">${item.file_name}</p>
                                    </td>
                                    <td>
                                        <span class="px-2 py-1 text-xs font-semibold uppercase rounded-full bg-slate-100 text-slate-700">
                                            ${inferExtension(item)}
                                        </span>
                                    </td>
                                    <td>${Math.round((item.file_size || 0) / 1024)}</td>
                                    <td class="max-w-[260px] truncate" title="${item.keterangan || ''}">${item.keterangan || '-'}</td>
                                    <td>
                                        <a href="/storage/${item.file_path}" target="_blank" class="btn btn-sm h-8 min-h-0 px-3 bg-blue-600 hover:bg-blue-700 text-white border-none">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            `).join('') : '<tr><td colspan="6" class="py-6 text-center text-slate-500">Tidak ada item.</td></tr>';

                            detailModal.showModal();
                        });
                    });
                }
                bindDetailButtons();

            })();
        </script>
    @endpush
</x-app-layout>
