/* =====================================================================
   TEMA ROYAL JAVA VINTAGE — efek tambahan ringan
     - parallax halus pada latar section (memakai properti `translate`,
       hanya berjalan saat section terlihat, mati bila reduced-motion)
     - panah gulir di hero
   ===================================================================== */
(function () {
  "use strict";
  const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* Panah gulir hero -> section berikutnya */
  const cue = document.querySelector(".hero__scroll");
  if (cue) cue.addEventListener("click", e => {
    const t = document.querySelector(cue.getAttribute("href"));
    if (!t) return;
    e.preventDefault();
    t.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "start" });
  });

  if (reduce) return;
  const items = Array.from(document.querySelectorAll(".hero__content, .std__bg, .live__bg, .closing__bg"))
    .map(el => ({ el, f: el.classList.contains("hero__content") ? 0.3 : 0.12, on: false }));
  items.forEach(i => i.el.setAttribute("data-parallax", ""));
  if (!items.length || !("IntersectionObserver" in window)) return;

  const io = new IntersectionObserver(es => es.forEach(e => {
    const it = items.find(i => i.el.parentElement === e.target);
    if (it) it.on = e.isIntersecting;
  }), { rootMargin: "10% 0px" });
  items.forEach(i => io.observe(i.el.parentElement));

  let ticking = false;
  function update() {
    ticking = false;
    const vh = window.innerHeight;
    for (const i of items) {
      if (!i.on) continue;
      const r = i.el.parentElement.getBoundingClientRect();
      const off = (r.top + r.height / 2 - vh / 2) * -i.f;
      i.el.style.translate = `0 ${off.toFixed(1)}px`;
    }
  }
  window.addEventListener("scroll", () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
  window.addEventListener("resize", update);
})();
