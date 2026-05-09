/**
 * Ichraka — script frontal (direction Joyeux).
 *
 *  - Compteurs animés (count-up) via IntersectionObserver
 *  - Toggle des paliers de don sur la home
 *  - Menu mobile (toggle nav)
 *  - Scroll fluide sur ancres internes
 *
 * Vanilla JS, ~3 Ko gzip.
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
        initDonateTiers();
        initMobileMenu();
        initSmoothScroll();
    }

    /* ----------------------------------------------------------------------
     * Compteurs animés (count-up)
     * -------------------------------------------------------------------- */
    function formatNum(n) {
        return n >= 1000 ? n.toLocaleString('fr-FR').replace(/,/g, ' ') : String(n);
    }

    function animateCount(el) {
        var target = parseInt(el.dataset.count, 10) || 0;
        var dur = 1600;
        var start = performance.now();
        function tick(now) {
            var t = Math.min(1, (now - start) / dur);
            var eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
            el.textContent = formatNum(Math.floor(target * eased));
            if (t < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = formatNum(target);
            }
        }
        requestAnimationFrame(tick);
    }

    function initCounters() {
        var counters = document.querySelectorAll('[data-count]');
        if (!counters.length) return;

        if (!('IntersectionObserver' in window)) {
            // Fallback : afficher la valeur cible directement
            counters.forEach(function (el) {
                el.textContent = formatNum(parseInt(el.dataset.count, 10) || 0);
            });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    animateCount(e.target);
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) { io.observe(el); });
    }

    /* ----------------------------------------------------------------------
     * Toggle paliers de don
     * -------------------------------------------------------------------- */
    function initDonateTiers() {
        var tiers = document.querySelectorAll('.tier');
        var amountLabel = document.getElementById('donate-amount');
        if (!tiers.length || !amountLabel) return;

        tiers.forEach(function (tier) {
            tier.addEventListener('click', function () {
                tiers.forEach(function (o) {
                    o.classList.remove('active');
                    o.setAttribute('aria-checked', 'false');
                });
                tier.classList.add('active');
                tier.setAttribute('aria-checked', 'true');

                var amount = parseInt(tier.dataset.amount, 10);
                amountLabel.textContent = amount.toLocaleString('fr-FR').replace(/,/g, ' ') + ' DH';
            });
        });
    }

    /* ----------------------------------------------------------------------
     * Menu mobile
     * -------------------------------------------------------------------- */
    function initMobileMenu() {
        var toggle = document.querySelector('.ichraka-nav-toggle');
        var nav = document.querySelector('.ichraka-nav');
        if (!toggle || !nav) return;

        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('menu-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Ferme le menu si on clique sur un lien
        nav.querySelectorAll('.ichraka-nav-links a').forEach(function (a) {
            a.addEventListener('click', function () {
                nav.classList.remove('menu-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /* ----------------------------------------------------------------------
     * Scroll fluide pour ancres internes
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
})();
