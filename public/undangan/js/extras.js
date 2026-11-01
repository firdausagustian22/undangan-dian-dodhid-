/* =====================================================================
   ART JAWA BIRU 3 — EXTRAS (opsional)
   Fitur dari versi sebelumnya yang TIDAK ada di desain referensi.
   Semuanya mati secara default; aktifkan lewat js/config.js -> extras.*
     - extras.introFan : pembuka kipas Jawa + gunungan sebelum cover
     - extras.petals   : kelopak bunga berjatuhan
     - extras.venue3d  : section "Pendopo 3D" + AR (model-viewer)
   Adegan joglo berlapis + parallax tetap tersedia untuk elemen
   [data-scene] / [data-hang] bila Anda menambahkannya di index.html.
   Gaya: css/extras.css.
   ===================================================================== */
(function () {
  "use strict";
  const cfg = window.weddingData || {};
  const X = cfg.extras || {};
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const cssUrl = src => `url("${String(src).replace(/["\\\n]/g, "\\$&")}")`;
  const LIB = { lottie: "assets/lib/lottie_canvas.min.js", modelViewer: "assets/lib/model-viewer-umd.min.js" };
  const scriptCache = {};
  function loadScript(src) {
    if (!scriptCache[src]) scriptCache[src] = new Promise((resolve, reject) => {
      const s = document.createElement("script");
      s.src = src; s.async = true; s.onload = resolve;
      s.onerror = () => { delete scriptCache[src]; reject(new Error("Gagal memuat " + src)); };
      document.head.appendChild(s);
    });
    return scriptCache[src];
  }
  function onceVisible(el, cb, rootMargin = "0px") {
    const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { io.disconnect(); cb(); } }), { rootMargin });
    io.observe(el);
  }
  function mediaKind(src) {
    const s = String(src || "").split(/[?#]/)[0].toLowerCase();
    if (/\.(mp4|webm|m4v|mov|ogv)$/.test(s)) return "video";
    if (/\.json$/.test(s)) return "lottie";
    if (/\.(glb|gltf)$/.test(s)) return "model";
    return "image";
  }
  function createMedia(src, opts = {}) {
    const kind = mediaKind(src);
    if (kind === "video") {
      const v = document.createElement("video");
      v.className = "media media--video"; v.muted = true; v.loop = true; v.playsInline = true; v.autoplay = !reduceMotion;
      v.setAttribute("muted", ""); v.setAttribute("playsinline", ""); v.src = src;
      return v;
    }
    if (kind === "lottie") {
      const d = document.createElement("div"); d.className = "media media--lottie";
      loadScript(LIB.lottie).then(() => window.lottie.loadAnimation({ container: d, renderer: "canvas", loop: true, autoplay: !reduceMotion, path: src })).catch(e => console.warn(e.message));
      return d;
    }
    const img = document.createElement("img");
    img.className = "media media--image"; img.src = src; img.alt = opts.alt || ""; img.decoding = "async";
    if (!opts.eager) img.loading = "lazy";
    return img;
  }
  let guestName = "", hasRealGuest = false;
  // Kompatibilitas nama kunci lama di dalam fungsi bawaan
  const cfgCompat = { ...cfg, intro: X.introFan || {}, venue3d: X.venue3d || {}, effects: { ...(cfg.effects || {}), petals: X.petals || {} } };
  /* ---------------- Bunga jatuh (legacy) ---------------- */
  function setupPetals() {
    const canvas = $("#petals"), conf = cfgCompat.effects?.petals || {};
    if (!canvas) return;
    if (conf.enabled === false || reduceMotion) { canvas.remove(); return; }
    const ctx = canvas.getContext("2d");
    const mkSprite = draw => { const c = document.createElement("canvas"); c.width = c.height = 64; draw(c.getContext("2d")); return c; };
    const petalPath = (x, edge, mid) => {
      const g = x.createLinearGradient(32, 6, 32, 58); g.addColorStop(0, edge); g.addColorStop(.45, mid); g.addColorStop(1, "#ffffff");
      x.fillStyle = g; x.beginPath(); x.moveTo(32, 58); x.bezierCurveTo(8, 44, 10, 12, 26, 8); x.quadraticCurveTo(32, 14, 38, 8); x.bezierCurveTo(54, 12, 56, 44, 32, 58); x.fill();
      x.strokeStyle = "rgba(40,80,150,.35)"; x.lineWidth = 1.2; x.beginPath(); x.moveTo(32, 52); x.quadraticCurveTo(30, 34, 32, 16); x.stroke();
    };
    const sprites = [
      mkSprite(x => petalPath(x, "#2f5ea8", "#a9c1e8")),   // kelopak biru
      mkSprite(x => petalPath(x, "#9db6dd", "#f1f5fc")),   // kelopak putih tepi biru
      mkSprite(x => {                                     // bunga kecil biru
        x.translate(32, 32);
        for (let i = 0; i < 5; i++) { x.save(); x.rotate(i / 5 * Math.PI * 2); const g = x.createLinearGradient(0, 0, 0, -24); g.addColorStop(0, "#dbe6f7"); g.addColorStop(1, "#4a78c2"); x.fillStyle = g; x.beginPath(); x.ellipse(0, -13, 7, 13, 0, 0, Math.PI * 2); x.fill(); x.restore(); }
        x.fillStyle = "#e5c06a"; x.beginPath(); x.arc(0, 0, 4.5, 0, Math.PI * 2); x.fill();
      })
    ];
    let w = 0, h = 0, dpr = 1, ps = [], raf = 0, last = 0;
    const count = Math.max(4, Math.min(60, conf.count || 18));
    function spawn(p, initial) {
      p.x = Math.random() * w; p.y = initial ? Math.random() * h : -30 - Math.random() * 80;
      p.size = 12 + Math.random() * 12; p.vy = .35 + Math.random() * .55; p.amp = 14 + Math.random() * 30;
      p.ph = Math.random() * 6.28; p.rot = Math.random() * 6.28; p.vr = (Math.random() - .5) * .03;
      p.flip = Math.random() * 6.28; p.vf = .02 + Math.random() * .04;
      p.s = sprites[Math.random() < .4 ? 0 : (Math.random() < .6 ? 1 : 2)]; p.base = p.x; return p;
    }
    function resize() {
      const r = canvas.getBoundingClientRect();
      dpr = Math.min(window.devicePixelRatio || 1, 2); w = r.width; h = r.height;
      canvas.width = Math.max(1, w * dpr); canvas.height = Math.max(1, h * dpr); ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      if (!ps.length) ps = Array.from({ length: count }, () => spawn({}, true));
    }
    function step(t) {
      const dt = Math.min(48, t - (last || t)) / 16; last = t;
      ctx.clearRect(0, 0, w, h);
      for (const p of ps) {
        p.y += p.vy * dt; p.ph += .012 * dt; p.rot += p.vr * dt; p.flip += p.vf * dt;
        if (p.y > h + 30) spawn(p, false);
        ctx.save(); ctx.translate(p.base + Math.sin(p.ph) * p.amp, p.y); ctx.rotate(p.rot); ctx.scale(Math.cos(p.flip), 1);
        ctx.globalAlpha = .88; ctx.drawImage(p.s, -p.size / 2, -p.size / 2, p.size, p.size); ctx.restore();
      }
      raf = requestAnimationFrame(step);
    }
    new ResizeObserver(resize).observe(canvas);
    resize();
    raf = requestAnimationFrame(step);
    document.addEventListener("visibilitychange", () => { if (document.hidden) cancelAnimationFrame(raf); else { last = 0; raf = requestAnimationFrame(step); } });
  }


  function buildScenes() {
    const sc = cfgCompat.scene || {};
    const root = document.documentElement.style;
    // URL dijadikan absolut: url() di variabel CSS dibaca relatif terhadap file CSS
    const abs = src => new URL(src, document.baseURI).href;
    if (sc.paper) root.setProperty("--paper", cssUrl(abs(sc.paper)));
    if (sc.paperTile) root.setProperty("--paper-tile", cssUrl(abs(sc.paperTile)));
    // Untaian bunga menggantung di sudut atas section [data-hang]
    if (sc.hanging) $$("[data-hang]").forEach(sec => {
      ["left", "right"].forEach(side => {
        const h = document.createElement("div");
        h.className = `hang hang--${side}`; h.setAttribute("aria-hidden", "true");
        h.appendChild(createMedia(sc.hanging, { eager: sec.id === "cover" }));
        sec.prepend(h);
      });
    });
    const hosts = $$("[data-scene]");
    if (sc.enabled === false || !(sc.layers || []).length) { hosts.forEach(h => (h.hidden = true)); return; }
    hosts.forEach(host => {
      const eager = !!host.closest("#cover");
      const keep = Array.from(host.children);   // mis. slot karakter
      (sc.layers || []).forEach((L, i) => {
        if (!L.src) return;
        const layer = document.createElement("div");
        layer.className = `layer layer--${L.id}`;
        layer.style.setProperty("--d", L.depth ?? 0);
        const box = document.createElement("div");
        box.className = "layer__media" + (L.anim ? ` anim-${L.anim}` : "") + (L.enter ? ` enter-${L.enter}` : "");
        const [x, y, bw, bh] = L.box || [0, 0, 100, 100];
        Object.assign(box.style, { left: `${x}%`, top: `${y}%`, width: `${bw}%`, height: `${bh}%` });
        if (L.origin) box.style.transformOrigin = L.origin;
        box.appendChild(createMedia(L.src, { eager }));
        layer.appendChild(box); host.appendChild(layer);
        if (L.id === "joglo") keep.forEach(k => host.appendChild(k)); // karakter berdiri di depan joglo
      });
      // kupu-kupu
      const n = sc.butterfly ? (sc.butterflies ?? 3) : 0;
      for (let i = 0; i < n; i++) {
        const b = document.createElement("span");
        b.className = `butterfly butterfly--${i % 3}`; b.setAttribute("aria-hidden", "true");
        const wing = document.createElement("span"); wing.className = "butterfly__wing";
        wing.style.backgroundImage = cssUrl(sc.butterfly);
        b.appendChild(wing); host.appendChild(b);
      }
      const sp = document.createElement("canvas"); sp.className = "sparkles"; sp.setAttribute("aria-hidden", "true");
      host.appendChild(sp);
      // animasi masuk + jeda animasi idle saat tidak terlihat (hemat baterai)
      new IntersectionObserver(es => es.forEach(e => {
        host.classList.toggle("is-visible", e.isIntersecting);
        if (e.isIntersecting && !host.closest("#cover")) host.classList.add("is-in");
      }), { rootMargin: "0px 0px -10% 0px" }).observe(host);
    });
  }

  /* Parallax 3D: lapisan adegan bergeser menurut kedalaman (mouse / kemiringan HP) */
  const Parallax = (() => {
    let tx = 0, ty = 0, cx = 0, cy = 0, raf = 0, layers = [], base = null, tiltOn = false;
    const on = cfgCompat.effects?.parallax !== false && !reduceMotion;
    const clamp = v => Math.max(-1, Math.min(1, v));
    function frame() {
      cx += (tx - cx) * .07; cy += (ty - cy) * .07;
      for (const [el, d] of layers) el.style.translate = `${(-cx * d * 16).toFixed(2)}px ${(-cy * d * 9).toFixed(2)}px`;
      raf = Math.abs(tx - cx) + Math.abs(ty - cy) > .002 ? requestAnimationFrame(frame) : 0;
    }
    function target(x, y) { tx = clamp(x); ty = clamp(y); if (!raf) raf = requestAnimationFrame(frame); }
    function onOrient(e) {
      if (e.gamma == null) return;
      if (!base) base = { b: e.beta, g: e.gamma };
      target((e.gamma - base.g) / 20, (e.beta - base.b) / 20);
    }
    function setup() {
      if (!on) return;
      layers = $$(".jscene .layer, .hang").map(el => [el, el.classList.contains("hang") ? .5 : (parseFloat(el.style.getPropertyValue("--d")) || 0)]);
      window.addEventListener("pointermove", e => { if (e.pointerType === "mouse") target((e.clientX / innerWidth - .5) * 2, (e.clientY / innerHeight - .5) * 2); }, { passive: true });
      if (!("DeviceOrientationEvent" in window) || typeof DeviceOrientationEvent.requestPermission !== "function") enableTilt();
      const items = $$("[data-parallax]");
      if (!items.length) return;
      let ticking = false;
      const update = () => {
        ticking = false; const vh = innerHeight;
        items.forEach(el => {
          const r = el.getBoundingClientRect();
          if (r.bottom < -100 || r.top > vh + 100) return;
          el.style.translate = `0 ${((r.top + r.height / 2 - vh / 2) * parseFloat(el.dataset.parallax)).toFixed(1)}px`;
        });
      };
      window.addEventListener("scroll", () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
    }
    function enableTilt() {
      if (!on || tiltOn) return; tiltOn = true;
      const D = window.DeviceOrientationEvent;
      if (D && typeof D.requestPermission === "function") {
        D.requestPermission().then(r => { if (r === "granted") window.addEventListener("deviceorientation", onOrient); }).catch(() => {});
      } else if (D) window.addEventListener("deviceorientation", onOrient);
    }
    return { setup, enableTilt };
  })();

  /* ---------------- Pembuka: kipas Jawa dengan gunungan ---------------- */
  const Intro = (() => {
    let el = null, ribsHost = null;
    let done = false, resolveFn;
    const finished = new Promise(r => (resolveFn = r));
    function finish() {
      if (done) return; done = true;
      el.classList.add("is-leaving");
      setTimeout(() => { el.remove(); resolveFn(); }, reduceMotion ? 0 : 900);
      document.querySelector("#cover")?.classList.add("is-ready");
    }
    function run() {
      el = $("#intro"); ribsHost = $("#introRibs");
      const c = cfgCompat.intro || {};
      if (!el) { resolveFn(); return finished; }
      if (c.enabled === false || !c.gunungan) { el.remove(); $("#cover").classList.add("is-ready"); resolveFn(); return finished; }
      const n = Math.max(5, Math.min(25, c.ribs || 13)), spread = c.spread || 168;
      for (let i = 0; i < n; i++) {
        const rib = document.createElement("span");
        rib.className = "intro__rib";
        const ang = -spread / 2 + (spread * i) / (n - 1);
        rib.style.setProperty("--a", `${ang.toFixed(2)}deg`);
        rib.style.setProperty("--w", (2 * Math.tan((spread / (n - 1) / 2 + .8) * Math.PI / 180) * 100).toFixed(2));
        rib.style.transitionDelay = `${Math.abs(i - (n - 1) / 2) * 45}ms`;
        if (i % 2) rib.classList.add("intro__rib--alt");
        ribsHost.appendChild(rib);
      }
      el.addEventListener("click", finish);
      el.addEventListener("keydown", finish);
      if (reduceMotion) { el.classList.add("is-open"); setTimeout(finish, 900); return finished; }
      requestAnimationFrame(() => el.classList.add("is-shown"));
      setTimeout(() => el.classList.add("is-open"), 1000);
      setTimeout(finish, 1000 + 1400 + (c.holdMs ?? 1500));
      return finished;
    }
    return { run };
  })();

  function setupVenue3D() {
    const v = cfgCompat.venue3d || {}, sec = $("#venue3d");
    if (!sec) return;
    if (!v.enabled || !v.model) { sec.hidden = true; $('#floatNav a[href="#venue3d"]')?.remove(); return; }
    const viewer = $("#venueViewer"), poster = $("#venuePoster"), status = $("#venueStatus"), btnAr = $("#btnAr");
    if (v.poster) poster.src = v.poster; else poster.remove();
    if (hasRealGuest) $("#btnArPage").href = `ar.html?${cfgCompat.guest?.urlParam || "to"}=${encodeURIComponent(guestName)}`;
    onceVisible(sec, async () => {
      if (location.protocol === "file:") { status.textContent = "Penampil 3D aktif saat undangan dibuka lewat server/hosting."; return; }
      status.textContent = "Memuat model 3D…";
      try { await loadScript(LIB.modelViewer); } catch (e) { status.textContent = "Model 3D gagal dimuat."; return; }
      const mv = document.createElement("model-viewer");
      const attrs = {
        src: v.model, alt: `Model 3D ${cfgCompat.venue3d?.titleScript || "pendopo"}`, "camera-controls": "", "touch-action": "pan-y",
        "interaction-prompt": "none", "shadow-intensity": "1", exposure: "1.05", "environment-image": "neutral",
        "camera-orbit": "32deg 72deg auto", "field-of-view": "26deg", "min-camera-orbit": "auto 30deg auto", "max-camera-orbit": "auto 88deg auto",
        ar: "", "ar-modes": "webxr scene-viewer quick-look", "ar-scale": "auto"
      };
      if (v.autoRotate !== false && !reduceMotion) Object.assign(attrs, { "auto-rotate": "", "auto-rotate-delay": "800", "rotation-per-second": "16deg" });
      if (v.iosModel) attrs["ios-src"] = v.iosModel;
      if (v.poster) attrs.poster = v.poster;
      Object.entries(attrs).forEach(([k, val]) => mv.setAttribute(k, val));
      const arSlot = document.createElement("span"); arSlot.slot = "ar-button"; arSlot.hidden = true; mv.appendChild(arSlot);
      mv.addEventListener("load", () => {
        status.textContent = ""; viewer.classList.add("is-loaded");
        if (mv.canActivateAR) { btnAr.hidden = false; btnAr.onclick = () => mv.activateAR(); }
      });
      mv.addEventListener("error", () => { status.textContent = "Model 3D gagal dimuat."; });
      viewer.appendChild(mv);
    }, "600px");
  }


  /* ---------------- Pembuka kipas: markup dibuat hanya bila aktif ---------------- */
  function buildIntroMarkup() {
    const d = document.createElement("div");
    d.className = "intro"; d.id = "intro"; d.setAttribute("role", "presentation");
    d.innerHTML = '<div class="intro__fan" id="introFan"><div class="intro__ribs" id="introRibs" aria-hidden="true"></div><div class="intro__gunungan"></div></div>' +
      '<div class="intro__text"><p class="intro__eyebrow">The Wedding Of</p><p class="intro__names script"></p></div><p class="intro__skip">Ketuk untuk melewati</p>';
    $(".intro__names", d).textContent = cfg.couple?.names || "";
    if (X.introFan?.gunungan) $(".intro__gunungan", d).appendChild(createMedia(X.introFan.gunungan, { eager: true }));
    document.body.prepend(d);
  }
  function buildVenueMarkup() {
    const v = X.venue3d || {};
    const s = document.createElement("section");
    s.id = "venue3d"; s.className = "section venue3d x-legacy";
    s.innerHTML = '<div class="heading-pair"><p class="heading-pair__script"></p><p class="heading-pair__caps"></p></div><p class="lead"></p>' +
      '<div class="venue3d__viewer" id="venueViewer"><img class="venue3d__poster" id="venuePoster" alt="" loading="lazy"><p class="venue3d__status" id="venueStatus" role="status"></p></div>' +
      '<div class="venue3d__actions"><button type="button" class="btn btn--primary" id="btnAr" hidden><span>Lihat di ruangan Anda</span></button><a class="btn btn--ghost" id="btnArPage" href="ar.html"><span>Buka halaman AR</span></a></div>';
    $(".heading-pair__script", s).textContent = v.titleScript || "";
    $(".heading-pair__caps", s).textContent = v.titleCaps || "";
    $(".lead", s).textContent = v.text || "";
    $("#acara")?.after(s);
  }

  window.JB3Extras = {
    /** Dipanggil oleh script.js sebelum cover dianimasikan. Mengembalikan Promise. */
    init(ctx = {}) {
      guestName = ctx.guestName || ""; hasRealGuest = !!ctx.hasRealGuest;
      if (X.petals?.enabled) {
        const c = document.createElement("canvas"); c.id = "petals"; c.className = "petals"; c.setAttribute("aria-hidden", "true");
        document.body.appendChild(c); setupPetals();
      }
      if (X.venue3d?.enabled && X.venue3d?.model) { buildVenueMarkup(); setupVenue3D(); }
      if ($$("[data-scene], [data-hang]").length) { buildScenes(); Parallax.setup(); }
      if (X.introFan?.enabled) { buildIntroMarkup(); return Intro.run(); }
      return Promise.resolve();
    },
    onOpen() { try { Parallax.enableTilt(); } catch (e) { /* abaikan */ } }
  };
})();
