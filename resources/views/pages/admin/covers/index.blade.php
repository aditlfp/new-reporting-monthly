<x-app-layout title="Data Cover" subtitle="Menampilkan Data Cover Yang Sudah Dibuat">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-slate-900">Cover Reports</h1>
                <button id="openCoverModal"
                    class="px-4 py-2 text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Add New Cover
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Mitra</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Jenis Cover</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($covers as $cover)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}.</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucwords(strtolower($cover->client->name)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $cover->jenis_rekap }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button data-cover-id="{{ $cover->id }}"
                                           class="btn btn-sm bg-amber-500/20 text-amber-500 border-none rounded-sm py-1 hover:bg-amber-500 hover:text-white transition-all ease-in-out duration-200"><i class="ri-settings-3-line text-xl"></i></button>
                                        <button data-cover-id="{{ $cover->id }}"
                                            class="btn btn-sm bg-red-500/20 text-red-500 border-none rounded-sm py-1 hover:bg-red-500 hover:text-white transition-all ease-in-out duration-200"><i class="ri-delete-bin-2-line text-xl"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No covers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($covers->hasPages())
                    <div class="flex justify-center mt-4">
                        {{ $covers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Cover create/edit modal --}}
    <div id="coverModal" class="modal">
        <div class="max-w-6xl bg-white modal-box max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-4 mb-6 md:px-6">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Add New Cover</h3>
                <button id="closeModalBtn"
                    class="transition-colors btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </div>

            <div class="grid grid-cols-1 gap-6 px-4 md:px-6 lg:grid-cols-3">
                <!-- Left Column: Form (2/3 width) -->
                <div class="p-4 border rounded-lg md:p-6 lg:col-span-2 bg-slate-50 border-slate-200">
                    <h4 class="mb-4 text-sm font-semibold text-slate-700">Cover Information</h4>
                    <form id="coverForm" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf
                        <!-- Hidden field for cover ID (used for edit) -->
                        <input type="hidden" id="coverId" name="cover_id" value="">

                        <!-- Hidden fields to store existing image paths -->
                        <input type="hidden" id="existing_img_src_1" name="existing_img_src_1" value="">
                        <input type="hidden" id="existing_img_src_2" name="existing_img_src_2" value="">
                        <div class="grid grid-cols-1 col-span-2 gap-4 md:grid-cols-5 md:gap-6">
                            <div class="md:col-span-3">
                                <label class="block mb-2 text-xs font-medium text-slate-600"
                                    for="client">Client</label>
                                <select id="client" name="clients_id"
                                    class="w-full bg-white select select-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    required>
                                    <option value="" disabled selected>Select a client</option>
                                    @foreach ($client as $cli)
                                        <option value="{{ $cli->id }}"
                                            data-name="{{ ucwords(strtolower($cli->name)) }}">
                                            {{ ucwords(strtolower($cli->name)) }}</option>
                                    @endforeach
                                </select>
                                <span class="hidden text-xs text-red-500" id="client-error">Please select a
                                    client</span>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-xs font-medium text-slate-600" for="client">Jenis
                                    rekap</label>
                                <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="jenis_rekap" id="jenis_rekap_cleaning"
                                            value="Cleaning Service" class="focus:ring-blue-500">
                                        <span class="text-sm text-slate-700 whitespace-nowrap">Cleaning Service</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="jenis_rekap" id="jenis_rekap_security"
                                            value="Security" class="focus:ring-blue-500">
                                        <span class="text-sm text-slate-700">Security</span>
                                    </label>
                                </div>
                                <span class="hidden text-xs text-red-500" id="jenis-rekap-error">Please select a report
                                    type</span>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="img_src_1">Image 1
                                (Kiri)</label>
                            <input type="file" id="img_src_1" name="img_src_1"
                                class="w-full bg-white file-input file-input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept=".svg,.png,.jpg,.jpeg">
                            <div id="preview1"
                                class="mt-2 border-2 border-dashed border-slate-300 rounded-lg p-4 min-h-[120px] flex items-center justify-center">
                                <span class="text-sm text-slate-500">No image selected</span>
                            </div>
                            <!-- Hidden field to track if image has changed -->
                            <input type="hidden" id="img1_changed" name="img1_changed" value="0">
                            <span class="hidden text-xs text-red-500" id="image1-error">Please select an image</span>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="img_src_2">Image 2
                                (Kanan)</label>
                            <input type="file" id="img_src_2" name="img_src_2"
                                class="w-full bg-white file-input file-input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept=".svg,.png,.jpg,.jpeg">
                            <div id="preview2"
                                class="mt-2 border-2 border-dashed border-slate-300 rounded-lg p-4 min-h-[120px] flex items-center justify-center">
                                <span class="text-sm text-slate-500">No image selected</span>
                            </div>
                            <!-- Hidden field to track if image has changed -->
                            <input type="hidden" id="img2_changed" name="img2_changed" value="0">
                            <span class="hidden text-xs text-red-500" id="image2-error">Please select an image</span>
                        </div>
                        <div class="flex flex-col gap-2 sm:flex-row col-span-full">
                            <button type="submit"
                                class="text-white transition-colors border-none btn bg-slate-900 hover:bg-slate-800 focus:ring-2 focus:ring-slate-500 focus:outline-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span id="submitButtonText">Save Cover</span>
                            </button>
                            <button type="button" id="cancelButton"
                                class="transition-colors btn btn-ghost hover:bg-gray-200">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview (1/3 width) -->
                <div class="lg:col-span-1" style="font-family: Arial, sans-serif;">
                    <div class="sticky p-4 bg-white border rounded-lg md:p-6 top-6 border-slate-200 h-fit">
                        <h4 class="mb-4 text-sm font-semibold text-slate-700">Live Preview</h4>
                        <div class="relative overflow-hidden rounded-lg bg-slate-100 aspect-square">
                            <!-- Base SVG Preview -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="{{ asset('img/COVER.svg') }}" alt="Cover Preview"
                                    class="object-contain w-full h-full" />
                            </div>

                            <!-- Overlay Content -->
                            <div class="absolute inset-0 flex flex-col p-3 sm:p-4 md:p-6 lg:p-4">
                                <!-- Jenis Rekap Overlay -->
                                <div class="mt-3 text-center sm:mt-4 md:mt-8">
                                    <div id="preview-jenis-rekap"
                                        class="inline-block px-2 sm:px-3 text-[6px] sm:text-[8px] md:text-[10.5px] text-[#323C8B] font-bold uppercase rounded-md bg-white/60 whitespace-nowrap">
                                        Jenis Rekap
                                    </div>
                                </div>

                                <!-- Client Name Overlay -->
                                <div class="-mt-1 text-center md:-mt-2">
                                    <div id="preview-client"
                                        class="inline-block px-2 sm:px-3 text-[5px] sm:text-[7px] md:text-[9.5px] text-[#323C8B] font-bold capitalize rounded-md bg-white/60 whitespace-nowrap overflow-hidden max-w-[80px] sm:max-w-[120px] md:max-w-[180px]">
                                        Client Name
                                    </div>
                                </div>

                                <!-- Images Overlay -->
                                <div
                                    class="flex justify-center flex-1 gap-1 mx-auto mt-2 sm:mt-2 md:mt-5 sm:gap-1 md:gap-2">
                                    <!-- Left Image -->
                                    <div class="w-1/2 pr-1">
                                        <div id="preview-overlay-left"
                                            class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-[65px] md:h-[65px] border-2 border-dashed rounded-lg bg-white/50 border-slate-300">
                                            <span class="text-[10px] sm:text-xs md:text-sm text-slate-500">Image
                                                1</span>
                                        </div>
                                    </div>

                                    <!-- Right Image -->
                                    <div class="w-1/2 pl-1">
                                        <div id="preview-overlay-right"
                                            class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-[65px] md:h-[65px] border-2 border-dashed rounded-lg bg-white/50 border-slate-300">
                                            <span class="text-[10px] sm:text-xs md:text-sm text-slate-500">Image
                                                2</span>
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
    </div>

    @push('scripts')
        <script>
            // Use an IIFE to avoid polluting the global namespace
            (function($) {
                'use strict';

                // Constants
                const ASSET_URL = "{{ asset('storage') }}";
                const DEBOUNCE_DELAY = 250;
                const NOTIFICATION_TIMEOUT = 5000;
                const MOBILE_BREAKPOINT = 768;

                // Cache DOM elements
                const elements = {
                    coverModal: $('#coverModal'),
                    coverForm: $('#coverForm'),
                    modalTitle: $('#modalTitle'),
                    submitButtonText: $('#submitButtonText'),
                    coverId: $('#coverId'),
                    clientSelect: $('#client'),
                    jenisRekapRadios: $('input[name="jenis_rekap"]'),
                    img1Input: $('#img_src_1'),
                    img2Input: $('#img_src_2'),
                    preview1: $('#preview1'),
                    preview2: $('#preview2'),
                    previewOverlayLeft: $('#preview-overlay-left'),
                    previewOverlayRight: $('#preview-overlay-right'),
                    previewClient: $('#preview-client'),
                    previewJenisRekap: $('#preview-jenis-rekap')
                };

                // State variables
                const state = {
                    image1Data: null,
                    image2Data: null,
                    isEditMode: false
                };

                // Initialize event listeners
                function init() {
                    // Open modal for create
                    $('#openCoverModal').on('click', openCreateModal);

                    // Close modal
                    $('#closeModalBtn, #cancelButton').on('click', closeModal);

                    // Edit cover buttons
                    $('.edit-cover-btn').on('click', function() {
                        const coverId = $(this).data('cover-id');
                        editCover(coverId);
                    });

                    // Delete cover buttons
                    $('.delete-cover-btn').on('click', function() {
                        const coverId = $(this).data('cover-id');
                        deleteCover(coverId);
                    });

                    // Image change handlers
                    elements.img1Input.on('change', function(e) {
                        handleImageChange(e, 'preview1', 'preview-overlay-left', 'img1_changed');
                    });

                    elements.img2Input.on('change', function(e) {
                        handleImageChange(e, 'preview2', 'preview-overlay-right', 'img2_changed');
                    });

                    // Preview update handlers
                    elements.clientSelect.on('change', updatePreview);
                    elements.jenisRekapRadios.on('change', updateJenisRekapPreview);

                    // Form submission
                    elements.coverForm.on('submit', handleFormSubmit);

                    // Window resize handler with debounce
                    $(window).on('resize', debounce(function() {
                        if (elements.previewClient.text() !== 'Client Name') {
                            resizeTextToFit(elements.previewClient[0]);
                        }
                    }, DEBOUNCE_DELAY));
                }

                // Debounce function to limit how often a function can be called
                function debounce(func, wait) {
                    let timeout;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(context, args), wait);
                    };
                }

                // Modal functions
                function openCreateModal() {
                    resetForm();
                    state.isEditMode = false;
                    elements.modalTitle.text('Add New Cover');
                    elements.coverModal.addClass('modal-open');
                }

                function closeModal() {
                    elements.coverModal.removeClass('modal-open');
                    resetForm();
                }

                // Edit cover function
                function editCover(id) {
                    resetFormFields();
                    state.isEditMode = true;
                    elements.modalTitle.text('Edit Cover');
                    elements.coverId.val(id);

                    $.ajax({
                        url: `/admin/admin-covers/${id}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const data = response.data;

                            // Set client selection
                            elements.clientSelect.val(data.clients_id);
                            elements.clientSelect.trigger('change');

                            // Set jenis_rekap selection
                            elements.jenisRekapRadios.filter(`[value="${data.jenis_rekap}"]`).prop('checked',
                                true);
                            elements.jenisRekapRadios.filter(`[value="${data.jenis_rekap}"]`).trigger('change');

                            // Store existing image paths
                            if (data.img_src_1) {
                                $('#existing_img_src_1').val(data.img_src_1);
                            }
                            if (data.img_src_2) {
                                $('#existing_img_src_2').val(data.img_src_2);
                            }

                            // Load existing images with proper URL construction
                            if (data.img_src_1) {
                                const img1Path = data.img_src_1.replace(/^\/+/, '');
                                const img1Url = `${ASSET_URL}/${img1Path}`;
                                displayImagePreview('preview1', img1Url);
                                state.image1Data = img1Url;
                                updateOverlayImage('preview-overlay-left', img1Url);
                            }

                            if (data.img_src_2) {
                                const img2Path = data.img_src_2.replace(/^\/+/, '');
                                const img2Url = `${ASSET_URL}/${img2Path}`;
                                displayImagePreview('preview2', img2Url);
                                state.image2Data = img2Url;
                                updateOverlayImage('preview-overlay-right', img2Url);
                            }

                            elements.coverModal.addClass('modal-open');
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr, 'Failed to load cover data.');
                        }
                    });
                }

                // Delete cover function
                function deleteCover(id) {
                    if (confirm('Are you sure you want to delete this cover?')) {
                        $.ajax({
                            url: `/admin/admin-covers/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove the row from the table
                                    $(`tr:has(button[data-cover-id="${id}"])`).fadeOut(300, function() {
                                        $(this).remove();

                                        // Update row numbers
                                        updateRowNumbers();

                                        // Show "No covers found" if table is empty
                                        if ($('tbody tr').length === 0) {
                                            $('tbody').html(`
                                                <tr>
                                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                        No covers found
                                                    </td>
                                                </tr>
                                            `);
                                        }
                                    });

                                    showNotification('Cover deleted successfully!', 'success');
                                } else {
                                    showNotification('Error: ' + (response.message || 'Something went wrong'),
                                        'error');
                                }
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr, 'An error occurred while deleting the cover.');
                            }
                        });
                    }
                }

                // Update row numbers after deletion
                function updateRowNumbers() {
                    $('tbody tr').each(function(index) {
                        $(this).find('td:first').text(`${index + 1}.`);
                    });
                }

                // Image handling functions
                function handleImageChange(event, previewId, overlayId, changedFieldId) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            displayImagePreview(previewId, e.target.result);
                            updateOverlayImage(overlayId, e.target.result);

                            // Store image data and mark as changed
                            if (previewId === 'preview1') {
                                state.image1Data = e.target.result;
                            } else {
                                state.image2Data = e.target.result;
                            }
                            $(`#${changedFieldId}`).val('1');
                        };
                        reader.readAsDataURL(file);
                    } else if (state.isEditMode) {
                        // If in edit mode and no new file, keep existing
                        const existingPath = $(`#existing_img_src_${previewId === 'preview1' ? '1' : '2'}`).val();

                        if (existingPath) {
                            const path = existingPath.replace(/^\/+/, '');
                            const imgUrl = `${ASSET_URL}/${path}`;
                            displayImagePreview(previewId, imgUrl);
                            updateOverlayImage(overlayId, imgUrl);

                            if (previewId === 'preview1') {
                                state.image1Data = imgUrl;
                            } else {
                                state.image2Data = imgUrl;
                            }
                        }
                        $(`#${changedFieldId}`).val('0');
                    } else {
                        // Clear preview and overlay
                        $(`#${previewId}`).html('<span class="text-sm text-slate-500">No image selected</span>');
                        $(`#${overlayId}`).html(
                            `<span class="text-sm text-slate-500">Image ${previewId === 'preview1' ? '1' : '2'}</span>`);

                        if (previewId === 'preview1') {
                            state.image1Data = null;
                        } else {
                            state.image2Data = null;
                        }
                        $(`#${changedFieldId}`).val('0');
                    }
                }

                function displayImagePreview(previewId, imageSrc) {
                    $(`#${previewId}`).html(
                        `<img src="${imageSrc}" class="object-contain max-w-full mx-auto max-h-[120px]" alt="Preview">`);
                }

                function updateOverlayImage(overlayId, imageData) {
                    $(`#${overlayId}`).html(`<img src="${imageData}" class="object-contain w-full h-full" alt="Preview">`);
                }

                // Preview update functions
                function updatePreview() {
                    const selectedOption = elements.clientSelect.find('option:selected');
                    const clientName = selectedOption.data('name') || 'Client Name';
                    elements.previewClient.text(clientName);
                    resizeTextToFit(elements.previewClient[0]);
                }

                function updateJenisRekapPreview() {
                    const selectedRadio = elements.jenisRekapRadios.filter(':checked');
                    const text = selectedRadio.length > 0 ? selectedRadio.val() : 'Jenis Rekap';
                    elements.previewJenisRekap.text(text);
                }

                function resizeTextToFit(element) {
                    const parentWidth = element.parentElement.offsetWidth;
                    const isMobile = window.innerWidth < MOBILE_BREAKPOINT;
                    let fontSize = isMobile ? 7 : 9.5;

                    element.style.fontSize = fontSize + 'px';

                    while (element.scrollWidth > parentWidth && fontSize > (isMobile ? 5 : 8)) {
                        fontSize -= 0.5;
                        element.style.fontSize = fontSize + 'px';
                    }
                }

                // Form handling functions
                function resetFormFields() {
                    elements.coverForm[0].reset();
                    elements.coverId.val('');
                    $('#existing_img_src_1').val('');
                    $('#existing_img_src_2').val('');
                    $('#img1_changed').val('0');
                    $('#img2_changed').val('0');
                    elements.preview1.html('<span class="text-sm text-slate-500">No image selected</span>');
                    elements.preview2.html('<span class="text-sm text-slate-500">No image selected</span>');

                    elements.previewClient.text('Client Name');
                    elements.previewClient.css('font-size', '');

                    elements.previewJenisRekap.text('Jenis Rekap');
                    elements.previewOverlayLeft.html('<span class="text-sm text-slate-500">Image 1</span>');
                    elements.previewOverlayRight.html('<span class="text-sm text-slate-500">Image 2</span>');

                    // Hide error messages
                    $('.text-red-500').addClass('hidden');

                    state.image1Data = null;
                    state.image2Data = null;
                }

                function resetForm() {
                    resetFormFields();
                    state.isEditMode = false;
                }

                function validateForm() {
                    let isValid = true;

                    // Validate client selection
                    if (!elements.clientSelect.val()) {
                        $('#client-error').removeClass('hidden');
                        isValid = false;
                    } else {
                        $('#client-error').addClass('hidden');
                    }

                    // Validate jenis rekap selection
                    if (!elements.jenisRekapRadios.is(':checked')) {
                        $('#jenis-rekap-error').removeClass('hidden');
                        isValid = false;
                    } else {
                        $('#jenis-rekap-error').addClass('hidden');
                    }

                    // Validate images
                    if (!state.isEditMode) {
                        if (!elements.img1Input[0].files[0]) {
                            $('#image1-error').removeClass('hidden');
                            isValid = false;
                        } else {
                            $('#image1-error').addClass('hidden');
                        }

                        if (!elements.img2Input[0].files[0]) {
                            $('#image2-error').removeClass('hidden');
                            isValid = false;
                        } else {
                            $('#image2-error').addClass('hidden');
                        }
                    }

                    return isValid;
                }

                function handleFormSubmit(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return;
                    }

                    // Capture the current edit mode state before it gets reset
                    const isEditMode = state.isEditMode;

                    // Create a new FormData object
                    const formData = new FormData();
                    const coverId = elements.coverId.val();

                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    // Add cover ID if in edit mode
                    if (isEditMode) {
                        formData.append('cover_id', coverId);
                        formData.append('_method', 'PUT');
                    }

                    // Add all form fields explicitly
                    formData.append('clients_id', elements.clientSelect.val());
                    formData.append('jenis_rekap', elements.jenisRekapRadios.filter(':checked').val());

                    // Add existing image paths
                    formData.append('existing_img_src_1', $('#existing_img_src_1').val());
                    formData.append('existing_img_src_2', $('#existing_img_src_2').val());

                    // Add image change flags
                    formData.append('img1_changed', $('#img1_changed').val());
                    formData.append('img2_changed', $('#img2_changed').val());

                    // Handle image files
                    if ($('#img1_changed').val() === '1' && elements.img1Input[0].files[0]) {
                        formData.append('img_src_1', elements.img1Input[0].files[0]);
                    } else if (isEditMode) {
                        formData.append('img_src_1', $('#existing_img_src_1').val());
                    }

                    if ($('#img2_changed').val() === '1' && elements.img2Input[0].files[0]) {
                        formData.append('img_src_2', elements.img2Input[0].files[0]);
                    } else if (isEditMode) {
                        formData.append('img_src_2', $('#existing_img_src_2').val());
                    }

                    const submitButton = elements.coverForm.find('button[type="submit"]');
                    const originalText = submitButton.html();
                    submitButton.html('<span class="loading loading-spinner loading-sm"></span> Saving...');
                    submitButton.prop('disabled', true);

                    const url = isEditMode ? `/admin/admin-covers/${coverId}` : '{{ route('admin-covers.store') }}';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.success) {
                                // Update the table dynamically instead of reloading
                                if (isEditMode) {
                                    updateTableRow(data.data);
                                } else {
                                    addNewTableRow(data.data);
                                }

                                closeModal();
                                console.log(isEditMode);

                                showNotification(isEditMode ? 'Cover updated successfully!' :
                                    'Cover created successfully!', 'success');
                            } else {
                                showNotification('Error: ' + (data.message || 'Something went wrong'), 'error');
                            }
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr, 'An error occurred while saving the cover.');
                        },
                        complete: function() {
                            submitButton.html(originalText);
                            submitButton.prop('disabled', false);
                        }
                    });
                }

                // Function to update an existing table row
                function updateTableRow(coverData) {
                    const row = $(`tr:has(button[data-cover-id="${coverData.id}"])`);
                    if (row.length) {
                        // Update client name
                        row.find('td:nth-child(2)').text(coverData.client_name);
                        // Update jenis rekap
                        row.find('td:nth-child(3)').text(coverData.jenis_rekap);
                    }
                }

                // Function to add a new table row
                function addNewTableRow(coverData) {
                    const tbody = $('tbody');
                    const noDataRow = tbody.find('tr:contains("No covers found")');

                    // Remove "No covers found" row if it exists
                    if (noDataRow.length) {
                        noDataRow.remove();
                    }

                    // Create new row HTML
                    const newRow = `
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">${tbody.find('tr').length + 1}.</td>
                            <td class="px-6 py-4 whitespace-nowrap">${coverData.client_name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${coverData.jenis_rekap}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button data-cover-id="${coverData.id}" class="text-blue-600 edit-cover-btn hover:text-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-500">Edit</button>
                                    <button data-cover-id="${coverData.id}" class="text-red-600 delete-cover-btn hover:text-red-900 focus:outline-none focus:ring-1 focus:ring-red-500">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `;

                    // Append the new row to the table
                    tbody.append(newRow);

                    // Re-attach event handlers to the new buttons
                    $(`button[data-cover-id="${coverData.id}"]`).each(function() {
                        const $button = $(this);
                        if ($button.hasClass('edit-cover-btn')) {
                            $button.on('click', function() {
                                const coverId = $(this).data('cover-id');
                                editCover(coverId);
                            });
                        } else if ($button.hasClass('delete-cover-btn')) {
                            $button.on('click', function() {
                                const coverId = $(this).data('cover-id');
                                deleteCover(coverId);
                            });
                        }
                    });
                }

                // Utility functions
                function handleAjaxError(xhr, defaultMessage) {
                    console.error('Error:', xhr);
                    try {
                        const response = JSON.parse(xhr.responseText);
                        let errorMessage = 'Error: ';

                        if (response.errors) {
                            errorMessage += Object.values(response.errors).join('<br>');
                        } else {
                            errorMessage += (response.message || defaultMessage);
                        }

                        showNotification(errorMessage, 'error');
                    } catch (e) {
                        showNotification(defaultMessage, 'error');
                    }
                }

                function showNotification(message, type) {
                    // Create a more user-friendly notification instead of alert
                    const notification = $(`
                        <div class="fixed top-4 right-4 p-4 rounded-md shadow-lg ${type === 'error' ? 'bg-red-500' : 'bg-green-500'} text-white z-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    ${type === 'error' 
                                        ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                                        : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">${message}</p>
                                </div>
                                <div class="pl-3 ml-auto">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button class="close-notification inline-flex rounded-md p-1.5 hover:bg-white/20 focus:outline-none">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                    $('body').append(notification);

                    // Auto-remove after timeout
                    setTimeout(function() {
                        notification.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, NOTIFICATION_TIMEOUT);

                    // Remove when close button is clicked
                    notification.find('.close-notification').on('click', function() {
                        notification.fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                }

                // Initialize when DOM is ready
                $(document).ready(init);
            })(jQuery);
        </script>
    @endpush
</x-app-layout>
