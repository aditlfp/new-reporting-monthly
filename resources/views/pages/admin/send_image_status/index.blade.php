<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
    @push('styles')
            /* Custom Scrollbar */
            #modalContent {
                max-height: calc(100vh - 200px);
                overflow-y: auto;
            }

            #modalContent::-webkit-scrollbar {
                width: 8px;
            }

            #modalContent::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            #modalContent::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            #modalContent::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            /* Animation */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            #fixedImagesModal > div > div {
                animation: fadeIn 0.2s ease-out;
            }
    @endpush
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 mt-16 overflow-y-auto md:mt-0">
            <!-- Filters -->
            <div class="my-6 bg-white shadow-lg card">
                <div class="card-body">
                    <form method="GET" action="{{ route('check.upload') }}"
                        class="grid grid-cols-1 md:gap-4 md:grid-cols-4">
                        <div class="form-control">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend required">Month</legend>
                                <select name="month" class="select select-sm rounded-sm">
                                    <option disabled selected>Pick a Month</option>
                                    <option value="">All Months</option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month['value'] }}"
                                            {{ request('month') == $month['value'] ? 'selected' : '' }}>
                                            {{ $month['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                         <div class="form-control">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend required">Year</legend>
                                <select name="year" class="select select-sm rounded-sm">
                                    <option disabled selected>Pick a Year</option>
                                    <option value="">All Year</option>
                                    <option value="2025">2025</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="form-control">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend required">Mitra</legend>
                                <select name="client_id" class="select select-sm rounded-sm">
                                    <option disabled selected>Pick a Mitra</option>
                                    <option value="">All Mitra</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="flex gap-x-2">
                            <div class="form-control">
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend required">Upload Count Min</legend>
                                    <input type="number" class="input input-sm rounded-sm validator" required
                                        placeholder="Type a number between 1 to 14" min="1" max="14"
                                        title="Must be between be 1 to 14" name="upload_min"
                                        value="{{ request('upload_min') }}" />
                                    <p class="validator-hint">Must be between be 1 to 14</p>
                                </fieldset>
                            </div>

                            <div class="form-control">
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend required">Upload Count Max</legend>
                                    <input type="number" class="input input-sm rounded-sm validator" required
                                        placeholder="Type a number between 1 to 14" min="1" max="14"
                                        title="Must be between be 1 to 14" name="upload_max"
                                        value="{{ request('upload_max') }}" />
                                    <p class="validator-hint">Must be between be 1 to 14</p>
                                </fieldset>
                            </div>
                        </div>
                        <div class="md:mt-3 form-control">
                            <label class="label">
                                <span class="label-text">&nbsp;</span>
                            </label>
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="btn btn-sm bg-blue-500/20 text-blue-500 hover:bg-blue-500 hover:text-white rounded-sm border-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('check.upload') }}"
                                    class="btn btn-sm bg-red-500/20 text-red-500 hover:bg-red-500 hover:text-white rounded-sm border-0">Reset</a>
                            </div>
                        </div>
                    </form>
                    {{-- Start Note --}}
                    <div
                        class="relative grid gap-2 p-4 mt-4 border-2 border-dashed rounded-md md:flex md:gap-4 grid-col-1 border-info">
                        <p class="absolute z-10 top-[-12px] left-4 bg-white px-1 font-semibold text-info">Info</p>
                        <div class="flex items-center gap-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                            <span class="text-xs">Info / Jabatan</span>
                        </div>

                        <div class="flex items-center gap-x-2">
                            <div class="w-4 h-4 rounded-full bg-amber-500"></div>
                            <span class="text-xs">Progress / Hitungan Upload 1 Bulan Masih Kurang</span>
                        </div>

                        <div class="flex items-center gap-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span class="text-xs">Selesai 11 Data / 33 Foto satu bulan</span>
                        </div>

                        <div class="flex items-center gap-x-2">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span class="text-xs">Belum Selesai</span>
                        </div>
                    </div>
                    {{-- End Note --}}
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-lg card">
                <div class="p-0 card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full text-xs table-zebra md:text-sm">
                            <thead>
                                <tr class="text-white bg-slate-950">
                                    <th class="p-2 text-center md:p-3">#</th>
                                    <th class="p-2 md:p-3">User / Nama</th>
                                    <th class="hidden p-2 md:p-3 sm:table-cell">Divisi</th>
                                    <th class="hidden p-2 md:p-3 md:table-cell">Client</th>
                                    <th class="hidden p-2 md:p-3 lg:table-cell">Upload Month</th>
                                    <th class="hidden p-2 md:p-3 lg:table-cell">Today Upload?</th>
                                    <th class="hidden p-2 text-center md:p-3 sm:table-cell">Monthly Count</th>
                                    <th class="p-2 text-center md:p-3">Status</th>
                                    <th class="p-2 text-center md:p-3">Action</th>
                                    <th class="p-2 text-center md:p-3">Yang Di ACC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uploads as $index => $upload)
                                    <tr class="hover">
                                        <td class="p-2 text-center md:p-3">{{ $uploads->firstItem() + $index }}</td>
                                        <td class="p-2 md:p-3">
                                            <div class="flex items-center gap-2 md:gap-3">
                                                <div class="avatar placeholder">
                                                    <div
                                                        class="w-8 h-8 rounded-full md:w-10 md:h-10 bg-neutral text-neutral-content">
                                                        <img
                                                            src={{ 'https://absensi-sac.sac-po.com/storage/images/' . $upload->user->image }}>
                                                    </div>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold truncate md:text-sm">
                                                        {{ $upload->user->nama_lengkap }}</div>
                                                    <div class="hidden text-xs truncate opacity-50 sm:block">
                                                        {{ $upload->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden p-2 md:p-3 sm:table-cell">
                                            <span
                                                class="uppercase badge badge-info badge-xs md:badge-sm w-fit h-fit">{{ $upload->jabatan }}</span>
                                        </td>
                                        <td class="hidden p-2 md:p-3 md:table-cell">
                                            @php
                                                $client = $clients->where('id', $upload->clients_id)->first()
                                            @endphp
                                            <div class="text-sm font-semibold">{{ $client->name }}</div>
                                            <div class="hidden text-xs opacity-50 lg:block">ID:
                                                {{ $upload->clients_id }}</div>
                                        </td>
                                        <td class="hidden p-2 md:p-3 lg:table-cell">
                                            <div class="text-sm">
                                                @forelse($months as $month)
                                                    @if ($month['value'] == $upload->month)
                                                        {{ $month['label'] . ' ' . $upload->year }}
                                                    @endif
                                                @empty
                                                    --
                                                @endforelse
                                            </div>
                                        </td>

                                        {{-- START LOGIC COUNTING TEMP --}}
                                        @php
                                            $badgeClass =
                                                $upload->total_count == 14
                                                    ? 'badge-success badge-xs md:badge-sm'
                                                    : ($upload->total_count <= 7
                                                        ? 'badge-warning badge-xs md:badge-sm'
                                                        : 'badge-error badge-xs md:badge-sm');
                                        @endphp
                                        {{-- END LOGIC COUNTING TEMP --}}


                                        <td class="p-2 text-center md:p-3">
                                            @if ($upload->has_uploaded_today)
                                                <span class="text-green-600"><i class="ri-checkbox-circle-line text-lg sm:text-2xl"></i></span>
                                            @else
                                                <span class="text-red-600"><i class="ri-close-circle-line text-lg sm:text-2xl"></i></span>
                                            @endif
                                        </td>

                                        <td class="p-2 text-center md:p-3">
                                            @if ($upload->total_count > 0)
                                                <span class="badge badge-success">{{ $upload->total_count }}</span>
                                            @else
                                                <span class="badge badge-error">0</span>
                                            @endif
                                        </td>

                                        <td class="p-2 text-center md:p-3">
                                            @if ($upload->total_count > 11)
                                                <span class="gap-1 badge badge-xs md:badge-sm badge-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Completed</span>
                                                </span>
                                            @elseif($upload->total_count < 11)
                                                <span class="gap-1 badge badge-xs md:badge-sm badge-error w-fit h-fit">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Not Finished</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-center md:p-3">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ url('admin/admin-check-status/' . $upload->user->id . '/' . $upload->clients_id . '/' . $upload->month . '/'. $upload->year) }}"
                                                    class="text-blue-500 btn btn-ghost btn-xs" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="p-2 text-center md:p-3">
                                            <button onclick='openFixedImagesModal(@json($upload))' class="btn btn-sm bg-blue-500/20 text-blue-500 hover:bg-blue-500 hover:text-white rounded-sm border-0">Detail</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-8 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-12 h-12 text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500">No uploads found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($uploads->hasPages())
                        <div
                            class="flex flex-col items-center justify-between gap-4 p-4 border-t md:flex-row md:gap-0">
                            <div class="text-sm text-center text-gray-600 md:text-left">
                                Showing {{ $uploads->firstItem() }} to {{ $uploads->lastItem() }} of
                                {{ $uploads->total() }} entries
                            </div>
                            <div class="w-full overflow-x-auto md:w-auto">
                                {{ $uploads->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="fixedImagesModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-md">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="relative w-full max-w-6xl bg-white rounded-lg shadow-xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 md:p-6">
                        <h3 class="text-lg font-bold text-gray-900 md:text-xl">
                            <i class="ri-image-line"></i>
                            Accept Images Detail
                        </h3>
                        <button onclick="closeFixedImagesModal()" class="text-gray-400 transition-colors hover:text-gray-600">
                            <i class="text-2xl ri-close-line"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div id="modalContent" class="p-4 md:p-6">
                        <!-- Loading State -->
                        <div id="loadingState" class="flex flex-col items-center justify-center py-12">
                            <div class="w-12 h-12 border-4 border-blue-200 rounded-full border-t-blue-600 animate-spin"></div>
                            <p class="mt-4 text-sm text-gray-600">Loading data...</p>
                        </div>

                        <!-- Content Grid (Hidden initially) -->
                        <div id="contentGrid" class="hidden grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-2 xl:grid-cols-3">
                            <!-- Content will be populated here -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="hidden py-12 text-center">
                            <i class="mb-3 text-6xl text-gray-300 ri-inbox-line"></i>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">No Fixed Images</h3>
                            <p class="text-sm text-gray-500">No fixed images found for this user.</p>
                        </div>

                        <!-- Error State -->
                        <div id="errorState" class="hidden py-12 text-center">
                            <i class="mb-3 text-6xl text-red-300 ri-error-warning-line"></i>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">Error Loading Data</h3>
                            <p class="text-sm text-gray-500">Something went wrong. Please try again.</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-2 p-4 border-t border-gray-200 md:p-6">
                        <button onclick="closeFixedImagesModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    @push('scripts')
        <script>
            // Auto-refresh for processing status
            @if ($uploads->where('status', 'processing')->count() > 0)
                setTimeout(() => {
                    window.location.reload();
                }, 30000);
            @endif
        </script>

        <script>
            // Open Modal and Fetch Data
            function openFixedImagesModal(upload) {
                $('#fixedImagesModal').removeClass('hidden');
                
                $('#loadingState').show();
                $('#contentGrid').addClass('hidden');
                $('#emptyState').addClass('hidden');
                $('#errorState').addClass('hidden');

                // Fetch data via AJAX
                const detailUrl = "{{ route('admin.api.v1.check.detail', ['user_id' => 0, 'month' => 0, 'year' => 0]) }}";

                let finalUrl = detailUrl
                    .replace('/0/', `/${upload.user_id}/`)
                    .replace('/0/', `/${upload.month}/`)
                    .replace('/0', `/${upload.year}`);
                $.ajax({
                    url: finalUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#loadingState').hide();
                        
                        if (response.status && response.data && response.data.length > 0) {
                            $('#contentGrid').removeClass('hidden');
                            
                            $('#contentGrid').empty();
                            
                            // Populate data
                            response.data.forEach(function(item, index) {
                                const card = createFixedImageCard(item, index + 1);
                                $('#contentGrid').append(card);
                            });
                        } else {
                            // Show empty state
                            $('#emptyState').removeClass('hidden');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingState').hide();
                        
                        $('#errorState').removeClass('hidden');
                        
                        console.error('Error fetching data:', error);
                    }
                });
            }

            function closeFixedImagesModal() {
                $('#fixedImagesModal').addClass('hidden');
                $('#contentGrid').empty();
            }

            function createFixedImageCard(data, index) {
                const createdDate = new Date(data.created_at);
                const formattedDate = createdDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
                
                return `
                    <div class="overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg">
                        <!-- Card Header -->
                        <div class="p-3 text-white md:p-4 bg-gradient-to-r from-blue-600 to-blue-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="text-lg md:text-xl ri-image-line"></i>
                                    <p class="text-sm font-semibold md:text-base">Fixed Image #${data.id}</p>
                                </div>
                                <span class="text-xs font-semibold md:text-sm">NO: ${index}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3 md:p-4">
                            <!-- Info Grid -->
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <span class="text-xs font-medium text-gray-600">Upload ID:</span>
                                    <span class="text-xs font-semibold text-gray-900">#${data.upload_image_id}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <span class="text-xs font-medium text-gray-600">User ID:</span>
                                    <span class="text-xs font-semibold text-gray-900">#${data.user_id}</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <span class="text-xs font-medium text-gray-600">Client ID:</span>
                                    <span class="text-xs font-semibold text-gray-900">#${data.clients_id}</span>
                                </div>
                            </div>

                            <!-- Date Info -->
                            <div class="pt-2 border-t border-gray-200">
                                <div class="flex items-center gap-1 text-xs text-gray-600">
                                    <i class="ri-calendar-line"></i>
                                    <span>Created: ${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            $(document).on('click', '#fixedImagesModal', function(e) {
                if (e.target === this) {
                    closeFixedImagesModal();
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$('#fixedImagesModal').hasClass('hidden')) {
                    closeFixedImagesModal();
                }
            });
            </script>
    @endpush
</x-app-layout>
