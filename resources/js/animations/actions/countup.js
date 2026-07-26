import { CountUp } from 'countup.js';

/**
 * Svelte 5 action untuk memicu CountUp.js ketika elemen masuk ke dalam viewport.
 * Memanfaatkan Intersection Observer agar animasi tidak berjalan sebelum terlihat.
 * 
 * @param {HTMLElement} node
 * @param {Object} params
 * @param {number} params.endVal - Nilai target akhir (misalnya 1250)
 * @param {number} [params.startVal=0] - Nilai awal
 * @param {number} [params.duration=2.5] - Durasi animasi dalam detik
 * @param {string} [params.suffix=''] - Suffix tambahan seperti '+'
 * @param {string} [params.prefix=''] - Prefix tambahan
 * @param {boolean} [params.enableScrollSpy=true] - Otomatis via observer kita
 */
export function countup(node, params = {}) {
    let observer;
    let countUpInstance;

    const {
        endVal = 0,
        startVal = 0,
        duration = 2.5,
        suffix = '',
        prefix = '',
        separator = '.',
        ...rest
    } = params;

    const initObserver = () => {
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        // Inisialisasi CountUp saat elemen mulai terlihat di viewport
                        countUpInstance = new CountUp(node, endVal, {
                            startVal,
                            duration,
                            suffix,
                            prefix,
                            separator,
                            useEasing: true,
                            ...rest,
                        });

                        if (!countUpInstance.error) {
                            countUpInstance.start();
                        } else {
                            console.error(countUpInstance.error);
                        }

                        // Disconnect observer setelah animasi dimulai (sekali jalan)
                        observer.unobserve(node);
                    }
                });
            },
            {
                threshold: 0.3, // Mulai animasi saat 30% elemen terlihat
            }
        );

        observer.observe(node);
    };

    initObserver();

    return {
        update(newParams) {
            if (countUpInstance && newParams.endVal !== undefined && newParams.endVal !== endVal) {
                countUpInstance.update(newParams.endVal);
            }
        },
        destroy() {
            if (observer) {
                observer.disconnect();
            }
        },
    };
}
