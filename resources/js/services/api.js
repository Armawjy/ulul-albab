import axios from './axios';

// Mock API service for future integration
export const api = {
    // Berita
    getBerita: () => axios.get('/berita'),
    getBeritaDetail: (slug) => axios.get(`/berita/${slug}`),
    
    // Prestasi
    getPrestasi: () => axios.get('/prestasi'),
    
    // Galeri
    getGaleri: () => axios.get('/galeri'),
    
    // Guru
    getGuru: () => axios.get('/guru'),
    
    // Program
    getProgram: () => axios.get('/program'),
    
    // Statistik
    getStatistik: () => axios.get('/statistik'),
};
