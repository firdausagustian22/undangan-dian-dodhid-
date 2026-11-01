/* =====================================================================
   Halaman AR (ar.html) — tujuan QR code yang dicetak di undangan fisik.
   Tamu memindai QR → halaman ini → model 3D tampil di ruangan lewat
   kamera HP (Android: WebXR / Scene Viewer, iPhone: Quick Look).
   Data diambil dari js/config.js (venue3d.*, couple.names).
   ===================================================================== */
(function () {
  "use strict";
  const cfg = window.weddingData || {};
  const v = cfg.extras?.venue3d || cfg.venue3d || {};
  const $ = s => document.querySelector(s);
  const note = $("#arNote"), viewer = $("#arViewer");

  $("#arNames").textContent = cfg.couple?.names || "";
  if (cfg.meta?.title) document.title = `AR · ${cfg.meta.title}`;

  // Bawa ?to=Nama ke tautan kembali ke undangan
  const to = new URLSearchParams(location.search).get(cfg.guest?.urlParam || "to");
  if (to) $("#arBack").href = `index.html?${cfg.guest?.urlParam || "to"}=${encodeURIComponent(to)}`;

  if (!v.model) { note.textContent = "Model 3D belum diatur di js/config.js (venue3d.model)."; return; }
  if (location.protocol === "file:") { note.textContent = "Halaman AR hanya berjalan lewat hosting (https)."; return; }

  note.textContent = "Memuat model 3D…";
  const s = document.createElement("script");
  s.src = "assets/lib/model-viewer-umd.min.js";
  s.onerror = () => { note.textContent = "Penampil 3D gagal dimuat. Periksa koneksi lalu muat ulang."; };
  s.onload = () => {
    const mv = document.createElement("model-viewer");
    const attrs = {
      src: v.model, alt: `Model 3D ${v.titleScript || "pendopo"}`,
      ar: "", "ar-modes": "webxr scene-viewer quick-look", "ar-scale": "auto", "ar-placement": "floor",
      "camera-controls": "", "touch-action": "pan-y", "interaction-prompt": "none",
      "auto-rotate": "", "rotation-per-second": "16deg", "camera-orbit": "32deg 72deg auto", "field-of-view": "28deg",
      "shadow-intensity": "1", exposure: "1.05", "environment-image": "neutral"
    };
    if (v.iosModel) attrs["ios-src"] = v.iosModel;
    if (v.poster) attrs.poster = v.poster;
    Object.entries(attrs).forEach(([k, val]) => mv.setAttribute(k, val));

    // Tombol AR milik kita (model-viewer hanya menampilkannya bila AR didukung)
    const btn = document.createElement("button");
    btn.type = "button"; btn.slot = "ar-button"; btn.className = "btn btn--primary ar__arbtn";
    btn.innerHTML = '<i class="ico i-ar" aria-hidden="true"></i><span>Tampilkan di ruangan Anda</span>';
    mv.appendChild(btn);

    mv.addEventListener("load", () => {
      if (mv.canActivateAR) {
        note.textContent = "";
        $("#arSteps").hidden = false;
      } else {
        note.textContent = "Perangkat ini belum mendukung AR. Model tetap bisa diputar dan diperbesar. Untuk AR, buka halaman ini di HP Android (Chrome) atau iPhone (Safari).";
      }
    });
    mv.addEventListener("ar-status", e => {
      if (e.detail.status === "failed") note.textContent = "AR gagal dimulai. Pastikan izin kamera diberikan, lalu coba lagi.";
    });
    mv.addEventListener("error", () => { note.textContent = "Model 3D gagal dimuat."; });
    viewer.appendChild(mv);
  };
  document.head.appendChild(s);
})();
