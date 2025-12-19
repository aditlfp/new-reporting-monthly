<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
<!-- Top Navbar -->
    <x-user-navbar />
    <div class="flex h-screen bg-slate-50">
 
    {{-- sidebar --}}
      <x-user-sidebar />
        <div class="flex-1 mt-16 overflow-y-auto md:mt-0">
            <div class="min-h-screen px-3 py-6 bg-gradient-to-br from-slate-50 to-gray-100 sm:px-4 md:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <!-- Header Section -->
                    <div class="mb-6 md:mb-8">
                        <div class="flex flex-col justify-between gap-3 mb-4 sm:flex-row sm:items-center md:mb-6">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('counting.data.upload.spv')}}" class="flex items-center justify-center w-10 h-10 transition-all duration-300 ease-in-out text-gray-950 hover:text-gray-600">
                                    <i class="text-2xl ri-arrow-left-circle-line"></i>
                                </a>
                                <h1 class="text-xl font-bold text-gray-900 truncate md:text-2xl" id="userName">
                                    Loading...
                                </h1>
                            </div>
                        </div>

                        <!-- Filter/Status Summary -->
                        <div class="flex gap-x-2 w-full justify-between">
                            <div class="p-3 bg-white min-w-1/2 border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Total Uploads</p>
                                        <p class="text-2xl font-bold text-gray-900 md:text-3xl" id="totalUploads">0</p>
                                    </div>
                                    <i class="text-3xl text-gray-400 md:text-4xl ri-folder-line"></i>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-white min-w-1/2 border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Has Verif Image</p>
                                        <div class="flex">
                                            <p class="text-2xl font-bold text-blue-600 md:text-3xl" id="fixedUploads">0</p>
                                            <p class="text-2xl font-bold text-gray-300 md:text-3xl">/11</p>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-400">Bulan Ini</div>
                                    </div>
                                    <i class="text-3xl text-blue-400 md:text-4xl ri-time-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Cards Grid -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-2 xl:grid-cols-3" id="uploadCardsContainer">
                        <!-- Loading State -->
                        <div class="col-span-full">
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md">
                                <div class="p-8 text-center md:p-16">
                                    <i class="mb-3 text-4xl text-gray-300 animate-spin md:mb-4 md:text-6xl ri-loader-4-line"></i>
                                    <h3 class="mb-1 text-xl font-bold text-gray-900 md:mb-2 md:text-2xl">Loading...</h3>
                                    <p class="text-sm text-gray-500 md:text-base">Please wait while we fetch your uploads.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination if needed -->
                    <div class="mt-6 text-center md:mt-8" id="paginationInfo" style="display: none;">
                        <p class="text-xs text-gray-600 md:text-sm">
                            <span id="clientName"></span> memiliki <span class="font-bold text-blue-600" id="totalCount">0</span>
                            verif bulan ini
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden p-4 z-70 bg-black/75" onclick="closeImageModal()">
            <div class="relative w-full max-w-full max-h-full" onclick="event.stopPropagation()">
                <button type="button" onclick="closeImageModal()" class="absolute top-0 right-0 z-10 p-2 text-white transition-colors md:-top-12 hover:text-gray-300">
                    <i class="text-3xl md:text-4xl ri-close-line"></i>
                </button>
                
                <div class="absolute top-0 left-0 z-10 p-2 text-white md:-top-12">
                    <h3 id="modalImageTitle" class="text-base font-semibold md:text-xl"></h3>
                </div>
                
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden max-h-[90vh]">
                    <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[80vh] w-auto h-auto object-contain block mx-auto">
                </div>
                
                <div class="absolute bottom-0 right-0 z-10 p-2 md:-bottom-11">
                    <a id="downloadLink" href="" download class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg md:px-4 md:py-2 md:text-base hover:bg-blue-700">
                        <i class="ri-download-line"></i>
                        <span class="hidden sm:inline">Download Image</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Fetch data via AJAX
        const userId = {{ request()->route('id') }}
        const month = {{ request()->route('month') }}
        const year = {{ request()->route('year') }}
        const detailUrl = "{{ route('api.v1.count.per.user', ['id' => 0, 'month' => 0, 'year' => 0]) }}";

        let finalUrl = detailUrl
            .replace('/0/', `/${userId}/`)
            .replace('/0/', `/${month}/`)
            .replace('/0', `/${year}`);
        function createUploadCard(client, upload, index, nama) {
            const statusBadge = upload.status == 1 
                ? '<span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-green-500 rounded-full"><i class="ri-checkbox-circle-line"></i><span class="hidden sm:inline">Completed</span></span>'
                : upload.status == 0
                ? '<span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-orange-500 rounded-full"><i class="ri-loader-4-line"></i><span class="hidden sm:inline">Processing</span></span>'
                : '<span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-blue-500 rounded-full"><i class="ri-time-line"></i><span class="hidden sm:inline">Pending</span></span>';

            const beforeImage = upload.img_before && upload.img_before !== 'none'
                ? `<div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                    <img src="/storage/${upload.img_before}" alt="Before" class="object-cover w-full h-full">
                    <button type="button" onclick="openImageModal('/storage/${upload.img_before}', 'Before - ID #${upload.upload_image_id}')" class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                        <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                    </button>
                   </div>`
                : '<div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square"><i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i></div>';

            const processImage = upload.img_proccess && upload.img_proccess !== 'none'
                ? `<div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                    <img src="/storage/${upload.img_proccess}" alt="Process" class="object-cover w-full h-full">
                    <button type="button" onclick="openImageModal('/storage/${upload.img_proccess}', 'Process - ID #${upload.upload_image_id}')" class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                        <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                    </button>
                   </div>`
                : '<div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square"><i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i></div>';

            const finalImage = upload.img_final && upload.img_final !== 'none'
                ? `<div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                    <img src="/storage/${upload.img_final}" alt="Final" class="object-cover w-full h-full">
                    <button type="button" onclick="openImageModal('/storage/${upload.img_final}', 'Final - ID #${upload.upload_image_id}')" class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                        <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                    </button>
                   </div>`
                : '<div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square"><i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i></div>';

            const noteSection = upload.note && upload.note !== 'none'
                ? `<div class="p-2 mb-2 border border-gray-200 rounded-lg md:p-3 md:mb-3 bg-gray-50">
                    <p class="flex items-center gap-1 mb-1 text-xs text-gray-500"><i class="ri-file-text-line"></i>Notes</p>
                    <p class="text-xs text-gray-700 md:text-sm line-clamp-2">${upload.note}</p>
                   </div>`
                : '';

            const createdDate = new Date(upload.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            return `
                <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg">
                    <div class="p-3 text-white md:p-4 bg-gradient-to-r from-blue-600 to-blue-700">
                        <div class="flex items-center gap-2">
                            <i class="text-lg md:text-xl ri-building-line"></i>
                            <p class="text-sm font-semibold truncate md:text-base">${client}</p>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs font-semibold md:text-sm">NO: ${index + 1}</span>
                            <span class="px-2 text-xs font-semibold text-gray-200 border border-gray-200 rounded-full">Position</span>
                        </div>
                    </div>
                    
                    <div class="p-3 md:p-4">
                        <div class="grid grid-cols-3 gap-2 mb-3 md:gap-3 md:mb-4">
                            <div class="space-y-1">
                                <span class="text-xs font-medium text-gray-600">Before</span>
                                ${beforeImage}
                            </div>
                            <div class="space-y-1">
                                <span class="text-xs font-medium text-gray-600">Process</span>
                                ${processImage}
                            </div>
                            <div class="space-y-1">
                                <span class="text-xs font-medium text-gray-600">Final</span>
                                ${finalImage}
                            </div>
                        </div>

                        ${noteSection}

                        <div class="grid grid-cols-2 gap-2 mb-2 text-xs md:mb-3">
                            <div class="flex items-center gap-1 text-gray-600">
                                <i class="ri-calendar-line"></i>
                                <span>${createdDate}</span>
                            </div>
                            <div class="flex items-center justify-end">
                                ${statusBadge}
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2 mb-2 border-t border-gray-200 md:pt-3 md:mb-3">
                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-blue-600 rounded-full md:w-8 md:h-8">
                                U
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 truncate md:text-sm">Di Verifikasi: ${nama}</p>
                                <p class="hidden text-xs text-gray-500 truncate sm:block">Upload ID: ${upload.upload_image_id}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Fungsi untuk menampilkan empty state
        function showEmptyState() {
            return `
                <div class="col-span-full">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md">
                        <div class="p-8 text-center md:p-16">
                            <i class="mb-3 text-4xl text-gray-300 md:mb-4 md:text-6xl ri-inbox-line"></i>
                            <h3 class="mb-1 text-xl font-bold text-gray-900 md:mb-2 md:text-2xl">No Uploads Yet</h3>
                            <p class="text-sm text-gray-500 md:text-base">You haven't uploaded any images this month.</p>
                        </div>
                    </div>
                </div>
            `;
        }
        // Fungsi utama untuk load data
        function loadDataById() {
            $.ajax({
                url: finalUrl,
                method: 'GET',
                success: function (res) {
                    if (res.status && res.data && res.data.fixed.length > 0) {
                        const uploads = res.data?.fixed;
                        const client = res.data.client?.client?.name;
                        // Update summary statistics
                        $('#totalUploads').text(uploads.length);
                        $('#completedUploads').text(uploads.filter(u => u.status == 1).length);
                        $('#draftUploads').text(uploads.filter(u => u.status == 0).length);
                        $('#userName').text(res.data.user.nama_lengkap)
                        $('#clientName').text(client)
                        // Count uploads with img_final (has acc image)
                        const fixedCount = uploads.filter(u => u.img_final && u.img_final !== 'none').length;
                        $('#fixedUploads').text(fixedCount);
                        
                        // Update header
                        
                        // Generate cards
                        let cardsHTML = '';

                        uploads.forEach((upload, index) => {
                            const nama = res.data.user.id == upload.user_id ? res.data.user.nama_lengkap : '';
                            cardsHTML += createUploadCard(client, upload, index, nama);
                        });
                        
                        $('#uploadCardsContainer').html(cardsHTML);
                        
                        // Show pagination info
                        $('#totalCount').text(uploads.length);
                        $('#paginationInfo').show();
                        
                    } else {
                        // Show empty state
                        $('#uploadCardsContainer').html(showEmptyState());
                        $('#totalUploads').text('0');
                        $('#completedUploads').text('0');
                        $('#draftUploads').text('0');
                        $('#fixedUploads').text('0');
                        $('#uploadCount').text('No uploads found');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading data:', error);
                    $('#uploadCardsContainer').html(`
                        <div class="col-span-full">
                            <div class="bg-white border border-red-200 rounded-lg shadow-md">
                                <div class="p-8 text-center md:p-16">
                                    <i class="mb-3 text-4xl text-red-300 md:mb-4 md:text-6xl ri-error-warning-line"></i>
                                    <h3 class="mb-1 text-xl font-bold text-gray-900 md:mb-2 md:text-2xl">Error Loading Data</h3>
                                    <p class="text-sm text-gray-500 md:text-base">Failed to fetch uploads. Please try again later.</p>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Modal functions
        function openImageModal(imageUrl, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalImageTitle');
            const downloadLink = document.getElementById('downloadLink');
            
            modalImage.src = imageUrl;
            modalTitle.textContent = title;
            downloadLink.href = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        $(document).ready(function() {
            loadDataById();
        });
    </script>
    @endpush
</x-app-layout>