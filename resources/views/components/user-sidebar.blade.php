<!-- Sidebar -->
<aside id="sidebar"
style="transform: translateX(-100%)"
    class="fixed z-50 w-64 h-screen text-sm transition-transform duration-300 ease-in-out transform bg-white border-r border-slate-100 md:translate-x-0 md:static">
    <div class="p-6 border-b border-slate-100">
        <div class="flex items-center space-x-3">
            <div
                class="flex items-center justify-center w-12 overflow-hidden font-semibold text-white bg-blue-500 rounded-full aspect-square">
                {{ auth()->user()->nama_lengkap ? substr(auth()->user()->nama_lengkap, 0, 1) : 'U' }}
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
    <nav class="flex-1 p-4 space-y-6">
        <!-- Master Data Section -->
        <a href="{{ route('dashboard')}}"
            class="flex items-center px-4 py-3 space-x-3  transition-all rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>
        <div class="space-y-1">
            <div class="px-4 mb-2 text-xs font-semibold tracking-wider uppercase text-slate-400">
                Master Data
            </div>
            
            <a href="{{ route('send.img.laporan')}}"
                class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('send.img.laporan') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-medium">Kirim Foto Laporan</span>
            </a>
            
            @if (auth()->user()->isAccess())

                <a href="{{ route('fixed.index')}}"
                    class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('fixed.*') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13.0607 8.11097L14.4749 9.52518C17.2086 12.2589 17.2086 16.691 14.4749 19.4247L14.1214 19.7782C11.3877 22.5119 6.95555 22.5119 4.22188 19.7782C1.48821 17.0446 1.48821 12.6124 4.22188 9.87874L5.6361 11.293C3.68348 13.2456 3.68348 16.4114 5.6361 18.364C7.58872 20.3166 10.7545 20.3166 12.7072 18.364L13.0607 18.0105C15.0133 16.0578 15.0133 12.892 13.0607 10.9394L11.6465 9.52518L13.0607 8.11097ZM19.7782 14.1214L18.364 12.7072C20.3166 10.7545 20.3166 7.58872 18.364 5.6361C16.4114 3.68348 13.2456 3.68348 11.293 5.6361L10.9394 5.98965C8.98678 7.94227 8.98678 11.1081 10.9394 13.0607L12.3536 14.4749L10.9394 15.8891L9.52518 14.4749C6.79151 11.7413 6.79151 7.30911 9.52518 4.57544L9.87874 4.22188C12.6124 1.48821 17.0446 1.48821 19.7782 4.22188C22.5119 6.95555 22.5119 11.3877 19.7782 14.1214Z"></path></svg>
                    <span class="font-medium">Pilih Gambar Laporan</span>
                </a>
            @endif
            @if(auth()->user()->canAccess())
            <a href="{{ route('counting.data.upload.spv')}}"
                    class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('counting.data.upload.spv') || request()->routeIs('count.per.user.show') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10.0072 2.10365C8.60556 1.64993 7.08193 2.28104 6.41168 3.59294L5.6059 5.17011C5.51016 5.35751 5.35775 5.50992 5.17036 5.60566L3.59318 6.41144C2.28128 7.08169 1.65018 8.60532 2.10389 10.0069L2.64935 11.6919C2.71416 11.8921 2.71416 12.1077 2.64935 12.3079L2.10389 13.9929C1.65018 15.3945 2.28129 16.9181 3.59318 17.5883L5.17036 18.3941C5.35775 18.4899 5.51016 18.6423 5.6059 18.8297L6.41169 20.4068C7.08194 21.7187 8.60556 22.3498 10.0072 21.8961L11.6922 21.3507C11.8924 21.2859 12.1079 21.2859 12.3081 21.3507L13.9931 21.8961C15.3947 22.3498 16.9183 21.7187 17.5886 20.4068L18.3944 18.8297C18.4901 18.6423 18.6425 18.4899 18.8299 18.3941L20.4071 17.5883C21.719 16.9181 22.3501 15.3945 21.8964 13.9929L21.3509 12.3079C21.2861 12.1077 21.2861 11.8921 21.3509 11.6919L21.8964 10.0069C22.3501 8.60531 21.719 7.08169 20.4071 6.41144L18.8299 5.60566C18.6425 5.50992 18.4901 5.3575 18.3944 5.17011L17.5886 3.59294C16.9183 2.28104 15.3947 1.64993 13.9931 2.10365L12.3081 2.6491C12.1079 2.71391 11.8924 2.71391 11.6922 2.6491L10.0072 2.10365ZM8.19271 4.50286C8.41612 4.06556 8.924 3.8552 9.39119 4.00643L11.0762 4.55189C11.6768 4.74632 12.3235 4.74632 12.9241 4.55189L14.6091 4.00643C15.0763 3.8552 15.5841 4.06556 15.8076 4.50286L16.6133 6.08004C16.9006 6.64222 17.3578 7.09946 17.92 7.38668L19.4972 8.19246C19.9345 8.41588 20.1448 8.92375 19.9936 9.39095L19.4481 11.076C19.2537 11.6766 19.2537 12.3232 19.4481 12.9238L19.9936 14.6088C20.1448 15.076 19.9345 15.5839 19.4972 15.8073L17.92 16.6131C17.3578 16.9003 16.9006 17.3576 16.6133 17.9197L15.8076 19.4969C15.5841 19.9342 15.0763 20.1446 14.6091 19.9933L12.9241 19.4479C12.3235 19.2535 11.6768 19.2535 11.0762 19.4479L9.3912 19.9933C8.924 20.1446 8.41612 19.9342 8.19271 19.4969L7.38692 17.9197C7.09971 17.3576 6.64246 16.9003 6.08028 16.6131L4.50311 15.8073C4.06581 15.5839 3.85544 15.076 4.00668 14.6088L4.55213 12.9238C4.74656 12.3232 4.74656 11.6766 4.55213 11.076L4.00668 9.39095C3.85544 8.92375 4.06581 8.41588 4.50311 8.19246L6.08028 7.38668C6.64246 7.09946 7.09971 6.64222 7.38692 6.08004L8.19271 4.50286ZM6.75972 11.7573L11.0023 15.9999L18.0734 8.92885L16.6592 7.51464L11.0023 13.1715L8.17394 10.343L6.75972 11.7573Z"></path></svg>
                    <span class="font-medium">Check Verif Gambar</span>
                </a>
            @endif
        </div>

        <!-- Tools Section -->
        <div class="space-y-1">
            <div class="px-4 mb-2 text-xs font-semibold tracking-wider uppercase text-slate-400">
                Tools
            </div>
            <a href="{{ route('check.calender.upload')}}"
                class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('check.calender.upload') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-medium">Kalender</span>
            </a>

           {{--  <a href="{{ route('user.settings.index')}}"
                class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('user.settings.index') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-medium">Pengaturan</span>
            </a> --}}

        </div>
    </nav>

    <div class="p-4 border-t border-slate-100">
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit"
            class="flex items-center w-full px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-red-50">
            <svg class="w-5 h-5 text-slate-400" stroke="red" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="font-medium text-red-600">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Overlay for mobile sidebar -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 hidden transition-opacity duration-300 opacity-0 bg-black/50 md:hidden"></div>