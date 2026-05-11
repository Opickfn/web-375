@push('styles')
<style>
    /* Split Screen Layout */
    .auth-visual {
        flex: 1.2;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem;
        background: #001524;
    }
    @media (max-width: 1024px) {
        .auth-visual {
            display: none !important;
        }
    }
    .visual-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: scale(1.05);
        animation: slowZoom 20s infinite alternate;
    }
    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }
    .visual-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 51, 78, 0.95) 0%, rgba(0, 21, 36, 0.8) 100%);
    }
    .visual-content {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 600px;
    }
    .visual-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 5rem;
    }
    .visual-logo {
        width: 80px;
        filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.3));
    }
    .visual-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1.1;
    }
    .visual-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
    }

    /* Edu Slider */
    .edu-slider {
        position: relative;
        min-height: 200px;
    }
    .edu-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 1.5rem; 
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        height: 100%;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    .edu-slide.active {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }
    .edu-icon {
        width: 48px;
        height: 48px;
        background: rgba(245, 158, 11, 0.2);
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        margin-bottom: 1.5rem;
    }
    .edu-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        line-height: 1.4;
        margin-bottom: 1rem;
    }
    .edu-text {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
    }
    .slider-dots {
        display: flex;
        gap: 0.5rem;
        margin-top: 3rem;
    }
    .dot {
        width: 24px;
        height: 4px;
        background: rgba(255,255,255,0.2);
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .dot.active {
        background: #f59e0b;
        width: 40px;
    }

    /* Form Side */
    .auth-form-side {
        flex: 1;
        background: #001524;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .auth-card-wrapper {
        width: 100%;
        max-width: 450px;
    }
    .auth-form-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 3rem;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .form-header {
        margin-bottom: 2.5rem;
    }
    .form-title {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }
    .form-subtitle {
        color: rgba(255,255,255,0.5);
    }

    /* Modern Inputs */
    .form-field {
        margin-bottom: 1.5rem;
    }
    .field-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        margin-bottom: 0.5rem;
    }
    .input-container {
        position: relative;
    }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: rgba(255,255,255,0.3);
        transition: color 0.3s ease;
    }
    .modern-input {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 1rem;
        padding: 1rem 1rem 1rem 3.5rem;
        color: white;
        transition: all 0.3s ease;
    }
    .modern-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }
    .modern-input:focus + .input-icon {
        color: #f59e0b;
    }
    .field-error {
        color: #f87171;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }

    /* Auth Button */
    .btn-auth-primary {
        width: 100%;
        background: #f59e0b;
        color: #001524;
        font-weight: 700;
        padding: 1rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        margin-top: 2rem;
    }
    .btn-auth-primary:hover {
        background: #fbbf24;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
    }
    .btn-auth-primary:active {
        transform: translateY(0);
    }

    /* Footer */
    .form-footer {
        margin-top: 2.5rem;
        text-align: center;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.5);
    }
    .form-footer a {
        color: #f59e0b;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .form-footer a:hover {
        color: #fbbf24;
    }
    .back-home {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        color: rgba(255,255,255,0.3) !important;
        font-weight: 400 !important;
    }

    /* Mobile adjustments */
    .mobile-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    .mobile-logo img {
        width: 50px;
    }
    .mobile-logo span {
        font-size: 1.5rem;
        font-weight: 800;
        color: white;
    }

    @media (max-width: 1024px) {
        .auth-form-side {
            padding: 1.5rem;
        }
        .auth-form-card {
            padding: 2rem;
            border-radius: 1.5rem;
        }
    }

    .animate-in {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider Logic
        const slides = document.querySelectorAll('.edu-slide');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 5000);

        // Input effects
        const inputs = document.querySelectorAll('.modern-input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                input.parentElement.parentElement.classList.remove('focused');
            });
        });

        // Initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush