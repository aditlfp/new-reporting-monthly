<x-app-layout title="Data Surat" subtitle="Menampilkan Data Surat Yang Sudah Dibuat">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="container px-4 py-8 mx-auto">
            <div class="m-5 bg-white shadow-xl card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl card-title">Letters Management</h2>
                        <button class="btn btn-info" id="btnCreate">
                            <i class="ri-add-line"></i> Create New Letter
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full table-zebra">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cover Mitra</th>
                                    <th>No Number</th>
                                    <th>Hal Surat</th>
                                    <th>Period</th>
                                    <th>Kepada</th>
                                    <th>Addition File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <span class="loading loading-spinner loading-lg"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="pagination" class="flex justify-center mt-6"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="letterModal" class="modal">
        <div class="max-w-2xl modal-box w-10/11">
            <h3 class="mb-4 text-lg font-bold" id="modalTitle">Create Letter</h3>

            <form id="letterForm" method="dialog">
                <input type="hidden" id="letterId" name="id">
                <input type="hidden" id="formMethod" value="POST">

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Cover ID <span class="text-error">*</span></span>
                    </label>
                    <select class="w-full select select-bordered" id="cover_id" name="cover_id" required>
                        <option value="" disabled selected>Select Cover</option>
                        @foreach ($covers as $cover)
                            <option value="{{ $cover->id }}">{{ $cover->client->name }}</option>
                        @endforeach
                    </select>
                    <label class="hidden label" id="error-cover_id">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Letter Number <span class="text-error">*</span></span>
                    </label>
                    <input type="text" class="w-full input input-bordered" id="latter_numbers" name="latter_numbers"
                        required maxlength="255" placeholder="XXX/XX/SAC/20XX">
                    <label class="hidden label" id="error-latter_numbers">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Lamp <span class="text-info italic">(opsional)</span></span>
                    </label>
                    <input type="text" class="w-full input input-bordered" id="lamp" name="lamp"
                        maxlength="255" placeholder="Satu Bandel">
                    <label class="hidden label" id="error-lamp">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Letter Matter <span class="text-error">*</span></span>
                    </label>
                    <textarea class="w-full h-24 textarea textarea-bordered" id="latter_matters" name="latter_matters" required
                        placeholder="Perihal... eg.(Laporan Pekerjaan Cleaning Service Bulan Oktober 2025)"></textarea>
                    <label class="hidden label" id="error-latter_matters">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Period <span class="text-error">*</span></span>
                    </label>
                    <input type="text" class="w-full input input-bordered" id="period" name="period" required
                        maxlength="255" placeholder="Periode eg.(Bulan November 2025)">
                    <label class="hidden label" id="error-period">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Kepada <span class="text-error">*</span></span>
                    </label>
                    <input type="text" class="w-full input input-bordered" id="letter_to" name="letter_to" required
                        maxlength="255" placeholder="Kepada yth....">
                    <label class="hidden label" id="error-letter_to">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Report Content</span>
                    </label>
                    <textarea class="w-full h-32 textarea textarea-bordered" id="report_content" name="report_content"
                        placeholder="1. surat (enter) 2. mcp (enter), str.."></textarea>
                    <label class="hidden label" id="error-report_content">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <!-- Update the file input section -->
                <div class="w-full mb-4 form-control">
                    <label class="label">
                        <span class="label-text">Add File Lanjutan Surat (MCP, dkk)</span>
                    </label>
                    <input type="file" class="w-full file-input file-input-bordered" id="signature"
                        name="signature">
                    <div id="current-file" class="mt-2 text-sm text-gray-600"></div>
                    <label class="hidden label" id="error-signature">
                        <span class="label-text-alt text-error"></span>
                    </label>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" id="btnClose">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">
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

    <!-- Toast Container -->
    <div class="z-50 toast toast-top toast-end" id="toastContainer"></div>

    @push('scripts')
        <script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.13/dist/html2canvas-pro.min.js"></script>
        <script src="{{ asset('js/coverPages.js') }}"></script>
        <script src="{{ asset('js/letterPages.js') }}"></script>
        <script>
            $(document).ready(function() {
                let currentPage = 1;
                const modal = document.getElementById('letterModal');

                const $els = {
                    // ... existing elements ...
                    generatePdfBtn: $('.generate-pdf-btn'),
                };

                let state = {
                    pdfGenerating: false
                };

                const ASSET_URL = "{{ asset('storage') }}";
                const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                function init() {
                    // Initial load
                    loadData();
                    $(document).on('click', '.generate-pdf-btn', function() {
                        generatePDFs($(this).data('latter-id'));
                    });
                }

                // Load data
                function loadData(page = 1) {
                    $.ajax({
                        url: '{{ route('admin-latters.index') }}',
                        type: 'GET',
                        data: {
                            page: page
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                renderTable(response.data.data);
                                renderPagination(response.data);
                                currentPage = page;
                            }
                        },
                        error: function(xhr) {
                            Notify('Error loading data', null, null, 'error');
                        }
                    });
                }

                // Render table
                function renderTable(data) {
                    let html = '';
                    if (data.length === 0) {
                        html =
                            '<tr><td colspan="8" class="py-8 text-center text-base-content/60">No data available</td></tr>';
                    } else {
                        data.forEach(function(item) {
                            html += `
                    <tr class="hover">
                        <td>${item.id}</td>
                        <td>${item.cover?.client.name}</td>
                        <td>${item.latter_numbers}</td>
                        <td>
                            <div class="max-w-xs truncate" title="${item.latter_matters}">
                                ${item.latter_matters}
                            </div>
                        </td>
                        <td>${item.period}</td>
                        <td>${item.letter_to}</td>
                        <td>
                            ${item.signature ? 
                                `<span><i class="ri-file-pdf-2-line text-2xl text-error"></i></span>`
                             : 
                                `<span>-</span>`
                            }
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit" data-id="${item.id}">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button data-latter-id="${item.id}" class="generate-pdf-btn btn btn-sm btn-info">
                                    <i class="ri-file-pdf-line"></i>
                                </button>
                                <button class="btn btn-sm btn-error btn-delete" data-id="${item.id}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                        });
                    }
                    $('#tableBody').html(html);
                }

                // Render pagination
                function renderPagination(data) {
                    if (data.last_page <= 1) {
                        $('#pagination').html('');
                        return;
                    }

                    let html = '<div class="join">';

                    // Previous
                    html += `<button class="join-item btn btn-sm ${data.current_page === 1 ? 'btn-disabled' : ''}" data-page="${data.current_page - 1}">
            <i class="ri-arrow-left-s-line"></i>
        </button>`;

                    // Pages
                    for (let i = 1; i <= data.last_page; i++) {
                        if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page +
                                2)) {
                            html +=
                                `<button class="join-item btn btn-sm ${i === data.current_page ? 'btn-active' : ''}" data-page="${i}">${i}</button>`;
                        } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                            html += '<button class="join-item btn btn-sm btn-disabled">...</button>';
                        }
                    }

                    // Next
                    html += `<button class="join-item btn btn-sm ${data.current_page === data.last_page ? 'btn-disabled' : ''}" data-page="${data.current_page + 1}">
            <i class="ri-arrow-right-s-line"></i>
        </button>`;

                    html += '</div>';
                    $('#pagination').html(html);
                }

                // Pagination click
                $(document).on('click', '#pagination button', function(e) {
                    e.preventDefault();
                    if (!$(this).hasClass('btn-disabled') && !$(this).hasClass('btn-active')) {
                        loadData($(this).data('page'));
                    }
                });

                // Create button
                $('#btnCreate').click(function() {
                    resetForm();
                    $('#modalTitle').text('Create Letter');
                    $('#formMethod').val('POST');
                    modal.showModal();
                });

                // Close button
                $('#btnClose').click(function() {
                    modal.close();
                });

                // Edit button
                $(document).on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    $.ajax({
                        url: `{{ url('admin/admin-latters') }}/${id}/edit`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                console.log(response)
                                $('#modalTitle').text('Edit Letter');
                                $('#formMethod').val('PUT');
                                $('#letterId').val(response.data.id);
                                $('#cover_id').val(response.data.cover_id);
                                $('#lamp').val(response.data.lamp)
                                $('#latter_numbers').val(response.data.latter_numbers);
                                $('#latter_matters').val(response.data.latter_matters);
                                $('#period').val(response.data.period);
                                $('#letter_to').val(response.data.letter_to);
                                $('#report_content').val(response.data.report_content || '');
                                // Display current file name if it exists
                                if (response.data.signature) {
                                    $('#current-file').html('Current file: ' + response.data
                                        .signature);
                                } else {
                                    $('#current-file').html('');
                                }
                                modal.showModal();
                            }
                        },
                        error: function(xhr) {
                            Notify('Error loading data for edit', null, null, 'error');
                        }
                    });
                });

                // Update the form submission to generate PDFs
                $('#letterForm').submit(function(e) {
                    e.preventDefault();
                    clearErrors();

                    const method = $('#formMethod').val();
                    const id = $('#letterId').val();
                    const url = method === 'POST' ?
                        '{{ route('admin-latters.store') }}' :
                        `{{ url('admin-latters') }}/${id}`;

                    // Use FormData for proper file handling
                    const formData = new FormData();
                    formData.append('cover_id', $('#cover_id').val());
                    formData.append('latter_numbers', $('#latter_numbers').val());
                    formData.append('lamp', $('#lamp').val());
                    formData.append('latter_matters', $('#latter_matters').val());
                    formData.append('period', $('#period').val());
                    formData.append('letter_to', $('#letter_to').val());
                    formData.append('report_content', $('#report_content').val());

                    const signatureFile = $('#signature')[0].files[0];
                    if (signatureFile) {
                        formData.append('signature', signatureFile);
                    }

                    formData.append('_token', '{{ csrf_token() }}');

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

                // Delete button
                $(document).on('click', '.btn-delete', function() {
                    const id = $(this).data('id');

                    if (confirm('Are you sure you want to delete this letter?')) {
                        $.ajax({
                            url: `{{ url('admin/admin-latters') }}/${id}`,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
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

                // Helper functions
                function resetForm() {
                    $('#letterForm')[0].reset();
                    $('#letterId').val('');
                    $('#current-file').html('');
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

                // Update the sendPDFToBackend function to handle both PDFs
                function sendPDFToBackend(coverId, pdfBlob, latterData) {
                    const formData = new FormData();
                    formData.append('pdf', pdfBlob, `${latterData.latter_matters}.pdf`);
                    formData.append('_token', CSRF_TOKEN);
                    formData.append('cover_id', coverId);
                    formData.append('srt_id', coverId);

                    $.ajax({
                        url: '/admin/admin-covers/store-pdf', // Updated to use admin-latters route
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.success) {
                                console.log('PDFs saved to server:', res.path);
                                Notify('PDFs saved to server successfully!', null, null, 'success');
                            } else {
                                console.error('Failed to save PDFs to server:', res.message);
                                Notify('Failed to save PDFs to server: ' + res.message, null, null,
                                    'error');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error saving PDFs to server:', xhr);
                            Notify('Error saving PDFs to server: ' + xhr.statusText, null, null, 'error');
                        }
                    });
                }

                // Add this function to generate both PDFs
                function generatePDFs(latterId) {
                    if (state.pdfGenerating) return;

                    state.pdfGenerating = true;
                    Notify('Generating PDFs....', null, null, 'success');

                    // First get the cover data
                    $.get(`/admin/admin-latters/${latterId}`, function(res) {
                        const latterData = res.data;

                        // Create temporary divs for rendering
                        const coverDiv = document.createElement('div');
                        coverDiv.style.position = 'absolute';
                        coverDiv.style.left = '-9999px';
                        coverDiv.style.width = '210mm'; // A4 width
                        coverDiv.style.backgroundColor = 'white';
                        coverDiv.style.padding = '0';
                        coverDiv.style.margin = '0';
                        coverDiv.style.boxSizing = 'border-box';
                        coverDiv.style.fontFamily = 'Arial, sans-serif'; // Cover font
                        coverDiv.style.overflow = 'hidden';

                        const letterDiv = document.createElement('div');
                        letterDiv.style.position = 'absolute';
                        letterDiv.style.left = '-9999px';
                        letterDiv.style.width = '210mm'; // A4 width
                        letterDiv.style.backgroundColor = 'white';
                        letterDiv.style.padding = '0';
                        letterDiv.style.margin = '0';
                        letterDiv.style.boxSizing = 'border-box';
                        letterDiv.style.fontFamily = 'Times New Roman, serif'; // Letter font
                        letterDiv.style.overflow = 'hidden';

                        // Build the HTML content using component functions
                        const coverPageHtml = getCoverPageHtml(latterData, ASSET_URL);
                        const letterPageHtml = getLetterPageHtml(latterData, ASSET_URL);

                        // Set content for each div
                        coverDiv.innerHTML = coverPageHtml;
                        letterDiv.innerHTML = letterPageHtml;

                        // Add to document
                        document.body.appendChild(coverDiv);
                        document.body.appendChild(letterDiv);

                        // Initialize PDF
                        try {
                            // Try different ways to access jsPDF constructor
                            const jsPDFConstructor =
                                (window.jspdf?.jsPDF) ||
                                (window.jspdf?.default) ||
                                (window.jsPDF) ||
                                (typeof jspdf !== 'undefined' && jspdf.jsPDF);

                            if (!jsPDFConstructor) {
                                throw new Error(
                                    'jsPDF library not loaded properly. Please check the console for details.'
                                );
                            }

                            pdf = new jsPDFConstructor({
                                orientation: 'portrait',
                                unit: 'mm',
                                format: 'a4'
                            });
                        } catch (error) {
                            console.error('Error initializing jsPDF:', error);
                            document.body.removeChild(coverDiv);
                            document.body.removeChild(letterDiv);
                            state.pdfGenerating = false;
                            Notify('Error initializing PDF generator: ' + error.message, null, null, 'error');
                            return;
                        }

                        // Capture the cover page with html2canvas
                        html2canvas(coverDiv, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            logging: false,
                            width: coverDiv.offsetWidth,
                            height: coverDiv.offsetHeight,
                            windowWidth: coverDiv.scrollWidth,
                            windowHeight: coverDiv.scrollHeight,
                            backgroundColor: null,
                            removeContainer: false,
                            onclone: function(clonedDoc) {
                                const clonedElement = clonedDoc.querySelector('section');
                                if (clonedElement) {
                                    // Force background image to load
                                    const bgImage = clonedElement.querySelector(
                                        'img[alt="Background"]');
                                    if (bgImage) {
                                        bgImage.setAttribute('crossorigin', 'anonymous');
                                    }

                                    // Force other images to load
                                    const images = clonedElement.querySelectorAll(
                                        'img:not([alt="Background"])');
                                    images.forEach(img => {
                                        img.setAttribute('crossorigin', 'anonymous');
                                    });
                                }
                            }
                        }).then(canvas => {
                            const imgData = canvas.toDataURL('image/jpeg', 0.70);
                            const imgProps = pdf.getImageProperties(imgData);
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                            // Add a new page for the letter
                            pdf.addPage();

                            // Capture the letter page
                            return html2canvas(letterDiv, {
                                scale: 2,
                                useCORS: true,
                                allowTaint: true,
                                logging: false,
                                width: letterDiv.offsetWidth,
                                height: letterDiv.offsetHeight,
                                windowWidth: letterDiv.scrollWidth,
                                windowHeight: letterDiv.scrollHeight,
                                backgroundColor: null,
                                removeContainer: false,
                                onclone: function(clonedDoc) {
                                    const clonedElement = clonedDoc.querySelector(
                                        'section');
                                    if (clonedElement) {
                                        // Force background image to load
                                        const bgImage = clonedElement.querySelector(
                                            'img[alt="Background"]');
                                        if (bgImage) {
                                            bgImage.setAttribute('crossorigin',
                                                'anonymous');
                                        }

                                        // Force other images to load
                                        const images = clonedElement.querySelectorAll(
                                            'img:not([alt="Background"])');
                                        images.forEach(img => {
                                            img.setAttribute('crossorigin',
                                                'anonymous');
                                        });
                                    }
                                }
                            });
                        }).then(canvas => {
                            const imgData = canvas.toDataURL('image/jpeg', 0.70);
                            const imgProps = pdf.getImageProperties(imgData);
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                            // Remove temporary elements
                            document.body.removeChild(coverDiv);
                            document.body.removeChild(letterDiv);

                            // Convert to blob for sending to backend
                            const pdfBlob = pdf.output('blob');

                            // Send PDF to backend
                            sendPDFToBackend(latterId, pdfBlob, latterData);

                            state.pdfGenerating = false;
                            Notify('PDFs saved successfully!', null, null, 'success');
                        }).catch(error => {
                            console.error('Error generating PDFs:', error);
                            document.body.removeChild(coverDiv);
                            document.body.removeChild(letterDiv);
                            state.pdfGenerating = false;
                            Notify('Error generating PDFs: ' + error.message, null, null, 'error');
                        });

                    }).fail(xhr => {
                        state.pdfGenerating = false;
                        handleError(xhr, 'Failed to load cover data for PDF generation');
                    });
                }

                // Initial load
                $(document).ready(init);
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
        </style>
    @endpush
</x-app-layout>
