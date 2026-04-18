import { animate, stagger, createTimer } from 'animejs';

class Polman375Landing {
    constructor() {
        try {
            this.init();
        } catch(e) {
            console.error('[Init] Error:', e);
        }
    }

    init() {
        const root = document.querySelector('#landing-root');
        if (!root) return;

        // Nav entrance — slide down + fade
        const nav = document.querySelector('#landingNav');
        if (nav) {
            nav.style.opacity = '0';
            nav.style.transform = 'translateY(-100%)';
            animate(nav, {
                opacity: [0, 1],
                translateY: ['-100%', '0%'],
                duration: 700,
                easing: 'easeOutExpo'
            });
        }

        // Hero 1 — staggered text entrance
        this.animateHero1Entrance();

        this.handleResize();

        try { this.setupHero1Slider(); } catch(e) { console.error('[Hero1Slider]', e); }
        try { this.setupHero2(); }       catch(e) { console.error('[Hero2]', e); }
        try { this.setupHero3Tabs(); }   catch(e) { console.error('[Hero3Tabs]', e); }
        try { this.setupScrollAnimations(); } catch(e) { console.error('[Scroll]', e); }
    }

    // ─────────────────────────────────────────
    //  SAFE ANIMATE WRAPPER
    // ─────────────────────────────────────────
    safeAnimate(target, params = {}) {
        if (!target) return { finished: Promise.resolve() };
        try {
            return animate(target, params);
        } catch (e) {
            console.warn('AnimeJS error:', e.message);
            return { finished: Promise.resolve() };
        }
    }

