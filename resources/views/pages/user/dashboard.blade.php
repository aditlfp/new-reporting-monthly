<x-app-layout>
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <nav class="sticky top-0 z-20 flex items-center justify-between px-4 py-3 bg-white border-b border-slate-100">
            <div class="flex items-center">
                <button id="sidebarToggle" class="p-2 transition-all rounded-lg text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="ml-4">
                    <h1 class="text-xl font-bold text-slate-900">Rekapitulasi Bulanan</h1>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="flex items-center p-1 space-x-2 transition-all rounded-lg hover:bg-slate-100">
                        <div
                            class="flex items-center justify-center w-8 h-8 font-semibold text-white bg-blue-500 rounded-full">
                            {{ auth()->user()->nama_lengkap ? substr(auth()->user()->nama_lengkap, 0, 1) : 'U' }}
                        </div>
                        <span
                            class="hidden text-sm font-medium md:block text-slate-700">{{ auth()->user()->nama_lengkap }}</span>
                    </button>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="fixed z-10 w-64 h-full transition-transform duration-300 ease-in-out transform -translate-x-full bg-white border-r border-slate-100 md:translate-x-0 md:static">
                <div class="p-6 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div
                            class="flex items-center justify-center w-12 h-12 font-semibold text-white bg-blue-500 rounded-full">
                            {{ auth()->user()->nama_lengkap ? substr(auth()->user()->nama_lengkap, 0, 1) : 'U' }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->nama_lengkap }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 p-4 space-y-1">
                    <a href="#"
                        class="flex items-center px-4 py-3 space-x-3 text-white transition-all bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Kalender</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Dokumen</span>
                    </a>

                    <a href="#"
                        class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-red-50">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="red"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span class="font-medium text-red-600">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

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
                                        <p class="text-2xl font-bold text-slate-900" id="remainingImages">15</p>
                                        <span class="ml-1 text-sm text-slate-500">/ 33</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                        <div class="bg-purple-500 h-1.5 rounded-full" id="imageProgress"
                                            style="width: 55%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                            <div class="flex items-center">
                                <div class="p-2 text-yellow-500 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-slate-500">Draft</p>
                                    <p class="text-2xl font-bold text-slate-900" id="draftCount">2</p>
                                </div>
                            </div>
                        </div>

                        @if($uploadDraft)
                        <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100">
                            <div class="flex items-center">
                                <div class="p-2 text-blue-500 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-slate-500">Draft Tersedia</p>
                                    <button id="editDraftBtn" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit Draft</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Create Report Form -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Buat Laporan Baru</h3>
                        </div>

                        <div class="p-5 bg-white border rounded-lg shadow-sm border-slate-100">
                            <form id="reportForm">
                                @csrf
                                <input type="hidden" id="reportStatus" name="status" value="0">
                                <input type="hidden" id="reportId" name="id" value="">
                                <input type="hidden" name="_method" id="_method" value="POST">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Image Uploads -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-slate-700">Gambar (maks
                                            3)</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <!-- Image 1 -->
                                            <div class="relative">
                                                <input type="file" id="image1" name="img_before"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic"
                                                    class="hidden">
                                                <label for="image1"
                                                    class="flex flex-col items-center justify-center h-20 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview1"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-cover w-full h-full">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(1)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Image 2 -->
                                            <div class="relative">
                                                <input type="file" id="image2" name="img_proccess"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic"
                                                    class="hidden">
                                                <label for="image2"
                                                    class="flex flex-col items-center justify-center h-20 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview2"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-cover w-full h-full">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(2)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Image 3 -->
                                            <div class="relative">
                                                <input type="file" id="image3" name="img_final"
                                                    accept=".gif,.tif,.tiff,.png,.crw,.cr2,.dng,.raf,.nef,.nrw,.orf,.rw2,.pef,.arw,.sr2,.raw,.psd,.svg,.webp,.heic"
                                                    class="hidden">
                                                <label for="image3"
                                                    class="flex flex-col items-center justify-center h-20 transition-colors border-2 border-dashed rounded-lg cursor-pointer border-slate-300 bg-slate-50 hover:bg-slate-100">
                                                    <svg class="w-6 h-6 mb-1 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-xs text-slate-500">Tambah</span>
                                                </label>
                                                <div id="preview3"
                                                    class="absolute inset-0 hidden overflow-hidden rounded-lg">
                                                    <img src="" alt="Preview"
                                                        class="object-cover w-full h-full">
                                                    <button type="button"
                                                        class="absolute p-1 text-white transition-colors bg-red-500 rounded-full top-1 right-1 hover:bg-red-600"
                                                        onclick="removeImage(3)">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Textarea for content -->
                                <div class="md:col-span-2">
                                    <label for="reportContent"
                                        class="block mb-2 text-sm font-medium text-slate-700">Isi Laporan</label>
                                    <textarea id="reportContent" name="note" rows="4"
                                        class="w-full px-4 py-2 bg-white border rounded-lg text-slate-900 border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Tulis isi keterangan di sini... (format: 'nama kegiatan - nama area')"></textarea>
                                </div>

                                <div class="hidden">
                                    <input type="text" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                                    <input type="text" name="clients_id" id="client_id" value="{{ auth()->user()->kerjasama ? auth()->user()->kerjasama->client_id : '' }}">
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-between mt-4 md:col-span-2">
                                    <button type="button" id="saveDraftBtn" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Simpan
                                        Draft</button>
                                    <button type="button" id="submitReportBtn" class="px-4 py-2 ml-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Kirim Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Riwayat Laporan</h3>
                        </div>

                        <div class="overflow-hidden bg-white border rounded-lg shadow-sm border-slate-100">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-slate-500">
                                                Periode</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-slate-500">
                                                Tanggal</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-slate-500">
                                                Status</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-slate-500">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">Juni 2023</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-500">
                                                15 Juni 2023
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    Disetujui
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                                <a href="#"
                                                    class="mr-3 text-blue-500 hover:text-blue-700">Lihat</a>
                                                <a href="#"
                                                    class="text-slate-500 hover:text-slate-700">Unduh</a>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">Mei 2023</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-500">
                                                10 Mei 2023
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    Disetujui
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                                <a href="#"
                                                    class="mr-3 text-blue-500 hover:text-blue-700">Lihat</a>
                                                <a href="#"
                                                    class="text-slate-500 hover:text-slate-700">Unduh</a>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">April 2023</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-500">
                                                5 April 2023
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                                    Menunggu
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                                <a href="#"
                                                    class="mr-3 text-blue-500 hover:text-blue-700">Lihat</a>
                                                <a href="#"
                                                    class="text-slate-500 hover:text-slate-700">Unduh</a>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">Maret 2023</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-500">
                                                3 Maret 2023
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    Disetujui
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                                <a href="#"
                                                    class="mr-3 text-blue-500 hover:text-blue-700">Lihat</a>
                                                <a href="#"
                                                    class="text-slate-500 hover:text-slate-700">Unduh</a>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">Februari 2023</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-500">
                                                2 Februari 2023
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                                    Ditolak
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                                <a href="#"
                                                    class="mr-3 text-blue-500 hover:text-blue-700">Lihat</a>
                                                <a href="#"
                                                    class="text-slate-500 hover:text-slate-700">Unduh</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const sidebarToggle = $('#sidebarToggle');
                const sidebar = $('#sidebar');
                const reportForm = $('#reportForm');
                const saveDraftBtn = $('#saveDraftBtn');
                const submitReportBtn = $('#submitReportBtn');
                const reportStatus = $('#reportStatus');
                const reportId = $('#reportId');
                const editDraftBtn = $('#editDraftBtn');
                const _method = $('#_method');

                // Image limit per month
                const IMAGE_LIMIT_PER_MONTH = 33;
                let imagesUploadedThisMonth = 18; // This would come from your backend
                let isEditMode = false;
                let draftData = null;

                // Store draft data for later use
                @if($uploadDraft)
                    draftData = {!! json_encode($uploadDraft) !!};
                @endif

                // Update remaining images display
                updateRemainingImages();

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

                // Handle edit draft button click
                editDraftBtn.on('click', function() {
                    if (draftData) {
                        loadDraftData(draftData);
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#reportForm').offset().top - 100
                        }, 500);
                    }
                });

                // Handle image uploads
                for (let i = 1; i <= 3; i++) {
                    const input = $(`#image${i}`);
                    const preview = $(`#preview${i}`);

                    input.on('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            // Check if user has reached the image limit
                            if (imagesUploadedThisMonth >= IMAGE_LIMIT_PER_MONTH) {
                                alert(
                                    `Anda telah mencapai batas upload gambar bulan ini (${IMAGE_LIMIT_PER_MONTH} gambar)`);
                                input.val('');
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function(event) {
                                preview.find('img').attr('src', event.target.result);
                                preview.removeClass('hidden');

                                // Increment the uploaded images count
                                imagesUploadedThisMonth++;
                                updateRemainingImages();
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }

                // Function to update remaining images display
                function updateRemainingImages() {
                    const remaining = IMAGE_LIMIT_PER_MONTH - imagesUploadedThisMonth;
                    const percentage = (imagesUploadedThisMonth / IMAGE_LIMIT_PER_MONTH) * 100;

                    $('#remainingImages').text(remaining);
                    $('#imageProgress').css('width', percentage + '%');

                    // Change color based on remaining images
                    const progressBar = $('#imageProgress');
                    if (remaining < 10) {
                        progressBar.removeClass('bg-purple-500 bg-yellow-500').addClass('bg-red-500');
                    } else if (remaining < 20) {
                        progressBar.removeClass('bg-purple-500 bg-red-500').addClass('bg-yellow-500');
                    } else {
                        progressBar.removeClass('bg-red-500 bg-yellow-500').addClass('bg-purple-500');
                    }
                }

                // Function to load draft data into the form
                function loadDraftData(draft) {
                    try {
                        isEditMode = true;
                        
                        // Set the report ID for editing
                        reportId.val(draft.id || '');
                        
                        // Set the content
                        $('#reportContent').val(draft.note || '');
                        
                        // Load images if they exist
                        const imageFields = ['img_before', 'img_proccess', 'img_final'];
                        
                        imageFields.forEach((field, index) => {
                            const i = index + 1;
                            if (draft[field]) {
                                // Try different possible image paths
                                const possiblePaths = [
                                    `/storage/${draft[field]}`,
                                    `/uploads/${draft[field]}`,
                                    draft[field]
                                ];
                                
                                // Try each path until one works
                                for (const path of possiblePaths) {
                                    const img = new Image();
                                    img.onload = function() {
                                        $(`#preview${i} img`).attr('src', path);
                                        $(`#preview${i}`).removeClass('hidden');
                                    };
                                    img.onerror = function() {
                                        // Try next path
                                    };
                                    img.src = path;
                                    break;
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error in loadDraftData:', e);
                        alert('Error loading draft data. Please try again.');
                    }
                }

                // Handle save draft button
                saveDraftBtn.on('click', function() {
                    // Set status to 0 for draft
                    reportStatus.val('0');

                    // Validate content
                    const content = $('#reportContent').val();
                    if (!content.trim()) {
                        alert('Silakan isi konten laporan');
                        return;
                    }

                    // Create FormData object
                    const formData = new FormData(reportForm[0]);

                    // Determine the URL and method based on whether we're creating or updating
                    let url, method;
                    if (isEditMode && reportId.val()) {
                        url = `{{ url('upload-img-lap') }}/${reportId.val()}`;
                        method = 'PUT';
                        _method.val('PUT');
                    } else {
                        url = '{{ url('upload-img-lap') }}';
                        method = 'POST';
                        _method.val('POST');
                    }

                    // Send AJAX request
                    $.ajax({
                        url: url,
                        type: 'POST', // Always use POST for FormData, but include _method for Laravel
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Draft berhasil disimpan!');

                            // Reset form
                            reportForm[0].reset();
                            reportId.val('');
                            isEditMode = false;
                            _method.val('POST');
                            for (let i = 1; i <= 3; i++) {
                                $(`#preview${i}`).addClass('hidden');
                                $(`#preview${i} img`).attr('src', '');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menyimpan draft.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                // Handle validation errors
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('\n');
                            }
                            alert(errorMessage);
                        }
                    });
                });

                // Handle submit report button
                submitReportBtn.on('click', function() {
                    // Set status to 1 for submitted report
                    reportStatus.val('1');

                    const content = $('#reportContent').val();

                    // Validate form
                    if (!content.trim()) {
                        alert('Silakan isi konten laporan');
                        return;
                    }

                    // Check if user has uploaded at least one image
                    let hasImage = false;
                    for (let i = 1; i <= 3; i++) {
                        if ($(`#image${i}`)[0].files.length > 0 || 
                            ($(`#preview${i} img`).attr('src') && !$(`#preview${i}`).hasClass('hidden'))) {
                            hasImage = true;
                            break;
                        }
                    }

                    if (!hasImage) {
                        alert('Silakan upload minimal satu gambar pendukung');
                        return;
                    }

                    // Create FormData object
                    const formData = new FormData(reportForm[0]);

                    // Determine the URL and method based on whether we're creating or updating
                    let url, method;
                    if (isEditMode && reportId.val()) {
                        url = `{{ url('upload-img-lap') }}/${reportId.val()}`;
                        method = 'PUT';
                        _method.val('PUT');
                    } else {
                        url = '{{ url('upload-img-lap') }}';
                        method = 'POST';
                        _method.val('POST');
                    }

                    // Send AJAX request
                    $.ajax({
                        url: url,
                        type: 'POST', // Always use POST for FormData, but include _method for Laravel
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Notify(response.message,null,null,'success');


                            // Reset form
                            reportForm[0].reset();
                            reportId.val('');
                            isEditMode = false;
                            _method.val('POST');
                            for (let i = 1; i <= 3; i++) {
                                $(`#preview${i}`).addClass('hidden');
                                $(`#preview${i} img`).attr('src', '');
                            }

                            // Update draft count (decrement by 1)
                            const draftCountElement = $('#draftCount');
                            if (draftCountElement.length) {
                                const currentDraftCount = parseInt(draftCountElement.text());
                                if (currentDraftCount > 0) {
                                    draftCountElement.text(currentDraftCount - 1);
                                }
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat mengirim laporan.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                // Handle validation errors
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('\n');
                            }
                            Notify(errorMessage,null,null,'error');
                        }
                    });
                });

                // Function to remove image
                window.removeImage = function(index) {
                    const input = $(`#image${index}`);
                    const preview = $(`#preview${index}`);

                    // Only decrement if there was actually an image uploaded
                    if (input[0].files.length > 0) {
                        imagesUploadedThisMonth--;
                        updateRemainingImages();
                    }

                    input.val('');
                    preview.find('img').attr('src', '');
                    preview.addClass('hidden');
                }

                // Function to edit a draft
                window.editDraft = function(draftId) {
                    // In a real application, this would load the draft data into the form
                    alert('Mengedit draft ' + draftId);

                    // Update the draft count (decrement by 1)
                    const draftCountElement = $('#draftCount');
                    if (draftCountElement.length) {
                        const currentDraftCount = parseInt(draftCountElement.text());
                        if (currentDraftCount > 0) {
                            draftCountElement.text(currentDraftCount - 1);
                        }
                    }
                }

                // Function to delete a draft
                window.deleteDraft = function(draftId) {
                    if (confirm('Apakah Anda yakin ingin menghapus draft ini?')) {
                        // In a real application, this would send a request to delete the draft
                        alert('Draft ' + draftId + ' telah dihapus');

                        // Update the draft count (decrement by 1)
                        const draftCountElement = $('#draftCount');
                        if (draftCountElement.length) {
                            const currentDraftCount = parseInt(draftCountElement.text());
                            if (currentDraftCount > 0) {
                                draftCountElement.text(currentDraftCount - 1);
                            }
                        }

                        // Remove the draft card from the DOM
                        const draftCard = $(`button[onclick="deleteDraft(${draftId})"]`).closest('.bg-white');
                        if (draftCard.length) {
                            draftCard.remove();
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>