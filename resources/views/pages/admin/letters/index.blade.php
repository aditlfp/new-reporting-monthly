<x-app-layout title="Data Surat" subtitle="Menampilkan Data Surat Yang Sudah Dibuat">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="container mx-auto px-4 py-8">
            <div class="card bg-white shadow-xl m-5">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title text-2xl">Letters Management</h2>
                        <button class="btn btn-info" id="btnCreate">
                            <i class="ri-add-line"></i> Create New Letter
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
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
    <div class="modal-box w-11/12 max-w-3xl">
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Create Letter</h3>
        
        <form id="letterForm" method="dialog">
            <input type="hidden" id="letterId" name="id">
            <input type="hidden" id="formMethod" value="POST">

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Cover ID <span class="text-error">*</span></span>
                </label>
                <select class="select select-bordered w-full" id="cover_id" name="cover_id" required>
                    <option value="" disabled selected>Select Cover</option>
                    @foreach($covers as $cover)
                    <option value="{{ $cover->id }}">{{ $cover->client->name }}</option>
                    @endforeach
                </select>
                <label class="label hidden" id="error-cover_id">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Letter Number <span class="text-error">*</span></span>
                </label>
                <input type="text" class="input input-bordered w-full" id="latter_numbers" name="latter_numbers" required maxlength="255">
                <label class="label hidden" id="error-latter_numbers">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Letter Matter <span class="text-error">*</span></span>
                </label>
                <textarea class="textarea textarea-bordered w-full h-24" id="latter_matters" name="latter_matters" required></textarea>
                <label class="label hidden" id="error-latter_matters">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Period <span class="text-error">*</span></span>
                </label>
                <input type="text" class="input input-bordered w-full" id="period" name="period" required maxlength="255">
                <label class="label hidden" id="error-period">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Report Content</span>
                </label>
                <textarea class="textarea textarea-bordered w-full h-32" id="report_content" name="report_content"></textarea>
                <label class="label hidden" id="error-report_content">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Signature</span>
                </label>
                <input type="text" class="input input-bordered w-full" id="signature" name="signature" maxlength="255">
                <label class="label hidden" id="error-signature">
                    <span class="label-text-alt text-error"></span>
                </label>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" id="btnClose">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSave">
                    <span class="loading loading-spinner loading-sm hidden" id="btnSpinner"></span>
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
<div class="toast toast-top toast-end z-50" id="toastContainer"></div>

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    const modal = document.getElementById('letterModal');

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
            html = '<tr><td colspan="7" class="text-center py-8 text-base-content/60">No data available</td></tr>';
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

    // Initial load
    loadData();
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
