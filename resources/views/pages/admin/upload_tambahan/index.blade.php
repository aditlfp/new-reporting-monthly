<x-app-layout title="Upload Tambahan" subtitle="Monitoring upload tambahan user isAccess">
    <div class="flex min-h-screen pb-10 admin-shell bg-slate-50">
        @include('components.sidebar-component')

        <div class="flex-1 min-w-0 p-2 sm:p-3 overflow-y-auto admin-content md:p-6">
            <div class="container px-3 py-6 mx-auto md:px-4 md:py-8 space-y-5">
                <section class="overflow-hidden bg-white border shadow-xl rounded-2xl border-slate-200 admin-panel">
                    <div class="grid gap-0 md:grid-cols-[1.4fr_1fr]">
                        <div class="px-5 py-6 border-b md:px-7 md:py-8 md:border-b-0 md:border-r border-slate-200">
                            <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase">Admin Monitoring</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Data Upload Tambahan</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Pantau seluruh data upload tambahan dari user yang memiliki akses upload.
                                Gunakan filter mitra, periode, dan pencarian nama untuk audit data.
                            </p>
                        </div>
                        <div class="grid content-between gap-4 px-5 py-6 md:px-6 md:py-8 bg-slate-50">
                            <div class="p-3 border rounded-xl border-blue-200 bg-blue-50">
                                <p class="text-xs font-semibold tracking-wide text-blue-700 uppercase">Total Data</p>
                                <p class="mt-1 text-2xl font-bold text-blue-800">{{ $uploads->total() }}</p>
                            </div>
                            <div class="p-3 border rounded-xl border-slate-200 bg-white">
                                <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Data Per Halaman</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $uploads->count() }} baris</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white shadow-xl rounded-2xl admin-panel">
                    <div class="p-4 md:p-5 border-b border-slate-200">
                        <div class="grid gap-3 md:grid-cols-5">
                            <div>
                                <label class="text-xs font-medium text-slate-600">Mitra</label>
                                <select id="mitraFilter" class="w-full rounded-md select select-bordered select-sm">
                                    <option value="">Semua Mitra</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ (string) request('mitra') === (string) $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Bulan</label>
                                <select id="monthFilter" class="w-full rounded-md select select-bordered select-sm">
                                    <option value="">Semua Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ (string) request('month') === (string) $month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(null, $month, 1)->locale('id')->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Tahun</label>
                                <select id="yearFilter" class="w-full rounded-md select select-bordered select-sm">
                                    <option value="">Semua Tahun</option>
                                    @foreach (range(now()->year - 3, now()->year + 1) as $year)
                                        <option value="{{ $year }}" {{ (string) request('year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-slate-600">Search Nama</label>
                                <input id="searchInput" class="w-full rounded-md input input-bordered input-sm"
                                    placeholder="nama_lengkap" value="{{ request('search') }}">
                            </div>
                            <div class="flex items-end">
                                <button id="applyFilter" class="inline-flex items-center justify-center w-full btn btn-sm bg-blue-600 hover:bg-blue-700 text-white border-none">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13v5a1 1 0 01-1.447.894l-2-1A1 1 0 0110 17v-4L3.2 4.6A1 1 0 013 4z"></path>
                                    </svg>
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table w-full min-w-[820px] text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-100">
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Nama User</th>
                                        <th>Jabatan</th>
                                        <th>Mitra</th>
                                        <th>Total Item</th>
                                        <th class="text-right">Detail</th>
                                    </tr>
                                </thead>
                                <tbody id="adminTableBody">
                                    @forelse ($uploads as $upload)
                                        <tr class="hover:bg-slate-50">
                                            <td class="font-semibold text-slate-700">{{ $upload->id }}</td>
                                            <td>{{ $upload->created_at?->format('d M Y H:i') }}</td>
                                            <td>{{ $upload->uploader_name }}</td>
                                            <td>{{ $upload->uploader_jabatan }}</td>
                                            <td>{{ $upload->client_name }}</td>
                                            <td>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                    {{ $upload->items_count }} item
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <button class="btn btn-sm h-8 min-h-0 px-3 bg-blue-600 hover:bg-blue-700 text-white border-none btn-detail" data-id="{{ $upload->id }}">
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
                                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada data upload tambahan.</td>
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
        </div>
    </div>

    <dialog id="detailModal" class="modal">
        <div class="w-[96%] sm:w-11/12 max-w-5xl border modal-box border-slate-200 max-h-[88vh] flex flex-col">
            <div class="flex items-start justify-between gap-4 shrink-0">
                <h3 class="text-lg font-bold text-slate-900" id="detailTitle">Detail Upload Tambahan</h3>
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
                const detailModal = document.getElementById('detailModal');
                const detailTitle = document.getElementById('detailTitle');
                const detailBody = document.getElementById('detailBody');
                const applyFilterBtn = document.getElementById('applyFilter');
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
                            const response = await fetch(`{{ url('/admin/admin-upload-tambahan') }}/${id}`, {
                                headers: {
                                    Accept: 'application/json'
                                }
                            });
                            const result = await response.json();
                            if (!response.ok || !result.status) {
                                if (window.Notify) window.Notify(result.message || 'Gagal memuat detail.', null, null, 'error');
                                return;
                            }

                            const upload = result.upload;
                            detailTitle.textContent = `Detail Upload #${upload.id}`;
                            const items = upload.items || [];
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

                applyFilterBtn.addEventListener('click', () => {
                    const params = new URLSearchParams();
                    const mitra = document.getElementById('mitraFilter').value;
                    const month = document.getElementById('monthFilter').value;
                    const year = document.getElementById('yearFilter').value;
                    const search = document.getElementById('searchInput').value.trim();
                    if (mitra) params.set('mitra', mitra);
                    if (month) params.set('month', month);
                    if (year) params.set('year', year);
                    if (search) params.set('search', search);
                    window.location.href = `{{ route('admin.upload-tambahan.index') }}?${params.toString()}`;
                });

                bindDetailButtons();
            })();
        </script>
    @endpush
</x-app-layout>
