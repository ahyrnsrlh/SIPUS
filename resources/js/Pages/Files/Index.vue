<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";

const page = usePage();

defineProps({
    files: Object,
    filters: Object,
    racks: Array,
    subRacks: Array,
    users: Array,
    tags: Array,
});

// Check if current user is admin
const isAdmin = computed(() => page.props.auth.user.role === "admin");
const currentUserId = computed(() => page.props.auth.user.id);

const searchQuery = ref("");
const showFilters = ref(false);
const showDeleteModal = ref(false);
const fileToDelete = ref(null);

const filterForm = reactive({
    search: "",
    rack_id: "",
    sub_rack_id: "",
    uploader_id: "",
    date_from: "",
    date_to: "",
    tags: [],
});

const searchFiles = () => {
    router.get(
        route("files.index"),
        { search: searchQuery.value },
        { preserveState: true }
    );
};

const applyFilters = () => {
    const params = {};
    Object.keys(filterForm).forEach((key) => {
        if (filterForm[key] && filterForm[key].length > 0) {
            params[key] = filterForm[key];
        }
    });
    router.get(route("files.index"), params, { preserveState: true });
};

const resetFilters = () => {
    Object.keys(filterForm).forEach((key) => {
        filterForm[key] = Array.isArray(filterForm[key]) ? [] : "";
    });
    router.get(route("files.index"));
};

const deleteFile = (file) => {
    fileToDelete.value = file;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (fileToDelete.value) {
        router.delete(route("files.destroy", fileToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                fileToDelete.value = null;
            },
            onError: () => {
                // Keep modal open on error
            },
        });
    }
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    fileToDelete.value = null;
};

// Check if user can edit/delete file
const canModifyFile = (file) => {
    return isAdmin.value || file.uploader_id === currentUserId.value;
};

// Get appropriate layout component
const LayoutComponent = computed(() =>
    isAdmin.value ? AdminLayout : UserLayout
);
</script>

