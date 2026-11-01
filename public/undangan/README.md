# Undangan Digital — ART JAWA BIRU 3 (recreate)

Recreate high-fidelity dari desain referensi **ART JAWA BIRU 3** dengan HTML5, CSS3, dan vanilla JavaScript. Tidak memakai WordPress, jQuery, atau framework, dan semua aset tersimpan lokal.

Sumber acuan:

| Sumber | Dipakai untuk |
|---|---|
| SVG ekspor desain (1920 × 13719, kolom undangan 450px) | Layout, posisi, ukuran, warna, tipografi, serta gambar asli (diekstrak dari SVG) |
| Website live | Urutan section, teks, perilaku cover, musik, countdown, form |
| ZIP versi sebelumnya | Logika JS (form, ucapan, lightbox, slideshow, copy, musik), ikon, font, fitur tambahan |

```
/
├── index.html          struktur halaman (komponen HTML asli, bukan gambar SVG)
├── ar.html             halaman AR (fitur tambahan, opsional)
├── css/style.css       tampilan sesuai referensi + responsive + animasi
├── css/icons.css       ikon (PNG mask, warna mengikuti teks)
├── css/extras.css      gaya fitur tambahan versi sebelumnya (opsional)
├── js/config.js        SEMUA DATA — cukup edit file ini
├── js/script.js        perilaku utama
├── js/extras.js        fitur tambahan versi sebelumnya (mati secara default)
├── js/ar.js            perilaku halaman AR
└── assets/
    ├── images/ref/     aset hasil ekstraksi SVG referensi (dipakai desain)
    ├── images/         placeholder & ilustrasi versi sebelumnya (tidak dihapus)
    ├── fonts/          Playfair Display, Pinyon Script, Cormorant Garamond, Poppins, Lora, Roboto (OFL)
    ├── audio/          wedding.mp3 (ganti dengan lagu Anda)
    ├── icons/  3d/  lib/
```

---

## 1. Menjalankan

Buka `index.html` langsung di browser. Semua fungsi berjalan, kecuali video YouTube yang butuh internet.

Server lokal (disarankan):

```bash
python3 -m http.server 8000
# buka http://localhost:8000/?to=Budi+Santoso
```

Untuk publikasi, upload seluruh folder ke hosting statis apa saja (Netlify, Vercel, GitHub Pages, Cloudflare Pages, atau cPanel).

**Link per tamu:**

| Link | Tampil di cover |
|---|---|
| `?to=Budi+Santoso` | Budi Santoso |
| `?to=Bpk.+Hadi+%26+Keluarga` | Bpk. Hadi & Keluarga |
| tanpa `?to` | Nama Tamu |

Nama tamu dari link juga otomatis mengisi kolom nama di RSVP, konfirmasi gift, dan ucapan.

---

## 2. Mengganti data (`js/config.js`)

| Yang diganti | Kunci |
|---|---|
| Nama pasangan (cover, hero, penutup), tanggal & hashtag hero, foto cover | `couple.*` |
| Mempelai wanita / pria (nama, orang tua, Instagram, foto) | `bride.*`, `groom.*` |
| Judul & teks pembuka | `opening.*` |
| Ayat / quote Save The Date | `quote.*` |
| Target countdown (ISO + zona waktu, mis. `2027-02-28T11:00:00+07:00`) | `countdown.target` |
| Tombol Save The Date (Google Calendar) | `saveTheDate.*` |
| Akad & resepsi (tanggal ditulis otomatis dari `start`, link Maps) | `event.items[]` |
| Live streaming | `liveStreaming.*` (kosongkan `url` untuk menyembunyikan tombol) |
| Carousel, video YouTube, grid foto | `gallery.*` |
| Love story | `loveStory.items[]` |
| Rekening, alamat kado, konfirmasi gift | `gift.*` |
| Pilihan RSVP & batas jumlah tamu | `rsvp.*` |
| Teks ucapan, avatar, data contoh | `wishes.*` (`seed: []` untuk mulai kosong) |
| Penutup & footer | `closing.*`, `footer.*` |
| Musik | `music.src` |
| Background (halaman, cover, hero, scene, kartu, slideshow Acara & RSVP) | `backgrounds.*` |
| Ornamen (bunga potret, ampersand, gunungan, kupu-kupu) | `ornaments.*` |
| Partikel, animasi, durasi cover | `effects.*` |
| Sisi kiri-kanan di desktop: `"plain"` (sesuai SVG) / `"blur"` | `layout.desktopBackdrop` |
| Navigasi melayang (tidak ada di referensi) | `features.floatingNav: true` |
| Fitur tambahan: kipas pembuka, kelopak jatuh, Pendopo 3D/AR | `extras.*.enabled` |

