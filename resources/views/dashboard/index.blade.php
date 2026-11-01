@extends('layouts.app')

@section('title', 'Dashboard Monitoring Undangan Pernikahan Dian & Dodhid')

@section('content')

    <!-- ========================================== -->
    <!-- 1. HERO HEADER & KPI CARDS -->
    <!-- ========================================== -->
    <div class="space-y-6">
        <!-- Welcoming & Event Info -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-6 sm:p-8 shadow-xl">
            <!-- Decorative Background Patterns -->
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-semibold text-rose-200 border border-white/10 mb-3">
                        <i class="fa-solid fa-sparkles text-gold-400"></i> Wedding Reception & Monitoring Console
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white mb-2">
                        Pernikahan <span class="text-rose-300">Dian</span> & <span class="text-gold-300">Dodhid</span>
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                        Pantau penyebaran undangan digital, status pengunjung, konfirmasi kehadiran (RSVP), serta doa restu para tamu kehormatan secara real-time.
                    </p>
                </div>

                <!-- Fast Actions -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 shrink-0">
                    <button type="button" onclick="openAddGuestModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-brand-500 to-rose-600 hover:from-brand-600 hover:to-rose-700 text-white text-xs sm:text-sm font-semibold shadow-lg shadow-brand-500/30 transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-user-plus"></i> Tambah Tamu Baru
                    </button>
                    <a href="{{ route('dashboard.guests.export') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold border border-white/15 transition flex items-center gap-2 cursor-pointer" title="Download data tamu format Excel/CSV">
                        <i class="fa-solid fa-file-excel text-emerald-400"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 MAIN KPI CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- KPI 1: TOTAL TAMU -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Tamu</span>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fa-solid fa-address-book text-base"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ number_format($totalGuests) }}</span>
                    <span class="text-xs font-medium text-slate-500">tamu terdaftar</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <span>VIP: <b class="text-slate-800">{{ $categoriesStats['VIP']['total'] ?? 0 }}</b></span>
                    <span>Keluarga: <b class="text-slate-800">{{ $categoriesStats['Keluarga']['total'] ?? 0 }}</b></span>
                    <span>Teman: <b class="text-slate-800">{{ $categoriesStats['Teman']['total'] ?? 0 }}</b></span>
                </div>
            </div>

            <!-- KPI 2: UNDANGAN DIBUKA (UNIQUE VIEWS) -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Undangan Dibuka</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-brand-600 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fa-solid fa-envelope-open-text text-base"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-brand-600">{{ number_format($openedGuests) }}</span>
                    <span class="text-xs font-medium text-slate-500">tamu ({{ $openedPercentage }}%)</span>
                </div>
                <!-- Progress bar -->
                <div class="w-full bg-slate-100 rounded-full h-2 mb-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-brand-500 to-rose-400 h-2 rounded-full transition-all duration-500" style="width: {{ $openedPercentage }}%"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>Belum dibuka: <b class="text-slate-700">{{ $unopenedGuests }}</b></span>
                    <span>Total Klik: <b class="text-slate-700">{{ $totalViews }}x</b></span>
                </div>
            </div>

            <!-- KPI 3: KONFIRMASI RSVP -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Konfirmasi RSVP</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fa-solid fa-calendar-check text-base"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-emerald-600">{{ number_format($attendingGuests) }}</span>
                    <span class="text-xs font-medium text-slate-500">pasti hadir</span>
                </div>
                <div class="grid grid-cols-3 gap-1 pt-2 border-t border-slate-100 text-[11px] text-center">
                    <div class="bg-emerald-50/80 rounded-lg py-1 text-emerald-700 font-medium">
                        Hadir: <b>{{ $attendingGuests }}</b>
                    </div>
                    <div class="bg-rose-50/80 rounded-lg py-1 text-rose-700 font-medium">
                        Tidak: <b>{{ $declinedGuests }}</b>
                    </div>
                    <div class="bg-amber-50/80 rounded-lg py-1 text-amber-700 font-medium">
                        Ragu: <b>{{ $tentativeGuests }}</b>
                    </div>
                </div>
            </div>

            <!-- KPI 4: ESTIMASI TOTAL PAX / HADIRIN -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Estimasi Hadirin (Pax)</span>
                    <div class="w-10 h-10 rounded-xl bg-gold-50 text-gold-600 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fa-solid fa-users-viewfinder text-base"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-gold-600">{{ number_format($totalPax) }}</span>
                    <span class="text-xs font-medium text-slate-500">total pax katering</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <span>Belum konfirmasi: <b class="text-slate-800">{{ $pendingGuests }} tamu</b></span>
                    <span class="text-gold-700 font-semibold"><i class="fa-solid fa-utensils mr-1"></i> Food Prep</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. ANALYTICS ROW & GENERATOR FORM -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- DONUT CHART & RSVP SUMMARY (1 COL) -->
        <div class="glass-card rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-brand-500"></i> Persentase RSVP
                    </h2>
                    <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ $attendingGuests + $declinedGuests + $tentativeGuests }} / {{ $totalGuests }} Respon
                    </span>
                </div>

                <!-- Donut Chart Canvas -->
                <div class="relative w-48 h-48 mx-auto my-2">
                    <canvas id="rsvpDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black text-slate-800">{{ $totalPax }}</span>
                        <span class="text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Pax Hadir</span>
                    </div>
                </div>

                <!-- Custom Legend -->
                <div class="space-y-2 mt-4 text-xs">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="font-medium text-emerald-950">Pasti Hadir</span>
                        </div>
                        <span class="font-bold text-emerald-700">{{ $attendingGuests }} Tamu ({{ $totalPax }} Pax)</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-rose-50/70 border border-rose-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="font-medium text-rose-950">Berhalangan Hadir</span>
                        </div>
                        <span class="font-bold text-rose-700">{{ $declinedGuests }} Tamu</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="font-medium text-amber-950">Masih Ragu-ragu</span>
                        </div>
                        <span class="font-bold text-amber-700">{{ $tentativeGuests }} Tamu</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                            <span class="font-medium text-slate-700">Belum Konfirmasi</span>
                        </div>
                        <span class="font-bold text-slate-600">{{ $pendingGuests }} Tamu</span>
                    </div>
                </div>
            </div>

            <!-- Live Activity Mini Feed -->
            <div class="mt-6 pt-4 border-t border-slate-100">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5">
                    <i class="fa-solid fa-clock-rotate-left mr-1"></i> Aktivitas Terkini
                </span>
                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                    @forelse($activities as $act)
                        <div class="flex items-start gap-2.5 text-xs text-slate-600">
                            <div class="w-5 h-5 rounded-full mt-0.5 shrink-0 flex items-center justify-center text-[10px] {{ $act->type === 'view' ? 'bg-rose-100 text-rose-600' : ($act->type === 'rsvp' ? 'bg-emerald-100 text-emerald-600' : 'bg-gold-100 text-gold-700') }}">
                                <i class="fa-solid {{ $act->type === 'view' ? 'fa-eye' : ($act->type === 'rsvp' ? 'fa-check' : 'fa-heart') }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="truncate leading-tight text-slate-700 font-medium">{{ $act->description }}</p>
                                <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada aktivitas baru.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- GENERATOR & PENYEBARAN UNDANGAN CEPAT (2 COLS) -->
        <div class="lg:col-span-2 glass-card rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-brand-500"></i> Generator & Penyebaran Undangan WhatsApp
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Input nama tamu, dapatkan slug/link personal otomatis, dan langsung kirim via WhatsApp.</p>
                    </div>
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fa-brands fa-whatsapp mr-1.5 text-emerald-600 text-sm"></i> Auto WA Message
                    </span>
                </div>

                <!-- Fast Generator Form -->
                <form id="fast-guest-form" action="{{ route('dashboard.guests.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4">
                        <!-- Nama Tamu -->
                        <div class="sm:col-span-6">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Nama Tamu <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="gen-name" 
                                required 
                                placeholder="Contoh: Budi Santoso, S.Kom & Keluarga"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                                oninput="updateGeneratorPreview()"
                            >
                        </div>

                        <!-- Kategori -->
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                            <select 
                                name="category" 
                                id="gen-category"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                                onchange="updateGeneratorPreview()"
                            >
                                <option value="VIP">⭐ VIP</option>
                                <option value="Keluarga">👨‍👩‍👧‍👦 Keluarga</option>
                                <option value="Teman" selected>🤝 Teman</option>
                                <option value="Rekan Kerja">💼 Rekan Kerja</option>
                            </select>
                        </div>

                        <!-- No. WhatsApp -->
                        <div class="sm:col-span-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="gen-phone" 
                                placeholder="08123456789"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                                oninput="updateGeneratorPreview()"
                            >
                        </div>
                    </div>

                    <!-- Live Link Preview & Message Preview Box -->
                    <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/90 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                                <i class="fa-solid fa-link text-brand-500"></i>
                                <span>Link Personal:</span>
                                <code id="preview-link-text" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-brand-600 font-mono text-[11px] truncate max-w-xs sm:max-w-md">
                                    {{ url('/to/nama-tamu') }}
                                </code>
                            </div>
                            <button type="button" onclick="copyCurrentPreviewLink()" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-900 font-semibold cursor-pointer shrink-0">
                                <i class="fa-regular fa-copy"></i> Salin Link
                            </button>
                        </div>

                        <!-- Template Pesan WhatsApp Preview -->
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                <i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> Preview Template Pesan WhatsApp:
                            </span>
                            <div id="preview-wa-message" class="p-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-sans whitespace-pre-line leading-relaxed shadow-xs max-h-36 overflow-y-auto">
