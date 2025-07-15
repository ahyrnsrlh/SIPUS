<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();

const props = defineProps({
    file: Object,
});

// Check if current user is admin
const isAdmin = computed(() => page.props.auth.user.role === "admin");
const currentUserId = computed(() => page.props.auth.user.id);

// Get appropriate layout component
const LayoutComponent = computed(() =>
    isAdmin.value ? AdminLayout : UserLayout
);

// Check if user can edit/delete file
const canModifyFile = computed(() => {
    return isAdmin.value || props.file.uploaded_by === currentUserId.value;
});

// Format file size
const formatFileSize = (bytes) => {
    if (bytes >= 1073741824) {
        return (bytes / 1073741824).toFixed(2) + " GB";
    } else if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + " MB";
    } else if (bytes >= 1024) {
        return (bytes / 1024).toFixed(2) + " KB";
    } else {
        return bytes + " bytes";
    }
};

// Delete functionality
const showDeleteModal = ref(false);
const showPreviewModal = ref(false);

const deleteFile = () => {
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    router.delete(route("files.destroy", props.file.id), {
        onSuccess: () => {
            // File deleted successfully, redirect is handled by controller
        },
        onError: (errors) => {
            console.error("Delete failed:", errors);
            showDeleteModal.value = false;

            // Handle different types of errors
            if (errors.error) {
                alert("Error: " + errors.error);
            } else if (errors.message && errors.message.includes("404")) {
                alert("File not found. It may have already been deleted.");
                // Redirect to files index since file doesn't exist
                router.visit(route("files.index"));
            } else {
                alert("Failed to delete file. Please try again.");
            }
        },
        onFinish: () => {
            showDeleteModal.value = false;
        },
    });
};

// Preview functionality
const previewFile = () => {
    showPreviewModal.value = true;
};

const closePreview = () => {
    showPreviewModal.value = false;
};

const isPreviewable = computed(() => {
    const previewableTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/gif",
        "image/webp",
        "image/svg+xml",
        "text/plain",
        "text/csv",
        "text/html",
        "text/css",
        "text/javascript",
        "application/json",
        "application/xml",
        "application/pdf",
    ];
    return previewableTypes.includes(props.file.mime_type);
});

// Download functionality
const downloadFile = () => {
    window.open(route("files.download", props.file.id), "_blank");
};
</script>

