import './bootstrap';
import { animate, stagger } from 'animejs';
import '../css/app.css';

/* ================================================================
   POLMAN 375 — Global Animation System
   Runs on every page load + Livewire morph update
   ================================================================ */

// ── Core boot ────────────────────────────────────────────────────
function boot() {
    initIcons();
    animNavbar();
    animSidebar();
    animFadeUps();
    animTableRows();
    animStatCards();
    animFlash();
    setupDropdown();
    setupSidebarControls();
    setupInputGlow();
}

// ── Lucide icons ─────────────────────────────────────────────────
function initIcons() {
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
}

// ── Navbar entrance (once only) ──────────────────────────────────
function animNavbar() {
    const nav = document.querySelector('.p-navbar, #landingNav');
    if (!nav || nav.dataset.navAnim) return;
    nav.dataset.navAnim = '1';
    nav.style.opacity = '0';
    nav.style.transform = 'translateY(-100%)';
    animate(nav, {
        opacity: [0, 1],
        translateY: ['-100%', '0%'],
        duration: 580,
        easing: 'easeOutExpo'
    });
}

// ── Sidebar entrance (once only) ─────────────────────────────────
function animSidebar() {
    const sidebar = document.querySelector('.p-sidebar');
    if (!sidebar || sidebar.dataset.sideAnim) return;
    sidebar.dataset.sideAnim = '1';
    sidebar.style.opacity = '0';
    sidebar.style.transform = 'translateX(-100%)';
    animate(sidebar, {
        opacity: [0, 1],
        translateX: ['-100%', '0%'],
        duration: 620,
        delay: 80,
        easing: 'easeOutExpo'
    });

    const links = sidebar.querySelectorAll('.p-sidebar-link');
    links.forEach(l => { l.style.opacity = '0'; l.style.transform = 'translateX(-12px)'; });
    animate(Array.from(links), {
        opacity: [0, 1],
        translateX: ['-12px', '0px'],
        duration: 360,
        delay: stagger(38, { start: 380 }),
        easing: 'easeOutCubic'
    });
}

// ── .p-fade-in / [data-anim="fade-up"] elements ──────────────────
function animFadeUps() {
    const els = document.querySelectorAll('.p-fade-in:not([data-anim-done]), [data-anim="fade-up"]:not([data-anim-done])');
    if (!els.length) return;
    els.forEach(el => {
        el.dataset.animDone = '1';
        el.style.opacity = '0';
        el.style.transform = 'translateY(18px)';
    });
    animate(Array.from(els), {
        opacity: [0, 1],
        translateY: ['18px', '0px'],
        duration: 460,
        delay: stagger(70),
        easing: 'easeOutCubic'
    });
}

// ── Table row stagger ────────────────────────────────────────────
function animTableRows() {
    const rows = document.querySelectorAll(
        '.p-table tbody tr:not([data-rowed]), .pm-table tbody tr:not([data-rowed])'
    );
    if (!rows.length) return;
    rows.forEach(r => {
        r.dataset.rowed = '1';
        r.style.opacity = '0';
        r.style.transform = 'translateX(-10px)';
    });
    animate(Array.from(rows), {
        opacity: [0, 1],
        translateX: ['-10px', '0px'],
        duration: 360,
        delay: stagger(40),
        easing: 'easeOutCubic'
    });
}

// ── Stat cards ───────────────────────────────────────────────────
function animStatCards() {
    const stats = document.querySelectorAll(
        '.p-stat:not([data-stated]), .pm-stat:not([data-stated])'
    );
    if (!stats.length) return;
    stats.forEach(s => {
        s.dataset.stated = '1';
        s.style.opacity = '0';
        s.style.transform = 'scale(0.88) translateY(20px)';
    });
    animate(Array.from(stats), {
        opacity: [0, 1],
        scale: [0.88, 1],
        translateY: ['20px', '0px'],
        duration: 520,
        delay: stagger(85),
        easing: 'easeOutBack'
    });

    // Hover interactions
    stats.forEach(s => {
        if (s.dataset.hoverBound) return;
        s.dataset.hoverBound = '1';
        s.addEventListener('mouseenter', () =>
            animate(s, { scale: 1.035, duration: 200, easing: 'easeOutBack' })
        );
        s.addEventListener('mouseleave', () =>
            animate(s, { scale: 1, duration: 200, easing: 'easeOutQuad' })
        );
    });
}

