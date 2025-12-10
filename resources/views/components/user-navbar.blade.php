 <!-- Top Navbar -->
 <nav class="sticky top-0 z-20 flex items-center justify-between px-4 py-3 bg-white border-b border-slate-100" id="sidebarToggle">
    <div class="flex items-center">
        <button class="p-2 transition-all rounded-lg text-slate-600 hover:bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="ml-4">
            <h1 class="text-xl font-bold text-slate-900">Rekapitulasi Bulanan</h1>
        </div>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <button class="flex items-center p-1 space-x-2 transition-all rounded-lg hover:bg-slate-100">
                <div
                    class="flex items-center justify-center w-8 h-8 font-semibold text-white bg-blue-500 rounded-full">
                    {{ auth()->user()->nama_lengkap ? substr(auth()->user()->nama_lengkap, 0, 1) : 'U' }}
                </div>
                <span
                    class="hidden text-sm font-medium md:block text-slate-700">{{ auth()->user()->nama_lengkap }}</span>
            </button>
        </div>
    </div>
</nav>