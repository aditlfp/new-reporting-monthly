<x-app-layout title="Data Temuan" subtitle="Daftar temuan untuk admin">
    <div class="flex min-h-screen pb-10 admin-shell bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-3 overflow-y-auto admin-content md:p-6">
            <div class="container px-3 py-6 mx-auto md:px-4 md:py-8">
                <div class="m-3 bg-white shadow-xl md:m-5 card admin-panel">
                    <div class="card-body">
                        <div class="flex flex-col gap-3 px-4 py-3 mb-4 admin-filter-card md:px-5 md:mb-6">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold md:text-2xl text-slate-900">Data Temuan</h2>
                                    <p class="text-xs text-slate-500 md:text-sm">Filter data, kelola temuan, dan hapus sesuai kebutuhan.</p>
                                </div>
                                <span id="activeFilterBadge"
                                    class="hidden px-3 py-1 text-xs font-medium text-blue-700 border border-blue-100 rounded-full w-fit bg-blue-50">
                                    Filter aktif
                                </span>
                            </div>

                            <div class="rounded-lg bg-white/45">
                                <div class="flex flex-wrap items-end gap-2 md:gap-3">
                                    <div class="w-full form-control sm:w-52 lg:w-56">
                                        <label for="findingMitraFilter" class="min-h-0 px-0 py-1 label">
                                            <span class="text-xs font-medium label-text">Mitra</span>
                                        </label>
                                        <select name="findingMitraFilter" id="findingMitraFilter"
                                            class="w-full rounded-md select select-bordered select-sm">
                                            <option selected value="">Semua Mitra</option>
                                            @foreach ($clients as $cl)
                                                <option value="{{ $cl->id }}">{{ ucwords(strtolower($cl->name)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="filterUserContainer" class="w-full form-control sm:w-52 lg:w-56">
                                        <label for="findingUserFilter" class="min-h-0 px-0 py-1 label">
                                            <span class="text-xs font-medium label-text">User</span>
                                        </label>
                                        <select name="findingUserFilter" id="findingUserFilter"
                                            class="w-full rounded-md select select-bordered select-sm" disabled>
                                            <option selected value="">Pilih Mitra Terlebih Dahulu</option>
                                        </select>
                                    </div>
                                    <div class="form-control w-[calc(50%-0.25rem)] sm:w-36">
                                        <label for="findingMonthFilter" class="min-h-0 px-0 py-1 label">
                                            <span class="text-xs font-medium label-text">Bulan</span>
                                        </label>
                                        <select id="findingMonthFilter"
                                            class="w-full rounded-md select select-bordered select-sm">
                                            <option selected value="">Semua Bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="form-control w-[calc(50%-0.25rem)] sm:w-28">
                                        <label for="findingYearFilter" class="min-h-0 px-0 py-1 label">
                                            <span class="text-xs font-medium label-text">Tahun</span>
                                        </label>
                                        <select id="findingYearFilter"
                                            class="w-full rounded-md select select-bordered select-sm">
                                            <option selected value="">Semua Tahun</option>
                                            @for ($year = now()->year; $year >= 2024; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="flex w-full gap-2 sm:w-auto">
                                        <button id="applyFindingFilter"
                                            class="flex-1 border-0 rounded-md btn btn-sm sm:flex-none bg-blue-500/20 hover:bg-blue-600 hover:text-white">
                                            <i class="text-sm ri-filter-3-line"></i>
                                            Terapkan
                                        </button>
                                        <button id="clearFindingFilter"
                                            class="flex-1 border-0 rounded-md btn btn-sm sm:flex-none bg-red-500/20 hover:bg-red-600 hover:text-white">
                                            <i class="text-sm ri-refresh-line"></i>
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p id="findingSelectionSummary" class="text-xs text-slate-500 md:text-sm">Total: {{ $findings->total() }} data temuan.</p>
                                <div class="flex flex-wrap gap-2">
                                    <button id="findingSelectAll"
                                        class="border-0 rounded-md btn btn-xs md:btn-sm bg-blue-500/20 hover:bg-blue-600 hover:text-white">
                                        <i class="ri-checkbox-multiple-line"></i>
                                        Select All
                                    </button>
                                    <button id="findingDeselectAll"
                                        class="border-0 rounded-md btn btn-xs md:btn-sm bg-red-500/20 hover:bg-red-600 hover:text-white">
                                        <i class="ri-checkbox-blank-line"></i>
                                        Deselect All
                                    </button>
                                    <button id="findingDeleteSelected"
                                        class="text-red-700 border-0 rounded-md btn btn-xs md:btn-sm bg-red-600/20 hover:bg-red-700 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                        <i class="mr-1 text-xs ri-delete-bin-6-line md:text-sm"></i><span
                                            class="hidden sm:inline">Delete Selected</span>
                                    </button>
                                </div>
                            </div>

                            <div id="findingPdfProgressContainer" class="hidden"
                                style="font-family: Arial, sans-serif; margin-top: 20px;">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 border-b-2 border-blue-500 rounded-full animate-spin"></div>
                                    <span id="findingPdfProgressMessage" class="text-sm text-slate-600">Membuat PDF...</span>
                                </div>
                                <div id="findingPdfProgressBar" class="w-full h-2 mt-3 rounded-full bg-slate-200">
                                    <div id="findingPdfProgressFill" class="h-2 transition-all duration-300 bg-blue-500 rounded-full"
                                        style="width: 0%"></div>
                                </div>
                                <div id="findingPdfProgressOverlay" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-white/80">
                                    <div class="w-full max-w-md p-6 mx-4 bg-white rounded-lg shadow-xl">
                                        <div class="text-center">
                                            <i class="text-5xl text-blue-500 ri-loader-4-line animate-spin"></i>
                                            <p id="findingPdfProgressTitle" class="mt-4 text-lg font-semibold text-slate-800">Sedang Memproses...</p>
                                            <p id="findingPdfProgressText" class="mt-2 text-sm text-slate-500">Mohon tunggu sebentar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 overflow-x-auto md:mt-6">
                                <table class="table w-full text-xs md:text-sm">
                                    <thead>
                                        <tr>
                                            <th class="p-2 text-left md:p-3">
                                                <input type="checkbox" id="findingSelectAllCheckbox" class="checkbox checkbox-xs md:checkbox-sm">
                                            </th>
                                            <th class="w-16 p-2 md:p-3">#</th>
                                            <th class="w-24 p-2 md:p-3">Gambar</th>
                                            <th class="p-2 md:p-3">Ruangan</th>
                                            <th class="p-2 md:p-3">Pengguna</th>
                                            <th class="hidden p-2 md:p-3 lg:table-cell">Keterangan</th>
                                            <th class="w-32 p-2 md:p-3">Tanggal</th>
                                            <th class="w-40 p-2 text-center md:p-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="findingTableBody">
                                        @forelse ($findings as $finding)
                                            <tr class="finding-row" data-id="{{ $finding->id }}">
                                                <td class="p-2 md:p-3">
                                                    <input type="checkbox" class="finding-checkbox checkbox checkbox-xs md:checkbox-sm" data-id="{{ $finding->id }}" data-status="{{ $finding->status ?? 'pending' }}">
                                                </td>
                                                <td class="p-2 font-mono text-xs md:p-3">{{ $finding->loop + 1 }}</td>
                                                <td class="p-2 md:p-3">
                                                    @if ($finding->image_path)
                                                        <img src="{{ asset('storage/' . $finding->image_path) }}" alt="Finding Image" class="object-cover w-16 h-16 rounded cursor-pointer finding-image-preview" data-id="{{ $finding->id }}">
                                                    @else
                                                        <div class="flex items-center justify-center w-16 h-16 bg-gray-200 rounded">
                                                            <i class="text-gray-500 ri-image-off-line"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="p-2 md:p-3">{{ $finding->ruangan }}</td>
                                                <td class="p-2 md:p-3">{{ optional($finding->user)->nama_lengkap ?? optional($finding->user)->name ?? '—' }}</td>
                                                <td class="hidden p-2 md:p-3 lg:table-cell">{{ Illuminate\Support\Str::limit($finding->note, 80) }}</td>
                                                <td class="p-2 md:p-3">{{ $finding->created_at->format('d M Y') }}</td>
                                                <td class="p-2 text-center md:p-3">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="#" class="text-yellow-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-yellow-500/20 hover:bg-yellow-600 hover:text-white finding-btn-edit" data-id="{{ $finding->id }}">
                                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM12 3.311L4.5 7.65311V16.3469L12 20.689L19.5 16.3469V7.65311L12 3.311ZM12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16ZM12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z"></path></svg>
                                                        </a>
                                                        <form action="{{ route('admin.finding.destroy', $finding) }}" method="POST" onsubmit="return confirm('Hapus temuan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-red-500/20 hover:bg-red-600 hover:text-white finding-btn-delete" data-id="{{ $finding->id }}">
                                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6ZM18 8H6V20H18V8ZM9 11H11V17H9V11ZM13 11H15V17H13V11ZM9 4V6H15V4H9Z"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="py-8 text-center">Data tidak tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div id="findingPagination" class="flex justify-center mt-4 md:mt-6">{{ $findings->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Finding page scripts
            let findingState = {
                images: [],
                selectedIds: new Set(),
                filter: {
                    mitra: '',
                    user: '',
                    month: '',
                    year: ''
                }
            };

            function initFindingPage() {
                updateSelectionSummary();
                setupFindingEventListeners();
                setupStatusChangeListeners();
                setupPaginationChange();
            }

            function updateSelectionSummary() {
                const summary = document.getElementById('findingSelectionSummary');
                const selectedCount = findingState.selectedIds.size;
                summary.textContent = `Total: {{ $findings->total() }} data temuan. ${selectedCount > 0 ? selectedCount + ' terpilih' : ''}`;
            }

            function setupFindingEventListeners() {
                document.getElementById('findingMitraFilter').addEventListener('change', function() {
                    const userFilter = document.getElementById('findingUserFilter');
                    userFilter.disabled = true;
                    userFilter.innerHTML = '<option value="">Pilih Mitra Terlebih Dahulu</option>';
                    findingState.filter.mitra = this.value;
                    
                    if (this.value !== '') {
                        userFilter.disabled = false;
                        userFilter.innerHTML = '<option value="">Pilih User</option>';
                        // TODO: Load users based on mitra
                    }
                });

                document.getElementById('findingUserFilter').addEventListener('change', function() {
                    findingState.filter.user = this.value;
                });

                document.getElementById('findingMonthFilter').addEventListener('change', function() {
                    findingState.filter.month = this.value;
                });

                document.getElementById('findingYearFilter').addEventListener('change', function() {
                    findingState.filter.year = this.value;
                });

                document.getElementById('applyFindingFilter').addEventListener('click', function() {
                    applyFindingFilter();
                });

                document.getElementById('clearFindingFilter').addEventListener('click', function() {
                    clearFindingFilter();
                });

                document.getElementById('findingSelectAll').addEventListener('click', function() {
                    toggleSelectAllFinding();
                });

                document.getElementById('findingDeselectAll').addEventListener('click', function() {
                    toggleDeselectAllFinding();
                });

                document.getElementById('findingDeleteSelected').addEventListener('click', function() {
                    deleteSelectedFindings();
                });

                document.querySelectorAll('.finding-status-select').forEach(function(select) {
                    select.addEventListener('change', function() {
                        updateFindingStatus(this.dataset.id, this.value);
                    });
                });

                document.querySelectorAll('.finding-delete-btn').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (confirm('Hapus temuan ini?')) {
                            const form = this.closest('form');
                            form.submit();
                        }
                    });
                });

                document.querySelectorAll('.finding-edit-btn').forEach(function(btn) { 
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        alert('Fitur edit akan segera hadir!');
                    });
                });

                document.querySelectorAll('#findingPagination a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const params = new URLSearchParams(url.search);
                        Object.keys(params).forEach(function(key) {
                            if (params.get(key) !== '') {
                                findingState.filter[key] = params.get(key);
                            }
                        });
                        window.location.href = this.href;
                    });
                });
            }

            function setupStatusChangeListeners() {
                document.querySelectorAll('.finding-status-select').forEach(function(select) {
                    select.addEventListener('change', function() {
                        const row = this.closest('tr');
                        const status = this.value;
                        
                        let badgeClass = 'bg-yellow-100 text-yellow-800';
                        if (status === 'process') {
                            badgeClass = 'bg-blue-100 text-blue-800';
                        } else if (status === 'done') {
                            badgeClass = 'bg-green-100 text-green-800';
                        }
                        
                        const span = row.querySelector('.finding-status-badge');
                        if (span) {
                            span.className = `finding-status-badge px-2 py-1 rounded-full text-xs font-medium ${badgeClass}`;
                            span.textContent = capitalizeFirstLetter(status);
                        }
                    });
                });
            }

            function setupPaginationChange() {
                document.querySelectorAll('#findingPagination a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const params = new URLSearchParams(url.search);
                        Object.keys(params).forEach(function(key) {
                            if (params.get(key) !== '') {
                                findingState.filter[key] = params.get(key);
                            }
                        });
                        window.location.href = this.href;
                    });
                });
            }

            function applyFindingFilter() {
                // TODO: Implement server-side filtering
                const activeFilterBadge = document.getElementById('activeFilterBadge');
                activeFilterBadge.classList.remove('hidden');
                alert('Filter akan diimplementasikan pada endpoint API');
            }

            function clearFindingFilter() {
                findingState.filter = {
                    mitra: '',
                    user: '',
                    month: '',
                    year: ''
                };
                document.getElementById('findingMitraFilter').value = '';
                document.getElementById('findingUserFilter').value = '';
                document.getElementById('findingMonthFilter').value = '';
                document.getElementById('findingYearFilter').value = '';
                document.getElementById('activeFilterBadge').classList.add('hidden');
            }

            function toggleSelectAllFinding() {
                const checkboxes = document.querySelectorAll('.finding-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
                updateSelectedFindingsState();
            }

            function toggleDeselectAllFinding() {
                document.querySelectorAll('.finding-checkbox').forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                updateSelectedFindingsState();
            }

            function updateSelectedFindingsState() {
                findingState.selectedIds.clear();
                document.querySelectorAll('.finding-checkbox:checked').forEach(function(checkbox) {
                    findingState.selectedIds.add(checkbox.dataset.id);
                });
                updateSelectionSummary();
                
                const deleteButton = document.getElementById('findingDeleteSelected');
                deleteButton.disabled = findingState.selectedIds.size === 0;
            }

            function deleteSelectedFindings() {
                if (confirm('Hapus temuan yang terpilih?')) {
                    alert('Fitur bulk delete akan segera hadir!');
                }
            }

            function updateFindingStatus(id, status) {
                // TODO: Implement status update via AJAX
                console.log('Updating finding ' + id + ' to ' + status);
                fetch(route('admin.finding.update', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert('Status berhasil diperbarui!');
                    }
                });
            }

            function capitalizeFirstLetter(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function closeImagePreview() {
                const imageModal = document.getElementById('findingImageModal');
                imageModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            initFindingPage();
        </script>
    @endpush

    @push('styles')
        <style>
            .finding-status-badge {
                transition: all 0.3s ease;
            }
            #findingPdfProgressContainer {
                border: 1px solid #e2e8f0;
                padding: 16px;
                border-radius: 8px;
            }
            #findingPdfProgressOverlay {
                backdrop-filter: blur(4px);
            }
        </style>
    @endpush
</x-app-layout>
