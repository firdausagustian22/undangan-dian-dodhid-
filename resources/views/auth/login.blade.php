<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin • Dian & Dodhid Wedding</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-slate-950 via-slate-900 to-stone-950 text-slate-100 relative overflow-hidden selection:bg-brand-500 selection:text-white">

    <!-- Ambient Glowing Orbs -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-gold-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 space-y-6">

        <!-- Card Container -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-7 sm:p-9 shadow-2xl border border-white/20 text-slate-800 space-y-6">
            
            <!-- Header Brand -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-rose-400 text-white flex items-center justify-center mx-auto shadow-lg shadow-brand-500/30 text-2xl">
                    <i class="fa-solid fa-ring"></i>
                </div>
                <div>
                    <span class="font-cinzel text-[11px] tracking-[0.25em] uppercase text-gold-600 font-bold block">Admin Monitoring Portal</span>
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-slate-900">Dian & Dodhid</h1>
                </div>
                <p class="text-xs text-slate-500 max-w-xs mx-auto">
                    Masuk untuk mengelola data tamu, menyebarkan undangan WhatsApp, dan memantau RSVP.
                </p>
            </div>

            <!-- Flash Error / Success Messages -->
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
                    <div class="flex items-center gap-2 font-bold text-rose-700">
                        <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                        <span>Gagal Masuk</span>
                    </div>
                    <ul class="list-disc list-inside pl-1 text-[11px] text-rose-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email / Username Input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="login-input">
                        Email atau Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input 
                            type="text" 
                            name="login" 
                            id="login-input" 
                            value="{{ old('login', 'admin@diandodhid.com') }}" 
                            required 
                            autocomplete="username" 
                            placeholder="admin@diandodhid.com"
                            class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="password-input">
                        Kata Sandi (Password)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password-input" 
                            value="admin123" 
                            required 
                            autocomplete="current-password" 
                            placeholder="••••••••"
                            class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                        >
                            <i id="eye-icon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                        <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                        <span>Ingat Saya</span>
                    </label>
                    <span class="text-[11px] text-slate-400">Aman & Terenkripsi</span>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-rose-500 hover:from-brand-700 hover:to-rose-600 text-white font-bold text-sm shadow-lg shadow-brand-500/30 transition active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-right-to-bracket text-base"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Quick Auto-fill Hint Banner -->
            <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="font-bold flex items-center gap-1.5 text-amber-800">
                        <i class="fa-solid fa-key text-gold-600"></i> Akun Login Default:
                    </span>
                    <button type="button" onclick="fillDefaultCredentials()" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700 underline cursor-pointer">
                        Gunakan Akun Ini
                    </button>
                </div>
                <div class="text-[11px] text-amber-800 font-mono space-y-0.5">
                    <div>Email: <b>admin@diandodhid.com</b></div>
                    <div>Password: <b>admin123</b></div>
                </div>
            </div>

            <!-- Footer Return Link -->
            <div class="pt-2 text-center text-xs text-slate-500 border-t border-slate-100">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-brand-600 font-semibold transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Halaman Undangan</span>
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Dian & Dodhid Wedding • Sacred Union
        </p>

    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function fillDefaultCredentials() {
            document.getElementById('login-input').value = 'admin@diandodhid.com';
            document.getElementById('password-input').value = 'admin123';
        }
    </script>
</body>
</html>
