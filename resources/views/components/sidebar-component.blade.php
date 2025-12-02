<!-- Sidebar -->
<aside class="flex flex-col w-64 min-h-screen bg-white border-r min-w-64 border-slate-200">

  <!-- Navigation -->
  <div class="flex-1 p-4 overflow-y-auto">
    <a href="{{ route('dashboard')}}" class="flex items-center my-2 px-3 py-2 space-x-2 transition-all rounded-lg {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      <span class="text-sm">Dashboard</span>
    </a>
    {{-- Master Data Dropdown --}}
    <div class="mb-2">
      <button 
        onclick="toggleDropdown('masterDataDropdown')" 
        class="flex items-center justify-between w-full px-3 py-2 text-left transition-all duration-200 rounded-lg hover:bg-slate-100"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-base text-slate-700" viewBox="0 0 24 24" fill="currentColor"><path d="M5 12.5C5 12.8134 5.46101 13.3584 6.53047 13.8931C7.91405 14.5849 9.87677 15 12 15C14.1232 15 16.0859 14.5849 17.4695 13.8931C18.539 13.3584 19 12.8134 19 12.5V10.3287C17.35 11.3482 14.8273 12 12 12C9.17273 12 6.64996 11.3482 5 10.3287V12.5ZM19 15.3287C17.35 16.3482 14.8273 17 12 17C9.17273 17 6.64996 16.3482 5 15.3287V17.5C5 17.8134 5.46101 18.3584 6.53047 18.8931C7.91405 19.5849 9.87677 20 12 20C14.1232 20 16.0859 19.5849 17.4695 18.8931C18.539 18.3584 19 17.8134 19 17.5V15.3287ZM3 17.5V7.5C3 5.01472 7.02944 3 12 3C16.9706 3 21 5.01472 21 7.5V17.5C21 19.9853 16.9706 22 12 22C7.02944 22 3 19.9853 3 17.5ZM12 10C14.1232 10 16.0859 9.58492 17.4695 8.89313C18.539 8.3584 19 7.81342 19 7.5C19 7.18658 18.539 6.6416 17.4695 6.10687C16.0859 5.41508 14.1232 5 12 5C9.87677 5 7.91405 5.41508 6.53047 6.10687C5.46101 6.6416 5 7.18658 5 7.5C5 7.81342 5.46101 8.3584 6.53047 8.89313C7.91405 9.58492 9.87677 10 12 10Z"></path></svg>
          <span class="text-sm font-medium text-slate-700">Master Data</span>
        </div>
        <i id="masterDataIcon" class="text-base transition-transform duration-300 rotate-180 ri-arrow-down-s-line text-slate-700"></i>
      </button>
      
      {{-- Dropdown Menu --}}
      <div id="masterDataDropdown" class="overflow-hidden transition-all duration-300 max-h-96" style="max-height: 500px;">
        <div class="mt-1 ml-3 border-l-2 border-slate-200">
          <a href="{{ route('check.upload')}}" class="flex items-center m-2 px-3 py-2 space-x-2 transition-all rounded-lg {{ request()->routeIs('check.upload*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 3V1H9V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V9H20V5H17V7H15V5H9V7H7V5H4V19H10V21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7ZM17 12C14.7909 12 13 13.7909 13 16C13 18.2091 14.7909 20 17 20C19.2091 20 21 18.2091 21 16C21 13.7909 19.2091 12 17 12ZM11 16C11 12.6863 13.6863 10 17 10C20.3137 10 23 12.6863 23 16C23 19.3137 20.3137 22 17 22C13.6863 22 11 19.3137 11 16ZM16 13V16.4142L18.2929 18.7071L19.7071 17.2929L18 15.5858V13H16Z"></path></svg>
            <span class="text-sm">Check Status Upload</span>
          </a>

          <a href="{{ route('admin-covers.index') }}" class="flex items-center m-2 px-3 py-2 space-x-2 rounded-lg transition-all {{ request()->routeIs('admin-covers.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 2C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V3C4 2.44772 4.44772 2 5 2H19ZM18 4H6V20H18V4ZM12 7C14.7614 7 17 9.23858 17 12C17 13.7973 16.0517 15.3731 14.6282 16.2544L12.5 12H15C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15L12.9552 16.9089C12.646 16.9687 12.3267 17 12 17C9.23858 17 7 14.7614 7 12C7 9.23858 9.23858 7 12 7Z"></path></svg>
            <span class="text-sm">Covers</span>
          </a>

          <a href="{{ route('admin-latters.index') }}" class="flex items-center m-2 px-3 py-2 space-x-2 rounded-lg transition-all {{ request()->routeIs('admin-latters.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 4H7C5.89543 4 5 4.89543 5 6C5 7.10457 5.89543 8 7 8H21V21C21 21.5523 20.5523 22 20 22H7C4.79086 22 3 20.2091 3 18V6C3 3.79086 4.79086 2 7 2H20C20.5523 2 21 2.44772 21 3V4ZM5 18C5 19.1046 5.89543 20 7 20H19V10H7C6.27143 10 5.58835 9.80521 5 9.46487V18ZM20 7H7C6.44772 7 6 6.55228 6 6C6 5.44772 6.44772 5 7 5H20V7Z"></path></svg>
            <span class="text-sm">Letters</span>
          </a>
        </div>
      </div>
    </div>
    
    {{-- Other Menu Items --}}   
    <a href="{{ route('admin.settings')}}" class="flex items-center px-3 py-2 space-x-2 rounded-lg transition-all {{ request()->routeIs('admin.settings') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <span class="text-sm">Settings</span>
    </a>
  </div>

  <!-- User Profile - Fixed at Bottom -->
  <div class="p-3 bg-white border-t border-slate-200">
    <div class="flex items-center px-3 py-2 space-x-2 transition-all duration-200 rounded-lg cursor-pointer hover:bg-slate-50">
      <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600">
        <span class="text-xs font-semibold text-white">{{strtoupper(substr(auth()->user()->name, 0, 1) . substr(auth()->user()->name, -1))}}</span>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs font-medium truncate text-slate-900">{{ auth()->user()->name}}</p>
        <p class="text-xs truncate text-slate-500">{{ auth()->user()->email}}</p>
      </div>
      <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </div>
  </div>
</aside>

@push('scripts')
<script>
  function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const icon = document.getElementById('masterDataIcon');
    
    if (dropdown.style.maxHeight && dropdown.style.maxHeight !== '0px') {
      // Close dropdown
      dropdown.style.maxHeight = '0px';
      icon.classList.remove('rotate-180');
    } else {
      // Open dropdown
      dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
      icon.classList.add('rotate-180');
    }
  }
</script>
@endpush