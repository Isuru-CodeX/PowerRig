'use strict';

/**
 * Universal Event Listener Utility Wrapper with Null Safeguards
 */
const addEventOnElem = function (elem, type, callback) {
  if (!elem) return;
  if (elem.nodeType || elem === window) {
    elem.addEventListener(type, callback);
    return;
  }
  if (elem.length > 0) {
    for (let i = 0; i < elem.length; i++) {
      if (elem[i]) elem[i].addEventListener(type, callback);
    }
  }
}

/**
 * Mobile Navigation Menu Toggles - Guarded against structural variations
 */
const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const overlay = document.querySelector("[data-overlay]");

if (navTogglers.length > 0 && navbar && overlay) {
  const toggleNavbar = function () {
    navbar.classList.toggle("active");
    overlay.classList.toggle("active");
  }
  addEventOnElem(navTogglers, "click", toggleNavbar);
}

if (navbarLinks.length > 0 && navbar && overlay) {
  const closeNavbar = function () {
    navbar.classList.remove("active");
    overlay.classList.remove("active");
  }
  addEventOnElem(navbarLinks, "click", closeNavbar);
}

/**
 * Header Scrolling and Back-to-Top Navigation Components Tracking
 */
const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

if (header || backTopBtn) {
  const headerActive = function () {
    if (window.scrollY > 150) {
      if (header) header.classList.add("active");
      if (backTopBtn) backTopBtn.classList.add("active");
    } else {
      if (header) header.classList.remove("active");
      if (backTopBtn) backTopBtn.classList.remove("active");
    }
  }
  addEventOnElem(window, "scroll", headerActive);
}

let lastScrolledPos = 0;
if (header) {
  const headerSticky = function () {
    if (lastScrolledPos >= window.scrollY) {
      header.classList.remove("header-hide");
    } else {
      header.classList.add("header-hide");
    }
    lastScrolledPos = window.scrollY;
  }
  addEventOnElem(window, "scroll", headerSticky);
}

/**
 * Element Viewport Scroll-Reveal Transitions Trigger
 */
const sections = document.querySelectorAll("[data-section]");
if (sections.length > 0) {
  const scrollReveal = function () {
    for (let i = 0; i < sections.length; i++) {
      if (sections[i].getBoundingClientRect().top < window.innerHeight / 1.5) {
        sections[i].classList.add("active");
      }
    }
  }
  addEventOnElem(window, "scroll", scrollReveal);
  addEventOnElem(window, "load", scrollReveal);
}

/**
 * =========================================================================
 * FIXED INFINITE HARDWARE HERO CAROUSEL ENGINE
 * =========================================================================
 */
document.addEventListener("DOMContentLoaded", function () {
  const slides = Array.from(document.querySelectorAll("[data-carousel-slide]"));
  const prevBtn = document.querySelector("[data-carousel-prev]");
  const nextBtn = document.querySelector("[data-carousel-next]");
  const indicatorBar = document.querySelector("[data-carousel-indicators]");
  
  // Exit gracefully if current view does not render the hero slider module
  if (slides.length === 0) {
    return;
  }

  let currentIdx = 0;
  let cycleTimer = null;

  // 1. Compile pagination indicator dot array mapping structures dynamically
  if (indicatorBar) {
    indicatorBar.innerHTML = ""; 
    slides.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.classList.add("ind-dot");
      if (i === 0) dot.classList.add("active");
      
      dot.addEventListener("click", function() {
        renderSlideFrame(i);
      });
      indicatorBar.appendChild(dot);
    });
  }
  
  const dots = Array.from(document.querySelectorAll(".carousel-indicator-bar .ind-dot"));

  // 2. Structural Slide Animation Core Rendering Pipeline
  function renderSlideFrame(targetIdx) {
    // Unset flags from active viewport item
    slides[currentIdx].classList.remove("active");
    if (dots.length > 0 && dots[currentIdx]) {
      dots[currentIdx].classList.remove("active");
    }
    
    // Circulate current index bounds cleanly
    currentIdx = (targetIdx + slides.length) % slides.length;
    
    // Set active visualization classes on the target hardware banner
    slides[currentIdx].classList.add("active");
    if (dots.length > 0 && dots[currentIdx]) {
      dots[currentIdx].classList.add("active");
    }
    
    // Keep user's custom directional triggers fluent by resetting timer
    initAutoplayTimer();
  }

  // 3. Automated Slide Cycle Ticker Config (Continuous slide change every 5 seconds)
  function initAutoplayTimer() {
    clearInterval(cycleTimer);
    cycleTimer = setInterval(() => {
      renderSlideFrame(currentIdx + 1);
    }, 5000); 
  }

  // 4. Attach Event Observers to Manual UI Control Elements
  if (prevBtn) {
    prevBtn.addEventListener("click", function (e) {
      e.preventDefault();
      renderSlideFrame(currentIdx - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function (e) {
      e.preventDefault();
      renderSlideFrame(currentIdx + 1);
    });
  }

  // Engage ticker loops actively on engine start
  initAutoplayTimer();
});