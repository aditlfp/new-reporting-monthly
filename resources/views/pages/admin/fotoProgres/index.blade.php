<x-app-layout title="Data Photo Progress" subtitle="Menampilkan Data Photo Yang Sudah Ada">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-3 mt-16 overflow-y-auto md:p-6 md:mt-0">
            <div class="container px-3 py-6 mx-auto md:px-4 md:py-8">
                <div class="m-3 bg-white shadow-xl md:m-5 card">
                    <div class="card-body">
                        <div class="flex flex-col gap-3 mb-4 md:gap-4 md:mb-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl md:text-2xl card-title">Data Photo Progress</h2>
                            </div>

                            <!-- Filter Section -->
                            <div class="flex flex-col gap-3 rounded-lg">
                                <div class="flex items-center form-control gap-x-2">
                                    <div class="flex flex-col">
                                        <label for="mitra" class="text-xs font-medium required label md:text-sm label-text">Mitra</label>
                                        <select name="mitraFilter" id="mitraFilter" class="rounded-sm select select-bordered select-xs md:select-sm">
                                            <option selected value="">All Mitra</option>
                                            @foreach ($client as $cl)
                                                <option value="{{ $cl->id }}">{{ ucwords(strtolower($cl->name)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="filterUserContainer">
                                        <label for="userFilter" class="text-xs font-medium required label md:text-sm label-text">User</label>
                                        <select name="userFilter" id="userFilter" class="rounded-sm select select-bordered select-xs md:select-sm" disabled>
                                            <option selected value="">Pilih Mitra Terlebih Dahulu</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="label">
                                            <span class="text-xs font-medium required md:text-sm label-text">Filter Bulan</span>
                                        </label>
                                        <select id="monthFilter" class="rounded-sm select select-bordered select-xs md:select-sm">
                                            <option selected value="">All Months</option>
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

                                    <div>
                                        <label class="label">
                                            <span class="text-xs font-medium required md:text-sm label-text">Filter Tahun</span>
                                        </label>
                                        <select id="yearFilter" class="rounded-sm select select-bordered select-xs md:select-sm">
                                            <option selected value="">All Year</option>
                                            @for ($year = now()->year; $year >= 2024; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button id="applyFilter" class="text-blue-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-blue-500/20 hover:bg-blue-600 hover:text-white">Apply Filter</button>
                                    <button id="clearFilter" class="text-red-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-red-500/20 hover:bg-red-600 hover:text-white">Clear</button>
                                    <button id="generatePdf" class="text-green-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-green-500/20 hover:bg-green-600 hover:text-white">
                                       <i class="mr-1 text-xs ri-download-cloud-2-line md:text-sm"></i><span class="hidden sm:inline">Download PDF</span>
                                    </button>
                                </div>
                                <divider class="border-t border-gray-100"/>
                            </div>

                            <!-- Selection Controls -->
                            <div class="flex gap-2">
                                <button id="selectAll" class="text-blue-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-blue-500/20 hover:bg-blue-600 hover:text-white">Select All</button>
                                <button id="deselectAll" class="text-red-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-red-500/20 hover:bg-red-600 hover:text-white">Deselect All</button>
                            </div>

                            <div id="pdf-progress-container" class="hidden" style="font-family: Arial, sans-serif; margin-top: 20px;">
                                <h3>PDF Generation Progress</h3>
                                <progress id="pdf-progress" class="w-56 progress progress-success" value="0" max="100"></progress>
                            </div>

                        </div>

                        <div class="overflow-x-auto">
                            <table class="table w-full text-xs table-zebra md:text-sm">
                                <thead>
                                    <tr>
                                        <th class="p-2 md:p-3">
                                            <input type="checkbox" id="headerCheckbox" class="checkbox checkbox-xs">
                                        </th>
                                        <th class="p-2 md:p-3">ID</th>
                                        <th class="p-2 md:p-3">Nama Mitra</th>
                                        <th class="p-2 md:p-3">Nama User</th>
                                        <th class="hidden p-2 md:p-3 sm:table-cell">Before</th>
                                        <th class="hidden p-2 md:p-3 md:table-cell">Progress</th>
                                        <th class="hidden p-2 md:p-3 lg:table-cell">After</th>
                                        <th class="hidden p-2 md:p-3 md:table-cell">Keterangan</th>
                                        <th class="p-2 text-center md:p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <span class="loading loading-spinner loading-lg"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="pagination" class="flex justify-center mt-4 md:mt-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <dialog id="fotoModal" class="modal">
        <div class="w-11/12 max-w-2xl p-4 modal-box md:w-10/11 md:p-6">
            <h3 class="mb-3 text-lg font-bold md:mb-4 md:text-xl" id="modalTitle">Edit Photo Progress</h3>

            <form id="photoForm" method="dialog">
                <input type="hidden" id="photoId" name="id">
                <input type="hidden" id="formMethod" value="POST">

                <div class="w-full mb-3 md:mb-4 form-control">
                    <label class="label">
                        <span class="text-xs label-text md:text-sm">Nama Mitra <span class="text-error">*</span></span>
                    </label>
                    <select name="client_id" id="client_id" class="w-full select select-bordered select-xs md:select-sm" required>
                        <option value="" disabled selected>Select Client</option>
                        @foreach ($client as $cl)
                            <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                        @endforeach
                    </select>
                    <label class="hidden label" id="error-client_id">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-3 md:mb-4 form-control">
                    <label class="label">
                        <span class="text-xs label-text md:text-sm">Before Image</span>
                    </label>
                    <input type="file" class="w-full file-input file-input-bordered file-input-xs md:file-input-sm" id="img_before"
                        name="img_before">
                    <div id="current-img_before" class="mt-2"></div>
                    <label class="hidden label" id="error-img_before">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-3 md:mb-4 form-control">
                    <label class="label">
                        <span class="text-xs label-text md:text-sm">Progress Image</span>
                    </label>
                    <input type="file" class="w-full file-input file-input-bordered file-input-xs md:file-input-sm" id="img_proccess"
                        name="img_proccess">
                    <div id="current-img_proccess" class="mt-2"></div>
                    <label class="hidden label" id="error-img_proccess">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-3 md:mb-4 form-control">
                    <label class="label">
                        <span class="text-xs label-text md:text-sm">After Image</span>
                    </label>
                    <input type="file" class="w-full file-input file-input-bordered file-input-xs md:file-input-sm" id="img_final" name="img_final">
                    <div id="current-img_final" class="mt-2"></div>
                    <label class="hidden label" id="error-img_final">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-3 md:mb-4 form-control">
                    <label class="label">
                        <span class="text-xs label-text md:text-sm">Keterangan</span>
                    </label>
                    <textarea class="w-full h-20 md:h-24 textarea textarea-bordered textarea-xs md:textarea-sm" id="note" name="note"></textarea>
                    <label class="hidden label" id="error-note">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-xs md:btn-sm btn-ghost" id="btnClose">Close</button>
                    <button type="submit" class="btn btn-xs md:btn-sm btn-primary" id="btnSave">
                        <span class="hidden loading loading-spinner loading-sm" id="btnSpinner"></span>
                        Save
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- Enhanced Image Preview Modal --}}
    <div id="imagePreviewModal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-90">
        <div class="relative max-w-6xl max-h-[90vh] w-full">
            <!-- Top controls -->
            <div class="absolute left-0 right-0 z-20 flex justify-center top-4">
                <div class="flex gap-2 p-1 bg-black rounded-full bg-opacity-70">
                    <button id="closeImagePreview"
                        class="p-2 text-white transition-colors rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button id="rotateImage" class="p-2 text-white transition-colors rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <button id="zoomInImage" class="p-2 text-white transition-colors rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </button>
                    <button id="zoomOutImage" class="p-2 text-white transition-colors rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                        </svg>
                    </button>
                    <button id="downloadImage"
                        class="p-2 text-white transition-colors rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation buttons -->
            <button id="prevImage"
                class="absolute z-10 p-2 text-white transition-all transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full md:p-3 left-2 md:left-4 top-1/2 hover:bg-opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="nextImage"
                class="absolute z-10 p-2 text-white transition-all transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full md:p-3 right-2 md:right-4 top-1/2 hover:bg-opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Image container -->
            <div class="flex items-center justify-center w-full h-[75vh] md:h-[85vh] overflow-hidden">
                <img id="previewImage" src="" alt="Preview"
                    class="object-contain max-w-full max-h-full transition-transform duration-300">
            </div>

            <!-- Image info -->
            <div id="imageInfo" class="absolute left-0 right-0 text-xs text-center text-white md:text-sm bottom-4">
                <span id="imageCounter"></span>
            </div>
        </div>
    </div>

    <audio id="notify-sound" src="{{ asset('/sound/done.mp3') }}" preload="auto"></audio>

    @push('scripts')
        <script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.13/dist/html2canvas-pro.min.js"></script>
        <script src="{{ asset('js/fotoPages.js') }}"></script>
        <script>

            function askNotificationPermission() {
                if (!("Notification" in window)) {
                    console.warn("This browser does not support notifications.");
                    return;
                }

                if (Notification.permission === "granted") {
                    return;
                } 

                if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(permission => {
                        console.log("Notification permission:", permission);
                    });
                }
            }

            askNotificationPermission();

            function showNotification(title, options) {
                if (Notification.permission === "granted") {
                    new Notification(title, options);
                }
            }


            function queueProgress(pageIndex, totalPages) {
                const list = document.getElementById('pdf-progress-list');
                const listItem = document.createElement('li');
                listItem.id = `pdf-page-${pageIndex}`;
                listItem.textContent = `Queued page ${pageIndex} of ${totalPages}`;
                list.appendChild(listItem);
            }

            function setProgress(percent) {
                const bar = document.getElementById('pdf-progress');
                $('#pdf-progress-container').removeClass('hidden');
                if (bar) {
                    bar.value = percent;
                }
            }


            function updateProgress(pageIndex, status) {
                const listItem = document.getElementById(`pdf-page-${pageIndex}`);
                $('#pdf-progress-container').removeClass('hidden');
                if (listItem) {
                    listItem.textContent = `${status} page ${pageIndex}`;
                }
            }

            function clearProgressQueue() {
                const bar = document.getElementById('pdf-progress');
                if (bar) {
                    bar.value = 0;
                }
            }


            $(document).ready(function() {
                let currentPage = 1;
                let currentMonth = '';
                let currentRequest = null;
                const modal = document.getElementById('fotoModal');
                const imageModal = document.getElementById('imagePreviewModal');
                const pdfViewerModal = document.getElementById('pdfViewerModal');

                // Image preview state
                let imagePreviewState = {
                    images: [],
                    currentIndex: 0,
                    rotation: 0,
                    zoom: 1
                };

                const ASSET_URL = "{{ asset('storage') }}";
                const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                function init() {
                    loadData();
                    setupImagePreviewControls();
                    setupFilterControls();
                    setupCheckboxControls();
                    setupPdfGeneration();
                }

                // Load data (defensive)
                function loadData(page = 1, month = null, year = null, mitra = null, user = null) {
                    // Abort previous request if any
                    if (currentRequest && currentRequest.readyState !== 4) {
                        currentRequest.abort();
                    }

                    // show loader or disable controls if you want
                    $('#tableContainer').html('Loading...');

                    currentRequest = $.ajax({
                        url: '{{ route('admin.upload.index') }}',
                        type: 'GET',
                        data: {
                            page: page,
                            month,
                            year,
                            mitra,
                            user
                        },
                        dataType: 'json',
                        success: function (response) {
                            // defensive checks
                            if (!response) {
                                console.error('Empty JSON response');
                                Notify('Empty response from server', null, null, 'error');
                                return;
                            }

                            if (response.status) {
                                renderTable(response.data.data);
                                renderPagination(response.data);
                                currentPage = page;
                            } else {
                                // server returned JSON but with status false
                                $('#tableContainer').html('<p>No data</p>');
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if (textStatus === 'abort') {
                                console.log('Previous request aborted');
                                return;
                            }

                            // show useful debugging info
                            {{-- console.error('AJAX error', textStatus, errorThrown); --}}
                            {{-- console.log('Response text:', xhr); --}}
                            Notify('Error loading data', null, null, 'error');

                            // if server returned HTML (login page / error page):
                            // inspect xhr.responseText in devtools
                        }
                    });
                }

                // Render table
                function renderTable(data) {
                    if (data.length === 0) {
                        $('#tableBody').html(
                            '<tr><td colspan="8" class="py-8 text-center text-base-content/60">No data available</td></tr>'
                        );
                        return;
                    }

                    const html = data.map((item, index) => `
                        <tr class="hover">
                            <td class="px-2 py-2 md:px-3">
                                <input type="checkbox" class="row-checkbox checkbox checkbox-xs" data-id="${item.id}">
                            </td>
                            <td class="px-2 py-2 md:px-3">${index + 1}</td>
                            <td class="px-2 py-2 md:px-3">
                                <div class="max-w-[100px] md:max-w-xs truncate" title="${item.clients?.name || '-'}">
                                    ${kapitalName(item.clients?.name || '-')}
                                </div>
                            </td>
                            <td class="px-2 py-2 md:px-3">
                                <div class="max-w-[100px] md:max-w-xs truncate" title="${item.user?.nama_lengkap || '-'}">
                                    ${kapitalName(item.user?.nama_lengkap || '-')}
                                </div>
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 sm:table-cell">
                                ${renderImageCell(item.img_before, 'Before')}
                            </td>
                            <td class="hidden px-2 py-2 text-center md:px-3 md:table-cell">
                                ${renderImageCell(item.img_proccess, '-')}
                            </td>
                            <td class="hidden px-2 py-2 text-center md:px-3 lg:table-cell">
                                ${renderImageCell(item.img_final, '-')}
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 md:table-cell">
                                <div class="max-w-[100px] md:max-w-xs truncate" title="${item.note || '-'}">
                                    ${item.note || '-'}
                                </div>
                            </td>
                            <td class="px-2 py-2 md:px-3">
                                <div class="flex justify-center gap-1 md:gap-2">
                                    <button class="text-yellow-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-yellow-500/20 hover:bg-yellow-600 hover:text-white btn-edit" data-id="${item.id}">
                                        <i class="text-xs ri-settings-3-line md:text-sm"></i>
                                    </button>
                                    <button class="text-red-600 border-0 rounded-sm btn btn-xs md:btn-sm bg-red-500/20 hover:bg-red-600 hover:text-white btn-delete" data-id="${item.id}">
                                        <i class="text-xs md:text-sm ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');

                    $('#tableBody').html(html);
                }

                // Helper function to render image cell
                function renderImageCell(imagePath, label) {
                    if (!imagePath) {
                        return `<img src="https://placehold.co/600x400?text=Kosong" class="max-w-[60px] md:max-w-[100px] opacity-50" />`;
                    }

                    const fullUrl = window.location.origin + '/storage/' + imagePath;
                    return `<img src="${fullUrl}" 
                             class="max-w-[60px] md:max-w-[100px] cursor-pointer hover:opacity-80 transition-opacity" 
                             onclick="showImagePreview('${fullUrl}')" 
                             alt="${label}" />`;
                }

                // Render pagination
                function renderPagination(data) {
                    if (data.last_page <= 1) {
                        $('#pagination').html('');
                        return;
                    }

                    let html = '<div class="join">';

                    // Previous button
                    html += `<button class="join-item btn btn-xs ${data.current_page === 1 ? 'btn-disabled' : ''}" data-page="${data.current_page - 1}">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>`;

                    // Page numbers
                    for (let i = 1; i <= data.last_page; i++) {
                        if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page +
                                2)) {
                            html +=
                                `<button class="join-item btn btn-xs ${i === data.current_page ? 'btn-active' : ''}" data-page="${i}">${i}</button>`;
                        } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                            html += '<button class="join-item btn btn-xs btn-disabled">...</button>';
                        }
                    }

                    // Next button
                    html += `<button class="join-item btn btn-xs ${data.current_page === data.last_page ? 'btn-disabled' : ''}" data-page="${data.current_page + 1}">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>`;

                    html += '</div>';
                    $('#pagination').html(html);
                }

                // Setup filter controls
                function setupFilterControls() {
                    // Handle mitra filter change
                    $('#mitraFilter').change(function() {
                        const mitraId = $(this).val();
                        
                        if (mitraId) {
                            // Fetch users for selected mitra
                            $.ajax({
                                url: '{{ route('admin.upload.get-users') }}',
                                type: 'GET',
                                data: { mitra_id: mitraId },
                                dataType: 'json',
                                beforeSend: function() {
                                    // Show loading state
                                    $('#userFilter').html('<option value="">Loading...</option>');
                                },
                                success: function(response) {
                                    if (response.status) {
                                        populateUserFilter(response.data);
                                    } else {
                                        console.error('Error in response:', response);
                                        $('#userFilter').html('<option selected value="">Error loading users</option>');
                                        $('#userFilter').prop('disabled', true);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX error:', {
                                        status: status,
                                        error: error,
                                        responseText: xhr.responseText
                                    });
                                    
                                    // Reset user filter on error
                                    $('#userFilter').html('<option selected value="">Error loading users</option>');
                                    $('#userFilter').prop('disabled', true);
                                }
                            });
                        } else {
                            // Reset user filter
                            $('#userFilter').html('<option selected value="">Select Mitra First</option>');
                            $('#userFilter').prop('disabled', true);
                        }
                    });

                    $('#clearFilter').click(function() {
                        $('#monthFilter').val('');
                        $('#yearFilter').val('');
                        $('#mitraFilter').val('');
                        $('#userFilter').html('<option selected value="">Select Mitra First</option>');
                        $('#userFilter').prop('disabled', true);
                        currentMonth = '';
                        loadData(1);
                    });
                }

                // Populate user filter dropdown
                function populateUserFilter(users) {
                    let html = '<option value="">All Users</option>';
                    
                    if (users && users.length > 0) {
                        users.forEach(user => {
                            html += `<option value="${user.id}">${kapitalName(user.nama_lengkap)}</option>`;
                        });
                    } else {
                        html += '<option value="">No users found</option>';
                    }
                    
                    $('#userFilter').html(html);
                    $('#userFilter').prop('disabled', false);
                }

                $('#applyFilter').click(function() {
                    const month = $('#monthFilter').val();
                    const year = $('#yearFilter').val();
                    const mitra = $('#mitraFilter').val();
                    const user = $('#userFilter').val();
                    // Update currentMonth when filter is applied
                    if (month || year) {
                        currentMonth = year + '-' + (month ? month.padStart(2, '0') : '01');
                    } else {
                        currentMonth = '';
                    }
                    
                    loadData(1, month, year, mitra, user);
                });

                // Setup checkbox controls
                function setupCheckboxControls() {
                    // Header checkbox
                    $('#headerCheckbox').change(function() {
                        const isChecked = $(this).prop('checked');
                        $('.row-checkbox').prop('checked', isChecked);
                    });

                    // Select all button
                    $('#selectAll').click(function() {
                        $('.row-checkbox').prop('checked', true);
                        $('#headerCheckbox').prop('checked', true);
                    });

                    // Deselect all button
                    $('#deselectAll').click(function() {
                        $('.row-checkbox').prop('checked', false);
                        $('#headerCheckbox').prop('checked', false);
                    });
                }

                // Setup PDF generation
                function setupPdfGeneration() {
                    $('#generatePdf').click(function() {
                        const selectedIds = [];
                        $('.row-checkbox:checked').each(function() {
                            selectedIds.push($(this).data('id'));
                        });

                        if (selectedIds.length === 0) {
                            if (!confirm(
                                    'No records selected. Generate PDF for all records in the current month?'
                                )) {
                                return;
                            }
                        }

                        // Show loading state
                        const $button = $(this);
                        const originalText = $button.html();
                        $button.prop('disabled', true);
                        $button.html('<span class="loading loading-spinner loading-sm"></span> Generating PDF...');

                        // Show progress overlay

                        // Get data for PDF generation
                        $.ajax({
                            url: '{{ route('admin.upload.get-pdf-data') }}',
                            type: 'GET',
                            data: {
                                ids: selectedIds,
                                month: currentMonth,
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    updatePdfProgress('Generating PDF...');
                                    generatePdf(response.data, currentMonth, function() {
                                        // Callback when PDF generation is complete
                                        hidePdfProgressOverlay();
                                        $button.prop('disabled', false);
                                        $button.html(originalText);
                                    });
                                } else {
                                    hidePdfProgressOverlay();
                                    $button.prop('disabled', false);
                                    $button.html(originalText);
                                    Notify('Error generating PDF', null, null, 'error');
                                }
                            },
                            error: function(xhr) {
                                hidePdfProgressOverlay();
                                $button.prop('disabled', false);
                                $button.html(originalText);
                                Notify('Error getting PDF data', null, null, 'error');
                            }
                        });
                    });
                }

                function generatePdf(data, currentMonth, onComplete) {
                  const pages = getFotoPageHtml(data, currentMonth);
                  const totalPages = pages.length;
                  setProgress(0);

                  const images = [];

                  let index = 0;

                  function renderNext() {
                    if (index >= totalPages) {
                      // sudah selesai render semua jadi image
                      setProgress(100);
                      startWorker(images);
                      return;
                    }


                    const pageNum = index + 1;
                    const progressPercent = Math.round((pageNum - 1) / totalPages * 100);

                    // Update bar for starting this page
                    setProgress(progressPercent);

                    const div = document.createElement("div");
                    div.style.position = "absolute";
                    div.style.left = "-9999px";
                    div.style.width = "297mm";
                    div.style.backgroundColor = "white";
                    div.innerHTML = pages[index];
                    document.body.appendChild(div);

                    html2canvas(div, {
                      scale: 2,
                      useCORS: true,
                      allowTaint: true,
                      logging: false,
                    }).then(canvas => {
                      document.body.removeChild(div);

                      const dataUrl = canvas.toDataURL("image/jpeg", 0.8);
                      images.push(dataUrl);

                      // Update after page finished
                      const newPercent = Math.round(pageNum / totalPages * 100);
                      setProgress(newPercent);

                      index++;
                      renderNext();

                    }).catch(err => {
                      console.error(err);
                    });
                  }

                  renderNext();

                  function startWorker(images) {
                    const worker = new Worker("/js/pdf-worker.js");

                    worker.onmessage = function (e) {
                      if (e.data.progress !== undefined) {
                        setProgress(e.data.progress);
                      }

                      if (e.data.done) {
                       const filename = `Photo_Progress_Report_${currentMonth}.pdf`;
                        downloadPdfBlob(e.data.pdfBlob, filename);
                        sendPdfToBackend(e.data.pdfBlob, currentMonth, data[0].clients_id, () => {
                          if (onComplete) onComplete();

                          showNotification("PDF Generation Complete", {
                                body: "Proses pembuatan PDF telah selesai!",
                                icon: "/favicon.ico"
                            });
                          document.getElementById("notify-sound").play().catch(console.warn);

                        });
                        worker.terminate();
                      }

                      if (e.data.error) {
                        console.error("Worker Error:", e.data.error);
                        worker.terminate();
                      }
                    };

                    worker.postMessage({ images });
                  }
                }



                // Function to send PDF to backend
                function sendPdfToBackend(pdfBlob, month, clientIds, onComplete) {
                    const formData = new FormData();
                    formData.append('pdf', pdfBlob, `Photo_Progress_Report_${month.replace(/\s+/g, '_')}.pdf`);
                    formData.append('month', month);
                    formData.append('client_ids', clientIds ? JSON.stringify(clientIds) : '');
                    formData.append('_token', CSRF_TOKEN);

                    $.ajax({
                        url: '{{ route('admin.upload.store-pdf') }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Notify('PDF saved successfully!', null, null, 'success');
                            if (onComplete) onComplete();
                        },
                        error: function(xhr) {
                            Notify('Error saving PDF: ' + (xhr.responseJSON?.message || 'Unknown error'), null, null, 'error');
                            if (onComplete) onComplete();
                        }
                    });
                }

                // Pagination click
                $(document).on('click', '#pagination button', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && !$(this).hasClass('btn-disabled') && !$(this).hasClass('btn-active')) {
                        const month = $('#monthFilter').val();
                        const year = $('#yearFilter').val();
                        const mitra = $('#mitraFilter').val();
                        const user = $('#userFilter').val();
                        loadData(page, month, year, mitra, user);
                    }
                });

                // Close button for edit modal
                $('#btnClose').click(function() {
                    modal.close();
                });

                // Edit button
                $(document).on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    $.ajax({
                        url: `{{ route('admin.upload.index') }}`,
                        data: {
                            id: id
                        },
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                $('#modalTitle').text('Edit Photo Progress');
                                $('#formMethod').val('PUT');
                                $('#photoId').val(response.data.id);
                                $('#client_id').val(response.data.client_id || '');
                                $('#note').val(response.data.note || '');

                                // Display current images
                                displayCurrentImage('img_before', response.data.img_before);
                                displayCurrentImage('img_proccess', response.data.img_proccess);
                                displayCurrentImage('img_final', response.data.img_final);

                                modal.showModal();
                            }
                        },
                        error: function(xhr) {
                            Notify('Error loading data for edit', null, null, 'error');
                        }
                    });
                });

                // Helper function to display current image
                function displayCurrentImage(field, imagePath) {
                    const container = $(`#current-${field}`);
                    if (imagePath) {
                        const fullUrl = window.location.origin + '/storage/' + imagePath;
                        container.html(
                            `<img src="${fullUrl}" class="max-w-[150px] md:max-w-[200px] mt-2 cursor-pointer hover:opacity-80 transition-opacity" onclick="showImagePreview('${fullUrl}')" />`
                        );
                    } else {
                        container.html('');
                    }
                }

                // Form submission
                $('#photoForm').submit(function(e) {
                    e.preventDefault();
                    clearErrors();

                    const method = $('#formMethod').val();
                    const id = $('#photoId').val();
                    const url = "{{ route('admin.upload.update', ':id') }}".replace(':id', id);

                    // Use FormData for proper file handling
                    const formData = new FormData();
                    formData.append('client_id', $('#client_id').val());
                    formData.append('note', $('#note').val());

                    // Append image files if they exist
                    appendImageIfExists(formData, 'img_before');
                    appendImageIfExists(formData, 'img_proccess');
                    appendImageIfExists(formData, 'img_final');

                    formData.append('_token', CSRF_TOKEN);

                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }

                    $('#btnSave').prop('disabled', true);
                    $('#btnSpinner').removeClass('hidden');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                modal.close();
                                loadData(currentPage);
                                Notify(response.message, null, null, 'success');
                                resetForm();
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                displayErrors(errors);
                            } else {
                                Notify('Error saving data', null, null, 'error');
                            }
                        },
                        complete: function() {
                            $('#btnSave').prop('disabled', false);
                            $('#btnSpinner').addClass('hidden');
                        }
                    });
                });

                // Helper function to append image if exists
                function appendImageIfExists(formData, fieldName) {
                    const file = $(`#${fieldName}`)[0].files[0];
                    if (file) {
                        formData.append(fieldName, file);
                    }
                }

                // Delete button
                $(document).on('click', '.btn-delete', function() {
                    const id = $(this).data('id');

                    if (confirm('Are you sure you want to delete this photo progress?')) {
                        $.ajax({
                            url: `{{ route('admin.upload.destroy', ':id') }}`.replace(':id', id),
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: CSRF_TOKEN
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    loadData(currentPage);
                                    Notify(response.message, null, null, 'success');
                                }
                            },
                            error: function(xhr) {
                                Notify('Error deleting data', null, null, 'error');
                            }
                        });
                    }
                });

                // Image preview functionality
                window.showImagePreview = function(imageSrc) {
                    // Reset preview state
                    imagePreviewState = {
                        images: [imageSrc],
                        currentIndex: 0,
                        rotation: 0,
                        zoom: 1
                    };

                    updatePreviewImage();
                    imageModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                };

                // Setup image preview controls
                function setupImagePreviewControls() {
                    // Close image preview
                    $('#closeImagePreview').click(closeImagePreview);

                    // Rotate image
                    $('#rotateImage').click(function() {
                        imagePreviewState.rotation = (imagePreviewState.rotation + 90) % 360;
                        updatePreviewImageTransform();
                    });

                    // Zoom in
                    $('#zoomInImage').click(function() {
                        imagePreviewState.zoom = Math.min(imagePreviewState.zoom + 0.25, 3);
                        updatePreviewImageTransform();
                    });

                    // Zoom out
                    $('#zoomOutImage').click(function() {
                        imagePreviewState.zoom = Math.max(imagePreviewState.zoom - 0.25, 0.5);
                        updatePreviewImageTransform();
                    });

                    // Download image
                    $('#downloadImage').click(function() {
                        const link = document.createElement('a');
                        link.href = imagePreviewState.images[imagePreviewState.currentIndex];
                        link.download = 'image.jpg';
                        link.click();
                    });

                    // Previous image
                    $('#prevImage').click(function() {
                        if (imagePreviewState.images.length > 1) {
                            imagePreviewState.currentIndex = (imagePreviewState.currentIndex - 1 +
                                imagePreviewState.images.length) % imagePreviewState.images.length;
                            updatePreviewImage();
                        }
                    });

                    // Next image
                    $('#nextImage').click(function() {
                        if (imagePreviewState.images.length > 1) {
                            imagePreviewState.currentIndex = (imagePreviewState.currentIndex + 1) %
                                imagePreviewState.images.length;
                            updatePreviewImage();
                        }
                    });

                    // Close when clicking outside
                    $(imageModal).click(function(e) {
                        if (e.target === imageModal) {
                            closeImagePreview();
                        }
                    });
                }

                // Update preview image
                function updatePreviewImage() {
                    $('#previewImage').attr('src', imagePreviewState.images[imagePreviewState.currentIndex]);
                    updatePreviewImageTransform();
                    updateImageCounter();
                    updateNavigationButtons();
                }

                // Update preview image transform
                function updatePreviewImageTransform() {
                    const transform = `rotate(${imagePreviewState.rotation}deg) scale(${imagePreviewState.zoom})`;
                    $('#previewImage').css('transform', transform);
                }

                // Update image counter
                function updateImageCounter() {
                    if (imagePreviewState.images.length > 1) {
                        $('#imageCounter').text(
                            `${imagePreviewState.currentIndex + 1} / ${imagePreviewState.images.length}`);
                        $('#imageInfo').show();
                    } else {
                        $('#imageInfo').hide();
                    }
                }

                // Update navigation buttons
                function updateNavigationButtons() {
                    if (imagePreviewState.images.length > 1) {
                        $('#prevImage, #nextImage').show();
                    } else {
                        $('#prevImage, #nextImage').hide();
                    }
                }

                // Close image preview
                function closeImagePreview() {
                    imageModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';

                    // Reset transform
                    imagePreviewState.rotation = 0;
                    imagePreviewState.zoom = 1;
                }

                function downloadPdfBlob(blob, filename) {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }

                // Helper functions
                function resetForm() {
                    $('#photoForm')[0].reset();
                    $('#photoId').val('');
                    $('#current-img_before, #current-img_proccess, #current-img_final').html('');
                    clearErrors();
                }

                function clearErrors() {
                    $('select, input, textarea').removeClass('input-error select-error textarea-error');
                    $('.label[id^="error-"]').addClass('hidden').find('span').text('');
                }

                function displayErrors(errors) {
                    $.each(errors, function(key, value) {
                        const $field = $(`#${key}`);
                        $field.addClass($field.is('select') ? 'select-error' : ($field.is('textarea') ?
                            'textarea-error' : 'input-error'));
                        $(`#error-${key}`).removeClass('hidden').find('span').text(value[0]);
                    });
                }

                function kapitalName(name) {
                    return name
                        .toLowerCase()
                        .trim()
                        .replace(/\b([a-z])|\'([a-z])/g, match => match.toUpperCase());
                }


                // Update progress message
                function updatePdfProgress(message) {
                    $('#pdfProgressMessage').text(message);
                }

                // Hide PDF generation progress overlay
                function hidePdfProgressOverlay() {
                    $('#pdfProgressOverlay').addClass('hidden');
                }


                // Initial load
                init();
            });
        </script>

        <style>
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fade-out {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }

                to {
                    opacity: 0;
                    transform: translateX(20px);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.3s ease-out;
            }

            .animate-fade-out {
                animation: fade-out 0.3s ease-out;
            }

            /* Image preview modal styles */
            #imagePreviewModal {
                transition: opacity 0.3s ease;
            }

            #previewImage {
                transition: transform 0.3s ease;
            }
        </style>
    @endpush
</x-app-layout>
