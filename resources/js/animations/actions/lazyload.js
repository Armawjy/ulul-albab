/**
 * Svelte 5 action untuk Lazy Loading gambar dengan animasi Fade In & Zoom In perlahan.
 * Memastikan gambar hanya dimuat saat masuk viewport dan transisi halus (300ms - 800ms ease-in-out).
 * 
 * @param {HTMLElement} node - Elemen <img> atau container
 */
export function lazyload(node) {
    // Pastikan atribut native loading="lazy" aktif
    if (node.tagName.toLowerCase() === 'img') {
        node.setAttribute('loading', 'lazy');
    }

    // Set styling awal sebelum gambar muncul
    node.style.opacity = '0';
    node.style.transform = 'scale(0.95)';
    node.style.transition = 'opacity 700ms ease-in-out, transform 700ms ease-in-out';
    node.style.willChange = 'opacity, transform';

    const showImage = () => {
        node.style.opacity = '1';
        node.style.transform = 'scale(1)';
    };

    const handleLoad = () => {
        // Jika gambar sudah berada di dalam viewport, jalankan animasi kemunculan
        showImage();
    };

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    if (node.tagName.toLowerCase() === 'img') {
                        if (node.complete && node.naturalHeight !== 0) {
                            showImage();
                        } else {
                            node.addEventListener('load', handleLoad, { once: true });
                            // Jika ada error atau gambar rusak, tetap dimunculkan agar tidak hilang total
                            node.addEventListener('error', showImage, { once: true });
                        }
                    } else {
                        showImage();
                    }
                    obs.unobserve(node);
                }
            });
        },
        {
            threshold: 0.1,
            rootMargin: '50px',
        }
    );

    observer.observe(node);

    return {
        destroy() {
            observer.disconnect();
            node.removeEventListener('load', handleLoad);
        },
    };
}
