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
                                <div class="form-control flex gap-x-2">
                                    <div>
                                        <label class="label">
                                            <span class="text-xs font-medium md:text-sm label-text">Filter Bulan</span>
                                        </label>
                                        <select id="monthFilter" class="select select-bordered select-xs md:select-md rounded-sm">
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
                                            <span class="text-xs font-medium md:text-sm label-text">Filter Tahun</span>
                                        </label>
                                        <select id="yearFilter" class="select select-bordered select-xs md:select-md rounded-sm">
                                            <option selected value="">All Year</option>
                                            @for ($year = now()->year; $year >= 2024; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button id="applyFilter" class="btn btn-xs md:btn-sm bg-blue-500/20 border-0 text-blue-600 rounded-sm hover:bg-blue-600 hover:text-white">Apply Filter</button>
                                    <button id="clearFilter" class="btn btn-xs md:btn-sm bg-red-500/20 border-0 text-red-600 rounded-sm hover:bg-red-600 hover:text-white">Clear</button>
                                    <button id="generatePdf" class="btn btn-xs md:btn-sm bg-green-500/20 border-0 text-green-600 rounded-sm hover:bg-green-600 hover:text-white">
                                       <i class="mr-1 ri-download-cloud-2-line text-xs md:text-sm"></i><span class="hidden sm:inline">Download PDF</span>
                                    </button>
                                </div>
                                <divider class="border-t border-gray-100"/>
                            </div>

                            <!-- Selection Controls -->
                            <div class="flex gap-2">
                                <button id="selectAll" class="btn btn-xs md:btn-sm bg-blue-500/20 border-0 text-blue-600 rounded-sm hover:bg-blue-600 hover:text-white">Select All</button>
                                <button id="deselectAll" class="btn btn-xs md:btn-sm bg-red-500/20 border-0 text-red-600 rounded-sm hover:bg-red-600 hover:text-white">Deselect All</button>
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

    @push('scripts')
        <script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.13/dist/html2canvas-pro.min.js"></script>
        <script src="{{ asset('js/fotoPages.js') }}"></script>
        <script>


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
                function loadData(page = 1, month = null, year = null) {
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
                            year
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
                                    ${item.clients?.name || '-'}
                                </div>
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 sm:table-cell">
                                ${renderImageCell(item.img_before, 'Before')}
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 md:table-cell text-center">
                                ${renderImageCell(item.img_proccess, '-')}
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 lg:table-cell text-center">
                                ${renderImageCell(item.img_final, '-')}
                            </td>
                            <td class="hidden px-2 py-2 md:px-3 md:table-cell">
                                <div class="max-w-[100px] md:max-w-xs truncate" title="${item.note || '-'}">
                                    ${item.note || '-'}
                                </div>
                            </td>
                            <td class="px-2 py-2 md:px-3">
                                <div class="flex justify-center gap-1 md:gap-2">
                                    <button class="btn btn-xs md:btn-sm bg-yellow-500/20 border-0 text-yellow-600 rounded-sm hover:bg-yellow-600 hover:text-white btn-edit" data-id="${item.id}">
                                        <i class="ri-settings-3-line text-xs md:text-sm"></i>
                                    </button>
                                    <button class="btn btn-xs md:btn-sm bg-red-500/20 border-0 text-red-600 rounded-sm hover:bg-red-600 hover:text-white btn-delete" data-id="${item.id}">
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
                    $('#clearFilter').click(function() {
                        $('#monthFilter').val('');
                        $('#yearFilter').val('');
                        currentMonth = '';
                        loadData(1);
                    });
                }

                 $('#applyFilter').click(function() {
                    const month = $('#monthFilter').val();
                    const year = $('#yearFilter').val();
                    loadData(1, month, year);
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

                        // Get data for PDF generation
                        $.ajax({
                            url: '{{ route('admin.upload.get-pdf-data') }}',
                            type: 'GET',
                            data: {
                                ids: selectedIds,
                                month: currentMonth
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    generatePdf(response.data);
                                } else {
                                    Notify('Error generating PDF', null, null, 'error');
                                }
                            },
                            error: function(xhr) {
                                Notify('Error getting PDF data', null, null, 'error');
                            }
                        });
                    });
                }

                // Generate PDF using getFotoPageHtml
                function generatePdf(data) {
                    // Get the array of page HTML strings
                    const pages = getFotoPageHtml(data, currentMonth);

                    // Initialize PDF
                    try {
                        const jsPDFConstructor =
                            (window.jspdf?.jsPDF) ||
                            (window.jspdf?.default) ||
                            (window.jsPDF) ||
                            (typeof jspdf !== 'undefined' && jspdf.jsPDF);

                        if (!jsPDFConstructor) {
                            throw new Error('jsPDF library not loaded properly.');
                        }

                        const pdf = new jsPDFConstructor({
                            orientation: 'landscape',
                            unit: 'mm',
                            format: 'a4'
                        });

                        // Process each page sequentially
                        let currentPageIndex = 0;

                        function processNextPage() {
                            if (currentPageIndex >= pages.length) {
                                // All pages processed, send PDF to backend
                                const pdfBlob = pdf.output('blob');
                                sendPdfToBackend(pdfBlob, currentMonth, data[0].clients_id);
                                return;
                            }

                            // Create temporary div for current page
                            const pageDiv = document.createElement('div');
                            pageDiv.style.position = 'absolute';
                            pageDiv.style.left = '-9999px';
                            pageDiv.style.width = '297mm'; // A4 width in mm
                            pageDiv.style.backgroundColor = 'white';
                            pageDiv.style.margin = '0';
                            pageDiv.style.boxSizing = 'border-box';
                            pageDiv.style.fontFamily = 'Arial, sans-serif';
                            pageDiv.innerHTML = pages[currentPageIndex];

                            // Add to document
                            document.body.appendChild(pageDiv);

                            // Capture the current page
                            html2canvas(pageDiv, {
                                scale: 2,
                                useCORS: true,
                                allowTaint: true,
                                logging: false,
                                width: pageDiv.offsetWidth,
                                height: pageDiv.offsetHeight,
                                backgroundColor: null
                            }).then(canvas => {
                                // Remove temporary element
                                document.body.removeChild(pageDiv);

                                // Convert to image data
                                const imgData = canvas.toDataURL('image/jpeg', 0.70);
                                const imgProps = pdf.getImageProperties(imgData);
                                const pdfWidth = pdf.internal.pageSize.getWidth();
                                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                                // Add new page if not the first page
                                if (currentPageIndex > 0) {
                                    pdf.addPage();
                                }

                                // Add image to PDF
                                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                                // Move to next page
                                currentPageIndex++;
                                processNextPage();
                            }).catch(error => {
                                console.error('Error capturing page:', error);
                                document.body.removeChild(pageDiv);
                                Notify('Error generating PDF: ' + error.message, null, null, 'error');
                            });
                        }

                        // Start processing pages
                        processNextPage();
                    } catch (error) {
                        console.error('Error initializing PDF generator:', error);
                        Notify('Error initializing PDF generator: ' + error.message, null, null, 'error');
                    }
                }

                // Function to send PDF to backend
                function sendPdfToBackend(pdfBlob, month, clientIds) {
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
                        },
                        error: function(xhr) {
                            Notify('Error saving PDF: ' + (xhr.responseJSON?.message || 'Unknown error'), null, null, 'error');
                        }
                    });
                }

                // Pagination click
                $(document).on('click', '#pagination button', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && !$(this).hasClass('btn-disabled') && !$(this).hasClass('btn-active')) {
                        loadData(page);
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