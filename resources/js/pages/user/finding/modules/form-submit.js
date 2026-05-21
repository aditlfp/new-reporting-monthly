export function initFormSubmit({
  config,
  reportForm,
  submitForm,
  notify,
  setLoading,
}) {
  async function handleSubmitReport() {
    setLoading(true);

    const note = $('#reportContent').val().trim();
    const ruangan = $('#reportArea').val().trim();
    const imageInput = $('#image1')[0];
    const userId = $('#user_id').val() || config.userId;

    if (!ruangan) {
      notify('Silakan isi ruangan.', 'warning');
      setLoading(false);
      return;
    }

    if (!note) {
      notify('Silakan isi keterangan temuan.', 'warning');
      setLoading(false);
      return;
    }

    if (!userId) {
      notify('User tidak valid.', 'warning');
      setLoading(false);
      return;
    }

    if (!imageInput?.files?.length) {
      notify('Silakan upload foto.', 'warning');
      setLoading(false);
      return;
    }

    const formData = new FormData();
    formData.append('_token', config.csrfToken);
    formData.append('user_id', userId);
    formData.append('ruangan', ruangan);
    formData.append('note', note);
    formData.append('image', imageInput.files[0]);

    try {
      const response = await submitForm(formData, config.routes.store, 'POST', config.csrfToken);
      notify(response?.message || response?.data?.message || 'Temuan berhasil disimpan!', 'success');

      reportForm[0].reset();
      $('#preview1').addClass('hidden');
      $('#preview1 img').attr('src', '');
      $('[data-upload-label="image1"]').removeClass('hidden');
      $('#uploadState1').addClass('hidden').attr('data-state', 'idle');
    } catch (xhr) {
      const statusCode = xhr.status;
      let errorMessage = `Terjadi kesalahan (${statusCode}).`;

      if (xhr.responseJSON?.message) {
        errorMessage = `Error ${statusCode}: ${xhr.responseJSON.message}`;
      } else if (xhr.responseJSON?.errors) {
        errorMessage = `Error ${statusCode}:\n${Object.values(xhr.responseJSON.errors).flat().join('\n')}`;
      }

      notify(errorMessage, 'error');
    } finally {
      setLoading(false);
    }
  }

  $('#submitReportBtn').on('click', handleSubmitReport);
}