Baris baru dalam teks ditulis `\n`. Data bertanda `[GANTI]` masih data contoh dari template referensi.

---

## 3. Cara desain diterjemahkan

**Kolom 450px.** Pada SVG, undangan berupa kolom 450px di tengah kanvas 1920px. Di layar lebar halaman ini tampil dengan cara yang sama, dan di HP kolom memenuhi layar.

**Satuan skala.** Semua ukuran di `css/style.css` ditulis dalam piksel desain:

- `calc(N * var(--u))`, dengan `--u = min(1px, 100vw/450)`. Komposisi di 320–450px identik dengan SVG, termasuk pemotongan baris teks (sudah diuji di 360–450px).
- Cover, hero, dan penutup memakai `--cu = min(--u, tinggi-layar/1200)`, karena ilustrasi latarnya mengikuti tinggi layar.
- Ukuran huruf punya batas minimum (±78%) agar tetap terbaca di 320px.

**Token warna.** Nilai diambil langsung dari SVG dan ada di `:root`:

| Token | Nilai |
|---|---|
| `--color-primary` | `#284B73` |
| `--color-secondary` | `#D7BB83` (emas) |
| `--color-event-border` | `#4F2323` |
| `--color-cream` | `#FFFCF3` |
| `--color-input-border` | `#69727D` |
| `--color-footer` | `#8C835A` |

**Font.** Teks di SVG sudah berupa path, jadi font dikenali dari bentuk huruf. Ukurannya dikalibrasi dari lebar teks di SVG:

| Font | Dipakai untuk |
|---|---|
| Playfair Display | Cover, paragraf pembuka |
| Pinyon Script | Semua judul script |
| Cormorant Garamond | Nama, Save The Date, acara, gift |
| Poppins | Orang tua, alamat, rekening, tombol kecil |
| Lora | Countdown, form, RSVP, penutup, footer |
| Roboto | Judul Love Story, widget ucapan |

**Hasil verifikasi.** Pada 450×1200, 116 posisi teks dan elemen diukur otomatis terhadap koordinat SVG:

- Median selisih 0,4px; 113 titik berada dalam ±2px.
- Tinggi total halaman 13.719px, sama dengan SVG.

**Bagian yang tidak terekam di SVG (rekonstruksi).** Bagian berikut bisa disesuaikan lewat `config.js` dan CSS:

- **Isi hero.** Di SVG tertutup cover. Teks dan urutannya diambil dari website live, ditata di dalam bingkai lengkung.
- **Isi kartu Love Story.** Di SVG belum muncul karena animasinya belum berjalan. Kartu berisi foto 2:3, judul, dan teks. Garis, ikon, dan tinggi section sesuai SVG.
- **Latar halaman** di belakang Mempelai, Love Story, Gift, dan Ucapan (`backgrounds.page`). Ini latar *fixed* yang tidak ikut terekam; saat ini memakai ilustrasi toile biru dari desain.
- **Latar foto Acara & RSVP.** Di SVG hanya terlihat overlay gelap. Diisi slideshow foto (`backgrounds.acara`, `backgrounds.rsvp`).
- **Pilihan kehadiran dan jumlah ucapan di widget Ucapan.** Ada di website live tetapi tidak terlihat di SVG. Diletakkan tanpa mengubah tinggi section.

---

## 4. Perilaku

| Bagian | Perilaku |
|---|---|
| Cover | Halaman terkunci. Kartu zoom-in, teks naik. Klik "Buka Undangan": scroll aktif, isi tampil di posisi teratas, musik berputar, cover naik + memudar 1,5 detik (sesuai JS referensi `animate({opacity:0, top:"-100%"}, 1500)`) |
| Hero | Latar zoom halus, teks muncul berurutan |
| Section lain | Animasi masuk saat discroll (fade/zoom/slide), bunga potret bergoyang, partikel gelembung, timeline story meluncur |
| Musik | Diputar setelah klik (sesuai aturan autoplay browser), tombol berputar saat aktif, jeda otomatis saat tab ditinggal |
| Countdown | Per detik dari target ISO (aman lintas zona waktu). Berhenti di 00 + pesan bila sudah lewat. Disembunyikan bila tanggal tidak valid |
| Galeri | Carousel berjalan terus (berhenti saat di-hover). Lightbox: panah, swipe, ←/→, ESC, klik overlay, focus trap |
| Copy | Clipboard API, dengan fallback `execCommand` |
| Form | Validasi, pesan error/sukses, dikirim sesuai mode backend (bagian 5) |
| Aksesibilitas | `prefers-reduced-motion` dihormati, label & `aria-*` pada kontrol, fokus terlihat |

---

## 5. Backend form (opsional)

Ada tiga mode, dipilih otomatis berdasarkan `config.backend`:

