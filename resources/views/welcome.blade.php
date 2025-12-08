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

        /* Modern Gradient Backgrounds */
        .gradient-mesh {
            background: 
                radial-gradient(at 27% 37%, hsla(215, 98%, 61%, 0.15) 0px, transparent 50%),
                radial-gradient(at 97% 21%, hsla(125, 98%, 72%, 0.12) 0px, transparent 50%),
                radial-gradient(at 52% 99%, hsla(354, 98%, 61%, 0.12) 0px, transparent 50%),
                radial-gradient(at 10% 29%, hsla(256, 96%, 67%, 0.15) 0px, transparent 50%),
                radial-gradient(at 97% 96%, hsla(38, 60%, 74%, 0.12) 0px, transparent 50%),
                radial-gradient(at 33% 50%, hsla(222, 67%, 73%, 0.15) 0px, transparent 50%),
                radial-gradient(at 79% 53%, hsla(343, 68%, 79%, 0.12) 0px, transparent 50%);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .gradient-accent {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Neumorphism Cards */
        .neu-card {
            background: #f0f4f8;
            border-radius: 30px;
            box-shadow: 
                20px 20px 60px #d1d9e6,
                -20px -20px 60px #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .neu-card:hover {
            box-shadow: 
                25px 25px 75px #d1d9e6,
                -25px -25px 75px #ffffff;
            transform: translateY(-5px);
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

        /* Modern Feature Cards */
        .feature-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
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

        /* Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .navbar-glass.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
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

        /* Screenshot Mockup */
        .mockup-browser {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(0, 0, 0, 0.1);
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

        /* Role Badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-user {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        /* Button Effects */
        .btn-modern {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-modern::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-modern:hover::after {
            width: 300px;
            height: 300px;
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

        /* Responsive Images */
        img {
            max-width: 100%;
            height: auto;
            display: block;
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

        /* Testimonial Cards */
        .testimonial-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        /* Footer Styling */
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body class="overflow-x-hidden bg-white">
    <!-- Navbar -->
    <nav class="navbar-glass fixed top-0 w-full z-50 px-4 lg:px-8">
        <div class="container flex items-center justify-between pr-10 py-4">
            <div class="flex items-center gap-8">
                <a href="#beranda" class="flex items-center gap-2 group">
                    <i class="ri-file-chart-line text-4xl gradient-text"></i>
                    <span class="text-2xl font-black hidden sm:inline gradient-text">SILAB</span>
                </a>
                <ul class="hidden lg:flex items-center gap-8">
                    <li><a href="#beranda" class="font-medium hover:text-purple-600 transition-colors">Beranda</a></li>
                    <li><a href="#fitur" class="font-medium hover:text-purple-600 transition-colors">Fitur</a></li>
                    <li><a href="#preview" class="font-medium hover:text-purple-600 transition-colors">Preview</a></li>
                    <li><a href="#kontak" class="font-medium hover:text-purple-600 transition-colors">Kontak</a></li>
                </ul>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="btn btn-sm hidden sm:inline-flex btn-disabled">
                    <i class="ri-user-line"></i>
                    Belum Dibuka Untuk Umum
                </a>
                <a href="{{ route('login')}}" class="btn btn-sm gradient-primary text-white border-0 btn-modern">
                    <i class="ri-login-box-line"></i>
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="min-h-screen gradient-primary relative overflow-hidden flex items-center pt-20" id="beranda">
            <div class="container mx-auto px-4 py-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-white space-y-8 fade-up">
                        <div class="inline-block">
                            <span class="glass-dark px-4 py-2 rounded-full text-sm font-semibold">
                                🚀 Sistem Terpercaya #1
                            </span>
                        </div>
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-tight">
                            Kelola Laporan<br />
                            <span class="text-yellow-300">Lebih Efisien</span>
                        </h1>
                        <p class="text-xl md:text-2xl opacity-95 leading-relaxed max-w-xl">
                            Sistem Laporan Bulanan terintegrasi dengan dashboard realtime dan role-based access control
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="#" class="btn btn-lg glass-dark text-white border-white hover:bg-white hover:text-purple-700 btn-modern">
                                <i class="ri-rocket-line text-xl"></i>
                                Mulai Gratis
                            </a>
                            <a href="#" class="btn btn-lg btn-outline border-white text-white hover:bg-white hover:text-purple-700">
                                <i class="ri-play-circle-line text-xl"></i>
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
                    <div class="relative float hidden lg:block">
                        <div class="glass-dark rounded-3xl p-8 backdrop-blur-xl">
                            <div class="aspect-square bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-2xl flex items-center justify-center">
                                <i class="ri-line-chart-line text-9xl text-white/80"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 scroll-indicator">
                <i class="ri-arrow-down-line text-4xl text-white opacity-70"></i>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 md:py-32 gradient-mesh" id="fitur">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 fade-up">
                    <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Fitur Unggulan</span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black mt-4 mb-6">
                        Solusi <span class="gradient-text">Lengkap</span> Untuk Anda
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Sistem terintegrasi dengan teknologi modern dan interface yang intuitif
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-primary rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-dashboard-3-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Dashboard Admin</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Monitoring realtime dengan statistik lengkap dan analytics dashboard
                        </p>
                    </div>

                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-secondary rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-user-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">User Dashboard</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Interface user-friendly untuk upload dan kelola dokumen dengan mudah
                        </p>
                    </div>

                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-accent rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-upload-cloud-2-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Upload Kegiatan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            3 step upload dengan dokumentasi lengkap: Before, Process, After
                        </p>
                    </div>

                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-primary rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-file-list-3-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Master Data</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Kelola status upload dan dokumen dengan sistem terorganisir
                        </p>
                    </div>

                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-secondary rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-calendar-check-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Kalender Laporan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            View riwayat laporan berdasarkan bulan dengan preview thumbnail
                        </p>
                    </div>

                    <div class="neu-card p-8 feature-card">
                        <div class="gradient-accent rounded-2xl w-16 h-16 flex items-center justify-center mb-6">
                            <i class="ri-file-chart-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Activity Tracking</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Timeline aktivitas dengan timestamp detail untuk setiap upload
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Preview Section -->
        <section class="py-20 md:py-32 bg-white" id="preview">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 fade-up">
                    <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Preview Sistem</span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black mt-4 mb-6">
                        Lihat <span class="gradient-text">Dashboard</span> Kami
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Tampilan modern dan responsive untuk admin dan user
                    </p>
                </div>

                <!-- Admin Dashboard -->
                <div class="max-w-6xl mx-auto mb-20 slide-in-left">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="role-badge badge-admin">
                            <i class="ri-admin-line text-xl"></i>
                            <span>Admin Dashboard</span>
                        </div>
                    </div>
                    <div class="mockup-browser">
                        <div class="mockup-header">
                            <div class="mockup-dot" style="background: #ff5f57;"></div>
                            <div class="mockup-dot" style="background: #ffbd2e;"></div>
                            <div class="mockup-dot" style="background: #28ca42;"></div>
                        </div>
                        <img src="https://i.ibb.co/zHv8xnL/admin-dashboard.png" alt="Admin Dashboard" />
                    </div>
                    <div class="grid md:grid-cols-3 gap-6 mt-8">
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-purple-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">Real-time Statistics</h4>
                                <p class="text-sm text-gray-600">Data rekap dengan persentase perubahan</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-purple-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">Activity Timeline</h4>
                                <p class="text-sm text-gray-600">Monitoring aktivitas user secara live</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-purple-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">Analytics Dashboard</h4>
                                <p class="text-sm text-gray-600">Persentase aktivitas per PT/SAC</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dashboard -->
                <div class="max-w-6xl mx-auto slide-in-right">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="role-badge badge-user">
                            <i class="ri-user-line text-xl"></i>
                            <span>User Dashboard</span>
                        </div>
                    </div>
                    <div class="mockup-browser">
                        <div class="mockup-header">
                            <div class="mockup-dot" style="background: #ff5f57;"></div>
                            <div class="mockup-dot" style="background: #ffbd2e;"></div>
                            <div class="mockup-dot" style="background: #28ca42;"></div>
                        </div>
                        <img src="https://i.ibb.co/9qvXvJY/user-dashboard.png" alt="User Dashboard" />
                    </div>
                    <div class="grid md:grid-cols-3 gap-6 mt-8">
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-pink-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">3-Step Upload</h4>
                                <p class="text-sm text-gray-600">Before, Process, dan After</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-pink-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">Limit Tracker</h4>
                                <p class="text-sm text-gray-600">Monitoring kuota upload gambar</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-pink-600"></i>
                            <div>
                                <h4 class="font-bold mb-1">Gallery View</h4>
                                <p class="text-sm text-gray-600">Riwayat laporan dengan preview</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 md:py-32 gradient-mesh">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 fade-up">
                    <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Testimoni</span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black mt-4 mb-6">
                        Kata <span class="gradient-text">Mereka</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Pengalaman nyata dari pengguna SILAB
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <div class="testimonial-card fade-up">
                        <div class="flex gap-1 mb-4">
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed">
                            "Dashboard admin memberikan insight yang jelas. Monitoring aktivitas jadi lebih efisien dan real-time!"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="gradient-secondary rounded-full w-12 h-12 flex items-center justify-center text-white font-bold text-lg">
                                AM
                            </div>
                            <div>
                                <h4 class="font-bold">Anonim</h4>
                                <p class="text-sm text-gray-600">Pengguna Setia</p>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card fade-up">
                        <div class="flex gap-1 mb-4">
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                            <i class="ri-star-fill text-xl text-yellow-400"></i>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed">
                            "Tracking limit upload sangat membantu. Riwayat laporan juga mudah dicari berdasarkan bulan. Recommended!"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="gradient-accent rounded-full w-12 h-12 flex items-center justify-center text-white font-bold text-lg">
                                AM
                            </div>
                            <div>
                                <h4 class="font-bold">Anonim</h4>
                                <p class="text-sm text-gray-600">Pengguna Pertama</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 md:py-32 gradient-primary relative overflow-hidden">
            <div class="container mx-auto px-4 text-center text-white relative z-10">
                <div class="max-w-4xl mx-auto fade-up">
                    <div class="mb-8">
                        <i class="ri-rocket-2-line text-7xl"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                        Siap Kelola Laporan<br />Dengan Lebih Baik?
                    </h2>
                    <p class="text-xl md:text-2xl mb-10 opacity-95 max-w-2xl mx-auto">
                        Bergabunglah sekarang dan rasakan kemudahan sistem rekapitulasi modern
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                        <a href="#" class="btn btn-lg glass-dark text-purple-200 border-white hover:bg-white hover:text-white btn-modern">
                            <i class="ri-download-cloud-line text-xl"></i>
                            Daftar Gratis
                        </a>
                        <a href="#" class="btn btn-lg btn-outline border-white text-white hover:bg-white hover:text-purple-700">
                            <i class="ri-phone-line text-xl"></i>
                            Hubungi Kami
                        </a>
                    </div>

                    <div class="flex flex-wrap justify-center gap-8 opacity-90">
                        <div class="flex items-center gap-2">
                            <i class="ri-shield-check-line text-2xl"></i>
                            <span class="text-sm font-medium">SSL Certified</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ri-server-line text-2xl"></i>
                            <span class="text-sm font-medium">99.9% Uptime</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ri-customer-service-line text-2xl"></i>
                            <span class="text-sm font-medium">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-gray-300 pt-20 pb-10" id="kontak">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="ri-file-chart-line text-5xl gradient-text"></i>
                        <span class="text-3xl font-black text-white">SILAB</span>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed max-w-md">
                        Platform Sistem laporan bulanan berbasis web dengan interface modern dan role-based access control untuk efisiensi maksimal.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i class="ri-facebook-fill text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i class="ri-twitter-fill text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i class="ri-instagram-fill text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i class="ri-linkedin-fill text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-6">Menu</h3>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="hover:text-white transition-colors flex items-center gap-2">
                            <i class="ri-arrow-right-s-line"></i> Beranda
                        </a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors flex items-center gap-2">
                            <i class="ri-arrow-right-s-line"></i> Fitur
                        </a></li>
                        <li><a href="#preview" class="hover:text-white transition-colors flex items-center gap-2">
                            <i class="ri-arrow-right-s-line"></i> Preview
                        </a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">
                            <i class="ri-arrow-right-s-line"></i> Dokumentasi
                        </a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-6">Kontak</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="ri-map-pin-line text-xl text-purple-400 mt-1"></i>
                            <span class="text-sm">Jl. Budi Utomo No. 10<br />Ponorogo, Jawa Timur, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ri-phone-line text-xl text-purple-400"></i>
                            <span class="text-sm">---</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ri-mail-line text-xl text-purple-400"></i>
                            <span class="text-sm">ponorogo.sac@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-500 text-sm">
                        © 2024 SILAB. All rights reserved.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-white transition-colors">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <div class="back-to-top" id="backToTop">
        <i class="ri-arrow-up-line text-2xl"></i>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar-glass').addClass('scrolled');
                } else {
                    $('.navbar-glass').removeClass('scrolled');
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