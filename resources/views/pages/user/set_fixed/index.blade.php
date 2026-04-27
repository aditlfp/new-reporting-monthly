<x-app-layout>
    @push('styles')
        /* Remove default select arrow and add custom styling */
        select.select {
        padding-left: 2.5rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
        stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7
        7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
        }

        /* Hover effects */
        .select:hover {
        border-color: hsl(var(--p));
        }

        .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Transition effects */
        .select, .btn {
        transition: all 0.2s ease-in-out;
        }
    @endpush

    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />
        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />
            <!-- Main Content -->
            <main class="flex-1 p-1 m-2 overflow-y-auto xs:p-2 sm:p-4 md:p-6">
                <div class="w-full max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6 md:mb-8">
                        <h2 class="mb-1 text-xl font-bold sm:text-2xl text-slate-900">Data Semua Foto</h2>
                        <p class="text-sm sm:text-base text-slate-500">Data Sesuai Dengan Mitra Yang Anda Tempati</p>
                    </div>

                    <!-- Client Info Card -->
                    <div class="mb-6 overflow-hidden border shadow-lg bg-white/95 rounded-2xl border-slate-200"
                        id="clientInfoCard" style="display: none;">
                        <!-- Accent Bar -->
                        <div class="h-1.5 bg-gradient-to-r from-blue-500 via-cyan-500 to-indigo-500"></div>

                        <div class="p-4 sm:p-6">
                            <!-- Header -->
                            <div class="flex flex-col gap-4 mb-4 xl:flex-row xl:items-start xl:justify-between">
                                <div class="min-w-0">
                                    <h3 class="flex items-center gap-2 text-lg font-bold break-words text-slate-900"
                                        id="clientName">
                                        <div class="p-1.5 bg-blue-100 rounded-xl shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        -
                                    </h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500" id="clientDetails">-</p>
                                </div>

                                <!-- Stats -->
                                <div class="grid w-full grid-cols-3 gap-2 xl:max-w-md">
                                    <div
                                        class="p-3 text-center border shadow-sm bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl border-slate-200">
                                        <div class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-500">Total
                                        </div>
                                        <div class="mt-1 text-xl font-bold sm:text-2xl text-slate-800" id="totalImages">0</div>
                                    </div>

                                    <div
                                        class="p-3 text-center border border-blue-200 shadow-sm bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl">
                                        <div class="text-[11px] font-semibold tracking-[0.18em] text-blue-600 uppercase">Dipilih
                                        </div>
                                        <div class="mt-1 text-xl font-bold text-blue-600 sm:text-2xl" id="totalHasFix">0</div>
                                    </div>

                                    <div
                                        class="p-3 text-center border shadow-sm border-violet-200 bg-gradient-to-br from-violet-50 to-fuchsia-50 rounded-2xl">
                                        <div class="text-[11px] font-semibold tracking-[0.18em] text-violet-600 uppercase">Maks
                                        </div>
                                        <div class="mt-1 text-xl font-bold sm:text-2xl text-violet-600">11</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Note -->
                            <div
                                class="flex items-start gap-2 p-3 border rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200">
                                <div class="p-1 rounded-full bg-amber-100 shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-medium text-amber-700">Termasuk foto Before, Progress, dan After
                                </p>
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->canAccess())
                        <div class="mb-6">
                            <form class="w-full">
                                <div class="p-3 bg-white border shadow-sm rounded-2xl border-slate-200 sm:p-4">
                                    <div class="items-center justify-between hidden gap-3 pb-3 border-b border-slate-100 sm:flex">
                                        <div>
                                            <h3 class="text-sm font-semibold text-slate-800">Filter Data</h3>
                                            <p class="text-xs text-slate-500">Pilih mitra dan periode untuk menampilkan foto.</p>
                                        </div>
                                        <div
                                            class="items-center justify-center hidden w-10 h-10 text-blue-600 rounded-xl bg-blue-50 sm:flex">
                                            <i class="text-lg ri-filter-3-line"></i>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 sm:mt-3 sm:grid-cols-12 sm:items-end">
                                            <div class="col-span-2 form-control sm:col-span-12 xl:col-span-6">
                                                <label for="client_id" class="mb-1.5">
                                                    <span class="text-xs font-semibold tracking-wide text-slate-600 required">Mitra</span>
                                                </label>
                                                <div class="relative">
                                                    <select name="client_id"
                                                        class="w-full rounded-xl border-slate-200 bg-slate-50/80 select select-bordered clientId focus:outline-none focus:ring-2 focus:ring-primary">
                                                        <option value="">Pilih mitra</option>
                                                        @forelse($clients as $client)
                                                            <option value="{{ $client->id }}">
                                                                {{ ucwords(strtolower($client->name)) }}</option>
                                                        @empty
                                                            <option value="">Mitra Kosong</option>
                                                        @endforelse
                                                    </select>
                                                    <i
                                                        class="absolute -translate-y-1/2 pointer-events-none ri-building-line left-3 top-1/2 text-base-content/50"></i>
                                                </div>
                                            </div>

                                            <div class="col-span-1 form-control sm:col-span-6 xl:col-span-3">
                                                <label for="month" class="mb-1.5">
                                                    <span class="text-xs font-semibold tracking-wide text-slate-600">Bulan <span
                                                            class="text-error">*</span></span>
                                                </label>
                                                <div class="relative">
                                                    <select name="month"
                                                        class="w-full rounded-xl border-slate-200 bg-slate-50/80 month select select-bordered focus:outline-none focus:ring-2 focus:ring-primary">
                                                        <option value="">Pilih Bulan</option>
                                                        @foreach (range(1, 12) as $month)
                                                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                                {{ \Carbon\Carbon::create(null, $month, 1)->locale('id')->translatedFormat('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i
                                                        class="absolute -translate-y-1/2 pointer-events-none ri-calendar-line left-3 top-1/2 text-base-content/50"></i>
                                                </div>
                                            </div>

                                            <div class="col-span-1 form-control sm:col-span-6 xl:col-span-3">
                                                <label for="year" class="mb-1.5">
                                                    <span class="text-xs font-semibold tracking-wide text-slate-600">Tahun <span
                                                            class="text-error">*</span></span>
                                                </label>
                                                <div class="relative">
                                                    <select name="year"
                                                        class="w-full rounded-xl border-slate-200 bg-slate-50/80 year select select-bordered focus:outline-none focus:ring-2 focus:ring-primary">
                                                        @php
                                                            $currentYear = now()->year;
                                                        @endphp
                                                        @foreach (range($currentYear - 5, $currentYear + 5) as $year)
                                                            <option value="{{ $year }}"
                                                                {{ $year == $currentYear ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i
                                                        class="absolute -translate-y-1/2 pointer-events-none ri-calendar-2-line left-3 top-1/2 text-base-content/50"></i>
                                                </div>
                                            </div>

                                            <div class="flex justify-end col-span-2 pt-1 sm:col-span-12 sm:pt-0 xl:col-span-3 xl:justify-stretch">
                                                <button type="button"
                                                    class="w-full gap-2 text-white bg-blue-600 border-0 shadow-sm rounded-xl btn hover:bg-blue-700 clientFilter sm:w-auto sm:min-w-36 xl:w-full">
                                                    <i class="text-lg ri-filter-3-line"></i>
                                                    Tampilkan
                                                </button>
                                            </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Filter Tabs -->
                    <div class="mb-6" id="filterTabs" style="display: none;">
                        <div role="tablist"
                            class="grid grid-cols-3 gap-2 p-2 border border-dashed tabs rounded-2xl border-base-300 bg-base-200/80">
                            <a role="tab" class="min-h-0 px-2 text-xs font-semibold border border-transparent tab tab-active h-11 rounded-xl sm:h-12 sm:text-sm" data-filter="before">
                                <i class="text-base sm:mr-2 ri-image-line"></i>
                                <span>Before</span>
                            </a>
                            <a role="tab" class="min-h-0 px-2 text-xs font-semibold border border-transparent tab h-11 rounded-xl sm:h-12 sm:text-sm" data-filter="proccess">
                                <i class="text-base sm:mr-2 ri-settings-3-line"></i>
                                <span>Proses</span>
                            </a>
                            <a role="tab" class="min-h-0 px-2 text-xs font-semibold border border-transparent tab h-11 rounded-xl sm:h-12 sm:text-sm" data-filter="final">
                                <i class="text-base sm:mr-2 ri-checkbox-circle-line"></i>
                                <span>After</span>
                            </a>
                        </div>
                    </div>

                    <!-- Loading Skeleton -->
                    <div id="loadingSkeleton"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4">
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="flex flex-col items-center justify-center py-12 text-center"
                        style="display: none;">
                        <i class="mb-4 text-6xl ri-image-line sm:text-7xl text-slate-300"></i>
                        <h3 class="mb-2 text-lg font-semibold sm:text-xl text-slate-700">Tidak Ada Foto</h3>
                        <p class="text-sm sm:text-base text-slate-500">Belum ada foto yang tersedia untuk mitra ini</p>
                    </div>

                    <!-- Image Gallery Grid -->
                    <div id="imageGallery"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4"
                        style="display: none;">
                        <!-- Images will be loaded here -->
                    </div>
                </div>
            </main>
        </div>
        <!-- Pagination -->
        <div id="pagination" class="px-3 mt-1 mb-3" style="display:none;"></div>
    </div>


    <!-- Image Selection Modal -->
    <dialog id="imageModal" class="flex items-center justify-center modal">
        <div class="w-[94vw] max-w-[94vw] overflow-hidden sm:max-w-2xl md:max-w-5xl modal-box">
            <form method="dialog">
                <button
                    class="absolute z-10 text-white btn btn-sm btn-circle btn-ghost right-2 top-2 bg-black/50 hover:bg-black/70">
                    <i class="text-xl ri-close-line"></i>
                </button>
            </form>

            <!-- Image Preview Tabs -->
            <div class="p-4 pb-2 sm:p-5">
                <h3 class="mb-3 text-base font-bold sm:text-lg" id="modalImageTitle">Pilih Foto</h3>
                <div class="grid gap-2 mb-3">
                    <div class="px-3 py-2 border rounded-xl bg-slate-50 border-slate-200">
                        <div class="mb-0.5 text-[10px] font-semibold tracking-[0.16em] uppercase text-slate-400">Uploader</div>
                        <span class="text-xs font-medium capitalize sm:text-sm text-slate-700 name_upload"></span>
                    </div>
                    <div class="hidden px-3 py-2 border rounded-xl bg-slate-50 border-slate-200 verified_by_wrapper">
                        <div class="mb-0.5 text-[10px] font-semibold tracking-[0.16em] uppercase text-slate-400">Dipilih Oleh</div>
                        <span class="text-xs font-medium capitalize sm:text-sm text-slate-700 verified_by"></span>
                    </div>
                </div>
                <div role="tablist" class="tabs tabs-lifted">
                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Before"
                        data-type="before" checked />
                    <div role="tabpanel" class="p-2 sm:p-3 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative flex items-center justify-center w-[82vw] max-w-full mx-auto overflow-hidden rounded-lg bg-slate-200 preview-frame sm:w-full">
                            <img id="imgBefore" src="" alt="Before" class="preview-image min-h-[22vh] max-h-[36vh]">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyBefore"
                                style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Proses"
                        data-type="process" />
                    <div role="tabpanel" class="p-2 sm:p-3 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative flex items-center justify-center w-[82vw] max-w-full mx-auto overflow-hidden rounded-lg bg-slate-200 preview-frame sm:w-full">
                            <img id="imgProcess" src="" alt="Process" class="preview-image min-h-[22vh] max-h-[36vh]">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyProcess"
                                style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="After"
                        data-type="final" />
                    <div role="tabpanel" class="p-2 sm:p-3 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative flex items-center justify-center w-[82vw] max-w-full mx-auto overflow-hidden rounded-lg bg-slate-200 preview-frame sm:w-full">
                            <img id="imgFinal" src="" alt="Final" class="preview-image min-h-[22vh] max-h-[36vh]">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyFinal"
                                style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>
                </div>
                <div class="px-3 py-2 mt-3 border rounded-xl bg-slate-50 border-slate-200">
                    <div class="mb-0.5 text-[10px] font-semibold tracking-[0.16em] uppercase text-slate-400">Keterangan</div>
                    <span class="text-xs leading-relaxed sm:text-sm text-slate-600 note"></span>
                </div>
            </div>

            <div class="p-4 pt-2 sm:p-5">
                <div class="flex gap-2 sm:flex-row">
                    <button type="button" disabled
                        class="flex-1 py-1 text-blue-600 border-0 rounded-sm disabled:bg-gray-300 disabled:text-gray-50 btn bg-blue-500/20 hover:bg-blue-500 hover:text-white"
                        id="saveSelectionBtn">
                        <i class="ri-check-line"></i>
                        Pilih
                    </button>
                    <button type="button"
                        class="flex-1 hidden py-1 border-0 rounded-sm btn bg-amber-500/20 text-amber-600 hover:bg-amber-500 hover:text-white"
                        id="cancelSelectionBtn">
                        <i class="ri-close-circle-line"></i>
                        Hapus Pilihan
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    <!-- Toast Notification -->
    <div class="z-50 toast toast-top toast-end" id="toastContainer" style="display: none;">
        <div class="alert" id="toastAlert">
            <i id="toastIcon" class="text-xl"></i>
            <span id="toastMessage">Message</span>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let clientData = null;
                let imagesData = [];
                let fixedData = [];
                let selectedImageData = null;
                let currentImageType = 'before';
                let currentFilter = 'before';
                let countToday = 0;
                let currentPage = 1;
                let lastPage = 1;
                let lastRequestParams = {};
                const imageModalEl = document.getElementById('imageModal');
                const baseStorageUrl = "{{ URL::asset('/storage/') }}";
                const placeholderImageUrl = 'https://placehold.co/1200x900/e2e8f0/64748b?text=Foto+Belum+Tersedia';
                let imageObserver = null;

                const getCurrentScopeParams = () => {
                    const now = new Date();
                    const fallbackMonth = String(now.getMonth() + 1).padStart(2, '0');
                    const fallbackYear = now.getFullYear();

                    return {
                        client_id: $('.clientId').val() || '{{ auth()->user()->kerjasama->client_id }}',
                        month: $('.month').val() || fallbackMonth,
                        year: $('.year').val() || fallbackYear,
                    };
                };

                const closeImageModal = () => {
                    if (imageModalEl && imageModalEl.open) {
                        imageModalEl.close();
                    }
                };

                const resolveImageSrc = (path) => {
                    if (!path) return placeholderImageUrl;
                    if (/^https?:\/\//i.test(path)) return path;
                    return `${baseStorageUrl}/${path}`;
                };

                const applyCountsFromResponse = (response) => {
                    const counts = response?.counts || response?.data;
                    if (!counts || typeof counts.count_today === 'undefined') {
                        const scope = getCurrentScopeParams();
                        getCountData(scope.client_id, scope.month, scope.year);
                        return;
                    }

                    countToday = Number(counts.count_today) || 0;
                    const totalFixed = Number(counts.total_fixed ?? counts.count ?? 0);
                    $('#totalHasFix').text(totalFixed);
                };

                const updateModalActionButtons = (isFixed) => {
                    if (isFixed) {
                        $('#cancelSelectionBtn').removeClass('hidden');
                        $('#saveSelectionBtn').prop('disabled', true).html(
                            '<i class="ri-save-2-line"></i> Sudah Dipilih'
                        );
                        return;
                    }

                    $('#cancelSelectionBtn').addClass('hidden');
                    if (countToday < 2) {
                        $('#saveSelectionBtn').prop('disabled', false).html(
                            '<i class="ri-check-line"></i> Pilih'
                        );
                    } else {
                        $('#saveSelectionBtn').prop('disabled', true).html(
                            '<i class="ri-save-2-line"></i> Maksimal 2 Foto/Hari'
                        );
                    }
                };

                const syncSelectionState = (fixedState) => {
                    if (!fixedState || !fixedState.upload_image_id) return;

                    const uploadImageId = Number(fixedState.upload_image_id);
                    const isFixed = Boolean(fixedState.is_fixed);
                    const verifiedBy = fixedState.verified_by || '-';
                    const imageIndex = imagesData.findIndex(img => Number(img.id) === uploadImageId);

                    if (imageIndex === -1) return;

                    if (isFixed) {
                        imagesData[imageIndex].fixed_image = {
                            user: {
                                nama_lengkap: verifiedBy,
                            }
                        };

                        if (!fixedData.find(item => Number(item.upload_image_id) === uploadImageId)) {
                            fixedData.push({
                                upload_image_id: uploadImageId,
                                user_id: {{ auth()->id() }},
                            });
                        }
                    } else {
                        imagesData[imageIndex].fixed_image = null;
                        fixedData = fixedData.filter(item => Number(item.upload_image_id) !== uploadImageId);
                    }

                    renderImages(imagesData, currentFilter);

                    if (selectedImageData && Number(selectedImageData.id) === uploadImageId) {
                        selectedImageData = imagesData[imageIndex];
                        if (selectedImageData.fixed_image) {
                            $('.verified_by').show().text('Di Pilih Oleh : ' + verifiedBy);
                        } else {
                            $('.verified_by').hide().text('');
                        }
                        updateModalActionButtons(isFixed);
                    }
                };


                $('.clientFilter').on('click', function() {
                    const clientId = $('.clientId').val();
                    const month = $('.month').val();
                    const year = $('.year').val();
                    currentPage = 1;

                    if (!clientId) {
                        {{-- console.log( $('.clientId'), month, year) --}}
                        Notify('Silakan Pilih Mitra!', null, null, 'warning');
                        return;
                    } else if (!month || !year) {
                        Notify('Silakan Pilih Bulan Dan Tahun!', null, null, 'warning');
                        return;
                    }

                    currentFilter = 'before';
                    $('.tabs .tab').removeClass('tab-active');
                    $('.tabs .tab[data-filter="before"]').addClass('tab-active');

                    loadData(clientId, month, year, 1);
                });

                $(document).on('click', '#pagination button', function() {
                    const page = $(this).data('page');
                    if (!page) return;

                    loadData(
                        lastRequestParams.clientId,
                        lastRequestParams.month,
                        lastRequestParams.year,
                        page
                    );
                });



                const getCountData = (clientId, month, year) => {
                    let data = {};

                    if (clientId) data.client_id = clientId;
                    if (month) data.month = month;
                    if (year) data.year = year;
                    $.ajax({
                        url: '{{ route('v1.count.fixed.image') }}',
                        method: 'GET',
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#totalHasFix').text("loading....");
                        },
                        success: function(response) {
                            if (response.status) {
                                countToday = response.data.count_today;
                                $('#totalHasFix').text(response.data.count);

                                if (response.data.count <= 11) {
                                    if (countToday < 2) {
                                        $('#saveSelectionBtn').prop('disabled', false).html(
                                            '<i class="ri-check-line"></i> Pilih');
                                    } else {
                                        $('#saveSelectionBtn').prop('disabled', true).html(
                                            '<i class="ri-save-2-line"></i> Maksimal 2 Foto/Hari');
                                    }
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#totalHasFix').text("loading....");
                            Notify('Gagal memuat data. Silakan coba lagi.', null, null, 'error');
                        }
                    })
                }

                // Lazy Load Images
                const lazyLoadImages = () => {
                    const lazyImages = document.querySelectorAll('img.lazy-load');

                    if (!imageObserver) {
                        imageObserver = new IntersectionObserver((entries, observer) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const img = entry.target;
                                    img.src = img.dataset.src;
                                    img.classList.remove('lazy-load');
                                    img.classList.add('loaded');
                                    observer.unobserve(img);
                                }
                            });
                        });
                    }

                    lazyImages.forEach(img => imageObserver.observe(img));
                };

                // Load Data
                const loadData = (clientId, month, year, page = 1) => {
                    let data = {};

                    if (clientId) data.client_id = clientId;
                    if (month) data.month = month;
                    if (year) data.year = year;
                    data.page = page;

                    lastRequestParams = {
                        clientId,
                        month,
                        year
                    };

                    $.ajax({
                        url: '{{ route('fixed.create') }}',
                        method: 'GET',
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#loadingSkeleton').show();
                            $('#imageGallery').hide();
                            $('#emptyState').hide();
                            $('#clientInfoCard').hide();
                            $('#filterTabs').hide();
                        },
                        success: function(response) {
                            if (response.status) {
                                clientData = response.data.client;
                                imagesData = response.data.image.data;
                                fixedData = response.data.fixed;

                                currentPage = response.data.image.current_page;
                                lastPage = response.data.image.last_page;
                                // Update client info
                                $('#clientName').text(clientData.name || '-');
                                $('#clientDetails').text(clientData.address || '-');
                                $('#totalImages').text(response.data.image.total);
                                applyCountsFromResponse(response.data);
                                $('#clientInfoCard').fadeIn();

                                if (imagesData.length > 0) {
                                    $('#filterTabs').fadeIn();
                                    renderImages(imagesData, currentFilter);
                                    renderPagination(currentPage, lastPage);
                                } else {
                                    $('#loadingSkeleton').hide();
                                    $('#emptyState').fadeIn();
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#loadingSkeleton').hide();
                            Notify('Gagal memuat data. Silakan coba lagi.', null, null, 'error');
                        }
                    });
                };

                // Get Primary Image for Display
                const getPrimaryImage = (image, filterType) => {

                    if (filterType == "before") {
                        return image.img_before ?? placeholderImageUrl;
                    }

                    if (filterType == "proccess") {
                        return image.img_proccess ?? placeholderImageUrl;
                    }

                    if (filterType == "final") {
                        return image.img_final ?? placeholderImageUrl;
                    }

                    if (image.img_before) return image.img_before;
                    if (image.img_proccess) return image.img_proccess;
                    if (image.img_final) return image.img_final;

                    return placeholderImageUrl;
                };


                // Get Image Type Badge
                const getImageBadge = (image) => {
                    const badges = [];
                    if (image.img_before) badges.push(
                        '<span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold text-blue-800 bg-blue-100 rounded-full">Before</span>'
                    );
                    if (image.img_proccess) badges.push(
                        '<span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-800">Process</span>'
                    );
                    if (image.img_final) badges.push(
                        '<span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold text-green-800 bg-green-100 rounded-full">After</span>'
                    );
                    if (image.fixed_image) badges.push(
                        '<span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold text-purple-800 bg-purple-100 rounded-full"><svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Verified</span>'
                    );
                    return badges.join(' ');
                };

                // Filter Images
                const filterImages = (images, filter) => {
                    if (filter == 'before') return images.filter(img => img.img_before);
                    if (filter == 'proccess') return images.filter(img => img.img_proccess);
                    if (filter == 'final') return images.filter(img => img.img_final);
                    return images.filter(img => img.img_before);
                };

                // Render Images
                const renderImages = (images, filter = 'before') => {
                    const gallery = $('#imageGallery');
                    gallery.empty();

                    if (imageObserver) {
                        imageObserver.disconnect();
                    }

                    const filteredImages = filterImages(images, filter);

                    if (filteredImages.length == 0) {
                        $('#loadingSkeleton').hide();
                        $('#emptyState').fadeIn();
                        $('#imageGallery').hide();
                        return;
                    }

                    const cardsHtml = filteredImages.map((image, index) => {
                        const primaryImage = getPrimaryImage(image, filter);
                        const imageBadges = getImageBadge(image);

                        return `
                        <div class="overflow-hidden transition-all duration-300 border bg-white rounded-2xl shadow-sm hover:-translate-y-0.5 hover:shadow-xl border-slate-200 image-card" data-image-id="${image.id}">
                            <div class="relative overflow-hidden bg-slate-200" style="padding-top: 100%;">
                                <img 
                                    data-src="${resolveImageSrc(primaryImage)}" 
                                    alt="Image ${index + 1}"
                                    class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 lazy-load hover:scale-105"
                                    style="opacity: 0; transition: opacity 0.3s;"
                                    onload="this.style.opacity=1"
                                >
                                <div class="absolute inset-x-0 top-0 flex items-start justify-between p-2">
                                    <div class="px-2 py-1 text-[11px] font-semibold text-white rounded-full bg-slate-900/70 backdrop-blur-sm">
                                        #${image.id}
                                    </div>
                                    <div class="flex flex-wrap justify-end gap-1 pl-2">
                                        ${imageBadges}
                                    </div>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="inline-flex items-center text-xs font-medium text-slate-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        ${image.created_at ? new Date(image.created_at).toLocaleDateString('id-ID') : '-'}
                                    </div>
                                    <span class="text-[11px] font-semibold tracking-wide uppercase text-slate-400">Lihat Detail</span>
                                </div>
                            </div>
                        </div>
                    `;
                    }).join('');

                    gallery.html(cardsHtml);

                    $('#loadingSkeleton').hide();
                    $('#emptyState').hide();
                    gallery.fadeIn();

                    // Initialize lazy loading
                    setTimeout(lazyLoadImages, 100);
                };

                // Filter Tab Click
                $('.tabs .tab').on('click', function() {
                    $('.tabs .tab').removeClass('tab-active');
                    $(this).addClass('tab-active');
                    currentFilter = $(this).data('filter');
                    renderImages(imagesData, currentFilter);
                });

                // Image Card Click - Open Modal
                $(document).on('click', '.image-card', function() {
                    const imageId = $(this).data('image-id');
                    const image = imagesData.find(img => img.id == imageId);
                    $('.note').text(image.note || '-')
                    $('.name_upload').text(capitalizeEachWord(image.user.nama_lengkap))
                    if (image.fixed_image) {
                        $('.verified_by_wrapper').removeClass('hidden');
                        $('.verified_by').text(image.fixed_image.user ?
                            capitalizeEachWord(image.fixed_image.user.nama_lengkap) : '-')
                    } else {
                        $('.verified_by_wrapper').addClass('hidden');
                        $('.verified_by').text('');
                    }
                    if (image) {
                        selectedImageData = image;
                        if (fixedData) {
                            const finalData = fixedData.find(e => e.upload_image_id == imageId);
                            updateModalActionButtons(!!finalData);
                        }
                        {{-- if(image.upload_image_id == ) --}}

                        // Load images into modal
                        if (image.img_before) {
                            $('#imgBefore').attr('src', resolveImageSrc(image.img_before)).show();
                            $('#emptyBefore').hide();
                        } else {
                            $('#imgBefore').attr('src', placeholderImageUrl).show();
                            $('#emptyBefore').hide();
                        }

                        if (image.img_proccess) {
                            $('#imgProcess').attr('src', resolveImageSrc(image.img_proccess)).show();
                            $('#emptyProcess').hide();
                        } else {
                            $('#imgProcess').attr('src', placeholderImageUrl).show();
                            $('#emptyProcess').hide();
                        }

                        if (image.img_final) {
                            $('#imgFinal').attr('src', resolveImageSrc(image.img_final)).show();
                            $('#emptyFinal').hide();
                        } else {
                            $('#imgFinal').attr('src', placeholderImageUrl).show();
                            $('#emptyFinal').hide();
                        }

                        $('#modalImageTitle').text(`Foto #${image.id}`);

                        // Reset to first tab
                        {{-- $('input[name="image_tabs"]:first').prop('checked', true);
                    currentImageType = 'before'; --}}

                        document.getElementById('imageModal').showModal();
                    }
                });

                // Track selected tab
                $('input[name="image_tabs"]').on('change', function() {
                    currentImageType = $(this).data('type');
                });

                // Save Selection Button
                $('#saveSelectionBtn').on('click', function(event) {
                    event.preventDefault();
                    if (!selectedImageData) {
                        Notify('Tidak ada foto yang dipilih', null, null, 'error');
                        return;
                    }

                    // Get selected image URL based on current tab
                    let selectedImageUrl = null;
                    if (currentImageType == 'before' && selectedImageData.img_before) {
                        selectedImageUrl = selectedImageData.img_before;
                    } else if (currentImageType == 'process' && selectedImageData.img_proccess) {
                        selectedImageUrl = selectedImageData.img_proccess;
                    } else if (currentImageType == 'final' && selectedImageData.img_final) {
                        selectedImageUrl = selectedImageData.img_final;
                    }

                    if (!selectedImageUrl) {
                        Notify('Foto tidak tersedia pada tab ini', null, null, 'error');
                        return;
                    }

                    const formData = {
                        user_id: {{ auth()->id() }},
                        clients_id: getCurrentScopeParams().client_id,
                        upload_image_id: selectedImageData.id,
                        month: getCurrentScopeParams().month,
                        year: getCurrentScopeParams().year,
                        _token: '{{ csrf_token() }}'
                    };

                    $.ajax({
                        url: '{{ route('fixed.store') }}',
                        method: 'POST',
                        data: formData,
                        beforeSend: function() {
                            $('#saveSelectionBtn').prop('disabled', true).html(
                                '<span class="loading loading-spinner"></span> Menyimpan...');
                        },
                        success: function(response) {
                            if (response.success) {
                                Notify('Data berhasil disimpan!', null, null, 'success');
                                applyCountsFromResponse(response);
                                syncSelectionState(response.fixed_state);
                                closeImageModal();
                            }
                        },
                        error: function(xhr) {
                            Notify(xhr.responseJSON ? xhr.responseJSON.message :
                                'Gagal menyimpan data. Silakan coba lagi.', null, null, 'error');
                        },
                        complete: function() {
                            $('#saveSelectionBtn').prop('disabled', false).html(
                                '<i class="ri-check-line"></i> Verifikasi Pilihan');
                        }
                    });
                });

                // Delete Selection Button
                $('#cancelSelectionBtn').on('click', function(event) {
                    event.preventDefault();
                    if (!selectedImageData) {
                        Notify('Tidak ada foto yang dipilih', null, null, 'warning');
                        return;
                    }
                    {{-- fixed.destroy --}}
                    $.ajax({
                        url: '{{ route('fixed.destroy', ':id') }}'.replace(':id', selectedImageData
                            .id),
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            _method: 'DELETE',
                            client_id: getCurrentScopeParams().client_id,
                            month: getCurrentScopeParams().month,
                            year: getCurrentScopeParams().year,
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            $('#cancelSelectionBtn').prop('disabled', true).html(
                                '<span class="loading loading-spinner"></span> Menghapus...');
                        },
                        success: function(response) {
                            if (response.status) {
                                Notify(response.message, null, null, 'warning');
                                applyCountsFromResponse(response);
                                syncSelectionState(response.fixed_state);
                                closeImageModal();
                            }
                        },
                        error: function(xhr) {
                            $('#loadingSkeleton').hide();
                            closeImageModal();
                            Notify('Gagal delete data. Silakan coba lagi.', null, null, 'error');
                        }
                    });
                })

                const renderPagination = (currentPage, lastPage) => {
                    if (lastPage <= 1) {
                        $('#pagination').hide().empty();
                        return;
                    }

                    const visiblePages = new Set([1, lastPage, currentPage, currentPage - 1, currentPage + 1]);
                    const pages = [...visiblePages]
                        .filter(page => page >= 1 && page <= lastPage)
                        .sort((a, b) => a - b);

                    const createBtn = (page, label = page, active = false, extraClass = '') => `
                    <button
                        class="join-item btn btn-sm min-h-9 h-9 px-3 text-xs sm:text-sm ${active ? 'btn-active' : 'btn-ghost'} ${extraClass}"
                        data-page="${page}">
                        ${label}
                    </button>
                `;

                    const createEllipsis = () => `
                    <span class="px-2 text-xs join-item btn btn-sm btn-disabled min-h-9 h-9">
                        ...
                    </span>
                `;

                    let pageButtons = createBtn(
                        currentPage - 1,
                        '<i class="text-base ri-arrow-left-s-line"></i>',
                        false,
                        currentPage === 1 ? 'btn-disabled pointer-events-none' : ''
                    );

                    pages.forEach((page, index) => {
                        const previousPage = pages[index - 1];

                        if (index > 0 && page - previousPage > 1) {
                            pageButtons += createEllipsis();
                        }

                        pageButtons += createBtn(page, page, currentPage === page);
                    });

                    pageButtons += createBtn(
                        currentPage + 1,
                        '<i class="text-base ri-arrow-right-s-line"></i>',
                        false,
                        currentPage === lastPage ? 'btn-disabled pointer-events-none' : ''
                    );

                    const html = `
                    <div class="flex flex-col items-center gap-2">
                        <div class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase">
                            Halaman ${currentPage} / ${lastPage}
                        </div>
                        <div class="w-full max-w-full overflow-x-auto">
                            <div class="flex justify-center min-w-full p-1 shadow-md join w-max rounded-2xl bg-base-100/90">
                                ${pageButtons}
                            </div>
                        </div>
                    </div>
                `;

                    $('#pagination').html(html).fadeIn();
                };



                function capitalizeEachWord(str) {
                    return str
                        .toLowerCase()
                        .replace(/\b\w/g, char => char.toUpperCase());
                }



                // Initialize
                loadData();
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .lazy-load {
                filter: blur(5px);
            }

            .lazy-load.loaded {
                filter: blur(0);
                transition: filter 0.3s;
            }

            .tab-content {
                display: none;
            }

            input[type="radio"]:checked+.tab-content {
                display: block;
            }

            .preview-frame {
                min-height: 260px;
                height: clamp(260px, 52vh, 420px);
            }

            .preview-image {
                width: 100% !important;
                height: 100% !important;
                max-width: none !important;
                max-height: none !important;
                object-fit: contain !important;
                display: block;
            }

            @media (max-width: 640px) {
                #imageModal .modal-box {
                    width: 94vw;
                    max-width: 94vw;
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                }

                .preview-frame {
                    min-height: 82vw;
                    height: 82vw;
                }
            }

            #pagination .join-item.btn.btn-active {
                background-color: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }

            #filterTabs .tab {
                gap: 0.35rem;
                background-color: rgba(255, 255, 255, 0.7);
                color: rgb(71 85 105);
            }

            #filterTabs .tab.tab-active {
                background-color: rgb(37 99 235);
                border-color: rgb(37 99 235);
                color: #fff;
                box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
            }
        </style>
    @endpush
</x-app-layout>
