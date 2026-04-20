import { combineNoteWithArea, createImageCard, notify, submitForm } from './dom-helpers';
import { saveDraftOffline } from './offline-sync';

export function initFormSubmit(options) {
  const {
    config,
    state,
    draftManager,
    chunkManager,
    setLoading,
    reportForm,
    reportStatus,
    reportId,
    typeInput,
  } = options;

  async function handleSaveDraft() {
    setLoading(true);
    reportStatus.val('0');
    typeInput.val('draft');

    const content = $('#reportContent').val();
    const area = $('#reportArea').val();

    if (chunkManager.hasBlockingUploads()) {
      notify('Tunggu sampai semua upload gambar selesai sebelum menyimpan draft.', 'warning');
      setLoading(false);
      return;
    }

    if (chunkManager.hasErroredUploads()) {
      notify('Masih ada upload gambar yang gagal. Silakan retry atau hapus gambar tersebut.', 'warning');
      setLoading(false);
      return;
    }

    if (!area.trim()) {
      notify('Silakan isi area kegiatan', 'warning');
      setLoading(false);
      return;
    }

    if (!content.trim()) {
      notify('Silakan isi konten laporan', 'warning');
      setLoading(false);
      return;
    }

    const formData = new FormData();
    formData.append('_token', config.csrfToken);
    formData.append('status', reportStatus.val());
    formData.append('id', reportId.val());
    formData.append('note', combineNoteWithArea(content, area));
    formData.append('area', area);
    formData.append('user_id', $('#user_id').val());
    formData.append('clients_id', $('#client_id').val());
    formData.append('type', 'draft');
    chunkManager.appendImageReferences(formData);

    let url = config.routes.storeDraft;
    let method = 'POST';

    if (state.isEditMode && reportId.val()) {
      url = `${config.routes.store}/${reportId.val()}`;
      formData.append('_method', 'PUT');
    }

    try {
      const response = await submitForm(formData, url, method, config.csrfToken);
      notify('Draft berhasil disimpan!', 'success');

      draftManager.setDraftData(response.data);
      draftManager.showDraftCard(response.data);
      draftManager.checkAndUpdateDraftDisplay();

      setLoading(false);
      reportForm[0].reset();
      reportId.val('');
      state.isEditMode = false;
      chunkManager.resetAllImageSlots({ clearPreview: true, clearExisting: true, clearTemp: true, removeTemp: false });
    } catch (xhr) {
      setLoading(false);
      let errorMessage = 'Terjadi kesalahan saat menyimpan draft.';
      if (xhr.responseJSON?.message) {
        errorMessage = xhr.responseJSON.message;
      } else if (xhr.responseJSON?.errors) {
        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
      }
      notify(errorMessage, 'error');
    }
  }

  async function handleSubmitReport() {
    setLoading(true);
    reportStatus.val('1');
    typeInput.val('submit');

    if (!navigator.onLine) {
      setLoading(false);
      saveDraftOffline();
      notify('Anda offline. Data disimpan di perangkat dan akan dikirim otomatis saat online.', 'warning');
      return;
    }

    const content = $('#reportContent').val();
    const area = $('#reportArea').val();

    if (chunkManager.hasBlockingUploads()) {
      notify('Tunggu sampai semua upload gambar selesai sebelum mengirim laporan.', 'warning');
      setLoading(false);
      return;
    }

    if (chunkManager.hasErroredUploads()) {
      notify('Masih ada upload gambar yang gagal. Silakan retry atau hapus gambar tersebut.', 'warning');
      setLoading(false);
      return;
    }

    if (!area.trim()) {
      notify('Silakan isi area kegiatan', 'warning');
      setLoading(false);
      return;
    }

    if (!content.trim()) {
      notify('Silakan isi konten laporan', 'warning');
      setLoading(false);
      return;
    }

    const hasBeforeImage = $('#image1')[0].files.length > 0
      || !!$('#temp_img_before').val()
      || !!$('#existing_img_before').val()
      || ($('#preview1 img').attr('src') && !$('#preview1').hasClass('hidden'));

    if (!hasBeforeImage) {
      notify('Silakan upload gambar Before.', 'warning');
      setLoading(false);
      return;
    }

    const hasFinalImage = $('#image3')[0].files.length > 0
      || !!$('#temp_img_final').val()
      || !!$('#existing_img_final').val()
      || ($('#preview3 img').attr('src') && !$('#preview3').hasClass('hidden'));

    if (!hasFinalImage) {
      notify('Silakan upload gambar After.', 'warning');
      setLoading(false);
      return;
    }

    const formData = new FormData();
    formData.append('_token', config.csrfToken);
    formData.append('status', reportStatus.val());
    formData.append('id', reportId.val());
    formData.append('note', combineNoteWithArea(content, area));
    formData.append('area', area);
    formData.append('user_id', $('#user_id').val());
    formData.append('clients_id', $('#client_id').val());
    formData.append('type', 'submit');
    chunkManager.appendImageReferences(formData);

    let url = config.routes.store;
    if (state.isEditMode && reportId.val()) {
      url = `${config.routes.store}/${reportId.val()}`;
      formData.append('_method', 'PUT');
    }

    try {
      const response = await submitForm(formData, url, 'POST', config.csrfToken);
      setLoading(false);
      notify('Laporan berhasil dikirim!', 'success');

      draftManager.checkAndUpdateDraftDisplay();
      const { draftData, firstDraft } = draftManager.getState();
      if (draftData) draftManager.showDraftCard(firstDraft);
      else draftManager.hideDraftCard();

      reportForm[0].reset();
      reportId.val('');
      state.isEditMode = false;
      chunkManager.resetAllImageSlots({ clearPreview: true, clearExisting: true, clearTemp: true, removeTemp: false });

      if (response.data) {
        $('#emptyHistoryMessage').remove();
        $('#historyGrid').prepend(createImageCard(response.data));
      }
    } catch (xhr) {
      setLoading(false);
      const statusCode = xhr.status;
      let errorMessage = `Terjadi kesalahan (${statusCode}).`;
      if (xhr.responseJSON?.message) {
        errorMessage = `Error ${statusCode}: ${xhr.responseJSON.message}`;
      } else if (xhr.responseJSON?.errors) {
        errorMessage = `Error ${statusCode}:\n${Object.values(xhr.responseJSON.errors).flat().join('\n')}`;
      }
      notify(errorMessage, 'error');
    }
  }

  $('#saveDraftBtn').on('click', handleSaveDraft);
  $('#submitReportBtn').on('click', handleSubmitReport);
}
