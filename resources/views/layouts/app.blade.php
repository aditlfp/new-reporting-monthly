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

        .field-required-marker {
            margin-left: 0.25rem;
            color: #dc2626;
            font-weight: 700;
        }

        .field-optional-marker {
            margin-left: 0.35rem;
            color: #0284c7;
            font-style: italic;
            font-weight: 500;
            font-size: 0.85em;
        }
    </style>
</head>

<body class="font-sans antialiased {{ Auth::check() && Auth::user()->role_id == 2 ? 'admin-ui' : '' }}">
    <div class="min-h-screen bg-gray-100">
        <div id="notifications"></div>
        @props(['title' => 'Default Title', 'subtitle' => 'Default Subtitle'])
        @if (Auth::user()->role_id == 2)
            <x-navigation :title="$title ?? 'Dashboard Overview'" :subtitle="$subtitle ?? 'Welcome back!'" />
        @endif
        <!-- Page Content -->
        <main class="{{ Auth::user()->role_id == 2 ? 'pt-20 admin-main' : 'pt-0' }}">
            {{ $slot }}
        </main>

            @include('layouts.footer')
        </div>

    @include('components.session-toast')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('label');

            labels.forEach(function(label) {
                if (label.querySelector('.field-required-marker, .field-optional-marker')) {
                    return;
                }

                let field = null;
                const fieldId = label.getAttribute('for');

                if (fieldId) {
                    field = document.getElementById(fieldId);
                }

                if (!field) {
                    field = label.closest('.form-control')?.querySelector('input, select, textarea')
                        || label.parentElement?.querySelector('input, select, textarea');
                }

                if (!field) {
                    return;
                }

                const marker = document.createElement('span');

                if (field.required) {
                    marker.className = 'field-required-marker';
                    marker.textContent = '*';
                } else {
                    marker.className = 'field-optional-marker';
                    marker.textContent = '(opsional)';
                }

                label.appendChild(marker);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const overlay = $('#sidebarOverlay');
            const toggle = $('#sidebarToggle');

            toggle.on('click', function() {
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
    @if(session('set_operator'))
    <script>
        localStorage.setItem('SACoperator', 'true');
    </script>
    @endif
</body>

</html>
