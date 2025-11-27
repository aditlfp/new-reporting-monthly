<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
	<div class="flex h-screen bg-slate-50">
	    @include('components.sidebar-component')
	    <div class="flex-1 p-6 overflow-y-auto">
		    <!-- Filters -->
		    <div class="card bg-white shadow-lg mb-6">
		        <div class="card-body">
		            <form method="GET" action="{{ route('check.upload') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
		                <div class="form-control">
		                	<fieldset class="fieldset">
							  <legend class="fieldset-legend">Month</legend>
							  <select name="month" class="select select-sm">
							    <option disabled selected>Pick a Month</option>
							    <option value="">All Months</option>
							    @foreach($months as $month)
								    <option value="{{ $month['value'] }}" {{ request('month') == $month['value'] ? 'selected' : '' }}>
								        {{ $month['label'] }}
								    </option>
								@endforeach
							  </select>
							</fieldset>
		                </div>

		                <div class="form-control">
		                	<fieldset class="fieldset">
							  <legend class="fieldset-legend">Mitra</legend>
							  <select name="client_id" class="select select-sm">
							    <option disabled selected>Pick a Mitra</option>
							    <option value="">All Mitra</option>
							    @foreach($clients as $client)
		                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
		                                {{ $client->name }}
		                            </option>
		                        @endforeach
							  </select>
							</fieldset>
		                </div>

		                <div class="flex gap-x-2">

			               <div class="form-control">
			               	<fieldset class="fieldset">
							  <legend class="fieldset-legend">Upload Count Min</legend>
							    <input
								  type="number"
								  class="input input-sm validator"
								  required
								  placeholder="Type a number between 1 to 14"
								  min="1"
								  max="14"
								  title="Must be between be 1 to 14"
								  name="upload_min"
								  value="{{ request('upload_min') }}"
								/>
								<p class="validator-hint">Must be between be 1 to 14</p>
							</fieldset>
							</div>

							<div class="form-control">
							  <fieldset class="fieldset">
							  	<legend class="fieldset-legend">Upload Count Max</legend>
							     <input
								  type="number"
								  class="input input-sm validator"
								  required
								  placeholder="Type a number between 1 to 14"
								  min="1"
								  max="14"
								  title="Must be between be 1 to 14"
								  name="upload_max"
								  value="{{ request('upload_max') }}"
								/>
								<p class="validator-hint">Must be between be 1 to 14</p>
							  </fieldset>
							</div>
						</div>
		                <div class="form-control mt-3">
		                    <label class="label">
		                        <span class="label-text">&nbsp;</span>
		                    </label>
		                    <div class="flex gap-2">
		                        <button type="submit" class="btn btn-sm bg-slate-950 hover:bg-slate-800 text-white px-6 py-4">
		                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
		                            </svg>
		                            Filter
		                        </button>
		                        <a href="{{ route('check.upload') }}" class="btn btn-sm bg-red-500 hover:bg-red-400 text-white px-6 py-4">Reset</a>
		                    </div>
		                </div>
		            </form>
		            {{-- Start Note --}}
		            <div class="flex gap-x-4">
		            	<div class="flex gap-x-2 items-center">
		            		<div class="bg-blue-500 w-4 h-4 rounded-full"></div>
		            		<span class="text-xs">Info / Jabatan</span>
		            	</div>

		            	<div class="flex gap-x-2 items-center">
		            		<div class="bg-amber-500 w-4 h-4 rounded-full"></div>
		            		<span class="text-xs">Progress / Hitungan Upload 1 Bulan Masih Kurang</span>
		            	</div>

		            	<div class="flex gap-x-2 items-center">
		            		<div class="bg-green-500 w-4 h-4 rounded-full"></div>
		            		<span class="text-xs">Selesai 14 Data / 33 Foto satu bulan</span>
		            	</div>

		            	<div class="flex gap-x-2 items-center">
		            		<div class="bg-red-500 w-4 h-4 rounded-full"></div>
		            		<span class="text-xs">Belum Selesai</span>
		            	</div>
		            </div>
		        	{{-- End Note --}}
		        </div>
		    </div>

		    <!-- Table -->
		    <div class="card bg-white shadow-lg">
		        <div class="card-body p-0">
		            <div class="overflow-x-auto">
		                <table class="table table-zebra w-full">
		                    <thead>
		                        <tr class="bg-slate-950 text-white">
		                            <th class="text-center">#</th>
		                            <th>User / Nama</th>
		                            <th>Divisi</th>
		                            <th>Client</th>
		                            <th>Upload Month</th>
		                            <th class="text-center">Monthly Count</th>
		                            <th class="text-center">Status</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        @forelse($uploads as $index => $upload)
		                        <tr class="hover">
		                            <td class="text-center">{{ $uploads->firstItem() + $index }}</td>
		                            <td>
		                                <div class="flex items-center gap-3">
		                                    <div class="avatar placeholder">
		                                        <div class="bg-neutral text-neutral-content rounded-full w-10 h-10">
		                                            <img src={{ "https://absensi-sac.sac-po.com/storage/images/" . $upload->user->image }}>
		                                        </div>
		                                    </div>
		                                    <div>
		                                        <div class="font-bold text-xs">{{ $upload->user->nama_lengkap }}</div>
		                                        <div class="text-xs opacity-50">{{ $upload->user->email }}</div>
		                                    </div>
		                                </div>
		                            </td>
		                            <td>
		                            	<span class="uppercase badge badge-info badge-sm w-fit h-fit">{{$upload->user->divisi->jabatan->name_jabatan}}</span>
		                            </td>
		                            <td>
		                                <div class="font-semibold text-sm">{{ $upload->clients->name }}</div>
		                                <div class="text-xs opacity-50">ID: {{ $upload->clients_id }}</div>
		                            </td>
		                            <td>
		                               <div class="text-sm">
		                               	@forelse($months as $month)
		                               		@if($month['value'] == $upload->month)
		                               			{{ $month['label'] . ' '. $upload->year }}
		                               		@endif
		                               	@empty
		                               		 --
		                               	@endforelse
										</div>
		                            </td>

		                            {{-- START LOGIC COUNTING TEMP --}}
		                            @php
	                                    $badgeClass = $upload->total_count == 14 ? 'badge-success badge-sm' : ($upload->total_count <= 7 ? 'badge-warning badge-sm' : 'badge-error badge-sm');
	                                @endphp
		                            {{-- END LOGIC COUNTING TEMP --}}

		                            <td class="text-center">
		                                <div class="badge {{ $badgeClass }} badge-sm font-bold">
		                                    {{ $upload->total_count }}/14
		                                </div>
		                            </td>
		                            <td class="text-center">
		                                @if($upload->total_count == 14)
		                                    <span class="badge badge-sm badge-success gap-1">
		                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
		                                        </svg>
		                                        Completed
		                                    </span>
		                                @elseif($upload->total_count < 14)
		                                    <span class="badge badge-sm badge-error gap-1 w-fit h-fit">
		                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
		                                        </svg>
		                                        Not Finished
		                                    </span>
		                                @endif
		                            </td>
		                            <td class="text-center">
		                                <div class="flex gap-1 justify-center">
		                                    <a href="{{ url('admin/admin-check-status/'. $upload->user->id) }}" class="btn btn-ghost btn-xs" title="View Details">
		                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
		                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
		                                        </svg>
		                                    </a>
		                                   
		                                </div>
		                            </td>
		                        </tr>
		                        @empty
		                        <tr>
		                            <td colspan="8" class="text-center py-8">
		                                <div class="flex flex-col items-center gap-2">
		                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
		            @if($uploads->hasPages())
		            <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t">
		                <div class="text-sm text-gray-600 mb-4 md:mb-0">
		                    Showing {{ $uploads->firstItem() }} to {{ $uploads->lastItem() }} of {{ $uploads->total() }} entries
		                </div>
		                <div>
		                    {{ $uploads->links() }}
		                </div>
		            </div>
		            @endif
		        </div>
		    </div>
		</div>
	</div>


	@push('scripts')
	<script>
	    // Auto-refresh for processing status
	    @if($uploads->where('status', 'processing')->count() > 0)
	    setTimeout(() => {
	        window.location.reload();
	    }, 30000);
	    @endif
	</script>
	@endpush
</x-app-layout>