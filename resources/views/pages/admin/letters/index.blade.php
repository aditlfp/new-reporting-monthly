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
                                    <th>Signature</th>
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
                    @foreach($covers as $cover)
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
                <input type="text" class="w-full input input-bordered" id="latter_numbers" name="latter_numbers" required maxlength="255">
                <label class="hidden label" id="error-latter_numbers">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="w-full mb-4 form-control">
                <label class="label">
                    <span class="label-text">Letter Matter <span class="text-error">*</span></span>
                </label>
                <textarea class="w-full h-24 textarea textarea-bordered" id="latter_matters" name="latter_matters" required></textarea>
                <label class="hidden label" id="error-latter_matters">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="w-full mb-4 form-control">
                <label class="label">
                    <span class="label-text">Period <span class="text-error">*</span></span>
                </label>
                <input type="text" class="w-full input input-bordered" id="period" name="period" required maxlength="255">
                <label class="hidden label" id="error-period">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="w-full mb-4 form-control">
                <label class="label">
                    <span class="label-text">Report Content</span>
                </label>
                <textarea class="w-full h-32 textarea textarea-bordered" id="report_content" name="report_content"></textarea>
                <label class="hidden label" id="error-report_content">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="w-full mb-4 form-control">
                <label class="label">
                    <span class="label-text">Add File Lanjutan Surat</span>
                </label>
                <input type="file" class="w-full file-input file-input-bordered" id="signature" name="signature">
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
        $(document).on('click', '.generate-pdf-btn', function() { generateCoverPDF($(this).data('latter-id')); });
    }

    // Load data
    function loadData(page = 1) {
        $.ajax({
            url: '{{ route("admin-latters.index") }}',
            type: 'GET',
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    renderTable(response.data.data);
                    renderPagination(response.data);
                    currentPage = page;
                }
            },
            error: function(xhr) {
                showToast('Error loading data', 'error');
            }
        });
    }

    // Render table
    function renderTable(data) {
        let html = '';
        if (data.length === 0) {
            html = '<tr><td colspan="7" class="py-8 text-center text-base-content/60">No data available</td></tr>';
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
                        <td>${item.signature || '-'}</td>
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
            if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                html += `<button class="join-item btn btn-sm ${i === data.current_page ? 'btn-active' : ''}" data-page="${i}">${i}</button>`;
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
                    $('#latter_numbers').val(response.data.latter_numbers);
                    $('#latter_matters').val(response.data.latter_matters);
                    $('#period').val(response.data.period);
                    $('#report_content').val(response.data.report_content || '');
                    $('#signature').val(response.data.signature || '');
                    modal.showModal();
                }
            },
            error: function(xhr) {
                showToast('Error loading data', 'error');
            }
        });
    });

    // Submit form
    $('#letterForm').submit(function(e) {
        e.preventDefault();
        clearErrors();
        
        const method = $('#formMethod').val();
        const id = $('#letterId').val();
        const url = method === 'POST' 
            ? '{{ route("admin-latters.store") }}'
            : `{{ url('admin-latters') }}/${id}`;
        
        const formData = {
            cover_id: $('#cover_id').val(),
            latter_numbers: $('#latter_numbers').val(),
            latter_matters: $('#latter_matters').val(),
            period: $('#period').val(),
            report_content: $('#report_content').val(),
            signature: $('#signature').val(),
            _token: '{{ csrf_token() }}'
        };

        if (method === 'PUT') {
            formData._method = 'PUT';
        }

        $('#btnSave').prop('disabled', true);
        $('#btnSpinner').removeClass('hidden');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    modal.close();
                    loadData(currentPage);
                    showToast(response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayErrors(errors);
                } else {
                    showToast('An error occurred', 'error');
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
                        showToast(response.message, 'success');
                    }
                },
                error: function(xhr) {
                    showToast('Error deleting data', 'error');
                }
            });
        }
    });

    // Helper functions
    function resetForm() {
        $('#letterForm')[0].reset();
        $('#letterId').val('');
        clearErrors();
    }

    function clearErrors() {
        $('select, input, textarea').removeClass('input-error select-error textarea-error');
        $('.label[id^="error-"]').addClass('hidden').find('span').text('');
    }

    function displayErrors(errors) {
        $.each(errors, function(key, value) {
            const $field = $(`#${key}`);
            $field.addClass($field.is('select') ? 'select-error' : ($field.is('textarea') ? 'textarea-error' : 'input-error'));
            $(`#error-${key}`).removeClass('hidden').find('span').text(value[0]);
        });
    }

    function showToast(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const icon = type === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
        
        const toast = `
            <div class="alert ${alertClass} shadow-lg mb-2 animate-fade-in">
                <div>
                    <i class="${icon} text-lg"></i>
                    <span>${message}</span>
                </div>
            </div>
        `;
        
        const $toast = $(toast);
        $('#toastContainer').append($toast);
        
        setTimeout(function() {
            $toast.addClass('animate-fade-out');
            setTimeout(function() {
                $toast.remove();
            }, 300);
        }, 3000);
    }

    function generateCoverPDF(latterId) {
        if (state.pdfGenerating) return;
        
        state.pdfGenerating = true;
        showToast('Generating PDF...', 'info');
        
        // First get the cover data
        $.get(`/admin/admin-latters/${latterId}`, function(res) {
            const latterData = res.data;
            
            // Create a temporary div for rendering
            const tempDiv = document.createElement('div');
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            tempDiv.style.width = '210mm'; // A4 width
            tempDiv.style.backgroundColor = 'white';
            tempDiv.style.padding = '0'; // Remove padding
            tempDiv.style.margin = '0'; // Remove margin
            tempDiv.style.boxSizing = 'border-box';
            tempDiv.style.fontFamily = 'Arial, sans-serif';
            tempDiv.style.overflow = 'hidden'; // Prevent any overflow
            
            // Build the HTML content using component functions
            const coverPageHtml = getCoverPageHtml(latterData, ASSET_URL);
            tempDiv.innerHTML = coverPageHtml;
            document.body.appendChild(tempDiv);
            
            // Initialize PDF
            let pdf;
            try {
                // Check if jsPDF is available through window.jspdf
                if (window.jspdf && typeof window.jspdf === 'object') {
                    // For UMD builds
                    if (window.jspdf.jsPDF) {
                        pdf = new window.jspdf.jsPDF({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        });
                    } else {
                        // Alternative access pattern
                        pdf = new window.jspdf.default({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        });
                    }
                } 
                // Check if jsPDF is available directly
                else if (window.jsPDF && typeof window.jsPDF === 'function') {
                    pdf = new window.jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });
                }
                // If none of the above work, try to access through module exports
                else if (typeof jspdf !== 'undefined' && jspdf.jsPDF) {
                    pdf = new jspdf.jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });
                }
                else {
                    throw new Error('jsPDF library not loaded properly. Please check the console for details.');
                }
            } catch (error) {
                console.error('Error initializing jsPDF:', error);
                document.body.removeChild(tempDiv);
                state.pdfGenerating = false;
                showToast('Error initializing PDF generator: ' + error.message, 'error');
                return;
            }
            
            // Capture the cover page with html2canvas
            html2canvas(tempDiv, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                logging: false,
                width: tempDiv.offsetWidth,
                height: tempDiv.offsetHeight,
                windowWidth: tempDiv.scrollWidth,
                windowHeight: tempDiv.scrollHeight,
                backgroundColor: null,
                removeContainer: false, // We'll remove the tempDiv at the end
                onclone: function(clonedDoc) {
                    const clonedElement = clonedDoc.querySelector('section');
                    if (clonedElement) {
                        // Force background image to load
                        const bgImage = clonedElement.querySelector('img[alt="Background"]');
                        if (bgImage) {
                            bgImage.setAttribute('crossorigin', 'anonymous');
                        }
                        
                        // Force other images to load
                        const images = clonedElement.querySelectorAll('img:not([alt="Background"])');
                        images.forEach(img => {
                            img.setAttribute('crossorigin', 'anonymous');
                        });
                    }
                }
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                
                // Remove temporary element
                document.body.removeChild(tempDiv);
                
                // Convert to blob for sending to backend
                const pdfBlob = pdf.output('blob');
                
                // Send PDF to backend
                sendPDFToBackend(latterId, pdfBlob);
                
                state.pdfGenerating = false;
                showToast('PDF saved successfully!', 'success');
            }).catch(error => {
                console.error('Error generating PDF:', error);
                document.body.removeChild(tempDiv);
                state.pdfGenerating = false;
                showToast('Error generating PDF: ' + error.message, 'error');
            });
            
        }).fail(xhr => {
            state.pdfGenerating = false;
            handleError(xhr, 'Failed to load cover data for PDF generation');
        });
    }

    // Function to send PDF to backend
    function sendPDFToBackend(coverId, pdfBlob) {
        const formData = new FormData();
        formData.append('pdf', pdfBlob, `Cover_${coverId}.pdf`);
        formData.append('_token', CSRF_TOKEN);
        formData.append('cover_id', coverId);
        
        $.ajax({
            url: '/admin/admin-covers/store-pdf',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    console.log('PDF saved to server:', res.path);
                    // Optionally show a more detailed success message
                    showToast('PDF saved to server successfully!', 'success');
                } else {
                    console.error('Failed to save PDF to server:', res.message);
                    showToast('Failed to save PDF to server: ' + res.message, 'error');
                }
            },
            error: function(xhr) {
                console.error('Error saving PDF to server:', xhr);
                showToast('Error saving PDF to server: ' + xhr.statusText, 'error');
            }
        });
    }

    // Initial load
    $(document).ready(init);
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes fade-out {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(20px); }
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
