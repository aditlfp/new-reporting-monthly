<x-app-layout>
    <div class="flex flex-col min-h-screen bg-slate-50">
        <x-user-navbar />
        <div class="flex flex-1">
            <x-user-sidebar />

            <main class="flex-1 min-w-0 p-2 m-1 sm:m-2 xs:p-2 sm:p-4 md:p-6">
                <div class="w-full max-w-6xl mx-auto space-y-6">
                    <section class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="grid gap-0 md:grid-cols-[1.4fr_1fr]">
                            <div class="px-5 py-6 border-b md:px-7 md:py-8 md:border-b-0 md:border-r border-slate-200">
                                <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase">Upload File
                                    Tambahan</p>
                                <h2 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Tambah Dokumen Pendukung
                                </h2>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Gunakan form ini untuk unggah dokumen PDF atau gambar sebagai lampiran laporan.
                                    Proses upload memakai chunk agar lebih aman untuk file berukuran besar.
                                </p>
                                <div class="flex flex-wrap gap-2 mt-4">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Maks
                                        30 item</span>
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">PDF
                                        / JPG / PNG / WEBP</span>
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Chunk
                                        Upload</span>
                                </div>
                            </div>
                            <div class="grid content-between gap-4 px-5 py-6 md:px-6 md:py-8 bg-slate-50">
                                <div>
                                    <p class="text-xs font-semibold tracking-wide uppercase text-slate-500">Aksi Cepat
                                    </p>
                                    <a href="{{ route('upload-tambahan.show') }}"
                                        class="inline-flex items-center justify-center w-full px-4 py-2.5 mt-3 text-sm font-semibold text-blue-700 transition bg-white border border-blue-200 rounded-lg hover:bg-blue-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z">
                                            </path>
                                            <circle cx="12" cy="12" r="3" stroke-width="2"></circle>
                                        </svg>
                                        Lihat Riwayat Upload
                                    </a>
                                </div>
                                <div class="p-3 border rounded-xl border-emerald-200 bg-emerald-50">
                                    <p class="text-xs font-semibold tracking-wide uppercase text-emerald-700">Status</p>
                                    <p class="mt-1 text-sm font-medium text-emerald-800">Pipeline upload siap digunakan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-4 lg:grid-cols-[2fr_1fr]">
                        <div class="bg-white border shadow-sm rounded-2xl border-slate-200">
                            <div
                                class="flex flex-col gap-3 p-4 border-b md:p-5 border-slate-200 md:flex-row md:items-center md:justify-between bg-slate-50/70 rounded-t-2xl">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Form Upload</h3>
                                    <p class="text-sm text-slate-500">Setiap item wajib file berhasil upload dan
                                        keterangan terisi.</p>
                                </div>
                                <button id="addItemBtn" type="button"
                                    class="inline-flex items-center justify-center w-full md:w-auto px-4 py-2.5 text-sm font-semibold text-white transition rounded-lg bg-blue-600 hover:bg-blue-700 disabled:bg-slate-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Item
                                </button>
                            </div>

                            <form id="uploadTambahanForm"
                                class="p-4 space-y-4 md:p-5 bg-gradient-to-b from-white to-slate-50/60 rounded-b-2xl">
                                @csrf
                                <div id="itemsContainer" class="space-y-3"></div>
                                <div id="emptyState"
                                    class="hidden p-6 text-center border border-dashed rounded-xl border-slate-300 bg-slate-50">
                                    <p class="text-sm text-slate-500">Belum ada item. Klik <span
                                            class="font-semibold text-slate-700">+ Tambah Item</span> untuk mulai
                                        upload.</p>
                                </div>

                                <div class="flex flex-col gap-3 pt-2 md:flex-row md:items-center md:justify-between">
                                    <div
                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-blue-700 rounded-lg bg-blue-50">
                                        Item aktif: <span id="itemCount" class="ml-1">0</span>/30
                                    </div>
                                    <button type="submit"
                                        class="w-full md:w-auto px-5 py-2.5 text-sm font-semibold text-white transition bg-green-600 rounded-lg hover:bg-green-700 disabled:bg-slate-400 flex items-center"
                                        id="submitBtn">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Upload Tambahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200 md:p-5">
                                <h4 class="text-sm font-semibold tracking-wide uppercase text-slate-700">Panduan</h4>
                                <ul class="mt-3 space-y-3 text-sm text-slate-600">
                                    <li class="flex gap-2">
                                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Maksimum total item per submit: 30 item.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Jika file gagal upload, pilih ulang file pada item tersebut.
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Pastikan keterangan menjelaskan isi dokumen.
                                    </li>
                                </ul>
                            </div>
                            <div class="p-4 border shadow-sm rounded-2xl md:p-5 border-sky-200 bg-sky-50">
                                <p class="text-sm font-semibold text-sky-700">Catatan Keamanan</p>
                                <p class="mt-1 text-xs leading-6 text-sky-700/90">
                                    File diproses melalui temporary chunk dan hanya dipindahkan ke storage final saat
                                    upload dinyatakan valid.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const routes = {
                    init: @json(route('upload-tambahan.chunk.init')),
                    upload: @json(route('upload-tambahan.chunk.upload')),
                    finalize: @json(route('upload-tambahan.chunk.finalize')),
                    cancel: @json(route('upload-tambahan.chunk.cancel')),
                    store: @json(route('upload-tambahan.store')),
                };
                const csrf = @json(csrf_token());
                const maxItems = 30;
                const chunkSize = 1024 * 512;
                const acceptedMime = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];

                const itemsContainer = document.getElementById('itemsContainer');
                const addItemBtn = document.getElementById('addItemBtn');
                const itemCountEl = document.getElementById('itemCount');
                const submitBtn = document.getElementById('submitBtn');
                const form = document.getElementById('uploadTambahanForm');

                const states = new Map();
                const emptyState = document.getElementById('emptyState');
                let rowSequence = 0;

                function notify(message, type = 'info') {
                    if (window.Notify) {
                        window.Notify(message, null, null, type);
                    } else {
                        alert(message);
                    }
                }

                function updateItemCount() {
                    const rows = Array.from(itemsContainer.querySelectorAll('[data-item-row]'));
                    rows.forEach((row, idx) => {
                        const label = row.querySelector('.item-label');
                        if (label) {
                            label.textContent = `Item ${idx + 1}`;
                        }
                    });
                    const count = rows.length;
                    itemCountEl.textContent = String(count);
                    addItemBtn.disabled = count >= maxItems;
                    emptyState.classList.toggle('hidden', count > 0);
                }

                function createItemRow(rowKey) {
                    const row = document.createElement('div');
                    row.className = 'overflow-hidden border rounded-2xl border-slate-200 bg-white shadow-sm';
                    row.dataset.itemRow = '1';
                    row.dataset.itemKey = rowKey;

                    row.innerHTML = `
                        <div class="p-4 md:p-5">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-200 rounded-full bg-blue-50 item-label">Item</span>
                                <button type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white transition bg-red-500 rounded-lg hover:bg-red-600 tambahan-remove-btn">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-slate-700">File <span class="text-red-500">*</span></label>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-2.5">
                                        <input id="file-input-${rowKey}" type="file" accept=".pdf" class="sr-only tambahan-file-input">
                                        <label for="file-input-${rowKey}" class="flex flex-wrap items-center justify-between gap-2 px-3 py-2 transition bg-white border rounded-lg cursor-pointer border-slate-200 hover:border-blue-300 hover:bg-blue-50/40">
                                            <span class="text-sm font-medium text-blue-700">Pilih File</span>
                                            <span class="text-xs text-slate-500 tambahan-file-name truncate max-w-[150px] sm:max-w-[220px] text-left sm:text-right">Belum ada file dipilih</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-flex w-2 h-2 rounded-full bg-slate-300 tambahan-file-dot"></span>
                                        <p class="text-xs text-slate-500 tambahan-file-status">Belum upload</p>
                                    </div>
                                    <div class="hidden mt-3 tambahan-preview-wrap">
                                        <p class="mb-1 text-xs font-medium text-slate-500">Preview Gambar</p>
                                        <img src="" alt="Preview gambar"
                                            class="object-cover w-full max-w-xs border rounded-lg tambahan-preview-image border-slate-200">
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Keterangan <span class="text-red-500">*</span></label>
                                    <textarea rows="3" class="w-full px-3 py-2 text-sm transition bg-white border rounded-lg border-slate-200 tambahan-keterangan focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" placeholder="Isi keterangan file"></textarea>
                                </div>
                                <input type="hidden" class="tambahan-temp-token" value="">
                            </div>
                        </div>
                    `;

                    bindRowEvents(row);
                    return row;
                }

                async function sendChunk(url, formData) {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Chunk upload gagal.');
                    }
                    return result;
                }

                async function cancelCurrentUpload(itemKey) {
                    const state = states.get(itemKey);
                    if (!state) return;

                    try {
                        const formData = new FormData();
                        if (state.tempToken) {
                            formData.append('temp_token', state.tempToken);
                        } else if (state.uploadId) {
                            formData.append('upload_id', state.uploadId);
                        } else {
                            return;
                        }
                        await sendChunk(routes.cancel, formData);
                    } catch (error) {
                        console.warn(error);
                    }
                }

                async function uploadFileByChunk(row, file) {
                    const itemKey = row.dataset.itemKey;
                    const statusEl = row.querySelector('.tambahan-file-status');
                    const tokenInput = row.querySelector('.tambahan-temp-token');

                    if (!acceptedMime.includes(file.type)) {
                        throw new Error('Format file tidak diizinkan.');
                    }

                    await cancelCurrentUpload(itemKey);
                    states.set(itemKey, {
                        uploadId: null,
                        tempToken: null,
                        uploading: true,
                        error: null,
                    });
                    tokenInput.value = '';
                    statusEl.textContent = 'Menyiapkan upload...';
                    statusEl.className = 'mt-1 text-xs text-blue-600 tambahan-file-status';

                    const totalChunks = Math.max(1, Math.ceil(file.size / chunkSize));
                    const initForm = new FormData();
                    initForm.append('file_name', file.name);
                    initForm.append('file_size', file.size.toString());
                    initForm.append('mime_type', file.type);
                    initForm.append('total_chunks', totalChunks.toString());
                    const initResult = await sendChunk(routes.init, initForm);

                    const state = states.get(itemKey);
                    state.uploadId = initResult.upload_id;

                    for (let i = 0; i < totalChunks; i++) {
                        const chunkForm = new FormData();
                        const start = i * chunkSize;
                        const end = Math.min(file.size, start + chunkSize);
                        chunkForm.append('upload_id', state.uploadId);
                        chunkForm.append('chunk_index', String(i));
                        chunkForm.append('chunk', file.slice(start, end), `${file.name}.part${i}`);
                        await sendChunk(routes.upload, chunkForm);

                        const progress = Math.round(((i + 1) / totalChunks) * 100);
                        statusEl.textContent = `Mengupload... ${progress}%`;
                    }

                    const finalizeForm = new FormData();
                    finalizeForm.append('upload_id', state.uploadId);
                    const finalizeResult = await sendChunk(routes.finalize, finalizeForm);

                    state.uploadId = null;
                    state.tempToken = finalizeResult.temp_token;
                    state.uploading = false;
                    state.error = null;
                    tokenInput.value = finalizeResult.temp_token;
                    statusEl.textContent = 'Upload selesai';
                    statusEl.className = 'mt-1 text-xs text-green-600 tambahan-file-status';
                }

                function bindRowEvents(row) {
                    const removeBtn = row.querySelector('.tambahan-remove-btn');
                    const fileInput = row.querySelector('.tambahan-file-input');
                    const fileNameEl = row.querySelector('.tambahan-file-name');
                    const fileDotEl = row.querySelector('.tambahan-file-dot');
                    const previewWrapEl = row.querySelector('.tambahan-preview-wrap');
                    const previewImageEl = row.querySelector('.tambahan-preview-image');
                    const itemKey = row.dataset.itemKey;

                    states.set(itemKey, {
                        uploadId: null,
                        tempToken: null,
                        uploading: false,
                        error: null,
                    });

                    removeBtn.addEventListener('click', async () => {
                        if (previewImageEl?.dataset?.objectUrl) {
                            URL.revokeObjectURL(previewImageEl.dataset.objectUrl);
                            delete previewImageEl.dataset.objectUrl;
                        }
                        await cancelCurrentUpload(itemKey);
                        states.delete(itemKey);
                        row.remove();
                        updateItemCount();
                    });

                    fileInput.addEventListener('change', async (event) => {
                        const file = event.target.files?.[0];
                        const statusEl = row.querySelector('.tambahan-file-status');
                        if (!file) return;

                        if (previewImageEl.dataset.objectUrl) {
                            URL.revokeObjectURL(previewImageEl.dataset.objectUrl);
                            delete previewImageEl.dataset.objectUrl;
                        }

                        fileNameEl.textContent = file.name;
                        fileDotEl.className = 'inline-flex w-2 h-2 rounded-full bg-blue-500 tambahan-file-dot';
                        if (file.type.startsWith('image/')) {
                            const objectUrl = URL.createObjectURL(file);
                            previewImageEl.src = objectUrl;
                            previewImageEl.dataset.objectUrl = objectUrl;
                            previewWrapEl.classList.remove('hidden');
                        } else {
                            previewImageEl.src = '';
                            previewWrapEl.classList.add('hidden');
                        }

                        try {
                            await uploadFileByChunk(row, file);
                            fileDotEl.className =
                                'inline-flex w-2 h-2 rounded-full bg-emerald-500 tambahan-file-dot';
                            row.classList.remove('border-red-300');
                            row.classList.add('border-emerald-200');
                        } catch (error) {
                            const state = states.get(itemKey);
                            if (state) {
                                state.uploading = false;
                                state.error = error.message;
                            }
                            statusEl.textContent = error.message || 'Upload gagal';
                            statusEl.className = 'mt-1 text-xs text-red-600 tambahan-file-status';
                            fileDotEl.className =
                                'inline-flex w-2 h-2 rounded-full bg-red-500 tambahan-file-dot';
                            row.classList.remove('border-emerald-200');
                            row.classList.add('border-red-300');
                            notify(error.message || 'Upload file gagal', 'error');
                        }
                    });
                }

                function addItem() {
                    const count = itemsContainer.querySelectorAll('[data-item-row]').length;
                    if (count >= maxItems) {
                        notify('Maksimal 30 item per submit.', 'warning');
                        return;
                    }
                    const rowKey = `row-${rowSequence++}`;
                    itemsContainer.appendChild(createItemRow(rowKey));
                    updateItemCount();
                }

                function hasBlockingUpload() {
                    return Array.from(states.values()).some((state) => state.uploading);
                }

                function hasErroredUpload() {
                    return Array.from(states.values()).some((state) => !!state.error);
                }

                addItemBtn.addEventListener('click', addItem);

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (hasBlockingUpload()) {
                        notify('Masih ada file yang sedang diupload.', 'warning');
                        return;
                    }
                    if (hasErroredUpload()) {
                        notify('Masih ada file gagal upload. Perbaiki dahulu.', 'warning');
                        return;
                    }

                    const rows = Array.from(itemsContainer.querySelectorAll('[data-item-row]'));
                    if (rows.length === 0) {
                        notify('Tambahkan minimal satu item upload.', 'warning');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', csrf);

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const token = row.querySelector('.tambahan-temp-token').value.trim();
                        const keterangan = row.querySelector('.tambahan-keterangan').value.trim();
                        if (!token) {
                            notify(`Item ke-${i + 1} belum selesai upload file.`, 'warning');
                            return;
                        }
                        if (!keterangan) {
                            notify(`Keterangan item ke-${i + 1} wajib diisi.`, 'warning');
                            return;
                        }
                        formData.append(`items[${i}][temp_token]`, token);
                        formData.append(`items[${i}][keterangan]`, keterangan);
                    }

                    submitBtn.disabled = true;
                    try {
                        const response = await fetch(routes.store, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });
                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result.message || 'Gagal menyimpan upload tambahan.');
                        }

                        notify(result.message || 'Upload tambahan berhasil disimpan.', 'success');
                        window.location.reload();
                    } catch (error) {
                        notify(error.message || 'Gagal menyimpan upload tambahan.', 'error');
                    } finally {
                        submitBtn.disabled = false;
                    }
                });

                addItem();
            })();
        </script>
    @endpush
</x-app-layout>
