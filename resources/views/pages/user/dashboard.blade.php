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
                            <div class="flex items-center gap-2 md:justify-between">
                                <div class="p-2 text-purple-500 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>                                
                                <div class="ml-4">
                                    <p class="text-sm text-slate-500">Limit Gambar (Bulan ini)</p>
                                    <div>
                                        <div class="flex items-baseline">
                                        @php
                                            $varCount = 33 - $totalImageCount;
                                            $varClass = $varCount >= 25 ? 'text-green-600'
                                                : ($varCount >= 15 ? 'text-amber-600'
                                                : ($varCount <= 14 ? 'text-red-600'
                                                : ''));
                                            $persen = $totalImageCount > 0 ?($totalImageCount / 33) * 100 : 0;
                                            $varPersenClass = $persen >= 80 ? 'bg-green-600'
                                                                : ($persen >= 75 ? 'bg-lime-600'
                                                                : ($persen >= 50 ? 'bg-amber-600'
                                                                : 'bg-red-600'));
                                        @endphp
                                        <p class="text-2xl font-bold {{ $varClass }}" id="remainingImages">{{ 33 -   $totalImageCount }}</p>
                                        <span class="ml-1 text-sm text-slate-500">/ 33</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                        <div class="{{ $varPersenClass }} h-1.5 rounded-full transition-all duration-300" id="imageProgress"
                                            style="width: {{  $persen. '%' }}"></div>
                                    </div>
                                    </div>
                                </div>
                                <svg class="w-16 h-16 text-blue-500 {{ $varCount === 0 ? '' : 'hidden'}}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10.007 2.10377C8.60544 1.65006 7.08181 2.28116 6.41156 3.59306L5.60578 5.17023C5.51004 5.35763 5.35763 5.51004 5.17023 5.60578L3.59306 6.41156C2.28116 7.08181 1.65006 8.60544 2.10377 10.007L2.64923 11.692C2.71404 11.8922 2.71404 12.1078 2.64923 12.308L2.10377 13.993C1.65006 15.3946 2.28116 16.9182 3.59306 17.5885L5.17023 18.3942C5.35763 18.49 5.51004 18.6424 5.60578 18.8298L6.41156 20.407C7.08181 21.7189 8.60544 22.35 10.007 21.8963L11.692 21.3508C11.8922 21.286 12.1078 21.286 12.308 21.3508L13.993 21.8963C15.3946 22.35 16.9182 21.7189 17.5885 20.407L18.3942 18.8298C18.49 18.6424 18.6424 18.49 18.8298 18.3942L20.407 17.5885C21.7189 16.9182 22.35 15.3946 21.8963 13.993L21.3508 12.308C21.286 12.1078 21.286 11.8922 21.3508 11.692L21.8963 10.007C22.35 8.60544 21.7189 7.08181 20.407 6.41156L18.8298 5.60578C18.6424 5.51004 18.49 5.35763 18.3942 5.17023L17.5885 3.59306C16.9182 2.28116 15.3946 1.65006 13.993 2.10377L12.308 2.64923C12.1078 2.71403 11.8922 2.71404 11.692 2.64923L10.007 2.10377ZM6.75977 11.7573L8.17399 10.343L11.0024 13.1715L16.6593 7.51465L18.0735 8.92886L11.0024 15.9999L6.75977 11.7573Z"></path></svg>
                            </div>
                        </div>

                    </div>
                     <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
                        <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                            <canvas id="monthlyChart" height="210"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script defer>
            $(document).ready(function() {
                const type = $('#type');
               
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

        {{-- Chart Script --}}

        <script>
        $(document).ready(function () {
            $.ajax({
                url: "/performance-per-month",
                type: "GET",
                success: function (data) {

                    const ctx = document.getElementById("monthlyChart");

                    setTimeout(() => {   // ensures animation works
                        new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: data.months,
                                datasets: [{
                                    label: "Uploads Per Month",
                                    data: data.totals,
                                    borderWidth: 1,
                                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                                    borderColor: "rgb(54, 162, 235)"
                                }]
                            },
                            options: {
                                animation: {
                                    duration: 1500,
                                    easing: "easeInOutQuart"
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }, 100);

                }
            });
        });
        </script>
        {{-- End Chart Script --}}
    @endpush
</x-app-layout>