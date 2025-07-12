<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

// FAQ state
const openFaq = ref(null);

const toggleFaq = (index) => {
    openFaq.value = openFaq.value === index ? null : index;
};

// Mobile menu state
const mobileMenuOpen = ref(false);

const scrollToSection = (sectionId) => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: "smooth" });
    // Close mobile menu if open
    if (mobileMenuOpen.value) {
        mobileMenuOpen.value = false;
    }
};
</script>

<template>
    <Head title="SIPUS - Sistem Informasi Pengelolaan Berkas" />

    <div
        class="min-h-screen bg-gradient-to-br from-light-blue via-white to-pln-gray"
    >
        <!-- Navigation -->
        <nav
            class="relative z-10 bg-white/90 backdrop-blur-sm border-b border-pln-gray"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-pln-blue to-dark-blue rounded-xl flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-pln-blue">
                                SIPUS
                            </h1>
                            <p class="text-xs text-pln-blue">
                                PT PLN (Persero)
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="hidden md:flex items-center space-x-8">
                        <button
                            @click="scrollToSection('features')"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 font-medium"
                        >
                            Fitur
                        </button>
                        <button
                            @click="scrollToSection('benefits')"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 font-medium"
                        >
                            Manfaat
                        </button>
                        <button
                            @click="scrollToSection('faq')"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 font-medium"
                        >
                            FAQ
                        </button>
                        <button
                            @click="scrollToSection('demo')"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 font-medium"
                        >
                            Demo
                        </button>
                    </div>

                    <!-- Auth Links -->
                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('login')"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 font-medium"
                        >
                            Masuk
                        </Link>
                        <Link
                            v-if="canRegister && !$page.props.auth.user"
                            :href="route('register')"
                            class="bg-pln-blue text-white px-6 py-2 rounded-lg hover:bg-dark-blue transition-colors duration-200 font-medium"
                        >
                            Daftar
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-pln-blue text-white px-6 py-2 rounded-lg hover:bg-dark-blue transition-colors duration-200 font-medium"
                        >
                            Dashboard
                        </Link>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-charcoal hover:text-pln-blue transition-colors duration-200 p-2"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div
                    v-show="mobileMenuOpen"
                    class="md:hidden border-t border-pln-gray bg-white"
                >
                    <div class="px-4 py-2 space-y-1">
                        <button
                            @click="scrollToSection('features')"
                            class="block w-full text-left px-3 py-2 text-charcoal hover:text-pln-blue hover:bg-gray-50 transition-colors duration-200 font-medium"
                        >
                            Fitur
                        </button>
                        <button
                            @click="scrollToSection('benefits')"
                            class="block w-full text-left px-3 py-2 text-charcoal hover:text-pln-blue hover:bg-gray-50 transition-colors duration-200 font-medium"
                        >
                            Manfaat
                        </button>
                        <button
                            @click="scrollToSection('faq')"
                            class="block w-full text-left px-3 py-2 text-charcoal hover:text-pln-blue hover:bg-gray-50 transition-colors duration-200 font-medium"
                        >
                            FAQ
                        </button>
                        <button
                            @click="scrollToSection('demo')"
                            class="block w-full text-left px-3 py-2 text-charcoal hover:text-pln-blue hover:bg-gray-50 transition-colors duration-200 font-medium"
                        >
                            Demo
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section
            class="relative px-4 sm:px-6 lg:px-8 pt-20 pb-16 overflow-hidden"
        >
            <!-- Background decoration -->
            <div class="absolute inset-0 -z-10">
                <div
                    class="absolute top-0 left-0 w-72 h-72 bg-pln-blue/10 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"
                ></div>
                <div
                    class="absolute top-0 right-0 w-72 h-72 bg-pln-yellow/10 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-1000"
                ></div>
                <div
                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-dark-blue/10 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-2000"
                ></div>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <!-- Announcement Badge -->
                    <div
                        class="inline-flex items-center px-6 py-3 mb-8 bg-gradient-to-r from-pln-blue/10 to-dark-blue/10 border border-pln-blue/20 rounded-full"
                    >
                        <span
                            class="w-2 h-2 bg-pln-blue rounded-full mr-3 animate-pulse"
                        ></span>
                        <span class="text-sm font-medium text-pln-blue">
                            Sistem Terbaru untuk Pengelolaan Dokumen Digital
                            PLN
                        </span>
                    </div>

                    <!-- Main Heading -->
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-bold text-pln-blue mb-6"
                    >
                        Sistem Informasi
                        <span
                            class="text-4xl sm:text-5xl lg:text-6xl font-bold text-pln-blue mb-6"
                        >
                            Pengelolaan Berkas
                        </span>
                    </h1>

                    <!-- Subtitle -->
                    <p
                        class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed"
                    >
                        Kelola dan atur dokumen perusahaan dengan sistem modern
                        yang aman, terstruktur, dan mudah digunakan untuk PT PLN
                        (Persero)
                    </p>

                    <!-- Feature highlights -->
                    <div class="flex flex-wrap justify-center gap-4 mb-12">
                        <div
                            class="flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-lg border border-pln-gray/20"
                        >
                            <svg
                                class="w-5 h-5 text-pln-blue mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                ></path>
                            </svg>
                            <span class="text-sm font-medium text-charcoal"
                                >Keamanan Berlapis</span
                            >
                        </div>
                        <div
                            class="flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-lg border border-pln-gray/20"
                        >
                            <svg
                                class="w-5 h-5 text-pln-blue mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                            <span class="text-sm font-medium text-charcoal"
                                >Pencarian Cerdas</span
                            >
                        </div>
                        <div
                            class="flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-lg border border-pln-gray/20"
                        >
                            <svg
                                class="w-5 h-5 text-pln-blue mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span class="text-sm font-medium text-charcoal"
                                >Akses Mobile</span
                            >
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16"
                    >
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="w-full sm:w-auto bg-gradient-to-r from-pln-blue to-dark-blue text-pln-blue px-8 py-4 rounded-xl hover:from-dark-blue hover:to-pln-blue transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                        >
                            🚀 Mulai Sekarang
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="w-full sm:w-auto bg-gradient-to-r from-pln-blue to-dark-blue text-white px-8 py-4 rounded-xl hover:from-dark-blue hover:to-pln-blue transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                        >
                            🚀 Buka Dashboard
                        </Link>
                        <button
                            @click="scrollToSection('features')"
                            class="w-full sm:w-auto border-2 border-pln-blue text-pln-blue px-8 py-4 rounded-xl hover:bg-pln-blue hover:text-white transition-all duration-300 font-semibold text-lg"
                        >
                            📖 Pelajari Lebih Lanjut
                        </button>
                    </div>

                    <!-- Hero illustration -->
                    <div class="relative max-w-4xl mx-auto">
                        <div
                            class="bg-white/30 backdrop-blur-sm rounded-2xl p-8 border border-white/20 shadow-2xl"
                        >
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <!-- File icons -->
                                <div
                                    class="bg-white/80 rounded-lg p-4 text-center shadow-sm"
                                >
                                    <svg
                                        class="w-8 h-8 text-pln-blue mx-auto mb-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                    <span class="text-xs text-gray-600"
                                        >Dokumen</span
                                    >
                                </div>
                                <div
                                    class="bg-white/80 rounded-lg p-4 text-center shadow-sm"
                                >
                                    <svg
                                        class="w-8 h-8 text-pln-yellow mx-auto mb-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                        ></path>
                                    </svg>
                                    <span class="text-xs text-gray-600"
                                        >Rak Digital</span
                                    >
                                </div>
                                <div
                                    class="bg-white/80 rounded-lg p-4 text-center shadow-sm"
                                >
                                    <svg
                                        class="w-8 h-8 text-green mx-auto mb-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        ></path>
                                    </svg>
                                    <span class="text-xs text-gray-600"
                                        >Keamanan</span
                                    >
                                </div>
                            </div>
                            <div class="text-center">
                                <div
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pln-blue to-dark-blue rounded-lg text-white font-semibold"
                                >
                                    <svg
                                        class="w-5 h-5 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        ></path>
                                    </svg>
                                    Dashboard SIPUS
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="px-4 sm:px-6 lg:px-8 py-20 bg-white/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl sm:text-4xl font-bold text-pln-blue mb-4"
                    >
                        Fitur Unggulan
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Sistem yang dirancang khusus untuk memenuhi kebutuhan
                        pengelolaan dokumen perusahaan modern
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <!-- Feature 1 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-pln-blue to-dark-blue rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Manajemen Rak Digital
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Atur dokumen dalam sistem rak virtual yang
                            terstruktur dengan kategori dan sub-kategori yang
                            dapat disesuaikan
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-pln-yellow to-orange rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Pencarian Cerdas
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Temukan dokumen dengan cepat menggunakan fitur
                            pencarian canggih dengan filter berdasarkan
                            kategori, tanggal, dan metadata
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green to-dark-green rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Keamanan Berlapis
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Sistem keamanan dengan kontrol akses berbasis role,
                            approval workflow, dan enkripsi data untuk
                            melindungi informasi sensitif
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-pln-red to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Kolaborasi Tim
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Bekerja sama dalam tim dengan sistem berbagi
                            dokumen, komentar, dan notifikasi real-time untuk
                            produktivitas maksimal
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Analitik & Laporan
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dashboard analitik komprehensif dengan visualisasi
                            data untuk monitoring penggunaan dan performa sistem
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-pln-gray hover:border-pln-blue group"
                    >
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-pink-500 to-pink-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-charcoal mb-4">
                            Akses Mobile
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Interface responsive yang dapat diakses dari
                            perangkat mobile kapan saja dan dimana saja dengan
                            performa optimal
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="px-4 sm:px-6 lg:px-8 py-20 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl sm:text-4xl font-bold text-pln-blue mb-4"
                    >
                        Mengapa Memilih SIPUS?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Solusi terdepan untuk transformasi digital pengelolaan
                        dokumen perusahaan
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <!-- Card 1: Efisiensi Operasional -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg border border-pln-gray hover:shadow-xl transition-shadow duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-pln-blue to-dark-blue rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold text-charcoal mb-4 text-center"
                        >
                            Efisiensi Operasional
                        </h3>
                        <p class="text-gray-600 text-center">
                            Mengurangi waktu pencarian dokumen hingga 75% dengan
                            sistem pengindeksan yang cerdas
                        </p>
                    </div>

                    <!-- Card 2: Kepatuhan Regulasi -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg border border-pln-gray hover:shadow-xl transition-shadow duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-pln-blue to-dark-blue rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold text-charcoal mb-4 text-center"
                        >
                            Kepatuhan Regulasi
                        </h3>
                        <p class="text-gray-600 text-center">
                            Memenuhi standar ISO dan regulasi kearsipan nasional
                            untuk perusahaan BUMN
                        </p>
                    </div>

                    <!-- Card 3: Penghematan Biaya -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg border border-pln-gray hover:shadow-xl transition-shadow duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-pln-blue to-dark-blue rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold text-charcoal mb-4 text-center"
                        >
                            Penghematan Biaya
                        </h3>
                        <p class="text-gray-600 text-center">
                            Menghemat biaya penyimpanan fisik dan operasional
                            hingga 60% per tahun
                        </p>
                    </div>

                    <!-- Card 4: Skalabilitas Tinggi -->
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg border border-pln-gray hover:shadow-xl transition-shadow duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-pln-blue to-dark-blue rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                ></path>
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold text-charcoal mb-4 text-center"
                        >
                            Skalabilitas Tinggi
                        </h3>
                        <p class="text-gray-600 text-center">
                            Dapat menangani jutaan dokumen dengan performa
                            optimal untuk seluruh kantor PLN
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section
            class="px-4 sm:px-6 lg:px-8 py-20 bg-gradient-to-r from-pln-blue to-dark-blue"
        >
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl sm:text-4xl font-bold text-pln-blue mb-4"
                    >
                        Dipercaya oleh PLN
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Sistem yang telah dioptimalkan untuk kebutuhan
                        enterprise PT PLN (Persero)
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div
                            class="text-4xl sm:text-5xl font-bold text-pln-blue mb-2"
                        >
                            99.9%
                        </div>
                        <div class="text-pln-blue font-medium">
                            Uptime System
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="text-4xl sm:text-5xl font-bold text-pln-blue mb-2"
                        >
                            1M+
                        </div>
                        <div class="text-pln-blue font-medium">
                            Dokumen Tersimpan
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="text-4xl sm:text-5xl font-bold text-pln-blue mb-2"
                        >
                            24/7
                        </div>
                        <div class="text-pln-blue font-medium">
                            Dukungan Teknis
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="text-4xl sm:text-5xl font-bold text-pln-blue mb-2"
                        >
                            500+
                        </div>
                        <div class="text-pln-blue font-medium">
                            Pengguna Aktif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="px-4 sm:px-6 lg:px-8 py-20 bg-white/50">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl sm:text-4xl font-bold text-pln-blue mb-4"
                    >
                        Pertanyaan yang Sering Diajukan
                    </h2>
                    <p class="text-xl text-gray-600">
                        Temukan jawaban untuk pertanyaan umum tentang SIPUS
                    </p>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div
                        class="bg-white rounded-xl shadow-lg border border-pln-gray overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(1)"
                            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-inset"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-charcoal">
                                    Apakah SIPUS aman untuk dokumen sensitif
                                    perusahaan?
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': openFaq === 1 }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openFaq === 1" class="px-6 pb-4">
                            <p class="text-gray-600">
                                Ya, SIPUS menggunakan enkripsi tingkat
                                enterprise dan sistem keamanan berlapis. Semua
                                dokumen dienkripsi end-to-end dengan standar
                                AES-256, dilengkapi kontrol akses berbasis role
                                dan audit trail lengkap.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div
                        class="bg-white rounded-xl shadow-lg border border-pln-gray overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(2)"
                            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-inset"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-charcoal">
                                    Berapa lama proses implementasi SIPUS?
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': openFaq === 2 }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openFaq === 2" class="px-6 pb-4">
                            <p class="text-gray-600">
                                Implementasi SIPUS dapat diselesaikan dalam 2-4
                                minggu tergantung kompleksitas organisasi.
                                Termasuk migrasi data, konfigurasi sistem, dan
                                pelatihan pengguna secara komprehensif.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div
                        class="bg-white rounded-xl shadow-lg border border-pln-gray overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(3)"
                            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-inset"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-charcoal">
                                    Apakah SIPUS dapat diintegrasikan dengan
                                    sistem existing PLN?
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': openFaq === 3 }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openFaq === 3" class="px-6 pb-4">
                            <p class="text-gray-600">
                                Tentu saja. SIPUS dilengkapi dengan API yang
                                fleksibel dan dapat diintegrasikan dengan
                                berbagai sistem enterprise seperti SAP, Oracle,
                                dan aplikasi internal PLN lainnya melalui web
                                services.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div
                        class="bg-white rounded-xl shadow-lg border border-pln-gray overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(4)"
                            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-inset"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-charcoal">
                                    Bagaimana dukungan teknis yang tersedia?
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': openFaq === 4 }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openFaq === 4" class="px-6 pb-4">
                            <p class="text-gray-600">
                                Kami menyediakan dukungan teknis 24/7 melalui
                                helpdesk dedicated, dokumentasi lengkap, video
                                tutorial, dan tim support yang berpengalaman
                                dengan sistem enterprise PLN.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div
                        class="bg-white rounded-xl shadow-lg border border-pln-gray overflow-hidden"
                    >
                        <button
                            @click="toggleFaq(5)"
                            class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-inset"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-charcoal">
                                    Apakah ada batasan ukuran atau jumlah file
                                    yang dapat disimpan?
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': openFaq === 5 }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </div>
                        </button>
                        <div v-show="openFaq === 5" class="px-6 pb-4">
                            <p class="text-gray-600">
                                SIPUS mendukung file hingga 500MB per dokumen
                                dengan total storage yang dapat disesuaikan
                                dengan kebutuhan. Sistem dapat menangani jutaan
                                dokumen dengan performa optimal melalui
                                teknologi cloud storage yang scalable.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-pln-blue text-white px-4 sm:px-6 lg:px-8 py-12">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-pln-blue to-dark-blue rounded-xl flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">SIPUS</h3>
                                <p class="text-sm text-white">
                                    PT PLN (Persero)
                                </p>
                            </div>
                        </div>
                        <p class="text-white leading-relaxed mb-6">
                            Sistem Informasi Pengelolaan Berkas yang dirancang
                            khusus untuk memenuhi kebutuhan pengelolaan dokumen
                            PT PLN (Persero) dengan standar keamanan dan
                            efisiensi tinggi.
                        </p>
                        <div class="text-sm text-white">
                            © 2025 PT PLN (Persero). All rights reserved.
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                        <ul class="space-y-2">
                            <li>
                                <button
                                    @click="scrollToSection('features')"
                                    class="text-white hover:text-white transition-colors duration-200 text-left"
                                >
                                    Fitur
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="scrollToSection('benefits')"
                                    class="text-white hover:text-white transition-colors duration-200 text-left"
                                >
                                    Manfaat
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="scrollToSection('faq')"
                                    class="text-white hover:text-white transition-colors duration-200 text-left"
                                >
                                    FAQ
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Dukungan</h4>
                        <ul class="space-y-2">
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:text-white transition-colors duration-200"
                                >
                                    Dokumentasi
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:text-white transition-colors duration-200"
                                >
                                    Panduan Pengguna
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:text-white transition-colors duration-200"
                                >
                                    Video Tutorial
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="text-white hover:text-white transition-colors duration-200"
                                >
                                    Helpdesk
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-center text-white">
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                sipus@pln.co.id
                            </li>
                            <li class="flex items-center text-white">
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    ></path>
                                </svg>
                                +62 21 7231234
                            </li>
                            <li class="flex items-start text-white">
                                <svg
                                    class="w-4 h-4 mr-2 mt-0.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    ></path>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    ></path>
                                </svg>
                                <span
                                    >Jl. ZA. Pagar Alam <br />Kedaton, Bandar
                                    Lampung</span
                                >
                            </li>
                            <li class="flex items-center text-white">
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                                Layanan 24/7
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
