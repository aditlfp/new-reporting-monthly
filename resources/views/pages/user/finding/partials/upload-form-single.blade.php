<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-900">Upload Gambar Temuan</h3>
        <button id="openModalRiwayat"
            class="text-white transition-all duration-150 ease-in-out bg-blue-500 border-0 rounded-sm btn btn-md hover:bg-slate-50 hover:text-blue-500">
            Riwayat Temuan
        </button>
    </div>

    <div id="draftCardContainer"></div>

    <div class="p-4 bg-white border rounded-b-lg shadow-sm border-slate-100 sm:p-6">
        <form id="reportForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->id() }}">

            <div class="space-y-6">
                <div>
                    <label class="block mb-3 text-sm font-medium text-slate-700 required">Gambar</label>
                    <div class="grid grid-cols-1 gap-2 sm:gap-3 md:gap-4">
                        @php
                            $imageConfig = [
                                ['id' => 'image1', 'name' => 'image', 'label' => 'Foto'],
                            ];

                            $acceptedTypes = '.gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic,.jpg,.jpeg';

                            $uploadIcon = '<svg class="w-5 h-5 mb-1 sm:w-6 sm:h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';

                            $deleteIcon = '<svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        @endphp

                        @foreach ($imageConfig as $index => $config)
                            <div class="min-w-0 space-y-1.5">
                                <div class="relative h-40 sm:h-28 md:h-32 lg:h-36">
                                    <input type="file" id="{{ $config['id'] }}" name="{{ $config['name'] }}" accept="{{ $acceptedTypes }}" class="hidden" data-upload-input="{{ $config['id'] }}" onchange="window.previewFindingImage(this)">
                                    <label for="{{ $config['id'] }}"
                                        class="flex flex-col items-center justify-center w-full h-full transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100"
                                        data-upload-label="{{ $config['id'] }}">
                                        {!! $uploadIcon !!}
                                        <span class="text-[10px] sm:text-xs text-slate-500 text-center px-1">+ {{ $config['label'] }}</span>
                                    </label>

                                    <div id="preview{{ $index + 1 }}" class="absolute inset-0 hidden overflow-hidden rounded-lg" hidden style="display:none;">
                                        <img src="" alt="Preview" class="object-cover w-full h-full">
                                        <button type="button"
                                            class="absolute p-1 sm:p-1.5 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                            onclick="removeImage({{ $index + 1 }})">
                                            {!! $deleteIcon !!}
                                        </button>
                                    </div>
                                </div>

                                <div id="uploadState{{ $index + 1 }}" class="hidden upload-progress-card" data-state="idle">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center min-w-0 gap-2">
                                            <span class="hidden upload-spinner"></span>
                                            <span class="text-[10px] sm:text-xs font-medium text-slate-700 truncate upload-status">Menunggu upload</span>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 gap-1.5">
                                            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 upload-percent">0%</span>
                                            <button type="button"
                                                class="hidden text-[10px] sm:text-xs font-medium text-red-500 transition-colors hover:text-red-600 upload-retry"
                                                onclick="retryImageUpload({{ $index + 1 }})">
                                                Retry
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-1 upload-progress-bar"><span style="width: 0%"></span></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="reportArea" class="block mb-2 text-sm font-medium text-slate-700">Ruangan</label>
                    <input type="text" id="reportArea" name="ruangan" value="{{ old('ruangan', request('n')) }}"
                        class="w-full px-3 py-2 text-sm bg-white border rounded-lg input sm:px-4 sm:py-3 sm:text-base text-slate-900 border-slate-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tulis ruangan di sini..." required>
                </div>

                <div>
                    <label for="reportContent" class="block mb-2 text-sm font-medium text-slate-700">Keterangan Temuan</label>
                    <textarea id="reportContent" name="note" rows="4"
                        class="w-full px-3 py-2 text-sm bg-white border rounded-lg resize-none textarea sm:px-4 sm:py-3 sm:text-base text-slate-900 border-slate-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tulis isi keterangan temuan di sini... (format: 'nama temuan')" required>{{ old('note', request('temuan')) }}</textarea>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
                    <button type="button" id="submitReportBtn"
                        class="w-full sm:w-auto px-4 py-2.5 text-sm sm:text-base text-center flex items-center justify-center gap-2 font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M4 19H20V12H22V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V12H4V19ZM13 9V16H11V9H6L12 3L18 9H13Z"></path></svg>
                        Simpan Temuan
                    </button>

                    <button class="hidden btn btnLoading">
                        <span class="loading loading-spinner"></span>
                        loading
                    </button>
                </div>
                @if(old('ruangan', request('n')))
                <div class="mt-4 text-sm text-slate-500">
                    <p class="flex flex-wrap items-center gap-2 text-slate-700">
                        <span class="font-semibold">Sisa kuota ruangan</span>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">
                            {{ $remainingQuota }}
                        </span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Ruangan: <span class="font-medium text-slate-700">{{ old('ruangan', request('n')) ?: 'belum dipilih' }}</span>
                    </p>
                </div>
                @endif
            </div>
        </form>
    </div>

    @include('pages.user.send_img.partials.draft-card')
</div>