<template>
    <Head :title="isAdmin ? 'Files Management - SIPUS' : 'My Files - SIPUS'" />

    <component :is="LayoutComponent">
        <template #header>
            {{ isAdmin ? "Files Management" : "My Files" }}
        </template>

        <!-- Advanced Filter Section -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    🔍 Filter & Cari File
                </h3>
                <button
                    @click="showFilters = !showFilters"
                    class="text-sm text-pln-blue hover:text-dark-blue font-medium"
                >
                    {{ showFilters ? "Hide Filters" : "Show Filters" }}
                </button>
            </div>

            <!-- Basic Search -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Kata Kunci</label
                >
                <div class="relative">
                    <input
                        v-model="filterForm.search"
                        type="text"
                        placeholder="Cari berdasarkan nama file, deskripsi..."
                        class="w-full pl-10 pr-4 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
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
            </div>

            <!-- Advanced Filters -->
            <div v-if="showFilters" class="space-y-4">
                <div
                    :class="[
                        isAdmin
                            ? 'grid grid-cols-1 md:grid-cols-3 gap-4'
                            : 'grid grid-cols-1 md:grid-cols-2 gap-4',
                    ]"
                >
                    <!-- Rak -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Rak</label
                        >
                        <select
                            v-model="filterForm.rack_id"
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                        >
                            <option value="">-- Semua Rak --</option>
                            <option
                                v-for="rack in racks"
                                :key="rack.id"
                                :value="rack.id"
                            >
                                {{ rack.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Sub-Rak -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Sub-Rak</label
                        >
                        <select
                            v-model="filterForm.sub_rack_id"
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                            :disabled="!filterForm.rack_id"
                        >
                            <option value="">-- Semua Sub-Rak --</option>
                            <option
                                v-for="subRack in subRacks"
                                :key="subRack.id"
                                :value="subRack.id"
                            >
                                {{ subRack.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Uploader - Only for Admin -->
                    <div v-if="isAdmin">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Uploader</label
                        >
                        <select
                            v-model="filterForm.uploader_id"
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                        >
                            <option value="">-- Semua User --</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tanggal Upload Dari -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Tanggal Upload Dari 📅</label
                        >
                        <input
                            v-model="filterForm.date_from"
                            type="date"
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                        />
                    </div>

                    <!-- Tanggal Upload Sampai -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Tanggal Upload Sampai 📅</label
                        >
                        <input
                            v-model="filterForm.date_to"
                            type="date"
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                        />
                    </div>

                    <!-- Tags -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Tag</label
                        >
                        <select
                            v-model="filterForm.tags"
                            multiple
                            class="w-full px-3 py-2 border border-pln-gray rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-pln-blue"
                        >
                            <option
                                v-for="tag in tags"
                                :key="tag.id"
                                :value="tag.id"
                            >
                                {{ tag.name }}
                            </option>
                        </select>
                        <div class="mt-1 text-xs text-gray-500">
                            Hold Ctrl/Cmd untuk pilih beberapa tag
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center gap-3 mt-4">
                <button
                    @click="applyFilters"
                    class="inline-flex items-center px-4 py-2 bg-pln-blue text-white text-sm font-medium rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue transition-colors duration-200"
                >
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
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                    Cari
                </button>
                <button
                    @click="resetFilters"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200"
                >
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
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        <!-- Action Bar -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
        >
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ isAdmin ? "File Library" : "My Files" }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{
                            isAdmin
                                ? "Manage and organize all files in the system"
                                : "Manage your uploaded files"
                        }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Upload Button -->
                    <Link
                        :href="route('files.create')"
                        class="inline-flex items-center px-4 py-2 bg-pln-blue text-white text-sm font-medium rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-offset-2 transition-colors duration-200"
                    >
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
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        {{ isAdmin ? "Upload File" : "Upload New File" }}
                    </Link>
                </div>
            </div>
        </div>

        <!-- Files Grid/List -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <div v-if="files && files.data && files.data.length > 0">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div
                        :class="[
                            'grid gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider',
                            isAdmin ? 'grid-cols-12' : 'grid-cols-10',
                        ]"
                    >
                        <div :class="isAdmin ? 'col-span-4' : 'col-span-5'">
                            File Info
                        </div>
                        <div class="col-span-2">Category</div>
                        <div class="col-span-2">Size</div>
                        <div v-if="isAdmin" class="col-span-2">Uploaded By</div>
                        <div class="col-span-1">Date</div>
                        <div class="col-span-1">Actions</div>
                    </div>
                </div>

                <!-- File List -->
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="file in files.data"
                        :key="file.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <div
                            :class="[
                                'grid gap-4 items-center',
                                isAdmin ? 'grid-cols-12' : 'grid-cols-10',
                            ]"
                        >
                            <!-- File Info -->
                            <div :class="isAdmin ? 'col-span-4' : 'col-span-5'">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3"
                                    >
                                        <svg
                                            class="w-5 h-5 text-pln-blue"
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
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ file.title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                file.description ||
                                                "No description"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-span-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-blue text-pln-blue"
                                >
                                    {{ file.rack?.name || "General" }}
                                </span>
                                <div
                                    v-if="file.sub_rack"
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    {{ file.sub_rack.name }}
                                </div>
                            </div>

                            <!-- Size -->
                            <div class="col-span-2">
                                <div class="text-sm text-gray-900">
                                    {{ file.formatted_size || "N/A" }}
                                </div>
                            </div>

                            <!-- Uploaded By - Only for Admin -->
                            <div v-if="isAdmin" class="col-span-2">
                                <div class="text-sm text-gray-900">
                                    {{ file.uploader?.name || "Unknown" }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ file.uploader?.instansi || "" }}
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-span-1">
                                <div class="text-sm text-gray-500">
                                    {{
                                        new Date(
                                            file.created_at
                                        ).toLocaleDateString()
                                    }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1">
                                <div class="flex items-center space-x-2">
                                    <!-- Download Button - Always available -->
                                    <Link
                                        :href="route('files.download', file.id)"
                                        class="text-pln-blue hover:text-dark-blue p-1 rounded"
                                        title="📥 Download"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </Link>

                                    <!-- Edit Button - Only if user can modify -->
                                    <Link
                                        v-if="canModifyFile(file)"
                                        :href="route('files.edit', file.id)"
                                        class="text-gray-600 hover:text-gray-900 p-1 rounded"
                                        title="✏️ Edit File"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                    </Link>

                                    <!-- Delete Button - Only if user can modify -->
                                    <button
                                        v-if="canModifyFile(file)"
                                        @click="deleteFile(file)"
                                        class="text-red-600 hover:text-red-900 p-1 rounded"
                                        title="🗑️ Hapus File"
                                    >
                                        <svg
                                            class="w-4 h-4"
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
                                    </button>

                                    <!-- Read-only indicator for files user cannot modify -->
                                    <span
                                        v-if="!canModifyFile(file) && !isAdmin"
                                        class="text-gray-400 text-xs px-2 py-1 bg-gray-100 rounded"
                                        title="File uploaded by another user"
                                    >
                                        👁️ View Only
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="files.links"
                    class="px-6 py-4 border-t border-gray-200 bg-gray-50"
                >
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ files.from }} to {{ files.to }} of
                            {{ files.total }} results
                        </div>
                        <div class="flex space-x-1">
                            <template
                                v-for="link in files.links"
                                :key="link.label"
                            >
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        link.active
                                            ? 'bg-pln-blue text-white'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100',
                                    ]"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        'text-gray-400 cursor-not-allowed',
                                    ]"
                                    v-html="link.label"
                                />
                            </template>
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
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    No files found
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by uploading your first file.
                </p>
                <div class="mt-6">
                    <Link
                        :href="route('files.create')"
                        class="inline-flex items-center px-4 py-2 bg-pln-blue text-white text-sm font-medium rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-offset-2"
                    >
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
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        Upload File
                    </Link>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
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
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
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
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="modal-title"
                                >
                                    🗑️ Form Hapus File
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-3">
                                        <strong
                                            >Konfirmasi Penghapusan File</strong
                                        >
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        ❗ Anda yakin ingin menghapus file:
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-gray-900 mt-1"
                                    >
                                        **{{
                                            fileToDelete?.title ||
                                            fileToDelete?.filename
                                        }}**?
                                    </p>
                                    <div
                                        class="mt-3 bg-yellow-50 border border-yellow-200 rounded-md p-3"
                                    >
                                        <p class="text-xs text-yellow-800">
                                            {{
                                                isAdmin
                                                    ? "⚠️ Sebagai admin, Anda dapat menghapus semua file. File yang dihapus tidak dapat dipulihkan."
                                                    : "⚠️ Anda hanya dapat menghapus file yang Anda upload sendiri. File yang dihapus tidak dapat dipulihkan."
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            @click="confirmDelete"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            🗑️ Hapus File
                        </button>
                        <button
                            @click="cancelDelete"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            ❌ Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
