(function () {
  const root = document.querySelector("#testimonials .tms-slider");
  const track = root.querySelector(".tms-track");
  const slides = Array.from(root.querySelectorAll(".tms-slide"));
  const dots = Array.from(root.querySelectorAll(".tms-dot"));
  const prev = root.querySelector(".tms-prev");
  const next = root.querySelector(".tms-next");

  let index = 0;
  const last = slides.length - 1;
  const AUTOPLAY_MS = 7000;
  let timer = null;

  function go(i) {
    index = (i + slides.length) % slides.length;
    track.style.transform = `translateX(${-100 * index}%)`;
    dots.forEach((d, k) =>
      d.setAttribute("aria-selected", k === index ? "true" : "false")
    );
  }

  function nextSlide() {
    go(index + 1);
  }
  function prevSlide() {
    go(index - 1);
  }

  function start() {
    stop();
    timer = setInterval(nextSlide, AUTOPLAY_MS);
  }
  function stop() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  }

  // events
  next.addEventListener("click", () => {
    nextSlide();
    start();
  });
  prev.addEventListener("click", () => {
    prevSlide();
    start();
  });
  dots.forEach((dot, i) =>
    dot.addEventListener("click", () => {
      go(i);
      start();
    })
  );

  root.addEventListener("mouseenter", stop);
  root.addEventListener("mouseleave", start);
  document.addEventListener("visibilitychange", () =>
    document.hidden ? stop() : start()
  );

  // init
  go(0);
  start();
})();

// WhatsApp Link
const phone = "97431101550";
const defaultMessage = encodeURIComponent(
  "Hi, I'm messaging from the Leaders Education website: *Please Type your following enquiry after this text*"
);
const whatsappLink = `https://wa.me/${phone}?text=${defaultMessage}`;
const whatsappAnchor = document.getElementById("whatsappLink");
if (whatsappAnchor) {
  whatsappAnchor.setAttribute("href", whatsappLink);
}

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".video-launch").forEach(function (btn) {
    btn.addEventListener(
      "click",
      function () {
        const wrap = btn.closest(".video-wrap");
        const src = btn.dataset.video;

        // Create HTML5 <video> with autoplay
        const video = document.createElement("video");
        video.setAttribute("controls", "");
        video.setAttribute("autoplay", "");
        video.setAttribute("playsinline", "");
        video.setAttribute("preload", "metadata");

        const source = document.createElement("source");
        source.src = src;
        source.type = "video/mp4"; // adjust if you use other mime types
        video.appendChild(source);

        wrap.innerHTML = ""; // remove poster/button
        wrap.appendChild(video); // insert video player
        video.focus(); // accessibility: focus player
      },
      { once: true }
    ); // run once; leave player in place
  });
});
