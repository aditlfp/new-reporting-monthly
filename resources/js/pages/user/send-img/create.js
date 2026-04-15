import '../../../../css/pages/send-img.css';
import { initHistoryModal } from './modules/history-modal';
import { createDraftManager } from './modules/draft-manager';
import { createChunkUploadManager } from './modules/chunk-upload';
import { initFormSubmit } from './modules/form-submit';
import { initOfflineSync } from './modules/offline-sync';

function initSendImgCreatePage() {
  const config = window.SendImgConfig;
  if (!config) return;

  const reportForm = $('#reportForm');
  const reportStatus = $('#reportStatus');
  const reportId = $('#reportId');
  const typeInput = $('#type');
  const draftCardContainer = $('#draftCardContainer');
  const submitReportBtn = $('#submitReportBtn');
  const loadingBtn = $('.btnLoading');

  const state = {
    isEditMode: false,
    imagesUploadedThisMonth: Number(config.initialData.totalImageCount || 0),
    isEditModeGetter() {
      return this.isEditMode;
    },
  };

  const setLoading = (isLoading) => {
    if (isLoading) {
      submitReportBtn.addClass('hidden');
      loadingBtn.removeClass('hidden');
    } else {
      submitReportBtn.removeClass('hidden');
      loadingBtn.addClass('hidden');
    }
  };

  const chunkManager = createChunkUploadManager(config, {
    incrementCount: () => { state.imagesUploadedThisMonth += 1; },
    decrementCount: () => { state.imagesUploadedThisMonth = Math.max(0, state.imagesUploadedThisMonth - 1); },
    isEditMode: () => state.isEditMode,
  });

  const draftManager = createDraftManager({
    config,
    reportForm,
    reportId,
    draftCardContainer,
    setEditMode: (val) => { state.isEditMode = val; },
    resetAllImageSlots: chunkManager.resetAllImageSlots,
    markExistingImageReady: chunkManager.markExistingImageReady,
  });

  draftManager.initFromServer();
  draftManager.checkAndUpdateDraftDisplay();

  chunkManager.bindInputs();
  initHistoryModal();
  initOfflineSync(config);

  initFormSubmit({
    config,
    state,
    draftManager,
    chunkManager,
    setLoading,
    reportForm,
    reportStatus,
    reportId,
    typeInput,
  });

  // Compatibility handlers for existing inline onclick attributes.
  window.removeImage = (index) => chunkManager.removeImage(index);
  window.retryImageUpload = (index) => chunkManager.retryImageUpload(index);
}

$(document).ready(initSendImgCreatePage);
