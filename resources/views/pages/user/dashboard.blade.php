<x-app-layout>
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <h2 class="mb-1 text-2xl font-bold text-slate-900">Rekapitulasi Bulanan</h2>
                        <p class="text-slate-500">Buat dan kelola laporan bulanan Anda</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
                        <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                            <div class="flex items-center">
                                <div class="p-2 text-purple-500 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-slate-500">Limit Gambar (Bulan ini)</p>
                                    <div class="flex items-baseline">
                                        <p class="text-2xl font-bold text-slate-900" id="remainingImages">{{ $totalImageCount }}</p>
                                        <span class="ml-1 text-sm text-slate-500">/ 33</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                        <div class="bg-purple-500 h-1.5 rounded-full transition-all duration-300" id="imageProgress"
                                            style="width: {{ $percentage . '%' }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script defer>
            $(document).ready(function() {
                const sidebarToggle = $('#sidebarToggle');
                const sidebar = $('#sidebar');
                const type = $('#type');

                // Image limit per month
                const IMAGE_LIMIT_PER_MONTH = 33;
                let imagesUploadedThisMonth = {{ $totalImageCount }}; // This would come from your backend
                let isEditMode = false;
                let draftData = null;

                // Store draft data for later use
                @if ($uploadDraft)
                    draftData = {!! json_encode($uploadDraft) !!};
                @endif


                sidebarToggle.on('click', function() {
                    sidebar.toggleClass('-translate-x-full');
                });

                // Close sidebar when clicking outside on mobile
                $(document).on('click', function(event) {
                    const isClickInsideSidebar = sidebar.has(event.target).length > 0;
                    const isClickOnToggle = sidebarToggle.has(event.target).length > 0;

                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.hasClass('-translate-x-full')) {
                        sidebar.addClass('-translate-x-full');
                    }
                });

                window.addEventListener("online", () => {
                    if(detectBrowser() == 'Chrome' || detectBrowser() == 'Edge') {
                        navigator.serviceWorker.ready.then(reg => {
                            reg.sync.register("sync-reports");
                        });
                    }else{
                        syncDrafts()
                    }
                });

                async function syncDrafts() {
                    const db = await idb.openDB('reportDB', 1);
                    const drafts = await db.getAll('drafts');

                    for (let draft of drafts) {
                        try {

                            const form = new FormData();
                            form.append('note', draft.note);

                            // load kembali file besar dari storage lokal
                            form.append('img_before', await loadLocalFile(draft.img_before));
                            form.append('img_proccess', await loadLocalFile(draft.img_proccess));
                            form.append('img_final', await loadLocalFile(draft.img_final));

                            await fetch('/upload-img-lap', {
                                method: 'POST',
                                body: form
                            });

                            await db.delete('drafts', draft.id);

                        } catch(e) {
                            console.log("Sync gagal, coba lagi nanti");
                            return; // stop sync
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>