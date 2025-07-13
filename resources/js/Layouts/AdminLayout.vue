<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage();
const sidebarOpen = ref(false);

// Provided slot for header
defineSlots();

const user = computed(() => page.props.auth.user);

const navigation = [
    {
        name: "Dashboard",
        href: route("dashboard"),
        icon: "M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z",
        current: route().current("dashboard"),
    },
    {
        name: "Files",
        href: route("files.index"),
        icon: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        current: route().current("files.*"),
    },
    {
        name: "Users",
        href: route("admin.users.index"),
        icon: "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z",
        current: route().current("admin.users.*"),
    },
    {
        name: "Racks",
        href: route("admin.racks.index"),
        icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        current: route().current("admin.racks.*"),
    },
];

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="SIPUS - Admin Panel" />

        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-pln-gray shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
            :class="{
                '-translate-x-full': !sidebarOpen,
                'translate-x-0': sidebarOpen,
            }"
        >
            <!-- Logo -->
            <div
                class="flex items-center justify-center h-16 px-4 border-b border-pln-gray"
            >
                <div class="flex items-center">
                    <div
                        class="w-17 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm overflow-hidden"
                    >
                        <img
                            src="/pln.png"
                            alt="Logo PLN"
                            class="w-6 h-6 object-contain"
                        />
                    </div>
                    <div class="ml-3">
                        <span class="text-xl font-bold text-pln-blue"
                            >SIPUS</span
                        >
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4 space-y-2">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'group flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors duration-200',
                        item.current
                            ? 'bg-light-blue text-pln-blue border border-pln-blue'
                            : 'text-charcoal hover:bg-pln-gray hover:text-dark-blue',
                    ]"
                >
                    <svg
                        :class="[
                            'mr-3 h-5 w-5 flex-shrink-0',
                            item.current
                                ? 'text-pln-blue'
                                : 'text-gray-400 group-hover:text-dark-blue',
                        ]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            :d="item.icon"
                        />
                    </svg>
                    {{ item.name }}
                </Link>
            </nav>

            <!-- User Info & Logout -->
            <div
                class="absolute bottom-0 left-0 right-0 p-4 border-t border-pln-gray"
            >
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-dark-blue rounded-full flex items-center justify-center"
                    >
                        <span class="text-white text-sm font-medium">
                            {{ user?.name?.charAt(0)?.toUpperCase() || "A" }}
                        </span>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-sm font-medium text-charcoal truncate">
                            {{ user?.name || "Admin" }}
                        </div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ user?.email || "admin@sipus.com" }}
                        </div>
                    </div>
                    <button
                        @click="logout"
                        class="ml-3 p-2 text-gray-400 hover:text-pln-red rounded-lg hover:bg-pln-gray transition-colors duration-200"
                        title="Logout"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Top bar -->
            <div
                class="sticky top-0 z-40 bg-white border-b border-pln-gray shadow-sm"
            >
                <div
                    class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
                >
                    <!-- Mobile menu button -->
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 text-charcoal hover:text-pln-blue hover:bg-pln-gray rounded-lg"
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
                            />
                        </svg>
                    </button>

                    <!-- Page title -->
                    <div class="flex-1 min-w-0 ml-4 lg:ml-0">
                        <h1
                            v-if="$slots.header"
                            class="text-2xl font-bold text-charcoal"
                        >
                            <slot name="header" />
                        </h1>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Search bar -->
                        <div class="hidden md:block relative">
                            <input
                                type="text"
                                placeholder="Quick search..."
                                class="w-64 pl-10 pr-4 py-2 border border-pln-gray rounded-lg text-sm focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                            />
                            <svg
                                class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
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

                        <!-- User avatar -->
                        <div class="hidden md:flex items-center space-x-3">
                            <div class="text-right">
                                <div class="text-sm font-medium text-charcoal">
                                    {{ user?.name || "Admin" }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Administrator
                                </div>
                            </div>
                            <div
                                class="w-8 h-8 bg-pln-blue rounded-full flex items-center justify-center"
                            >
                                <span class="text-white text-sm font-medium">
                                    {{
                                        user?.name?.charAt(0)?.toUpperCase() ||
                                        "A"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <div
                v-if="page.props.flash?.success || page.props.flash?.error"
                class="px-4 sm:px-6 lg:px-8 py-4"
            >
                <div
                    v-if="page.props.flash.success"
                    class="bg-light-blue border border-pln-blue text-dark-blue px-4 py-3 rounded-lg mb-4 flex items-center"
                    role="alert"
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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <span>{{ page.props.flash.success }}</span>
                </div>

                <div
                    v-if="page.props.flash.error"
                    class="bg-red-100 border border-pln-red text-pln-red px-4 py-3 rounded-lg mb-4 flex items-center"
                    role="alert"
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
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <span>{{ page.props.flash.error }}</span>
                </div>
            </div>

            <!-- Page content -->
            <main class="px-4 sm:px-6 lg:px-8 py-6">
                <slot />
            </main>
        </div>
    </div>
</template>
