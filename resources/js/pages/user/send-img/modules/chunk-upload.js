import { debounce, notify } from './dom-helpers';
import { normalizeImageFile } from './image-processor';

export function createChunkUploadManager(config, stateRef) {
  const CHUNK_SIZE = 1024 * 512;
  const imageSlotConfig = {
    1: { inputId: 'image1', field: 'img_before', previewId: 'preview1', uploadStateId: 'uploadState1', existingInputId: 'existing_img_before', tempInputId: 'temp_img_before' },
    2: { inputId: 'image2', field: 'img_proccess', previewId: 'preview2', uploadStateId: 'uploadState2', existingInputId: 'existing_img_proccess', tempInputId: 'temp_img_proccess' },
    3: { inputId: 'image3', field: 'img_final', previewId: 'preview3', uploadStateId: 'uploadState3', existingInputId: 'existing_img_final', tempInputId: 'temp_img_final' },
  };

  const uploadState = {
    image1: createEmptyUploadState(),
    image2: createEmptyUploadState(),
    image3: createEmptyUploadState(),
  };

  function createEmptyUploadState() {
    return {
      status: 'idle', progress: 0, uploadId: null, tempToken: null,
      sourceFile: null, processedFile: null, previewUrl: null,
      countedUpload: false, cancelRequested: false, sessionId: null,
    };
  }

  const getSlotConfig = (idxOrId) => typeof idxOrId === 'number'
    ? imageSlotConfig[idxOrId]
    : Object.values(imageSlotConfig).find((slot) => slot.inputId === idxOrId);

  const getSlotIndex = (inputId) => Number(inputId.replace('image', ''));

  function setUploadUi(index, uiState, progress, message, options = {}) {
    const progressCard = $(`#${getSlotConfig(index).uploadStateId}`);
    progressCard.removeClass('hidden').attr('data-state', uiState);
    progressCard.find('.upload-status').text(message);
    progressCard.find('.upload-percent').text(`${progress}%`);
    progressCard.find('.upload-progress-bar > span').css('width', `${progress}%`);
    progressCard.find('.upload-spinner').toggleClass('hidden', !(uiState === 'preparing' || uiState === 'uploading'));
    progressCard.find('.upload-retry').toggleClass('hidden', !options.showRetry);
  }

  function hideUploadUi(index) {
    const progressCard = $(`#${getSlotConfig(index).uploadStateId}`);
    progressCard.addClass('hidden').attr('data-state', 'idle');
    progressCard.find('.upload-progress-bar > span').css('width', '0%');
    progressCard.find('.upload-percent').text('0%');
    progressCard.find('.upload-status').text('Menunggu upload');
    progressCard.find('.upload-spinner').addClass('hidden');
    progressCard.find('.upload-retry').addClass('hidden');
  }

  function setPreview(index, previewUrl, isNewImage = true) {
    const preview = $(`#${getSlotConfig(index).previewId}`);
    preview.find('img').attr('src', previewUrl);
    preview.removeClass('hidden').data('is-new-image', isNewImage);
  }

  function revokePreviewUrlIfNeeded(inputId) {
    const state = uploadState[inputId];
    if (state.previewUrl?.startsWith('blob:')) URL.revokeObjectURL(state.previewUrl);
    state.previewUrl = null;
  }

  async function sendChunkRequest(url, formData, method = 'POST') {
    const response = await fetch(url, {
      method,
      headers: { 'X-CSRF-TOKEN': config.csrfToken, Accept: 'application/json' },
      body: formData,
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.message || 'Chunk upload gagal diproses.');
    return result;
  }

  async function cancelTempUpload(inputId) {
    const state = uploadState[inputId];
    try {
      if (state.tempToken) {
        const formData = new FormData();
        formData.append('temp_token', state.tempToken);
        await sendChunkRequest(config.routes.chunkCancel, formData);
      } else if (state.uploadId) {
        const formData = new FormData();
        formData.append('upload_id', state.uploadId);
        await sendChunkRequest(config.routes.chunkCancel, formData);
      }
    } catch (error) {
      console.warn('Cancel temp upload failed', error);
    }
  }

  function resetImageSlot(index, options = {}) {
    const configSlot = getSlotConfig(index);
    const state = uploadState[configSlot.inputId];
    const { clearPreview = true, clearExisting = false, clearTemp = true, removeTemp = false, keepUploadedUi = false, clearInput = true } = options;

    state.cancelRequested = true;
    if (removeTemp) cancelTempUpload(configSlot.inputId);
    revokePreviewUrlIfNeeded(configSlot.inputId);

    if (clearPreview) {
      const preview = $(`#${configSlot.previewId}`);
      preview.addClass('hidden');
      preview.find('img').attr('src', '');
      preview.removeData('is-new-image').removeData('original-path');
    }

    if (clearExisting) $(`#${configSlot.existingInputId}`).val('');
    if (clearTemp) $(`#${configSlot.tempInputId}`).val('');
    if (clearInput) $(`#${configSlot.inputId}`).val('');

    Object.assign(state, createEmptyUploadState());
    if (!keepUploadedUi) hideUploadUi(index);
  }

  function markExistingImageReady(index) {
    const state = uploadState[getSlotConfig(index).inputId];
    state.status = 'uploaded';
    state.progress = 100;
    setUploadUi(index, 'uploaded', 100, 'Gambar siap digunakan');
  }

  async function uploadFileInChunks(index, file, sessionId) {
    const slot = getSlotConfig(index);
    const state = uploadState[slot.inputId];

    state.cancelRequested = false;
    state.status = 'preparing';
    state.progress = 5;
    $(`#${slot.tempInputId}`).val('');
    setUploadUi(index, 'preparing', 5, 'Menyiapkan upload...');

    const totalChunks = Math.max(1, Math.ceil(file.size / CHUNK_SIZE));
    const initFormData = new FormData();
    initFormData.append('field', slot.field);
    initFormData.append('file_name', file.name);
    initFormData.append('file_size', file.size);
    initFormData.append('mime_type', file.type || 'application/octet-stream');
    initFormData.append('total_chunks', totalChunks);

    const initResult = await sendChunkRequest(config.routes.chunkInit, initFormData);
    state.uploadId = initResult.upload_id;
    state.status = 'uploading';

    for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
      if (state.cancelRequested || state.sessionId !== sessionId) throw new Error('Upload dibatalkan.');

      const start = chunkIndex * CHUNK_SIZE;
      const end = Math.min(file.size, start + CHUNK_SIZE);
      const chunkFormData = new FormData();
      chunkFormData.append('upload_id', state.uploadId);
      chunkFormData.append('chunk_index', chunkIndex);
      chunkFormData.append('chunk', file.slice(start, end), `${file.name}.part${chunkIndex}`);
      await sendChunkRequest(config.routes.chunkUpload, chunkFormData);

      const progress = Math.min(95, Math.round(((chunkIndex + 1) / totalChunks) * 95));
      state.progress = progress;
      setUploadUi(index, 'uploading', progress, 'Mengupload gambar...');
    }

    const finalizeFormData = new FormData();
    finalizeFormData.append('upload_id', state.uploadId);
    const finalizeResult = await sendChunkRequest(config.routes.chunkFinalize, finalizeFormData);
    if (state.sessionId !== sessionId) throw new Error('Upload dibatalkan.');

    state.tempToken = finalizeResult.temp_token;
    state.uploadId = null;
    state.status = 'uploaded';
    state.progress = 100;
    $(`#${slot.tempInputId}`).val(finalizeResult.temp_token);
    setUploadUi(index, 'uploaded', 100, 'Upload selesai');
  }

  async function processAndUploadImage(inputId, file) {
    const index = getSlotIndex(inputId);
    const slot = getSlotConfig(index);
    const state = uploadState[inputId];
    const wasCountedUpload = state.countedUpload;

    state.cancelRequested = true;
    await cancelTempUpload(inputId);
    resetImageSlot(index, { clearPreview: false, clearExisting: false, clearTemp: true, removeTemp: false, clearInput: false });

    state.sourceFile = file;
    state.countedUpload = wasCountedUpload;
    state.cancelRequested = false;
    state.sessionId = `${Date.now()}-${Math.random().toString(16).slice(2)}`;

    const processedFile = await normalizeImageFile(file, config.userName, config.userJob);
    state.processedFile = processedFile;
    revokePreviewUrlIfNeeded(inputId);
    state.previewUrl = URL.createObjectURL(processedFile);
    setPreview(index, state.previewUrl, true);

    if (!state.countedUpload) {
      stateRef.incrementCount();
      state.countedUpload = true;
    }

    try {
      await uploadFileInChunks(index, processedFile, state.sessionId);
      if (stateRef.isEditMode()) $(`#${slot.existingInputId}`).val('');
    } catch (error) {
      if (error.message === 'Upload dibatalkan.') return;
      state.status = 'error';
      setUploadUi(index, 'error', Math.max(state.progress || 0, 8), error.message || 'Upload gambar gagal.', { showRetry: true });
      notify(error.message || 'Upload gambar gagal. Silakan coba lagi.', 'error');
    }
  }

  function bindInputs() {
    const debounced = debounce((e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      processAndUploadImage(e.target.id, file);
    }, 250);

    [1, 2, 3].forEach((slot) => $(`#image${slot}`).on('change', debounced));
  }

  function appendImageReferences(formData) {
    [1, 2, 3].forEach((slotIndex) => {
      const slot = getSlotConfig(slotIndex);
      const tempValue = $(`#${slot.tempInputId}`).val();
      const existingValue = $(`#${slot.existingInputId}`).val();

      if (tempValue) formData.append(slot.tempInputId, tempValue);
      if (stateRef.isEditMode()) formData.append(slot.existingInputId, existingValue);
    });
  }

  function hasBlockingUploads() {
    return Object.values(uploadState).some((state) => state.status === 'preparing' || state.status === 'uploading');
  }

  function hasErroredUploads() {
    return Object.values(uploadState).some((state) => state.status === 'error');
  }

  function resetAllImageSlots(options = {}) {
    [1, 2, 3].forEach((slot) => resetImageSlot(slot, options));
  }

  async function removeImage(index) {
    const slot = getSlotConfig(index);
    const preview = $(`#preview${index}`);
    const state = uploadState[slot.inputId];

    if (preview.data('is-new-image') === true && state.countedUpload) {
      stateRef.decrementCount();
      state.countedUpload = false;
    }

    state.cancelRequested = true;
    await cancelTempUpload(slot.inputId);
    resetImageSlot(index, { clearPreview: true, clearExisting: stateRef.isEditMode(), clearTemp: true, removeTemp: false });

    if (stateRef.isEditMode()) {
      const fieldName = `existing_img_${index === 1 ? 'before' : (index === 2 ? 'proccess' : 'final')}`;
      $(`#${fieldName}`).val('');
    }
  }

  function retryImageUpload(index) {
    const slot = getSlotConfig(index);
    const state = uploadState[slot.inputId];
    if (!state.processedFile) {
      notify('File belum tersedia untuk diupload ulang.', 'warning');
      return;
    }
    processAndUploadImage(slot.inputId, state.processedFile);
  }

  return {
    bindInputs,
    appendImageReferences,
    hasBlockingUploads,
    hasErroredUploads,
    resetAllImageSlots,
    resetImageSlot,
    markExistingImageReady,
    removeImage,
    retryImageUpload,
  };
}
