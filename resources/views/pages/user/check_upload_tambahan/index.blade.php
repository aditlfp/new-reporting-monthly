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
                                <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase">Check Upload Tambahan</p>
                                <h2 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Monitoring Upload Tim</h2>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Pantau status upload tambahan user pada cakupan mitra Anda.
                                    Gunakan filter periode untuk melihat user yang sudah atau belum upload.
                                </p>
                            </div>
                            <div class="grid content-between gap-4 px-5 py-6 md:px-6 md:py-8 bg-slate-50">
                                <div class="p-3 border rounded-xl border-blue-200 bg-blue-50">
                                    <p class="text-xs font-semibold tracking-wide text-blue-700 uppercase">Periode Aktif</p>
                                    <p id="periodInfo" class="mt-1 text-sm font-semibold text-blue-800">-</p>
                                </div>
                                <div class="p-3 border rounded-xl border-slate-200 bg-white">
                                    <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Catatan</p>
                                    <p class="mt-1 text-sm text-slate-700">Data menyesuaikan scope supervisor area/wilayah.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="grid gap-3 md:grid-cols-4">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-slate-600">Bulan</label>
                                <select id="monthFilter" class="w-full rounded-md select select-bordered select-sm">
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ $month === now()->month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(null, $month, 1)->locale('id')->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-slate-600">Tahun</label>
                                <select id="yearFilter" class="w-full rounded-md select select-bordered select-sm">
                                    @foreach (range(now()->year - 3, now()->year + 1) as $year)
                                        <option value="{{ $year }}" {{ $year === now()->year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 flex items-end">
                                <button id="applyFilter" type="button"
                                    class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-semibold text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13v5a1 1 0 01-1.447.894l-2-1A1 1 0 0110 17v-4L3.2 4.6A1 1 0 013 4z"></path>
                                    </svg>
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="p-4 md:p-5">
                            <div class="overflow-x-auto">
                                <table class="table w-full min-w-[720px] text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700">
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Mitra</th>
                                            <th>Status Upload</th>
                                            <th>Total</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summaryBody">
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-slate-500">Memuat data...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <dialog id="detailModal" class="modal">
        <div class="w-[96%] sm:w-11/12 max-w-5xl border modal-box border-slate-200 max-h-[88vh] flex flex-col">
            <div class="flex items-start justify-between gap-4 shrink-0">
                <h3 class="text-lg font-bold text-slate-900" id="detailTitle">Detail Upload</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">X</button>
                </form>
            </div>
            <div class="mt-3 overflow-auto grow">
                <table class="table w-full min-w-[680px] text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-100">
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

    @push('scripts')
        <script>
            (() => {
                const routes = {
                    summary: @json(route('api.v1.upload-tambahan.check.summary')),
                    detailBase: @json(url('/api/v1/check-upload-tambahan')),
                };

                const summaryBody = document.getElementById('summaryBody');
                const detailBody = document.getElementById('detailBody');
                const detailTitle = document.getElementById('detailTitle');
                const detailModal = document.getElementById('detailModal');
                const monthFilter = document.getElementById('monthFilter');
                const yearFilter = document.getElementById('yearFilter');
                const applyFilterBtn = document.getElementById('applyFilter');
                const periodInfo = document.getElementById('periodInfo');

                const monthNames = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
                ];
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

                function notify(message, type = 'info') {
                    if (window.Notify) {
                        window.Notify(message, null, null, type);
                    } else {
                        alert(message);
                    }
                }

                async function loadSummary() {
                    summaryBody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500">Memuat data...</td></tr>';
                    periodInfo.textContent = `${monthNames[Number(monthFilter.value) - 1]} ${yearFilter.value}`;
                    const url = `${routes.summary}?month=${monthFilter.value}&year=${yearFilter.value}`;
                    const response = await fetch(url, {
                        headers: {
                            Accept: 'application/json'
                        }
                    });
                    const result = await response.json();

                    if (!response.ok || !result.status) {
                        summaryBody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-red-500">Gagal memuat data.</td></tr>';
                        notify(result.message || 'Gagal memuat data check.', 'error');
                        return;
                    }

                    const rows = result.data || [];
                    if (!rows.length) {
                        summaryBody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500">Tidak ada data pada periode ini.</td></tr>';
                        return;
                    }

                    summaryBody.innerHTML = rows.map((row, idx) => `
                        <tr class="hover:bg-slate-50">
                            <td>${idx + 1}</td>
                            <td>${row.nama_lengkap}</td>
                            <td>${row.jabatan}</td>
                            <td>${row.mitra}</td>
                            <td>${row.uploaded ? '<span class="text-green-600 font-semibold">Sudah Upload</span>' : '<span class="text-red-600 font-semibold">Belum Upload</span>'}</td>
                            <td>${row.total_uploads}</td>
                            <td>
                                <button class="btn btn-sm h-8 min-h-0 px-3 bg-blue-600 hover:bg-blue-700 text-white border-none" data-user-id="${row.user_id}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    `).join('');

                    summaryBody.querySelectorAll('button[data-user-id]').forEach((btn) => {
                        btn.addEventListener('click', () => loadDetail(btn.dataset.userId));
                    });
                }

                async function loadDetail(userId) {
                    const url = `${routes.detailBase}/${userId}?month=${monthFilter.value}&year=${yearFilter.value}`;
                    const response = await fetch(url, {
                        headers: {
                            Accept: 'application/json'
                        }
                    });
                    const result = await response.json();

                    if (!response.ok || !result.status) {
                        notify(result.message || 'Gagal memuat detail.', 'error');
                        return;
                    }

                    detailTitle.textContent = `Detail Upload - ${result.user.nama_lengkap} (${result.month}/${result.year})`;
                    const uploads = result.uploads || [];

                    if (!uploads.length) {
                        detailBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-500">Belum ada upload.</td></tr>';
                    } else {
                        const items = uploads.flatMap((upload) => upload.items || []);
                        detailBody.innerHTML = items.length ? items.map((item, idx) => `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${item.file_name}</td>
                                <td>
                                    <span class="px-2 py-1 text-xs font-semibold uppercase rounded-full bg-slate-100 text-slate-700">
                                        ${inferExtension(item)}
                                    </span>
                                </td>
                                <td>${Math.round((item.file_size || 0) / 1024)}</td>
                                <td class="max-w-[260px] truncate" title="${item.keterangan || ''}">${item.keterangan || '-'}</td>
                                <td>
                                    <a target="_blank" class="btn btn-sm h-8 min-h-0 px-3 bg-blue-600 hover:bg-blue-700 text-white border-none" href="/storage/${item.file_path}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        `).join('') : '<tr><td colspan="6" class="py-6 text-center text-slate-500">Belum ada item.</td></tr>';
                    }

                    detailModal.showModal();
                }

                applyFilterBtn.addEventListener('click', loadSummary);
                loadSummary();
            })();
        </script>
    @endpush
</x-app-layout>
