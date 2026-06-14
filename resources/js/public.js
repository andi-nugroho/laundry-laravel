import Alpine from 'alpinejs';
import Lenis from 'lenis';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    const revealSelector = '.reveal, .reveal-fade';
    const revealElements = Array.from(document.querySelectorAll(revealSelector));

    const revealAll = () => {
        revealElements.forEach((element) => element.classList.add('revealed'));
    };

    if (motionQuery.matches || revealElements.length === 0) {
        revealAll();
        return;
    }

    let lenis = null;

    try {
        lenis = new Lenis({
            duration: 1.2,
            easing: (time) => Math.min(1, 1.001 - Math.pow(2, -10 * time)),
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            smoothWheel: true,
            wheelMultiplier: 1,
        });
    } catch (error) {
        console.warn('Lenis smooth scroll failed to initialize:', error);
    }

    const viewportHeight = () => window.innerHeight || document.documentElement.clientHeight;

    const visibleRatio = (element) => {
        const rect = element.getBoundingClientRect();
        const viewportBottom = viewportHeight();
        const visibleHeight = Math.min(rect.bottom, viewportBottom) - Math.max(rect.top, 0);

        if (visibleHeight <= 0) {
            return 0;
        }

        return visibleHeight / Math.max(rect.height, 1);
    };

    const syncReveal = (element) => {
        const ratio = visibleRatio(element);
        const isRevealed = element.classList.contains('revealed');

        if (!isRevealed && ratio >= 0.12) {
            element.classList.add('revealed');
            return;
        }

        if (isRevealed && ratio <= 0) {
            element.classList.remove('revealed');
        }
    };

    const syncAllReveals = () => {
        revealElements.forEach(syncReveal);
    };

    const raf = (time) => {
        lenis?.raf(time);
        syncAllReveals();
        requestAnimationFrame(raf);
    };

    requestAnimationFrame(raf);

    syncAllReveals();
    window.addEventListener('load', syncAllReveals, { once: true });
    window.addEventListener('resize', syncAllReveals);

    motionQuery.addEventListener('change', (event) => {
        if (event.matches) {
            revealAll();
        } else {
            syncAllReveals();
        }
    });
});
