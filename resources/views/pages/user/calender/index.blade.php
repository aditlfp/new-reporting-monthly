<x-app-layout>
	@push('styles')
		.calendar-day {
            aspect-ratio: 1;
            transition: all 0.2s;
            min-height: 0;
        }
        .calendar-day:hover {
            transform: translateY(-2px);
        }
        .today {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        }
        .other-month {
            opacity: 0.3;
        }
        .sunday-text {
            color: #dc2626 !important;
        }
        .friday-text {
            color: #16a34a !important;
        }
        .holiday {
            color: #dc2626 !important;
            font-weight: 600;
        }
        .holiday-badge {
            font-size: 0.5rem;
            margin-top: 1px;
            line-height: 1.2;
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .calendar-day {
                font-size: 0.625rem;
                padding: 0.125rem !important;
            }
            .day-header {
                font-size: 0.625rem !important;
                padding: 0.25rem 0 !important;
            }
            .indicator-item i {
                font-size: 0.75rem !important;
            }
            .indicator-item.badge {
                font-size: 0.5rem !important;
                padding: 0.125rem 0.25rem !important;
            }
        }
        
        @media (min-width: 481px) and (max-width: 640px) {
            .calendar-day {
                font-size: 0.75rem;
            }
            .holiday-badge {
                font-size: 0.55rem;
            }
        }
        
        @media (min-width: 641px) {
            .calendar-day {
                font-size: 0.875rem;
            }
            .holiday-badge {
                font-size: 0.6rem;
            }
        }
        
        .calendar-day-wrapper {
            width: 100%;
            height: 100%;
        }
        
        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #2563eb;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Ensure grid doesn't overflow */
        #calendarDays {
            min-width: 0;
        }
        
        .indicator {
            min-width: 0;
            max-width: 100%;
        }
	@endpush

	<div class="flex flex-col h-screen bg-white">
        <!-- Top Navbar -->
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            {{-- sidebar --}}
            <x-user-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-1 overflow-y-auto xs:p-2 sm:p-4 md:p-6">
            	<div class="w-full max-w-6xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-3 sm:mb-4 md:mb-6 px-1">
                        <h2 class="mb-1 text-base text-sm sm:text-xl md:text-2xl font-bold text-slate-900">Calender Bulanan</h2>
                        <p class="text-[10px] sm:text-sm md:text-base text-slate-500">Cek Kapan Anda Upload Gambar Dan Siapa Aja Yang Upload</p>
                        <span class="badge bg-green-500 text-xs text-white">Berapa kali Upload</span>
                        <span class="badge bg-blue-500 text-xs text-white">Sudah Upload Tanggal Itu</span>

                    </div>

		        <!-- Loading Indicator -->
		        <div id="loadingIndicator" class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50">
		            <div class="bg-white rounded-lg p-6 shadow-xl flex flex-col items-center">
		                <div class="loading-spinner"></div>
		                <p class="mt-4 text-gray-600">Loading calendar data...</p>
		            </div>
		        </div>

		        <!-- Calendar Card -->
		        <div class="card bg-white shadow-xl w-full mx-auto">
		            <div class="card-body p-2 xs:p-3 sm:p-4 md:p-6">
		                <!-- Calendar Header -->
		                <div class="flex items-center justify-between mb-2 sm:mb-4 md:mb-6">
		                    <button id="prevMonth" class="btn btn-xs sm:btn-sm md:btn-md btn-circle btn-ghost hover:bg-blue-100 hover:text-blue-600">
		                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
		                        </svg>
		                    </button>
		                    
		                    <div class="text-center">
		                        <h2 id="currentMonth" class="text-sm sm:text-lg md:text-xl lg:text-2xl font-bold text-blue-600"></h2>
		                        <p id="currentYear" class="text-xs sm:text-sm md:text-base text-gray-500"></p>
		                    </div>
		                    
		                    <button id="nextMonth" class="btn btn-xs sm:btn-sm md:btn-md btn-circle btn-ghost hover:bg-blue-100 hover:text-blue-600">
		                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
		                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
		                        </svg>
		                    </button>
		                </div>

		                <!-- Today Button -->
		                <div class="text-center mb-2 sm:mb-3 md:mb-4">
		                    <button id="todayBtn" class="btn btn-xs sm:btn-sm bg-blue-500 hover:bg-blue-600 text-white border-none px-3">
		                        Hari Ini
		                    </button>
		                </div>

		                <!-- Calendar Grid -->
		                <div class="w-full overflow-hidden">
		                    <div class="grid grid-cols-7 gap-0.5 xs:gap-1 sm:gap-2">
		                        <!-- Day Headers -->
		                        <div class="day-header text-center font-semibold text-red-600 py-1 text-xs sm:text-sm">Ming</div>
		                        <div class="day-header text-center font-semibold text-blue-600 py-1 text-xs sm:text-sm">Sen</div>
		                        <div class="day-header text-center font-semibold text-blue-600 py-1 text-xs sm:text-sm">Sel</div>
		                        <div class="day-header text-center font-semibold text-blue-600 py-1 text-xs sm:text-sm">Rab</div>
		                        <div class="day-header text-center font-semibold text-blue-600 py-1 text-xs sm:text-sm">Kam</div>
		                        <div class="day-header text-center font-semibold text-green-600 py-1 text-xs sm:text-sm">Jum</div>
		                        <div class="day-header text-center font-semibold text-blue-600 py-1 text-xs sm:text-sm">Sab</div>
		                        
		                        <!-- Calendar Days -->
		                        <div id="calendarDays" class="col-span-7 grid grid-cols-7 gap-0.5 xs:gap-1 sm:gap-2">
		                            <!-- Days will be inserted here by JavaScript -->
		                        </div>
		                    </div>
		                </div>

		            </div>
		        </div>
                <dialog id="uploadModal" class="modal">
                    <div class="modal-box max-w-3xl">
                        <h3 class="font-bold text-lg mb-3">Data Upload</h3>

                        <div id="modalContent">
                            <p class="text-center text-gray-500">Loading...</p>
                        </div>

                        <div class="modal-action">
                            <button class="btn" onclick="uploadModal.close()">Tutup</button>
                        </div>
                    </div>
                </dialog>

            </main>
       </div>
    </div>

    @push('scripts')
        <script>
        $(document).ready(function() {
            let currentDate = new Date();
            let selectedDateElement = null;
            let holidaysCache = {};
            let holidaysData = @json($holidays);
            const holidayTranslate = @json($translate);
            const uploadsByDay = @json($uploadsByDay);

			function cleanName(name) {
			    return name.replace(/\s*\([^)]*\)/g, "").trim();
			}

            $.get("/fetch-calender", function (res) {
			    const thisYear = res[new Date().getFullYear()];
			});

            // Setup CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Show/Hide Loading
            function showLoading() {
                $('#loadingIndicator').removeClass('hidden');
            }

            function hideLoading() {
                $('#loadingIndicator').addClass('hidden');
            }

            // Check if mobile
            function isMobile() {
                return window.innerWidth <= 480;
            }

            // Fetch holidays for a specific month/year
            function fetchHolidays(year, month) {
			    return new Promise(resolve => {
			        const all = holidaysData[year] || [];
			        const filtered = all.data.filter(h => new Date(h.date).getMonth() === month && h.is_holiday).map(h => {
				        const clean = cleanName(h.name)
				        const translated = holidayTranslate[clean] ?? clean;
			      		return {
			      			...h,
				        	name: translated
			      		}
				    });
			        resolve(filtered);
			    });
			}

            function renderCalendar(date) {
                const year = date.getFullYear();
                const month = date.getMonth();
                const mobile = isMobile();
                
                showLoading();

                // Fetch holidays dynamically
                fetchHolidays(year, month).then(function(holidays) {
                    // Update header
                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    $('#currentMonth').text(monthNames[month]);
                    $('#currentYear').text(year);

                    // Get first day of month and number of days
                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const daysInPrevMonth = new Date(year, month, 0).getDate();

                    // Clear calendar
                    $('#calendarDays').empty();

                    // Get today's date for comparison
                    const today = new Date();
                    const isCurrentMonth = today.getMonth() === month && today.getFullYear() === year;
                    const todayDate = today.getDate();

                    // Previous month days
                    for (let i = firstDay - 1; i >= 0; i--) {
                        const day = daysInPrevMonth - i;
                        $('#calendarDays').append(
                            `<div class="calendar-day flex items-center justify-center p-0.5 xs:p-1 sm:p-2 rounded-lg cursor-pointer other-month bg-gray-50 text-gray-400">
                                ${day} 
                            </div>`
                        );
                    }

                    // Current month days
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dateObj = new Date(year, month, day);
                        const dayOfWeek = dateObj.getDay();
                        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const isHoliday = holidays.find(h => h.date === dateStr);
                        const isToday = isCurrentMonth && day === todayDate;

                        // Determine text color class
                        let textColorClass = '';
                        if (isHoliday) {
                            textColorClass = 'text-red-500';
                        } else if (dayOfWeek === 0 && !isToday) { // Sunday
                            textColorClass = 'sunday-text';
                        } else if (dayOfWeek === 5 && !isToday) { // Friday
                            textColorClass = 'friday-text';
                        }
                        
                        const classes = isToday 
                            ? 'calendar-day flex flex-col items-center justify-center p-0.5 xs:p-1 sm:p-2 rounded-lg cursor-pointer today text-white font-bold shadow-lg'
                            : `calendar-day flex flex-col items-center justify-center p-0.5 xs:p-1 sm:p-2 rounded-lg cursor-pointer ${textColorClass}`;
                        
                        let dayContent = `<span class="leading-none">${day}</span>`;
                        if (isHoliday && !mobile) {
                            dayContent += `<span class="holiday-badge text-center line-clamp-2 mt-0.5">${isHoliday.name}</span>`;
                        }

                        const verify = uploadsByDay.filter(h => h.date === dateStr);
                        const totalUploads = verify.length ? (mobile ? verify[0].total + "x" : verify[0].total + "x") : '';
                        
                        $('#calendarDays').append(
                            `<div class="tglClick indicator w-full" data-date="${dateStr}"
                                data-client="{{ auth()->user()->kerjasama->client->id }}">
                              <span class="${verify.length >= 1 ? 'indicator-item indicator-bottom indicator-center badge bg-green-500 text-white border-0' : 'hidden'}">${totalUploads}</span>
							  <div class="${classes} calendar-day-wrapper ${verify.length >= 1 ? 'bg-blue-100/80 border border-blue-500' : 'hover:bg-blue-100 bg-gray-50'} grid place-items-center w-full" data-date="${dateStr}" data-holiday="${isHoliday ? isHoliday.name : ''}">
                                	${dayContent}
								</div>
                            </div>`
                        );
                    }

                    // Next month days
                    const totalCells = $('#calendarDays').children().length;
                    const remainingCells = 42 - totalCells; // 6 rows * 7 days
                    for (let day = 1; day <= remainingCells; day++) {
                        $('#calendarDays').append(
                            `<div class="calendar-day flex items-center justify-center p-0.5 xs:p-1 sm:p-2 rounded-lg cursor-pointer other-month bg-gray-50 text-gray-400">
                                ${day}
                            </div>`
                        );
                    }

                    hideLoading();
                }).catch(function(error) {
                    console.error('Error rendering calendar:', error);
                    hideLoading();
                });
            }

            $(document).on('click', '.tglClick', function () {
                const date = $(this).data('date');
                const clientId = $(this).data('client');

                $('#uploadModal')[0].showModal();
                $('#modalContent').html('<p class="text-center">Loading...</p>');

                $.ajax({
                    url: "{{ route('api.v1.calender.show') }}", // sesuaikan route
                    method: 'GET',
                    data: {
                        date: date,
                        client_id: clientId
                    },
                    success: function (res) {
                        if (!res.status) {
                            $('#modalContent').html(
                                '<p class="text-red-500 text-center">' + res.message + '</p>'
                            );
                            return;
                        }

                        if (res.data.length === 0) {
                            $('#modalContent').html(
                                '<p class="text-center text-gray-500">Tidak ada data</p>'
                            );
                            return;
                        }

                        let html = `
                          <div class="p-6 max-h-[70vh] overflow-y-auto">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        `;

                        res.data.forEach(imgData => {
                            html += `
                            <div class="overflow-hidden transition-all duration-300 min-h-[110px] bg-base-100 border rounded-lg shadow-sm cursor-pointer border-base-300 hover:shadow-md card-expandable"
                                 data-card-id="${imgData.id}">
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-base text-base-content line-clamp-2">
                                                ${imgData.note ?? '-'}
                                            </h4>
                                            <p class="text-sm text-base-content/70 mt-1">
                                                ${imgData.created_at_formatted}
                                            </p>
                                            <p class="text-xs text-base-content/60 truncate mt-0.5">
                                                Upload Oleh: ${imgData.user ? imgData.user.nama_lengkap : 'User Hilang'}
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 text-base-content/40 expand-icon"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-4 hidden expanded-content">
                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            ${renderImage(imgData.img_before, 'Before')}
                                            ${renderImage(imgData.img_proccess, 'Process')}
                                            ${renderImage(imgData.img_final, 'Final')}
                                        </div>
                                        ${imgData.note ? `<p class="text-sm text-base-content/80 mt-2">${imgData.note}</p>` : ''}
                                    </div>
                                </div>
                            </div>
                            `;
                        });

                        html += `
                            </div>
                          </div>
                        `;

                        $('#modalContent').html(html);
                    },
                    error: function () {
                        $('#modalContent').html(
                            '<p class="text-red-500 text-center">Terjadi kesalahan</p>'
                        );
                    }
                });
            });


            function renderImage(path, label) {
                const src = path
                    ? `/storage/${path}`
                    : 'https://placehold.co/400x400?text=Kosong';

                return `
                <div class="overflow-hidden rounded-lg aspect-square bg-slate-100">
                    <img src="${src}" alt="${label}"
                         class="object-cover w-full h-full">
                </div>
                `;
            }

            $(document).on('click', '.card-expandable', function () {
                $(this).find('.expanded-content').toggleClass('hidden');
                $(this).find('.expand-icon').toggleClass('rotate-180');
            });





            function fetchDateDetails(dateStr, holidayName) {
                showLoading();

                $.ajax({
                    url: 'https://libur.deno.dev/api',
                    method: 'POST',
                    data: { 
                        date: dateStr,
                        holiday: holidayName || null
                    },
                    success: function(response) {
                        // Display event details
                        console.log("fetchDateDetails",response)
                        let eventHtml = '';
                        
                        if (holidayName) {
                            eventHtml += `<div class="mb-3">
                                <span class="badge badge-error mb-2">Holiday</span>
                                <p class="font-bold text-red-600">${holidayName}</p>
                            </div>`;
                        }
                        
                        if (response.events && response.events.length > 0) {
                            eventHtml += '<div class="space-y-2">';
                            response.events.forEach(function(event) {
                                eventHtml += `
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <p class="font-semibold text-blue-600">${event.title}</p>
                                        <p class="text-sm text-gray-600">${event.description || 'No description'}</p>
                                        ${event.time ? `<p class="text-xs text-gray-500 mt-1">⏰ ${event.time}</p>` : ''}
                                    </div>
                                `;
                            });
                            eventHtml += '</div>';
                        } else if (!holidayName) {
                            eventHtml = '<p class="text-gray-500 italic">No events scheduled for this date.</p>';
                        }
                        
                        $('#eventContent').html(eventHtml);
                        $('#eventDetails').removeClass('hidden');
                        
                        hideLoading();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching date details:', error);
                        $('#eventContent').html('<p class="text-red-500">Failed to load event details.</p>');
                        $('#eventDetails').removeClass('hidden');
                        hideLoading();
                    }
                });
            }

            // Navigation buttons
            $('#prevMonth').on('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                selectedDateElement = null;
                $('#eventDetails').addClass('hidden');
                renderCalendar(currentDate);
            });

            $('#nextMonth').on('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                selectedDateElement = null;
                $('#eventDetails').addClass('hidden');
                renderCalendar(currentDate);
            });

            $('#todayBtn').on('click', function() {
                currentDate = new Date();
                selectedDateElement = null;
                $('#eventDetails').addClass('hidden');
                renderCalendar(currentDate);
            });

            // Re-render on window resize
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    renderCalendar(currentDate);
                }, 250);
            });

            // Initial render
            renderCalendar(currentDate);
        });
    </script>
    @endpush

</x-app-layout>