| Kondisi | RSVP & konfirmasi gift | Ucapan (buku tamu) |
|---|---|---|
| `endpoint` diisi | Dikirim ke endpoint | Dibaca & disimpan di endpoint (tampil untuk semua tamu) |
| Hanya `whatsapp` diisi | Membuka WhatsApp berisi data form | Disimpan di perangkat tamu (localStorage) |
| Keduanya kosong | Disimpan di perangkat tamu + pesan "backend belum dikonfigurasi" | Data contoh `wishes.seed` + localStorage |

Agar ucapan bisa dilihat oleh semua tamu, Anda butuh backend. Cara termudah dan gratis adalah **Google Sheets + Apps Script**:

1. Buat Google Sheet baru, lalu buka **Extensions → Apps Script**.
2. Tempel kode di bawah, lalu simpan.
3. Klik **Deploy → New deployment → Web app**.
   - *Execute as:* **Me**
   - *Who has access:* **Anyone**
4. Salin URL `https://script.google.com/macros/s/.../exec` ke `backend.endpoint` di `config.js`.

```javascript
// Google Apps Script — backend undangan
const SHEETS = { rsvp: "RSVP", gift: "Gift", wish: "Wishes" };
const DRIVE_FOLDER_NAME = "Bukti Transfer Undangan";

function sheet_(name, header) {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  let sh = ss.getSheetByName(name);
  if (!sh) { sh = ss.insertSheet(name); sh.appendRow(header); }
  return sh;
}
function json_(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
function clean_(v, max) { return String(v == null ? "" : v).slice(0, max || 1000); }

function doGet(e) {
  if (e.parameter.type === "wishes") {
    const sh = sheet_(SHEETS.wish, ["id", "parentId", "name", "message", "attendance", "date"]);
    const rows = sh.getDataRange().getValues().slice(1);
    const data = rows.map(r => ({ id: String(r[0]), parentId: r[1] ? String(r[1]) : null,
      name: r[2], message: r[3], attendance: r[4],
      date: r[5] instanceof Date ? r[5].toISOString() : r[5] }));
    return json_({ ok: true, data });
  }
  return json_({ ok: false, error: "unknown type" });
}

function doPost(e) {
  const lock = LockService.getScriptLock(); lock.waitLock(10000);
  try {
    const d = JSON.parse(e.postData.contents);
    if (d.type === "rsvp") {
      sheet_(SHEETS.rsvp, ["Waktu", "Nama", "Kehadiran", "Jumlah"])
        .appendRow([new Date(), clean_(d.nama, 80), clean_(d.kehadiran, 30), clean_(d.jumlah, 30)]);
    } else if (d.type === "gift") {
      let fileUrl = "";
      if (d.fileData) {
        const it = DriveApp.getFoldersByName(DRIVE_FOLDER_NAME);
        const folder = it.hasNext() ? it.next() : DriveApp.createFolder(DRIVE_FOLDER_NAME);
        const blob = Utilities.newBlob(Utilities.base64Decode(d.fileData), d.mimeType, clean_(d.fileName, 120));
        fileUrl = folder.createFile(blob).getUrl();
      }
      sheet_(SHEETS.gift, ["Waktu", "Nama", "Bank", "Nominal", "Ucapan", "Bukti TF"])
        .appendRow([new Date(), clean_(d.nama, 80), clean_(d.bank, 60), clean_(d.nominal, 40), clean_(d.ucapan), fileUrl]);
    } else if (d.type === "wish") {
      sheet_(SHEETS.wish, ["id", "parentId", "name", "message", "attendance", "date"])
        .appendRow([clean_(d.id, 60), clean_(d.parentId, 60), clean_(d.name, 60),
                    clean_(d.message, 1000), clean_(d.attendance, 30), clean_(d.date, 40)]);
    } else {
      return json_({ ok: false, error: "unknown type" });
    }
    return json_({ ok: true });
  } catch (err) {
    return json_({ ok: false, error: String(err) });
  } finally { lock.releaseLock(); }
}
```

Script mengirim data sebagai `text/plain` sehingga tidak memicu *preflight* CORS, dan Apps Script menerimanya langsung. Jika Anda memakai backend sendiri (PHP, Node, Firebase, dll.), cukup terima `POST` JSON dengan field `type` (`rsvp` | `gift` | `wish`) dan sediakan `GET ?type=wishes` yang mengembalikan `{ ok: true, data: [...] }`.

---

---

## 6. Checklist uji

Hasil uji otomatis (Chromium headless):

