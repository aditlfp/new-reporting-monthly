<div id="modalRiwayat" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-md">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="relative w-full max-w-6xl transition-all transform bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 class="text-xl font-semibold text-slate-900">Riwayat Laporan</h3>
                <button id="closeModalRiwayat" class="transition-colors text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3" id="historyGrid">
                    @forelse ($allImages as $imgData)
                        <div class="overflow-hidden transition-all duration-300 min-h-[110px] ease-in-out bg-white border rounded-lg shadow-sm cursor-pointer border-slate-100 hover:shadow-md card-expandable"
                            data-card-id="{{ $imgData->id }}">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-md text-slate-900 line-clamp-2">{{ $imgData->note }}</h4>
                                        <p class="text-sm text-slate-500">{{ $imgData->created_at->isoformat('D MMMM Y') }}</p>
                                        <p class="text-xs text-slate-500 truncate max-w-[300px]">
                                            Di Upload Oleh : {{ $imgData->user ? $imgData->user->nama_lengkap : 'User Hilang' }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 transition-transform duration-300 text-slate-400 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <div class="mt-4 expanded-content">
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                            <img data-src="{{ URL::asset('/storage/' . $imgData->img_before) }}" alt="Before" class="object-cover w-full h-full lazy-load">
                                        </div>
                                        <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                            <img data-src="{{ $imgData->img_proccess ? URL::asset('/storage/' . $imgData->img_proccess) : 'https://placehold.co/400x400?text=Kosong' }}" alt="Process" class="object-cover w-full h-full lazy-load">
                                        </div>
                                        <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                                            <img data-src="{{ URL::asset('/storage/' . $imgData->img_final) }}" alt="Final" class="object-cover w-full h-full lazy-load">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text-sm text-slate-700 line-clamp-none">{{ $imgData->note }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center col-span-full text-slate-500" id="emptyHistoryMessage">
                            Belum ada riwayat laporan Bulan Ini
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-end p-6 border-t border-slate-200">
                <button id="closeModalRiwayatFooter"
                    class="px-4 py-2 text-sm font-medium transition-colors rounded-lg text-slate-700 bg-slate-100 hover:bg-slate-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