Kepada Yth. *Budi Santoso*,

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir di momen istimewa dan bahagia pernikahan kami:

💍 *Dian & Dodhid*

Buka undangan digital Anda melalui tautan resmi berikut:
👉 {{ url('/to/budi-santoso') }}

Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu kepada kami berdua.

Terima kasih hangat,
*Dian & Dodhid*
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                        <div class="text-xs text-slate-500">
                            <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> Data tamu langsung tersinkron ke tabel monitoring real-time
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs sm:text-sm font-semibold transition active:scale-95 cursor-pointer shadow-xs flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan ke Database
                            </button>
                            <button type="button" onclick="saveAndSendWhatsApp()" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition active:scale-95 cursor-pointer flex items-center gap-2">
                                <i class="fa-brands fa-whatsapp text-base"></i> Simpan & Kirim WA
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. TABEL MONITORING TAMU (GUEST TRACKING) -->
    <!-- ========================================== -->
    <div id="guest-table-section" class="glass-card rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <!-- Table Header & Filter Toolbar -->
        <div class="p-5 sm:p-6 border-b border-slate-200/80 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-users text-brand-500"></i> Tabel Monitoring Tamu Undangan
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Lacak status pembukaan undangan, konfirmasi kehadiran, dan bagikan tautan secara terorganisir.</p>
                </div>

                <!-- Stats Badges -->
                <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        Total: <b>{{ $guests->total() }}</b>
                    </span>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Hadir: <b>{{ $attendingGuests }}</b>
                    </span>
                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        Dibuka: <b>{{ $openedGuests }}</b>
                    </span>
                </div>
            </div>

            <!-- Filters & Search Bar Form -->
            <form action="{{ route('dashboard.index') }}#guest-table-section" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">
                <!-- Search Input -->
                <div class="sm:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama tamu, nomor WA, atau catatan..."
                        class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white"
                    >
                </div>

                <!-- Filter Kategori -->
                <div class="sm:col-span-2">
                    <select name="category" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white">
                        <option value="all">Semua Kategori</option>
                        <option value="VIP" {{ request('category') === 'VIP' ? 'selected' : '' }}>⭐ VIP</option>
                        <option value="Keluarga" {{ request('category') === 'Keluarga' ? 'selected' : '' }}>👨‍👩‍👧‍👦 Keluarga</option>
                        <option value="Teman" {{ request('category') === 'Teman' ? 'selected' : '' }}>🤝 Teman</option>
                        <option value="Rekan Kerja" {{ request('category') === 'Rekan Kerja' ? 'selected' : '' }}>💼 Rekan Kerja</option>
                    </select>
                </div>

                <!-- Filter Status RSVP -->
                <div class="sm:col-span-2">
                    <select name="rsvp" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white">
                        <option value="all">Semua Status RSVP</option>
                        <option value="attending" {{ request('rsvp') === 'attending' ? 'selected' : '' }}>✅ Hadir</option>
                        <option value="declined" {{ request('rsvp') === 'declined' ? 'selected' : '' }}>❌ Tidak Hadir</option>
                        <option value="tentative" {{ request('rsvp') === 'tentative' ? 'selected' : '' }}>❓ Ragu-ragu</option>
                        <option value="pending" {{ request('rsvp') === 'pending' ? 'selected' : '' }}>⏳ Belum Konfirmasi</option>
                    </select>
                </div>

                <!-- Filter Status Dibuka -->
                <div class="sm:col-span-2">
                    <select name="view_status" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500 bg-white">
                        <option value="all">Semua Status Dilihat</option>
                        <option value="opened" {{ request('view_status') === 'opened' ? 'selected' : '' }}>👀 Sudah Dibuka</option>
                        <option value="unopened" {{ request('view_status') === 'unopened' ? 'selected' : '' }}>🔒 Belum Dibuka</option>
                    </select>
                </div>

                <!-- Reset / Filter Button -->
                <div class="sm:col-span-1 flex items-center gap-1">
                    <button type="submit" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer flex items-center justify-center" title="Terapkan Filter">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'category', 'rsvp', 'view_status']))
                        <a href="{{ route('dashboard.index') }}#guest-table-section" class="py-2.5 px-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-semibold transition flex items-center justify-center" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Nama Tamu & No. WA</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Link Undangan</th>
                        <th class="py-3.5 px-4">Status Dilihat</th>
                        <th class="py-3.5 px-4">Konfirmasi RSVP</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Nama Tamu & Kontak -->
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar Initials -->
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 {{ $guest->category === 'VIP' ? 'bg-gold-100 text-gold-800' : ($guest->category === 'Keluarga' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ strtoupper(substr($guest->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 group-hover:text-brand-600 transition truncate max-w-xs">
                                            {{ $guest->name }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                                            @if($guest->phone)
                                                <span><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i>{{ $guest->phone }}</span>
                                            @else
                                                <span class="text-slate-400 italic">Tanpa No. WA</span>
                                            @endif
                                            @if($guest->notes)
                                                <span>•</span>
                                                <span class="truncate max-w-[120px] text-slate-400" title="{{ $guest->notes }}">
                                                    <i class="fa-regular fa-note-sticky mr-1"></i>{{ $guest->notes }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($guest->category === 'VIP')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gold-100 text-gold-800 border border-gold-200">
                                        <i class="fa-solid fa-crown text-[10px] text-gold-600"></i> VIP
                                    </span>
                                @elseif($guest->category === 'Keluarga')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <i class="fa-solid fa-heart text-[10px] text-rose-600"></i> Keluarga
                                    </span>
                                @elseif($guest->category === 'Rekan Kerja')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        <i class="fa-solid fa-briefcase text-[10px] text-indigo-600"></i> Rekan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <i class="fa-solid fa-user-group text-[10px] text-slate-500"></i> Teman
                                    </span>
                                @endif
                            </td>

                            <!-- Link Personal (Tombol Copy Link & Open) -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        onclick="copyText('{{ $guest->personal_link }}', 'Tautan untuk {{ addslashes($guest->name) }} disalin!')" 
                                        class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 font-medium text-xs border border-slate-200 transition cursor-pointer flex items-center gap-1.5"
                                        title="Salin Link Undangan"
                                    >
                                        <i class="fa-regular fa-copy text-slate-400 group-hover:text-brand-500"></i>
                                        <span>Copy Link</span>
                                    </button>
                                    <a 
                                        href="{{ $guest->personal_link }}" 
                                        target="_blank" 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition"
                                        title="Buka Undangan"
                                    >
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                </div>
                            </td>

                            <!-- Status Dilihat (Opened Status) -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($guest->opened_at)
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Dibuka
                                        </span>
                                        <div class="text-[10px] text-slate-500 mt-0.5">
                                            {{ $guest->opened_at->diffForHumans() }} ({{ $guest->views_count }}x)
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Belum Dibuka
                                    </span>
                                @endif
                            </td>

                            <!-- Konfirmasi RSVP -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($guest->rsvp_status === 'attending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Hadir ({{ $guest->pax }} Pax)
                                        </span>
                                    @elseif($guest->rsvp_status === 'declined')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800">
                                            <i class="fa-solid fa-circle-xmark text-rose-600"></i> Tidak Hadir
                                        </span>
                                    @elseif($guest->rsvp_status === 'tentative')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-800">
                                            <i class="fa-solid fa-circle-question text-amber-600"></i> Ragu-ragu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-500">
                                            <i class="fa-regular fa-clock text-slate-400"></i> Menunggu
                                        </span>
                                    @endif

                                    <!-- Quick Change Dropdown -->
                                    <button 
                                        type="button" 
                                        onclick="openQuickRsvpModal({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ $guest->rsvp_status }}', {{ $guest->pax }})"
                                        class="text-slate-400 hover:text-slate-700 text-xs p-1 cursor-pointer"
                                        title="Ubah Konfirmasi RSVP"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Aksi (Kirim WA Ulang, Edit, Hapus) -->
                            <td class="py-4 px-4 sm:px-6 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1">
                                    <!-- Kirim WhatsApp Button -->
                                    <button 
                                        type="button" 
                                        onclick="openShareWhatsAppModal({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ $guest->phone ?? '' }}', '{{ $guest->personal_link }}', '{{ addslashes(str_replace("\n", '\n', $guest->whatsapp_share_message)) }}')" 
                                        class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition cursor-pointer"
                                        title="Kirim Undangan via WhatsApp"
                                    >
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                    </button>

                                    <!-- Edit Button -->
                                    <button 
                                        type="button" 
                                        onclick="openEditGuestModal({{ json_encode($guest) }})" 
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer"
                                        title="Edit Data Tamu"
                                    >
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('dashboard.guests.destroy', $guest) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ addslashes($guest->name) }} dari daftar undangan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold transition cursor-pointer"
                                            title="Hapus Tamu"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-3">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Tidak ada data tamu ditemukan.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau tambah tamu baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination -->
        @if($guests->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $guests->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $guests->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $guests->total() }}</span> tamu
                </div>
                <div>
                    {{ $guests->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- 4. SEKSI UCAPAN & DOA (WISHES MONITOR) -->
    <!-- ========================================== -->
    <div id="wishes-section" class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-envelope-open-heart text-brand-500"></i> Seksi Ucapan & Doa Restu (Wishes Monitor)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar doa tulus dan restu yang disampaikan para tamu untuk pernikahan Dian & Dodhid.</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                <span class="px-3 py-1 rounded-full bg-rose-50 text-brand-700 border border-rose-200">
                    <i class="fa-solid fa-heart text-brand-500 mr-1"></i> {{ $totalWishes }} Ucapan Diterima
                </span>
            </div>
        </div>

        <!-- Wishes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($wishes as $wish)
                <div class="relative flex flex-col justify-between p-5 rounded-2xl border transition hover:shadow-md {{ $wish->is_pinned ? 'bg-gradient-to-b from-gold-50/80 to-white border-gold-200 ring-1 ring-gold-300' : 'bg-white border-slate-200/80' }}">
                    <!-- Top Ribbon / Actions -->
                    <div>
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <span class="font-bold text-sm text-slate-900 block truncate" title="{{ $wish->name }}">{{ $wish->name }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $wish->attendance === 'attending' ? 'bg-emerald-100 text-emerald-800' : ($wish->attendance === 'declined' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $wish->attendance === 'attending' ? 'Hadir' : ($wish->attendance === 'declined' ? 'Tidak Hadir' : 'Ragu-ragu') }}
                                    </span>
                                    @if($wish->relationship)
                                        <span class="text-[10px] text-slate-500 truncate">• {{ $wish->relationship }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Pin & Delete Actions -->
                            <div class="flex items-center gap-1">
                                <form action="{{ route('dashboard.wishes.togglePin', $wish) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-1 rounded-md text-xs {{ $wish->is_pinned ? 'text-gold-600 bg-gold-100' : 'text-slate-300 hover:text-slate-600' }}" title="{{ $wish->is_pinned ? 'Lepas Sematan' : 'Sematkan di Atas' }}">
                                        <i class="fa-solid fa-thumbtack"></i>
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.wishes.destroy', $wish) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus ucapan dari {{ addslashes($wish->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-md text-xs text-slate-300 hover:text-rose-600" title="Hapus Ucapan">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Wish Content / Message -->
                        <div class="relative my-3">
                            <i class="fa-solid fa-quote-left text-brand-200 text-xs mr-1 opacity-70"></i>
                            <p class="text-xs text-slate-700 leading-relaxed italic font-serif inline">
                                {{ $wish->message }}
                            </p>
                            <i class="fa-solid fa-quote-right text-brand-200 text-xs ml-1 opacity-70"></i>
                        </div>
                    </div>

                    <!-- Footer / Timestamp -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1"></i> {{ $wish->created_at->diffForHumans() }}</span>
                        @if($wish->is_pinned)
                            <span class="text-gold-600 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-thumbtack text-[9px]"></i> Disematkan
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400">
                    <p class="text-xs">Belum ada ucapan dan doa yang masuk dari tamu.</p>
                </div>
            @endforelse
        </div>

        @if($wishes->hasPages())
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                {{ $wishes->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- MODAL 1: TAMBAH TAMU CEPAT -->
    <!-- ========================================== -->
    <div id="add-guest-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Tambah Tamu Undangan Baru</h3>
                </div>
                <button type="button" onclick="closeAddGuestModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('dashboard.guests.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Tamu <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        placeholder="Contoh: Budi Santoso & Partner"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            <option value="VIP">⭐ VIP</option>
                            <option value="Keluarga">👨‍👩‍👧‍👦 Keluarga</option>
                            <option value="Teman" selected>🤝 Teman</option>
                            <option value="Rekan Kerja">💼 Rekan Kerja</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                        <input 
                            type="text" 
                            name="phone" 
                            placeholder="08123456789"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Pax Hadir</label>
                        <input 
                            type="number" 
                            name="pax" 
                            value="1" 
                            min="1" 
                            max="10"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                        <input 
                            type="text" 
                            name="notes" 
                            placeholder="Teman SMA / Meja 3"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                        >
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeAddGuestModal()" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 text-xs font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition shadow-md shadow-brand-500/20 cursor-pointer">
                        Simpan Tamu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: EDIT DATA TAMU -->
    <!-- ========================================== -->
    <div id="edit-guest-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Edit Data Tamu</h3>
                </div>
                <button type="button" onclick="closeEditGuestModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="edit-guest-form" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Tamu</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit-name" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category" id="edit-category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            <option value="VIP">⭐ VIP</option>
                            <option value="Keluarga">👨‍👩‍👧‍👦 Keluarga</option>
                            <option value="Teman">🤝 Teman</option>
                            <option value="Rekan Kerja">💼 Rekan Kerja</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="edit-phone" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kehadiran</label>
                        <select name="rsvp_status" id="edit-rsvp_status" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            <option value="pending">Menunggu Konfirmasi</option>
                            <option value="attending">Hadir</option>
                            <option value="declined">Tidak Hadir</option>
                            <option value="tentative">Ragu-ragu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Pax</label>
                        <input 
                            type="number" 
                            name="pax" 
                            id="edit-pax" 
                            min="0" 
                            max="20"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                    <input 
                        type="text" 
                        name="notes" 
                        id="edit-notes" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
                    >
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeEditGuestModal()" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 text-xs font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-semibold transition cursor-pointer">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 3: SHARE WHATSAPP POPUP -->
    <!-- ========================================== -->
    <div id="share-wa-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50/60">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-base">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Kirim Undangan via WhatsApp</h3>
                        <p class="text-[11px] text-slate-500">Pesan ramah dan personal untuk tamu</p>
                    </div>
                </div>
                <button type="button" onclick="closeShareWhatsAppModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between text-xs bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div>
                        <span class="text-slate-500 block">Penerima:</span>
                        <b id="wa-guest-name" class="text-slate-800 text-sm">Budi Santoso</b>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-500 block">Nomor WhatsApp:</span>
                        <b id="wa-guest-phone" class="text-emerald-700 font-mono">08123456789</b>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Pesan Undangan WhatsApp
                    </label>
                    <textarea 
                        id="wa-message-text" 
                        rows="7" 
                        class="w-full p-3 rounded-xl border border-slate-200 text-xs text-slate-700 font-sans focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 bg-slate-50/50 leading-relaxed"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button" onclick="copyCurrentWaLink()" class="flex-1 sm:flex-none px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-regular fa-copy"></i> Salin Link
                        </button>
                        <button type="button" onclick="copyCurrentWaMessage()" class="flex-1 sm:flex-none px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-regular fa-clipboard"></i> Salin Teks
                        </button>
                    </div>

                    <a id="btn-open-wa" href="#" target="_blank" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition shadow-md shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-base"></i> Buka WhatsApp Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 4: QUICK RSVP UPDATE -->
    <!-- ========================================== -->
    <div id="quick-rsvp-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-900 text-sm">Ubah Konfirmasi RSVP</h3>
                <button type="button" onclick="closeQuickRsvpModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="quick-rsvp-form" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <span class="text-xs text-slate-500 block">Nama Tamu:</span>
                    <b id="quick-rsvp-name" class="text-slate-900 text-sm"></b>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kehadiran</label>
                    <select name="rsvp_status" id="quick-rsvp-status" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                        <option value="pending">Menunggu Konfirmasi</option>
                        <option value="attending">Hadir</option>
                        <option value="declined">Tidak Hadir</option>
                        <option value="tentative">Ragu-ragu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Pax</label>
                    <input type="number" name="pax" id="quick-rsvp-pax" min="0" max="20" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeQuickRsvpModal()" class="px-3.5 py-2 rounded-xl text-slate-600 text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ===============================================
    // CHART.JS: RSVP DONUT CHART
    // ===============================================
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('rsvpDonutChart').getContext('2d');
        const attending = {{ $attendingGuests }};
        const declined = {{ $declinedGuests }};
        const tentative = {{ $tentativeGuests }};
        const pending = {{ $pendingGuests }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Tidak Hadir', 'Ragu-ragu', 'Belum Konfirmasi'],
                datasets: [{
                    data: [attending, declined, tentative, pending],
                    backgroundColor: [
                        '#10b981', // emerald-500
                        '#f43f5e', // brand/rose-500
                        '#f59e0b', // amber-500
                        '#cbd5e1'  // slate-300
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const val = context.raw || 0;
                                const total = attending + declined + tentative + pending;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} Tamu (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    });

    // ===============================================
    // GENERATOR PREVIEW & WHATSAPP GENERATION
    // ===============================================
    const appBaseUrl = "{{ url('/') }}";
    let currentGeneratedSlug = 'budi-santoso';
    let currentGeneratedLink = appBaseUrl + '/to/' + currentGeneratedSlug;

    function slugify(text) {
        return text.toString().toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    }

    function updateGeneratorPreview() {
        const nameInput = document.getElementById('gen-name').value.trim();
        const phoneInput = document.getElementById('gen-phone').value.trim();
        const displayName = nameInput || 'Budi Santoso';
        currentGeneratedSlug = slugify(nameInput) || 'budi-santoso';
        currentGeneratedLink = `${appBaseUrl}/to/${currentGeneratedSlug}`;

        // Update Link Text
        document.getElementById('preview-link-text').textContent = currentGeneratedLink;

        // Update Message Template
        const template = `Kepada Yth. *${displayName}*,\n\n` +
            `Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir di momen istimewa dan bahagia pernikahan kami:\n\n` +
            `💍 *Dian & Dodhid*\n\n` +
            `Buka undangan digital Anda melalui tautan resmi berikut:\n` +
            `👉 ${currentGeneratedLink}\n\n` +
            `Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu kepada kami berdua.\n\n` +
            `Terima kasih hangat,\n` +
            `*Dian & Dodhid*`;

        document.getElementById('preview-wa-message').textContent = template;
    }

    function copyCurrentPreviewLink() {
        copyText(currentGeneratedLink, 'Tautan personal berhasil disalin!');
    }

    function saveAndSendWhatsApp() {
        const form = document.getElementById('fast-guest-form');
        const name = document.getElementById('gen-name').value.trim();
        const phone = document.getElementById('gen-phone').value.trim();

        if (!name) {
            alert('Mohon isi nama tamu terlebih dahulu!');
            document.getElementById('gen-name').focus();
            return;
        }

        // Submit via fetch to get created guest data and open WhatsApp immediately
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Tamu berhasil disimpan! Membuka WhatsApp...');
                // Open WhatsApp link
                window.open(data.whatsapp_url, '_blank');
                // Reload dashboard after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(err => {
            form.submit();
        });
    }

    // ===============================================
    // MODAL HANDLERS
    // ===============================================
    function openAddGuestModal() {
        document.getElementById('add-guest-modal').classList.remove('hidden');
    }
    function closeAddGuestModal() {
        document.getElementById('add-guest-modal').classList.add('hidden');
    }

    function openEditGuestModal(guest) {
        document.getElementById('edit-name').value = guest.name;
        document.getElementById('edit-category').value = guest.category;
        document.getElementById('edit-phone').value = guest.phone || '';
        document.getElementById('edit-rsvp_status').value = guest.rsvp_status;
        document.getElementById('edit-pax').value = guest.pax;
        document.getElementById('edit-notes').value = guest.notes || '';

        const form = document.getElementById('edit-guest-form');
        form.action = `/dashboard/guests/${guest.id}`;

        document.getElementById('edit-guest-modal').classList.remove('hidden');
    }
    function closeEditGuestModal() {
        document.getElementById('edit-guest-modal').classList.add('hidden');
    }

    // WhatsApp Share Modal
    let activeShareLink = '';
    function openShareWhatsAppModal(id, name, phone, link, message) {
        activeShareLink = link;
        document.getElementById('wa-guest-name').textContent = name;
        document.getElementById('wa-guest-phone').textContent = phone || 'Nomor tidak dicatat';
        document.getElementById('wa-message-text').value = message.replace(/\\n/g, '\n');

        // Form clean phone
        let cleanPhone = (phone || '').replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.substring(1);
        } else if (cleanPhone.startsWith('8')) {
            cleanPhone = '62' + cleanPhone;
        }

        const encodedMsg = encodeURIComponent(message.replace(/\\n/g, '\n'));
        const waUrl = cleanPhone 
            ? `https://api.whatsapp.com/send?phone=${cleanPhone}&text=${encodedMsg}`
            : `https://api.whatsapp.com/send?text=${encodedMsg}`;

        document.getElementById('btn-open-wa').href = waUrl;
        document.getElementById('share-wa-modal').classList.remove('hidden');
    }
    function closeShareWhatsAppModal() {
        document.getElementById('share-wa-modal').classList.add('hidden');
    }

    function copyCurrentWaLink() {
        copyText(activeShareLink, 'Tautan undangan disalin!');
    }
    function copyCurrentWaMessage() {
        const text = document.getElementById('wa-message-text').value;
        copyText(text, 'Teks pesan WhatsApp disalin!');
    }

    // Quick RSVP Modal
    function openQuickRsvpModal(id, name, status, pax) {
        document.getElementById('quick-rsvp-name').textContent = name;
        document.getElementById('quick-rsvp-status').value = status;
        document.getElementById('quick-rsvp-pax').value = pax;

        const form = document.getElementById('quick-rsvp-form');
        form.action = `/dashboard/guests/${id}/rsvp`;

        document.getElementById('quick-rsvp-modal').classList.remove('hidden');
    }
    function closeQuickRsvpModal() {
        document.getElementById('quick-rsvp-modal').classList.add('hidden');
    }
</script>
@endpush
