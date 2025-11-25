@props(['title' => 'Dashboard Overview', 
        'subtitle' => "Welcome back! Here's what's happening today."
      ])
<nav class="flex w-full fixed z-20">
 <!-- Logo/Brand -->
    <div class="p-6 pr-7 border-b border-slate-200 bg-white">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gradient-to-br from-slate-700 to-slate-900 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <div class="flex w-full flex-col">
            <span class="text-xl font-semibold text-slate-800">SILAB</span>
            <span class="text-sm w-full mr-12">Sistem Laporan Bulanan</span>
        </div>
      </div>
    </div>   
  <!-- Header -->
    <header class="bg-white border-b border-l border-slate-200 sticky top-0 z-10 w-full">
      <div class="px-8 py-5 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">{{ $title }}</h1>
          <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
        </div>
        <div class="flex items-center space-x-4">
        <!-- Logout Button -->
        <div class="p-4">
          <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="relative p-2 text-red-500 hover:text-red-400 transition-colors bg-red-500/20 rounded-full">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
            </button>
          </form>
        </div>
        </div>
      </div>
    </header>


</nav>