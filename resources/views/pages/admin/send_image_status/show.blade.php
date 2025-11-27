<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')

        <div class="flex-1 overflow-y-auto">
            <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Header Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                            	<div class="flex items-center gap-3">
	                            	<a href="{{ route('check.upload')}}" class="text-2xl font-bold text-gray-950 hover:text-gray-600 flex items-center gap-3 transition-all ease-in-out duration-300">
	                                	<i class="ri-arrow-left-circle-line"></i>
	                            	</a>
	                                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
	                                    {{ $UploadsAll[0]->user->nama_lengkap }} Uploads
	                                </h1>
	                            </div>
                                <p class="text-gray-600 mt-2">Showing {{ $UploadsAll->count() }} of 14 uploads this month</p>
                            </div>
                        </div>

                        <!-- Filter/Status Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-500 text-sm">Total Uploads</p>
                                        <p class="text-3xl font-bold text-gray-900">{{ $UploadsAll->count() }}</p>
                                    </div>
                                    <i class="ri-folder-line text-4xl text-gray-400"></i>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-500 text-sm">Completed</p>
                                        <p class="text-3xl font-bold text-green-600">{{ $UploadsAll->where('status', 1)->count() }}</p>
                                    </div>
                                    <i class="ri-checkbox-circle-line text-4xl text-green-400"></i>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-500 text-sm">Draft</p>
                                        <p class="text-3xl font-bold text-orange-600">{{ $UploadsAll->where('status', 0)->count() }}</p>
                                    </div>
                                    <i class="ri-loader-4-line text-4xl text-orange-400"></i>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-500 text-sm">Remaining Uploads</p>
                                        <p class="text-3xl font-bold text-blue-600">{{ 14 - $UploadsAll->count() }}</p>
                                    	<div class="text-xs text-gray-400 mt-1">This month</div>

                                    </div>
                                    <i class="ri-time-line text-4xl text-blue-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($UploadsAll as $index => $allUploads)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                                <div class="flex items-center gap-2">
                                    <i class="ri-building-line text-xl"></i>
                                    <p class="font-semibold truncate">{{ $allUploads->clients->name ?? 'Unknown Client' }}</p>
                                </div>
                                <div class="flex justify-between items-center">
                            	<span class="text-sm font-semibold">NO: {{ $index + 1 }}</span>
                                 <span class="text-sm text-gray-200 font-semibold border border-gray-200 rounded-full px-2">{{ $UploadsAll[0]->user->divisi->jabatan->name_jabatan }}</span>
                                </div>
                            </div>

                            <!-- Image Preview Grid -->
                            <div class="p-4">
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <!-- Before Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs text-gray-600 font-medium">Before</span>
                                        @if($allUploads->img_before)
                                            <div class="relative group overflow-hidden rounded-lg border border-gray-300 aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_before) }}"  
                                                     alt="Before" 
                                                     class="w-full h-full object-cover">
                                                <button type="button" 
                                                	onclick="openImageModal('{{ asset('storage/' . $allUploads->img_before) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                   target="_blank"
                                                   class="absolute inset-0 bg-black/0 group-hover:bg-black/50 flex items-center justify-center transition-all">
                                                    <i class="ri-eye-line text-white text-xl opacity-0 group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <i class="ri-image-line text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Process Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs text-gray-600 font-medium">Process</span>
                                        @if($allUploads->img_proccess)
                                            <div class="relative group overflow-hidden rounded-lg border border-gray-300 aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_proccess) }}" 
                                                     alt="Process" 
                                                     class="w-full h-full object-cover">
                                                <button type="button" 
                                                	onclick="openImageModal('{{ asset('storage/' . $allUploads->img_proccess) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                   target="_blank"
                                                   class="absolute inset-0 bg-black/0 group-hover:bg-black/50 flex items-center justify-center transition-all">
                                                    <i class="ri-eye-line text-white text-xl opacity-0 group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <i class="ri-image-line text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Final Image -->
                                    <div class="space-y-1">
                                        <span class="text-xs text-gray-600 font-medium">Final</span>
                                        @if($allUploads->img_final)
                                            <div class="relative group overflow-hidden rounded-lg border border-gray-300 aspect-square">
                                                <img src="{{ asset('storage/' . $allUploads->img_final) }}" 
                                                     alt="Final" 
                                                     class="w-full h-full object-cover">
                                                <button type="button" 
                                                	onclick="openImageModal('{{ asset('storage/' . $allUploads->img_final) }}', 'Final - ID #{{ $allUploads->id }}')"
                                                   target="_blank"
                                                   class="absolute inset-0 bg-black/0 group-hover:bg-black/50 flex items-center justify-center transition-all">
                                                    <i class="ri-eye-line text-white text-xl opacity-0 group-hover:opacity-100"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <i class="ri-image-line text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Notes Preview -->
                                @if($allUploads->note)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-3">
                                    <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                        <i class="ri-file-text-line"></i>
                                        Notes
                                    </p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $allUploads->note }}</p>
                                </div>
                                @endif

                                <!-- Meta Information -->
                                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                                    <div class="flex items-center gap-1 text-gray-600">
                                        <i class="ri-calendar-line"></i>
                                        <span>{{ $allUploads->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-end mb-2">
                                    @if($allUploads->status == 1)
                                        <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full flex items-center gap-1">
                                            <i class="ri-checkbox-circle-line"></i>
                                            Completed
                                        </span>
                                    @elseif($allUploads->status == 0)
                                        <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full flex items-center gap-1">
                                            <i class="ri-loader-4-line"></i>
                                            Processing
                                        </span>
                                    @else
                                        <span class="bg-blue-500 text-white text-xs px-3 py-1 rounded-full flex items-center gap-1">
                                            <i class="ri-time-line"></i>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                                </div>

                                <!-- User Info -->
                                <div class="flex items-center gap-2 pt-3 border-t border-gray-200 mb-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                        {{ substr($allUploads->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $allUploads->user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $allUploads->user->email }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @empty
                        <div class="col-span-full">
                            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                                <div class="p-16 text-center">
                                    <i class="ri-inbox-line text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Uploads Yet</h3>
                                    <p class="text-gray-500 mb-6">You haven't uploaded any images this month.</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination if needed -->
                    @if($UploadsAll->count() > 0)
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            {{ $UploadsAll[0]->user->nama_lengkap }} have used <span class="font-bold text-blue-600">{{ $UploadsAll->count() }}</span> out of 
                            <span class="font-bold">14</span> uploads this month
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Image Modal -->
	    <div id="imageModal" class="hidden fixed inset-0 bg-black/75 z-50 flex items-center justify-center p-10" onclick="closeImageModal()">
	        <div class="relative max-w-7xl max-h-full" onclick="event.stopPropagation()">
	            <!-- Close Button -->
	            <button type="button" onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors pt-4 mb-2">
	                <i class="ri-close-line text-4xl"></i>
	            </button>
	            
	            <!-- Image Title -->
	            <div class="absolute -top-12 left-0 text-white pt-5">
	                <h3 id="modalImageTitle" class="text-xl font-semibold"></h3>
	            </div>
	            
	            <!-- Image Container -->
	            <div class="bg-white rounded-lg shadow-2xl overflow-hidden max-h-[90vh]">
	                <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[90vh] w-auto h-auto object-contain">
	            </div>
	            
	            <!-- Download Button -->
	            <div class="absolute -bottom-11 right-0">
	                <a id="downloadLink" href="" download class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
	                    <i class="ri-download-line"></i>
	                    Download Image
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