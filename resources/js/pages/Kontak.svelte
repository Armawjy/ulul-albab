<script>
    import { MapPin, Phone, Mail, Clock, Send } from "lucide-svelte";
    import Button from "../components/Button.svelte";
    import Card from "../components/Card.svelte";
</script>

<svelte:head>
    <title>Kontak Kami - Pondok Pesantren Ulul Albab Makassar</title>
</svelte:head>

<div class="pt-24 pb-16 bg-primary-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <!-- Abstract pattern bg instead of image for clean look -->
        <div
            class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] mix-blend-overlay"
        ></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl animate-fade-up text-center mx-auto">
            <h1
                class="text-4xl md:text-5xl font-heading font-bold text-white mb-6"
            >
                Hubungi Kami
            </h1>
            <p class="text-lg text-gray-200">
                Kami siap membantu dan menjawab pertanyaan Anda. Jangan ragu
                untuk menghubungi atau mengunjungi kami.
            </p>
        </div>
    </div>
</div>

<section class="py-20 bg-bg-base relative -mt-10 rounded-t-[40px] z-20">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div data-aos="fade-right" data-aos-duration="700">
                <h2
                    class="text-3xl font-heading font-bold text-text-title mb-8"
                >
                    Informasi Kontak
                </h2>

                <div class="space-y-6 mb-12">
                    <div
                        class="flex items-start gap-4 p-4 rounded-[20px] bg-bg-section border border-border-color/50 transition-all duration-300 hover:border-primary/40 hover:shadow-md hover:-translate-y-1"
                        data-aos="fade-up" data-aos-delay="100"
                    >
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
                        >
                            <MapPin size={24} />
                        </div>
                        <div>
                            <h3
                                class="font-heading font-bold text-text-title mb-1"
                            >
                                Alamat
                            </h3>
                            <p class="text-text-body text-sm leading-relaxed">
                                Jl. Dg. Ramang, Sudiang Raya, Kec. Biringkanaya,
                                Kota Makassar, Sulawesi Selatan
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-4 p-4 rounded-[20px] bg-bg-section border border-border-color/50 transition-all duration-300 hover:border-primary/40 hover:shadow-md hover:-translate-y-1"
                        data-aos="fade-up" data-aos-delay="200"
                    >
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
                        >
                            <Phone size={24} />
                        </div>
                        <div>
                            <h3
                                class="font-heading font-bold text-text-title mb-1"
                            >
                                Telepon & WhatsApp
                            </h3>
                            <p class="text-text-body text-sm mb-1">
                                (0411) 1234567 - Kantor
                            </p>
                            <p class="text-text-body text-sm">
                                0858-2424-6172 - Admin PPDB / WhatsApp
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-4 p-4 rounded-[20px] bg-bg-section border border-border-color/50 transition-all duration-300 hover:border-primary/40 hover:shadow-md hover:-translate-y-1"
                        data-aos="fade-up" data-aos-delay="300"
                    >
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
                        >
                            <Mail size={24} />
                        </div>
                        <div>
                            <h3
                                class="font-heading font-bold text-text-title mb-1"
                            >
                                Email
                            </h3>
                            <p class="text-text-body text-sm">
                               ponpesululalbabmks@gmail.com
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-4 p-4 rounded-[20px] bg-bg-section border border-border-color/50 transition-all duration-300 hover:border-primary/40 hover:shadow-md hover:-translate-y-1"
                        data-aos="fade-up" data-aos-delay="400"
                    >
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
                        >
                            <Clock size={24} />
                        </div>
                        <div>
                            <h3
                                class="font-heading font-bold text-text-title mb-1"
                            >
                                Jam Operasional
                            </h3>
                            <p class="text-text-body text-sm mb-1">
                                Senin - Jumat: 08:00 - 15:00 WITA
                            </p>
                            <p class="text-text-body text-sm">
                                Sabtu: 08:00 - 12:00 WITA
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div data-aos="fade-left" data-aos-duration="700">
                <Card class="border border-border-color/50 p-8 shadow-soft">
                    <h2
                        class="text-2xl font-heading font-bold text-text-title mb-6"
                    >
                        Kirim Pesan
                    </h2>

                    <form
                        class="space-y-5"
                        onsubmit={(e) => e.preventDefault()}
                    >
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-text-title mb-1.5"
                                >Nama Lengkap</label
                            >
                            <input
                                type="text"
                                id="name"
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary focus:shadow-[0_0_15px_rgba(22,101,52,0.15)] transition-all duration-300 text-sm"
                                placeholder="Masukkan nama Anda"
                            />
                        </div>

                        <div>
                            <label
                                for="email"
                                class="block text-sm font-medium text-text-title mb-1.5"
                                >Email</label
                            >
                            <input
                                type="email"
                                id="email"
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary focus:shadow-[0_0_15px_rgba(22,101,52,0.15)] transition-all duration-300 text-sm"
                                placeholder="Alamat email Anda"
                            />
                        </div>

                        <div>
                            <label
                                for="subject"
                                class="block text-sm font-medium text-text-title mb-1.5"
                                >Subjek</label
                            >
                            <input
                                type="text"
                                id="subject"
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary focus:shadow-[0_0_15px_rgba(22,101,52,0.15)] transition-all duration-300 text-sm"
                                placeholder="Subjek pesan"
                            />
                        </div>

                        <div>
                            <label
                                for="message"
                                class="block text-sm font-medium text-text-title mb-1.5"
                                >Pesan</label
                            >
                            <textarea
                                id="message"
                                rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-border-color focus:outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary focus:shadow-[0_0_15px_rgba(22,101,52,0.15)] transition-all duration-300 text-sm resize-none"
                                placeholder="Tulis pesan Anda di sini..."
                            ></textarea>
                        </div>

                        <Button
                            type="submit"
                            class="w-full justify-center gap-2"
                        >
                            <Send size={18} />
                            Kirim Pesan
                        </Button>
                    </form>
                </Card>
            </div>
        </div>

        <!-- Google Maps Interactive -->
        <div
            class="mt-20 w-full h-[450px] bg-gray-200 rounded-[30px] overflow-hidden relative shadow-card border border-border-color/50"
            data-aos="zoom-in"
            data-aos-duration="800"
        >
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4015.4274588743374!2d119.51975067498182!3d-5.098641394878287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefb9d60702f9d%3A0x660d019dcb402681!2sPesantren%20Ulul%20Albab!5e1!3m2!1sid!2sid!4v1785071299434!5m2!1sid!2sid"
                title="Peta Lokasi Pesantren Ulul Albab Makassar"
                class="w-full h-full border-0 absolute inset-0 z-0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
            ></iframe>

            <!-- Floating Info Card (Diposisikan di pojok kiri bawah agar peta tetap bebas digeser dan di-zoom) -->
            <div
                class="absolute bottom-6 left-6 z-10 max-w-xs bg-white/95 backdrop-blur-md p-5 rounded-[20px] shadow-xl border border-white/60 hidden sm:block"
            >
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <MapPin size={20} />
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-text-title text-base leading-tight">
                            Pesantren Ulul Albab
                        </h3>
                        <p class="text-xs text-primary font-semibold">Makassar, Sulawesi Selatan</p>
                    </div>
                </div>
                <p class="text-xs text-text-body mb-3 leading-relaxed">
                    Jl. Dg. Ramang, Sudiang Raya, Kec. Biringkanaya, Kota Makassar
                </p>
                <a
                    href="https://www.google.com/maps/search/?api=1&query=Pesantren+Ulul+Albab+Makassar"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block w-full text-center py-2 bg-primary text-white text-xs font-semibold rounded-xl hover:bg-primary-hover transition-colors shadow-sm"
                >
                    Buka Rute di Google Maps
                </a>
            </div>
        </div>
    </div>
</section>
