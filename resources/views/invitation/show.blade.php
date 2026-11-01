<!DOCTYPE html>
<html lang="id">
<head>
  <base href="{{ asset('undangan') }}/">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="format-detection" content="telephone=no">
  <meta name="theme-color" content="#3E2723">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $guest ? 'The Wedding - ' . $guest->name : 'The Wedding' }}</title>
  <meta name="description" content="Undangan pernikahan digital">
  <meta property="og:type" content="website">
  <meta property="og:title" content="The Wedding">
  <meta property="og:description" content="Undangan pernikahan digital">
  <meta property="og:image" content="assets/images/ref/G1.JPG">
  <link rel="icon" href="assets/icons/favicon.png" type="image/png">
  <link rel="apple-touch-icon" href="assets/icons/apple-touch-icon.png">
  <link rel="preload" href="assets/fonts/playfair-display-latin-500-normal.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="assets/fonts/playfair-display-latin-600-normal.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="assets/images/vintage/scene-cover.jpg" as="image">
  <link rel="preload" href="assets/images/vintage/scene-pendopo.jpg" as="image">
  <link rel="preload" href="assets/fonts/cinzel-latin-600-normal.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="assets/fonts/pinyon-script-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="css/icons.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/extras.css">
  <link rel="stylesheet" href="css/theme-vintage.css">
  <link rel="stylesheet" href="css/cover.css">