<template>
    <Head :title="`${file.title} - SIPUS`" />

    <component :is="LayoutComponent">
        <template #header> File Details </template>

        <div class="max-w-4xl mx-auto">
            <!-- File Info Card -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            >
                <!-- File Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-pln-blue to-dark-blue rounded-xl flex items-center justify-center mr-4"
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
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-charcoal mb-1">
                                {{ file.title }}
                            </h1>
                            <p class="text-sm text-gray-600">
                                {{ file.original_name }}
                            </p>
                            <div
                                class="flex items-center mt-2 space-x-4 text-sm text-gray-500"
                            >
                                <span>📁 {{ formatFileSize(file.size) }}</span>
                                <span
                                    >📅
                                    {{
                                        new Date(
                                            file.created_at
                                        ).toLocaleDateString()
                                    }}</span
                                >
                                <span
                                    v-if="file.uploaded_by === currentUserId"
                                    class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium"
                                >
                                    My File
                                </span>
                                <span
                                    v-else-if="
                                        file.uploader &&
                                        file.uploader.instansi ===
                                            $page.props.auth.user.instansi
                                    "
                                    class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium"
                                >
                                    {{ file.uploader.instansi }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <!-- Preview Button -->
                        <button
                            v-if="isPreviewable"
                            @click="previewFile"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
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
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                            Preview
                        </button>

                        <!-- Download Button -->
                        <button
                            @click="downloadFile"
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
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Download
                        </button>

                        <!-- Edit Button -->
                        <Link
                            v-if="canModifyFile"
                            :href="route('files.edit', file.id)"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
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
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Edit
                        </Link>

                        <!-- Delete Button -->
                        <button
                            v-if="canModifyFile"
                            @click="deleteFile"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
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
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- File Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Description
                            </h3>
                            <p
                                class="text-sm text-gray-900 bg-gray-50 rounded-lg p-3"
                            >
                                {{
                                    file.description ||
                                    "No description provided"
                                }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                File Information
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium">{{
                                        file.mime_type
                                    }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Size:</span>
                                    <span class="font-medium">{{
                                        formatFileSize(file.size)
                                    }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Uploaded:</span>
                                    <span class="font-medium">{{
                                        new Date(
                                            file.created_at
                                        ).toLocaleString()
                                    }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600"
                                        >Last Modified:</span
                                    >
                                    <span class="font-medium">{{
                                        new Date(
                                            file.updated_at
                                        ).toLocaleString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category and Tags -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Category
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-if="file.rack"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-light-blue text-pln-blue"
                                >
                                    📁 {{ file.rack.name }}
                                </div>
                                <div
                                    v-if="file.sub_rack"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 ml-2"
                                >
                                    📂 {{ file.sub_rack.name }}
                                </div>
                            </div>
                        </div>

                        <div v-if="file.tags && file.tags.length > 0">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="tag in file.tags"
                                    :key="tag.id"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700"
                                >
                                    #{{ tag.name }}
                                </span>
                            </div>
                        </div>

                        <div v-if="file.uploader">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Uploaded By
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-pln-blue rounded-full flex items-center justify-center mr-3"
                                    >
                                        <span
                                            class="text-white text-xs font-bold"
                                            >{{
                                                file.uploader.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}</span
                                        >
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ file.uploader.name }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            {{ file.uploader.email }}
                                        </p>
                                        <p
                                            v-if="file.uploader.instansi"
                                            class="text-xs text-gray-600"
                                        >
                                            {{ file.uploader.instansi }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="flex justify-start">
                <Link
                    :href="route('files.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
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
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                    Back to Files
                </Link>
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
                    @click="showDeleteModal = false"
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
                                    Delete File
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">
                                        Are you sure you want to delete "<strong
                                            >{{ file.title }}</strong
                                        >"? This action cannot be undone.
                                    </p>
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
                            Delete
                        </button>
                        <button
                            @click="showDeleteModal = false"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Preview Modal -->
        <div
            v-if="showPreviewModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="preview-modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                    @click="closePreview"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full"
                >
                    <!-- Header -->
                    <div
                        class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="preview-modal-title"
                                >
                                    File Preview
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ file.title }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <!-- Download button in modal -->
                                <button
                                    @click="downloadFile"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-pln-blue hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue"
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
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Download
                                </button>
                                <!-- Close button -->
                                <button
                                    @click="closePreview"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Content -->
                    <div
                        class="bg-gray-50 px-4 py-6 sm:px-6"
                        style="max-height: 70vh; overflow-y: auto"
                    >
                        <!-- Image Preview -->
                        <div
                            v-if="file.mime_type.startsWith('image/')"
                            class="text-center"
                        >
                            <img
                                :src="route('files.preview', file.id)"
                                :alt="file.title"
                                class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg"
                                @error="$event.target.style.display = 'none'"
                            />
                        </div>

                        <!-- PDF Preview -->
                        <div
                            v-else-if="file.mime_type === 'application/pdf'"
                            class="w-full"
                        >
                            <iframe
                                :src="route('files.preview', file.id)"
                                class="w-full h-96 border rounded-lg"
                                title="PDF Preview"
                            ></iframe>
                        </div>

                        <!-- Text/Code Preview -->
                        <div
                            v-else-if="
                                file.mime_type.startsWith('text/') ||
                                file.mime_type === 'application/json' ||
                                file.mime_type === 'application/xml'
                            "
                        >
                            <iframe
                                :src="route('files.preview', file.id)"
                                class="w-full h-96 border rounded-lg bg-white"
                                title="Text Preview"
                            ></iframe>
                        </div>

                        <!-- Fallback for unsupported types -->
                        <div v-else class="text-center py-8">
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
                                Preview not available
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                This file type cannot be previewed directly.
                            </p>
                            <div class="mt-4">
                                <button
                                    @click="downloadFile"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-pln-blue hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue"
                                >
                                    Download to view
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
