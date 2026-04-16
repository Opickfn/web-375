import anime from 'https://unpkg.com/animejs@4.0.0/lib/anime.es.js';

const utils = {
    random(min, max) {
        return Math.random() * (max - min) + min;
    },
    clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    },
    toArray(list) {
        return Array.from(list);
    },
};

class Polman375Landing {
    constructor() {
        this.hero1Index = 0;
        this.hero2Index = '5R';
        this.heroExpanded = false;
        this.init();
    }

    init() {
        this.root = document.querySelector('#landing-root');
        if (!this.root) return;

        this.bindHero1();
        this.bindHero2();
        this.bindHero3();
        this.bindHero4();
        this.bindScrollEffects();
    }

    bindHero1() {
        this.hero1Slides = utils.toArray(document.querySelectorAll('.hero1-slide'));
        this.hero1Buttons = utils.toArray(document.querySelectorAll('.hero1-action'));
        this.hero1Buttons.forEach((button, idx) => {
            button.addEventListener('mouseenter', () => this.animateButton(button, 1.2));
            button.addEventListener('mouseleave', () => this.animateButton(button, 1));
            button.addEventListener('mousedown', () => this.animateButton(button, 0.9, 80));
            button.addEventListener('mouseup', () => this.animateButton(button, 1, 120));
            button.addEventListener('click', () => this.switchHero1(idx));
        });
    }

    bindHero2() {
        this.hero2Boxes = utils.toArray(document.querySelectorAll('.hero2-box'));
        this.hero2NavItems = utils.toArray(document.querySelectorAll('.hero2-nav-item'));
        this.hero2Panels = utils.toArray(document.querySelectorAll('.hero2-panel'));
        this.hero2Container = document.querySelector('#hero2');

        if (!this.hero2Container) return;

        this.hero2Boxes.forEach((box) => {
            const x = utils.random(-80, 80);
            const y = utils.random(-40, 40);
            const rotation = utils.random(-18, 18);
            box.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
            box.addEventListener('click', () => this.activateHero2(box.dataset.target));
        });

        this.hero2NavItems.forEach((nav) => {
            nav.addEventListener('click', () => this.activateHero2(nav.dataset.target));
        });

        this.activateHero2(this.hero2Index, true);
    }

    bindHero3() {
        this.hero3Items = utils.toArray(document.querySelectorAll('.hero3-card'));
        this.modalOverlay = document.querySelector('#hero3-modal');
        this.modalTitle = document.querySelector('#hero3-modal-title');
        this.modalBody = document.querySelector('#hero3-modal-body');
        this.modalClose = document.querySelector('#hero3-modal-close');
        this.hero3Line = document.querySelector('#hero3-line');

        if (!this.hero3Items.length || !this.modalOverlay) return;

        const details = {
            'workflow-1': {
                title: 'Identifikasi Cepat',
                body: 'Temukan potensi bahaya, dokumentasikan, dan kirim laporan singkat dalam beberapa detik.',
            },
            'workflow-2': {
                title: 'Verifikasi & Follow-up',
                body: 'Tim memeriksa laporan Anda, menugaskan tindak lanjut, dan mengkomunikasikan hasilnya.',
            },
            'workflow-3': {
                title: 'Komunikasi Transparan',
                body: 'Setiap langkah dapat dilacak dengan notifikasi real-time dan tampilan status yang jelas.',
            },
        };

        this.hero3Items.forEach((card) => {
            card.addEventListener('click', () => {
                this.modalLayout = this.modalLayout || new ModalLayout(this);
                this.modalLayout.update(details[card.dataset.target]);
            });
        });

        this.modalClose.addEventListener('click', () => this.modalLayout?.close());
        this.animateHero3Line();
    }

