import * as anime from 'animejs';

const { animate, remove, stagger, utils } = anime;

class Polman375Landing {
    constructor() {
        this.hero1Index = 0;
        this.hero1Interval = null;
        this.hero2Index = null;
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
        this.sliderCard = document.querySelector('.slider-card');
        this.sliderLabel = document.querySelector('.slider-label');
        this.sliderTitle = document.querySelector('.slider-title');
        this.sliderDescription = document.querySelector('.slider-description');
        this.sliderIndex = document.querySelector('.slider-index');
        this.sliderTotal = document.querySelector('.slider-total');
        this.sliderContainer = document.querySelector('.slider-container');
        this.heroCopy = document.querySelector('.hero-copy');
        this.hero1Buttons = Array.from(document.querySelectorAll('.btn-animate'));
        this.heroNavButtons = Array.from(document.querySelectorAll('.slider-nav'));

        if (!this.sliderCard || !this.sliderLabel || !this.sliderTitle || !this.sliderDescription || !this.sliderIndex || !this.heroCopy) return;

        this.hero1Slides = [
            {
                image: 'https://images.unsplash.com/photo-1519337265831-281ec6cc8514?auto=format&fit=crop&w=1200&q=80',
                label: 'Bangunan Kampus Modern',
                title: 'Peningkatan Infrastruktur Kampus',
                description: 'Bangunan kampus modern dengan pemantauan dan pelaporan K3 yang terintegrasi untuk menjaga keselamatan semua pengguna.',
                heroCopy: 'Slide pertama menyorot kekuatan sistem pelaporan untuk mendukung lingkungan kampus yang aman dan terkontrol.',
            },
            {
                image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
                label: 'Peringatan Kebersihan',
                title: 'Temuan Kebersihan & Keamanan',
                description: 'Laporan cepat ketika area kerja tidak memenuhi standar kebersihan dan SOP K3.',
                heroCopy: 'Dukung kepatuhan lapangan dengan laporan kebersihan yang mendetail dan tindak lanjut yang transparan.',
            },
            {
                image: 'https://images.unsplash.com/photo-1520027296538-7ecaef410a6a?auto=format&fit=crop&w=1200&q=80',
                label: 'Peralatan Rusak',
                title: 'Perbaikan Peralatan Prioritas',
                description: 'Identifikasi kerusakan peralatan lebih cepat dan kirim laporan langsung kepada tim pemeliharaan.',
                heroCopy: 'Fokus pada penanganan risiko dengan pelaporan alat rusak yang mempercepat respons teknis.',
            },
        ];

        if (this.sliderTotal) {
            this.sliderTotal.textContent = this.hero1Slides.length;
        }

        this.hero1Buttons.forEach((button) => {
            remove(button);
            button.style.transformOrigin = 'center center';
            button.addEventListener('mouseenter', () => this.animateButton(button, 1.2));
            button.addEventListener('mouseleave', () => this.animateButton(button, 1));
            button.addEventListener('mousedown', () => this.animateButton(button, 0.92, 140));
            button.addEventListener('mouseup', () => this.animateButton(button, 1, 220));
        });

        this.heroNavButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const action = button.dataset.action;
                const nextIndex = action === 'next' ? this.hero1Index + 1 : this.hero1Index - 1;
                this.updateHero1Slide(nextIndex);
            });
        });

        if (this.sliderContainer) {
            this.sliderContainer.addEventListener('mouseenter', () => this.stopHero1Loop());
            this.sliderContainer.addEventListener('mouseleave', () => this.startHero1Loop());
        }

        this.updateHero1Slide(0);
        this.startHero1Loop();
    }

    updateHero1Slide(index) {
        if (!this.sliderCard) return;

        const nextIndex = (index + this.hero1Slides.length) % this.hero1Slides.length;
        const slide = this.hero1Slides[nextIndex];
        this.hero1Index = nextIndex;

        if (this.sliderIndex) {
            this.sliderIndex.textContent = this.hero1Index + 1;
        }

        animate({
            targets: [this.sliderLabel, this.sliderTitle, this.sliderDescription, this.heroCopy],
            opacity: [1, 0],
            translateY: [0, 18],
            duration: 220,
            delay: stagger(35),
            easing: 'easeInQuad',
        });

        animate({
            targets: this.sliderCard,
            opacity: [1, 0.72],
            duration: 250,
            easing: 'easeInQuad',
        });

        window.setTimeout(() => {
            this.sliderCard.style.backgroundImage = `url('${slide.image}')`;
            this.sliderLabel.textContent = slide.label;
            this.sliderTitle.textContent = slide.title;
            this.sliderDescription.textContent = slide.description;
            this.heroCopy.textContent = slide.heroCopy;

            animate({
                targets: this.sliderCard,
                opacity: [0.72, 1],
                duration: 420,
                easing: 'easeOutExpo',
            });

            animate({
                targets: [this.sliderLabel, this.sliderTitle, this.sliderDescription, this.heroCopy],
                opacity: [0, 1],
                translateY: [18, 0],
                duration: 420,
                delay: stagger(50),
                easing: 'easeOutExpo',
            });
        }, 260);
    }

    startHero1Loop() {
        if (this.hero1Interval) {
            clearInterval(this.hero1Interval);
        }
        this.hero1Interval = setInterval(() => this.updateHero1Slide(this.hero1Index + 1), 6500);
    }

    stopHero1Loop() {
        if (this.hero1Interval) {
            clearInterval(this.hero1Interval);
            this.hero1Interval = null;
        }
    }

    bindHero2() {
        this.hero2Section = document.querySelector('#hero2');
        this.hero2Root = document.querySelector('#layout-root');
        this.hero2Boxes = Array.from(document.querySelectorAll('.hero2-box'));
        this.hero2Content = document.querySelector('#content-area');

        if (!this.hero2Section || !this.hero2Boxes.length || !this.hero2Root || !this.hero2Content) return;

        this.hero2Details = {
            '5R': {
                themeBg: '#022c48',
                cardBg: 'rgba(56, 189, 248, 0.18)',
                tag: '5R',
                title: 'Budaya Area Rapi',
                body: 'Fokus pada area yang rapih, bersih, dan teratur. Setiap temuan dicatat secara cepat agar area kerja selalu siap operasi.',
                items: [
                    'Inspeksi visual area kerja untuk menjaga kebersihan.',
                    'Sistem laporan cepat untuk temuan rapi.',
                    'Tindak lanjut terjadwal untuk area prioritas.',
                ],
                sampleReports: [
                    'Laporan kebersihan ruang kelas berkala.',
                    'Audit tata letak gudang sesuai standar 5R.',
                ],
            },
            '7S': {
                themeBg: '#4c0519',
                cardBg: 'rgba(244, 63, 94, 0.18)',
                tag: '7S',
                title: 'Budaya Teratur',
                body: 'Menciptakan disiplin dan tata kelola kerja yang konsisten melalui monitoring dan laporan terstruktur.',
                items: [
                    'Standarisasi kebersihan dan kerapian area.',
                    'Pencatatan temuan secara rutin.',
                    'Evaluasi budaya kerja berdasarkan 7S.',
                ],
                sampleReports: [
                    'Penataan meja kerja untuk efisiensi operasional.',
                    'Laporan audit kebersihan area produksi.',
                ],
            },
            'K3': {
                themeBg: '#064e3b',
                cardBg: 'rgba(16, 185, 129, 0.18)',
                tag: 'K3',
                title: 'Keamanan Kerja',
                body: 'Menangani risiko kerja dengan laporan K3 terstruktur, memastikan perlindungan tim lapangan dan pencegahan insiden.',
                items: [
                    'Identifikasi bahaya potensial secara proaktif.',
                    'Koordinasi tindakan keselamatan lapangan.',
                    'Pelaporan cepat untuk pencegahan insiden.',
                ],
                sampleReports: [
                    'Laporan kecelakaan kecil untuk perbaikan SOP.',
                    'Checklist keselamatan harian area kerja.',
                ],
            },
        };

        this.hero2Root.style.transition = 'grid-template-columns 0.4s ease';
        this.hero2Content.style.display = 'none';
        this.hero2Section.style.backgroundColor = '#0b1120';

        this.hero2Boxes.forEach((box) => {
            const x = utils.random(-20, 20);
            const y = utils.random(-12, 12);
            const rotation = utils.random(-4, 4);
            box.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
            box.addEventListener('click', () => this.selectHero2(box.dataset.layoutId));
        });

        animate({
            targets: this.hero2Boxes,
            translateY: [0, -10],
            rotate: [0, 3],
            duration: () => utils.random(1700, 2300),
            direction: 'alternate',
            loop: true,
            easing: 'easeInOutSine',
            delay: stagger(120),
        });
    }

    selectHero2(layoutId) {
        const selected = this.hero2Details[layoutId];
        if (!selected || this.hero2Index === layoutId) return;
        this.hero2Index = layoutId;

        this.hero2Boxes.forEach((box) => {
            const isSelected = box.dataset.layoutId === layoutId;
            const detail = this.hero2Details[box.dataset.layoutId];
            animate({
                targets: box,
                scale: isSelected ? 1.1 : 0.95,
                opacity: isSelected ? 1 : 0.5,
                backgroundColor: detail.cardBg,
                boxShadow: isSelected ? '0 30px 90px rgba(255,255,255,0.22)' : '0 0 0 0 rgba(0,0,0,0)',
                duration: 450,
                easing: 'easeOutExpo',
            });
        });

        this.hero2Root.style.gridTemplateColumns = '280px 1fr';
        this.hero2Section.style.backgroundColor = selected.themeBg;

        this.hero2Content.style.display = 'block';
        this.hero2Content.style.opacity = '0';
        this.hero2Content.style.transform = 'translateY(16px)';
        this.updateHero2Content(selected);

        animate({
            targets: this.hero2Content,
            opacity: [0, 1],
            translateY: ['16px', '0px'],
            duration: 650,
            easing: 'easeOutQuart',
        });
    }

    updateHero2Content(selected) {
        const contentTag = document.querySelector('#content-tag');
        const contentTitle = document.querySelector('#content-title');
        const contentBody = document.querySelector('#content-body');
        const contentList = document.querySelector('#content-list');
        const sampleList = document.querySelector('#sample-report-list');

        contentTag.textContent = selected.tag;
        contentTitle.textContent = selected.title;
        contentBody.textContent = selected.body;
        contentList.innerHTML = selected.items
            .map((item) => `<div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-slate-200">${item}</div>`)
            .join('');
        sampleList.innerHTML = selected.sampleReports
            .map((report) => `<div class="rounded-3xl border border-slate-700/50 bg-slate-950/80 p-4 text-slate-200">${report}</div>`)
            .join('');
    }

    bindHero3() {
        this.hero3Items = Array.from(document.querySelectorAll('.hero3-card'));
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
        this.hero4Rows = Array.from(document.querySelectorAll('#hero4 tbody tr'));

        if (!this.hero4Section || !this.hero4Rows.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animate({
                        targets: this.hero4Rows,
                        translateY: [24, 0],
                        opacity: [0, 1],
                        delay: stagger(80),
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
        remove(button);
        animate({
            targets: button,
            scale,
            duration,
            easing: 'easeOutBack',
        });
    }

    animateHero3Line() {
        if (!this.hero3Line) return;
        animate({
            targets: this.hero3Line,
            opacity: [0, 1],
            duration: 650,
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
        animate({
            targets: this.modal,
            opacity: [0, 1],
            scale: [0.95, 1],
            duration: 450,
            easing: 'easeOutBack',
        });
    }

    close() {
        animate({
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
