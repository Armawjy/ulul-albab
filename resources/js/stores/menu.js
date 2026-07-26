import { writable } from 'svelte/store';

export const menuItems = writable([
    { name: 'Beranda', path: '/' },
    { name: 'Profil', path: '/profil' },
    { name: 'Sejarah', path: '/sejarah' },
    { name: 'Visi & Misi', path: '/visi-misi' },
    { name: 'Program SMA/MA', path: '/program' },
    { name: 'Guru', path: '/guru' },
    { name: 'Prestasi', path: '/prestasi' },
    { name: 'Galeri', path: '/galeri' },
    { name: 'Berita', path: '/berita' },
    { name: 'PPDB', path: '/ppdb' },
    { name: 'Kontak', path: '/kontak' },
]);
