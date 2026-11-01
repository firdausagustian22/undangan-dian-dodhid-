<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Monitoring Undangan') | Dian & Dodhid</title>

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        },
                        gold: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                        cinzel: ['"Cinzel"', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome / Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased bg-slate-50/60 selection:bg-brand-500 selection:text-white">

    <!-- TOP NAVIGATION BAR -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Brand / Couple Name -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-rose-400 text-white flex items-center justify-center shadow-md shadow-brand-500/20">
                        <i class="fa-solid fa-ring text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-serif text-lg sm:text-xl font-bold tracking-tight text-slate-900">Dian & Dodhid</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1 animate-pulse"></span> Wedding
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 hidden sm:block">Dashboard Monitoring & RSVP Digital Invitation</p>
                    </div>
                </div>

                <!-- Navigation Tabs / Links -->
                <nav class="hidden md:flex items-center gap-1 bg-slate-100/80 p-1 rounded-xl border border-slate-200/60 text-xs font-medium text-slate-600">
                    <a href="{{ route('dashboard.index') }}" class="px-3.5 py-1.5 rounded-lg bg-white text-slate-900 font-semibold shadow-xs transition">
                        <i class="fa-solid fa-chart-pie mr-1.5 text-brand-500"></i> Dashboard
                    </a>
                    <a href="#guest-table-section" class="px-3.5 py-1.5 rounded-lg hover:text-slate-900 transition">
                        <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Data Tamu
                    </a>
                    <a href="#wishes-section" class="px-3.5 py-1.5 rounded-lg hover:text-slate-900 transition">
                        <i class="fa-solid fa-heart mr-1.5 text-slate-400"></i> Ucapan & Doa
                    </a>
                </nav>

                <!-- Actions Header -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Quick Add Guest Button -->
                    <button type="button" onclick="openAddGuestModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-brand-600 to-rose-500 hover:from-brand-700 hover:to-rose-600 text-white text-xs sm:text-sm font-semibold shadow-md shadow-brand-500/25 transition active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Tambah Tamu</span>
                    </button>

                    <!-- Preview Invitation Link -->
                    <a href="{{ route('home', ['to' => 'Tamu Undangan']) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition cursor-pointer" title="Lihat Tampilan Website Undangan">
                        <i class="fa-solid fa-external-link text-slate-500"></i>
                        <span class="hidden sm:inline">Lihat Web</span>
                    </a>

                    <!-- Logout Button -->
                    @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline-block" onsubmit="return confirm('Keluar dari sesi Admin?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs sm:text-sm font-semibold transition cursor-pointer" title="Keluar / Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">
        @if (session('success'))
            <div id="flash-toast" class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-xs transition-all duration-300">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
                <button onclick="document.getElementById('flash-toast').remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="font-serif font-bold text-slate-800">Dian & Dodhid Wedding</span>
                <span>•</span>
                <span>The Sacred Union & Eternal Love</span>
            </div>
            <div class="text-slate-400">
                &copy; {{ date('Y') }} Sistem Monitoring & Generator Undangan Digital
            </div>
        </div>
    </footer>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <!-- JAVASCRIPT HELPERS -->
    <script>
        // Global Toast Notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm transition-all duration-300 transform translate-y-4 opacity-0 ${
                type === 'success' 
                    ? 'bg-slate-900 text-white border-slate-800' 
                    : 'bg-rose-600 text-white border-rose-700'
            }`;

            const icon = type === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-rose-200';
            toast.innerHTML = `
                <i class="fa-solid ${icon} text-base"></i>
                <span class="font-medium">${message}</span>
            `;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });

            // Remove after 3.5s
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Copy text helper
        function copyText(text, successMsg = 'Tautan berhasil disalin ke clipboard!') {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast(successMsg);
                }).catch(() => {
                    fallbackCopyText(text, successMsg);
                });
            } else {
                fallbackCopyText(text, successMsg);
            }
        }

        function fallbackCopyText(text, successMsg) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                showToast(successMsg);
            } catch (err) {
                showToast('Gagal menyalin text', 'error');
            }
            document.body.removeChild(textarea);
        }
    </script>

    @stack('scripts')
</body>
</html>
