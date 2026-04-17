<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAB - Sistem Laporan Bulanan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #f5f1e8;
            --surface: #fffdfa;
            --surface-alt: #f1ece2;
            --ink: #172126;
            --muted: #5f686f;
            --line: rgba(23, 33, 38, 0.1);
            --accent: #b85f34;
            --accent-dark: #934721;
            --green: #29453f;
            --shadow: 0 16px 40px rgba(23, 33, 38, 0.08);
        }

        * {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(184, 95, 52, 0.12), transparent 24%),
                radial-gradient(circle at right 10%, rgba(41, 69, 63, 0.12), transparent 18%),
                linear-gradient(180deg, #f7f2e9 0%, #f2ede4 100%);
            color: var(--ink);
            font-family: "Manrope", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            letter-spacing: -0.01em;
        }

        .shell {
            width: min(1120px, calc(100% - 2rem));
            margin: 0 auto;
        }

        h1,
        h2,
        h3,
        .brand-type {
            font-family: "Outfit", "Manrope", sans-serif;
            letter-spacing: -0.04em;
        }

        h1 {
            line-height: 0.95;
        }

        h2 {
            line-height: 1.02;
        }

        .hero-panel {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .hero-shape,
        .hero-shape-alt,
        .hero-shape-line {
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }

        .hero-shape {
            top: -3rem;
            right: -2rem;
            width: 11rem;
            height: 11rem;
            border-radius: 2rem;
            background: linear-gradient(135deg, rgba(184, 95, 52, 0.18), rgba(184, 95, 52, 0.05));
            transform: rotate(18deg);
        }

        .hero-shape-alt {
            left: -2rem;
            bottom: -3.25rem;
            width: 8rem;
            height: 8rem;
            border-radius: 999px;
            border: 1px solid rgba(41, 69, 63, 0.12);
            background: rgba(41, 69, 63, 0.06);
        }

        .hero-shape-line {
            top: 2rem;
            right: 20%;
            width: 7rem;
            height: 7rem;
            border-top: 1px solid rgba(23, 33, 38, 0.08);
            border-right: 1px solid rgba(23, 33, 38, 0.08);
            border-radius: 0 2rem 0 0;
            transform: rotate(14deg);
        }

        .panel,
        .card {
            background: rgba(255, 253, 250, 0.92);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.82);
            color: var(--green);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .kicker-block {
            position: relative;
            padding-left: 1rem;
        }

        .kicker-block::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.2rem;
            width: 0.35rem;
            height: 2.8rem;
            border-radius: 999px;
            background: linear-gradient(180deg, var(--accent), rgba(184, 95, 52, 0.08));
        }

        .section-line {
            width: 84px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent), rgba(184, 95, 52, 0.12));
        }

        .btn-primary,
        .btn-secondary {
            min-height: 3rem;
            padding-inline: 1.2rem;
            border-radius: 999px;
            font-weight: 700;
            transition: background-color 180ms ease, border-color 180ms ease, transform 180ms ease;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border: 1px solid transparent;
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            background: #fff;
            border-color: rgba(23, 33, 38, 0.18);
            transform: translateY(-1px);
        }

        .simple-grid {
            background-image:
                linear-gradient(rgba(23, 33, 38, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(23, 33, 38, 0.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        .accent-quote {
            position: relative;
        }

        .accent-quote::after {
            content: "";
            position: absolute;
            right: 1rem;
            top: 1rem;
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.02));
        }

        .back-to-top {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: rgba(23, 33, 38, 0.88);
            color: #fff;
            box-shadow: 0 12px 30px rgba(23, 33, 38, 0.18);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 180ms ease, transform 180ms ease, visibility 180ms ease;
            z-index: 40;
        }

        .back-to-top.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .mobile-menu {
            display: none;
            margin-top: 0.75rem;
        }

        .mobile-menu.is-open {
            display: block;
        }

        .mobile-menu-inner {
            overflow: visible;
        }

        .menu-toggle-icon {
            position: relative;
            display: block;
            width: 1.1rem;
            height: 1rem;
        }

        .menu-toggle-line {
            position: absolute;
            left: 0;
            top: calc(50% - 1px);
            width: 1.1rem;
            height: 2px;
            border-radius: 999px;
            background: var(--ink);
            transform-origin: center;
            transition: transform 160ms ease, opacity 120ms ease;
        }

        .menu-toggle-line:nth-child(1) {
            transform: translateY(-6px);
        }

        .menu-toggle-line:nth-child(2) {
            transform: translateY(0);
        }

        .menu-toggle-line:nth-child(3) {
            transform: translateY(6px);
        }

        .menu-toggle.is-open .menu-toggle-line:nth-child(1) {
            transform: translateY(0) rotate(45deg);
        }

        .menu-toggle.is-open .menu-toggle-line:nth-child(2) {
            opacity: 0;
            transform: translateY(0) scaleX(0.6);
        }

        .menu-toggle.is-open .menu-toggle-line:nth-child(3) {
            transform: translateY(0) rotate(-45deg);
        }

        @media (max-width: 767px) {
            .shell {
                width: min(100%, calc(100% - 1rem));
            }

            .eyebrow {
                width: 100%;
                justify-content: center;
                text-align: center;
                line-height: 1.5;
                white-space: normal;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
                padding-inline: 1rem;
            }

            .back-to-top {
                right: 0.85rem;
                bottom: 0.85rem;
                width: 2.75rem;
                height: 2.75rem;
            }

            .hero-shape {
                width: 6.5rem;
                height: 6.5rem;
                top: -1.5rem;
                right: -1.5rem;
            }

            .hero-shape-alt,
            .hero-shape-line {
                display: none;
            }

            .kicker-block {
                padding-left: 0;
            }

            .kicker-block::before {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .nav-menu {
                display: none;
            }
        }

        @media (min-width: 1025px) {
            .mobile-only {
                display: none;
            }
        }

        @media (max-width: 640px) {
            #navbar {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                padding-top: 0.5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                scroll-behavior: auto;
            }

            .btn-primary,
            .btn-secondary,
            .back-to-top {
                transition: none;
            }
        }
    </style>
</head>

<body class="overflow-x-hidden">
    <nav class="fixed top-0 z-40 w-full px-4 pt-4" id="navbar">
        <div class="shell">
            <div class="panel rounded-[22px] px-3 py-3 sm:px-4 md:rounded-[26px] md:px-6">
                <div class="flex items-center justify-between gap-3">
                    <a href="#beranda" class="flex min-w-0 items-center gap-2.5 sm:gap-3">
                        <img src="{{ asset('img/logo-320.webp') }}" alt="Logo SILAB" width="48" height="48" class="flex-shrink-0 object-contain w-10 h-10 p-1 bg-white rounded-2xl sm:h-12 sm:w-12" fetchpriority="high" decoding="async">
                        <div class="min-w-0">
                            <p class="brand-type truncate text-base font-extrabold tracking-tight text-[#172126] sm:text-lg">SILAB</p>
                            <p class="hidden text-[10px] uppercase tracking-[0.24em] text-slate-500 sm:block md:text-xs">Sistem Laporan Bulanan</p>
                        </div>
                    </a>

                    <div class="items-center text-sm font-semibold nav-menu gap-7 text-slate-600 lg:flex">
                        <a href="#beranda" class="transition hover:text-[#172126]">Beranda</a>
                        <a href="#tentang" class="transition hover:text-[#172126]">Tentang</a>
                        <a href="#fitur" class="transition hover:text-[#172126]">Fitur</a>
                        <a href="#kontak" class="transition hover:text-[#172126]">Kontak</a>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="hidden h-auto shrink-0 border-0 px-4 py-2.5 text-sm btn btn-sm btn-primary sm:inline-flex sm:px-5">
                            <i class="text-base ri-login-box-line"></i>
                            <span>Masuk</span>
                        </a>

                        <button type="button" id="mobileMenuToggle" class="mobile-only menu-toggle inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-black/10 bg-white text-[#172126] lg:hidden" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobileMenu">
                            <span class="menu-toggle-icon">
                                <span class="menu-toggle-line"></span>
                                <span class="menu-toggle-line"></span>
                                <span class="menu-toggle-line"></span>
                            </span>
                        </button>
                    </div>
                </div>

                <div id="mobileMenu" class="mobile-menu mobile-only lg:hidden">
                    <div class="mobile-menu-inner">
                        <div class="grid gap-2 pt-3 border-t border-black/10">
                            <a href="#beranda" class="px-4 py-3 text-sm font-semibold transition rounded-2xl text-slate-700 hover:bg-black/5">Beranda</a>
                            <a href="#tentang" class="px-4 py-3 text-sm font-semibold transition rounded-2xl text-slate-700 hover:bg-black/5">Tentang</a>
                            <a href="#fitur" class="px-4 py-3 text-sm font-semibold transition rounded-2xl text-slate-700 hover:bg-black/5">Fitur</a>
                            <a href="#kontak" class="px-4 py-3 text-sm font-semibold transition rounded-2xl text-slate-700 hover:bg-black/5">Kontak</a>
                            <a href="{{ route('login') }}" class="rounded-2xl bg-[#172126] px-4 py-3 text-sm font-semibold text-white transition hover:bg-black">Masuk ke sistem</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section id="beranda" class="px-4 pt-24 sm:pt-28 md:pt-32">
            <div class="shell">
                <div class="hero-panel panel rounded-[26px] px-4 py-5 sm:px-6 sm:py-8 md:rounded-[34px] md:px-10 md:py-10 lg:px-14 lg:py-14">
                    <div class="hero-shape"></div>
                    <div class="hero-shape-alt"></div>
                    <div class="hero-shape-line"></div>
                    <div class="relative z-10 grid items-center gap-6 md:gap-8 lg:grid-cols-[1.08fr_0.92fr]">
                        <div class="kicker-block">
                            <div class="eyebrow">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-[#b85f34]"></span>
                                Pengelolaan laporan yang lebih terstruktur
                            </div>

                            <h1 class="mt-5 max-w-4xl text-3xl font-extrabold text-[#172126] sm:text-4xl md:mt-6 md:text-5xl lg:text-6xl">
                                Satu sistem untuk mencatat, memantau, dan merapikan laporan kegiatan bulanan.
                            </h1>

                            <p class="max-w-2xl mt-4 text-sm leading-7 text-slate-600 sm:text-base md:mt-6 md:text-lg md:leading-8">
                                SILAB membantu tim menyusun dokumentasi kegiatan, memantau progres pekerjaan, dan menyimpan arsip laporan bulanan secara lebih rapi, cepat, dan mudah ditelusuri.
                            </p>

                            <div class="flex flex-col gap-3 mt-8 sm:flex-row">
                                <a href="{{ route('login') }}" class="btn btn-primary sm:w-auto">
                                    <i class="text-lg ri-login-box-line"></i>
                                    Masuk ke SILAB
                                </a>
                                <a href="#fitur" class="btn btn-secondary sm:w-auto">
                                    <i class="text-lg ri-layout-grid-line"></i>
                                    Pelajari fiturnya
                                </a>
                            </div>

                            <div class="grid gap-3 mt-8 sm:grid-cols-3 sm:gap-4 md:mt-10">
                                <div class="card rounded-[20px] p-4">
                                    <p class="text-sm font-medium text-slate-500">Dokumentasi</p>
                                    <p class="mt-2 text-2xl font-bold text-[#172126] sm:text-3xl">3 tahap</p>
                                    <p class="mt-2 text-sm text-slate-600">Before, process, dan after tersusun dalam satu alur.</p>
                                </div>
                                <div class="card rounded-[20px] p-4">
                                    <p class="text-sm font-medium text-slate-500">Monitoring</p>
                                    <p class="mt-2 text-2xl font-bold text-[#172126] sm:text-3xl">Lebih cepat</p>
                                    <p class="mt-2 text-sm text-slate-600">Progres kegiatan lebih mudah dipantau dari satu halaman.</p>
                                </div>
                                <div class="card rounded-[20px] p-4">
                                    <p class="text-sm font-medium text-slate-500">Arsip</p>
                                    <p class="mt-2 text-2xl font-bold text-[#172126] sm:text-3xl">Per periode</p>
                                    <p class="mt-2 text-sm text-slate-600">Riwayat laporan lebih mudah dicari saat dibutuhkan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card simple-grid rounded-[24px] p-4 sm:p-5 md:rounded-[30px] md:p-6">
                            <div class="flex items-start justify-between gap-4 pb-4 border-b border-black/10">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Ringkasan</p>
                                    <h2 class="mt-2 text-xl font-bold text-[#172126] sm:text-2xl">Ringkas, jelas, dan langsung ke kebutuhan utama</h2>
                                </div>
                                <img src="{{ asset('img/logo-320.webp') }}" alt="Logo SILAB" width="56" height="56" class="flex-shrink-0 object-contain w-12 h-12 p-1 bg-white rounded-2xl sm:h-14 sm:w-14" loading="eager" decoding="async">
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="accent-quote rounded-[20px] bg-[#172126] p-4 text-white sm:p-5">
                                    <p class="text-sm uppercase tracking-[0.22em] text-white/65">Tujuan utama</p>
                                    <p class="mt-3 text-xl font-bold sm:text-2xl">Membuat pelaporan bulanan lebih tertib</p>
                                    <p class="mt-3 text-sm leading-7 text-white/75">
                                        Data kegiatan, foto progres, dan catatan pekerjaan tersimpan dalam alur yang konsisten dan lebih mudah diperiksa.
                                    </p>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="card rounded-[20px] p-4 sm:p-5">
                                        <p class="text-sm font-semibold text-slate-500">Untuk admin</p>
                                        <p class="mt-2 text-lg font-bold text-[#172126]">Pantau progres dengan lebih cepat</p>
                                        <p class="mt-3 text-sm leading-7 text-slate-600">
                                            Lihat perkembangan laporan, cek data yang masuk, dan telusuri aktivitas tanpa proses manual yang melelahkan.
                                        </p>
                                    </div>
                                    <div class="card rounded-[20px] p-4 sm:p-5">
                                        <p class="text-sm font-semibold text-slate-500">Untuk user</p>
                                        <p class="mt-2 text-lg font-bold text-[#172126]">Input kegiatan dengan lebih praktis</p>
                                        <p class="mt-3 text-sm leading-7 text-slate-600">
                                            Unggah dokumentasi kerja dan keterangan kegiatan dalam format yang sudah disiapkan dengan rapi.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl bg-[#edf2ee] px-4 py-3 text-sm font-medium leading-6 text-[#29453f]">Riwayat laporan tersusun per periode</div>
                                    <div class="rounded-2xl bg-[#fff2ea] px-4 py-3 text-sm font-medium leading-6 text-[#b85f34]">Dokumentasi progres lebih mudah dibaca</div>
                                    <div class="px-4 py-3 text-sm font-medium leading-6 bg-white border rounded-2xl border-black/10 text-slate-700">Akses kerja lebih terarah</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tentang" class="px-4 py-16 sm:py-20 md:py-24">
            <div class="shell">
                <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                    <div>
                        <div class="eyebrow">
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-[#29453f]"></span>
                            Tentang SILAB
                        </div>
                        <h2 class="mt-5 max-w-xl text-3xl font-extrabold text-[#172126] sm:text-4xl md:mt-6 md:text-5xl">
                            Platform internal untuk menjaga proses pelaporan kegiatan tetap tertata.
                        </h2>
                        <div class="mt-6 section-line"></div>
                        <p class="max-w-xl mt-5 text-sm leading-7 text-slate-600 sm:text-base md:mt-6 md:text-lg md:leading-8">
                            SILAB dirancang untuk memudahkan pengumpulan laporan kegiatan bulanan, terutama saat tim perlu menyatukan dokumentasi pekerjaan, catatan lapangan, dan arsip periode dalam format yang konsisten.
                        </p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="card rounded-[24px] p-5 sm:p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#b85f34]">1. Input</p>
                            <h3 class="mt-3 text-2xl font-bold text-[#172126]">Catat kegiatan</h3>
                            <p class="mt-3 leading-7 text-slate-600">
                                Dokumentasi dan uraian pekerjaan dikirim dalam satu alur yang lebih jelas dan seragam.
                            </p>
                        </div>
                        <div class="card rounded-[24px] p-5 sm:p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#29453f]">2. Pantau</p>
                            <h3 class="mt-3 text-2xl font-bold text-[#172126]">Pantau perkembangan</h3>
                            <p class="mt-3 leading-7 text-slate-600">
                                Admin dapat melihat aktivitas terbaru dan memantau perkembangan laporan yang masuk dari waktu ke waktu.
                            </p>
                        </div>
                        <div class="card rounded-[24px] p-5 sm:p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">3. Arsip</p>
                            <h3 class="mt-3 text-2xl font-bold text-[#172126]">Arsipkan per periode</h3>
                            <p class="mt-3 leading-7 text-slate-600">
                                Laporan lebih mudah ditelusuri kembali saat dibutuhkan untuk rekap, validasi, atau pengecekan ulang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="px-4 pb-16 sm:pb-20 md:pb-24">
            <div class="shell">
                <div class="mb-10">
                    <div class="eyebrow">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-[#b85f34]"></span>
                        Fitur utama
                    </div>
                    <h2 class="mt-5 max-w-2xl text-3xl font-extrabold text-[#172126] sm:text-4xl md:mt-6 md:text-5xl">
                        Fitur-fitur inti yang mendukung proses pelaporan bulanan dengan lebih efisien.
                    </h2>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#172126] text-white">
                            <i class="text-xl ri-dashboard-3-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Dashboard ringkas</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Menampilkan ringkasan data penting agar proses pemantauan terasa lebih cepat dan fokus.
                        </p>
                    </article>

                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#29453f] text-white">
                            <i class="text-xl ri-image-2-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Foto progres kegiatan</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Dokumentasi before, process, dan after tersimpan dalam satu rangkaian kegiatan yang utuh.
                        </p>
                    </article>

                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#b85f34] text-white">
                            <i class="text-xl ri-folder-chart-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Riwayat per bulan</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Arsip laporan tersusun berdasarkan periode untuk memudahkan pencarian, rekap, dan peninjauan ulang.
                        </p>
                    </article>

                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff1e8] text-[#b85f34]">
                            <i class="text-xl ri-time-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Aktivitas tercatat</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Perubahan data dan aktivitas penting tetap tercatat melalui jejak waktu yang jelas.
                        </p>
                    </article>

                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#edf2ee] text-[#29453f]">
                            <i class="text-xl ri-shield-check-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Akses sesuai peran</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Admin dan user menggunakan area kerja yang disesuaikan dengan kebutuhan masing-masing.
                        </p>
                    </article>

                    <article class="card rounded-[24px] p-5 sm:rounded-[28px] sm:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black/5 text-[#172126]">
                            <i class="text-xl ri-smartphone-line"></i>
                        </div>
                        <h3 class="mt-5 text-2xl font-bold text-[#172126]">Responsif di berbagai perangkat</h3>
                        <p class="mt-4 leading-7 text-slate-600">
                            Tetap nyaman diakses dari desktop maupun perangkat mobile saat dibutuhkan di lapangan.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="px-4 pb-16 sm:pb-20 md:pb-24">
            <div class="shell">
                <div class="rounded-[28px] bg-[#172126] px-5 py-8 text-white shadow-[0_20px_60px_rgba(23,33,38,0.16)] sm:px-6 sm:py-10 md:rounded-[34px] md:px-10 md:py-12">
                    <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-white/60">Akses sistem</p>
                            <h2 class="max-w-3xl mt-4 text-3xl font-extrabold sm:text-4xl md:text-5xl">
                                Gunakan SILAB untuk mulai mengelola dan memantau laporan kegiatan dengan lebih rapi.
                            </h2>
                            <p class="max-w-2xl mt-5 text-sm leading-7 text-white/72 sm:text-base md:text-lg md:leading-8">
                                Halaman depan ini dirancang ringan agar pengguna dapat langsung masuk ke sistem tanpa menunggu elemen yang tidak diperlukan.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            <a href="{{ route('login') }}" class="btn btn-primary sm:w-auto">
                                <i class="text-lg ri-login-box-line"></i>
                                Masuk sekarang
                            </a>
                            <a href="#kontak" class="btn btn-secondary border-white/15 bg-white/10 text-white hover:bg-white hover:text-[#172126] sm:w-auto">
                                <i class="text-lg ri-customer-service-2-line"></i>
                                Hubungi admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="px-4 pb-8">
        <div class="shell">
            <div class="panel rounded-[24px] px-5 py-7 sm:px-6 sm:py-8 md:rounded-[30px] md:px-8">
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('img/logo-320.webp') }}" alt="Logo SILAB" width="56" height="56" class="object-contain w-12 h-12 p-1 bg-white rounded-2xl sm:h-14 sm:w-14" loading="lazy" decoding="async">
                            <div class="min-w-0">
                                <p class="text-xl font-extrabold text-[#172126] sm:text-2xl">SILAB</p>
                                <p class="text-sm text-slate-500">Sistem Laporan Bulanan</p>
                            </div>
                        </div>
                        <p class="max-w-xl mt-5 text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                            SILAB membantu pengelolaan dokumentasi kegiatan dan laporan bulanan agar lebih tertata, mudah dipantau, dan siap ditelusuri kembali saat diperlukan.
                        </p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Navigasi</p>
                            <div class="flex flex-col gap-3 mt-4 text-sm font-semibold text-slate-600">
                                <a href="#beranda" class="transition hover:text-[#172126]">Beranda</a>
                                <a href="#tentang" class="transition hover:text-[#172126]">Tentang</a>
                                <a href="#fitur" class="transition hover:text-[#172126]">Fitur</a>
                                <a href="{{ route('login') }}" class="transition hover:text-[#172126]">Login</a>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Kontak</p>
                            <div class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                                <p class="flex items-start gap-3">
                                    <i class="ri-map-pin-line mt-1 text-[#b85f34]"></i>
                                    <span>Jl. Budi Utomo No. 10, Ponorogo, Jawa Timur</span>
                                </p>
                                <p class="flex items-center gap-3">
                                    <i class="ri-mail-line text-[#b85f34]"></i>
                                    <span>ponorogo.sac@gmail.com</span>
                                </p>
                                <p class="flex items-center gap-3">
                                    <i class="ri-shield-user-line text-[#b85f34]"></i>
                                    <span>Akses saat ini digunakan untuk kebutuhan internal.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-5 mt-8 text-sm border-t border-black/10 text-slate-500 md:flex-row md:items-center md:justify-between">
                    <p>© {{ date('Y') }} SILAB. Sistem laporan kegiatan bulanan.</p>
                    <p>Tampilan dirancang ringan untuk akses yang lebih cepat.</p>
                </div>
            </div>
        </div>
    </footer>

    <button type="button" class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <i class="text-xl ri-arrow-up-line"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const backToTop = document.getElementById('backToTop');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            const closeMobileMenu = () => {
                if (!mobileMenu || !mobileMenuToggle) {
                    return;
                }

                mobileMenu.classList.remove('is-open');
                mobileMenuToggle.classList.remove('is-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            };

            const handleScrollState = () => {
                if (window.scrollY > 280) {
                    backToTop.classList.add('is-visible');
                } else {
                    backToTop.classList.remove('is-visible');
                }
            };

            document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
                anchor.addEventListener('click', (event) => {
                    const targetId = anchor.getAttribute('href');
                    const target = document.querySelector(targetId);

                    if (!target) {
                        return;
                    }

                    event.preventDefault();
                    target.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'start',
                    });

                    closeMobileMenu();
                });
            });

            if (mobileMenu && mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', () => {
                    const isOpen = mobileMenu.classList.toggle('is-open');
                    mobileMenuToggle.classList.toggle('is-open', isOpen);
                    mobileMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        closeMobileMenu();
                    }
                }, {
                    passive: true
                });
            }

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth',
                });
            });

            handleScrollState();
            window.addEventListener('scroll', handleScrollState, {
                passive: true
            });
        });
    </script>
</body>

</html>