    bindHero4() {
        this.hero4Section = document.querySelector('#hero4');
        this.hero4Rows = utils.toArray(document.querySelectorAll('#hero4 tbody tr'));

        if (!this.hero4Section || !this.hero4Rows.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    anime({
                        targets: this.hero4Rows,
                        translateY: [24, 0],
                        opacity: [0, 1],
                        delay: anime.stagger(80),
                        duration: 700,
                        easing: 'easeOutCubic',
                    });
                    observer.disconnect();
                }
            });
        }, { threshold: 0.25 });

        observer.observe(this.hero4Section);
    }

    bindScrollEffects() {
        const svgLine = document.querySelector('#hero3-line path');
        if (!svgLine) return;
        const length = svgLine.getTotalLength();
        svgLine.style.strokeDasharray = length;
        svgLine.style.strokeDashoffset = length;

        window.addEventListener('scroll', () => {
            const rect = svgLine.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const progress = utils.clamp(1 - rect.top / viewportHeight, 0, 1);
            svgLine.style.strokeDashoffset = length * (1 - progress);
        });
    }

    animateButton(button, scale, duration = 250) {
        anime.remove(button);
        anime({
            targets: button,
            scale,
            duration,
            easing: 'easeOutBack',
        });
    }

    switchHero1(index) {
        if (index === this.hero1Index) return;
        const previous = this.hero1Slides[this.hero1Index];
        const next = this.hero1Slides[index];
        this.hero1Buttons[this.hero1Index].classList.remove('active');
        this.hero1Buttons[index].classList.add('active');
        this.hero1Index = index;

        anime.timeline({ easing: 'easeOutCubic', duration: 500 })
            .add({ targets: previous, opacity: 0, translateX: '-20%' })
            .add({ targets: previous, visibility: 'hidden', duration: 0 }, '-=120')
            .add({ targets: next, visibility: 'visible', opacity: 1, translateX: '0%' }, '-=200');
    }

    activateHero2(target, initial = false) {
        this.hero2Index = target;
        const activeNav = document.querySelector(`.hero2-nav-item[data-target="${target}"]`);
        this.hero2NavItems.forEach((nav) => nav.classList.toggle('active', nav === activeNav));
        this.hero2Panels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.target !== target));

        anime({
            targets: this.hero2NavItems,
            scale: (nav) => (nav.dataset.target === target ? 1.1 : 0.94),
            opacity: (nav) => (nav.dataset.target === target ? 1 : 0.6),
            boxShadow: (nav) => nav.dataset.target === target ? '0 24px 60px rgba(110, 191, 255, 0.24)' : '0 12px 22px rgba(15, 23, 42, 0.06)',
            duration: 550,
            easing: 'easeOutExpo',
        });

        anime({
            targets: this.hero2Panels,
            opacity: (panel) => (panel.dataset.target === target ? 1 : 0),
            translateX: (panel) => (panel.dataset.target === target ? '0%' : '12%'),
            duration: 650,
            easing: 'easeOutQuad',
        });

        if (!initial) {
            this.createLayout();
        }
    }

    createLayout() {
        if (!this.hero2Container) return;
        this.hero2Container.classList.add('hero2-expanded');
        anime({
            targets: '.hero2-floating',
            opacity: [1, 0],
            duration: 500,
            easing: 'easeOutExpo',
        });
        anime({
            targets: '.hero2-content',
            opacity: [0, 1],
            translateY: ['24px', '0px'],
            duration: 700,
            easing: 'easeOutCubic',
        });
    }
}

class ModalLayout {
    constructor(parent) {
        this.parent = parent;
        this.modal = parent.modalOverlay;
        this.title = parent.modalTitle;
        this.body = parent.modalBody;
        this.closeButton = parent.modalClose;
    }

    update(detail) {
        this.title.textContent = detail.title;
        this.body.textContent = detail.body;
        this.open();
    }

    open() {
        this.modal.classList.remove('hidden');
        anime({
            targets: this.modal,
            opacity: [0, 1],
            scale: [0.95, 1],
            duration: 450,
            easing: 'easeOutBack',
        });
    }

    close() {
        anime({
            targets: this.modal,
            opacity: [1, 0],
            scale: [1, 0.95],
            duration: 300,
            easing: 'easeInBack',
            complete: () => {
                this.modal.classList.add('hidden');
            },
        });
    }
}

window.Polman375Landing = Polman375Landing;

document.addEventListener('DOMContentLoaded', () => {
    new Polman375Landing();
});
