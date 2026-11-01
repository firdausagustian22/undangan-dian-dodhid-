/* =====================================================================
   COVER / PEMBUKA — perilaku tambahan khusus cover Royal Java Vintage.
   Logika buka undangan (kunci scroll, musik, transisi, hero) tetap di
   js/script.js; file ini hanya:
     - menyusun nama mempelai bertumpuk dari couple.names ("A & B")
     - membuat debu emas melayang
     - efek riak pada tombol "Buka Undangan"
     - warna bilah browser (theme-color) cokelat selama cover tampil,
       lalu dikembalikan seperti semula setelah undangan dibuka
   ===================================================================== */
(function () {
  "use strict";
  const cfg = window.weddingData || {};
  const cover = document.getElementById("cover");
  if (!cover || !cover.classList.contains("cover--vintage")) return;
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* Nama mempelai: "Dodhid & Putri" -> Dodhid / & / Putri */
  try {
    const full = String(cfg.couple?.names || "");
    const parts = full.split("&").map(s => s.trim()).filter(Boolean);
    const h = document.getElementById("coverNames");
    const a = h.querySelector(".cover__name--a"), b = h.querySelector(".cover__name--b"), amp = h.querySelector(".cover__amp");
    if (parts.length >= 2) { a.textContent = parts[0]; b.textContent = parts.slice(1).join(" & "); }
    else { a.textContent = full; amp.remove(); b.remove(); }
    h.setAttribute("aria-label", full);
  } catch (e) { console.error("[cover] nama mempelai:", e); }

  /* Debu emas melayang (ringan: 14 titik CSS) */
  if (!reduceMotion) {
    const dust = cover.querySelector(".cover__dust");
    for (let i = 0; i < 14; i++) {
      const d = document.createElement("i");
      d.style.left = `${(Math.random() * 100).toFixed(1)}%`;
      d.style.setProperty("--s", `${(1.5 + Math.random() * 2.5).toFixed(1)}px`);
      d.style.setProperty("--d", `${(7 + Math.random() * 7).toFixed(1)}s`);
      d.style.setProperty("--delay", `${(-Math.random() * 12).toFixed(1)}s`);
      d.style.setProperty("--x", `${(Math.random() * 60 - 30).toFixed(0)}px`);
      d.style.setProperty("--o", (0.35 + Math.random() * 0.45).toFixed(2));
      dust.appendChild(d);
    }
  }

  /* Warna bilah browser mengikuti cover, dipulihkan setelah dibuka */
  const meta = document.querySelector('meta[name="theme-color"]');
  const originalTheme = meta ? meta.getAttribute("content") : null;
  if (meta) meta.setAttribute("content", "#3E2723");

  /* Riak pada tombol + pemulihan theme-color */
  const btn = document.getElementById("tombolbuka");
  btn.addEventListener("click", e => {
    if (!reduceMotion) {
      const r = btn.getBoundingClientRect();
      const s = document.createElement("span");
      s.className = "cover__ripple";
      s.style.left = `${(e.clientX || r.left + r.width / 2) - r.left}px`;
      s.style.top = `${(e.clientY || r.top + r.height / 2) - r.top}px`;
      btn.appendChild(s);
      setTimeout(() => s.remove(), 800);
    }
    const ms = reduceMotion ? 0 : (cfg.effects?.coverExitMs ?? 1500);
    setTimeout(() => { if (meta && originalTheme) meta.setAttribute("content", originalTheme); }, ms);
  }, { once: true });
})();
