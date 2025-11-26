<x-app-layout>
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <h2 class="mb-1 text-2xl font-bold text-slate-900">Rekapitulasi Bulanan</h2>
                        <p class="text-slate-500">Buat dan kelola laporan bulanan Anda</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
                        <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                            <div class="flex items-center">
                                <div class="p-2 text-purple-500 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-slate-500">Limit Gambar (Bulan ini)</p>
                                    <div class="flex items-baseline">
                                        <p class="text-2xl font-bold text-slate-900" id="remainingImages">{{ $totalImageCount }}</p>
                                        <span class="ml-1 text-sm text-slate-500">/ 33</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                        <div class="bg-purple-500 h-1.5 rounded-full transition-all duration-300" id="imageProgress"
                                            style="width: 55%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Draft Card Container -->
                        <div id="draftCardContainer">
                            @if ($uploadDraft)
                                <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                                    <div class="flex items-center">
                                        <div class="p-2 text-blue-500 bg-blue-100 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm text-slate-500">Draft Tersedia</p>
                                            <button id="editDraftBtn"
                                                class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit
                                                Draft</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Create Report Form -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Upload Gambar Kegiatan</h3>
                        </div>

                        <div class="p-5 bg-white border rounded-lg shadow-sm border-slate-100">
                            <form id="reportForm">
                                @csrf
                                <input type="hidden" id="reportStatus" name="status" value="0">
                                <input type="hidden" name="type" value="">
                                <input type="hidden" id="reportId" name="id" value="">
                                <input type="hidden" name="_method" value="POST">
                                <!-- Hidden fields to store existing image paths -->
                                <input type="hidden" id="existing_img_before" name="existing_img_before" value="">
                                <input type="hidden" id="existing_img_proccess" name="existing_img_proccess" value="">
                                <input type="hidden" id="existing_img_final" name="existing_img_final" value="">
                                
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Image Uploads -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-slate-700">Gambar (maks
                                            3)</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <!-- Image 1 -->
                                            <div class="relative">
                                                <input type="file" id="image1" name="img_before"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic,.jpg,.jpeg"
                                                    class="hidden">
                                                <label for="image1"
                                                    class="flex flex-col items-center justify-center w-24 h-24 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview1"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-contain object-center w-24 h-24 lazy-load">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(1)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Image 2 -->
                                            <div class="relative">
                                                <input type="file" id="image2" name="img_proccess"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic,.jpg,.jpeg"
                                                    class="hidden">
                                                <label for="image2"
                                                    class="flex flex-col items-center justify-center w-24 h-24 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview2"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-contain object-center w-24 h-24 lazy-load">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(2)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Image 3 -->
                                            <div class="relative">
                                                <input type="file" id="image3" name="img_final"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic,.jpg,.jpeg"
                                                    class="hidden">
                                                <label for="image3"
                                                    class="flex flex-col items-center justify-center w-24 h-24 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview3"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-contain object-center w-24 h-24 lazy-load">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(3)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Textarea for content -->
                                <div class="mt-4 md:col-span-2">
                                    <label for="reportContent"
                                        class="block mb-2 text-sm font-medium text-slate-700">Isi Keterangan</label>
                                    <textarea id="reportContent" name="note" rows="4"
                                        class="w-full px-4 py-2 bg-white border rounded-lg text-slate-900 border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Tulis isi keterangan di sini... (format: 'nama kegiatan - nama area')"></textarea>
                                </div>

                                <div class="hidden">
                                    <input type="text" name="user_id" id="user_id"
                                        value="{{ auth()->user()->id }}">
                                    <input type="text" name="clients_id" id="client_id"
                                        value="{{ auth()->user()->kerjasama ? auth()->user()->kerjasama->client_id : '' }}">
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-between mt-4 md:col-span-2">
                                    <button type="button" id="saveDraftBtn"
                                        class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Simpan
                                        Draft</button>
                                    <button type="button" id="submitReportBtn"
                                        class="px-4 py-2 ml-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Kirim Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Riwayat Laporan</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Card -->
                            @forelse ($allImages as $imgData)
                                <div class="overflow-hidden transition-shadow bg-white border rounded-lg shadow-sm border-slate-100 hover:shadow-md">
                                    <div class="p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h4 class="font-semibold text-md text-slate-900">{{ $imgData->created_at->isoformat('MMMM Y') }}</h4>
                                                <p class="text-sm text-slate-500">{{ $imgData->created_at->isoformat('d MMMM Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Gallery -->
                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                <img src="{{ URL::asset('/storage/'. $imgData->img_before) }}" alt="Before" class="object-cover w-full h-full">
                                            </div>
                                            <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                <img src="{{ URL::asset('/storage/'. $imgData->img_proccess) }}" alt="Process" class="object-cover w-full h-full">
                                            </div>
                                            <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                <img src="{{ URL::asset('/storage/'. $imgData->img_final) }}" alt="Final" class="object-cover w-full h-full">
                                            </div>
                                        </div>
                                        
                                        <!-- Note -->
                                        <div class="mb-3">
                                            <p class="text-sm text-slate-700">{{ $imgData->note }}</p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        {{-- <div class="flex justify-end space-x-2">
                                            <a href="#" class="text-sm text-blue-500 hover:text-blue-700">Lihat</a>
                                            <a href="#" class="text-sm text-slate-500 hover:text-slate-700">Unduh</a>
                                        </div> --}}
                                    </div>
                                </div>
                            @empty
                                
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script defer>
            $(document).ready(function() {
                const sidebarToggle = $('#sidebarToggle');
                const sidebar = $('#sidebar');
                const reportForm = $('#reportForm');
                const saveDraftBtn = $('#saveDraftBtn');
                const submitReportBtn = $('#submitReportBtn');
                const reportStatus = $('#reportStatus');
                const reportId = $('#reportId');
                const editDraftBtn = $('#editDraftBtn');
                const draftCardContainer = $('#draftCardContainer');
                const type = $('#type');

                // Image limit per month
                const IMAGE_LIMIT_PER_MONTH = 33;
                let imagesUploadedThisMonth = {{ $totalImageCount }}; // This would come from your backend
                let isEditMode = false;
                let draftData = null;

                // Store draft data for later use
                @if ($uploadDraft)
                    draftData = {!! json_encode($uploadDraft) !!};
                @endif

                // Update remaining images display
                updateRemainingImages();

                sidebarToggle.on('click', function() {
                    sidebar.toggleClass('-translate-x-full');
                });

                // Close sidebar when clicking outside on mobile
                $(document).on('click', function(event) {
                    const isClickInsideSidebar = sidebar.has(event.target).length > 0;
                    const isClickOnToggle = sidebarToggle.has(event.target).length > 0;

                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.hasClass('-translate-x-full')) {
                        sidebar.addClass('-translate-x-full');
                    }
                });

                // Handle edit draft button click
                editDraftBtn.on('click', function() {
                    if (draftData) {
                        loadDraftData(draftData);
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#reportForm').offset().top - 100
                        }, 500);
                    }
                });

                // Update the image upload handler with debouncing
                const debouncedImageHandler = debounce(function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (imagesUploadedThisMonth >= IMAGE_LIMIT_PER_MONTH) {
                            alert(`Anda telah mencapai batas upload gambar bulan ini (${IMAGE_LIMIT_PER_MONTH} gambar)`);
                            e.target.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const preview = $(`#preview${e.target.id.replace('image', '')}`);
                            preview.find('img').attr('src', event.target.result);
                            preview.removeClass('hidden');
                            preview.data('is-new-image', true);
                            
                            imagesUploadedThisMonth++;
                            updateRemainingImages();
                        };
                        reader.readAsDataURL(file);
                    }
                }, 300);

                // Apply debounced handler
                for (let i = 1; i <= 3; i++) {
                    $(`#image${i}`).on('change', debouncedImageHandler);
                }

                // Function to update remaining images display
                function updateRemainingImages() {
                    const remaining = IMAGE_LIMIT_PER_MONTH - imagesUploadedThisMonth;
                    const percentage = (imagesUploadedThisMonth / IMAGE_LIMIT_PER_MONTH) * 100;

                    $('#remainingImages').text(remaining);
                    // Use CSS transitions instead of direct style manipulation
                    const progressBar = $('#imageProgress');
                    progressBar.css('width', percentage + '%');
                    
                    // Use class toggling for color changes
                    if (remaining < 10) {
                        progressBar.removeClass('bg-purple-500 bg-yellow-500').addClass('bg-red-500');
                    } else if (remaining < 20) {
                        progressBar.removeClass('bg-purple-500 bg-red-500').addClass('bg-yellow-500');
                    } else {
                        progressBar.removeClass('bg-red-500 bg-yellow-500').addClass('bg-purple-500');
                    }
                }

                // Function to load draft data into the form
                function loadDraftData(draft) {
                    try {
                        // Reset the form first
                        reportForm[0].reset();
                        for (let i = 1; i <= 3; i++) {
                            $(`#preview${i}`).addClass('hidden');
                            $(`#preview${i} img`).attr('src', '');
                            $(`#preview${i}`).removeData('is-new-image');
                        }

                        isEditMode = true;

                        // Set the report ID for editing
                        reportId.val(draft.id || '');

                        // Set the content
                        $('#reportContent').val(draft.note || '');

                        // Store existing image paths in hidden fields
                        $('#existing_img_before').val(draft.img_before || '');
                        $('#existing_img_proccess').val(draft.img_proccess || '');
                        $('#existing_img_final').val(draft.img_final || '');

                        // Load images if they exist
                        const imageFields = ['img_before', 'img_proccess', 'img_final'];

                        imageFields.forEach((field, index) => {
                            const i = index + 1;
                            if (draft[field] && draft[field] !== 'none') {
                                // Construct the image URL - use the full URL from the draft if available
                                let imageUrl = draft[field];
                                
                                // If it's not a full URL, construct it
                                if (!imageUrl.startsWith('http')) {
                                    imageUrl = window.location.origin + `/storage/${imageUrl}`;
                                }

                                const preview = $(`#preview${i}`);
                                const img = preview.find('img');

                                // Set the image source and show the preview
                                loadImageWithLazyLoading(img[0], imageUrl);
                                preview.removeClass('hidden');
                                preview.data('is-new-image', false); // Mark as existing image
                                preview.data('original-path', draft[field]); // Store original path
                            }
                        });
                    } catch (e) {
                        console.error('Error in loadDraftData:', e);
                        alert('Error loading draft data. Please try again.');
                    }
                }

                // Use document fragments for batch DOM updates
                function showDraftCard(draft) {
                    const fragment = document.createDocumentFragment();
                    const draftCard = document.createElement('div');
                    draftCard.className = 'p-4 bg-white border rounded-lg shadow-sm border-slate-100';
                    draftCard.innerHTML = `
                        <div class="flex items-center">
                            <div class="p-2 text-blue-500 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-slate-500">Draft Tersedia</p>
                                <button id="editDraftBtn" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit Draft</button>
                            </div>
                        </div>
                    `;
                    
                    fragment.appendChild(draftCard);
                    draftCardContainer[0].innerHTML = '';
                    draftCardContainer[0].appendChild(fragment);
                    
                    // Use event delegation instead of re-attaching event handlers
                    draftCardContainer.off('click', '#editDraftBtn').on('click', '#editDraftBtn', function() {
                        if (draft) {
                            loadDraftData(draft);
                            $('html, body').animate({
                                scrollTop: $('#reportForm').offset().top - 100
                            }, 500);
                        }
                    });
                }

                // Function to hide draft card
                function hideDraftCard() {
                    draftCardContainer.empty();
                }

                // Add lazy loading for images
                function loadImageWithLazyLoading(imgElement, src) {
                    if ('loading' in HTMLImageElement.prototype) {
                        imgElement.loading = 'lazy';
                        imgElement.src = src;
                    } else {
                        // Fallback for browsers that don't support lazy loading
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    imgElement.src = src;
                                    observer.unobserve(imgElement);
                                }
                            });
                        });
                        observer.observe(imgElement);
                    }
                }

                // Add debounce function
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }

                // Use async/await for better readability and performance
                async function submitForm(formData, url, method) {
                    try {
                        const response = await $.ajax({
                            url: url,
                            type: method,
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                            }
                        });
                        return response;
                    } catch (error) {
                        throw error;
                    }
                }

                // Handle save draft button
                saveDraftBtn.on('click', async function() {
                    // Set status to 0 for draft
                    reportStatus.val('0');
                    type.val('draft');

                    // Validate content
                    const content = $('#reportContent').val();
                    if (!content.trim()) {
                        alert('Silakan isi konten laporan');
                        return;
                    }

                    // Create FormData object manually
                    const formData = new FormData();
                    
                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}');
                    
                    // Add form fields
                    formData.append('status', $('#reportStatus').val());
                    formData.append('id', $('#reportId').val());
                    formData.append('note', $('#reportContent').val());
                    formData.append('user_id', $('#user_id').val());
                    formData.append('clients_id', $('#client_id').val());
                    formData.append('type', 'draft');
                    
                    // Add existing image paths if in edit mode
                    if (isEditMode) {
                        formData.append('existing_img_before', $('#existing_img_before').val());
                        formData.append('existing_img_proccess', $('#existing_img_proccess').val());
                        formData.append('existing_img_final', $('#existing_img_final').val());
                    }
                    
                    // Add files if they exist
                    for (let i = 1; i <= 3; i++) {
                        const fileInput = $(`#image${i}`)[0];
                        if (fileInput.files.length > 0) {
                            formData.append(fileInput.name, fileInput.files[0]);
                        }
                    }
                    
                    // Determine the URL and method based on whether we're creating or updating
                    let url, method;
                    if (isEditMode && reportId.val()) {
                        url = `{{ url('upload-img-lap') }}/${reportId.val()}`;
                        method = 'POST'; // Use POST with _method override
                        formData.append('_method', 'PUT'); // Add method override
                    } else {
                        url = '{{ route('upload-images.draft') }}';
                        method = 'POST';
                    }

                    // Send AJAX request
                    try {
                        const response = await submitForm(formData, url, method);
                        alert('Draft berhasil disimpan!');
                        draftData = response.data;
                        showDraftCard(response.data);
                        // ... reset form ...
                        reportForm[0].reset();
                        reportId.val('');
                        isEditMode = false;
                        for (let i = 1; i <= 3; i++) {
                            $(`#preview${i}`).addClass('hidden');
                            $(`#preview${i} img`).attr('src', '');
                            $(`#preview${i}`).removeData('is-new-image');
                            $(`#preview${i}`).removeData('original-path');
                        }
                    } catch (xhr) {
                        // ... error handling ...
                        let errorMessage = 'Terjadi kesalahan saat menyimpan draft.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        alert(errorMessage);
                    }
                    // $.ajax({
                    //     url: url,
                    //     type: method,
                    //     data: formData,
                    //     processData: false,
                    //     contentType: false,
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') ||
                    //             '{{ csrf_token() }}'
                    //     },
                    //     success: function(response) {
                    //         alert('Draft berhasil disimpan!');
                            
                    //         // Update draft data with the response
                    //         draftData = response.data;
                            
                    //         // Show draft card
                    //         showDraftCard(response.data);

                    //         // Reset form
                    //         reportForm[0].reset();
                    //         reportId.val('');
                    //         isEditMode = false;
                    //         for (let i = 1; i <= 3; i++) {
                    //             $(`#preview${i}`).addClass('hidden');
                    //             $(`#preview${i} img`).attr('src', '');
                    //             $(`#preview${i}`).removeData('is-new-image');
                    //             $(`#preview${i}`).removeData('original-path');
                    //         }
                    //     },
                    //     error: function(xhr) {
                    //         let errorMessage = 'Terjadi kesalahan saat menyimpan draft.';
                    //         if (xhr.responseJSON && xhr.responseJSON.message) {
                    //             errorMessage = xhr.responseJSON.message;
                    //         } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    //             // Handle validation errors
                    //             const errors = Object.values(xhr.responseJSON.errors).flat();
                    //             errorMessage = errors.join('\n');
                    //         }
                    //         alert(errorMessage);
                    //     }
                    // });
                });

                // Handle submit report button
                submitReportBtn.on('click', function() {
                    // Set status to 1 for submitted report
                    reportStatus.val('1');
                    type.val('submit');

                    const content = $('#reportContent').val();

                    // Validate form
                    if (!content.trim()) {
                        alert('Silakan isi konten laporan');
                        return;
                    }

                    // Check if user has uploaded at least one image or has existing images
                    let hasImage = false;
                    for (let i = 1; i <= 3; i++) {
                        if ($(`#image${i}`)[0].files.length > 0 ||
                            ($(`#preview${i} img`).attr('src') && !$(`#preview${i}`).hasClass('hidden'))) {
                            hasImage = true;
                            break;
                        }
                    }

                    if (!hasImage) {
                        alert('Silakan upload minimal satu gambar pendukung');
                        return;
                    }

                    // Create FormData object manually
                    const formData = new FormData();
                    
                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}');
                    
                    // Add form fields
                    formData.append('status', $('#reportStatus').val());
                    formData.append('id', $('#reportId').val());
                    formData.append('note', $('#reportContent').val());
                    formData.append('user_id', $('#user_id').val());
                    formData.append('clients_id', $('#client_id').val());
                    formData.append('type', 'submit');
                    
                    // Add existing image paths if in edit mode
                    if (isEditMode) {
                        formData.append('existing_img_before', $('#existing_img_before').val());
                        formData.append('existing_img_proccess', $('#existing_img_proccess').val());
                        formData.append('existing_img_final', $('#existing_img_final').val());
                    }
                    
                    // Add files if they exist
                    for (let i = 1; i <= 3; i++) {
                        const fileInput = $(`#image${i}`)[0];
                        if (fileInput.files.length > 0) {
                            formData.append(fileInput.name, fileInput.files[0]);
                        }
                    }
                    
                    // Determine the URL and method based on whether we're creating or updating
                    let url, method;
                    if (isEditMode && reportId.val()) {
                        url = `{{ url('upload-img-lap') }}/${reportId.val()}`;
                        method = 'POST'; // Use POST with _method override
                        formData.append('_method', 'PUT'); // Add method override
                    } else {
                        url = '{{ url('upload-img-lap') }}';
                        method = 'POST';
                    }

                    // Send AJAX request
                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') ||
                                '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Laporan berhasil dikirim!');
                            
                            // Hide draft card since the draft has been submitted
                            hideDraftCard();

                            // Reset form
                            reportForm[0].reset();
                            reportId.val('');
                            isEditMode = false;
                            for (let i = 1; i <= 3; i++) {
                                $(`#preview${i}`).addClass('hidden');
                                $(`#preview${i} img`).attr('src', '');
                                $(`#preview${i}`).removeData('is-new-image');
                                $(`#preview${i}`).removeData('original-path');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat mengirim laporan.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                // Handle validation errors
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('\n');
                            }
                            alert(errorMessage);
                        }
                    });
                });

                // Function to remove image
                window.removeImage = function(index) {
                    const input = $(`#image${index}`);
                    const preview = $(`#preview${index}`);

                    // Only decrement if it's a newly uploaded image
                    if (preview.data('is-new-image') === true) {
                        imagesUploadedThisMonth--;
                        updateRemainingImages();
                    }

                    input.val('');
                    preview.find('img').attr('src', '');
                    preview.addClass('hidden');
                    preview.removeData('is-new-image');
                    
                    // If in edit mode and this was an existing image, clear the corresponding hidden field
                    if (isEditMode) {
                        const fieldName = `existing_img_${index === 1 ? 'before' : (index === 2 ? 'proccess' : 'final')}`;
                        $(`#${fieldName}`).val('');
                    }
                }

                // Function to edit a draft
                window.editDraft = function(draftId) {
                    // In a real application, this would load the draft data into the form
                    alert('Mengedit draft ' + draftId);

                }

                // Function to delete a draft
                window.deleteDraft = function(draftId) {
                    if (confirm('Apakah Anda yakin ingin menghapus draft ini?')) {
                        // In a real application, this would send a request to delete the draft
                        alert('Draft ' + draftId + ' telah dihapus');

                        // Remove the draft card from the DOM
                        const draftCard = $(`button[onclick="deleteDraft(${draftId})"]`).closest('.bg-white');
                        if (draftCard.length) {
                            draftCard.remove();
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>