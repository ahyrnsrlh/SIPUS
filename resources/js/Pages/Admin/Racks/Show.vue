<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    rack: Object,
    files: Object,
    filesBySubRack: Object,
    subRacks: {
        type: Array,
        default: () => []
    },
});

const showPreviewModal = ref(false);
const fileToPreview = ref(null);

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

// Check if file is previewable
const isPreviewable = (file) => {
    const previewableTypes = [
        "image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp", "image/svg+xml",
        "text/plain", "text/csv", "text/html", "text/css", "text/javascript",
        "application/json", "application/xml", "application/pdf",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document", // .docx
        "application/msword", // .doc
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", // .xlsx
        "application/vnd.ms-excel", // .xls
        "application/vnd.openxmlformats-officedocument.presentationml.presentation", // .pptx
        "application/vnd.ms-powerpoint", // .ppt
    ];
    return previewableTypes.includes(file.mime_type);
};

// Preview functionality
const previewFile = (file) => {
    fileToPreview.value = file;
    showPreviewModal.value = true;
};

const closePreview = () => {
    showPreviewModal.value = false;
    fileToPreview.value = null;
};

// Download functionality
const downloadFile = (file) => {
    window.open(route('files.download', file.id), '_blank');
};

// Get file type display name
const getFileTypeDisplay = (mimeType) => {
    const typeMap = {
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'Word Document (.docx)',
        'application/msword': 'Word Document (.doc)',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'Excel Spreadsheet (.xlsx)',
        'application/vnd.ms-excel': 'Excel Spreadsheet (.xls)',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'PowerPoint Presentation (.pptx)',
        'application/vnd.ms-powerpoint': 'PowerPoint Presentation (.ppt)',
        'application/pdf': 'PDF Document',
        'image/jpeg': 'JPEG Image',
        'image/png': 'PNG Image',
        'text/plain': 'Text File',
    };
    return typeMap[mimeType] || mimeType;
};

// Open office document in external viewer
const openOfficeViewer = (file, viewerType) => {
    const fileUrl = encodeURIComponent(window.location.origin + route('files.download', file.id));
    
    let viewerUrl = '';
    
    if (viewerType === 'microsoft') {
        viewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${fileUrl}`;
    } else if (viewerType === 'google') {
        viewerUrl = `https://docs.google.com/gview?url=${fileUrl}&embedded=true`;
    }
    
    window.open(viewerUrl, '_blank');
};

// Get files for a specific sub-rack
const getSubRackFiles = (subRackId) => {
    if (!subRackId || !props.filesBySubRack) return [];
    return props.filesBySubRack[subRackId] || [];
};

// Get files without sub-rack (general files)
const getGeneralFiles = () => {
    if (!props.filesBySubRack) return [];
    return props.filesBySubRack[null] || [];
};
</script>

