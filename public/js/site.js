/**
 * Elite Physio Clinics — site interactions
 * Navbar scroll, stats counter, services tabs
 */

(function () {
  'use strict';

  const MOBILE_BREAKPOINT = 768;
  const TABLET_BREAKPOINT = 1024;

  function isMobile() {
    return window.innerWidth < MOBILE_BREAKPOINT;
  }

  function isTablet() {
    return window.innerWidth >= MOBILE_BREAKPOINT && window.innerWidth < TABLET_BREAKPOINT;
  }

  /* ── Navbar scroll & mobile menu ── */
  function initNavbar() {
    const nav = document.getElementById('main-nav');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuToggle = document.getElementById('menu-toggle');
    const menuIconOpen = document.getElementById('menu-icon-open');
    const menuIconClose = document.getElementById('menu-icon-close');

    if (!nav) return;

    let menuOpen = false;

    function applyNavStyles() {
      const mobile = isMobile();
      const scrolled = window.scrollY > 60;
      const active = scrolled || menuOpen;

      nav.style.padding = mobile ? '14px 20px' : scrolled ? '16px 48px' : '24px 48px';
      nav.style.background = active ? 'rgba(8,18,11,0.96)' : 'transparent';
      nav.style.backdropFilter = active ? 'blur(24px)' : 'none';
      nav.style.borderBottom = scrolled && !menuOpen ? '1px solid rgba(201,160,66,0.12)' : 'none';
    }

    function setMenuOpen(open) {
      menuOpen = open;
      if (mobileMenu) {
        mobileMenu.style.display = open && isMobile() ? 'flex' : 'none';
      }
      if (menuIconOpen) menuIconOpen.style.display = open ? 'none' : 'block';
      if (menuIconClose) menuIconClose.style.display = open ? 'block' : 'none';
      applyNavStyles();
    }

    window.addEventListener('scroll', applyNavStyles, { passive: true });

    window.addEventListener('resize', function () {
      if (!isMobile()) setMenuOpen(false);
      applyNavStyles();
    }, { passive: true });

    if (menuToggle) {
      menuToggle.addEventListener('click', function () {
        setMenuOpen(!menuOpen);
      });
    }

    if (mobileMenu) {
      mobileMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          setMenuOpen(false);
        });
      });
    }

    applyNavStyles();
  }

  /* ── Stats counter animation ── */
  function initStatsCounters() {
    const cards = document.querySelectorAll('[data-stat-value]');
    if (!cards.length) return;

    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          if (el.dataset.counted) return;
          el.dataset.counted = '1';

          const target = parseInt(el.dataset.statValue, 10);
          const suffix = el.dataset.statSuffix || '';
          let start = 0;

          function step(ts) {
            if (!start) start = ts;
            const progress = Math.min((ts - start) / 1800, 1);
            const count = Math.floor((1 - Math.pow(1 - progress, 3)) * target);
            el.textContent = count + suffix;
            if (progress < 1) requestAnimationFrame(step);
          }

          requestAnimationFrame(step);
          observer.unobserve(el);
        });
      },
      { threshold: 0.3 }
    );

    cards.forEach(function (card) {
      observer.observe(card);
    });
  }

  /* ── Services tabs ── */
  function initServicesTabs() {
    const section = document.getElementById('services');
    if (!section) return;

    const adultPanel = document.getElementById('services-panel-adult');
    const paediatricPanel = document.getElementById('services-panel-paediatric');
    const tabAdult = document.getElementById('tab-adult');
    const tabPaediatric = document.getElementById('tab-paediatric');
    const underlineAdult = document.getElementById('tab-underline-adult');
    const underlinePaediatric = document.getElementById('tab-underline-paediatric');
    const swipeHint = document.getElementById('services-swipe-hint');
    const panelArea = document.getElementById('services-panel-area');

    if (!adultPanel || !paediatricPanel) return;

    let activeTab = 'adult';

    function switchTab(tab) {
      if (tab === activeTab) return;
      activeTab = tab;

      adultPanel.style.display = tab === 'adult' ? 'block' : 'none';
      paediatricPanel.style.display = tab === 'paediatric' ? 'block' : 'none';

      if (tabAdult) {
        tabAdult.style.color = tab === 'adult' ? '#faf6ef' : 'rgba(250,246,239,0.35)';
      }
      if (tabPaediatric) {
        tabPaediatric.style.color = tab === 'paediatric' ? '#faf6ef' : 'rgba(250,246,239,0.35)';
      }
      if (underlineAdult) underlineAdult.style.display = tab === 'adult' ? 'block' : 'none';
      if (underlinePaediatric) underlinePaediatric.style.display = tab === 'paediatric' ? 'block' : 'none';

      if (swipeHint) swipeHint.style.display = 'none';
    }

    if (tabAdult) {
      tabAdult.addEventListener('click', function () {
        switchTab('adult');
      });
    }
    if (tabPaediatric) {
      tabPaediatric.addEventListener('click', function () {
        switchTab('paediatric');
      });
    }

    if (isMobile() && swipeHint) {
      setTimeout(function () {
        if (swipeHint) swipeHint.style.display = 'none';
      }, 3000);
    }

    if (panelArea && isMobile()) {
      let touchStartX = 0;
      panelArea.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });
      panelArea.addEventListener('touchend', function (e) {
        const diff = e.changedTouches[0].screenX - touchStartX;
        if (diff < -60) switchTab('paediatric');
        if (diff > 60) switchTab('adult');
      }, { passive: true });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    initNavbar();
    initStatsCounters();
    initServicesTabs();
  });
})();
