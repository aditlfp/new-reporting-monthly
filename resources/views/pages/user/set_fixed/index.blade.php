<x-app-layout>
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />
        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />
            <!-- Main Content -->
            <main class="flex-1 p-1 overflow-y-auto xs:p-2 sm:p-4 md:p-6 m-2">
                <div class="w-full max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6 md:mb-8">
                        <h2 class="mb-1 text-xl font-bold sm:text-2xl text-slate-900">Data Semua Foto</h2>
                        <p class="text-sm sm:text-base text-slate-500">Data Sesuai Dengan Mitra Yang Anda Tempati</p>
                    </div>

                    <!-- Client Info Card -->
                    <div class="mb-6 card bg-base-100 shadow-xl" id="clientInfoCard" style="display: none;">
                        <div class="card-body p-4 sm:p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold sm:text-xl text-slate-900" id="clientName">-</h3>
                                    <p class="text-sm text-slate-500" id="clientDetails">-</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="flex flex-col">
                                        <span>Total Foto</span>
                                        <div class="badge badge-info badge-lg">
                                            <i class="ri-image-line mr-2"></i>
                                            <span id="totalImages">0</span> Foto
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span>Total Foto Yang Dipilih</span>
                                        <div class="badge badge-success badge-lg">
                                            <i class="ri-image-line mr-2"></i>
                                            <span id="totalHasFix">0</span>/ 11 Foto
                                        </div>
                                    </div>
                                </div>
                                 <span class="text-xs italic font-bold">Note : Termasuk (before/progress/after)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="mb-6" id="filterTabs" style="display: none;">
                        <div role="tablist" class="tabs tabs-boxed bg-base-200">
                            <a role="tab" class="tab tab-active" data-filter="all">
                                <i class="ri-gallery-line mr-2"></i>
                                <span class="hidden sm:inline">Semua</span>
                            </a>
                            <a role="tab" class="tab" data-filter="before">
                                <i class="ri-image-line mr-2"></i>
                                <span class="hidden sm:inline">Before</span>
                            </a>
                            <a role="tab" class="tab" data-filter="process">
                                <i class="ri-settings-3-line mr-2"></i>
                                <span class="hidden sm:inline">Proses</span>
                            </a>
                            <a role="tab" class="tab" data-filter="final">
                                <i class="ri-checkbox-circle-line mr-2"></i>
                                <span class="hidden sm:inline">After</span>
                            </a>
                        </div>
                    </div>

                    <!-- Loading Skeleton -->
                    <div id="loadingSkeleton" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:gap-4">
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                        <div class="skeleton h-48 sm:h-56 md:h-64"></div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="flex flex-col items-center justify-center py-12 text-center" style="display: none;">
                        <i class="ri-image-line text-6xl sm:text-7xl text-slate-300 mb-4"></i>
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
    <dialog id="imageModal" class="modal p-2">
        <div class="modal-box max-w-5xl p-0 overflow-hidden">
            <form method="dialog">
                <button class="absolute btn btn-sm btn-circle btn-ghost right-2 top-2 z-10 bg-black/50 text-white hover:bg-black/70">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </form>
            
            <!-- Image Preview Tabs -->
            <div class="p-4 sm:p-6 pb-2">
                <h3 class="mb-4 text-lg font-bold sm:text-xl" id="modalImageTitle">Pilih Foto</h3>
                <span class="name_upload text-sm"></span>
                <div role="tablist" class="tabs tabs-lifted">
                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Before" data-type="before" checked />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-4">
                        <figure class="relative w-full bg-slate-200 rounded-lg overflow-hidden" style="min-height: 300px;">
                            <img id="imgBefore" src="" alt="Before" class="w-full h-auto object-contain" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyBefore" style="display: none;">
                                <div class="text-center">
                                    <i class="ri-image-line text-5xl text-slate-400 mb-2"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="Proses" data-type="process" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-4">
                        <figure class="relative w-full bg-slate-200 rounded-lg overflow-hidden" style="min-height: 300px;">
                            <img id="imgProcess" src="" alt="Process" class="w-full h-auto object-contain" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyProcess" style="display: none;">
                                <div class="text-center">
                                    <i class="ri-image-line text-5xl text-slate-400 mb-2"></i>
                                    <p class="text-slate-500">Tidak ada foto</p>
                                </div>
                            </div>
                        </figure>
                    </div>

                    <input type="radio" name="image_tabs" role="tab" class="tab" aria-label="After" data-type="final" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-4">
                        <figure class="relative w-full bg-slate-200 rounded-lg overflow-hidden" style="min-height: 300px;">
                            <img id="imgFinal" src="" alt="Final" class="w-full h-auto object-contain" style="max-height: 400px;">
                            <div class="absolute inset-0 flex items-center justify-center" id="emptyFinal" style="display: none;">
                                <div class="text-center">
                                    <i class="ri-image-line text-5xl text-slate-400 mb-2"></i>
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

            <div class="p-4 sm:p-6 pt-2">
                <div class="flex gap-2 sm:flex-row">
                    <button disabled class="disabled:bg-gray-300 disabled:text-gray-50 btn bg-blue-500/20 text-blue-600 border-0 rounded-sm flex-1 py-1 hover:bg-blue-500 hover:text-white" id="saveSelectionBtn">
                        <i class="ri-check-line"></i>
                        Simpan
                    </button>
                     <button type="button" class="hidden btn bg-amber-500/20 text-amber-600 border-0 rounded-sm flex-1 py-1 hover:bg-amber-500 hover:text-white" id="cancelSelectionBtn">
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
    <div class="toast toast-top toast-end z-50" id="toastContainer" style="display: none;">
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

            const getCountData = () => {
                $.ajax({
                    url: '{{ route("v1.count.fixed.image")}}',
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#totalHasFix').text("loading....");
                    },
                    success: function(response) {
                        if (response.status) {

                            if (response.data.count >= 0) {
                                $('#totalHasFix').text(response.data.count);
                                if(response.data.count <= 11) $('#saveSelectionBtn').prop('disabled', false);

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
            const loadData = () => {
                $.ajax({
                    url: '{{ route("fixed.create") }}',
                    method: 'GET',
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
                if (image.img_before) badges.push('<span class="badge badge-xs badge-info">B</span>');
                if (image.img_proccess) badges.push('<span class="badge badge-xs badge-warning">P</span>');
                if (image.img_final) badges.push('<span class="badge badge-xs badge-success">A</span>');
                if(image.fixed_image) badges.push('<span class="badge badge-xs py-2 bg-blue-600 text-white"><i class="ri-verified-badge-line text-lg font-semibold"></i></span>')
                return badges.join(' ');
            };

            // Filter Images
            const filterImages = (images, filter) => {
                if (filter == 'all') return images;
                if (filter == 'before') return images.filter(img => img.img_before);
                if (filter == 'process') return images.filter(img => img.img_proccess);
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
                        <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow cursor-pointer image-card" data-image-id="${image.id}">
                            <figure class="relative overflow-hidden bg-slate-200" style="padding-top: 100%;">
                                <img 
                                    data-src="${baseUrl + "/" + primaryImage}" 
                                    alt="Image ${index + 1}"
                                    class="lazy-load absolute inset-0 w-full h-full object-cover transition-transform hover:scale-105"
                                    style="opacity: 0; transition: opacity 0.3s;"
                                    onload="this.style.opacity=1"
                                >
                                <div class="absolute top-2 right-2 flex items-center gap-1">
                                    ${imageBadges}
                                </div>
                                <div class="absolute bottom-2 left-2">
                                    <div class="badge badge-sm badge-neutral">
                                        <i class="ri-image-line mr-1"></i>
                                        #${image.id}
                                    </div>
                                </div>
                            </figure>
                            <div class="card-body p-3">
                                <p class="text-xs text-slate-500">
                                    <i class="ri-calendar-line mr-1"></i>
                                    ${image.created_at ? new Date(image.created_at).toLocaleDateString('id-ID') : '-'}
                                </p>
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
                            $('#saveSelectionBtn').prop('disabled', false).html('<i class="ri-check-line"></i> Simpan');
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