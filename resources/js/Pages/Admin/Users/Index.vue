<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    users: Array,
    stats: Object,
});

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const deleteUser = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (userToDelete.value) {
        router.delete(route("admin.users.destroy", userToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                userToDelete.value = null;
            },
            onError: () => {
                // Keep modal open on error
            },
        });
    }
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const approveUser = (user) => {
    router.post(route("admin.users.approve", user.id));
};

const rejectUser = (user) => {
    router.post(route("admin.users.reject", user.id));
};

const getStatusBadge = (status) => {
    const badges = {
        approved: "bg-green-100 text-green-800 border-green-200",
        pending: "bg-pln-yellow text-charcoal border-pln-yellow",
        rejected: "bg-pln-red text-white border-pln-red",
    };
    return badges[status] || "bg-gray-100 text-gray-800 border-gray-200";
};

const getRoleBadge = (role) => {
    const badges = {
        admin: "bg-pln-blue text-white border-pln-blue",
        user: "bg-light-blue text-pln-blue border-light-blue",
    };
    return badges[role] || "bg-gray-100 text-gray-800 border-gray-200";
};
</script>

<template>
    <Head title="Users Management - SIPUS" />

    <AdminLayout>
        <template #header> Users Management </template>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div
                class="bg-white rounded-xl shadow-sm border border-pln-gray p-6"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-pln-blue rounded-lg flex items-center justify-center"
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
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Total Users
                        </p>
                        <p class="text-2xl font-bold text-charcoal">
                            {{ stats.total }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-pln-gray p-6"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center"
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Approved
                        </p>
                        <p class="text-2xl font-bold text-charcoal">
                            {{ stats.approved }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-pln-gray p-6"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-pln-yellow rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-charcoal"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Pending Approval
                        </p>
                        <p class="text-2xl font-bold text-charcoal">
                            {{ stats.pending }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm border border-pln-gray p-6"
            >
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-pln-red rounded-lg flex items-center justify-center"
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Rejected
                        </p>
                        <p class="text-2xl font-bold text-charcoal">
                            {{ stats.rejected }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray p-6 mb-6"
        >
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-charcoal">
                        👥 Manajemen Pengguna
                    </h2>
                    <p class="text-sm text-gray-600">
                        Kelola persetujuan dan status pengguna sistem
                    </p>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray overflow-hidden"
        >
            <div v-if="users && users.length > 0">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-pln-gray bg-pln-gray">
                    <div
                        class="grid grid-cols-12 gap-4 text-xs font-medium text-charcoal uppercase tracking-wider"
                    >
                        <div class="col-span-3">User Info</div>
                        <div class="col-span-2">Email</div>
                        <div class="col-span-2">Instansi</div>
                        <div class="col-span-1">Role</div>
                        <div class="col-span-2">Status</div>
                        <div class="col-span-2">Actions</div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="divide-y divide-pln-gray">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="px-6 py-4 hover:bg-pln-gray transition-colors duration-200"
                    >
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- User Info -->
                            <div class="col-span-3 flex items-center">
                                <div
                                    class="w-10 h-10 bg-light-blue rounded-full flex items-center justify-center mr-3"
                                >
                                    <span
                                        class="text-pln-blue font-medium text-sm"
                                    >
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-medium text-charcoal"
                                    >
                                        {{ user.name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ID: {{ user.id }}
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-2">
                                <div class="text-sm text-charcoal">
                                    {{ user.email }}
                                </div>
                            </div>

                            <!-- Instansi -->
                            <div class="col-span-2">
                                <div class="text-sm text-charcoal">
                                    {{ user.instansi || "-" }}
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="col-span-1">
                                <span
                                    :class="getRoleBadge(user.role)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize"
                                >
                                    {{ user.role }}
                                </span>
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <span
                                    :class="getStatusBadge(user.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize"
                                >
                                    {{ user.status }}
                                </span>
                                <div
                                    v-if="user.approved_at"
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    {{
                                        new Date(
                                            user.approved_at
                                        ).toLocaleDateString()
                                    }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-2">
                                <div class="flex items-center gap-2">
                                    <!-- Approve Button -->
                                    <button
                                        v-if="user.status === 'pending'"
                                        @click="approveUser(user)"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 border border-green-300 rounded hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                                        title="Approve User"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                        Approve
                                    </button>

                                    <!-- Reject Button -->
                                    <button
                                        v-if="user.status === 'pending'"
                                        @click="rejectUser(user)"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-pln-red border border-pln-red rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-pln-red focus:ring-offset-2 transition-colors duration-200"
                                        title="Reject User"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                        Reject
                                    </button>

                                    <!-- Delete Button -->
                                    <button
                                        @click="deleteUser(user)"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gray-600 border border-gray-600 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                                        title="Delete User"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="px-6 py-12 text-center">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Users</h3>
                <p class="mt-1 text-sm text-gray-500">
                    No users found in the system.
                </p>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="cancelDelete"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                >
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                        >
                            <svg
                                class="h-6 w-6 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"
                                />
                            </svg>
                        </div>
                        <div
                            class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                        >
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900"
                            >
                                Delete User
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete
                                    <strong>{{ userToDelete?.name }}</strong
                                    >? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button
                            @click="confirmDelete"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Delete
                        </button>
                        <button
                            @click="cancelDelete"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
