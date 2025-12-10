<x-app-layout>
    @push('styles')
        .card-expandable {
        max-height: 6rem;
        overflow: hidden;
        }

        .card-expandable.expanded {
        max-height: 1000px; /* Large enough to show all content */
        }

        .card-expandable .expanded-content {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        }

        .card-expandable.expanded .expanded-content {
        opacity: 1;
        }

        .active {
        background-color: oklch(98.5% 0.001 106.423);
        }
    @endpush
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Create Report Form -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Upload Gambar Kegiatan</h3>
                            <button id="openModalRiwayat"
                                class="text-white transition-all duration-150 ease-in-out bg-blue-500 border-0 rounded-sm btn btn-md hover:bg-slate-50 hover:text-blue-500">Riwayat
                                Laporan</button>
                        </div>
                        <div id="draftCardContainer"></div>

                        <div class="p-4 bg-white border rounded-b-lg shadow-sm border-slate-100 sm:p-6">
                            <form id="reportForm">
                                @csrf
                                <input type="hidden" id="reportStatus" name="status" value="0">
                                <input type="hidden" name="type" value="">
                                <input type="hidden" id="reportId" name="id" value="">
                                <input type="hidden" name="_method" value="POST">
                                <!-- Hidden fields to store existing image paths -->
                                <input type="hidden" id="existing_img_before" name="existing_img_before"
                                    value="">
                                <input type="hidden" id="existing_img_proccess" name="existing_img_proccess"
                                    value="">
                                <input type="hidden" id="existing_img_final" name="existing_img_final" value="">

                                <div class="space-y-6">
                                    <!-- Image Uploads Section -->
                                    <div>
                                        <label class="block mb-3 text-sm font-medium text-slate-700">Gambar (maks
                                            3)</label>
                                        <div class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4">
                                            @php
                                                $imageConfig = [
                                                    [
                                                        'id' => 'image1',
                                                        'name' => 'img_before',
                                                        'label' => 'Before',
                                                    ],
                                                    [
                                                        'id' => 'image2',
                                                        'name' => 'img_proccess',
                                                        'label' => 'Process',
                                                    ],
                                                    [
                                                        'id' => 'image3',
                                                        'name' => 'img_final',
                                                        'label' => 'After',
                                                    ],
                                                ];

                                                $acceptedTypes =
                                                    '.gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic,.jpg,.jpeg';

                                                $uploadIcon = '<svg class="w-5 h-5 mb-1 sm:w-6 sm:h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						                        </svg>';

                                                $deleteIcon = '<svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						                        </svg>';
                                            @endphp

                                            @foreach ($imageConfig as $index => $config)
                                                <div class="relative">
                                                    <input type="file" id="{{ $config['id'] }}"
                                                        name="{{ $config['name'] }}" accept="{{ $acceptedTypes }}"
                                                        class="hidden">
                                                    <label for="{{ $config['id'] }}"
                                                        class="flex flex-col items-center justify-center w-full h-24 transition-colors border-2 border-dashed rounded-lg cursor-pointer sm:h-28 md:h-32 lg:h-36 border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                        {!! $uploadIcon !!}
                                                        <span
                                                            class="text-[10px] sm:text-xs text-slate-500 text-center px-1">+
                                                            {{ $config['label'] }}</span>
                                                    </label>
                                                    <div id="preview{{ $index + 1 }}"
                                                        class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                        <img src="" alt="Preview"
                                                            class="object-cover w-full h-full lazy-load">
                                                        <button type="button"
                                                            class="absolute p-1 sm:p-1.5 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                            onclick="removeImage({{ $index + 1 }})">
                                                            {!! $deleteIcon !!}
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Textarea for content -->
                                    <div>
                                        <label for="reportContent"
                                            class="block mb-2 text-sm font-medium text-slate-700">Isi Keterangan</label>
                                        <textarea id="reportContent" name="note" rows="4"
                                            class="w-full px-3 py-2 text-sm bg-white border rounded-lg resize-none sm:px-4 sm:py-3 sm:text-base text-slate-900 border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tulis isi keterangan di sini... (format: 'nama kegiatan - nama area')"></textarea>
                                    </div>

                                    <!-- Hidden fields -->
                                    <div class="hidden">
                                        <input type="text" name="user_id" id="user_id"
                                            value="{{ auth()->user()->id }}">
                                        <input type="text" name="clients_id" id="client_id"
                                            value="{{ auth()->user()->kerjasama ? auth()->user()->kerjasama->client_id : '' }}">
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
                                        <button type="button" id="saveDraftBtn"
                                            class="w-full sm:w-auto px-4 py-2.5 text-sm sm:text-base font-medium text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            Simpan Draft
                                        </button>
                                        <button type="button" id="submitReportBtn"
                                            class="w-full sm:w-auto px-4 py-2.5 text-sm sm:text-base font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Kirim Laporan
                                        </button>
                                        <button class="hidden btn btnLoading">
                                            <span class="loading loading-spinner"></span>
                                            loading
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <!-- Modal Overlay -->
                <div id="modalRiwayat" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-md">
                    <div class="flex items-center justify-center min-h-screen px-4 py-6">
                        <!-- Modal Content -->
                        <div class="relative w-full max-w-6xl transition-all transform bg-white rounded-lg shadow-xl">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                                <h3 class="text-xl font-semibold text-slate-900">Riwayat Laporan</h3>
                                <button id="closeModalRiwayat"
                                    class="transition-colors text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 max-h-[70vh] overflow-y-auto">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3" id="historyGrid">
                                    <!-- Card -->
                                    @forelse ($allImages as $imgData)
                                        <div class="overflow-hidden transition-all duration-300 ease-in-out bg-white border rounded-lg shadow-sm cursor-pointer border-slate-100 hover:shadow-md card-expandable"
                                            data-card-id="{{ $imgData->id }}">
                                            <div class="p-4">
                                                <!-- Collapsed View (Always Visible) -->
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-md text-slate-900">
                                                            {{ $imgData->note }}</h4>
                                                        <p class="text-sm text-slate-500">
                                                            {{ $imgData->created_at->isoformat('d MMMM Y') }}</p>
                                                        <p class="text-xs text-slate-500 truncate max-w-[300px]">Di
                                                            Upload Oleh : {{ $imgData->user->nama_lengkap }}</p>
                                                    </div>
                                                    <svg class="w-5 h-5 transition-transform duration-300 text-slate-400 expand-icon"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>

                                                <!-- Expanded Content (Hidden by default) -->
                                                <div class="mt-4 expanded-content">
                                                    <!-- Image Gallery -->
                                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                                        <div
                                                            class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                            <img src="{{ URL::asset('/storage/' . $imgData->img_before) }}"
                                                                alt="Before" class="object-cover w-full h-full">
                                                        </div>
                                                        <div
                                                            class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                            <img src="{{ $imgData->img_proccess ? URL::asset('/storage/' . $imgData->img_proccess) : 'https://placehold.co/400x400?text=Kosong' }}"
                                                                alt="Process" class="object-cover w-full h-full">
                                                        </div>
                                                        <div
                                                            class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                                            <img src="{{ URL::asset('/storage/' . $imgData->img_final) }}"
                                                                alt="Final" class="object-cover w-full h-full">
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
                                        </div>
                                    @empty
                                        <div class="py-8 text-center col-span-full text-slate-500"
                                            id="emptyHistoryMessage">
                                            Belum ada riwayat laporan
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex justify-end p-6 border-t border-slate-200">
                                <button id="closeModalRiwayatFooter"
                                    class="px-4 py-2 text-sm font-medium transition-colors rounded-lg text-slate-700 bg-slate-100 hover:bg-slate-200">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        {{-- Script Modal --}}
        <script>
            // Get elements
            const modal = document.getElementById('modalRiwayat');
            const openBtn = document.getElementById('openModalRiwayat');
            const closeBtn = document.getElementById('closeModalRiwayat');
            const closeFooterBtn = document.getElementById('closeModalRiwayatFooter');

            // Open modal
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            // Close modal function
            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                // Collapse all cards when modal closes
                const expandableCards = document.querySelectorAll('.card-expandable');
                expandableCards.forEach(card => {
                    collapseCard(card);
                });
            };

            // Close modal on button click
            closeBtn.addEventListener('click', closeModal);
            closeFooterBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close modal on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Function to collapse a card
            function collapseCard(card) {
                const icon = card.querySelector('.expand-icon');
                card.classList.remove('expanded', 'active');
                card.style.maxHeight = '6rem';
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            }

            // Function to expand a card
            function expandCard(card) {
                const icon = card.querySelector('.expand-icon');
                card.classList.add('expanded', 'active');
                card.style.maxHeight = card.scrollHeight + 'px';
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                }
            }

            // Expandable cards functionality - Only one at a time
            document.addEventListener('DOMContentLoaded', () => {
                const expandableCards = document.querySelectorAll('.card-expandable');

                expandableCards.forEach(card => {
                    card.addEventListener('click', function(e) {
                        // Prevent collapse if clicking on links or buttons inside
                        if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                            return;
                        }

                        const isExpanded = this.classList.contains('expanded');

                        // Collapse all other cards first
                        expandableCards.forEach(otherCard => {
                            if (otherCard !== this) {
                                collapseCard(otherCard);
                            }
                        });

                        // Toggle current card
                        if (isExpanded) {
                            collapseCard(this);
                        } else {
                            expandCard(this);
                        }
                    });
                });
            });
        </script>
        {{-- End Script Modal --}}

        <script defer>
            $(document).ready(function() {
                const reportForm = $('#reportForm');
                const saveDraftBtn = $('#saveDraftBtn');
                const submitReportBtn = $('#submitReportBtn');
                const reportStatus = $('#reportStatus');
                const reportId = $('#reportId');
                const editDraftBtn = $('#editDraftBtn');
                const draftCardContainer = $('#draftCardContainer');
                const type = $('#type');
                const loading = $(".btnLoading");
                let isLoading = false;
                let prevLoading = isLoading;

                // Image limit per month
                const IMAGE_LIMIT_PER_MONTH = 33;
                let imagesUploadedThisMonth = {{ $totalImageCount }}; // This would come from your backend
                let isEditMode = false;
                let draftData = null;
                let firstDraft = null;

                // Store draft data for later use
                @if ($uploadDraft)
                    draftData = @json($uploadDraft);
                    firstDraft = draftData.sort((a, b) =>
                        new Date(a.created_at) - new Date(b.created_at)
                    )[0];
                    showDraftCard(firstDraft)
                @endif

                // Update remaining images display
                updateRemainingImages();

                // Fungsi untuk memeriksa dan memperbarui tampilan draft
                function checkAndUpdateDraftDisplay() {
                    fetch('{{ route('v1.count.data') }}', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            const draftCardContainer = $('#draftCardContainer');

                            if (res.data > 0) {
                                // Jika ada draft, tampilkan kartu draft
                                if (!draftCardContainer.children().length) {
                                    // Buat kartu draft sederhana jika belum ada
                                    const draftCard = document.createElement('div');
                                    draftCard.className =
                                        'p-4 bg-white border rounded-t-lg shadow-sm border-slate-100';
                                    draftCard.innerHTML = `
                                <div class="flex items-center">
                                    <div class="p-2 text-blue-500 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-slate-500">Draft Tersedia (${res.data})</p>
                                        <button id="editDraftBtn" class="px-3 py-1 mt-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit Draft</button>
                                    </div>
                                </div>
                            `;
                                    draftCardContainer.html(draftCard);

                                    // Tambahkan event listener untuk tombol edit
                                    $('#editDraftBtn').on('click', function() {
                                        // Ambil draft pertama yang tersedia
                                        fetch('{{ route('upload-img-lap.index') }}', {
                                                headers: {
                                                    'Accept': 'application/json'
                                                }
                                            })
                                            .then(res => res.json())
                                            .then(response => {
                                                if (response.draft) {
                                                    loadDraftData(response.draft);
                                                    $('html, body').animate({
                                                        scrollTop: $('#reportForm').offset()
                                                            .top - 100
                                                    }, 500);
                                                }
                                            })
                                            .catch(err => console.error(err));
                                    });
                                } else {
                                    // Update jumlah draft jika kartu sudah ada
                                    $('#draftCardContainer p.text-sm').text(`Draft Tersedia (${res.data})`);
                                }
                            } else {
                                // Jika tidak ada draft, sembunyikan kartu draft
                                draftCardContainer.empty();
                            }
                        })
                        .catch(err => console.error(err));
                }


                function getCount() {
                    checkAndUpdateDraftDisplay();
                }
                getCount()

                function setLoading(val) {
                    if (val !== prevLoading) {
                        onLoadingChanged(val, prevLoading);
                        prevLoading = val;
                    }
                    isLoading = val;
                }

                function onLoadingChanged(newVal, oldVal) {
                    if (newVal) {
                        submitReportBtn.addClass("hidden");
                        loading.removeClass("hidden");
                    } else {
                        submitReportBtn.removeClass("hidden");
                        loading.addClass("hidden");
                    }
                }

                submitReportBtn.on('click', function() {
                    setLoading(true);
                    if (!navigator.onLine) {
                        setLoading(false);
                        saveDraftOffline()
                        alert(
                        "Anda offline. Data disimpan di perangkat dan akan dikirim otomatis saat online.");
                        return;
                    }
                });

                function saveDraftOffline() {
                    const draft = {
                        id: Date.now(),
                        note: $('#reportContent').val(),
                        img_before: $('#image1')[0].files[0] ? $('#image1')[0].files[0] : null,
                        img_proccess: $('#image2')[0].files[0] ? $('#image2')[0].files[0] : null,
                        img_final: $('#image3')[0].files[0] ? $('#image3')[0].files[0] : null,
                    };

                    const request = indexedDB.open("reportDB", 1);

                    request.onupgradeneeded = function(event) {
                        const db = event.target.result;
                        db.createObjectStore("drafts", {
                            keyPath: "id"
                        });
                    };

                    request.onsuccess = function(event) {
                        const db = event.target.result;
                        const tx = db.transaction("drafts", "readwrite");
                        tx.objectStore("drafts").put(draft);
                    };
                }

                function detectBrowser() {
                    const ua = navigator.userAgent;

                    if (typeof InstallTrigger !== 'undefined') {
                        return "Firefox";
                    }

                    if (ua.includes("Edg/")) {
                        return "Edge";
                    }

                    if (!!window.chrome && !ua.includes("Edg/")) {
                        return "Chrome";
                    }

                    if (/^((?!chrome|android).)*safari/i.test(ua)) {
                        return "Safari";
                    }

                    return "Unknown";
                }

                window.addEventListener("online", () => {
                    if (detectBrowser() == 'Chrome' || detectBrowser() == 'Edge') {
                        navigator.serviceWorker.ready.then(reg => {
                            reg.sync.register("sync-reports");
                        });
                    } else {
                        syncDrafts()
                    }
                });

                async function syncDrafts() {
                    const db = await idb.openDB('reportDB', 1);
                    const drafts = await db.getAll('drafts');

                    for (let draft of drafts) {
                        try {

                            const form = new FormData();
                            form.append('note', draft.note);

                            // load kembali file besar dari storage lokal
                            form.append('img_before', await loadLocalFile(draft.img_before));
                            form.append('img_proccess', await loadLocalFile(draft.img_proccess));
                            form.append('img_final', await loadLocalFile(draft.img_final));

                            await fetch('/upload-img-lap', {
                                method: 'POST',
                                body: form
                            });

                            await db.delete('drafts', draft.id);

                        } catch (e) {
                            console.log("Sync gagal, coba lagi nanti");
                            return; // stop sync
                        }
                    }
                }

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
                            alert(
                                `Anda telah mencapai batas upload gambar bulan ini (${IMAGE_LIMIT_PER_MONTH} gambar)`);
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
                    draftCard.className = 'p-4 bg-white border rounded-t-lg shadow-sm border-slate-100';
                    draftCard.innerHTML = `
                        <div class="flex items-center">
                            <div class="p-2 text-blue-500 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-slate-500" id="draftCount">Draft Tersedia</p>
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

                // Function to create image card HTML
                function createImageCard(imgData) {
                    // Format dates
                    const createdDate = new Date(imgData.created_at);
                    const monthYear = createdDate.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                    const dayMonthYear = createdDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    // Build the image URLs
                    const baseUrl = window.location.origin + '/storage/';
                    const imgBefore = imgData.img_before ? baseUrl + imgData.img_before :
                        'https://placehold.co/400x400?text=Kosong';
                    const imgProcess = imgData.img_proccess ? baseUrl + imgData.img_proccess :
                        'https://placehold.co/400x400?text=Kosong';
                    const imgFinal = imgData.img_final ? baseUrl + imgData.img_final :
                        'https://placehold.co/400x400?text=Kosong';

                    return `
                        <div class="overflow-hidden transition-shadow bg-white border rounded-lg shadow-sm border-slate-100 hover:shadow-md">
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold text-md text-slate-900">${monthYear}</h4>
                                        <p class="text-sm text-slate-500">${dayMonthYear}</p>
                                    </div>
                                </div>
                                
                                <!-- Image Gallery -->
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                        <img src="${imgBefore}" alt="Before" class="object-cover w-full h-full">
                                    </div>
                                    <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                        <img src="${imgProcess}" alt="Process" class="object-cover w-full h-full">
                                    </div>
                                    <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                        <img src="${imgFinal}" alt="Final" class="object-cover w-full h-full">
                                    </div>
                                </div>
                                
                                <!-- Note -->
                                <div class="mb-3">
                                    <p class="text-sm text-slate-700">${imgData.note}</p>
                                </div>
                            </div>
                        </div>
                    `;
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
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') ||
                                    '{{ csrf_token() }}'
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
                    setLoading(true);
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
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content') ||
                        '{{ csrf_token() }}');

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
                        checkAndUpdateDraftDisplay();
                        // ... reset form ...
                        setLoading(false);
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
                        console.log(xhr)
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
                });

                // Handle submit report button
                submitReportBtn.on('click', function() {
                    // Set status to 1 for submitted report
                    setLoading(true);
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
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content') ||
                        '{{ csrf_token() }}');

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
                            setLoading(false);
                            alert('Laporan berhasil dikirim!');

                            // Periksa kembali keberadaan draft setelah berhasil mengirim laporan
                            checkAndUpdateDraftDisplay();

                            if (draftData) {
                                showDraftCard(firstDraft);
                            } else {
                                hideDraftCard();
                            }

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

                            if (response.data) {
                                $('#emptyHistoryMessage').remove();

                                const newCard = createImageCard(response.data);
                                $('#historyGrid').prepend(newCard);

                                imagesUploadedThisMonth = response.totalImageCount ||
                                    imagesUploadedThisMonth;
                                updateRemainingImages();
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
                        const fieldName =
                            `existing_img_${index === 1 ? 'before' : (index === 2 ? 'proccess' : 'final')}`;
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
