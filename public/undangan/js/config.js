/* =====================================================================
   KONFIGURASI UNDANGAN — ART JAWA BIRU 3
   ---------------------------------------------------------------------
   Semua teks, tanggal, foto, rekening, musik, dan link ada di sini.
   Ganti data di file ini saja; index.html / css / script tidak perlu
   disentuh.

   Baris baru di dalam teks ditulis dengan "\n".
   Penanda [GANTI] = data contoh dari template referensi yang wajib
   Anda ganti dengan data asli.
   ===================================================================== */

window.weddingData = {

  /* ---------- Meta halaman (judul tab & pratinjau link) ---------- */
  meta: {
    title: "The Wedding of Dodhid & Putri",
    description: "Undangan pernikahan Dodhid & Putri",
    ogImage: "assets/images/ref/g15.JPG"
  },

  /* ---------- Nama tamu: https://domain.com/?to=Nama+Tamu ---------- */
  guest: {
    urlParam: "to",
    fallbackName: "Nama Tamu",
    greeting: "Kepada Bapak/Ibu/Saudara/i",
    place: "Di Tempat",
    invitation: "Tanpa mengurangi rasa hormat, kami mengundang Anda\nuntuk hadir di hari bahagia kami"   // teks kecil di cover
  },

  /* ---------- Pasangan ---------- */
  couple: {
    names: "Dodhid & Putri",                 // cover, hero, penutup
    eyebrow: "The Wedding Of",
    displayDate: "Senin, 16 November 2026",   // teks tanggal di hero
    hashtag: "#DodhidDanPutri",
    coverPhoto: "assets/images/ref/g15.JPG"   // foto kartu cover (arch frame tengah)
  },

  // Urutan sesuai referensi: mempelai wanita dulu, lalu pria.
  bride: {
    nickname: "Putri",
    name: "Putri Yana Primadani",
    parents: "Putri dari Bapak Ngatilan Edi Purwanto\n& Ibu Yanah",
    instagram: "https://www.instagram.com/pxtrynnh/",
    photo: "assets/images/ref/g14.JPG"
  },
  groom: {
    nickname: "Dodhid",
    name: "Dodhid Gilang Fredita",
    parents: "Putra dari Bapak Ikhsanul Muslim(Paidi) \n& Ibu Narulita",
    instagram: "https://www.instagram.com/hallodod",
    photo: "assets/images/ref/g12.JPG"
  },

  opening: {
    title: "We Are\nGetting Married!",
    text: "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu. Atas kehadiran dan doa restunya kami ucapkan terima kasih."
  },

  /* ---------- Save The Date + countdown ---------- */
  quote: {
    title: "SAVE THE DATE",
    text: "Di antara tanda-tanda (kebesaran)-Nya ialah bahwa Dia menciptakan pasangan-pasangan untukmu dari (jenis) dirimu sendiri agar kamu merasa tenteram kepadanya. Dia menjadikan di antaramu rasa cinta dan kasih sayang. Sesungguhnya pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir.",
    source: "Ar Rum Ayat 21"
  },

  // Target countdown (ISO 8601 + zona waktu). Referensi: "Feb 28 2027 11:00:00".
  countdown: {
    target: "2026-11-16T11:00:00+07:00",
    labels: { days: "Day", hours: "Hrs", minutes: "Min", seconds: "Sec" },
    doneText: "Acara telah berlangsung"
  },

  // Tombol "SAVE THE DATE" -> Google Calendar
  saveTheDate: {
    label: "SAVE THE DATE",
    title: "Pernikahan Dodhid & Putri",
    start: "2026-11-15T11:00:00+07:00",
    end: "2026-11-16T14:00:00+07:00",
    location: "Jalan Raya Sempol RT.04 RW.01 Desa Sempol, Kec. Pagak, Kabupaten Malang 65158",
    details: "Merupakan suatu kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.",
    timezone: "Asia/Jakarta"
  },

  /* ---------- Acara ---------- */
  event: {
    intro: "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami :",
    items: [
      {
        id: "akad",
        title: "Akad Nikah",
        icon: "assets/images/vintage/icon-akad.webp",
        start: "2026-11-16T08:00:00+07:00",        // tanggal ditulis otomatis (Sabtu, 18 April 2026)
        timeText: "08.00 WIB - Selesai",
        venue: "Kediaman Mempelai Wanita",
        address: "Jalan Raya Sempol RT.04 RW.01 Desa Sempol, Kec. Pagak, Kabupaten Malang 65158",
        mapsUrl: "https://maps.app.goo.gl/k2W6Pc9hHYXCUuSv8?g_st=aw"
      },
      {
        id: "resepsi",
        title: "Resepsi",
        icon: "assets/images/vintage/icon-resepsi.webp",
        start: "2026-11-16T13:00:00+07:00",
        timeText: "12.00 WIB - Selesai",
        venue: "Kediaman Mempelai Wanita",
        address: "Jalan Raya Sempol RT.04 RW.01 Desa Sempol, Kec. Pagak, Kabupaten Malang 65158",
        mapsUrl: "https://maps.app.goo.gl/k2W6Pc9hHYXCUuSv8?g_st=aw"
      }
    ]
  },

  /* ---------- Live streaming ---------- */
  liveStreaming: {
    enabled: true,
    title: "LIVE STREAMING",
    text: "Dan ikuti prosesi pernikahan kami melalui link Instagram, klik dibawah untuk melihat LIVE",
    buttonLabel: "LIVE INSTAGRAM",
    url: "https://www.instagram.com/pxtrynnh/"   // [GANTI] link live Anda
  },

  /* ---------- Galeri ---------- */
  gallery: {
    // Carousel foto berjalan terus
    carousel: [
      "assets/images/ref/G1.JPG",
      "assets/images/ref/G2.JPG",
      "assets/images/ref/g3.JPG",
      "assets/images/ref/g4.JPG",
      "assets/images/ref/g5.JPG"
    ],
    carouselSpeed: 4000,
    youtubeId: "",              // kosongkan "" untuk menyembunyikan video
    youtubeTitle: "Video pernikahan",
    // Grid foto (G1 sampai g14), klik = lightbox
    photos: [
      "assets/images/ref/G1.JPG",
      "assets/images/ref/G2.JPG",
      "assets/images/ref/g3.JPG",
      "assets/images/ref/g4.JPG",
      "assets/images/ref/g5.JPG",
      "assets/images/ref/g6.JPG",
      "assets/images/ref/g7.JPG",
      "assets/images/ref/g8.JPG",
      "assets/images/ref/g9.JPG",
      "assets/images/ref/g10.JPG",
      "assets/images/ref/g11.JPG",
      "assets/images/ref/g12.JPG",
      "assets/images/ref/g13.JPG",
      "assets/images/ref/g14.JPG"
    ]
  },

  /* ---------- Love Story ---------- */
  loveStory: {
    title: "Love Story",
    items: [
      {
        title: "Awal Bertemu",
        image: "assets/images/ref/g6.JPG",
        text: "Di dunia yang luas ini, kami percaya bahwa tidak ada pertemuan yang benar-benar kebetulan.\nTuhan mempertemukan dua hati pada waktu yang tepat dengan cara yang sederhana tapi bermakna."
      },
      {
        title: "Lamaran",
        image: "assets/images/ref/G2.JPG",
        text: "Atas kehendak-Nya, kami melangkah ke tahap yang lebih serius dengan melangsungkan lamaran pada bulan spesial kami yaitu bulan kelahiran kami berdua yaitu pada Mei 2025."
      },
      {
        title: "Acara Resepsi",
        image: "assets/images/ref/g11.JPG",
        text: "Aku memandangi mu tanpa perlu menatap, aku mendengar mu tanpa alat, aku menemui mu tanpa perlu hadir. Aku mencintai mu tanpa perlu apa-apa. Karna kini kumiliki segalanya"
      }
    ]
  },

  /* ---------- Wedding Gift ---------- */
  gift: {
    intro: "Tanpa mengurangi rasa hormat, bagi rekan-rekan dan sahabat yang hendak memberikan tanda kasih untuk kami, dapat melalui nomor rekening di bawah ini.",
    accounts: [
      { bank: "Bank Mandiri", number: "123123123", holder: "Elyana Azkiya Nur" }, // [GANTI]
      { bank: "Bank Mandiri", number: "123123123", holder: "Elyana Azkiya Nur" }  // [GANTI]
    ],
    shipping: {
      enabled: true,
      title: "Kirim Kado:",
      recipient: "Elyana Azkiya Nur",                                                   // [GANTI]
      address: "Jalan Raya Bojongsari No.5, Gunung Putri, Citeureup, Bogor, Jawa Barat"  // [GANTI]
    },
    confirm: {
      enabled: true,
      text: "Mohon konfirmasi untuk pengiriman gift\nTerima kasih"
    }
  },

  /* ---------- RSVP ---------- */
  rsvp: {
    title: "RSVP",
    text: "Bantu kami mempersiapkan jamuan yang hangat untuk anda semua dengan mengirimkan konfirmasi kehadiran melalui form berikut ini",
    attendanceOptions: ["Hadir", "Tidak Hadir"],
    guestCountOptions: ["1 Orang", "2 Orang"],
    maxGuests: 2
  },

  /* ---------- Ucapan / Wishes ---------- */
  wishes: {
    title: "Wishes",
    text: "Berikan Harapan dan doa tulus anda disini karena kami sangat bersemangat untuk memulai perjalanan baru bersama.",
    hint: "Silakan kasih ucapan di bawah ini",
    avatar: "assets/images/ref/avatar.webp",
    perPage: 5,
    // Data contoh bila backend.endpoint kosong. Kosongkan [] untuk memulai bersih.
    seed: [
      { name: "Nama Tamu", attendance: "Hadir", message: "hi", date: "2026-10-01T22:30:00+07:00" },
      { name: "Nama Tamu", attendance: "Hadir", message: "Tes", date: "2026-09-30T19:10:00+07:00" }
    ]
  },

  /* ---------- Penutup & footer ---------- */
  closing: {
    title: "Terima Kasih",
    text: "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu. Atas kehadiran dan doa restunya kami ucapkan terima kasih."
  },
  footer: {
    enabled: true,
    logo: "",
    background: "",
    text: "Exclusive Web Invitation\naurevistudio ❤️",
    links: [
      { type: "link", label: "Website", url: "https://invitation-wedding-putri-dodhid.edgeone.dev/" },
      { type: "instagram", label: "Instagram", url: "https://www.instagram.com/pxtrynnh/" },
      { type: "whatsapp", label: "WhatsApp", url: "https://wa.me/6281216350226?&text=Hallo%20galeri%20undangan" }
    ]
  },

  /* ---------- Musik ---------- */
  music: {
    src: "assets/audio/kasetyan-djati.mp3",   // Sasya Arkhisna ft. Radit Fasolla - Kasetyan Djati (YouTube DiJNVPz0XUM)
    loop: true
  },

  /* ---------- Background ----------
     page   : latar tetap di belakang section transparan (Mempelai, Love Story,
              Gift, Wishes). Tidak terekam di SVG referensi, lihat README.
     section: slideshow foto (images[] > 1 = berganti), effect "fade" | "slide_left".
  */
  backgrounds: {
    page: { image: "assets/images/vintage/bg-card.jpg", position: "center", overlay: "rgba(62, 39, 35, .28)" },
    cover: "assets/images/vintage/scene-cover.jpg",    // lukisan pendopo di cover (juga panel samping laptop/tablet)
    hero: "assets/images/vintage/scene-pendopo.jpg",  // bagian dalam pendopo (tujuan transisi pembuka)
    scene: "assets/images/vintage/bg-scene.jpg",   // Save The Date, Live Streaming, Penutup
    card: "assets/images/vintage/bg-card.jpg",    // kartu acara & gift
    acara: { images: ["assets/images/ref/g7.JPG", "assets/images/ref/g10.JPG"], duration: 4000, transition: 2000, effect: "fade", kenBurns: true },
    rsvp: { images: ["assets/images/ref/g8.JPG", "assets/images/ref/g9.JPG"], duration: 4000, transition: 2000, effect: "slide_left", kenBurns: true }
  },

  /* ---------- Ornamen ---------- */
  ornaments: {
    portraitFlower: "assets/images/vintage/frame-flower.webp",
    ampersand: "assets/images/vintage/ampersand.webp",
    gunungan: "assets/images/vintage/gunungan.webp",
    butterflies: "assets/images/vintage/butterflies.webp"
  },

  /* ---------- Efek ---------- */
  effects: {
    particles: true,          // gelembung putih halus (Save The Date, Live, RSVP)
    animations: true,         // animasi masuk saat discroll
    coverExitMs: 2400         // durasi transisi pembuka (kamera masuk ke dalam pendopo)
  },

  /* ---------- Tata letak ---------- */
  layout: {
    // Di layar lebar undangan tampil sebagai kolom 450px di tengah (sesuai referensi).
    // "plain" = sisi kiri-kanan polos (sesuai SVG), "blur" = foto latar diburamkan.
    desktopBackdrop: "plain"
  },

  /* ---------- Backend form (opsional, lihat README) ---------- */
  backend: {
    endpoint: "",   // URL Google Apps Script Web App
    whatsapp: ""    // nomor WA 62xxxxxxxxxx (dipakai bila endpoint kosong)
  },

  /* ---------- Navigasi melayang (tidak ada di referensi) ---------- */
  features: {
    floatingNav: true
  },

  /* =====================================================================
     EXTRAS — fitur versi sebelumnya yang TIDAK ada di desain referensi.
     Semuanya mati secara default. Kode ada di js/extras.js & css/extras.css.
     ===================================================================== */
  extras: {
    introFan: { enabled: false, gunungan: "assets/images/gunungan.webp", ribs: 13, spread: 168, holdMs: 1500 },
    petals: { enabled: false, count: 18 },
    venue3d: {
      enabled: false,
      titleScript: "Pendopo", titleCaps: "3D",
      text: "Putar dan perbesar suasana pendopo tempat kami berbahagia. Di HP, tampilkan langsung di ruangan Anda dengan AR.",
      model: "assets/3d/joglo.glb", poster: "assets/3d/joglo-poster.webp", iosModel: "", autoRotate: true
    }
  }
};
