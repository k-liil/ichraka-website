/**
 * Ichraka — script frontal.
 *
 * Fonctionnalités :
 *  - Compteurs animés via IntersectionObserver
 *  - Scroll fluide pour les ancres
 *  - Lazy reveal des cartes (.ichraka-card)
 *  - Lightbox simple pour la galerie
 *
 * Aucune dépendance — ~3 Ko gzip.
 */
(function () {
    'use strict';

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initCounters();
        initSmoothScroll();
        initRevealOnScroll();
        initGalleryLightbox();
        initMobileMenuFocusTrap();
    }

    /* ----------------------------------------------------------------------
     * Compteurs animés
     * -------------------------------------------------------------------- */
    function initCounters() {
        var counters = document.querySelectorAll('.ichraka-counter__number');
        if (!counters.length || !('IntersectionObserver' in window)) {
            counters.forEach(function (c) {
                c.textContent = c.dataset.target + (c.dataset.suffix || '');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(function (c) { observer.observe(c); });
    }

    function animateCounter(el) {
        var target = parseInt(el.dataset.target, 10) || 0;
        var suffix = el.dataset.suffix || '';
        var duration = 1800;
        var startTime = performance.now();

        function tick(now) {
            var progress = Math.min((now - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
            var value = Math.floor(eased * target);
            el.textContent = formatNumber(value) + suffix;
            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = formatNumber(target) + suffix;
            }
        }
        requestAnimationFrame(tick);
    }

    function formatNumber(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    /* ----------------------------------------------------------------------
     * Scroll fluide
     * -------------------------------------------------------------------- */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var href = link.getAttribute('href');
                if (href.length < 2 || href === '#') return;
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    target.setAttribute('tabindex', '-1');
                    target.focus({ preventScroll: true });
                }
            });
        });
    }

    /* ----------------------------------------------------------------------
     * Reveal on scroll (cartes)
     * -------------------------------------------------------------------- */
    function initRevealOnScroll() {
        var prefersReduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduce || !('IntersectionObserver' in window)) return;

        var els = document.querySelectorAll('.ichraka-card, .ichraka-team-member, .ichraka-testimonial');
        els.forEach(function (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        els.forEach(function (el) { observer.observe(el); });
    }

    /* ----------------------------------------------------------------------
     * Lightbox simple pour les images de galerie
     * -------------------------------------------------------------------- */
    function initGalleryLightbox() {
        var galleries = document.querySelectorAll('.ichraka-gallery, .wp-block-gallery');
        if (!galleries.length) return;

        var overlay = null;

        galleries.forEach(function (gallery) {
            gallery.addEventListener('click', function (e) {
                var img = e.target.closest('img');
                if (!img) return;
                e.preventDefault();
                openLightbox(img.src, img.alt || '');
            });
        });

        function openLightbox(src, alt) {
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'ichraka-lightbox';
                overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;padding:2rem;cursor:zoom-out;';
                overlay.setAttribute('role', 'dialog');
                overlay.setAttribute('aria-modal', 'true');
                overlay.addEventListener('click', closeLightbox);
                document.body.appendChild(overlay);
            }
            overlay.innerHTML = '';
            var image = new Image();
            image.src = src;
            image.alt = alt;
            image.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain;border-radius:8px;';
            overlay.appendChild(image);
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.addEventListener('keydown', escClose);
        }

        function closeLightbox() {
            if (!overlay) return;
            overlay.style.display = 'none';
            document.body.style.overflow = '';
            document.removeEventListener('keydown', escClose);
        }

        function escClose(e) {
            if (e.key === 'Escape') closeLightbox();
        }
    }

    /* ----------------------------------------------------------------------
     * Focus trap menu mobile (basique)
     * -------------------------------------------------------------------- */
    function initMobileMenuFocusTrap() {
        var toggle = document.querySelector('.menu-toggle, .ast-mobile-menu-trigger-fill');
        var menu = document.querySelector('#primary-menu, .main-header-menu');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', function () {
            setTimeout(function () {
                if (menu.getAttribute('aria-expanded') === 'true' || menu.classList.contains('toggled-on')) {
                    var first = menu.querySelector('a');
                    if (first) first.focus();
                }
            }, 50);
        });
    }
})();