- **Responsive:** tanpa error JavaScript dan tanpa scroll horizontal di 320×568, 360×800, 375×812, 390×844, 412×915, 430×932, 768×1024, 1280×720, 1366×768, 1440×900, dan 1920×1080.
- **Lolos:**
  - parameter `?to=` dan fallback
  - kunci scroll, cover terbuka, posisi kembali ke atas
  - play/pause musik
  - countdown berjalan, sudah lewat, dan tidak valid
  - link Google Calendar & Maps
  - copy rekening & alamat
  - lightbox
  - validasi & kirim RSVP
  - validasi, unggah, & kirim konfirmasi gift
  - kirim, balas, & simpan ucapan
  - mode reduced motion
  - dibuka langsung lewat `file://`
  - fitur tambahan saat diaktifkan

Perlu dicek manual di perangkat asli:

- [ ] Musik berbunyi setelah "Buka Undangan" (iPhone: mode senyap mematikan audio web)
- [ ] Video YouTube tampil (butuh internet)
- [ ] Tombol Instagram, Maps, dan WhatsApp membuka aplikasi yang benar

---

## 7. Lisensi aset

- **Font:** Playfair Display, Pinyon Script, Cormorant Garamond, Poppins, Lora, Roboto — SIL Open Font License / Apache 2.0, lihat `assets/fonts/LICENSE-OFL.txt`.
- **`assets/images/ref/`:** diekstrak dari SVG desain referensi. Foto pasangan contoh, ilustrasi, dan logo footer adalah milik pembuat template (Galeri Undangan Official). Pastikan Anda berhak memakainya sebelum dipublikasikan, dan ganti foto contoh dengan foto mempelai lewat `config.js`.
- **Musik referensi tidak disertakan.** Ganti `assets/audio/wedding.mp3` dengan lagu yang Anda miliki haknya.
- **Library** (fitur tambahan): model-viewer (Apache-2.0), lottie-web (MIT).

---

## Revisi 2: Tema Royal Java Vintage × palet Vintage Soul (seluruh undangan)

| Warna | Kode | Dipakai untuk |
|---|---|---|
| Cream | `#F8F1E7` | Latar utama |
| Warm Beige | `#D8C3A5` | Garis, ornamen, elemen pendukung |
| Coffee Brown | `#8B6B4A` | Aksen |
| Burgundy | `#6B2C2C` | Judul, tombol, ikon (penekanan) |
| Dark Chocolate | `#3E2723` | Teks & latar gelap pendukung |

### File

| File | Isi |
|---|---|
| `css/theme-vintage.css` | Palet & gaya seluruh section, tombol musik, navigasi, efek, tata letak per perangkat. Dimuat setelah `style.css`, sehingga struktur lama tetap utuh. |
| `css/cover.css` | Pembuka baru. |
| `js/cover.js` | Nama bertumpuk, debu emas, riak tombol. |
| `js/theme.js` | Parallax halus & panah gulir hero. |
| `assets/images/vintage/` | Lukisan pendopo dari desain `Frame_1undangan.svg` (`scene-cover.jpg`, `scene-pendopo.jpg`), serta ilustrasi lama yang diwarnai ulang ke palet (latar toile, kartu, bunga potret, ampersand, gunungan, ikon akad/resepsi, kupu-kupu). File asli di `ref/` tidak dihapus. |
| `js/config.js` | Path gambar ke `vintage/`, overlay latar halaman, durasi pembuka 2400 ms, navigasi melayang aktif. |

### Pembuka

Latarnya lukisan pendopo bertirai dengan wayang dan gunungan. Isinya:

- **Atas:** "The Wedding Of", nama mempelai, tanggal.
- **Bawah:** nama tamu (`?to=`), teks undangan, tombol **Buka Undangan**.

Saat tombol ditekan:

1. Teks memudar.
2. Kamera "masuk" ke pintu pendopo, lalu tampilan berganti ke bagian dalam pendopo.
3. Bagian dalam pendopo ini sekaligus menjadi latar hero, jadi peralihannya mulus.
4. Musik *Kasetyan Djati* mulai diputar berulang.

### Perangkat

| Perangkat | Tampilan |
|---|---|
| **Android (utama)** & iPhone, layar < 768px | Undangan penuh selebar layar. Landscape: nama satu baris, info diringkas. |
| **Tablet** (768–1023px) | Kolom undangan di tengah, lukisan pendopo buram di kiri-kanan. |
| **Laptop/desktop** (≥ 1024px) | Panel lukisan berisi nama, tanggal, dan hashtag di kiri; undangan di kanan. |

### Mengatur ulang

- **Nada foto:** foto mempelai diberi nada hangat ringan lewat `--photo-tone` di `css/theme-vintage.css`. Isi `none` untuk foto asli.
- **Navigasi melayang:** matikan dengan `features.floatingNav: false`.
- **Teks kecil di cover:** ubah lewat `guest.invitation`.
