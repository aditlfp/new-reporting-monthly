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
        @laravelPWA
        <script src="/js/jquery3.7.1-min.js"></script>  

        <script src="/js/Notify.js"></script> 

        {{-- Stack To Push Styles --}}
        <style>
            @stack('styles')
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

                const sidebarToggle = $('#sidebarToggle');
                sidebarToggle.on('click', function() {
                        sidebar.toggleClass('-translate-x-full');
                });
                // Close sidebar when clicking outside on mobile
                $(document).on('click', function(event) {
                    const isClickInsideSidebar = sidebar.has(event.target).length > 0;
                    const isClickOnToggle = sidebarToggle.has(event.target).length > 0;

                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.hasClass('-translate-x-full')) {
                        sidebar.addClass('-translate-x-full');
                    }
                });
            })
        </script>
    </body>
</html>