</head>
<body class="is-locked">

  <!-- Panel samping untuk tablet & laptop (di HP tersembunyi) -->
  <aside class="side-panel" aria-hidden="true">
    <div class="side-panel__bg" data-bg="backgrounds.cover"></div>
    <div class="side-panel__content">
      <p class="side-panel__eyebrow" data-text="couple.eyebrow"></p>
      <p class="side-panel__names" data-text="couple.names"></p>
      <div class="side-panel__divider"></div>
      <p class="side-panel__date" data-text="couple.displayDate"></p>
      <p class="side-panel__tag" data-text="couple.hashtag"></p>
    </div>
  </aside>

  <!-- Latar tetap di belakang section transparan (config.backgrounds.page) -->
  <div class="page-bg" aria-hidden="true"><div class="page-bg__img"></div></div>

  <!-- =================================================================
       1. COVER — fixed di atas undangan, terkunci sampai "Buka Undangan"
       ================================================================= -->
  <section id="cover" class="cover cover--vintage" aria-label="Sampul undangan">
    <!-- Lukisan pendopo (scene) — saat dibuka, kamera "masuk" ke dalam pendopo -->
    <div class="cover__scene" data-bg="backgrounds.cover" aria-hidden="true"></div>
    <div class="cover__inside" data-bg="backgrounds.hero" aria-hidden="true"></div>
    <div class="cover__shade" aria-hidden="true"></div>
    <div class="cover__dust" aria-hidden="true"></div>

    <div class="cover__frame" aria-hidden="true">
      <span class="cover__corner cover__corner--tl"><svg viewBox="0 0 80 80" aria-hidden="true"><path d="M4 76V30C4 15 15 4 30 4h46" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M12 76V34c0-12 10-22 22-22h42" fill="none" stroke="currentColor" stroke-width=".7" opacity=".7"/><path d="M4 30c10 0 16-6 16-16 6 6 4 14-2 18 6 2 12 0 14-6 2 8-6 14-14 12" fill="none" stroke="currentColor" stroke-width="1"/><circle cx="20" cy="20" r="2.2" fill="currentColor"/><path d="M40 4c0 5 4 8 8 8M4 40c5 0 8 4 8 8" fill="none" stroke="currentColor" stroke-width=".8"/></svg></span>
      <span class="cover__corner cover__corner--tr"><svg viewBox="0 0 80 80" aria-hidden="true"><path d="M4 76V30C4 15 15 4 30 4h46" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M12 76V34c0-12 10-22 22-22h42" fill="none" stroke="currentColor" stroke-width=".7" opacity=".7"/><path d="M4 30c10 0 16-6 16-16 6 6 4 14-2 18 6 2 12 0 14-6 2 8-6 14-14 12" fill="none" stroke="currentColor" stroke-width="1"/><circle cx="20" cy="20" r="2.2" fill="currentColor"/><path d="M40 4c0 5 4 8 8 8M4 40c5 0 8 4 8 8" fill="none" stroke="currentColor" stroke-width=".8"/></svg></span>
      <span class="cover__corner cover__corner--bl"><svg viewBox="0 0 80 80" aria-hidden="true"><path d="M4 76V30C4 15 15 4 30 4h46" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M12 76V34c0-12 10-22 22-22h42" fill="none" stroke="currentColor" stroke-width=".7" opacity=".7"/><path d="M4 30c10 0 16-6 16-16 6 6 4 14-2 18 6 2 12 0 14-6 2 8-6 14-14 12" fill="none" stroke="currentColor" stroke-width="1"/><circle cx="20" cy="20" r="2.2" fill="currentColor"/><path d="M40 4c0 5 4 8 8 8M4 40c5 0 8 4 8 8" fill="none" stroke="currentColor" stroke-width=".8"/></svg></span>
      <span class="cover__corner cover__corner--br"><svg viewBox="0 0 80 80" aria-hidden="true"><path d="M4 76V30C4 15 15 4 30 4h46" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M12 76V34c0-12 10-22 22-22h42" fill="none" stroke="currentColor" stroke-width=".7" opacity=".7"/><path d="M4 30c10 0 16-6 16-16 6 6 4 14-2 18 6 2 12 0 14-6 2 8-6 14-14 12" fill="none" stroke="currentColor" stroke-width="1"/><circle cx="20" cy="20" r="2.2" fill="currentColor"/><path d="M40 4c0 5 4 8 8 8M4 40c5 0 8 4 8 8" fill="none" stroke="currentColor" stroke-width=".8"/></svg></span>
    </div>

    <div class="cover__top">
      <p class="cover__eyebrow" data-text="couple.eyebrow"></p>
      <h1 class="cover__names" id="coverNames">
        <span class="cover__name cover__name--a"></span>
        <span class="cover__amp">&amp;</span>
        <span class="cover__name cover__name--b"></span>
      </h1>
      <p class="cover__date" data-text="couple.displayDate"></p>
    </div>

    <!-- Frame Foto Tengah (Arch Frame) -->
    <div class="cover__middle">
      <div class="cover__portrait">
        <img class="cover__portrait-flower cover__portrait-flower--back" src="assets/images/vintage/frame-flower.webp" alt="" aria-hidden="true" loading="lazy">
        <div class="cover__portrait-frame">
          <img class="cover__portrait-img" src="assets/images/ref/G1.JPG" data-src="couple.coverPhoto" alt="Mempelai" loading="eager">
        </div>
        <img class="cover__portrait-flower cover__portrait-flower--front" src="assets/images/vintage/frame-flower.webp" alt="" aria-hidden="true" loading="lazy">
      </div>
    </div>

    <div class="cover__bottom">
      <div class="cover__divider" aria-hidden="true">
        <svg viewBox="0 0 200 16"><path d="M2 8h76M122 8h76" stroke="currentColor" stroke-width="1"/><path d="M100 1l7 7-7 7-7-7z" fill="none" stroke="currentColor" stroke-width="1.2"/><circle cx="100" cy="8" r="2" fill="currentColor"/><circle cx="84" cy="8" r="1.6" fill="currentColor"/><circle cx="116" cy="8" r="1.6" fill="currentColor"/></svg>
      </div>
      <div class="cover__guest">
        <p class="cover__to" data-text="guest.greeting"></p>
        <p class="cover__guest-name" id="guestName">{{ $guest ? $guest->name : 'Nama Tamu' }}</p>
        <p class="cover__place" data-text="guest.place"></p>
      </div>
      <p class="cover__invite" data-multiline="guest.invitation"></p>
      <div class="cover__action">
        <button type="button" id="tombolbuka" class="btn-open">
          <i class="ico i-envelope-solid" aria-hidden="true"></i><span>Buka Undangan</span>
        </button>
      </div>
    </div>
  </section>

  <!-- ================================================================= -->
  <main id="isi" class="invitation" hidden>

    <!-- 2. HERO -->
    <section id="home" class="hero" aria-label="The Wedding Of">
      <div class="hero__bg" data-bg="backgrounds.hero"></div>
      <div class="hero__content">
        <p class="hero__eyebrow" data-hero-anim="fadeInDown">THE WEDDING OF</p>
        <h2 class="hero__names" data-text="couple.names" data-hero-anim="zoomIn"></h2>
        <p class="hero__date" data-text="couple.displayDate" data-hero-anim="fadeInUp"></p>
        <p class="hero__tag" data-text="couple.hashtag" data-hero-anim="fadeInUp"></p>
      </div>
      <a class="hero__scroll" href="#mempelai" aria-label="Gulir ke bagian berikutnya">
        <svg viewBox="0 0 40 40" aria-hidden="true"><path d="M10 12l10 10 10-10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 22l10 10 10-10" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity=".45"/></svg>
      </a>
    </section>

    <!-- 3. MEMPELAI -->
    <section id="mempelai" class="couple" aria-label="Mempelai">
      <div class="couple__card">
        <h2 class="couple__title script" data-multiline="opening.title" data-anim="fadeInDown"></h2>
        <p class="couple__lead" data-text="opening.text" data-anim="fadeInUp"></p>

        <article class="person" data-person="bride"></article>
        <div class="couple__amp" data-anim="zoomIn"><img data-src="ornaments.ampersand" alt="" loading="lazy"></div>
        <article class="person" data-person="groom"></article>
      </div>
    </section>

    <!-- 4. SAVE THE DATE + COUNTDOWN -->
    <section id="save-the-date" class="std" aria-label="Save The Date">
      <div class="std__bg" data-bg="backgrounds.scene"></div>
      <canvas class="particles" aria-hidden="true"></canvas>
      <div class="std__inner">
        <h2 class="std__title" data-text="quote.title" data-anim="fadeInDown"></h2>
        <p class="std__quote" data-text="quote.text" data-anim="fadeInUp"></p>
        <p class="std__source" data-text="quote.source" data-anim="fadeInUp"></p>
        <ul class="countdown" id="countdown" aria-label="Hitung mundur menuju hari pernikahan" data-anim="zoomIn">
          <li><span class="countdown__num" data-unit="days">00</span><span class="countdown__label" data-text="countdown.labels.days"></span></li>
          <li><span class="countdown__num" data-unit="hours">00</span><span class="countdown__label" data-text="countdown.labels.hours"></span></li>
          <li><span class="countdown__num" data-unit="minutes">00</span><span class="countdown__label" data-text="countdown.labels.minutes"></span></li>
          <li><span class="countdown__num" data-unit="seconds">00</span><span class="countdown__label" data-text="countdown.labels.seconds"></span></li>
        </ul>
        <p class="countdown__done" id="countdownDone" hidden data-text="countdown.doneText"></p>
        <div class="std__action" data-anim="fadeInUp">
          <a class="btn-square" id="btnSaveDate" target="_blank" rel="noopener">
            <i class="ico i-calendar-solid" aria-hidden="true"></i><span data-text="saveTheDate.label"></span>
          </a>
        </div>
      </div>
    </section>

    <!-- 5. WEDDING EVENT -->
    <section id="acara" class="event" data-slideshow="acara" aria-label="Wedding Event">
      <div class="heading-pair heading-pair--light">
        <p class="heading-pair__script" data-anim="fadeInLeft">Wedding</p>
        <p class="heading-pair__caps" data-anim="fadeInRight">Event</p>
      </div>
      <p class="event__intro" data-text="event.intro" data-anim="fadeInUp"></p>
      <div class="event__list" id="eventList"></div>
    </section>

    <!-- 6. LIVE STREAMING -->
    <section id="live" class="live" aria-label="Live Streaming">
      <div class="live__bg" data-bg="backgrounds.scene"></div>
      <canvas class="particles" aria-hidden="true"></canvas>
      <div class="live__inner">
        <h2 class="live__title" data-text="liveStreaming.title" data-anim="zoomIn"></h2>
        <p class="live__text" data-text="liveStreaming.text" data-anim="fadeInUp"></p>
        <div class="live__action" id="liveBtnWrap" data-anim="fadeInUp">
          <a class="btn-square" id="btnLive" target="_blank" rel="noopener">
            <i class="ico i-instagram" aria-hidden="true"></i><span data-text="liveStreaming.buttonLabel"></span>
          </a>
        </div>
      </div>
    </section>

    <!-- 7. GALERI -->
    <section id="gallery" class="gallery" aria-label="Galeri foto">
      <div class="heading-pair">
        <p class="heading-pair__script" data-anim="fadeInLeft">Galeri</p>
        <p class="heading-pair__caps" data-anim="fadeInRight">Foto</p>
      </div>
      <div class="carousel" id="carousel" aria-label="Carousel foto">
        <div class="carousel__track" id="carouselTrack"></div>
      </div>
      <div class="video-embed" id="videoEmbed">
        <iframe id="ytFrame" title="Video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
      </div>
      <div class="photo-grid" id="photoGrid"></div>
    </section>

    <!-- 8. LOVE STORY -->
    <section id="love-story" class="story" aria-label="Love Story">
      <h2 class="story__title" data-text="loveStory.title" data-anim="fadeInDown"></h2>
      <ol class="timeline" id="timeline"></ol>
    </section>

    <!-- 9. WEDDING GIFT -->
    <section id="gift" class="gift" aria-label="Wedding Gift">
      <div class="pill-card gift-card" data-anim="fadeInUp">
        <div class="pill-card__inner">
          <img class="pill-card__crest" data-src="ornaments.gunungan" alt="" loading="lazy">
          <div class="heading-pair">
            <p class="heading-pair__script" data-anim="fadeInLeft">Wedding</p>
            <p class="heading-pair__caps" data-anim="fadeInRight">Gift</p>
          </div>
          <p class="gift__intro" data-text="gift.intro"></p>
          <div class="gift__accounts" id="giftAccounts"></div>
          <div class="gift__ship" id="giftShipping">
            <i class="ico i-gift-solid gift__icon" aria-hidden="true"></i>
            <p class="gift__ship-title" data-text="gift.shipping.title"></p>
            <p class="gift__ship-addr"><strong data-text="gift.shipping.recipient"></strong><br><span data-text="gift.shipping.address"></span></p>
            <button type="button" class="btn-pill btn-pill--copy js-copy" data-copy-key="gift.shipping.address" data-done="Alamat disalin">
              <span>Copy Alamat</span><i class="ico i-copy-solid" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="pill-card confirm-card" id="giftConfirm" data-anim="fadeInUp">
        <div class="pill-card__inner">
          <img class="pill-card__crest" data-src="ornaments.gunungan" alt="" loading="lazy">
          <div class="heading-pair heading-pair--confirm">
            <p class="heading-pair__script" data-anim="fadeInLeft">Gift</p>
            <p class="heading-pair__caps" data-anim="fadeInRight">Confirm</p>
          </div>
          <p class="confirm__text" data-multiline="gift.confirm.text"></p>

          <form class="cform" id="giftForm" novalidate>
            <label class="cform__field"><span class="cform__label">Nama :</span>
              <input type="text" name="nama" placeholder="Nama" required autocomplete="name" maxlength="80"></label>
            <label class="cform__field"><span class="cform__label">Nama Bank :</span>
              <input type="text" name="bank" placeholder="Nama Bank" required maxlength="60"></label>
            <label class="cform__field"><span class="cform__label">Nominal :</span>
              <input type="text" name="nominal" placeholder="Nominal" inputmode="numeric" required maxlength="40"></label>
            <label class="cform__field"><span class="cform__label">Ucapan :</span>
              <input type="text" name="ucapan" placeholder="Ucapan" maxlength="500"></label>
            <label class="cform__field cform__field--file"><span class="cform__label">Bukti TF :</span>
              <span class="file-input">
                <input type="file" name="bukti" accept="image/*,application/pdf">
                <span class="file-input__btn" aria-hidden="true">Choose File</span>
                <span class="file-input__name">No file chosen</span>
              </span></label>
            <p class="form__msg" role="status" aria-live="polite"></p>
            <button type="submit" class="btn-block"><i class="ico i-whatsapp" aria-hidden="true"></i><span>Konfirmasi</span></button>
          </form>
        </div>
      </div>
    </section>

    <!-- 10. RSVP -->
    <section id="rsvp" class="rsvp" data-slideshow="rsvp" aria-label="RSVP">
      <canvas class="particles" aria-hidden="true"></canvas>
      <div class="rsvp__inner">
        <h2 class="rsvp__title" data-text="rsvp.title" data-anim="fadeInDown"></h2>
        <p class="rsvp__text" data-text="rsvp.text" data-anim="fadeInUp"></p>
        <form class="rform" id="rsvpForm" novalidate data-anim="fadeInUp">
          <label class="rform__field"><span class="rform__label">Nama <b aria-hidden="true">*</b></span>
            <input type="text" name="nama" placeholder="Nama" required autocomplete="name" maxlength="80"></label>
          <label class="rform__field"><span class="rform__label">Konfirmasi Kehadiran <b aria-hidden="true">*</b></span>
            <span class="select"><select name="kehadiran" id="rsvpAttendance" required></select></span></label>
          <label class="rform__field" id="rsvpCountField"><span class="rform__label">Jumlah <b aria-hidden="true">*</b></span>
            <span class="select"><select name="jumlah" id="rsvpCount" required></select></span></label>
          <p class="form__msg" role="status" aria-live="polite"></p>
          <button type="submit" class="btn-block btn-block--rsvp"><i class="ico i-whatsapp" aria-hidden="true"></i><span>Submit</span></button>
        </form>
      </div>
    </section>

    <!-- 11. WISHES -->
    <section id="ucapan" class="wishes" aria-label="Ucapan dan doa">
      <div class="wishes__card glass">
        <h2 class="wishes__title" data-text="wishes.title" data-anim="fadeInDown"></h2>
        <p class="wishes__text" data-text="wishes.text" data-anim="fadeInUp"></p>

        <div class="guestbook__stats" aria-live="polite">
          <span><b id="wishCount">0</b> Ucapan</span>
          <span><b id="statHadir">0</b> Hadir</span>
          <span><b id="statTidak">0</b> Tidak hadir</span>
        </div>
        <div class="guestbook">
          <form class="guestbook__form" id="wishForm" novalidate>
            <div class="guestbook__reply-note" id="replyNote" hidden>
              <span>Membalas <strong id="replyTo"></strong></span>
              <button type="button" class="link-btn" id="cancelReply">Batal</button>
            </div>
            <input type="text" name="nama" id="wishName" placeholder="Nama" required maxlength="60" autocomplete="name" aria-label="Nama">
            <p class="guestbook__hint" data-text="wishes.hint"></p>
            <textarea name="ucapan" placeholder="Ucapan" rows="3" required minlength="2" maxlength="1000" aria-label="Ucapan"></textarea>
            <p class="form__msg" role="status" aria-live="polite"></p>
            <div class="guestbook__row">
              <button type="submit" class="guestbook__submit">Kirim</button>
              <span class="select select--sm" id="wishAttendanceWrap"><select name="kehadiran" id="wishAttendance" required aria-label="Konfirmasi kehadiran">
                <option value="" selected disabled>Konfirmasi Kehadiran</option>
                <option value="Hadir">Datang</option>
                <option value="Tidak Hadir">Absen</option>
              </select></span>
            </div>
          </form>
          <ul class="guestbook__list" id="wishList"></ul>
          <nav class="guestbook__pager" id="wishPager" aria-label="Halaman ucapan"></nav>
        </div>
      </div>
    </section>

    <!-- 12. PENUTUP -->
    <section id="penutup" class="closing" aria-label="Penutup">
      <div class="closing__bg" data-bg="backgrounds.scene"></div>
      <div class="closing__inner">
        <h2 class="closing__title" data-text="closing.title" data-anim="fadeInDown"></h2>
        <p class="closing__names" data-text="couple.names" data-anim="zoomIn"></p>
        <p class="closing__text" data-text="closing.text" data-anim="fadeInUp"></p>
      </div>
    </section>

    <!-- 13. FOOTER -->
    <footer class="footer" id="footer">
      <img class="footer__logo" id="footerLogo" alt="" loading="lazy">
      <p class="footer__text" data-multiline="footer.text"></p>
      <div class="footer__social" id="footerLinks"></div>
    </footer>
  </main>

  <!-- MUSIK -->
  <div id="audio-container" class="audio" hidden>
    <audio id="song" preload="none"></audio>
    <button type="button" class="audio__btn" id="musicBtn" aria-label="Jeda musik" aria-pressed="true">
      <i class="ico audio__icon i-pause" aria-hidden="true"></i>
    </button>
  </div>

  <!-- Navigasi melayang opsional (tidak ada di referensi; features.floatingNav) -->
  <nav class="float-nav" id="floatNav" hidden aria-label="Navigasi undangan">
    <a href="#home"><i class="ico i-home" aria-hidden="true"></i><span>Home</span></a>
    <a href="#mempelai"><i class="ico i-users" aria-hidden="true"></i><span>Mempelai</span></a>
    <a href="#acara"><i class="ico i-calendar-solid" aria-hidden="true"></i><span>Acara</span></a>
    <a href="#gallery"><i class="ico i-image" aria-hidden="true"></i><span>Galeri</span></a>
    <a href="#gift"><i class="ico i-gift" aria-hidden="true"></i><span>Gift</span></a>
    <a href="#rsvp"><i class="ico i-mail" aria-hidden="true"></i><span>RSVP</span></a>
    <a href="#ucapan"><i class="ico i-message" aria-hidden="true"></i><span>Ucapan</span></a>
  </nav>

  <!-- Lightbox -->
  <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Foto galeri" hidden>
    <div class="lightbox__bar">
      <span class="lightbox__count" id="lbCount"></span>
      <button type="button" class="lightbox__btn" id="lbClose" aria-label="Tutup"><i class="ico i-close" aria-hidden="true"></i></button>
    </div>
    <button type="button" class="lightbox__nav lightbox__nav--prev" id="lbPrev" aria-label="Foto sebelumnya"><i class="ico i-left" aria-hidden="true"></i></button>
    <figure class="lightbox__stage"><img id="lbImg" alt=""></figure>
    <button type="button" class="lightbox__nav lightbox__nav--next" id="lbNext" aria-label="Foto berikutnya"><i class="ico i-right" aria-hidden="true"></i></button>
  </div>

  <div class="toast" id="toast" role="status" aria-live="polite"></div>

  <!-- ================= TEMPLATES ================= -->
  <template id="tpl-person">
    <div class="person__portrait" data-anim="zoomIn">
      <img class="person__flower person__flower--back" alt="" loading="lazy">
      <div class="person__photo"><img alt="" loading="lazy"></div>
      <img class="person__flower person__flower--front" alt="" loading="lazy">
    </div>
    <p class="person__nick" data-anim="fadeInUp"></p>
    <p class="person__name" data-anim="fadeInUp"></p>
    <p class="person__parents" data-anim="fadeInUp"></p>
    <div class="person__action" data-anim="fadeInUp">
      <a class="btn-pill btn-pill--ig" target="_blank" rel="noopener"><i class="ico i-instagram" aria-hidden="true"></i><span>INSTAGRAM</span></a>
    </div>
  </template>

  <template id="tpl-event">
    <article class="event-card" data-anim="fadeInUp">
      <div class="event-card__inner">
        <img class="event-card__icon" alt="" loading="lazy">
        <h3 class="event-card__title"></h3>
        <p class="event-card__when"><span class="event-card__date"></span><br><span class="event-card__time"></span></p>
        <i class="ico i-pin-solid event-card__pin" aria-hidden="true"></i>
        <p class="event-card__place"><span class="event-card__venue"></span><br><span class="event-card__address"></span></p>
        <a class="btn-pill event-card__map" target="_blank" rel="noopener"><i class="ico i-pin-solid" aria-hidden="true"></i><span>Google Map</span></a>
      </div>
    </article>
  </template>

  <template id="tpl-account">
    <div class="account">
      <p class="account__info"><span class="account__bank"></span><br>No. Rekening <span class="account__number"></span><br>a.n <strong class="account__holder"></strong></p>
      <button type="button" class="btn-pill btn-pill--copy js-copy" data-done="Nomor rekening disalin"><span>Copy Rekening</span><i class="ico i-copy-solid" aria-hidden="true"></i></button>
      <div class="divider-heart" aria-hidden="true"><i class="ico i-heart"></i></div>
    </div>
  </template>

  <template id="tpl-story">
    <li class="timeline__item">
      <span class="timeline__icon" aria-hidden="true"><i class="ico i-heart"></i></span>
      <div class="timeline__card">
        <figure class="timeline__thumb"><img alt="" loading="lazy"></figure>
        <h3 class="timeline__title"></h3>
        <p class="timeline__text"></p>
      </div>
    </li>
  </template>

  <template id="tpl-wish">
    <li class="wish">
      <img class="wish__avatar" alt="" loading="lazy">
      <div class="wish__body">
        <p class="wish__head"><span class="wish__name"></span><span class="wish__badge"><span class="wish__att"></span></span></p>
        <p class="wish__text"></p>
        <p class="wish__meta"><span class="wish__time"><i class="ico i-clock" aria-hidden="true"></i><span></span></span><button type="button" class="link-btn wish__reply">Reply</button></p>
        <ul class="wish__children"></ul>
      </div>
    </li>
  </template>

  <script src="js/config.js"></script>
  <script>
    // Injeksi konfigurasi Laravel & Nama Tamu ke frontend public/undangan
    if (window.weddingData) {
      @if($guest)
        window.weddingData.guest = window.weddingData.guest || {};
        window.weddingData.guest.fallbackName = @json($guest->name);
      @endif

      // Sambungkan backend endpoint ke route Laravel backendBridge
      window.weddingData.backend = {
        endpoint: "{{ url('/invitation/backend-bridge') }}",
        whatsapp: "628123456789"
      };
    }
  </script>
  <script src="js/extras.js"></script>
  <script src="js/cover.js"></script>
  <script src="js/script.js"></script>
  <script src="js/theme.js"></script>
  <script>
    // Memastikan nama tamu langsung terpasang saat halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function () {
      @if($guest)
        var gn = document.getElementById("guestName");
        if (gn) gn.textContent = @json($guest->name);
        var rformNama = document.querySelector("#rsvpForm input[name='nama']");
        if (rformNama) rformNama.value = @json($guest->name);
        var wName = document.getElementById("wishName");
        if (wName) wName.value = @json($guest->name);
      @endif
    });
  </script>
</body>
</html>
