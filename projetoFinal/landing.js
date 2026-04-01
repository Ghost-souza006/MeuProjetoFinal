// ============================================
// ANIMAÇÕES DE ENTRADA PARA LANDING PAGE
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Animação de entrada das feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    const statItems = document.querySelectorAll('.stat-item');

    // Observer para animações de entrada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);

    // Observar feature cards
    featureCards.forEach(card => {
        observer.observe(card);
    });

    // Observar stat items
    statItems.forEach(item => {
        observer.observe(item);
    });

    // Animação suave para links de navegação
    const navLinks = document.querySelectorAll('.footer-links a, .hero-actions a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href !== '#') {
                e.preventDefault();
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    window.location.href = href;
                }, 150);
            }
        });
    });

    // Efeito de hover melhorado para botões
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});