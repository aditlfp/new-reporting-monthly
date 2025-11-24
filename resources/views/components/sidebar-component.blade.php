<!-- Sidebar -->
  <aside class="flex flex-col w-64 bg-white border-r border-slate-200">

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1">
      <a href="#" class="flex items-center px-4 py-3 space-x-3 text-white transition-all rounded-lg bg-slate-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="font-medium">Dashboard</span>
      </a>
      
      <a href="#" id="openUserModal" class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span class="font-medium">Users</span>
      </a>

      <a href="{{ route('admin-covers.index') }}" id="openUserModal" class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 2C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V3C4 2.44772 4.44772 2 5 2H19ZM18 4H6V20H18V4ZM12 7C14.7614 7 17 9.23858 17 12C17 13.7973 16.0517 15.3731 14.6282 16.2544L12.5 12H15C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15L12.9552 16.9089C12.646 16.9687 12.3267 17 12 17C9.23858 17 7 14.7614 7 12C7 9.23858 9.23858 7 12 7Z"></path></svg>
        <span class="font-medium">Covers</span>
      </a>

      <a href="{{ route('admin-latters.index') }}" id="openUserModal" class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 4H7C5.89543 4 5 4.89543 5 6C5 7.10457 5.89543 8 7 8H21V21C21 21.5523 20.5523 22 20 22H7C4.79086 22 3 20.2091 3 18V6C3 3.79086 4.79086 2 7 2H20C20.5523 2 21 2.44772 21 3V4ZM5 18C5 19.1046 5.89543 20 7 20H19V10H7C6.27143 10 5.58835 9.80521 5 9.46487V18ZM20 7H7C6.44772 7 6 6.55228 6 6C6 5.44772 6.44772 5 7 5H20V7Z"></path></svg>
        <span class="font-medium">Letters</span>
      </a>

      <a href="#" class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span class="font-medium">Analytics</span>
      </a>

      <a href="#" class="flex items-center px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span class="font-medium">Settings</span>
      </a>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-slate-200">
      <form action="{{ route('logout') }}" method="POST" class="w-full">
        @csrf
        <button type="submit" class="flex items-center w-full px-4 py-3 space-x-3 transition-all rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          <span class="font-medium">Logout</span>
        </button>
      </form>
    </div>

    <!-- User Profile -->
    <div class="p-4 border-t border-slate-200">
      <div class="flex items-center px-4 py-3 space-x-3">
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-200">
          <span class="font-semibold text-slate-700">AD</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium truncate text-slate-900">Admin User</p>
          <p class="text-xs truncate text-slate-500">admin@example.com</p>
        </div>
      </div>
    </div>
  </aside>