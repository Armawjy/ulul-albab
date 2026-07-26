/**
 * Svelte 5 action untuk memberikan efek Ripple (gelombang air) modern saat elemen/tombol diklik.
 * 
 * @param {HTMLElement} node - Tombol atau elemen interaktif
 * @param {Object} options
 * @param {string} [options.color='rgba(255, 255, 255, 0.35)'] - Warna gelombang ripple
 */
export function ripple(node, options = {}) {
    let { color = 'rgba(255, 255, 255, 0.35)' } = options;

    // Pastikan kontainer memiliki overflow hidden dan position relative/absolute/fixed
    const computedStyle = window.getComputedStyle(node);
    if (computedStyle.position === 'static') {
        node.style.position = 'relative';
    }
    node.style.overflow = 'hidden';

    const handleClick = (e) => {
        const rect = node.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height) * 2;
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        const circle = document.createElement('span');
        circle.style.position = 'absolute';
        circle.style.top = `${y}px`;
        circle.style.left = `${x}px`;
        circle.style.width = `${size}px`;
        circle.style.height = `${size}px`;
        circle.style.backgroundColor = color;
        circle.style.borderRadius = '50%';
        circle.style.transform = 'scale(0)';
        circle.style.pointerEvents = 'none';
        circle.style.transition = 'transform 600ms ease-out, opacity 600ms ease-out';
        circle.style.opacity = '1';
        circle.style.zIndex = '10';

        node.appendChild(circle);

        // Paksa browser reflow sebelum memicu animasi
        window.requestAnimationFrame(() => {
            circle.style.transform = 'scale(1)';
            circle.style.opacity = '0';
        });

        circle.addEventListener('transitionend', () => {
            if (circle && circle.parentNode === node) {
                node.removeChild(circle);
            }
        });
    };

    node.addEventListener('click', handleClick);

    return {
        update(newOptions) {
            color = newOptions.color !== undefined ? newOptions.color : color;
        },
        destroy() {
            node.removeEventListener('click', handleClick);
        },
    };
}
