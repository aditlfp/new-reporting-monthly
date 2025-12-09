@props(['title' => 'Dashboard Overview', 
        'subtitle' => "Welcome back! Here's what's happening today."
      ])
<nav class="fixed z-40 flex flex-col w-full md:flex-row">
  <!-- Logo/Brand -->
  <div class="w-full p-4 pl-16 bg-white border-b md:p-6 md:pr-16 border-slate-200 md:w-auto hidden md:block">
    <div class="flex items-center space-x-3">
      <div class="flex items-center justify-center w-8 h-8 rounded-lg md:w-10 md:h-10 bg-gradient-to-br from-slate-700 to-slate-900">
        <svg class="w-5 h-5 text-white md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <div class="flex flex-col">
        <span class="text-lg font-semibold md:text-xl text-slate-800">SILAB</span>
        <span class="hidden text-xs md:text-sm text-slate-600 sm:block">Sistem Laporan Bulanan</span>
      </div>
    </div>
  </div>   
  
  <!-- Header -->
  <header class="sticky top-0 z-10 w-full bg-white border-b border-l border-slate-200 pt-16 md:pt-0">
    <div class="flex flex-col items-start justify-between px-4 py-3 md:px-8 md:py-5 sm:flex-row sm:items-center">
      <div class="mb-3 sm:mb-0">
        <h1 class="text-lg font-bold md:text-2xl text-slate-900">{{ $title }}</h1>
        <p class="mt-1 text-xs md:text-sm text-slate-500">{{ $subtitle }}</p>
      </div>
      <div class="flex items-center space-x-4">
        <!-- Logout Button -->
        <div class="hidden p-2 md:p-4 md:block">
          <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="relative p-2 text-red-500 transition-colors rounded-full hover:text-red-400 bg-red-500/20">
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