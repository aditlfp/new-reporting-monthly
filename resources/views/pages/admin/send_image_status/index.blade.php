<x-app-layout title="Check Status Upload" subtitle="Monitor upload status - Maximum 14 uploads per month per mitra">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 mt-16 overflow-y-auto md:mt-0">
            <!-- Filters -->
            <div class="mb-6 bg-white shadow-lg card">
                <div class="card-body">
                    <form method="GET" action="{{ route('check.upload') }}"
                        class="grid grid-cols-1 md:gap-4 md:grid-cols-4">
                        <div class="form-control">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Month</legend>
                                <select name="month" class="select select-sm">
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
                                <legend class="fieldset-legend">Mitra</legend>
                                <select name="client_id" class="select select-sm">
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
                                    <legend class="fieldset-legend">Upload Count Min</legend>
                                    <input type="number" class="input input-sm validator" required
                                        placeholder="Type a number between 1 to 14" min="1" max="14"
                                        title="Must be between be 1 to 14" name="upload_min"
                                        value="{{ request('upload_min') }}" />
                                    <p class="validator-hint">Must be between be 1 to 14</p>
                                </fieldset>
                            </div>

                            <div class="form-control">
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Upload Count Max</legend>
                                    <input type="number" class="input input-sm validator" required
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
                                    class="px-6 py-4 text-white btn btn-sm bg-slate-950 hover:bg-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('check.upload') }}"
                                    class="px-6 py-4 text-white bg-red-500 btn btn-sm hover:bg-red-400">Reset</a>
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
                            <span class="text-xs">Selesai 14 Data / 33 Foto satu bulan</span>
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
                                    <th class="hidden p-2 text-center md:p-3 sm:table-cell">Monthly Count</th>
                                    <th class="p-2 text-center md:p-3">Status</th>
                                    <th class="p-2 text-center md:p-3"></th>
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
                                                class="uppercase badge badge-info badge-xs md:badge-sm w-fit h-fit">{{ $upload->user->divisi->jabatan->name_jabatan }}</span>
                                        </td>
                                        <td class="hidden p-2 md:p-3 md:table-cell">
                                            <div class="text-sm font-semibold">{{ $upload->clients->name }}</div>
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

                                        <td class="hidden p-2 text-center md:p-3 sm:table-cell">
                                            <div class="badge {{ $badgeClass }} font-bold">
                                                {{ $upload->total_count }}/14
                                            </div>
                                        </td>
                                        <td class="p-2 text-center md:p-3">
                                            @if ($upload->total_count == 14)
                                                <span class="gap-1 badge badge-xs md:badge-sm badge-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3 h-3 md:w-4 md:h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Completed</span>
                                                </span>
                                            @elseif($upload->total_count < 14)
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
                                                <a href="{{ url('admin/admin-check-status/' . $upload->user->id . '/' . $upload->month) }}"
                                                    class="text-blue-500 btn btn-ghost btn-xs" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
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
    @endpush
</x-app-layout>
