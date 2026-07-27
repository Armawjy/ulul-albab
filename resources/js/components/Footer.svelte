<script>
    import { MapPin, Phone, Mail } from "lucide-svelte";
    import { menuItems } from "../stores/menu";
    import logo from "../assets/logo/ub.jpeg";
</script>

<footer class="bg-primary-dark text-white pt-20 pb-10">
    <div class="container mx-auto px-6">
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16"
        >
            <!-- Brand -->
            <div data-aos="fade-up" data-aos-duration="600">
                <div class="flex items-center gap-3 mb-6">
                    <img
                        src={logo}
                        alt="Logo"
                        class="w-12 h-12 object-contain rounded-full bg-white p-1"
                    />
                    <div>
                        <div
                            class="font-heading font-bold leading-tight text-lg"
                        >
                            Pondok Pesantren
                        </div>
                        <div
                            class="font-heading font-bold text-xl text-primary-hover"
                        >
                            Ulul Albab Makassar
                        </div>
                    </div>
                </div>
                <p class="text-gray-300 text-sm mb-6 leading-relaxed">
                    Membentuk generasi Qurani, berprestasi, berkarakter, dan
                    siap menjadi pemimpin masa depan dengan berpegang pada
                    nilai-nilai Islam.
                </p>
                <div class="flex gap-4">
                    <a
                        href="https://www.facebook.com/pages/Pondok%20Pesantren%20Ulul%20Albab/837724936276664/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook Pesantren Ulul Albab"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877F2] transition-colors shadow-sm"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            ><path
                                d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"
                            ></path></svg
                        >
                    </a>
                    <a
                        href="https://www.instagram.com/maululalbab_/"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram Pesantren Ulul Albab"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#E4405F] transition-colors shadow-sm"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            ><rect
                                x="2"
                                y="2"
                                width="20"
                                height="20"
                                rx="5"
                                ry="5"
                            ></rect><path
                                d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"
                            ></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"
                            ></line></svg
                        >
                    </a>
                    <a
                        href="https://www.youtube.com/channel/UC46nQZaypi8dJOH8oNhhhaw"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube Pesantren Ulul Albab"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF0000] transition-colors shadow-sm"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            ><path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"
                            ></path><polygon
                                points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"
                            ></polygon></svg
                        >
                    </a>
                    <a
                        href="#"
                        class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary-hover transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            ><path
                                d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"
                            ></path></svg
                        >
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div
                data-aos="fade-up"
                data-aos-delay="150"
                data-aos-duration="600"
            >
                <h3
                    class="font-heading font-semibold text-lg mb-6 text-white border-b border-white/20 pb-2 inline-block"
                >
                    Quick Links
                </h3>
                <ul class="space-y-3">
                    {#each $menuItems.slice(0, 6) as item}
                        <li>
                            <a
                                href="/#{item.path}"
                                class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                                ></span>
                                {item.name}
                            </a>
                        </li>
                    {/each}
                </ul>
            </div>

            <!-- Program Unggulan -->
            <div
                data-aos="fade-up"
                data-aos-delay="300"
                data-aos-duration="600"
            >
                <h3
                    class="font-heading font-semibold text-lg mb-6 text-white border-b border-white/20 pb-2 inline-block"
                >
                    Program Unggulan
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a
                            href="/#/program"
                            class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                            ></span> Tahfidz Al-Qur'an
                        </a>
                    </li>
                    <li>
                        <a
                            href="/#/program"
                            class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                            ></span> Bahasa Arab
                        </a>
                    </li>
                    <li>
                        <a
                            href="/#/program"
                            class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                            ></span> Bahasa Inggris
                        </a>
                    </li>
                    <li>
                        <a
                            href="/#/program"
                            class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                            ></span> Leadership
                        </a>
                    </li>
                    <li>
                        <a
                            href="/#/program"
                            class="text-gray-300 hover:text-primary-hover transition-colors text-sm flex items-center gap-2"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-primary-hover"
                            ></span> Technology
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kontak Info -->
            <div
                data-aos="fade-up"
                data-aos-delay="450"
                data-aos-duration="600"
            >
                <h3
                    class="font-heading font-semibold text-lg mb-6 text-white border-b border-white/20 pb-2 inline-block"
                >
                    Kontak Kami
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-gray-300 text-sm">
                        <MapPin
                            size={18}
                            class="text-primary-hover shrink-0 mt-0.5"
                        />
                        <span
                            >Jl. Dg. Ramang, Sudiang Raya, Kec. Biringkanaya,
                            Kota Makassar, Sulawesi Selatan, Indonesia</span
                        >
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <Phone size={18} class="text-primary-hover shrink-0" />
                        <span>0858-2424-6172</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-300 text-sm">
                        <Mail size={18} class="text-primary-hover shrink-0" />
                        <span>ponpesululalbabmks@gmail.com</span>
                    </li>
                </ul>

                <a
                    href="https://wa.me/6285824246172"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-6 p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 flex items-center gap-4 transition-all duration-300 group block"
                >
                    <div
                        class="w-10 h-10 bg-[#25D366] rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            width="22"
                            height="22"
                            class="fill-white"
                        >
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                            />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-300">
                            Chat WhatsApp (085824246172)
                        </div>
                        <div class="text-sm font-semibold text-white">
                            Klik untuk menghubungi
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div
            class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4"
        >
            <p class="text-sm text-gray-400">
                &copy; {new Date().getFullYear()} Pondok Pesantren Ulul Albab Makassar.
                All rights reserved. <span class="text-emerald-400 font-semibold ml-1">| Dev: Arma Wijaya</span>
            </p>
            <div class="flex items-center gap-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition-colors"
                    >Privacy Policy</a
                >
                <a href="#" class="hover:text-white transition-colors"
                    >Terms of Service</a
                >
            </div>
        </div>
    </div>
</footer>
