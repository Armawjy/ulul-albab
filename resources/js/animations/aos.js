import AOS from 'aos';

/**
 * Inisialisasi AOS (Animate On Scroll) dengan konfigurasi yang halus, ringan, dan siap production.
 * Durasi animasi dikonfigurasi 700ms dengan easing 'ease-in-out' sesuai persyaratan.
 */
export function initAOS() {
    AOS.init({
        duration: 700,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: 50,
        delay: 50,
        disable: 'phone', // Opsional, namun untuk performa FPS tinggi kita biarkan aktif pada desktop dan lancar pada HP modern
        throttleDelay: 99, // Debounce scroll event untuk menghemat CPU & menjaga FPS tinggi
    });
}

/**
 * Segarkan (refresh) kalkulasi posisi elemen AOS ketika rute atau DOM berubah (misalnya pada Hash Router Svelte).
 */
export function refreshAOS() {
    // Gunakan beberapa setTimeout agar DOM Svelte serta gambar/iframe selesai dirender
    const triggerRefresh = () => {
        AOS.refreshHard();
        window.dispatchEvent(new Event('scroll'));
    };

    window.requestAnimationFrame(() => {
        setTimeout(triggerRefresh, 50);
        setTimeout(triggerRefresh, 200);
        setTimeout(triggerRefresh, 600);
        setTimeout(triggerRefresh, 1200);
    });
}
