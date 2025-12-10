<!-- Sidebar -->
<aside id="sidebar"
    class="fixed z-10 w-64 h-screen transition-transform duration-300 ease-in-out transform -translate-x-full bg-white border-r border-slate-100 md:translate-x-0 md:static text-sm">
    <div class="p-6 border-b border-slate-100">
        <div class="flex items-center space-x-3">
            <div
                class="flex items-center justify-center w-12 h-12 font-semibold text-white bg-blue-500 rounded-full">
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