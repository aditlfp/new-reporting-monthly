<x-app-layout title="Data Cover" subtitle="Menampilkan Data Cover Yang Sudah Dibuat">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 mt-16 overflow-y-auto md:mt-0">
            <div class="flex items-center justify-end mb-6">
                <button id="openCoverModal"
                    class="text-blue-500 uppercase transition-all duration-200 ease-in-out border-none rounded-sm btn btn-sm bg-blue-500/20 hover:bg-blue-500 hover:text-white">
                    <i class="ri-add-line"></i> Add New Cover
                </button>
            </div>

            <div class="bg-white shadow-lg card">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full table-zebra">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mitra</th>
                                    <th class="hidden md:block">Jenis Cover</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="4" class="py-8 text-center">
                                        <span class="loading loading-spinner loading-lg"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="pagination" class="flex justify-center pb-4 mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="coverModal" class="modal">
        <div class="modal-box max-w-6xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Add New Cover</h3>
                <button id="closeModalBtn" class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column: Form -->
                <div class="p-4 border rounded-lg md:p-6 lg:col-span-2 bg-slate-50 border-slate-200">
                    <h4 class="mb-4 text-sm font-semibold text-slate-700">Cover Information</h4>
                    <form id="coverForm" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf
                        <input type="hidden" id="coverId">
                        <input type="hidden" id="formMethod" value="POST">
                        <input type="hidden" id="existingImg1">
                        <input type="hidden" id="existingImg2">
                        <input type="hidden" id="img1Changed" value="0">
                        <input type="hidden" id="img2Changed" value="0">

                        <div class="grid grid-cols-1 col-span-2 gap-4 md:grid-cols-5 md:gap-6">
                            <div class="md:col-span-3">
                                <label class="label">
                                    <span class="label-text">Client <span class="text-error">*</span></span>
                                </label>
                                <select id="clientSelect" class="w-full select select-bordered" required>
                                    <option value="" disabled selected>Select a client</option>
                                    @foreach ($client as $cli)
                                        <option value="{{ $cli->id }}" data-name="{{ ucwords(strtolower($cli->name)) }}">
                                            {{ ucwords(strtolower($cli->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="hidden label" id="errorClient">
                                    <span class="label-text-alt text-error">Please select a client</span>
                                </label>
                            </div>
                            <div class="md:col-span-2">
                                <label class="label">
                                    <span class="label-text">Jenis rekap <span class="text-error">*</span></span>
                                </label>
                                <div class="flex items-center gap-2 md:flex-col sm:space-x-4 sm:space-y-0">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="jenisRekap" value="Cleaning Service" class="radio radio-primary radio-sm">
                                        <span class="text-sm text-slate-700 whitespace-nowrap">Cleaning Service</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="jenisRekap" value="Security" class="radio radio-primary radio-sm">
                                        <span class="text-sm text-slate-700">Security</span>
                                    </label>
                                </div>
                                <label class="hidden label" id="errorJenisRekap">
                                    <span class="label-text-alt text-error">Please select a report type</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Image 1 (Kiri) <span class="text-error">*</span></span>
                                </label>
                                <input type="file" id="img1Input" class="w-full file-input file-input-bordered" accept=".svg,.png,.jpg,.jpeg">
                                <div id="preview1" class="mt-2 border-2 border-dashed border-slate-300 rounded-lg p-4 min-h-[120px] flex items-center justify-center">
                                    <span class="text-sm text-slate-500">No image selected</span>
                                </div>
                                <label class="hidden label" id="errorImg1">
                                    <span class="label-text-alt text-error">Please select an image</span>
                                </label>
                            </div>
    
                            <div>
                                <label class="label">
                                    <span class="label-text">Image 2 (Kanan) <span class="text-error">*</span></span>
                                </label>
                                <input type="file" id="img2Input" class="w-full file-input file-input-bordered" accept=".svg,.png,.jpg,.jpeg">
                                <div id="preview2" class="mt-2 border-2 border-dashed border-slate-300 rounded-lg p-4 min-h-[120px] flex items-center justify-center">
                                    <span class="text-sm text-slate-500">No image selected</span>
                                </div>
                                <label class="hidden label" id="errorImg2">
                                    <span class="label-text-alt text-error">Please select an image</span>
                                </label>
                            </div>
                        </div>


                        <div class="flex gap-2 col-span-full">
                            <button type="submit" class="text-white border-none btn bg-slate-900 hover:bg-slate-800">
                                <i class="hidden ri-save-line" id="btnSpinner"></i>
                                <span id="btnText">Save Cover</span>
                            </button>
                            <button type="button" id="cancelBtn" class="btn btn-ghost hover:bg-gray-200">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview -->
                <div class="hidden lg:col-span-1 md:block">
                    <div class="sticky p-4 bg-white border rounded-lg md:p-6 top-6 border-slate-200 h-fit">
                        <h4 class="mb-4 text-sm font-semibold text-slate-700">Live Preview</h4>
                        <div class="relative overflow-hidden rounded-lg bg-slate-100 aspect-square">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="{{ asset('img/COVER.svg') }}" alt="Cover Preview" class="object-contain w-full h-full" />
                            </div>

                            <div class="absolute inset-0 flex flex-col p-3 sm:p-4 md:p-6 lg:p-4">
                                <div class="mt-3 text-center sm:mt-4 md:mt-8">
                                    <div id="previewJenisRekap" class="inline-block px-2 sm:px-3 text-[6px] sm:text-[8px] md:text-[10.5px] text-[#323C8B] font-bold uppercase rounded-md bg-white/60 whitespace-nowrap">
                                        Jenis Rekap
                                    </div>
                                </div>

                                <div class="-mt-1 text-center md:-mt-2">
                                    <div id="previewClient" class="inline-block px-2 sm:px-3 text-[5px] sm:text-[7px] md:text-[9.5px] text-[#323C8B] font-bold capitalize rounded-md bg-white/60 whitespace-nowrap overflow-hidden max-w-[80px] sm:max-w-[120px] md:max-w-[180px]">
                                        Client Name
                                    </div>
                                </div>

                                <div class="flex justify-center flex-1 gap-1 mx-auto mt-2 sm:mt-2 md:mt-5 sm:gap-1 md:gap-2">
                                    <div class="w-1/2 pr-1">
                                        <div id="previewOverlayLeft" class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-[65px] md:h-[65px] border-2 border-dashed rounded-lg bg-white/50 border-slate-300">
                                            <span class="text-[10px] sm:text-xs md:text-sm text-slate-500">Image 1</span>
                                        </div>
                                    </div>

                                    <div class="w-1/2 pl-1">
                                        <div id="previewOverlayRight" class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-[65px] md:h-[65px] border-2 border-dashed rounded-lg bg-white/50 border-slate-300">
                                            <span class="text-[10px] sm:text-xs md:text-sm text-slate-500">Image 2</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-center text-slate-500">
                            <p>Preview updates as you make changes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Toast Container -->
    <div class="z-50 toast toast-top toast-end" id="toastContainer"></div>

    @push('scripts')
    <script src="{{ asset('js/Notify.js') }}"></script>
    <script>
    (function($) {
        'use strict';

        const ASSET_URL = "{{ asset('storage') }}";
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
        const $els = {
            modal: document.getElementById('coverModal'),
            form: $('#coverForm'),
            tableBody: $('#tableBody'),
            pagination: $('#pagination'),
            modalTitle: $('#modalTitle'),
            btnText: $('#btnText'),
            btnSpinner: $('#btnSpinner'),
            clientSelect: $('#clientSelect'),
            jenisRekapRadios: $('input[name="jenisRekap"]'),
            img1Input: $('#img1Input'),
            img2Input: $('#img2Input'),
            preview1: $('#preview1'),
            preview2: $('#preview2'),
            previewOverlayLeft: $('#previewOverlayLeft'),
            previewOverlayRight: $('#previewOverlayRight'),
            previewClient: $('#previewClient'),
            previewJenisRekap: $('#previewJenisRekap')
        };

        let state = { img1Data: null, img2Data: null, isEdit: false, currentPage: 1 };

        function init() {
            loadData();
            $('#openCoverModal').on('click', openCreateModal);
            $('#closeModalBtn, #cancelBtn').on('click', closeModal);
            $(document).on('click', '.edit-cover-btn', function() { editCover($(this).data('cover-id')); });
            $(document).on('click', '.delete-cover-btn', function() { deleteCover($(this).data('cover-id')); });
            $(document).on('click', '.pagination-btn', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page && !$(this).hasClass('btn-disabled') && !$(this).hasClass('btn-active')) {
                    loadData(page);
                }
            });
            
            $els.img1Input.on('change', (e) => handleImageChange(e, 1));
            $els.img2Input.on('change', (e) => handleImageChange(e, 2));
            $els.clientSelect.on('change', updateClientPreview);
            $els.jenisRekapRadios.on('change', updateJenisRekapPreview);
            $els.form.on('submit', handleSubmit);
            
            $(window).on('resize', debounce(() => {
                if ($els.previewClient.text() !== 'Client Name') {
                    resizeTextToFit($els.previewClient[0]);
                }
            }, 250));
        }

        function loadData(page = 1) {
            $.ajax({
                url: '{{ route("admin-covers.index") }}',
                type: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        renderTable(res.data.data);
                        renderPagination(res.data);
                        state.currentPage = page;
                    }
                },
                error: function(xhr) {
                    $els.tableBody.html('<tr><td colspan="4" class="py-8 text-center text-error">Failed to load data</td></tr>');
                }
            });
        }

        function renderTable(data) {
            if (data.length === 0) {
                $els.tableBody.html('<tr><td colspan="4" class="py-8 text-center text-gray-500">No covers found</td></tr>');
                return;
            }

            let html = '';
            data.forEach((item, index) => {
                const badgeClass = item.jenis_rekap === 'Cleaning Service' ? 'badge-success' : 'badge-info';
                html += `
                    <tr>
                        <td class="px-3 py-2">${index + 1}.</td>
                        <td class="px-3 py-2">
                            <span class="text-sm font-semibold">${item.client ? item.client.panggilan : '-'}</span>
                            <span class="badge badge-sm ${badgeClass} w-full whitespace-nowrap">${item.jenis_rekap}</span>
                        </td>
                        <td class="hidden px-3 py-2 md:block">
                            <span class="badge badge-sm ${badgeClass}">${item.jenis_rekap}</span>
                        </td>
                        <td class="whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button data-cover-id="${item.id}" class="edit-cover-btn btn btn-sm btn-warning">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button data-cover-id="${item.id}" class="delete-cover-btn btn btn-sm btn-error">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            $els.tableBody.html(html);
        }

        function renderPagination(data) {
            if (data.last_page <= 1) {
                $els.pagination.html('');
                return;
            }

            let html = '<div class="join">';
            
            // Previous button
            html += `<button class="join-item btn btn-sm pagination-btn ${data.current_page === 1 ? 'btn-disabled' : ''}" data-page="${data.current_page - 1}">
                <i class="ri-arrow-left-s-line"></i>
            </button>`;
            
            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    html += `<button class="join-item btn btn-sm pagination-btn ${i === data.current_page ? 'btn-active' : ''}" data-page="${i}">${i}</button>`;
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    html += '<button class="join-item btn btn-sm btn-disabled">...</button>';
                }
            }
            
            // Next button
            html += `<button class="join-item btn btn-sm pagination-btn ${data.current_page === data.last_page ? 'btn-disabled' : ''}" data-page="${data.current_page + 1}">
                <i class="ri-arrow-right-s-line"></i>
            </button>`;
            
            html += '</div>';
            $els.pagination.html(html);
        }

        function debounce(fn, delay) {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, arguments), delay);
            };
        }

        function openCreateModal() {
            resetForm();
            state.isEdit = false;
            $els.modalTitle.text('Add New Cover');
            $els.modal.showModal();
        }

        function closeModal() {
            $els.modal.close();
            resetForm();
        }

        function editCover(id) {
            resetForm();
            state.isEdit = true;
            $els.modalTitle.text('Edit Cover');
            $('#coverId').val(id);
            $('#formMethod').val('PUT');

            $.get(`/admin/admin-covers/${id}`, function(res) {
                const d = res.data;
                $els.clientSelect.val(d.clients_id).trigger('change');
                $els.jenisRekapRadios.filter(`[value="${d.jenis_rekap}"]`).prop('checked', true).trigger('change');

                if (d.img_src_1) {
                    $('#existingImg1').val(d.img_src_1);
                    const url1 = `${ASSET_URL}/${d.img_src_1.replace(/^\/+/, '')}`;
                    displayImage('preview1', url1);
                    updateOverlayImage('previewOverlayLeft', url1);
                    state.img1Data = url1;
                }

                if (d.img_src_2) {
                    $('#existingImg2').val(d.img_src_2);
                    const url2 = `${ASSET_URL}/${d.img_src_2.replace(/^\/+/, '')}`;
                    displayImage('preview2', url2);
                    updateOverlayImage('previewOverlayRight', url2);
                    state.img2Data = url2;
                }

                $els.modal.showModal();
            }).fail((xhr) => handleError(xhr, 'Failed to load cover data'));
        }

        function deleteCover(id) {
            if (!confirm('Are you sure you want to delete this cover?')) return;

            $.ajax({
                url: `/admin/admin-covers/${id}`,
                type: 'DELETE',
                data: { _token: CSRF_TOKEN },
                success: function(res) {
                    if (res.success) {
                        loadData(state.currentPage);
                        Notify('success', res.message || 'Cover deleted successfully');
                    } else {
                        Notify('error', res.message || 'Failed to delete cover');
                    }
                },
                error: (xhr) => handleError(xhr, 'Error deleting cover')
            });
        }

        function handleImageChange(e, num) {
            const file = e.target.files[0];
            const previewId = num === 1 ? 'preview1' : 'preview2';
            const overlayId = num === 1 ? 'previewOverlayLeft' : 'previewOverlayRight';
            const changedId = num === 1 ? 'img1Changed' : 'img2Changed';

            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    displayImage(previewId, ev.target.result);
                    updateOverlayImage(overlayId, ev.target.result);
                    state[num === 1 ? 'img1Data' : 'img2Data'] = ev.target.result;
                    $(`#${changedId}`).val('1');
                };
                reader.readAsDataURL(file);
            } else {
                $(`#${previewId}`).html('<span class="text-sm text-slate-500">No image selected</span>');
                $(`#${overlayId}`).html(`<span class="text-sm text-slate-500">Image ${num}</span>`);
                state[num === 1 ? 'img1Data' : 'img2Data'] = null;
                $(`#${changedId}`).val('0');
            }
        }

        function displayImage(id, src) {
            $(`#${id}`).html(`<img src="${src}" class="object-contain max-w-full mx-auto max-h-[120px]" alt="Preview">`);
        }

        function updateOverlayImage(id, src) {
            $(`#${id}`).html(`<img src="${src}" class="object-contain w-full h-full" alt="Preview">`);
        }

        function updateClientPreview() {
            const name = $els.clientSelect.find('option:selected').data('name') || 'Client Name';
            $els.previewClient.text(name);
            resizeTextToFit($els.previewClient[0]);
        }

        function updateJenisRekapPreview() {
            const text = $els.jenisRekapRadios.filter(':checked').val() || 'Jenis Rekap';
            $els.previewJenisRekap.text(text);
        }

        function resizeTextToFit(el) {
            const parentWidth = el.parentElement.offsetWidth;
            const isMobile = window.innerWidth < 768;
            let fontSize = isMobile ? 7 : 9.5;
            el.style.fontSize = fontSize + 'px';

            while (el.scrollWidth > parentWidth && fontSize > (isMobile ? 5 : 8)) {
                fontSize -= 0.5;
                el.style.fontSize = fontSize + 'px';
            }
        }

        function resetForm() {
            $els.form[0].reset();
            $('#coverId, #existingImg1, #existingImg2, #formMethod').val('');
            $('#img1Changed, #img2Changed').val('0');
            $els.preview1.html('<span class="text-sm text-slate-500">No image selected</span>');
            $els.preview2.html('<span class="text-sm text-slate-500">No image selected</span>');
            $els.previewClient.text('Client Name').css('font-size', '');
            $els.previewJenisRekap.text('Jenis Rekap');
            $els.previewOverlayLeft.html('<span class="text-sm text-slate-500">Image 1</span>');
            $els.previewOverlayRight.html('<span class="text-sm text-slate-500">Image 2</span>');
            $('.label[id^="error"]').addClass('hidden');
            state = { img1Data: null, img2Data: null, isEdit: false };
        }

        function validateForm() {
            let valid = true;
            const checks = [
                { val: $els.clientSelect.val(), err: '#errorClient' },
                { val: $els.jenisRekapRadios.is(':checked'), err: '#errorJenisRekap' }
            ];

            if (!state.isEdit) {
                checks.push(
                    { val: $els.img1Input[0].files[0], err: '#errorImg1' },
                    { val: $els.img2Input[0].files[0], err: '#errorImg2' }
                );
            }

            checks.forEach(c => {
                if (!c.val) {
                    $(c.err).removeClass('hidden');
                    valid = false;
                } else {
                    $(c.err).addClass('hidden');
                }
            });

            return valid;
        }

        function handleSubmit(e) {
            e.preventDefault();
            if (!validateForm()) return;

            const formData = new FormData();
            const id = $('#coverId').val();
            
            formData.append('_token', CSRF_TOKEN);
            if (state.isEdit) {
                formData.append('_method', 'PUT');
                formData.append('cover_id', id);
            }

            formData.append('clients_id', $els.clientSelect.val());
            formData.append('jenis_rekap', $els.jenisRekapRadios.filter(':checked').val());
            formData.append('existing_img_src_1', $('#existingImg1').val());
            formData.append('existing_img_src_2', $('#existingImg2').val());
            formData.append('img1_changed', $('#img1Changed').val());
            formData.append('img2_changed', $('#img2Changed').val());

            if ($('#img1Changed').val() === '1' && $els.img1Input[0].files[0]) {
                formData.append('img_src_1', $els.img1Input[0].files[0]);
            }
            if ($('#img2Changed').val() === '1' && $els.img2Input[0].files[0]) {
                formData.append('img_src_2', $els.img2Input[0].files[0]);
            }

            $els.btnText.text('Saving...');
            $els.btnSpinner.removeClass('hidden');
            $els.form.find('button[type="submit"]').prop('disabled', true);

            const url = state.isEdit ? `/admin/admin-covers/${id}` : '{{ route("admin-covers.store") }}';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        closeModal();
                        loadData(state.currentPage);
                        Notify('success', res.message || 'Cover saved successfully');
                    } else {
                        Notify('error', res.message || 'Failed to save cover');
                    }
                },
                error: (xhr) => handleError(xhr, 'Error saving cover'),
                complete: function() {
                    $els.btnText.text('Save Cover');
                    $els.btnSpinner.addClass('hidden');
                    $els.form.find('button[type="submit"]').prop('disabled', false);
                }
            });
        }

        function handleError(xhr, msg) {
            try {
                const res = JSON.parse(xhr.responseText);
                const errMsg = res.errors ? Object.values(res.errors).join('<br>') : (res.message || msg);
                Notify('error', errMsg);
            } catch (e) {
                Notify('error', msg);
            }
        }
        $(document).ready(init);
    })(jQuery);
    </script>
    @endpush
</x-app-layout>