import '../../../../css/pages/send-img.css';
import { initHistoryModal } from './modules/history-modal';
import { initFormSubmit } from './modules/form-submit';
import { notify, submitForm } from './modules/dom-helpers';

function initFindingIndexPage() {
  const config = window.SendImgConfig || {};
  const initialData = config.initialData || { totalImageCount: 0, uploadDraft: null };
  config.initialData = initialData;

  const reportForm = $('#reportForm');
  const submitReportBtn = $('#submitReportBtn');
  const loadingBtn = $('.btnLoading');
  const imageInput = $('#image1');
  const imagePreview = $('#preview1');
  const imagePreviewTag = $('#preview1 img');
  const imageLabel = $('[data-upload-label="image1"]');

  const setPreviewVisibility = (showPreview) => {
    imagePreview.prop('hidden', !showPreview);
    imagePreview.css('display', showPreview ? 'block' : 'none');
    imageLabel.prop('hidden', showPreview);
    imageLabel.css('display', showPreview ? 'none' : 'flex');
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

  const resetImagePreview = () => {
    imageInput.val('');
    imagePreviewTag.attr('src', '');
    setPreviewVisibility(false);
  };

  const renderImagePreview = (eventOrInput) => {
    const inputEl = eventOrInput?.target || eventOrInput;
    if (!inputEl?.files) {
      resetImagePreview();
      return;
    }

    const [file] = inputEl.files || [];

    if (!file) {
      resetImagePreview();
      return;
    }

    const objectUrl = URL.createObjectURL(file);
    imagePreviewTag.attr('src', objectUrl);
    setPreviewVisibility(true);
  };

  imageInput.on('change', renderImagePreview);
  window.previewFindingImage = (event) => renderImagePreview(event);
  setPreviewVisibility(false);

  window.removeImage = () => {
    resetImagePreview();
  };

  if (!window.SendImgConfig) return;

  initHistoryModal();

  initFormSubmit({
    config,
    reportForm,
    submitForm,
    notify,
    setLoading,
  });
}

$(document).ready(initFindingIndexPage);
