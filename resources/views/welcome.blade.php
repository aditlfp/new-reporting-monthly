<!DOCTYPE html>
<html lang="id" data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAB - Sistem Laporan Bulanan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Animated Background */
        .animated-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Animation */
        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-text-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Animated Elements */
        .fade-up {
            opacity: 0;
            transform: translateY(60px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(60px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Stats Counter */
        .stat-number {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Feature Cards */
        .feature-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        /* Mockup Browser */
        .mockup-browser {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            background: white;
            transition: transform 0.3s ease;
        }

        .mockup-browser:hover {
            transform: scale(1.02);
        }

        .mockup-header {
            background: linear-gradient(180deg, #f5f5f5 0%, #e8e8e8 100%);
            padding: 16px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mockup-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* Testimonial Cards */
        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
        }

        /* Scroll Indicator */
        .scroll-indicator {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>

<body class="overflow-x-hidden bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 z-50 w-full px-4 py-4 transition-all duration-300 glass lg:px-8" id="navbar">
        <div class="container flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="#beranda" class="flex items-center gap-2 group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-lg bg-gradient-to-br from-purple-600 to-indigo-700">
                        <i class="text-xl text-white ri-file-chart-line"></i>
                    </div>
                    <span class="hidden text-2xl font-bold text-gray-800 sm:inline">SILAB</span>
                </a>
                <ul class="items-center hidden gap-8 lg:flex">
                    <li><a href="#beranda" class="font-medium text-gray-700 transition-colors hover:text-purple-600">Beranda</a></li>
                    <li><a href="#fitur" class="font-medium text-gray-700 transition-colors hover:text-purple-600">Fitur</a></li>
                    <li><a href="#preview" class="font-medium text-gray-700 transition-colors hover:text-purple-600">Preview</a></li>
                    <li><a href="#kontak" class="font-medium text-gray-700 transition-colors hover:text-purple-600">Kontak</a></li>
                </ul>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="hidden btn btn-sm sm:inline-flex btn-ghost">
                    <i class="ri-user-line"></i>
                    Belum Dibuka Untuk Umum
                </a>
                <a href="{{ route('login')}}" class="text-white transition-all duration-300 border-0 shadow-lg btn btn-sm bg-gradient-to-r from-purple-600 to-indigo-700 hover:shadow-xl">
                    <i class="ri-login-box-line"></i>
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="relative flex items-center min-h-screen pt-20 overflow-hidden animated-bg" id="beranda">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="container relative z-10 px-4 py-20 mx-auto">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <!-- Left Content -->
                    <div class="space-y-8 text-white fade-up">
                        <div class="inline-block">
                            <span class="px-6 py-3 text-sm font-semibold rounded-full shadow-lg glass">
                                🚀 Sistem Terpercaya #1
                            </span>
                        </div>
                        <h1 class="text-5xl font-black leading-tight md:text-6xl lg:text-7xl">
                            Kelola Laporan<br />
                            <span class="text-yellow-300">Lebih Efisien</span>
                        </h1>
                        <p class="max-w-xl text-xl leading-relaxed md:text-2xl opacity-95">
                            Sistem Laporan Bulanan terintegrasi dengan dashboard realtime dan role-based access control
                        </p>
                        <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                            <a href="#" class="text-white transition-all duration-300 border-white shadow-lg btn btn-lg glass hover:bg-white hover:text-purple-700 hover:shadow-xl">
                                <i class="text-xl ri-rocket-line"></i>
                                Mulai Gratis
                            </a>
                            <a href="#" class="text-white transition-all duration-300 border-white btn btn-lg btn-outline hover:bg-white hover:text-purple-700">
                                <i class="text-xl ri-play-circle-line"></i>
                                Lihat Demo
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 pt-8">
                            <div class="text-center">
                                <div class="stat-number">500+</div>
                                <div class="text-sm opacity-90">User Aktif</div>
                            </div>
                            <div class="text-center">
                                <div class="stat-number">10K+</div>
                                <div class="text-sm opacity-90">Laporan</div>
                            </div>
                            <div class="text-center">
                                <div class="stat-number">99.9%</div>
                                <div class="text-sm opacity-90">Uptime</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Floating Illustration -->
                    <div class="relative hidden float lg:block">
                        <div class="p-8 shadow-2xl glass rounded-3xl">
                            <div class="flex items-center justify-center aspect-square bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-2xl">
                                <i class="ri-line-chart-line text-9xl text-white/80"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute transform -translate-x-1/2 bottom-8 left-1/2 scroll-indicator">
                <i class="text-4xl text-white ri-arrow-down-line opacity-70"></i>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 md:py-32 bg-gradient-to-br from-purple-50 to-indigo-50" id="fitur">
            <div class="container px-4 mx-auto">
                <div class="mb-16 text-center fade-up">
                    <span class="text-sm font-semibold tracking-wider text-purple-600 uppercase">Fitur Unggulan</span>
                    <h2 class="mt-4 mb-6 text-4xl font-black md:text-5xl lg:text-6xl">
                        Solusi <span class="gradient-text">Lengkap</span> Untuk Anda
                    </h2>
                    <p class="max-w-2xl mx-auto text-xl text-gray-600">
                        Sistem terintegrasi dengan teknologi modern dan interface yang intuitif
                    </p>
                </div>

                <div class="grid gap-8 mx-auto md:grid-cols-2 lg:grid-cols-3 max-w-7xl">
                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-purple-600 to-indigo-700 rounded-xl">
                            <i class="text-3xl text-white ri-dashboard-3-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">Dashboard Admin</h3>
                        <p class="leading-relaxed text-gray-600">
                            Monitoring realtime dengan statistik lengkap dan analytics dashboard
                        </p>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl">
                            <i class="text-3xl text-white ri-user-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">User Dashboard</h3>
                        <p class="leading-relaxed text-gray-600">
                            Interface user-friendly untuk upload dan kelola dokumen dengan mudah
                        </p>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl">
                            <i class="text-3xl text-white ri-upload-cloud-2-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">Upload Kegiatan</h3>
                        <p class="leading-relaxed text-gray-600">
                            3 step upload dengan dokumentasi lengkap: Before, Process, After
                        </p>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-purple-600 to-indigo-700 rounded-xl">
                            <i class="text-3xl text-white ri-file-list-3-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">Master Data</h3>
                        <p class="leading-relaxed text-gray-600">
                            Kelola status upload dan dokumen dengan sistem terorganisir
                        </p>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl">
                            <i class="text-3xl text-white ri-calendar-check-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">Kalender Laporan</h3>
                        <p class="leading-relaxed text-gray-600">
                            View riwayat laporan berdasarkan bulan dengan preview thumbnail
                        </p>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl feature-card">
                        <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-lg bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl">
                            <i class="text-3xl text-white ri-file-chart-line"></i>
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-800">Activity Tracking</h3>
                        <p class="leading-relaxed text-gray-600">
                            Timeline aktivitas dengan timestamp detail untuk setiap upload
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 md:py-32 bg-gradient-to-br from-purple-50 to-indigo-50">
            <div class="container px-4 mx-auto">
                <div class="mb-16 text-center fade-up">
                    <span class="text-sm font-semibold tracking-wider text-purple-600 uppercase">Testimoni</span>
                    <h2 class="mt-4 mb-6 text-4xl font-black md:text-5xl lg:text-6xl">
                        Kata <span class="gradient-text-2">Mereka</span>
                    </h2>
                    <p class="max-w-2xl mx-auto text-xl text-gray-600">
                        Pengalaman nyata dari pengguna SILAB
                    </p>
                </div>

                <div class="grid max-w-6xl gap-8 mx-auto md:grid-cols-3">
                    <div class="p-8 bg-white shadow-lg rounded-2xl testimonial-card">
                        <div class="flex gap-1 mb-4">
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                        </div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            "Dashboard admin memberikan insight yang jelas. Monitoring aktivitas jadi lebih efisien dan real-time!"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white rounded-full bg-gradient-to-r from-pink-500 to-rose-600">
                                AM
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Anonim</h4>
                                <p class="text-sm text-gray-600">Pengguna Setia</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-white shadow-lg rounded-2xl testimonial-card">
                        <div class="flex gap-1 mb-4">
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                            <i class="text-xl text-yellow-400 ri-star-fill"></i>
                        </div>
                        <p class="mb-6 leading-relaxed text-gray-700">
                            "Tracking limit upload sangat membantu. Riwayat laporan juga mudah dicari berdasarkan bulan. Recommended!"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white rounded-full bg-gradient-to-r from-cyan-500 to-blue-600">
                                AM
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Anonim</h4>
                                <p class="text-sm text-gray-600">Pengguna Pertama</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative py-20 overflow-hidden md:py-32 animated-bg">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="container relative z-10 px-4 mx-auto text-center text-white">
                <div class="max-w-4xl mx-auto fade-up">
                    <div class="mb-8">
                        <i class="ri-rocket-2-line text-7xl"></i>
                    </div>
                    <h2 class="mb-6 text-4xl font-black leading-tight md:text-5xl lg:text-6xl">
                        Siap Kelola Laporan<br />Dengan Lebih Baik?
                    </h2>
                    <p class="max-w-2xl mx-auto mb-10 text-xl md:text-2xl opacity-95">
                        Bergabunglah sekarang dan rasakan kemudahan sistem rekapitulasi modern
                    </p>
                    <div class="flex flex-col justify-center gap-4 mb-12 sm:flex-row">
                        <a href="#" class="text-white transition-all duration-300 border-white shadow-lg btn btn-lg glass hover:bg-white hover:text-purple-700 hover:shadow-xl">
                            <i class="text-xl ri-download-cloud-line"></i>
                            Daftar Gratis
                        </a>
                        <a href="#" class="text-white transition-all duration-300 border-white btn btn-lg btn-outline hover:bg-white hover:text-purple-700">
                            <i class="text-xl ri-phone-line"></i>
                            Hubungi Kami
                        </a>
                    </div>

                    <div class="flex flex-wrap justify-center gap-8 opacity-90">
                        <div class="flex items-center gap-2">
                            <i class="text-2xl ri-shield-check-line"></i>
                            <span class="text-sm font-medium">SSL Certified</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="text-2xl ri-server-line"></i>
                            <span class="text-sm font-medium">99.9% Uptime</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="text-2xl ri-customer-service-line"></i>
                            <span class="text-sm font-medium">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="pt-20 pb-10 text-gray-300 bg-gradient-to-br from-gray-900 to-gray-800" id="kontak">
        <div class="container px-4 mx-auto">
            <div class="grid gap-12 mb-12 md:grid-cols-4">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg shadow-lg bg-gradient-to-br from-purple-600 to-indigo-700">
                            <i class="text-2xl text-white ri-file-chart-line"></i>
                        </div>
                        <span class="text-3xl font-black text-white">SILAB</span>
                    </div>
                    <p class="max-w-md mb-6 leading-relaxed text-gray-400">
                        Platform Sistem laporan bulanan berbasis web dengan interface modern dan role-based access control untuk efisiensi maksimal.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="flex items-center justify-center w-12 h-12 text-white transition-transform rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:scale-110">
                            <i class="text-xl ri-facebook-fill"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center w-12 h-12 text-white transition-transform rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:scale-110">
                            <i class="text-xl ri-twitter-fill"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center w-12 h-12 text-white transition-transform rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:scale-110">
                            <i class="text-xl ri-instagram-fill"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center w-12 h-12 text-white transition-transform rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:scale-110">
                            <i class="text-xl ri-linkedin-fill"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="mb-6 text-lg font-bold text-white">Menu</h3>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="flex items-center gap-2 transition-colors hover:text-white">
                            <i class="ri-arrow-right-s-line"></i> Beranda
                        </a></li>
                        <li><a href="#fitur" class="flex items-center gap-2 transition-colors hover:text-white">
                            <i class="ri-arrow-right-s-line"></i> Fitur
                        </a></li>
                        <li><a href="#preview" class="flex items-center gap-2 transition-colors hover:text-white">
                            <i class="ri-arrow-right-s-line"></i> Preview
                        </a></li>
                        <li><a href="#" class="flex items-center gap-2 transition-colors hover:text-white">
                            <i class="ri-arrow-right-s-line"></i> Dokumentasi
                        </a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-6 text-lg font-bold text-white">Kontak</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="mt-1 text-xl text-purple-400 ri-map-pin-line"></i>
                            <span class="text-sm">Jl. Budi Utomo No. 10<br />Ponorogo, Jawa Timur, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="text-xl text-purple-400 ri-phone-line"></i>
                            <span class="text-sm">---</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="text-xl text-purple-400 ri-mail-line"></i>
                            <span class="text-sm">ponorogo.sac@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-700">
                <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                    <p class="text-sm text-gray-500">
                        © 2024 SILAB. All rights reserved.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="#" class="transition-colors hover:text-white">Privacy Policy</a>
                        <a href="#" class="transition-colors hover:text-white">Terms of Service</a>
                        <a href="#" class="transition-colors hover:text-white">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <div class="back-to-top" id="backToTop">
        <i class="text-2xl ri-arrow-up-line"></i>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('#navbar').addClass('bg-white/95 shadow-lg');
                } else {
                    $('#navbar').removeClass('bg-white/95 shadow-lg');
                }
            });

            // Scroll animations
            function checkVisible() {
                $('.fade-up, .slide-in-left, .slide-in-right').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();

                    if (elementBottom > viewportTop && elementTop < viewportBottom - 100) {
                        $(this).addClass('visible');
                    }
                });
            }

            setTimeout(checkVisible, 100);
            $(window).on('scroll', checkVisible);

            // Smooth scroll
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $(this.hash);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            });

            // Back to top
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#backToTop').addClass('visible');
                } else {
                    $('#backToTop').removeClass('visible');
                }
            });

            $('#backToTop').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 800);
            });
        });
    </script>
</body>

</html>