    // ─────────────────────────────────────────
    //  HERO 1 — entrance animation
    // ─────────────────────────────────────────
    animateHero1Entrance() {
        const targets = [
            document.querySelector('#hero1 p.text-sm'),
            document.querySelector('#hero1 h1'),
            document.querySelector('#hero1 .hero-copy'),
            document.querySelector('#hero1 .grid.gap-4'),
        ].filter(Boolean);

        targets.forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
        });

        animate(targets, {
            opacity: [0, 1],
            translateY: ['30px', '0px'],
            duration: 700,
            delay: stagger(120, { start: 400 }),
            easing: 'easeOutCubic'
        });

        // Slider card — scale + fade
        const sliderContainer = document.querySelector('.slider-container');
        if (sliderContainer) {
            sliderContainer.style.opacity = '0';
            sliderContainer.style.transform = 'scale(0.95) translateY(20px)';
            animate(sliderContainer, {
                opacity: [0, 1],
                scale: [0.95, 1],
                translateY: ['20px', '0px'],
                duration: 900,
                delay: 600,
                easing: 'easeOutExpo'
            });
        }
    }

    // ─────────────────────────────────────────
    //  HERO 1 — btn hover (micro-interaction)
    // ─────────────────────────────────────────
    setupHero1Slider() {
        const btns = document.querySelectorAll('.btn-hero');
        btns.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                this.safeAnimate(btn, { scale: 1.06, duration: 250, easing: 'easeOutBack' });
            });
            btn.addEventListener('mouseleave', () => {
                this.safeAnimate(btn, { scale: 1, duration: 250, easing: 'easeOutQuad' });
            });
        });
    }

    // ─────────────────────────────────────────
    //  HERO 2 — kategori cards hover
    // ─────────────────────────────────────────
    setupHero2() {
        const cards = document.querySelectorAll('.kategori-card');
        const detailBox = document.querySelector('#hero-2-detail-panel');

        if (!cards.length) return;

        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                const title = card.getAttribute('data-title') || 'Pilih Kartu';
                const desc  = card.getAttribute('data-desc')  || '';

                if (detailBox) {
                    detailBox.style.borderColor = 'rgba(85,136,163,0.6)';
                    detailBox.innerHTML = `
                        <div class="p-4">
                            <p class="text-xs uppercase tracking-widest mb-2" style="color:#5588A3;">Detail Kategori</p>
                            <h2 class="text-3xl font-black mb-3" style="color:#E8E8E8;">${title}</h2>
                            <p class="text-base leading-relaxed" style="color:rgba(232,232,232,0.65);">${desc}</p>
                            <div class="flex gap-2 mt-4">
                                <span class="px-3 py-1.5 text-xs rounded-full" style="background:rgba(85,136,163,0.15);border:1px solid rgba(85,136,163,0.3);color:#5588A3;">Status: Aktif</span>
                                <span class="px-3 py-1.5 text-xs rounded-full" style="background:rgba(85,136,163,0.15);border:1px solid rgba(85,136,163,0.3);color:#5588A3;">ISO 45001</span>
                            </div>
                        </div>`;
                    this.safeAnimate(detailBox, { scale: [0.98, 1], opacity: [0.7, 1], duration: 350, easing: 'easeOutBack' });
                }

                this.safeAnimate(card, { scale: 1.05, translateY: '-6px', duration: 300, easing: 'easeOutBack' });
                cards.forEach(c => {
                    if (c !== card) this.safeAnimate(c, { opacity: 0.45, scale: 0.97, duration: 250 });
                });
            });

            card.addEventListener('mouseleave', () => {
                if (detailBox) detailBox.style.borderColor = 'rgba(20,83,116,0.5)';
                this.safeAnimate(cards, { scale: 1, opacity: 1, translateY: '0px', duration: 300, easing: 'easeOutQuad' });
            });
        });
    }

    // ─────────────────────────────────────────
    //  HERO 3 — tab switching with rich animation
    // ─────────────────────────────────────────
    setupHero3Tabs() {
        const tabs   = document.querySelectorAll('.h3-tab');
        const panels = document.querySelectorAll('.h3-panel');

        if (!tabs.length) return;

        const tabColors = {
            k3:  { bg: '#4ade80', text: '#003348' },
            '5r':{ bg: '#5588A3', text: '#00334E' },
            '7s': { bg: '#f87171', text: '#450a0a' },
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const key = tab.dataset.tab;

                // Update tab styles
                tabs.forEach(t => {
                    const c = tabColors[t.dataset.tab] || tabColors.k3;
                    t.style.backgroundColor = 'rgba(20,83,116,0.3)';
                    t.style.color           = 'rgba(232,232,232,0.6)';
                    t.style.border          = '1px solid rgba(20,83,116,0.5)';
                    this.safeAnimate(t, { scale: 1, duration: 200 });
                });
                const col = tabColors[key] || tabColors.k3;
                tab.style.backgroundColor = col.bg;
                tab.style.color           = col.text;
                tab.style.border          = 'none';
                this.safeAnimate(tab, { scale: [0.9, 1.08, 1], duration: 400, easing: 'easeOutElastic(1, 0.6)' });

                // Panels — fade out current, fade in new
                const currentPanel = document.querySelector('.h3-panel:not(.hidden)');
                const nextPanel    = document.querySelector(`#h3-panel-${key}`);
                if (!nextPanel || currentPanel === nextPanel) return;

                if (currentPanel) {
                    animate(currentPanel, {
                        opacity: [1, 0],
                        translateX: ['0px', '-30px'],
                        duration: 220,
                        easing: 'easeInQuad',
                        complete: () => {
                            currentPanel.classList.add('hidden');
                            nextPanel.classList.remove('hidden');

                            // Reset children for stagger
                            const children = nextPanel.querySelectorAll('.h3-case-card, .space-y-6 > *');
                            children.forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateY(20px)'; });

                            animate(nextPanel, {
                                opacity: [0, 1],
                                translateX: ['30px', '0px'],
                                duration: 350,
                                easing: 'easeOutCubic'
                            });

                            animate(children, {
                                opacity: [0, 1],
                                translateY: ['20px', '0px'],
                                duration: 400,
                                delay: stagger(80, { start: 100 }),
                                easing: 'easeOutCubic'
                            });
                        }
                    });
                }
            });
        });
    }

    // ─────────────────────────────────────────
    //  HERO 3 — entrance animation (scroll trigger)
    // ─────────────────────────────────────────
    playHero3Entrance() {
        // Header
        const header = document.querySelector('.hero3-header');
        if (header) {
            const children = header.children;
            Array.from(children).forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateY(24px)'; });
            animate(Array.from(children), {
                opacity: [0, 1],
                translateY: ['24px', '0px'],
                duration: 600,
                delay: stagger(120),
                easing: 'easeOutCubic'
            });
        }

        // Tabs
        const tabs = document.querySelectorAll('.hero3-tabs .h3-tab');
        tabs.forEach(t => { t.style.opacity = '0'; t.style.transform = 'scale(0.85)'; });
        animate(tabs, {
            opacity: [0, 1],
            scale: [0.85, 1],
            duration: 450,
            delay: stagger(80, { start: 300 }),
            easing: 'easeOutBack'
        });

        // First panel case cards
        const firstCases = document.querySelectorAll('#h3-panel-k3 .h3-case-card');
        firstCases.forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateX(20px)'; });
        animate(firstCases, {
            opacity: [0, 1],
            translateX: ['20px', '0px'],
            duration: 480,
            delay: stagger(90, { start: 500 }),
            easing: 'easeOutCubic'
        });

        // First panel left content
        const firstLeft = document.querySelectorAll('#h3-panel-k3 .space-y-6 > *');
        firstLeft.forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateY(16px)'; });
        animate(firstLeft, {
            opacity: [0, 1],
            translateY: ['16px', '0px'],
            duration: 480,
            delay: stagger(100, { start: 400 }),
            easing: 'easeOutCubic'
        });
    }

    // ─────────────────────────────────────────
    //  HERO 4 — flow steps stagger entrance
    // ─────────────────────────────────────────
    playHero4Entrance() {
        // Header
        const header = document.querySelector('.hero4-header');
        if (header) {
            Array.from(header.children).forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateY(20px)'; });
            animate(Array.from(header.children), {
                opacity: [0, 1],
                translateY: ['20px', '0px'],
                duration: 600,
                delay: stagger(120),
                easing: 'easeOutCubic'
            });
        }

        // Flow steps — cascade with icons popping
        const steps = document.querySelectorAll('.flow-step');
        steps.forEach(step => { step.style.opacity = '0'; step.style.transform = 'translateY(40px)'; });

        animate(steps, {
            opacity: [0, 1],
            translateY: ['40px', '0px'],
            duration: 600,
            delay: stagger(150, { start: 300 }),
            easing: 'easeOutCubic',
        });

        // Icon rings — pulse in sequence after steps appear
        const rings = document.querySelectorAll('.flow-icon-ring');
        animate(rings, {
            opacity: [0, 1],
            scale: [0.6, 1],
            duration: 500,
            delay: stagger(150, { start: 700 }),
            easing: 'easeOutElastic(1, 0.5)'
        });

        // Progress line fill animation
        const line = document.querySelector('#flow-progress-line');
        if (line) {
            animate(line, {
                width: ['0%', '100%'],
                duration: 1200,
                delay: 600,
                easing: 'easeInOutQuart'
            });
        }

        // Icon wrap hover pulse — add after entrance
        setTimeout(() => {
            document.querySelectorAll('.flow-icon-wrap').forEach(wrap => {
                wrap.addEventListener('mouseenter', () => {
                    this.safeAnimate(wrap, { scale: 1.12, duration: 300, easing: 'easeOutBack' });
                });
                wrap.addEventListener('mouseleave', () => {
                    this.safeAnimate(wrap, { scale: 1, duration: 250, easing: 'easeOutQuad' });
                });
            });
        }, 1500);

        // Achievement cards stagger
        const achievements = document.querySelectorAll('.achievement-card');
        achievements.forEach(el => { el.style.opacity = '0'; el.style.transform = 'scale(0.85) translateY(16px)'; });
        animate(achievements, {
            opacity: [0, 1],
            scale: [0.85, 1],
            translateY: ['16px', '0px'],
            duration: 500,
            delay: stagger(100, { start: 900 }),
            easing: 'easeOutBack'
        });

        // Leaderboard rows
        this.animateLeaderboardRows(document.querySelector('#hero4'));
    }

    // ─────────────────────────────────────────
    //  LEADERBOARD rows stagger
    // ─────────────────────────────────────────
    animateLeaderboardRows(section) {
        if (!section) return;
        const rows = section.querySelectorAll('tbody tr');
        rows.forEach(row => { row.style.opacity = '0'; row.style.transform = 'translateX(-20px)'; });
        animate(rows, {
            opacity: [0, 1],
            translateX: ['-20px', '0px'],
            duration: 450,
            delay: stagger(90, { start: 1100 }),
            easing: 'easeOutCubic'
        });
    }

    // ─────────────────────────────────────────
    //  HERO 2 cards entrance (scroll trigger)
    // ─────────────────────────────────────────
    animateHero2Cards() {
        const cards = document.querySelectorAll('#hero-2-section .kategori-card');
        cards.forEach(card => { card.style.opacity = '0'; card.style.transform = 'translateY(24px) scale(0.95)'; });
        animate(cards, {
            opacity: [0, 1],
            translateY: ['24px', '0px'],
            scale: [0.95, 1],
            duration: 500,
            delay: stagger(130),
            easing: 'easeOutBack'
        });

        const panel = document.querySelector('#hero-2-detail-panel');
        if (panel) {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(20px)';
            animate(panel, {
                opacity: [0, 1],
                translateY: ['20px', '0px'],
                duration: 600,
                easing: 'easeOutCubic'
            });
        }
    }

    // ─────────────────────────────────────────
    //  SCROLL ANIMATIONS — Intersection Observer
    // ─────────────────────────────────────────
    setupScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                const id = entry.target.id || entry.target.closest('section')?.id;

                if (id === 'hero-2-section' || entry.target.closest('#hero-2-section')) {
                    this.animateHero2Cards();
                    observer.unobserve(entry.target);
                }

                if (id === 'hero3' || entry.target.closest('#hero3')) {
                    this.playHero3Entrance();
                    observer.unobserve(entry.target);
                }

                if (id === 'hero4' || entry.target.closest('#hero4')) {
                    this.playHero4Entrance();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        const sections = document.querySelectorAll('#hero-2-section, #hero3, #hero4');
        sections.forEach(s => observer.observe(s));
    }

    // ─────────────────────────────────────────
    //  RESIZE HANDLER
    // ─────────────────────────────────────────
    handleResize() {
        const refresh = () => {
            document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
        };
        refresh();
        window.addEventListener('resize', refresh);
    }
}

window.Polman375Landing = Polman375Landing;
document.addEventListener('DOMContentLoaded', () => { new Polman375Landing(); });