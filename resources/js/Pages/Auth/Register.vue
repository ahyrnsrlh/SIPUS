<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    instansi: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Daftar - SIPUS" />

    <div
        class="min-h-screen bg-gradient-to-br from-pln-blue to-dark-blue flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div
                    class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-6"
                >
                    <img
                        src="/pln.png"
                        alt="Logo PLN"
                        class="h-12 w-12 object-contain"
                    />
                </div>
                <h2 class="text-3xl font-extrabold text-white mb-2">
                    Bergabung Dengan SIPUS
                </h2>
                <p class="text-pln-blue text-lg">
                    Sistem Informasi Penyimpanan File
                </p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 space-y-6">
                <div>
                    <h3
                        class="text-2xl font-bold text-charcoal text-center mb-6"
                    >
                        Buat Akun Baru
                    </h3>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Nama Lengkap"
                            class="text-charcoal font-semibold"
                        />
                        <div class="mt-2 relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="name"
                                type="text"
                                class="pl-10 block w-full rounded-xl border-gray-300 focus:border-pln-blue focus:ring-pln-blue shadow-sm"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap Anda"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-charcoal font-semibold"
                        />
                        <div class="mt-2 relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="pl-10 block w-full rounded-xl border-gray-300 focus:border-pln-blue focus:ring-pln-blue shadow-sm"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Instansi Field -->
                    <div>
                        <InputLabel
                            for="instansi"
                            value="Instansi/Divisi"
                            class="text-charcoal font-semibold"
                        />
                        <div class="mt-2 relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="instansi"
                                type="text"
                                class="pl-10 block w-full rounded-xl border-gray-300 focus:border-pln-blue focus:ring-pln-blue shadow-sm"
                                v-model="form.instansi"
                                required
                                autocomplete="organization"
                                placeholder="Masukkan nama instansi/divisi"
                            />
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.instansi"
                        />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <InputLabel
                            for="password"
                            value="Password"
                            class="text-charcoal font-semibold"
                        />
                        <div class="mt-2 relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
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
                            <TextInput
                                id="password"
                                type="password"
                                class="pl-10 block w-full rounded-xl border-gray-300 focus:border-pln-blue focus:ring-pln-blue shadow-sm"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan password"
                            />
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Konfirmasi Password"
                            class="text-charcoal font-semibold"
                        />
                        <div class="mt-2 relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="pl-10 block w-full rounded-xl border-gray-300 focus:border-pln-blue focus:ring-pln-blue shadow-sm"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Konfirmasi password Anda"
                            />
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <!-- Warning Message -->
                    <div
                        class="bg-amber-50 border border-amber-200 rounded-xl p-4"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-amber-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">
                                    Perhatian
                                </h3>
                                <p class="text-sm text-amber-700 mt-1">
                                    Akun Anda perlu persetujuan dari admin
                                    sebelum dapat digunakan untuk login.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <div>
                        <PrimaryButton
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-all duration-200"
                            :class="{
                                'opacity-50 cursor-not-allowed':
                                    form.processing,
                            }"
                            :disabled="form.processing"
                        >
                            <svg
                                v-if="form.processing"
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                />
                            </svg>
                            {{
                                form.processing
                                    ? "Memproses..."
                                    : "Daftar Sekarang"
                            }}
                        </PrimaryButton>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <Link
                                :href="route('login')"
                                class="font-medium text-pln-blue hover:text-dark-blue transition-colors duration-200"
                            >
                                Masuk sekarang
                            </Link>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-pln-blue">
                    © 2025 SIPUS. Sistem Informasi Penyimpanan File.
                </p>
            </div>
        </div>
    </div>
</template>
