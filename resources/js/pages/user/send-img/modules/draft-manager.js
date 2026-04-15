import { extractAreaFromNote, loadImageWithLazyLoading, notify } from './dom-helpers';

export function createDraftManager(options) {
  const {
    config,
    reportForm,
    reportId,
    draftCardContainer,
    setEditMode,
    resetAllImageSlots,
    markExistingImageReady,
  } = options;

  let draftData = null;
  let firstDraft = null;

  function initFromServer() {
    const uploadDraft = config.initialData.uploadDraft || [];
    if (!uploadDraft.length) return;

    draftData = uploadDraft;
    firstDraft = [...uploadDraft].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))[0];
    showDraftCard(firstDraft);
  }

  function getState() {
    return { draftData, firstDraft };
  }

  function showDraftCard(draft) {
    const template = document.getElementById('draftCardTemplate');
    if (!template) return;

    const fragment = template.content.cloneNode(true);
    draftCardContainer[0].innerHTML = '';
    draftCardContainer[0].appendChild(fragment);

    draftCardContainer.off('click', '#editDraftBtn').on('click', '#editDraftBtn', () => {
      if (!draft) return;
      loadDraftData(draft);
      $('html, body').animate({ scrollTop: $('#reportForm').offset().top - 100 }, 500);
    });
  }

  function hideDraftCard() {
    draftCardContainer.empty();
  }

  async function checkAndUpdateDraftDisplay() {
    try {
      const response = await fetch(config.routes.countDataApi, {
        headers: { Accept: 'application/json' },
      });
      const result = await response.json();

      if ((result.data || 0) > 0 && firstDraft) {
        showDraftCard(firstDraft);
      } else if ((result.data || 0) <= 0) {
        hideDraftCard();
      }
    } catch (error) {
      console.warn('Gagal update draft display', error);
    }
  }

  function loadDraftData(draft) {
    try {
      reportForm[0].reset();
      resetAllImageSlots({
        clearPreview: true,
        clearExisting: true,
        clearTemp: true,
        removeTemp: true,
      });

      setEditMode(true);
      reportId.val(draft.id || '');

      const extracted = extractAreaFromNote(draft.note || '');
      $('#reportContent').val(extracted.note);
      $('#reportArea').val(extracted.area);

      $('#existing_img_before').val(draft.img_before || '');
      $('#existing_img_proccess').val(draft.img_proccess || '');
      $('#existing_img_final').val(draft.img_final || '');

      const imageFields = ['img_before', 'img_proccess', 'img_final'];
      imageFields.forEach((field, index) => {
        const slot = index + 1;
        if (!draft[field] || draft[field] === 'none') return;

        const imageUrl = draft[field].startsWith('http')
          ? draft[field]
          : `${window.location.origin}/storage/${draft[field]}`;

        const preview = $(`#preview${slot}`);
        const img = preview.find('img');
        loadImageWithLazyLoading(img[0], imageUrl);
        preview.removeClass('hidden');
        preview.data('is-new-image', false);
        preview.data('original-path', draft[field]);
        markExistingImageReady(slot);
      });
    } catch (error) {
      console.error('Error in loadDraftData', error);
      notify('Error loading draft data. Please try again.', 'error');
    }
  }

  function setDraftData(nextDraftData) {
    draftData = nextDraftData;
    firstDraft = nextDraftData;
  }

  return {
    initFromServer,
    getState,
    showDraftCard,
    hideDraftCard,
    checkAndUpdateDraftDisplay,
    loadDraftData,
    setDraftData,
  };
}
