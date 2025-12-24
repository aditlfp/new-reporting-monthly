<x-app-layout>
	@push('styles')
	    .highlight {
	        background-color: #fde68a; /* amber-200 */
	        padding: 0 2px;
	        border-radius: 2px;
	    }
	@endpush
    <div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />
        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />
            <!-- Main Content -->
            <main class="flex-1 p-1 m-2 overflow-y-auto xs:p-2 sm:p-4 md:p-6">
                <div class="w-full max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6 md:mb-8">
                        <h2 class="mb-1 text-xl font-bold sm:text-2xl text-slate-900">Data Verifikasi Leader / Danru</h2>
                        <p class="text-sm sm:text-base text-slate-500">Data Terbaru Sesuai Bulan Saat Ini</p>
                    </div>

                    <div class="my-6 bg-gray-50 shadow-lg card">
                		<div class="card-body">
                			<span class="text-lg sm:text-xl font-semibold">Filter</span>
                			<form id="filterForm" class="flex gap-2 items-end w-full">

								<div class="form-control flex flex-col w-full">
                					<label for="client" class="required">Mitra</label>
								    <select name="client" id="client" class="select select-sm select-bordered rounded-sm">
								        <option value="">Pilih Mitra</option>

								       @forelse($clients as $client)
								       	<option value="{{ $client->id }}">{{ $client->name }}</option>
								       @empty
								       	<option value="">Mitra Kosong</option>
								       @endforelse
								    </select>
								</div>

                				<div class="form-control flex flex-col w-full">
                					<label for="month" class="required">Bulan</label>
								    <select name="month" id="monthSelect" class="select select-sm select-bordered rounded-sm">
								        <option value="">Pilih Bulan</option>

								        @foreach (range(1, 12) as $month)
								            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
								                {{ \Carbon\Carbon::create(null, $month, 1)
								                    ->locale('id')
								                    ->translatedFormat('F') }}
								            </option>
								        @endforeach
								    </select>
								</div>

								<div class="form-control flex flex-col w-full">
                					<label for="year" class="required">Tahun</label>
								    <select name="year" id="yearSelect" class="select select-sm select-bordered rounded-sm">
									    @php
									        $currentYear = now()->year;
									    @endphp

									    @foreach (range($currentYear - 5, $currentYear + 5) as $year)
									        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
									            {{ $year }}
									        </option>
									    @endforeach
									</select>
								</div>

							    <button type="submit"
							        class="btn btn-sm rounded-sm border-0 bg-blue-500/20 text-blue-500 hover:bg-blue-500 hover:text-white">
							        Filter
							    </button>
							</form>
                		</div>
                	</div>

                    <div class="my-6 bg-gray-50 shadow-lg card">
                		<div class="card-body">
							<div class="flex flex-col gap-y-2 mb-3">
							    <span class="text-sm font-semibold text-gray-700">Search</span>
							    <input
							        type="text"
							        id="searchInput"
							        placeholder="Cari nama user / mitra..."
							        class="input input-sm input-bordered w-full max-w-xs rounded-sm"
							    />
							</div>
                			<div class="overflow-x-auto">
                        		<table class="table w-full text-xs table-zebra md:text-sm">
	                            	<thead>
		                                <tr class="text-gray-800 bg-gray-200">
		                                    <th class="p-2 text-center md:p-3">#</th>
		                                    <th class="p-2 md:p-3">Nama</th>
		                                    <th class="p-2 md:p-3 sm:table-cell">Jabatan</th>
		                                    <th class="p-2 md:p-3 md:table-cell">Client</th>
		                                    <th class="p-2 md:p-3 lg:table-cell">Bulan Upload</th>
		                                    <th class="p-2 md:p-3 lg:table-cell">Hari Ini Upload?</th>
		                                    <th class="p-2 text-center md:p-3 sm:table-cell">Verifikasi Bulanan</th>
		                                    <th class="p-2 text-center md:p-3">Yang Di ACC</th>
		                                </tr>
	                            	</thead>
	                            	<tbody id="userTableBody">
	                            		 <!-- Loading State -->
	                            		 <td id="loadingState" colspan="10">
					                        <div class="flex flex-col items-center justify-center py-12">
					                            <div class="w-12 h-12 border-4 border-blue-200 rounded-full border-t-blue-600 animate-spin"></div>
					                            <p class="mt-4 text-sm text-gray-600">Loading data...</p>
					                        </div>
					                    </td>
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
    	var today = new Date();
    	let allUsers = [];

    	$('#filterForm').on('submit', function (e) {
		    e.preventDefault();

		    const month = $('#monthSelect').val();
		    const year = $('#yearSelect').val();
		    const client = $('#client').val();

		    renderLoadData(client, month, year);
		});

		const debouncedSearch = debounce(function () {
		    const keyword = $('#searchInput').val().toLowerCase();

		    const filtered = allUsers.filter(user => {
		        const namaUser = user.nama_lengkap?.toLowerCase() || '';
		        const namaClient = user.kerjasama?.client?.name?.toLowerCase() || '';

		        return namaUser.includes(keyword) || namaClient.includes(keyword);
		    });

		    renderTable(filtered, null, null, keyword);
		}, 300);

		$('#searchInput').on('input', debouncedSearch);



    	function thisMonth() {
			var month = String(today.getMonth() + 1).padStart(2, '0'); // months are 0-based
			return month;
    	}

    	function thisYear() {
    		var today = new Date();
			var year  = today.getFullYear();
			return year;
    	}

    	function tdLoading() {
		    return `
		        <div class="flex justify-center">
		            <span class="w-4 h-4 border-2 border-blue-200 rounded-full border-t-blue-600 animate-spin"></span>
		        </div>
		    `;
		}

    	function renderLoadData(client = null, month = null, year = null) {
			const tbody = $('#userTableBody');

		    $.ajax({
		        url: '{{ route('api.v1.count.data.upload.spv') }}',
		        method: 'GET',
		        data: {
		        	client: client,
		            month: month,
		            year: year
		        },
		        success: function (data) {
		            tbody.empty();

		            const countTodayMap = {};

		            $.each(data?.data?.count_today, function (_, item) {
		                countTodayMap[item.user_id] = item.total_upload;
		                countTodayMap["date"] = item.date;
		            });
		            allUsers = data?.data?.users || [];
		            if (!allUsers.length) {
				        tbody.append(`
				            <tr>
				                <td colspan="10" class="text-center py-6 text-gray-500">
				                    Data tidak ditemukan
				                </td>
				            </tr>
				        `);
				        return;
				    }

		            $.each(allUsers, function (index, user) {
		                const uploadToday = countTodayMap[user.id] ?? 0;

		                tbody.append(`
		                    <tr>
		                        <td>${index + 1}</td>
		                        <td>${user.nama_lengkap}</td>
		                        <td>${user.jabatan.name_jabatan}</td>
		                        <td>${user.kerjasama.client.name}</td>
		                        <td class="monthly">${getMonthNameIndo(month ?? thisMonth())}</td>
		                        <td class="text-center">
		                            ${
		                                uploadToday > 0
		                                    ? `<span class="text-green-600">
		                                           <i class="ri-checkbox-circle-line text-lg sm:text-2xl"></i>
		                                       </span>`
		                                    : `<span class="text-red-600">
		                                           <i class="ri-close-circle-line text-lg sm:text-2xl"></i>
		                                       </span>`
		                            }
		                        </td>
		                        <td class="monthlyCountTd text-center">
						            ${tdLoading()}
						        </td>
		                        <td>
		                            <a href=${`/count-per-user/${user.id}/${month ?? thisMonth()}/${year ?? thisYear()}`} class="btn btn-sm rounded-sm btn-detail bg-blue-500/20 border-0 text-blue-500 hover:bg-blue-500 hover:text-white"><i class="ri-eye-line text-lg"></i></a>
		                        </td>
		                    </tr>
		                `);
		                const lastRow = tbody.find('tr').last();
						setTimeout(() => {
						    lastRow.find('.monthlyCountTd')
						        .html(`${user.total_per_month}/11`);
						}, 300);
		            });
		        }
		    });
		}

		function renderTable(users, month, year, keyword = '') {
		    const tbody = $('#userTableBody');
		    tbody.empty();

		    if (!users.length) {
		        tbody.append(`
		            <tr>
		                <td colspan="10" class="text-center py-6 text-gray-500">
		                    Data tidak ditemukan
		                </td>
		            </tr>
		        `);
		        return;
		    }

		    users.forEach((user, index) => {
		        const uploadToday = 0; // tetap dari logic kamu sebelumnya
		        const namaUser = highlightText(user.nama_lengkap, keyword);
        		const namaClient = highlightText(user.kerjasama.client.name, keyword);
		        tbody.append(`
		            <tr>
		                <td>${index + 1}</td>
		                <td>${namaUser}</td>
		                <td>${user.jabatan.name_jabatan}</td>
		                <td>${namaClient}</td>
		                <td>${getMonthNameIndo(month ?? thisMonth())}</td>
		                <td class="text-center">
		                    ${user.upload_today > 0
		                        ? `<span class="text-green-600"><i class="ri-checkbox-circle-line text-xl"></i></span>`
		                        : `<span class="text-red-600"><i class="ri-close-circle-line text-xl"></i></span>`
		                    }
		                </td>
		                <td class="text-center">${user.total_per_month}/11</td>
		                <td>
		                    <a href="/count-per-user/${user.id}/${month ?? thisMonth()}/${year ?? thisYear()}"
		                       class="btn btn-sm rounded-sm bg-blue-500/20 border-0 text-blue-500 hover:bg-blue-500 hover:text-white">
		                        <i class="ri-eye-line text-lg"></i>
		                    </a>
		                </td>
		            </tr>
		        `);
		    });
		}

		renderLoadData()

		function getMonthNameIndo(dateString) {
		    const date = new Date(dateString);
		    return date.toLocaleDateString('id-ID', { month: 'long' });
		}

		function debounce(fn, delay = 300) {
		    let timeout;
		    return function (...args) {
		        clearTimeout(timeout);
		        timeout = setTimeout(() => fn.apply(this, args), delay);
		    };
		}

		function highlightText(text, keyword) {
		    if (!keyword) return text;

		    const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
		    const regex = new RegExp(`(${escaped})`, 'gi');

		    return text.replace(regex, '<span class="highlight">$1</span>');
		}

	</script>
    @endpush
</x-app-layout>
