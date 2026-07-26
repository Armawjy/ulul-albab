/**
 * Svelte 5 action menggunakan Intersection Observer untuk memicu animasi saat section masuk ke dalam viewport.
 * Menjamin elemen tidak akan dianimasikan sebelum benar-benar terlihat di layar pengguna.
 * 
 * @param {HTMLElement} node
 * @param {Object} options
 * @param {string} [options.animationClass='animate-fade-up'] - Kelas CSS animasi yang akan ditambahkan
 * @param {number} [options.threshold=0.15] - Persentase elemen terlihat sebelum animasi dimulai
 * @param {boolean} [options.once=true] - Animasi hanya terjadi sekali
 * @param {Function} [options.onEnter] - Callback kustom saat elemen terlihat
 */
export function inview(node, options = {}) {
    const {
        animationClass = 'animate-fade-up',
        threshold = 0.15,
        once = true,
        onEnter = null,
    } = options;

    // Sembunyikan elemen secara default sampai masuk viewport jika animationClass diset
    if (animationClass) {
        node.style.opacity = '0';
        node.style.transition = 'opacity 700ms ease-in-out, transform 700ms ease-in-out';
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    if (animationClass) {
                        node.style.opacity = '1';
                        node.classList.add(...animationClass.split(' '));
                    }
                    if (typeof onEnter === 'function') {
                        onEnter(entry);
                    }
                    if (once) {
                        obs.unobserve(node);
                    }
                } else if (!once && animationClass) {
                    node.style.opacity = '0';
                    node.classList.remove(...animationClass.split(' '));
                }
            });
        },
        { threshold }
    );

    observer.observe(node);

    return {
        destroy() {
            observer.disconnect();
        },
    };
}
