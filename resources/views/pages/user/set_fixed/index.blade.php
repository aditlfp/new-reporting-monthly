<x-app-layout>
    @push('styles')
         /* Remove default select arrow and add custom styling */
            select.select {
                padding-left: 2.5rem;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
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
                    <div class="mb-6 overflow-hidden bg-white border shadow-lg rounded-xl border-slate-200" id="clientInfoCard" style="display: none;">
                        <!-- Accent Bar -->
                        <div class="h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                        
                        <div class="p-5 sm:p-6">
                            <!-- Header -->
                            <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-900" id="clientName">
                                        <div class="p-1.5 bg-blue-100 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        -
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500" id="clientDetails">-</p>
                                </div>
                                
                                <!-- Stats -->
                                <div class="flex items-center justify-center gap-2 mx-4">
                                    <div class="p-3 text-center border shadow-sm min-w-1/4 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border-slate-200">
                                        <div class="text-2xl font-bold text-slate-800" id="totalImages">0</div>
                                        <div class="text-xs font-medium tracking-wide uppercase text-slate-600">Total</div>
                                    </div>
                                    
                                    <div class="text-xl font-light text-slate-300">|</div>
                                    
                                    <div class="p-3 text-center border border-blue-200 shadow-sm min-w-1/3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                                        <div class="text-2xl font-bold text-blue-600" id="totalHasFix">0</div>
                                        <div class="text-xs font-medium tracking-wide text-blue-600 uppercase">Dipilih</div>
                                    </div>
                                    
                                    <div class="text-xl font-light text-slate-300">|</div>
                                    
                                    <div class="p-3 text-center border border-purple-200 shadow-sm min-w-1/4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                        <div class="text-2xl font-bold text-purple-600">11</div>
                                        <div class="text-xs font-medium tracking-wide text-purple-600 uppercase">Maks</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Note -->
                            <div class="flex items-center gap-2 p-3 border rounded-lg bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200">
                                <div class="p-1 rounded-full bg-amber-100">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-medium text-amber-700">Termasuk foto Before, Progress, dan After</p>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->canAccess())
                        <div class="mb-6">
                            <form class="w-full">
                                <!-- Desktop Layout -->
                                <div class="flex flex-col md:flex-row items-end gap-3">
                                    <div class="form-control flex-1">
                                        <label for="client_id" class="label">
                                            <span class="label-text font-medium required">Mitra</span>
                                        </label>
                                        <div class="relative">
                                            <select name="client_id" class="select select-bordered w-full clientId focus:outline-none focus:ring-2 focus:ring-primary">
                                                <option value="">Pilih mitra</option>
                                                @forelse($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @empty
                                                    <option value="">Mitra Kosong</option>
                                                @endforelse
                                            </select>
                                            <i class="ri-building-line absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    <div class="flex md:block w-full sm:w-auto gap-x-2">
                                        <div class="form-control flex-1">
                                            <label for="month" class="label">
                                                <span class="label-text font-medium">Bulan <span class="text-error">*</span></span>
                                            </label>
                                            <div class="relative">
                                                <select name="month" class="month select select-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary">
                                                    <option value="">Pilih Bulan</option>
                                                    @foreach (range(1, 12) as $month)
                                                        <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                            {{ \Carbon\Carbon::create(null, $month, 1)
                                                                ->locale('id')
                                                                ->translatedFormat('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="ri-calendar-line absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50 pointer-events-none"></i>
                                            </div>
                                        </div>

                                        <div class="form-control flex-1">
                                            <label for="year" class="label">
                                                <span class="label-text font-medium">Tahun <span class="text-error">*</span></span>
                                            </label>
                                            <div class="relative">
                                                <select name="year" class="year select select-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary">
                                                    @php
                                                        $currentYear = now()->year;
                                                    @endphp
                                                    @foreach (range($currentYear - 5, $currentYear + 5) as $year)
                                                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="ri-calendar-2-line absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50 pointer-events-none"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn rounded-sm bg-blue-500/20 text-blue-500 border-0 hover:bg-blue-500 hover:text-white gap-2 clientFilter w-full md:w-auto">
                                        <i class="ri-filter-3-line text-lg"></i>
                                        Filter
                                    </button>
                                </div>

                            </form>
                        </div>
                    @endif

                    <!-- Filter Tabs -->
                    <div class="mb-6" id="filterTabs" style="display: none;">
                        <div role="tablist" class="border-2 border-dashed rounded-md border-base-300 tabs tabs-boxed bg-base-200">
                            <a role="tab" class="tab tab-active" data-filter="all">
                                <i class="mr-2 ri-gallery-line"></i>
                                <span class="inline">Semua</span>
                            </a>
                            <a role="tab" class="tab" data-filter="before">
                                <i class="mr-2 ri-image-line"></i>
                                <span class="inline">Before</span>
                            </a>
                            <a role="tab" class="tab" data-filter="proccess">
                                <i class="mr-2 ri-settings-3-line"></i>
                                <span class="inline">Proses</span>
                            </a>
                            <a role="tab" class="tab" data-filter="final">
                                <i class="mr-2 ri-checkbox-circle-line"></i>
                                <span class="inline">After</span>
                            </a>
                        </div>
                    </div>

                    <!-- Loading Skeleton -->
                    <div id="loadingSkeleton" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4">
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                        <div class="h-48 skeleton sm:h-56 md:h-64"></div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="flex flex-col items-center justify-center py-12 text-center" style="display: none;">
                        <i class="mb-4 text-6xl ri-image-line sm:text-7xl text-slate-300"></i>
                        <h3 class="mb-2 text-lg font-semibold sm:text-xl text-slate-700">Tidak Ada Foto</h3>
                        <p class="text-sm sm:text-base text-slate-500">Belum ada foto yang tersedia untuk mitra ini</p>
                    </div>

                    <!-- Image Gallery Grid -->
                    <div id="imageGallery" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4" style="display: none;">
                        <!-- Images will be loaded here -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Image Selection Modal -->
    <dialog id="imageModal" class="p-2 modal">
        <div class="max-w-5xl p-0 overflow-hidden modal-box">
            <form method="dialog">
                <button class="absolute z-10 text-white btn btn-sm btn-circle btn-ghost right-2 top-2 bg-black/50 hover:bg-black/70">
                    <i class="text-xl ri-close-line"></i>
                </button>
            </form>
            
            <!-- Image Preview Tabs -->
            <div class="p-4 pb-2 sm:p-6">
                <h3 class="mb-4 text-lg font-bold sm:text-xl" id="modalImageTitle">Pilih Foto</h3>
                <span class="text-sm name_upload"></span>
                <div role="tablist" class="tabs tabs-lifted">
                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Before" data-type="before" checked />
                    <div role="tabpanel" class="p-4 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative w-full overflow-hidden rounded-lg bg-slate-200" style="min-height: 300px;">
                            <img id="imgBefore" src="" alt="Before" class="object-contain w-full h-auto" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyBefore" style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Proses" data-type="process" />
                    <div role="tabpanel" class="p-4 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative w-full overflow-hidden rounded-lg bg-slate-200" style="min-height: 300px;">
                            <img id="imgProcess" src="" alt="Process" class="object-contain w-full h-auto" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyProcess" style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="After" data-type="final" />
                    <div role="tabpanel" class="p-4 tab-content bg-base-100 border-base-300 rounded-box">
                        <figure class="relative w-full overflow-hidden rounded-lg bg-slate-200" style="min-height: 300px;">
                            <img id="imgFinal" src="" alt="Final" class="object-contain w-full h-auto" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyFinal" style="display: none;">
                                <div class="text-center">
                                    <i class="mb-2 text-5xl ri-image-line text-slate-400"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>
                </div>
                <div class="mt-5">
                    <span class="note"></span>
                </div>
            </div>

            <div class="p-4 pt-2 sm:p-6">
                <div class="flex gap-2 sm:flex-row">
                    <button disabled class="flex-1 py-1 text-blue-600 border-0 rounded-sm disabled:bg-gray-300 disabled:text-gray-50 btn bg-blue-500/20 hover:bg-blue-500 hover:text-white" id="saveSelectionBtn">
                        <i class="ri-check-line"></i>
                        Simpan
                    </button>
                     <button type="button" class="flex-1 hidden py-1 border-0 rounded-sm btn bg-amber-500/20 text-amber-600 hover:bg-amber-500 hover:text-white" id="cancelSelectionBtn">
                        <i class="ri-close-circle-line"></i>
                         Hapus Pilihan
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button id="modalCloseBtn">close</button>
        </form>
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
            let currentFilter = 'all';
            let countToday = 0;

            $('.clientFilter').on('click', function () {
                const clientId = $('.clientId').val();
                const month = $('.month').val();
                const year = $('.year').val();

                if (!clientId || !month || !year) {
                    console.log( $('.clientId'), month, year)
                    Notify('Silakan pilih mitra', null, null, 'warning');
                    return;
                }

                currentFilter = 'all';
                $('.tabs .tab').removeClass('tab-active');
                $('.tabs .tab[data-filter="all"]').addClass('tab-active');

                loadData(clientId, month, year);
                getCountData(clientId, month, year);
            });


            const getCountData = (clientId, month, year) => {
                let data = {};

                if (clientId) data.client_id = clientId;
                if (month) data.month = month;
                if (year) data.year = year;
                $.ajax({
                    url: '{{ route("v1.count.fixed.image")}}',
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

                            if(response.data.count <= 11) {
                                if(countToday < 2) {
                                    $('#saveSelectionBtn').prop('disabled', false).html('<i class="ri-check-line"></i> Simpan');
                                } else {
                                    $('#saveSelectionBtn').prop('disabled', true).html('<i class="ri-save-2-line"></i> Maksimal 2 Foto/Hari');
                                }
                            }
                        }
                    },
                    error: function(xhr) {
                        $('#totalHasFix').text("loading....");
                        Notify('Gagal memuat data. Silakan coba lagi.',null,null, 'error');
                    }
                })
            }

            // Lazy Load Images
            const lazyLoadImages = () => {
                const lazyImages = document.querySelectorAll('img.lazy-load');
                
                const imageObserver = new IntersectionObserver((entries, observer) => {
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

                lazyImages.forEach(img => imageObserver.observe(img));
            };

            // Load Data
            const loadData = (clientId, month, year) => {
                let data = {};

                if (clientId) data.client_id = clientId;
                if (month) data.month = month;
                if (year) data.year = year;
                $.ajax({
                    url: '{{ route("fixed.create") }}',
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
                            imagesData = response.data.image;
                            fixedData = response.data.fixed;

                            // Update client info
                            $('#clientName').text(clientData.name || '-');
                            $('#clientDetails').text(clientData.address || '-');
                            $('#totalImages').text(imagesData.length);
                            $('#clientInfoCard').fadeIn();

                            if (imagesData.length > 0) {
                                $('#filterTabs').fadeIn();
                                renderImages(imagesData, currentFilter);
                            } else {
                                $('#loadingSkeleton').hide();
                                $('#emptyState').fadeIn();
                            }
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSkeleton').hide();
                        Notify('Gagal memuat data. Silakan coba lagi.',null,null, 'error');
                    }
                });
            };

            // Get Primary Image for Display
           const getPrimaryImage = (image, filterType) => {

                if (filterType == "before") {
                    return image.img_before ?? "/placeholder.jpg";
                }

                if (filterType == "proccess") {
                    return image.img_proccess ?? "/placeholder.jpg";
                }

                if (filterType == "final") {
                    return image.img_final ?? "/placeholder.jpg";
                }

                if (image.img_before) return image.img_before;
                if (image.img_proccess) return image.img_proccess;
                if (image.img_final) return image.img_final;

                return "/placeholder.jpg";
            };


            // Get Image Type Badge
            const getImageBadge = (image) => {
                const badges = [];
                if (image.img_before) badges.push('<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Before</span>');
                if (image.img_proccess) badges.push('<span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Process</span>');
                if (image.img_final) badges.push('<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">After</span>');
                if(image.fixed_image) badges.push('<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Verified</span>');
                return badges.join(' ');
            };

            // Filter Images
            const filterImages = (images, filter) => {
                if (filter == 'all') return images;
                if (filter == 'before') return images.filter(img => img.img_before);
                if (filter == 'proccess') return images.filter(img => img.img_proccess);
                if (filter == 'final') return images.filter(img => img.img_final);
                return images;
            };

            // Render Images
            const renderImages = (images, filter = 'all') => {
                const gallery = $('#imageGallery');
                gallery.empty();

                const filteredImages = filterImages(images, filter);

                if (filteredImages.length == 0) {
                    $('#loadingSkeleton').hide();
                    $('#emptyState').fadeIn();
                    $('#imageGallery').hide();
                    return;
                }

                filteredImages.forEach((image, index) => {
                    const primaryImage = getPrimaryImage(image, filter);
                    const baseUrl = "{{ URL::asset('/storage/')}}"
                    const imageBadges = getImageBadge(image);
                    
                    const imageCard = `
    <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-md hover:shadow-xl image-card" data-image-id="${image.id}">
        <div class="relative overflow-hidden bg-slate-200" style="padding-top: 100%;">
            <img 
                data-src="${baseUrl + "/" + primaryImage}" 
                alt="Image ${index + 1}"
                class="absolute inset-0 object-cover w-full h-full transition-transform duration-500 lazy-load hover:scale-105"
                style="opacity: 0; transition: opacity 0.3s;"
                onload="this.style.opacity=1"
            >
            <div class="absolute top-0 right-0 grid grid-cols-1 gap-2 p-2">
                <div class="flex flex-wrap justify-end gap-1 max-w-[120px]">
                    ${imageBadges}
                </div>
                <div class="flex justify-start px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-50 rounded-full backdrop-blur-sm">
                    #${image.id}
                </div>
            </div>
        </div>
        <div class="p-3">
            <div class="flex items-center text-xs text-slate-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                ${image.created_at ? new Date(image.created_at).toLocaleDateString('id-ID') : '-'}
            </div>
        </div>
    </div>
`;
                    gallery.append(imageCard);
                });

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
                const baseUrl = "{{ URL::asset('/storage/')}}"
                const image = imagesData.find(img => img.id == imageId);
                $('.note').text("Keterangan : " + image.note)
                $('.name_upload').text("Di Upload Oleh : " + image.user.nama_lengkap)
                if (image) {
                    selectedImageData = image;
                    if(fixedData)
                    {
                        const finalData = fixedData.find(e => e.upload_image_id == imageId);
                        if(finalData) 
                        {
                            $('#cancelSelectionBtn').removeClass('hidden')
                            $('#saveSelectionBtn').prop('disabled', true).html('<i class="ri-save-2-line"></i> Sudah Disimpan');
                        }
                        else 
                        {
                            $('#cancelSelectionBtn').addClass('hidden')
                            // Check current count_today before enabling button
                            if(countToday < 2) {
                                $('#saveSelectionBtn').prop('disabled', false).html('<i class="ri-check-line"></i> Simpan');
                            } else {
                                $('#saveSelectionBtn').prop('disabled', true).html('<i class="ri-save-2-line"></i> Maksimal 2 Foto/Hari');
                            }
                        }
                    }
                    {{-- if(image.upload_image_id == ) --}}
                    
                    // Load images into modal
                    if (image.img_before) {
                        $('#imgBefore').attr('src', baseUrl + "/" + image.img_before).show();
                        $('#emptyBefore').hide();
                    } else {
                        $('#imgBefore').hide();
                        $('#emptyBefore').show();
                    }

                    if (image.img_proccess) {
                        $('#imgProcess').attr('src', baseUrl + "/" + image.img_proccess).show();
                        $('#emptyProcess').hide();
                    } else {
                        $('#imgProcess').hide();
                        $('#emptyProcess').show();
                    }

                    if (image.img_final) {
                        $('#imgFinal').attr('src', baseUrl + "/" + image.img_final).show();
                        $('#emptyFinal').hide();
                    } else {
                        $('#imgFinal').hide();
                        $('#emptyFinal').show();
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
            $('#saveSelectionBtn').on('click', function() {
                if (!selectedImageData) {
                    Notify('Tidak ada foto yang dipilih',null,null, 'error');
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
                    Notify('Foto tidak tersedia pada tab ini',null,null, 'error');
                    return;
                }

                const formData = {
                    user_id: {{ auth()->id() }},
                    clients_id: {{ auth()->user()->kerjasama->client_id }},
                    upload_image_id: selectedImageData.id,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route("fixed.store") }}',
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#saveSelectionBtn').prop('disabled', true).html('<span class="loading loading-spinner"></span> Menyimpan...');
                    },
                    success: function(response) {
                        if (response.status) {
                            Notify('Data berhasil disimpan!',null,null, 'success');
                            selectedImageData = null;
                            loadData();
                            getCountData()
                            $('#modalCloseBtn').click();
                        }
                    },
                    error: function(xhr) {
                        Notify('Gagal menyimpan data. Silakan coba lagi.',null,null, 'error');
                    },
                    complete: function() {
                        $('#saveSelectionBtn').prop('disabled', false).html('<i class="ri-check-line"></i> Simpan Pilihan');
                    }
                });
            });

            // Delete Selection Button
            $('#cancelSelectionBtn').on('click', function() {
                {{-- fixed.destroy --}}
                $.ajax({
                    url: '{{ route("fixed.destroy", ":id") }}'.replace(':id', selectedImageData.id),
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#cancelSelectionBtn').prop('disabled', true).html('<span class="loading loading-spinner"></span> Menghapus...');
                    },
                    success: function(response) {
                        if (response.status) {
                            Notify(response.message,null,null, 'warning');
                            loadData()
                            getCountData()
                            $('#modalCloseBtn').click();
                        }
                    },
                    error: function(xhr) {
                        $('#loadingSkeleton').hide();
                        $('#modalCloseBtn').click();
                        Notify('Gagal delete data. Silakan coba lagi.',null,null, 'error');
                    }
                });
            })


            // Initialize
            loadData();
            getCountData();
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
        input[type="radio"]:checked + .tab-content {
            display: block;
        }
    </style>
    @endpush
</x-app-layout>
