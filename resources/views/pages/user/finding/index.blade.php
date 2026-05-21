<x-app-layout>
    <div class="flex flex-col h-screen bg-white" data-page="finding-index">
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            <x-user-sidebar />

            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-6xl mx-auto">
                    @include('pages.user.finding.partials.upload-form-single')
                </div>

                @include('pages.user.finding.partials.history-modal')
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            window.SendImgConfig = {
                csrfToken: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                userId: @json(optional(Auth::user())->id),
                userName: @json(optional(Auth::user())->nama_lengkap),
                userJob: @json(optional(optional(Auth::user())->jabatan)->name_jabatan ? ucwords(strtolower(optional(optional(Auth::user())->jabatan)->name_jabatan)) : null),
                routes: {
                    store: @json(route('finding.store')),
                },
                initialData: {
                    totalImageCount: 0,
                    uploadDraft: null,
                },
            };

            (function() {
                function setFindingPreviewVisible(show) {
                    const preview = document.getElementById('preview1');
                    const label = document.querySelector('[data-upload-label="image1"]');
                    if (!preview || !label) return;

                    preview.hidden = !show;
                    preview.style.display = show ? 'block' : 'none';
                    label.hidden = show;
                    label.style.display = show ? 'none' : 'flex';
                }

                window.previewFindingImage = function(inputOrEvent) {
                    const input = inputOrEvent?.target || inputOrEvent;
                    const previewImage = document.querySelector('#preview1 img');
                    if (!input || !previewImage) return;

                    const file = input.files && input.files[0];
                    if (!file) {
                        previewImage.src = '';
                        setFindingPreviewVisible(false);
                        return;
                    }

                    previewImage.src = URL.createObjectURL(file);
                    setFindingPreviewVisible(true);
                };

                window.removeImage = function() {
                    const input = document.getElementById('image1');
                    const previewImage = document.querySelector('#preview1 img');
                    if (input) input.value = '';
                    if (previewImage) previewImage.src = '';
                    setFindingPreviewVisible(false);
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        setFindingPreviewVisible(false);
                    });
                } else {
                    setFindingPreviewVisible(false);
                }
            })();
        </script>
    @endpush
</x-app-layout>
