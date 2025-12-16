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
            
            @if (stripos(auth()->user()->jabatan->type_jabatan, 'leader') !== false || stripos(auth()->user()->jabatan->name_jabatan, 'leader') !== false || stripos(auth()->user()->jabatan->type_jabatan, 'MANAJEMEN') !== false || stripos(auth()->user()->jabatan->code_jabatan, 'CO-CS') !== false)

                <a href="{{ route('fixed.index')}}"
                    class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg {{ request()->routeIs('fixed.*') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-100'}}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13.0607 8.11097L14.4749 9.52518C17.2086 12.2589 17.2086 16.691 14.4749 19.4247L14.1214 19.7782C11.3877 22.5119 6.95555 22.5119 4.22188 19.7782C1.48821 17.0446 1.48821 12.6124 4.22188 9.87874L5.6361 11.293C3.68348 13.2456 3.68348 16.4114 5.6361 18.364C7.58872 20.3166 10.7545 20.3166 12.7072 18.364L13.0607 18.0105C15.0133 16.0578 15.0133 12.892 13.0607 10.9394L11.6465 9.52518L13.0607 8.11097ZM19.7782 14.1214L18.364 12.7072C20.3166 10.7545 20.3166 7.58872 18.364 5.6361C16.4114 3.68348 13.2456 3.68348 11.293 5.6361L10.9394 5.98965C8.98678 7.94227 8.98678 11.1081 10.9394 13.0607L12.3536 14.4749L10.9394 15.8891L9.52518 14.4749C6.79151 11.7413 6.79151 7.30911 9.52518 4.57544L9.87874 4.22188C12.6124 1.48821 17.0446 1.48821 19.7782 4.22188C22.5119 6.95555 22.5119 11.3877 19.7782 14.1214Z"></path></svg>
                    <span class="font-medium">Pilih Gambar Laporan</span>
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