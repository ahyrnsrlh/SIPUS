<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Login - SIPUS" />

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

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 space-y-6">
                <div>
                    <h3
                        class="text-2xl font-bold text-charcoal text-center mb-6"
                    >
                        Masuk ke Akun Anda
                    </h3>
                </div>

                <div
                    v-if="status"
                    class="bg-green-50 border border-green-200 rounded-lg p-4"
                >
                    <div class="flex">
                        <svg
                            class="w-5 h-5 text-green-400 mr-3"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <p class="text-sm text-green-700">{{ status }}</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
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
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
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
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                            />
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                                class="rounded text-pln-blue focus:ring-pln-blue"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Ingat saya</span
                            >
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-pln-blue hover:text-dark-blue font-medium"
                        >
                            Lupa Password?
                        </Link>
                    </div>

                    <!-- Login Button -->
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
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                />
                            </svg>
                            {{ form.processing ? "Memproses..." : "Masuk" }}
                        </PrimaryButton>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <Link
                                :href="route('register')"
                                class="font-medium text-pln-blue hover:text-dark-blue transition-colors duration-200"
                            >
                                Daftar sekarang
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
