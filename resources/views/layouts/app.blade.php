<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="silk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="/js/jquery3.7.1-min.js"></script>  

        <script src="/js/Notify.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/exif-js@2.3.0/exif.js"></script>

        {{-- Stack To Push Styles --}}
        <style>
            @stack('styles')
        </style>
        <style>
            @media screen and (min-width: 768px) {
                #sidebar {
                    transform: translateX(0) !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <div id="notifications"></div>
            @props(['title' => 'Default Title', 'subtitle' => 'Default Subtitle'])
            @if(Auth::user()->role_id == 2)
                <x-navigation 
                    :title="$title ?? 'Dashboard Overview'"
                    :subtitle="$subtitle ?? 'Welcome back!'"
                />
            @endif
            <!-- Page Content -->
            <main class="{{ Auth::user()->role_id == 2 ? 'pt-24' : 'pt-0' }}">
                {{ $slot }}
            </main>
            
            @include('layouts.footer')
        </div>

        @stack('scripts')
        <script>
            $(document).ready(function() {
                const sidebar = $('#sidebar');
                const overlay = $('#sidebarOverlay');
                const toggle = $('#sidebarToggle');

                toggle.on('click', function () {
                    const isOpen = sidebar.css('transform') == 'matrix(1, 0, 0, 1, 0, 0)';
                    if (!isOpen) {
                        sidebar.css('transform', 'translateX(0)');
                        overlay.removeClass('hidden');
                        setTimeout(() => overlay.addClass('opacity-100').removeClass('opacity-0'), 10);
                    } else {
                        sidebar.css('transform', 'translateX(-100%)');
                        overlay.removeClass('opacity-100').addClass('opacity-0');
                        setTimeout(() => overlay.addClass('hidden'), 300);
                    }

                    // DaisyUI icon swap
                    toggle.toggleClass('swap-active');
                });

                // Close when clicking outside
                $(document).on('click', function(e) {
                    const insideSidebar = sidebar.has(e.target).length > 0;
                    const onToggle = toggle.has(e.target).length > 0;
                    const maxScreenWidth = window.screen.width;

                    if (!insideSidebar && !onToggle && maxScreenWidth < 768) {
                        sidebar.css('transform', 'translateX(-100%)');
                        overlay.removeClass('opacity-100').addClass('opacity-0');
                        setTimeout(() => overlay.addClass('hidden'), 300);
                        toggle.removeClass('swap-active');
                    }
                });
            });

        </script>
    </body>
</html>
