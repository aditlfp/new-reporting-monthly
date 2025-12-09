<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')

        <div class="flex-1 mt-16 overflow-y-auto md:mt-0">
            <div class="min-h-screen px-3 py-6 bg-gradient-to-br from-slate-50 to-gray-100 sm:px-4 md:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <!-- Header Section -->
                    <div class="mb-6 md:mb-8">
                        <div class="flex flex-col justify-between gap-3 mb-4 sm:flex-row sm:items-center md:mb-6">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('check.upload')}}" class="flex items-center justify-center w-10 h-10 transition-all duration-300 ease-in-out text-gray-950 hover:text-gray-600">
                                    <i class="text-2xl ri-arrow-left-circle-line"></i>
                                </a>
                                <h1 class="text-xl font-bold text-gray-900 truncate md:text-2xl">
                                    {{ $UploadsAll[0]->user->nama_lengkap }} Uploads
                                </h1>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 md:text-base">Showing {{ $UploadsAll->count() }} of 14 uploads this month</p>

                        <!-- Filter/Status Summary -->
                        <div class="grid grid-cols-1 gap-3 mb-6 md:grid-cols-2 lg:grid-cols-4">
                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Total Uploads</p>
                                        <p class="text-2xl font-bold text-gray-900 md:text-3xl">{{ $UploadsAll->count() }}</p>
                                    </div>
                                    <i class="text-3xl text-gray-400 md:text-4xl ri-folder-line"></i>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Completed</p>
                                        <p class="text-2xl font-bold text-green-600 md:text-3xl">{{ $UploadsAll->where('status', 1)->count() }}</p>
                                    </div>
                                    <i class="text-3xl text-green-400 md:text-4xl ri-checkbox-circle-line"></i>
                                </div>
                            </div>

                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Draft</p>
                                        <p class="text-2xl font-bold text-orange-600 md:text-3xl">{{ $UploadsAll->where('status', 0)->count() }}</p>
                                    </div>
                                    <i class="text-3xl text-orange-400 md:text-4xl ri-loader-4-line"></i>
                                </div>
                            </div>

                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-md md:p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 md:text-sm">Remaining Uploads</p>
                                        <p class="text-2xl font-bold text-blue-600 md:text-3xl">{{ 14 - $UploadsAll->count() }}</p>
                                        <div class="mt-1 text-xs text-gray-400">This month</div>
                                    </div>
                                    <i class="text-3xl text-blue-400 md:text-4xl ri-time-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Cards Grid -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-2 xl:grid-cols-3">
                        @forelse($UploadsAll as $index => $allUploads)
                        <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg">
                            <!-- Card Header -->
                            <div class="p-3 text-white md:p-4 bg-gradient-to-r from-blue-600 to-blue-700">
                                <div class="flex items-center gap-2">
                                    <i class="text-lg md:text-xl ri-building-line"></i>
                                    <p class="text-sm font-semibold truncate md:text-base">{{ $allUploads->clients->name ?? 'Unknown Client' }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs font-semibold md:text-sm">NO: {{ $index + 1 }}</span>
                                    <span class="px-2 text-xs font-semibold text-gray-200 border border-gray-200 rounded-full">{{ $UploadsAll[0]->user->divisi->jabatan->name_jabatan }}</span>
                                </div>
                            </div>

                            <!-- Image Preview Grid -->
                            <div class="p-3 md:p-4">
                                <div class="grid grid-cols-3 gap-2 mb-3 md:gap-3 md:mb-4">
                                    <!-- Before Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs font-medium text-gray-600">Before</span>
                                        @if($allUploads->img_before)
                                            <div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_before) }}"  
                                                     alt="Before" 
                                                     class="object-cover w-full h-full">
                                                <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $allUploads->img_before) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                    target="_blank"
                                                    class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                                                    <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square">
                                                <i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Process Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs font-medium text-gray-600">Process</span>
                                        @if($allUploads->img_proccess)
                                            <div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_proccess) }}" 
                                                     alt="Process" 
                                                     class="object-cover w-full h-full">
                                                <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $allUploads->img_proccess) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                    target="_blank"
                                                    class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                                                    <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square">
                                                <i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Final Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs font-medium text-gray-600">Final</span>
                                        @if($allUploads->img_final)
                                            <div class="relative overflow-hidden border border-gray-300 rounded-lg group aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_final) }}" 
                                                     alt="Final" 
                                                     class="object-cover w-full h-full">
                                                <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $allUploads->img_final) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                    target="_blank"
                                                    class="absolute inset-0 flex items-center justify-center transition-all bg-black/0 group-hover:bg-black/50">
                                                    <i class="text-xl text-white opacity-0 ri-eye-line group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg aspect-square">
                                                <i class="text-xl text-gray-400 md:text-2xl ri-image-line"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Notes Preview -->
                                @if($allUploads->note)
                                <div class="p-2 mb-2 border border-gray-200 rounded-lg md:p-3 md:mb-3 bg-gray-50">
                                    <p class="flex items-center gap-1 mb-1 text-xs text-gray-500">
                                        <i class="ri-file-text-line"></i>
                                        Notes
                                    </p>
                                    <p class="text-xs text-gray-700 md:text-sm line-clamp-2">{{ $allUploads->note }}</p>
                                </div>
                                @endif

                                <!-- Meta Information -->
                                <div class="grid grid-cols-2 gap-2 mb-2 text-xs md:mb-3">
                                    <div class="flex items-center gap-1 text-gray-600">
                                        <i class="ri-calendar-line"></i>
                                        <span>{{ $allUploads->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-end">
                                        @if($allUploads->status == 1)
                                            <span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-green-500 rounded-full">
                                                <i class="ri-checkbox-circle-line"></i>
                                                <span class="hidden sm:inline">Completed</span>
                                            </span>
                                        @elseif($allUploads->status == 0)
                                            <span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-orange-500 rounded-full">
                                                <i class="ri-loader-4-line"></i>
                                                <span class="hidden sm:inline">Processing</span>
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-blue-500 rounded-full">
                                                <i class="ri-time-line"></i>
                                                <span class="hidden sm:inline">Pending</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- User Info -->
                                <div class="flex items-center gap-2 pt-2 mb-2 border-t border-gray-200 md:pt-3 md:mb-3">
                                    <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-blue-600 rounded-full md:w-8 md:h-8">
                                        {{ substr($allUploads->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-900 truncate md:text-sm">{{ $allUploads->user->name }}</p>
                                        <p class="hidden text-xs text-gray-500 truncate sm:block">{{ $allUploads->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full">
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md">
                                <div class="p-8 text-center md:p-16">
                                    <i class="mb-3 text-4xl text-gray-300 md:mb-4 md:text-6xl ri-inbox-line"></i>
                                    <h3 class="mb-1 text-xl font-bold text-gray-900 md:mb-2 md:text-2xl">No Uploads Yet</h3>
                                    <p class="text-sm text-gray-500 md:text-base">You haven't uploaded any images this month.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination if needed -->
                    @if($UploadsAll->count() > 0)
                    <div class="mt-6 text-center md:mt-8">
                        <p class="text-xs text-gray-600 md:text-sm">
                            {{ $UploadsAll[0]->user->nama_lengkap }} have used <span class="font-bold text-blue-600">{{ $UploadsAll->count() }}</span> out of 
                            <span class="font-bold">14</span> uploads this month
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 flex items-center justify-center hidden p-4 z-70 bg-black/75" onclick="closeImageModal()">
            <div class="relative w-full max-w-full max-h-full" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button type="button" onclick="closeImageModal()" class="absolute top-0 right-0 z-10 p-2 text-white transition-colors md:-top-12 hover:text-gray-300">
                    <i class="text-3xl md:text-4xl ri-close-line"></i>
                </button>
                
                <!-- Image Title -->
                <div class="absolute top-0 left-0 z-10 p-2 text-white md:-top-12">
                    <h3 id="modalImageTitle" class="text-base font-semibold md:text-xl"></h3>
                </div>
                
                <!-- Image Container -->
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden max-h-[90vh]">
                    <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[80vh] w-auto h-auto object-contain block mx-auto">
                </div>
                
                <!-- Download Button -->
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

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
    @endpush
</x-app-layout>