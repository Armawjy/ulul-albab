import gsap from 'gsap';

/**
 * Svelte 5 action menggunakan GSAP untuk efek Parallax yang halus, ringan, dan tinggi FPS.
 * Memanfaatkan requestAnimationFrame dan passive event listener agar tidak terjadi lag saat scroll.
 * 
 * @param {HTMLElement} node
 * @param {Object} options
 * @param {number} [options.speed=0.35] - Kecepatan pergerakan parallax relative terhadap scroll
 * @param {boolean} [options.enabled=true] - Aktif atau tidaknya efek
 */
export function parallax(node, options = {}) {
    let { speed = 0.35, enabled = true } = options;
    let ticking = false;
    let handleScroll;

    // Cek preferensi user untuk reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (enabled && !prefersReducedMotion) {
        handleScroll = () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const rect = node.parentElement ? node.parentElement.getBoundingClientRect() : node.getBoundingClientRect();
                    
                    // Hanya kalkulasi jika elemen induk atau elemen ada di dalam atau dekat viewport
                    if (rect.top <= window.innerHeight && rect.bottom >= 0) {
                        // Hitung pergeseran Y berdasarkan posisi scroll
                        const yPos = (window.innerHeight - rect.top) * speed - (window.innerHeight * speed * 0.5);
                        
                        gsap.to(node, {
                            y: yPos,
                            duration: 0.6,
                            ease: 'power2.out',
                            overwrite: 'auto',
                        });
                    }
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        // Jalankan sekali saat inisialisasi
        handleScroll();
    }

    return {
        update(newOptions) {
            speed = newOptions.speed !== undefined ? newOptions.speed : speed;
        },
        destroy() {
            if (handleScroll) {
                window.removeEventListener('scroll', handleScroll);
            }
            gsap.killTweensOf(node);
        },
    };
}