// ── Flash alert ──────────────────────────────────────────────────
function animFlash() {
    const flashes = document.querySelectorAll(
        '.p-flash:not([data-flashed]), .pm-flash:not([data-flashed])'
    );
    flashes.forEach(el => {
        el.dataset.flashed = '1';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        animate(el, { opacity:[0,1], translateY:['-10px','0px'], duration:380, easing:'easeOutBack' });
    });
}

// ── Input focus glow ─────────────────────────────────────────────
function setupInputGlow() {
    const sel = '.p-input:not([data-glow]), .pm-input:not([data-glow]), .p-select-full:not([data-glow]), .pm-select-full:not([data-glow]), .pm-search:not([data-glow]), .pm-textarea:not([data-glow])';
    document.querySelectorAll(sel).forEach(inp => {
        inp.dataset.glow = '1';
        inp.addEventListener('focus', () =>
            animate(inp, { boxShadow: '0 0 0 3px rgba(85,136,163,0.2)', duration: 220 })
        );
        inp.addEventListener('blur', () =>
            animate(inp, { boxShadow: '0 0 0 0px rgba(85,136,163,0)', duration: 180 })
        );
    });
}

// ── User dropdown ────────────────────────────────────────────────
function setupDropdown() {
    const trigger  = document.getElementById('p-user-trigger');
    const dropdown = document.getElementById('p-user-dropdown');
    if (!trigger || !dropdown || trigger.dataset.dropBound) return;
    trigger.dataset.dropBound = '1';

    trigger.addEventListener('click', e => {
        e.stopPropagation();
        const showing = dropdown.classList.toggle('show');
        if (showing) {
            animate(dropdown, {
                opacity: [0, 1], scale: [0.92, 1], translateY: ['-8px', '0px'],
                duration: 220, easing: 'easeOutBack'
            });
        }
    });
    document.addEventListener('click', () => dropdown.classList.remove('show'));
}

// ── Sidebar controls ─────────────────────────────────────────────
function setupSidebarControls() {
    const sidebar  = document.querySelector('.p-sidebar');
    const overlay  = document.getElementById('p-sidebar-overlay');
    const toggle   = document.getElementById('p-sidebar-toggle');
    const collapse = document.getElementById('p-collapse-btn');

    // Mobile toggle
    if (toggle && sidebar && !toggle.dataset.bound) {
        toggle.dataset.bound = '1';
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay?.classList.toggle('show');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    // Desktop collapse
    if (collapse && sidebar && !collapse.dataset.bound) {
        collapse.dataset.bound = '1';
        collapse.style.display = window.innerWidth > 1024 ? 'inline-flex' : 'none';
        collapse.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            animate(collapse, {
                rotate: sidebar.classList.contains('collapsed') ? 180 : 0,
                duration: 320, easing: 'easeInOutQuad'
            });
        });
    }
}

// ── Button microinteraction (global hover via event delegation) ──
document.addEventListener('mouseover', e => {
    // Cek apakah e.target valid dan memiliki fungsi closest
    if (e.target && e.target.nodeType === 1) { 
        const btn = e.target.closest('.pm-btn:not(.pm-btn-icon)');
        if (btn) animate(btn, { scale: 1.03, duration: 180, easing: 'easeOutBack' });
    }
}, true);
document.addEventListener('mouseout', e => {
    if (e.target && e.target.nodeType === 1) {
        const btn = e.target.closest('.pm-btn:not(.pm-btn-icon)');
        if (btn) animate(btn, { scale: 1, duration: 180, easing: 'easeOutQuad' });
    }
}, true)

// ── Badge pop on first appearance ────────────────────────────────
function animBadges() {
    const badges = document.querySelectorAll(
        '.pm-badge:not([data-popped]), .p-badge:not([data-popped])'
    );
    badges.forEach(b => b.dataset.popped = '1');
    if (!badges.length) return;
    animate(Array.from(badges), {
        scale: [0.6, 1], opacity: [0, 1],
        duration: 300,
        delay: stagger(18),
        easing: 'easeOutBack'
    });
}

// ── Bootstrap ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    boot();
    animBadges();
});

// ── Livewire hooks ───────────────────────────────────────────────
document.addEventListener('livewire:navigated', () => {
    initIcons();
    animFadeUps();
    animTableRows();
    animStatCards();
    animFlash();
    animBadges();
    setupInputGlow();
});

if (typeof Livewire !== 'undefined') {
    Livewire.hook('morph.updated', () => {
        setTimeout(() => {
            initIcons();
            animFadeUps();
            animTableRows();
            animStatCards();
            animFlash();
            animBadges();
            setupInputGlow();
        }, 30);
    });
}

// Expose for manual use in Alpine/inline scripts
window.pmAnimate = animate;
window.pmStagger = stagger;