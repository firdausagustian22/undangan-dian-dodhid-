/* =====================================================================
   ART JAWA BIRU 3 — script utama (vanilla JS, tanpa dependency)
   Data dibaca dari window.weddingData (js/config.js).
   Fitur opsional di luar desain referensi ada di js/extras.js.
   ===================================================================== */
(function () {
  "use strict";

  const cfg = window.weddingData;
  if (!cfg) { console.error("weddingData tidak ditemukan. Pastikan js/config.js dimuat sebelum js/script.js"); return; }

  /* ---------------- Helpers ---------------- */
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const animOn = cfg.effects?.animations !== false && !reduceMotion;

  function get(path, obj = cfg) {
    return String(path || "").split(".").reduce((o, k) => (o == null ? undefined : o[k]), obj);
  }
  function setMultiline(el, text) {
    el.textContent = "";
    String(text ?? "").split("\n").forEach((line, i) => {
      if (i) el.appendChild(document.createElement("br"));
      el.appendChild(document.createTextNode(line));
    });
  }
  const abs = src => new URL(src, document.baseURI).href;   // url() di variabel CSS relatif ke file CSS
  function cssUrl(src) { return `url("${abs(String(src)).replace(/["\\\n]/g, "\\$&")}")`; }
  function pad(n) { return String(n).padStart(2, "0"); }
  function onceVisible(el, cb, rootMargin = "0px") {
    if (!el) return;
    if (!("IntersectionObserver" in window)) { cb(); return; }
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) { io.disconnect(); cb(); } });
    }, { rootMargin });
    io.observe(el);
  }

  let toastTimer;
  function toast(msg) {
    const t = $("#toast");
    t.textContent = msg;
    t.classList.add("is-shown");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove("is-shown"), 2400);
  }

  /* ---------------- Nama tamu dari ?to= ---------------- */
  const fallbackGuest = cfg.guest?.fallbackName || "Nama Tamu";
  const guestName = (() => {
    let name = "";
    try {
      const params = new URLSearchParams(window.location.search);
      name = (params.get(cfg.guest?.urlParam || "to") || "").replace(/\s+/g, " ").trim(); // '+' & %20 sudah di-decode
    } catch (e) { name = ""; }
    if (name.length > 80) name = name.slice(0, 80);
    return name || fallbackGuest;
  })();
  const hasRealGuest = guestName !== fallbackGuest;
  // Parameter ?to= ada (walau isinya sama dengan fallback) -> isi otomatis kolom nama di form
  const hasGuestParam = (() => { try { return !!new URLSearchParams(location.search).get(cfg.guest?.urlParam || "to"); } catch (e) { return false; } })();

  /* ---------------- Meta ---------------- */
  function applyMeta() {
    if (cfg.meta?.title) document.title = cfg.meta.title;
    const setMeta = (sel, val) => { const m = $(sel); if (m && val) m.setAttribute("content", val); };
    setMeta('meta[name="description"]', cfg.meta?.description);
    setMeta('meta[property="og:title"]', cfg.meta?.title);
    setMeta('meta[property="og:description"]', cfg.meta?.description);
    setMeta('meta[property="og:image"]', cfg.meta?.ogImage);
  }

  /* ---------------- Background & ornamen ---------------- */
  function applyBackgrounds() {
    const b = cfg.backgrounds || {}, root = document.documentElement.style;
    if (b.page?.image) root.setProperty("--page-bg-image", cssUrl(b.page.image));
    if (b.page?.position) root.setProperty("--page-bg-pos", b.page.position);
    if (b.page?.overlay) root.setProperty("--page-bg-overlay", b.page.overlay);
    if (b.card) root.setProperty("--card-bg", cssUrl(b.card));
    if (cfg.ornaments?.butterflies) root.setProperty("--butterflies", cssUrl(cfg.ornaments.butterflies));
    if (cfg.effects?.coverExitMs) root.setProperty("--cover-exit", `${cfg.effects.coverExitMs}ms`);
    if (cfg.layout?.desktopBackdrop === "blur") document.body.classList.add("backdrop-blur");
    const footer = $("#footer");
    if (footer && cfg.footer?.background) footer.style.backgroundImage = cssUrl(cfg.footer.background);
  }

  /* ---------------- Data binding ---------------- */
  function bindData(root = document) {
    $$("[data-text]", root).forEach(el => { const v = get(el.dataset.text); if (v != null) el.textContent = v; });
    $$("[data-multiline]", root).forEach(el => setMultiline(el, get(el.dataset.multiline)));
    $$("[data-href]", root).forEach(el => {
      const v = get(el.dataset.href);
      if (v) el.href = v; else el.hidden = true;
    });
    $$("[data-src]", root).forEach(el => { const v = get(el.dataset.src); if (v) el.src = v; else el.hidden = true; });
    $$("[data-bg]", root).forEach(el => { const v = get(el.dataset.bg); if (v) el.style.backgroundImage = cssUrl(v); });
    $("#guestName").textContent = guestName;
  }

  /* ---------------- Render: Mempelai ---------------- */
  function renderPersons() {
    const tpl = $("#tpl-person"), flower = cfg.ornaments?.portraitFlower;
    ["bride", "groom"].forEach(key => {
      const p = cfg[key] || {}, host = $(`.person[data-person="${key}"]`);
      if (!host) return;
      const node = tpl.content.cloneNode(true);
      const photo = $(".person__photo img", node);
      photo.src = p.photo || ""; photo.alt = p.name || "";
      $$(".person__flower", node).forEach(f => { if (flower) f.src = flower; else f.remove(); });
      $(".person__nick", node).textContent = p.nickname || "";
      $(".person__name", node).textContent = p.name || "";
      setMultiline($(".person__parents", node), p.parents);
      const ig = $(".btn-pill--ig", node);
      if (p.instagram) ig.href = p.instagram; else ig.parentElement.remove();
      host.appendChild(node);
    });
  }

  /* ---------------- Render: Acara ---------------- */
  const dateFmt = new Intl.DateTimeFormat("id-ID", { weekday: "long", day: "numeric", month: "long", year: "numeric", timeZone: "Asia/Jakarta" });
  function renderEvents() {
    const list = $("#eventList"), tpl = $("#tpl-event");
    (cfg.event?.items || []).forEach(ev => {
      const node = tpl.content.cloneNode(true);
      const icon = $(".event-card__icon", node);
      if (ev.icon) icon.src = ev.icon; else icon.remove();
      $(".event-card__title", node).textContent = ev.title || "";
      const d = ev.start ? new Date(ev.start) : null;
      $(".event-card__date", node).textContent = ev.dateText || (d && !isNaN(d) ? dateFmt.format(d) : "");
      $(".event-card__time", node).textContent = ev.timeText || "";
      $(".event-card__venue", node).textContent = ev.venue || "";
      $(".event-card__address", node).textContent = ev.address || "";
      const map = $(".event-card__map", node);
      if (ev.mapsUrl) { map.href = ev.mapsUrl; map.setAttribute("aria-label", `Buka lokasi ${ev.title || ""} di Google Maps`); }
      else map.remove();
      list.appendChild(node);
    });
  }

  /* ---------------- Render: Gift ---------------- */
  function renderGift() {
    const wrap = $("#giftAccounts"), tpl = $("#tpl-account");
    (cfg.gift?.accounts || []).forEach(acc => {
      const node = tpl.content.cloneNode(true);
      $(".account__bank", node).textContent = acc.bank || "";
      $(".account__number", node).textContent = acc.number || "";
      $(".account__holder", node).textContent = acc.holder || "";
      const btn = $(".js-copy", node);
      btn.dataset.copy = acc.number || "";
      btn.setAttribute("aria-label", `Salin nomor rekening ${acc.bank || ""} ${acc.number || ""}`);
      wrap.appendChild(node);
    });
    if (!cfg.gift?.shipping?.enabled) {
      $("#giftShipping").hidden = true;
      const last = $$(".account .divider-heart", wrap).pop();
      if (last) last.hidden = true;
    }
    if (!cfg.gift?.confirm?.enabled) $("#giftConfirm").hidden = true;
  }

  /* ---------------- Render: Love story ---------------- */
  function renderStory() {
    const tl = $("#timeline"), tpl = $("#tpl-story");
    const items = cfg.loveStory?.items || [];
    if (!items.length) { $("#love-story").hidden = true; return; }
    items.forEach(it => {
      const node = tpl.content.cloneNode(true);
      const img = $(".timeline__thumb img", node);
      if (it.image) { img.src = it.image; img.alt = it.title || ""; } else $(".timeline__thumb", node).remove();
      $(".timeline__title", node).textContent = it.title || "";
      setMultiline($(".timeline__text", node), it.text);
      tl.appendChild(node);
    });
  }

  /* ---------------- Render: Galeri ---------------- */
  function renderGallery() {
    const g = cfg.gallery || {};
    const track = $("#carouselTrack");
    const slides = g.carousel || [];
    if (!slides.length) $("#carousel").hidden = true;
    // Slide diduplikasi agar marquee berputar mulus
    [...slides, ...slides, ...slides, ...slides].forEach((src, i) => {
      const fig = document.createElement("figure");
      fig.className = "carousel__slide";
      const img = document.createElement("img");
      img.src = src; img.alt = i < slides.length ? `Foto ${i + 1}` : ""; img.loading = "lazy"; img.decoding = "async";
      if (i >= slides.length) fig.setAttribute("aria-hidden", "true");
      fig.appendChild(img); track.appendChild(fig);
    });
    track.style.setProperty("--carousel-duration", `${Math.max(1, slides.length) * 2 * (g.carouselSpeed || 4000)}ms`);

    if (!g.youtubeId) $("#videoEmbed").hidden = true;
    else $("#ytFrame").title = g.youtubeTitle || "Video";

    const grid = $("#photoGrid");
    (g.photos || []).forEach((src, i) => {
      const b = document.createElement("button");
      b.type = "button"; b.className = "photo-grid__item";
      b.dataset.anim = "zoomIn"; b.dataset.delay = String((i % 3) * 120);
      b.setAttribute("aria-label", `Perbesar foto ${i + 1}`);
      const img = document.createElement("img");
      img.src = src; img.alt = ""; img.loading = "lazy"; img.decoding = "async";
      b.appendChild(img);
      b.addEventListener("click", () => Lightbox.open(i, b));
      grid.appendChild(b);
    });
    if (!(g.photos || []).length) grid.hidden = true;
  }

  function loadYouTube() {
    const id = cfg.gallery?.youtubeId, frame = $("#ytFrame");
    if (!id || frame.src) return;
    const p = new URLSearchParams({ autoplay: "1", mute: "1", loop: "1", playlist: id, playsinline: "1", rel: "0", modestbranding: "1" });
    frame.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}?${p}`;
  }

  /* ---------------- Render: Footer ---------------- */
  function renderFooter() {
    const f = cfg.footer || {};
    if (!f.enabled) { $("#footer").hidden = true; return; }
    const logo = $("#footerLogo");
    if (f.logo) logo.src = f.logo; else logo.remove();
    const iconMap = { link: "i-link", instagram: "i-instagram", whatsapp: "i-whatsapp" };
    const wrap = $("#footerLinks");
    (f.links || []).forEach(l => {
      if (!l.url) return;
      const a = document.createElement("a");
      a.href = l.url; a.target = "_blank"; a.rel = "noopener"; a.setAttribute("aria-label", l.label || l.type);
      const ic = document.createElement("i");
      ic.className = `ico ${iconMap[l.type] || "i-link"}`; ic.setAttribute("aria-hidden", "true");
      a.appendChild(ic); wrap.appendChild(a);
    });
  }

  /* ---------------- Live streaming ---------------- */
  function setupLive() {
    const ls = cfg.liveStreaming || {};
    if (ls.enabled === false) { $("#live").hidden = true; return; }
    if (ls.url) $("#btnLive").href = ls.url; else $("#liveBtnWrap").hidden = true;
  }

  /* ---------------- Save the date (Google Calendar) ---------------- */
  function gcalStamp(iso) { return new Date(iso).toISOString().replace(/[-:]/g, "").replace(/\.\d{3}/, ""); }
  function setupSaveTheDate() {
    const s = cfg.saveTheDate || {};
    const start = s.start || cfg.countdown?.target;
    const btn = $("#btnSaveDate");
    if (!start || isNaN(new Date(start))) { btn.parentElement.hidden = true; return; }
    const end = s.end && !isNaN(new Date(s.end)) ? s.end : new Date(new Date(start).getTime() + 2 * 3600e3).toISOString();
    const p = new URLSearchParams({
      action: "TEMPLATE",
      text: s.title || `Pernikahan ${cfg.couple?.names || ""}`,
      dates: `${gcalStamp(start)}/${gcalStamp(end)}`,
      details: s.details || "",
      location: s.location || "",
      ctz: s.timezone || "Asia/Jakarta"
    });
    btn.href = `https://calendar.google.com/calendar/render?${p}`;
  }

  /* ---------------- Countdown ----------------
     Target berupa waktu absolut (ISO + zona waktu), jadi hasilnya sama di
     zona waktu mana pun. Dihitung ulang tiap detik dari jam perangkat
     (tidak bergeser walau tab sempat tidur). */
  function setupCountdown() {
    const target = new Date(cfg.countdown?.target).getTime();
    const box = $("#countdown"), els = {};
    $$("[data-unit]", box).forEach(el => (els[el.dataset.unit] = el));
    if (isNaN(target)) { box.hidden = true; console.warn("countdown.target tidak valid di config.js"); return; }
    let timer = 0;
    function tick() {
      const left = target - Date.now();
      let diff = Math.max(0, left);
      const d = Math.floor(diff / 864e5); diff -= d * 864e5;
      const h = Math.floor(diff / 36e5); diff -= h * 36e5;
      const m = Math.floor(diff / 6e4); diff -= m * 6e4;
      const s = Math.floor(diff / 1e3);
      els.days.textContent = pad(d); els.hours.textContent = pad(h);
      els.minutes.textContent = pad(m); els.seconds.textContent = pad(s);
      if (left <= 0) {
        clearInterval(timer); timer = 0;
        box.classList.add("is-done");
        if (cfg.countdown?.doneText) $("#countdownDone").hidden = false;
      }
    }
    tick();
    if (target > Date.now()) timer = setInterval(tick, 1000);
    document.addEventListener("visibilitychange", () => { if (!document.hidden) tick(); });
  }

  /* ---------------- Background slideshow (Acara, RSVP) ---------------- */
  function setupSlideshows() {
    $$("[data-slideshow]").forEach(section => {
      const conf = cfg.backgrounds?.[section.dataset.slideshow];
      if (!conf || !(conf.images || []).length) return;
      const wrap = document.createElement("div");
      wrap.className = "bg-slideshow" + (conf.effect === "slide_left" ? " bg-slideshow--slide" : "");
      wrap.setAttribute("aria-hidden", "true");
      const tDur = conf.transition ?? 2000, sDur = conf.duration ?? 4000;
      const slides = conf.images.map(src => {
        const s = document.createElement("div"); s.className = "bg-slideshow__slide";
        s.style.transitionDuration = `${tDur}ms`;
        const img = document.createElement("div"); img.className = "bg-slideshow__img";
        img.style.setProperty("--kb-x", `${(Math.random() * 6 - 3).toFixed(1)}%`);
        img.style.setProperty("--kb-y", `${(Math.random() * 6 - 3).toFixed(1)}%`);
        img.style.backgroundImage = cssUrl(src);
        s.appendChild(img); wrap.appendChild(s);
        return s;
      });
      section.prepend(wrap);
      let idx = 0, timer = 0;
      const kenBurns = !!conf.kenBurns && !reduceMotion;
      function show(i) {
        slides.forEach((s, k) => {
          s.classList.toggle("is-active", k === i);
          s.classList.toggle("is-prev", k === idx && k !== i);
          if (k !== i && k !== idx) s.classList.remove("kb");
        });
        if (kenBurns) { slides[i].classList.remove("kb"); void slides[i].offsetWidth; slides[i].classList.add("kb"); }
        idx = i;
      }
      slides[0].style.transitionDuration = "0ms";
      slides[0].classList.add("is-active");
      requestAnimationFrame(() => (slides[0].style.transitionDuration = `${tDur}ms`));
      if (kenBurns) onceVisible(section, () => slides[idx].classList.add("kb"));
      if (slides.length > 1 && !reduceMotion) {
        // berjalan hanya saat section terlihat (hemat baterai)
        new IntersectionObserver(es => es.forEach(e => {
          if (e.isIntersecting && !timer) timer = setInterval(() => show((idx + 1) % slides.length), sDur + tDur);
          else if (!e.isIntersecting && timer) { clearInterval(timer); timer = 0; }
        })).observe(section);
      }
    });
  }

  /* ---------------- Animasi masuk saat discroll (Elementor-like) ---------------- */
  function setupAnimations() {
    const items = $$("main [data-anim]");
    if (!animOn || !("IntersectionObserver" in window)) return;
    items.forEach(el => el.classList.add("anim-wait"));
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        io.unobserve(el);
        const delay = parseInt(el.dataset.delay || "0", 10);
        setTimeout(() => {
          el.classList.remove("anim-wait");
          el.classList.add("animated", el.dataset.anim);
          if (el.dataset.speed) el.classList.add(`animated-${el.dataset.speed}`);
        }, delay);
      });
    }, { threshold: 0, rootMargin: "0px 0px -6% 0px" });
    items.forEach(el => io.observe(el));
  }

  /* Timeline love story: kartu meluncur dari kanan, ikon membesar */
  function setupTimeline() {
    const items = $$(".timeline__item");
    if (!animOn || !("IntersectionObserver" in window)) { items.forEach(t => t.classList.add("is-in")); return; }
    items.forEach(t => t.classList.add("is-wait"));
    const io = new IntersectionObserver(entries => entries.forEach(e => {
      if (!e.isIntersecting) return;
      io.unobserve(e.target);
      e.target.classList.remove("is-wait"); e.target.classList.add("is-in");
    }), { rootMargin: "0px 0px -15% 0px" });
    items.forEach(t => io.observe(t));
  }

  /* ---------------- Gelembung putih (pengganti PowerPack particles) ---------------- */
  function setupParticles() {
    const canvases = $$("canvas.particles");
    if (cfg.effects?.particles === false) { canvases.forEach(c => c.remove()); return; }
    canvases.forEach(canvas => {
      const ctx = canvas.getContext("2d");
      if (!ctx) return;
      let w = 0, h = 0, dpr = 1, pts = [], running = false, raf = 0, visible = false, last = 0;
      function resize() {
        const r = canvas.getBoundingClientRect();
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = r.width; h = r.height;
        canvas.width = Math.max(1, w * dpr); canvas.height = Math.max(1, h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        const n = Math.min(40, Math.max(10, Math.round((w * h) / 7000)));
        pts = Array.from({ length: n }, () => ({
          x: Math.random() * w, y: Math.random() * h,
          r: Math.random() < .25 ? 6 + Math.random() * 9 : 1.2 + Math.random() * 3,
          a: .08 + Math.random() * .22,
          vx: (Math.random() - .5) * .25, vy: (Math.random() - .5) * .25
        }));
        draw();
      }
      function draw() {
        ctx.clearRect(0, 0, w, h);
        for (const p of pts) {
          ctx.globalAlpha = p.a; ctx.fillStyle = "#fff";
          ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fill();
        }
        ctx.globalAlpha = 1;
      }
      function step(t) {
        const dt = Math.min(48, t - (last || t)) / 16; last = t;
        for (const p of pts) {
          p.x += p.vx * dt; p.y += p.vy * dt;
          if (p.x < -20) p.x = w + 20; else if (p.x > w + 20) p.x = -20;
          if (p.y < -20) p.y = h + 20; else if (p.y > h + 20) p.y = -20;
        }
        draw(); raf = requestAnimationFrame(step);
      }
      function start() { if (running || reduceMotion || document.hidden || !visible) return; running = true; last = 0; raf = requestAnimationFrame(step); }
      function stop() { running = false; cancelAnimationFrame(raf); }
      new IntersectionObserver(es => es.forEach(e => { visible = e.isIntersecting; visible ? start() : stop(); })).observe(canvas);
      document.addEventListener("visibilitychange", () => (document.hidden ? stop() : start()));
      if ("ResizeObserver" in window) new ResizeObserver(resize).observe(canvas); else resize();
    });
  }

  /* ---------------- Musik ---------------- */
  const Music = (() => {
    const audio = $("#song"), btn = $("#musicBtn"), icon = $(".audio__icon", btn);
    let playing = false, broken = !cfg.music?.src;
    if (!broken) { audio.src = cfg.music.src; audio.loop = cfg.music.loop !== false; audio.preload = "auto"; }
    audio.addEventListener("error", () => { broken = true; $("#audio-container").hidden = true; });
    function render() {
      icon.classList.toggle("i-pause", playing); icon.classList.toggle("i-play", !playing);
      btn.classList.toggle("audioRotate", playing && !reduceMotion);
      btn.setAttribute("aria-label", playing ? "Jeda musik" : "Putar musik");
      btn.setAttribute("aria-pressed", String(playing));
    }
    function play() {
      if (broken) return;
      let p;
      try { p = audio.play(); } catch (e) { playing = false; render(); return; }
      playing = true; render();
      if (p && p.catch) p.catch(() => { playing = false; render(); }); // autoplay ditolak browser
    }
    function pause() { audio.pause(); playing = false; render(); }
    btn.addEventListener("click", () => (playing ? pause() : play()));
    // Jeda saat tab disembunyikan, lanjut saat kembali (bila sebelumnya diputar)
    let resume = false;
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) { resume = playing; if (playing) { audio.pause(); } }
      else if (resume) { play(); }
    });
    return { play, show() { if (!broken) $("#audio-container").hidden = false; } };
  })();

  /* ---------------- Cover: masuk & buka ---------------- */
  function playCover() {
    const cover = $("#cover");
    const go = () => cover.classList.add("is-ready");
    if (document.fonts && document.fonts.ready) {
      Promise.race([document.fonts.ready, new Promise(r => setTimeout(r, 1200))]).then(go);
    } else go();
  }

  function openInvitation() {
    const cover = $("#cover"), main = $("#isi");
    const exitMs = reduceMotion ? 0 : (cfg.effects?.coverExitMs ?? 1500);
    // 1. buka scroll, 2. tampilkan isi di posisi paling atas
    document.body.classList.remove("is-locked");
    main.hidden = false;
    window.scrollTo(0, 0);
    // 3. musik (diizinkan karena dipicu klik)
    Music.play();
    Music.show();
    // 4. cover naik + memudar (referensi: animate({opacity:0, top:"-100%"}, 1500))
    cover.style.setProperty("--cover-exit", `${exitMs}ms`);
    requestAnimationFrame(() => cover.classList.add("is-leaving"));
    setTimeout(() => { cover.hidden = true; }, exitMs + 50);
    // 5. animasi hero
    $("#home").classList.add("is-opened");
    // 6. navigasi (opsional)
    if (cfg.features?.floatingNav) { $("#floatNav").hidden = false; document.body.classList.add("has-nav"); }
    window.JB3Extras?.onOpen?.();
    onceVisible($("#gallery"), loadYouTube, "400px");
    $("#home").setAttribute("tabindex", "-1");
    $("#home").focus({ preventScroll: true });
  }

  /* ---------------- Copy (rekening / alamat) ---------------- */
  async function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      try { await navigator.clipboard.writeText(text); return true; } catch (e) { /* fallback di bawah */ }
    }
    const ta = document.createElement("textarea");
    ta.value = text; ta.setAttribute("readonly", ""); ta.style.position = "fixed"; ta.style.top = "0"; ta.style.opacity = "0";
    document.body.appendChild(ta); ta.select(); ta.setSelectionRange(0, text.length);
    let ok = false; try { ok = document.execCommand("copy"); } catch (_) { ok = false; }
    ta.remove(); return ok;
  }
  function setupCopy() {
    document.addEventListener("click", async e => {
      const b = e.target.closest(".js-copy");
      if (!b) return;
      const text = b.dataset.copy || get(b.dataset.copyKey || "") || "";
      if (!text) return;
      const label = $("span", b), prev = label.textContent;
      const ok = await copyText(String(text));
      label.textContent = ok ? "Tersalin!" : "Gagal menyalin";
      toast(ok ? (b.dataset.done || "Disalin") : "Salin manual: " + text);
      setTimeout(() => (label.textContent = prev), 1500);
    });
  }

  /* ---------------- Lightbox ---------------- */
  const Lightbox = (() => {
    const box = $("#lightbox"), img = $("#lbImg"), count = $("#lbCount");
    const photos = () => cfg.gallery?.photos || [];
    let idx = 0, lastFocus = null, touchX = null;
    function show(i) {
      const list = photos(); if (!list.length) return;
      idx = (i + list.length) % list.length;
      img.classList.add("is-switching");
      const next = new Image();
      next.onload = next.onerror = () => { img.src = list[idx]; img.alt = `Foto ${idx + 1} dari ${list.length}`; img.classList.remove("is-switching"); };
      next.src = list[idx];
      count.textContent = `${idx + 1} / ${list.length}`;
    }
    function open(i, trigger) {
      lastFocus = trigger || document.activeElement;
      box.hidden = false; document.body.style.overflow = "hidden";
      requestAnimationFrame(() => box.classList.add("is-open"));
      show(i); $("#lbClose").focus();
    }
    function close() {
      box.classList.remove("is-open"); document.body.style.overflow = "";
      setTimeout(() => { box.hidden = true; img.removeAttribute("src"); }, 250);
      if (lastFocus) lastFocus.focus();
    }
    $("#lbClose").addEventListener("click", close);
    $("#lbPrev").addEventListener("click", () => show(idx - 1));
    $("#lbNext").addEventListener("click", () => show(idx + 1));
    box.addEventListener("click", e => { if (e.target === box || e.target.classList.contains("lightbox__stage")) close(); });
    document.addEventListener("keydown", e => {
      if (box.hidden) return;
      if (e.key === "Escape") close();
      else if (e.key === "ArrowLeft") show(idx - 1);
      else if (e.key === "ArrowRight") show(idx + 1);
      else if (e.key === "Tab") { // focus trap
        const f = $$("button", box); const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
    box.addEventListener("touchstart", e => { touchX = e.touches[0].clientX; }, { passive: true });
    box.addEventListener("touchend", e => {
      if (touchX == null) return;
      const dx = e.changedTouches[0].clientX - touchX; touchX = null;
      if (Math.abs(dx) > 50) show(idx + (dx < 0 ? 1 : -1));
    });
    return { open };
  })();

  /* ---------------- Backend / pengiriman ---------------- */
  const LS_KEYS = { rsvp: "jb3-rsvp", gift: "jb3-gift", wishes: "jb3-wishes" };
  function lsRead(k) { try { const v = JSON.parse(localStorage.getItem(k) || "[]"); return Array.isArray(v) ? v : []; } catch (e) { return []; } }
  function lsWrite(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); return true; } catch (e) { return false; } }

  async function postToBackend(type, data) {
    const res = await fetch(cfg.backend.endpoint, {
      method: "POST",
      headers: { "Content-Type": "text/plain;charset=utf-8" }, // tanpa preflight CORS (Apps Script)
      body: JSON.stringify({ type, ...data })
    });
    if (!res.ok) throw new Error("HTTP " + res.status);
    const json = await res.json().catch(() => ({ ok: true }));
    if (json.ok === false) throw new Error(json.error || "Gagal");
    return json;
  }
  function openWhatsApp(text) {
    const num = String(cfg.backend?.whatsapp || "").replace(/\D/g, "");
    window.open(`https://wa.me/${num}?text=${encodeURIComponent(text)}`, "_blank", "noopener");
  }
  function readFileB64(file) {
    return new Promise((resolve, reject) => {
      const r = new FileReader();
      r.onload = () => resolve(String(r.result).split(",")[1]);
      r.onerror = () => reject(r.error);
      r.readAsDataURL(file);
    });
  }

  function validate(form) {
    let ok = true, first = null;
    $$("[required]", form).forEach(f => {
      if (f.closest("[hidden]")) { f.classList.remove("is-invalid"); return; }
      const v = (f.value || "").trim();
      const min = parseInt(f.getAttribute("minlength") || "1", 10);
      const bad = v.length < min;
      f.classList.toggle("is-invalid", bad);
      f.setAttribute("aria-invalid", String(bad));
      if (bad) { ok = false; first = first || f; }
    });
    if (first) first.focus();
    return ok;
  }
  function formMsg(form, text, isError) {
    const m = $(".form__msg", form);
    m.textContent = text; m.classList.toggle("is-error", !!isError);
  }
  function busy(form, on) { const b = $('button[type="submit"]', form); b.disabled = on; b.setAttribute("aria-busy", String(on)); }
  function clearInvalidOnInput(form) {
    form.addEventListener("input", e => { if (e.target.classList.contains("is-invalid") && e.target.value.trim()) { e.target.classList.remove("is-invalid"); e.target.removeAttribute("aria-invalid"); } });
    form.addEventListener("change", e => { if (e.target.value) e.target.classList.remove("is-invalid"); });
  }

  /* ---------------- RSVP ---------------- */
  function setupRsvp() {
    const form = $("#rsvpForm"), r = cfg.rsvp || {};
    const fill = (sel, opts) => { sel.textContent = ""; (opts || []).forEach(o => { const op = document.createElement("option"); op.value = o; op.textContent = o; sel.appendChild(op); }); };
    fill($("#rsvpAttendance"), r.attendanceOptions);
    fill($("#rsvpCount"), r.guestCountOptions);
    if (hasGuestParam) form.nama.value = guestName;
    clearInvalidOnInput(form);
    const absent = () => form.kehadiran.value !== (r.attendanceOptions || ["Hadir"])[0];
    form.kehadiran.addEventListener("change", () => { $("#rsvpCountField").hidden = absent(); });
    form.addEventListener("submit", async e => {
      e.preventDefault();
      formMsg(form, "");
      if (!validate(form)) { formMsg(form, "Mohon isi nama dan pilih konfirmasi kehadiran.", true); return; }
      const jumlah = absent() ? "0 Orang" : form.jumlah.value;
      const n = parseInt(jumlah, 10);
      if (!absent() && (!(n >= 1) || (r.maxGuests && n > r.maxGuests))) {
        form.jumlah.classList.add("is-invalid");
        formMsg(form, `Jumlah tamu 1–${r.maxGuests || n} orang.`, true); return;
      }
      const data = { nama: form.nama.value.trim().slice(0, 80), kehadiran: form.kehadiran.value, jumlah, waktu: new Date().toISOString() };
      busy(form, true);
      try {
        if (cfg.backend?.endpoint) {
          await postToBackend("rsvp", data);
          formMsg(form, "Terima kasih, konfirmasi kehadiran Anda sudah terkirim.");
        } else if (cfg.backend?.whatsapp) {
          openWhatsApp(`Konfirmasi Kehadiran\nNama: ${data.nama}\nKehadiran: ${data.kehadiran}\nJumlah: ${data.jumlah}`);
          formMsg(form, "WhatsApp terbuka. Kirim pesannya untuk menyelesaikan konfirmasi.");
        } else {
          lsWrite(LS_KEYS.rsvp, [...lsRead(LS_KEYS.rsvp), data]);
          formMsg(form, "Konfirmasi tersimpan di perangkat ini. (Backend belum diatur di js/config.js)");
        }
        toast("Terima kasih atas konfirmasinya");
      } catch (err) {
        formMsg(form, "Gagal mengirim. Periksa koneksi lalu coba lagi.", true);
      } finally { busy(form, false); }
    });
  }

  /* ---------------- Konfirmasi gift ---------------- */
  function setupGiftForm() {
    const form = $("#giftForm");
    if (!form) return;
    if (hasGuestParam) form.nama.value = guestName;
    clearInvalidOnInput(form);
    const fileInput = form.bukti, nameEl = $(".file-input__name", form);
    const resetName = () => (nameEl.textContent = "No file chosen");
    fileInput.addEventListener("change", () => { nameEl.textContent = fileInput.files[0]?.name || "No file chosen"; });
    form.addEventListener("submit", async e => {
      e.preventDefault();
      formMsg(form, "");
      if (!validate(form)) { formMsg(form, "Mohon isi nama, nama bank, dan nominal.", true); return; }
      const file = fileInput.files[0];
      if (file && file.size > 5 * 1024 * 1024) { formMsg(form, "Ukuran bukti transfer maksimal 5 MB.", true); return; }
      const data = { nama: form.nama.value.trim(), bank: form.bank.value.trim(), nominal: form.nominal.value.trim(), ucapan: form.ucapan.value.trim(), waktu: new Date().toISOString() };
      busy(form, true);
      try {
        if (cfg.backend?.endpoint) {
          if (file) Object.assign(data, { fileName: file.name, mimeType: file.type || "application/octet-stream", fileData: await readFileB64(file) });
          await postToBackend("gift", data);
          formMsg(form, "Terima kasih, konfirmasi gift sudah terkirim.");
          form.reset(); resetName();
        } else if (cfg.backend?.whatsapp) {
          openWhatsApp(`Konfirmasi Gift\nNama: ${data.nama}\nBank: ${data.bank}\nNominal: ${data.nominal}\nUcapan: ${data.ucapan}${file ? "\n(Bukti transfer saya lampirkan di chat ini)" : ""}`);
          formMsg(form, file ? "WhatsApp terbuka. Lampirkan bukti transfer di chat." : "WhatsApp terbuka. Kirim pesannya untuk menyelesaikan konfirmasi.");
        } else {
          lsWrite(LS_KEYS.gift, [...lsRead(LS_KEYS.gift), { ...data, fileName: file?.name || "" }]);
          formMsg(form, "Konfirmasi tersimpan di perangkat ini. (Backend belum diatur di js/config.js)");
        }
        toast("Terima kasih atas tanda kasihnya");
      } catch (err) {
        formMsg(form, "Gagal mengirim. Periksa koneksi lalu coba lagi.", true);
      } finally { busy(form, false); }
    });
  }

  /* ---------------- Wishes / Guestbook ---------------- */
  const Wishes = (() => {
    const list = $("#wishList"), pager = $("#wishPager"), form = $("#wishForm"), tpl = $("#tpl-wish");
    const per = cfg.wishes?.perPage || 5;
    let data = [], page = 1, replyTo = null;

    function timeAgo(iso) {
      const s = Math.max(0, (Date.now() - new Date(iso).getTime()) / 1000);
      if (!isFinite(s)) return "";
      if (s < 60) return "baru saja";
      const units = [["tahun", 31536000], ["bulan", 2592000], ["minggu", 604800], ["hari", 86400], ["jam", 3600], ["menit", 60]];
      const out = []; let rest = s;
      for (const [label, sec] of units) {
        const v = Math.floor(rest / sec);
        if (v > 0) { out.push(`${v} ${label}`); rest -= v * sec; }
        if (out.length === 2) break;
      }
      return out.join(", ") + " lalu";
    }
    function makeItem(w) {
      const node = tpl.content.cloneNode(true);
      $(".wish", node).dataset.id = w.id;
      const av = $(".wish__avatar", node);
      if (cfg.wishes?.avatar) av.src = cfg.wishes.avatar; else av.remove();
      $(".wish__name", node).textContent = w.name;
      const badge = $(".wish__badge", node);
      if (w.attendance) { $(".wish__att", node).textContent = w.attendance; badge.classList.toggle("is-absent", w.attendance !== "Hadir"); }
      else badge.remove();
      $(".wish__text", node).textContent = w.message;
      $(".wish__time span", node).textContent = timeAgo(w.date);
      $(".wish__reply", node).addEventListener("click", () => startReply(w));
      return node;
    }
    function render() {
      const top = data.filter(w => !w.parentId).sort((a, b) => new Date(b.date) - new Date(a.date));
      const children = data.filter(w => w.parentId);
      $("#wishCount").textContent = data.length;
      $("#statHadir").textContent = top.filter(w => w.attendance === "Hadir").length;
      $("#statTidak").textContent = top.filter(w => w.attendance === "Tidak Hadir").length;
      const pages = Math.max(1, Math.ceil(top.length / per));
      page = Math.min(page, pages);
      list.textContent = "";
      if (!top.length) {
        const li = document.createElement("li"); li.className = "guestbook__empty"; li.textContent = "Belum ada ucapan. Kirim doa pertama untuk kedua mempelai.";
        list.appendChild(li);
      }
      top.slice((page - 1) * per, page * per).forEach(w => {
        const node = makeItem(w);
        const childUl = $(".wish__children", node);
        children.filter(c => String(c.parentId) === String(w.id))
          .sort((a, b) => new Date(a.date) - new Date(b.date))
          .forEach(c => { const cn = makeItem(c); $(".wish__reply", cn).remove(); childUl.appendChild(cn); });
        if (!childUl.children.length) childUl.remove();
        list.appendChild(node);
      });
      pager.textContent = "";
      if (pages > 1) for (let i = 1; i <= pages; i++) {
        const b = document.createElement("button"); b.type = "button"; b.textContent = i;
        b.setAttribute("aria-label", `Halaman ${i}`);
        if (i === page) b.setAttribute("aria-current", "page");
        b.addEventListener("click", () => { page = i; render(); list.scrollTop = 0; });
        pager.appendChild(b);
      }
    }
    function startReply(w) {
      replyTo = w;
      $("#replyTo").textContent = w.name;
      $("#replyNote").hidden = false;
      $("#wishAttendanceWrap").hidden = true;
      form.ucapan.focus();
    }
    function cancelReply() {
      replyTo = null;
      $("#replyNote").hidden = true;
      $("#wishAttendanceWrap").hidden = false;
    }
    async function load() {
      const seed = (cfg.wishes?.seed || []).map((w, i) => ({ id: `seed-${i}`, parentId: null, ...w }));
      if (cfg.backend?.endpoint) {
        list.innerHTML = '<li class="guestbook__loading">Memuat ucapan…</li>';
        try {
          const res = await fetch(`${cfg.backend.endpoint}?type=wishes`);
          const json = await res.json();
          data = Array.isArray(json.data) ? json.data : [];
          render(); return;
        } catch (e) { console.warn("Gagal memuat ucapan dari backend, memakai data lokal.", e); }
      }
      data = [...seed, ...lsRead(LS_KEYS.wishes)];
      render();
    }
    function setup() {
      if (hasGuestParam) $("#wishName").value = guestName;
      clearInvalidOnInput(form);
      $("#cancelReply").addEventListener("click", cancelReply);
      form.addEventListener("submit", async e => {
        e.preventDefault();
        formMsg(form, "");
        if (!validate(form)) { formMsg(form, "Isi nama, ucapan (min. 2 karakter), dan konfirmasi kehadiran.", true); return; }
        const entry = {
          id: `w-${Date.now()}-${Math.random().toString(36).slice(2, 7)}`,
          parentId: replyTo ? replyTo.id : null,
          name: form.nama.value.trim().slice(0, 60),
          message: form.ucapan.value.trim().slice(0, 1000),
          attendance: replyTo ? "" : form.kehadiran.value,
          date: new Date().toISOString()
        };
        busy(form, true);
        try {
          if (cfg.backend?.endpoint) await postToBackend("wish", entry);
          else lsWrite(LS_KEYS.wishes, [...lsRead(LS_KEYS.wishes), entry]);
          data.push(entry); page = 1;
          form.ucapan.value = ""; cancelReply();
          render(); list.scrollTop = 0; toast("Terima kasih atas ucapan dan doanya");
        } catch (err) {
          formMsg(form, "Gagal mengirim ucapan. Coba lagi.", true);
        } finally { busy(form, false); }
      });
      load();
      setInterval(() => { if (!document.hidden && data.length) render(); }, 60000);
    }
    return { setup };
  })();

  /* ---------------- Navigasi melayang (opsional) ---------------- */
  function setupNav() {
    if (!cfg.features?.floatingNav) return;
    const links = $$("#floatNav a");
    links.forEach(a => a.addEventListener("click", e => {
      const t = $(a.getAttribute("href")); if (!t) return;
      e.preventDefault();
      t.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "start" });
    }));
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        links.forEach(a => {
          const on = a.getAttribute("href") === `#${en.target.id}`;
          a.classList.toggle("is-active", on);
          if (on) a.setAttribute("aria-current", "true"); else a.removeAttribute("aria-current");
        });
      });
    }, { rootMargin: "-45% 0px -50% 0px" });
    links.forEach(a => { const s = $(a.getAttribute("href")); if (s) io.observe(s); });
  }

  /* ---------------- Ornamen bergoyang halus ---------------- */
  function setupOrnaments() {
    if (!animOn) return;
    $$(".person__flower").forEach(f => f.classList.add("is-sway"));
  }

  /* ---------------- Init ---------------- */
  if ("scrollRestoration" in history) history.scrollRestoration = "manual";
  window.addEventListener("beforeunload", () => window.scrollTo(0, 0));

  function safe(name, fn) { try { fn(); } catch (e) { console.error(`[undangan] ${name} gagal:`, e); } }

  safe("meta", applyMeta);
  safe("backgrounds", applyBackgrounds);
  safe("mempelai", renderPersons);
  safe("acara", renderEvents);
  safe("gift", renderGift);
  safe("love story", renderStory);
  safe("galeri", renderGallery);
  safe("footer", renderFooter);
  safe("data", () => bindData());
  safe("live", setupLive);
  safe("save the date", setupSaveTheDate);
  safe("countdown", setupCountdown);
  safe("slideshow", setupSlideshows);
  safe("animasi", setupAnimations);
  safe("timeline", setupTimeline);
  safe("partikel", setupParticles);
  safe("ornamen", setupOrnaments);
  safe("copy", setupCopy);
  safe("rsvp", setupRsvp);
  safe("gift form", setupGiftForm);
  safe("ucapan", () => Wishes.setup());
  safe("navigasi", setupNav);

  const extrasReady = window.JB3Extras ? window.JB3Extras.init({ guestName, hasRealGuest }) : Promise.resolve();
  Promise.resolve(extrasReady).catch(() => {}).then(playCover);

  $("#tombolbuka").addEventListener("click", openInvitation, { once: true });
})();