<template>
    <Head :title="`${rack?.name || 'Rack'} - Files - SIPUS`" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('admin.racks.index')"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ rack?.name || 'Unknown Rack' }}</h1>
                        <p class="text-sm text-gray-600">Files in this rack</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('admin.racks.edit', rack?.id)"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Rack
                    </Link>
                </div>
            </div>
        </template>

        <!-- Rack Info Card -->
        <div v-if="rack" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">📁 Rack Information</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Name:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack?.name || 'Unknown' }}</span>
                        </div>
                        <div v-if="rack?.description">
                            <span class="text-sm font-medium text-gray-500">Description:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack.description }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Created:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack?.created_at ? new Date(rack.created_at).toLocaleDateString() : 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">📊 Statistics</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Files:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ files?.total || 0 }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Sub-Racks:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack?.sub_racks_count || 0 }}</span>
                        </div>
                    </div>
                </div>
                
                <div v-if="rack?.creator">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">👤 Creator</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Created by:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack?.creator?.name || 'Unknown' }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ rack?.creator?.email || 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="!rack" class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pln-blue mx-auto mb-4"></div>
            <p class="text-gray-500">Loading rack information...</p>
        </div>

        <!-- Files by Sub-Rack -->
        <div class="space-y-6" v-if="rack">
            <!-- General Files (No Sub-Rack) -->
            <div v-if="getGeneralFiles().length > 0" class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">📄 General Files</h3>
                    <p class="text-sm text-gray-600">Files not assigned to any sub-rack</p>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="file in getGeneralFiles()"
                        :key="file.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- File Info -->
                            <div class="col-span-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-pln-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ file.title }}</div>
                                        <div class="text-sm text-gray-500">{{ file.description || 'No description' }}</div>
                                        <!-- Tags -->
                                        <div v-if="file.tags && file.tags.length > 0" class="mt-1 flex flex-wrap gap-1">
                                            <span
                                                v-for="tag in file.tags.slice(0, 3)"
                                                :key="tag.id"
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700"
                                            >
                                                #{{ tag.name }}
                                            </span>
                                            <span v-if="file.tags.length > 3" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                                +{{ file.tags.length - 3 }} more
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Size -->
                            <div class="col-span-2">
                                <div class="text-sm text-gray-900">{{ file.formatted_size || formatFileSize(file.size) }}</div>
                            </div>

                            <!-- Uploader -->
                            <div class="col-span-3">
                                <div class="text-sm text-gray-900">{{ file.uploader?.name || 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ file.uploader?.instansi || '' }}</div>
                            </div>

                            <!-- Date -->
                            <div class="col-span-1">
                                <div class="text-sm text-gray-500">{{ new Date(file.created_at).toLocaleDateString() }}</div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1">
                                <div class="flex items-center space-x-2">
                                    <!-- Preview Button -->
                                    <button
                                        v-if="isPreviewable(file)"
                                        @click="previewFile(file)"
                                        class="text-gray-500 hover:text-gray-700 p-1 rounded transition-colors duration-200"
                                        title="👁️ Preview File"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- View Details Button -->
                                    <Link
                                        :href="route('files.show', file.id)"
                                        class="text-blue-500 hover:text-blue-700 p-1 rounded transition-colors duration-200"
                                        title="📄 View Details"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </Link>

                                    <!-- Download Button -->
                                    <button
                                        @click="downloadFile(file)"
                                        class="text-pln-blue hover:text-dark-blue p-1 rounded transition-colors duration-200"
                                        title="📥 Download"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub-Rack Files -->
            <div
                v-for="subRack in (subRacks || [])"
                :key="subRack?.id"
                v-if="subRack?.id && getSubRackFiles(subRack.id).length > 0"
                class="bg-white rounded-xl shadow-sm border border-gray-200"
            >
                <div class="px-6 py-4 border-b border-gray-200 bg-light-blue">
                    <h3 class="text-lg font-semibold text-pln-blue">📂 {{ subRack?.name || 'Unknown Sub-Rack' }}</h3>
                    <p class="text-sm text-gray-600">{{ subRack?.description || 'No description' }}</p>
                    <div class="text-sm text-gray-500 mt-1">{{ getSubRackFiles(subRack?.id).length }} file(s)</div>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="file in getSubRackFiles(subRack?.id)"
                        :key="file.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- File Info -->
                            <div class="col-span-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-pln-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ file.title }}</div>
                                        <div class="text-sm text-gray-500">{{ file.description || 'No description' }}</div>
                                        <!-- Tags -->
                                        <div v-if="file.tags && file.tags.length > 0" class="mt-1 flex flex-wrap gap-1">
                                            <span
                                                v-for="tag in file.tags.slice(0, 3)"
                                                :key="tag.id"
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700"
                                            >
                                                #{{ tag.name }}
                                            </span>
                                            <span v-if="file.tags.length > 3" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                                +{{ file.tags.length - 3 }} more
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Size -->
                            <div class="col-span-2">
                                <div class="text-sm text-gray-900">{{ file.formatted_size || formatFileSize(file.size) }}</div>
                            </div>

                            <!-- Uploader -->
                            <div class="col-span-3">
                                <div class="text-sm text-gray-900">{{ file.uploader?.name || 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ file.uploader?.instansi || '' }}</div>
                            </div>

                            <!-- Date -->
                            <div class="col-span-1">
                                <div class="text-sm text-gray-500">{{ new Date(file.created_at).toLocaleDateString() }}</div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1">
                                <div class="flex items-center space-x-2">
                                    <!-- Preview Button -->
                                    <button
                                        v-if="isPreviewable(file)"
                                        @click="previewFile(file)"
                                        class="text-gray-500 hover:text-gray-700 p-1 rounded transition-colors duration-200"
                                        title="👁️ Preview File"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- View Details Button -->
                                    <Link
                                        :href="route('files.show', file.id)"
                                        class="text-blue-500 hover:text-blue-700 p-1 rounded transition-colors duration-200"
                                        title="📄 View Details"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </Link>

                                    <!-- Download Button -->
                                    <button
                                        @click="downloadFile(file)"
                                        class="text-pln-blue hover:text-dark-blue p-1 rounded transition-colors duration-200"
                                        title="📥 Download"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="(files?.total || 0) === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No files in this rack</h3>
                <p class="mt-1 text-sm text-gray-500">Files uploaded to this rack will appear here.</p>
            </div>
        </div>

        <!-- File Preview Modal -->
        <div
            v-if="showPreviewModal && fileToPreview"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="preview-modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                    @click="closePreview"
                ></div>

                <!-- Modal panel -->
                <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full">
                    <!-- Header -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="preview-modal-title">
                                    File Preview
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">{{ fileToPreview.title }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <!-- Download button in modal -->
                                <button
                                    @click="downloadFile(fileToPreview)"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-pln-blue hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download
                                </button>
                                <!-- Close button -->
                                <button
                                    @click="closePreview"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Content -->
                    <div class="bg-gray-50 px-4 py-6 sm:px-6" style="max-height: 70vh; overflow-y: auto">
                        <!-- Image Preview -->
                        <div v-if="fileToPreview.mime_type.startsWith('image/')" class="text-center">
                            <img
                                :src="route('files.preview', fileToPreview.id)"
                                :alt="fileToPreview.title"
                                class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg"
                                @error="$event.target.style.display = 'none'"
                            />
                        </div>

                        <!-- PDF Preview -->
                        <div v-else-if="fileToPreview.mime_type === 'application/pdf'" class="w-full">
                            <iframe
                                :src="route('files.preview', fileToPreview.id)"
                                class="w-full h-96 border rounded-lg"
                                title="PDF Preview"
                            ></iframe>
                        </div>

                        <!-- Office Documents Preview -->
                        <div
                            v-else-if="
                                fileToPreview.mime_type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                                fileToPreview.mime_type === 'application/msword' ||
                                fileToPreview.mime_type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
                                fileToPreview.mime_type === 'application/vnd.ms-excel' ||
                                fileToPreview.mime_type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                                fileToPreview.mime_type === 'application/vnd.ms-powerpoint'
                            "
                            class="w-full"
                        >
                            <!-- Office Online Viewer -->
                            <div class="bg-white rounded-lg border p-4 mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">📄 Office Document Preview</h4>
                                <p class="text-xs text-gray-600 mb-3">Choose a viewer to preview this document:</p>
                                <div class="flex flex-wrap gap-2">
                                    <!-- Microsoft Office Online Viewer -->
                                    <button
                                        @click="openOfficeViewer(fileToPreview, 'microsoft')"
                                        class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                        </svg>
                                        Microsoft Office Online
                                    </button>
                                    
                                    <!-- Google Docs Viewer -->
                                    <button
                                        @click="openOfficeViewer(fileToPreview, 'google')"
                                        class="inline-flex items-center px-3 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Google Docs Viewer
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Document Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ fileToPreview.original_name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ getFileTypeDisplay(fileToPreview.mime_type) }} • {{ formatFileSize(fileToPreview.size) }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">Click a viewer button above to preview this document</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Text/Code Preview -->
                        <div
                            v-else-if="
                                fileToPreview.mime_type.startsWith('text/') ||
                                fileToPreview.mime_type === 'application/json' ||
                                fileToPreview.mime_type === 'application/xml'
                            "
                        >
                            <iframe
                                :src="route('files.preview', fileToPreview.id)"
                                class="w-full h-96 border rounded-lg bg-white"
                                title="Text Preview"
                            ></iframe>
                        </div>

                        <!-- Fallback for unsupported types -->
                        <div v-else class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Preview not available</h3>
                            <p class="mt-1 text-sm text-gray-500">This file type cannot be previewed directly.</p>
                            <div class="mt-4">
                                <button
                                    @click="downloadFile(fileToPreview)"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-pln-blue hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue"
                                >
                                    Download to view
                                </button>
                            </div>
                        </div>

                        <!-- File Information -->
                        <div class="mt-6 bg-white rounded-lg p-4 border">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">File Information</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Original Name</dt>
                                    <dd class="text-sm text-gray-900">{{ fileToPreview.original_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">File Type</dt>
                                    <dd class="text-sm text-gray-900">{{ fileToPreview.mime_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Size</dt>
                                    <dd class="text-sm text-gray-900">{{ formatFileSize(fileToPreview.size) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Uploaded</dt>
                                    <dd class="text-sm text-gray-900">{{ new Date(fileToPreview.created_at).toLocaleDateString() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
