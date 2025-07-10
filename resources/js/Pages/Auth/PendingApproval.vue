<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Account Pending Approval - SIPUS" />

    <UserLayout>
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow px-6 py-8">
                <div class="text-center mb-6">
                    <div class="mx-auto h-16 w-16 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="mt-4 text-2xl font-bold text-gray-900">Account Pending Approval</h1>
                    <p class="mt-2 text-gray-600">Your account is currently waiting for administrator approval</p>
                </div>
                    <div class="space-y-4">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Approval Required
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>
                                            Your registration has been received successfully. An administrator will review your account and approve it shortly.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-900">What happens next?</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Administrator will review your registration details
                                </li>
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    You'll receive an email notification once approved
                                </li>
                                <li class="flex items-start">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    After approval, you can access all SIPUS features
                                </li>
                            </ul>
                        </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500 text-center">
                            Approval typically takes 1-2 business days. If you have urgent questions, please contact your administrator.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
