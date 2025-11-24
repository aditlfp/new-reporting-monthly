<x-app-layout>
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-slate-900">Letters Reports</h1>
                <button id="openLetterModal"
                    class="px-4 py-2 text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Add New Letters
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                No. Surat
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Mitra</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Jenis Rekap</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Matters</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Periode</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Content</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                TTD</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($letters as $letter)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->latter_numbers }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ ucwords(strtolower($letter->cover->client->name)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ ucwords(strtolower($letter->cover->jenis_rekap)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->latter_matters }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->period }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->report_content }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->signature }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button data-letter-id="{{ $letter->id }}"
                                            class="text-blue-600 edit-letter-btn hover:text-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-500">Edit</button>
                                        <button data-letter-id="{{ $letter->id }}"
                                            class="text-red-600 delete-letter-btn hover:text-red-900 focus:outline-none focus:ring-1 focus:ring-red-500">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No letters found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($letters->hasPages())
                    <div class="flex justify-center mt-4">
                        {{ $letters->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Letter create/edit modal --}}
    <div id="letterModal" class="modal">
        <div class="max-w-4xl bg-white modal-box max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-4 mb-6 md:px-6">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-900">Add New Letter</h3>
                <button id="closeModalBtn"
                    class="transition-colors btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </div>

            <div class="grid grid-cols-1 gap-6 px-4 md:px-6">
                <!-- Letter Form -->
                <div class="p-4 border rounded-lg md:p-6 bg-slate-50 border-slate-200">
                    <h4 class="mb-4 text-sm font-semibold text-slate-700">Letter Information</h4>
                    <form id="letterForm" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf
                        <!-- Hidden field for letter ID (used for edit) -->
                        <input type="hidden" id="letterId" name="letter_id" value="">
                        <!-- Hidden field to track if file has changed -->
                        <input type="hidden" id="file_changed" name="file_changed" value="0">
                        <!-- Hidden field to store existing file path -->
                        <input type="hidden" id="existing_file_path" name="existing_file_path" value="">

                        <!-- Cover Selection -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="cover_id">Cover</label>
                            <select id="cover_id" name="cover_id"
                                class="w-full bg-white select select-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required>
                                <option value="" disabled selected>Select a cover</option>
                                @foreach ($covers as $cover)
                                    <option value="{{ $cover->id }}">
                                        {{ $cover->latter_numbers }} - {{ ucwords(strtolower($cover->client->name)) }}
                                        ({{ $cover->jenis_rekap }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="hidden text-xs text-red-500" id="cover-error">Please select a cover</span>
                        </div>

                        <!-- File input for letter -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="letter_file">Letter
                                File</label>
                            <input type="file" id="letter_file" name="letter_file"
                                class="w-full bg-white file-input file-input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept=".pdf,.doc,.docx">
                            <div id="filePreview" class="mt-2 text-sm text-slate-500">
                                <span>No file selected</span>
                            </div>
                            <span class="hidden text-xs text-red-500" id="file-error">Please select a letter file</span>
                        </div>

                        <!-- Letter Number -->
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="latter_numbers">No.
                                Surat</label>
                            <input type="text" id="latter_numbers" name="latter_numbers"
                                class="w-full bg-white input input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Enter letter number" required>
                            <span class="hidden text-xs text-red-500" id="number-error">Please enter a letter
                                number</span>
                        </div>

                        <!-- Matters -->
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600"
                                for="latter_matters">Matters</label>
                            <input type="text" id="latter_matters" name="latter_matters"
                                class="w-full bg-white input input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Enter matters" required>
                            <span class="hidden text-xs text-red-500" id="matters-error">Please enter matters</span>
                        </div>

                        <!-- Period -->
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600"
                                for="period">Periode</label>
                            <input type="text" id="period" name="period"
                                class="w-full bg-white input input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Enter period" required>
                            <span class="hidden text-xs text-red-500" id="period-error">Please enter period</span>
                        </div>

                        <!-- Signature -->
                        <div>
                            <label class="block mb-2 text-xs font-medium text-slate-600" for="signature">TTD</label>
                            <input type="text" id="signature" name="signature"
                                class="w-full bg-white input input-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Enter signature">
                            <span class="hidden text-xs text-red-500" id="signature-error">Please enter
                                signature</span>
                        </div>

                        <!-- Content -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-medium text-slate-600"
                                for="report_content">Content</label>
                            <textarea id="report_content" name="report_content" rows="3"
                                class="w-full bg-white textarea textarea-bordered focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Enter content"></textarea>
                            <span class="hidden text-xs text-red-500" id="content-error">Please enter content</span>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col gap-2 sm:flex-row md:col-span-2">
                            <button type="submit"
                                class="text-white transition-colors border-none btn bg-slate-900 hover:bg-slate-800 focus:ring-2 focus:ring-slate-500 focus:outline-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span id="submitButtonText">Save Letter</span>
                            </button>
                            <button type="button" id="cancelButton"
                                class="transition-colors btn btn-ghost hover:bg-gray-200">Cancel</button>
                        </div>
                    </form>
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

                // Cache DOM elements
                const elements = {
                    letterModal: $('#letterModal'),
                    letterForm: $('#letterForm'),
                    modalTitle: $('#modalTitle'),
                    submitButtonText: $('#submitButtonText'),
                    letterId: $('#letterId'),
                    letterFileInput: $('#letter_file'),
                    filePreview: $('#filePreview'),
                    fileChanged: $('#file_changed'),
                    existingFilePath: $('#existing_file_path'),
                    coverSelect: $('#cover_id'),
                    latterNumbersInput: $('#latter_numbers'),
                    latterMattersInput: $('#latter_matters'),
                    periodInput: $('#period'),
                    reportContentTextarea: $('#report_content'),
                    signatureInput: $('#signature')
                };

                // State variables
                const state = {
                    isEditMode: false,
                    fileData: null
                };

                // Initialize event listeners
                function init() {
                    // Open modal for create
                    $('#openLetterModal').on('click', openCreateModal);

                    // Close modal
                    $('#closeModalBtn, #cancelButton').on('click', closeModal);

                    // Edit letter buttons
                    $('.edit-letter-btn').on('click', function() {
                        const letterId = $(this).data('letter-id');
                        editLetter(letterId);
                    });

                    // Delete letter buttons
                    $('.delete-letter-btn').on('click', function() {
                        const letterId = $(this).data('letter-id');
                        deleteLetter(letterId);
                    });

                    // File change handler
                    elements.letterFileInput.on('change', handleFileChange);

                    // Form submission
                    elements.letterForm.on('submit', handleFormSubmit);
                }


                // Modal functions
                function openCreateModal() {
                    resetForm();
                    state.isEditMode = false;
                    elements.modalTitle.text('Add New Letter');
                    elements.submitButtonText.text('Save Letter');
                    elements.letterModal.addClass('modal-open');
                }

                function closeModal() {
                    elements.letterModal.removeClass('modal-open');
                    resetForm();
                }

                // Edit letter function
                function editLetter(id) {
                    resetFormFields();
                    state.isEditMode = true;
                    elements.modalTitle.text('Edit Letter');
                    elements.submitButtonText.text('Update Letter');
                    elements.letterId.val(id);

                    $.ajax({
                        url: `/letters/${id}/edit`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const data = response.data;

                            // Set form fields
                            elements.coverSelect.val(data.cover_id);
                            elements.latterNumbersInput.val(data.latter_numbers);
                            elements.latterMattersInput.val(data.latter_matters);
                            elements.periodInput.val(data.period);
                            elements.reportContentTextarea.val(data.report_content || '');
                            elements.signatureInput.val(data.signature || '');

                            // Store existing file path
                            if (data.file_path) {
                                elements.existingFilePath.val(data.file_path);
                                elements.filePreview.html(`<span>${data.file_name}</span>`);
                                state.fileData = data.file_path;
                            }

                            elements.letterModal.addClass('modal-open');
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr, 'Failed to load letter data.');
                        }
                    });
                }

                // Delete letter function
                function deleteLetter(id) {
                    if (confirm('Are you sure you want to delete this letter?')) {
                        $.ajax({
                            url: `/letters/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove the row from the table
                                    $(`tr:has(button[data-letter-id="${id}"])`).fadeOut(300, function() {
                                        $(this).remove();

                                        // Show "No letters found" if table is empty
                                        if ($('tbody tr').length === 0) {
                                            $('tbody').html(`
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No letters found
                                        </td>
                                    </tr>
                                `);
                                        }
                                    });

                                    showNotification('Letter deleted successfully!', 'success');
                                } else {
                                    showNotification('Error: ' + (response.message || 'Something went wrong'),
                                        'error');
                                }
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr, 'An error occurred while deleting the letter.');
                            }
                        });
                    }
                }

                // File handling functions
                function handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Display file name
                        elements.filePreview.html(`<span>${file.name}</span>`);

                        // Mark file as changed
                        elements.fileChanged.val('1');

                        // Store file data
                        state.fileData = file;
                    } else if (state.isEditMode) {
                        // If in edit mode and no new file, keep existing
                        const existingPath = elements.existingFilePath.val();
                        if (existingPath) {
                            elements.filePreview.html(`<span>${existingPath.split('/').pop()}</span>`);
                            state.fileData = existingPath;
                        }
                        elements.fileChanged.val('0');
                    } else {
                        // Clear preview
                        elements.filePreview.html('<span>No file selected</span>');
                        state.fileData = null;
                        elements.fileChanged.val('0');
                    }
                }

                // Form handling functions
                function resetFormFields() {
                    elements.letterForm[0].reset();
                    elements.letterId.val('');
                    elements.fileChanged.val('0');
                    elements.existingFilePath.val('');
                    elements.filePreview.html('<span>No file selected</span>');

                    // Hide error messages
                    $('.text-red-500').addClass('hidden');

                    state.fileData = null;
                }

                function resetForm() {
                    resetFormFields();
                    state.isEditMode = false;
                }

                function validateForm() {
                    let isValid = true;

                    // Validate cover selection
                    if (!elements.coverSelect.val()) {
                        $('#cover-error').removeClass('hidden');
                        isValid = false;
                    } else {
                        $('#cover-error').addClass('hidden');
                    }

                    // Validate file input (only required for new letters)
                    if (!state.isEditMode && !elements.letterFileInput[0].files[0]) {
                        $('#file-error').removeClass('hidden');
                        isValid = false;
                    } else {
                        $('#file-error').addClass('hidden');
                    }

                    // Validate required fields
                    const requiredFields = [{
                            id: 'latter_numbers',
                            errorId: 'number-error'
                        },
                        {
                            id: 'latter_matters',
                            errorId: 'matters-error'
                        },
                        {
                            id: 'period',
                            errorId: 'period-error'
                        }
                    ];

                    requiredFields.forEach(field => {
                        const input = $(`#${field.id}`);
                        if (!input.val().trim()) {
                            $(`#${field.errorId}`).removeClass('hidden');
                            isValid = false;
                        } else {
                            $(`#${field.errorId}`).addClass('hidden');
                        }
                    });

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
                    const letterId = elements.letterId.val();

                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    // Add letter ID if in edit mode
                    if (isEditMode) {
                        formData.append('letter_id', letterId);
                        formData.append('_method', 'PUT');
                    }

                    // Add all form fields explicitly
                    formData.append('cover_id', elements.coverSelect.val());
                    formData.append('latter_numbers', elements.latterNumbersInput.val());
                    formData.append('latter_matters', elements.latterMattersInput.val());
                    formData.append('period', elements.periodInput.val());
                    formData.append('report_content', elements.reportContentTextarea.val() || '');
                    formData.append('signature', elements.signatureInput.val() || '');

                    // Add existing file path
                    formData.append('existing_file_path', elements.existingFilePath.val());

                    // Add file change flag
                    formData.append('file_changed', elements.fileChanged.val());

                    // Handle file
                    if (elements.fileChanged.val() === '1' && elements.letterFileInput[0].files[0]) {
                        formData.append('letter_file', elements.letterFileInput[0].files[0]);
                    } else if (isEditMode) {
                        formData.append('letter_file', elements.existingFilePath.val());
                    }

                    const submitButton = elements.letterForm.find('button[type="submit"]');
                    const originalText = submitButton.html();
                    submitButton.html('<span class="loading loading-spinner loading-sm"></span> Saving...');
                    submitButton.prop('disabled', true);

                    const url = isEditMode ? `/admin-latters/${letterId}` : 'admin-latters';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            console.log(data)
                            if (data && data.status) {
                                // Update the table dynamically instead of reloading
                                if (isEditMode) {
                                    updateTableRow(data.data);
                                } else {
                                    addNewTableRow(data.data);
                                }

                                closeModal();
                                Notify(data.message,null,null,'success');
                            } else {
                                Notify(data.message,null,null,'error');
                            }
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr, 'An error occurred while saving the letter.');
                        },
                        complete: function() {
                            submitButton.html(originalText);
                            submitButton.prop('disabled', false);
                        }
                    });
                }

                // Function to update an existing table row
                function updateTableRow(letterData) {
                    const row = $(`tr:has(button[data-letter-id="${letterData.id}"])`);
                    if (row.length) {
                        // Update all cells
                        row.find('td:nth-child(1)').text(letterData.latter_numbers);
                        row.find('td:nth-child(2)').text(letterData.client_name);
                        row.find('td:nth-child(3)').text(letterData.jenis_rekap);
                        row.find('td:nth-child(4)').text(letterData.latter_matters);
                        row.find('td:nth-child(5)').text(letterData.period);
                        row.find('td:nth-child(6)').text(letterData.report_content || '');
                        row.find('td:nth-child(7)').text(letterData.signature || '');
                    }
                }

                // Function to add a new table row
                function addNewTableRow(letterData) {
                    const tbody = $('tbody');
                    const noDataRow = tbody.find('tr:contains("No letters found")');

                    // Remove "No letters found" row if it exists
                    if (noDataRow.length) {
                        noDataRow.remove();
                    }

                    // Create new row HTML
                    const newRow = `
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.latter_numbers}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.client_name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.jenis_rekap}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.latter_matters}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.period}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.report_content || ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${letterData.signature || ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button data-letter-id="${letterData.id}" class="text-blue-600 edit-letter-btn hover:text-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-500">Edit</button>
                                    <button data-letter-id="${letterData.id}" class="text-red-600 delete-letter-btn hover:text-red-900 focus:outline-none focus:ring-1 focus:ring-red-500">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `;

                    // Append the new row to the table
                    tbody.append(newRow);

                    // Re-attach event handlers to the new buttons
                    $(`button[data-letter-id="${letterData.id}"]`).each(function() {
                        const $button = $(this);
                        if ($button.hasClass('edit-letter-btn')) {
                            $button.on('click', function() {
                                const letterId = $(this).data('letter-id');
                                editLetter(letterId);
                            });
                        } else if ($button.hasClass('delete-letter-btn')) {
                            $button.on('click', function() {
                                const letterId = $(this).data('letter-id');
                                deleteLetter(letterId);
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

                        Notify(errorMessage,null,null,'error');
                    } catch (e) {
                        Notify(defaultMessage,null,null,'error');
                    }
                }

                // Initialize when DOM is ready
                $(document).ready(init);
            })(jQuery);
        </script>
    @endpush
</x-app-layout>
