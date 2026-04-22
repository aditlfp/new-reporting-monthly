@props(['title' => 'Dashboard Overview', 'subtitle' => "Welcome back! Here's what's happening today."])
<nav class="fixed top-0 z-40 w-full bg-white/95 border-b border-slate-200 backdrop-blur">
    <header class="admin-topbar flex items-center justify-between gap-3 px-4 py-3 md:pr-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            {{-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600/15 text-blue-700">
                <i class="text-lg ri-shield-flash-line"></i>
            </span> --}}
            <img src="{{ URL::asset('img/logo.png') }}" alt="logo silab" srcset="{{ URL::asset('img/logo.png') }}"
                width="40" height="40">
            <span class="text-3xl font-bold tracking-tight text-slate-800 leading-none">SILAB</span>
        </a>
        <div class="flex items-center gap-3 w-full max-w-3xl">
            <button id="sidebarToggleDesktop" type="button"
                class="hidden p-2 text-slate-500 rounded-lg hover:bg-slate-100">
                <i class="text-lg ri-menu-line"></i>
            </button>
        </div>

        <div class="flex items-center self-center gap-2 shrink-0">
            <button type="button" class="btn btn-ghost btn-sm btn-circle text-slate-500 hover:bg-slate-100 shrink-0">
                <i class="text-lg ri-notification-3-line"></i>
            </button>
            <form action="{{ route('logout') }}" method="POST" class="m-0 shrink-0">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm btn-circle text-slate-500 hover:bg-slate-100 shrink-0"
                    title="Logout">
                    <i class="text-lg ri-logout-box-r-line"></i>
                </button>
            </form>
        </div>
    </header>
</nav>
