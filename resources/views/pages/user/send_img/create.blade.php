<x-app-layout>
    <div class="flex flex-col h-screen bg-white" data-page="send-img-create">
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            <x-user-sidebar />

            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-6xl mx-auto">
                    @include('pages.user.send_img.partials.upload-form')
                </div>

                @include('pages.user.send_img.partials.history-modal')
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            window.SendImgConfig = {
                csrfToken: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                userName: @json(Auth::user()->nama_lengkap),
                userJob: @json(ucwords(strtolower(Auth::user()->jabatan->name_jabatan))),
                routes: {
                    store: @json(url('upload-img-lap')),
                    storeDraft: @json(route('upload-images.draft')),
                    uploadIndex: @json(route('upload-img-lap.index')),
                    countDataApi: @json(route('v1.count.data')),
                    chunkInit: @json(route('upload-images.chunk.init')),
                    chunkUpload: @json(route('upload-images.chunk.upload')),
                    chunkFinalize: @json(route('upload-images.chunk.finalize')),
                    chunkCancel: @json(route('upload-images.chunk.cancel')),
                },
                initialData: {
                    totalImageCount: @json($totalImageCount),
                    uploadDraft: @json($uploadDraft),
                },
            };
        </script>
    @endpush
</x-app-layout>
