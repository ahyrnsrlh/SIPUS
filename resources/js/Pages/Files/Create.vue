<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const props = defineProps({
    racks: Array,
    subRacks: Array,
    tags: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === "admin");

const form = useForm({
    title: "",
    file: null,
    rack_id: "",
    sub_rack_id: "",
    description: "",
    tags: [],
});

const fileInput = ref(null);
const tagInput = ref("");
const selectedTags = ref([]);

// Filter sub-racks based on selected rack
const filteredSubRacks = computed(() => {
    if (!form.rack_id) return [];
    return props.subRacks.filter((subRack) => subRack.rack_id == form.rack_id);
});

// Watch for rack changes to reset sub-rack
watch(
    () => form.rack_id,
    () => {
        form.sub_rack_id = "";
    }
);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
        // Auto-fill title if empty
        if (!form.title) {
            form.title = file.name.replace(/\.[^/.]+$/, ""); // Remove extension
        }
    }
};

const addTag = () => {
    const tag = tagInput.value.trim();
    if (tag && !selectedTags.value.includes(tag)) {
        selectedTags.value.push(tag);
        form.tags = [...selectedTags.value];
        tagInput.value = "";
    }
};

const removeTag = (index) => {
    selectedTags.value.splice(index, 1);
    form.tags = [...selectedTags.value];
};

const addExistingTag = (tagName) => {
    if (!selectedTags.value.includes(tagName)) {
        selectedTags.value.push(tagName);
        form.tags = [...selectedTags.value];
    }
};

const submit = () => {
    form.post(route("files.store"), {
        onSuccess: () => {
            form.reset();
            selectedTags.value = [];
            if (fileInput.value) {
                fileInput.value.value = "";
            }
        },
    });
};

const getFileSize = (file) => {
    if (!file) return "";
    const bytes = file.size;
    if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + " MB";
    } else if (bytes >= 1024) {
        return (bytes / 1024).toFixed(2) + " KB";
    } else {
        return bytes + " bytes";
    }
};
</script>

<template>
    <Head title="Upload File - SIPUS" />

    <component :is="isAdmin ? AdminLayout : UserLayout">
        <template #header> Upload New File </template>

        <!-- Page Header -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray p-6 mb-6"
        >
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-pln-blue rounded-xl flex items-center justify-center mr-4"
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
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-charcoal">
                        📤 Upload File Baru
                    </h1>
                    <p class="text-sm text-gray-600">
                        Unggah dan kategorikan file ke dalam sistem SIPUS
                    </p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray overflow-hidden"
        >
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="px-6 py-4 border-b border-pln-gray bg-pln-gray">
                    <h2 class="text-lg font-semibold text-charcoal">
                        📋 Informasi File
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- File Upload -->
                    <div>
                        <label
                            for="file"
                            class="block text-sm font-medium text-charcoal mb-2"
                        >
                            📁 File *
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-pln-blue transition-colors duration-200"
                        >
                            <div class="space-y-1 text-center">
                                <svg
                                    v-if="!form.file"
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 48 48"
                                >
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <div v-if="form.file" class="text-center">
                                    <p
                                        class="text-sm text-pln-blue font-medium"
                                    >
                                        {{ form.file.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ getFileSize(form.file) }}
                                    </p>
                                </div>
                                <div class="flex text-sm text-gray-600">
                                    <label
                                        for="file"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-pln-blue hover:text-dark-blue focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-pln-blue"
                                    >
                                        <span v-if="!form.file"
                                            >Upload a file</span
                                        >
                                        <span v-else>Change file</span>
                                        <input
                                            ref="fileInput"
                                            id="file"
                                            name="file"
                                            type="file"
                                            class="sr-only"
                                            @change="handleFileChange"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.txt,.zip,.rar"
                                        />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, DOC, XLS, PPT, Images, TXT, ZIP up to
                                    10MB
                                </p>
                            </div>
                        </div>
                        <div
                            v-if="form.errors.file"
                            class="mt-2 text-sm text-pln-red"
                        >
                            {{ form.errors.file }}
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-charcoal mb-2"
                        >
                            🏷️ Judul File *
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-pln-blue focus:border-pln-blue sm:text-sm"
                            placeholder="Masukkan judul file..."
                            required
                        />
                        <div
                            v-if="form.errors.title"
                            class="mt-2 text-sm text-pln-red"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <!-- Rack Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="rack_id"
                                class="block text-sm font-medium text-charcoal mb-2"
                            >
                                🗂️ Rak *
                            </label>
                            <select
                                id="rack_id"
                                v-model="form.rack_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-pln-blue focus:border-pln-blue sm:text-sm"
                                required
                            >
                                <option value="">Pilih Rak</option>
                                <option
                                    v-for="rack in racks"
                                    :key="rack.id"
                                    :value="rack.id"
                                >
                                    {{ rack.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.rack_id"
                                class="mt-2 text-sm text-pln-red"
                            >
                                {{ form.errors.rack_id }}
                            </div>
                        </div>

                        <div>
                            <label
                                for="sub_rack_id"
                                class="block text-sm font-medium text-charcoal mb-2"
                            >
                                📂 Sub Rak
                            </label>
                            <select
                                id="sub_rack_id"
                                v-model="form.sub_rack_id"
                                :disabled="!form.rack_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-pln-blue focus:border-pln-blue sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                            >
                                <option value="">
                                    Pilih Sub Rak (Opsional)
                                </option>
                                <option
                                    v-for="subRack in filteredSubRacks"
                                    :key="subRack.id"
                                    :value="subRack.id"
                                >
                                    {{ subRack.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.sub_rack_id"
                                class="mt-2 text-sm text-pln-red"
                            >
                                {{ form.errors.sub_rack_id }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-charcoal mb-2"
                        >
                            📝 Deskripsi
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-pln-blue focus:border-pln-blue sm:text-sm"
                            placeholder="Deskripsi file (opsional)..."
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="mt-2 text-sm text-pln-red"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label
                            for="tags"
                            class="block text-sm font-medium text-charcoal mb-2"
                        >
                            🏷️ Tags
                        </label>

                        <!-- Selected Tags -->
                        <div v-if="selectedTags.length > 0" class="mb-3">
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(tag, index) in selectedTags"
                                    :key="index"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-light-blue text-pln-blue border border-pln-blue"
                                >
                                    {{ tag }}
                                    <button
                                        type="button"
                                        @click="removeTag(index)"
                                        class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full text-pln-blue hover:bg-pln-blue hover:text-white focus:outline-none"
                                    >
                                        <svg
                                            class="w-3 h-3"
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
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Add Tag Input -->
                        <div class="flex gap-2">
                            <input
                                v-model="tagInput"
                                type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-pln-blue focus:border-pln-blue sm:text-sm"
                                placeholder="Ketik tag baru..."
                                @keydown.enter.prevent="addTag"
                            />
                            <button
                                type="button"
                                @click="addTag"
                                class="px-4 py-2 bg-pln-blue text-white text-sm font-medium rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-offset-2 transition-colors duration-200"
                            >
                                Tambah
                            </button>
                        </div>

                        <!-- Existing Tags -->
                        <div v-if="tags && tags.length > 0" class="mt-3">
                            <p class="text-xs text-gray-500 mb-2">
                                Tags yang sudah ada:
                            </p>
                            <div class="flex flex-wrap gap-1">
                                <button
                                    v-for="tag in tags"
                                    :key="tag.id"
                                    type="button"
                                    @click="addExistingTag(tag.name)"
                                    :disabled="selectedTags.includes(tag.name)"
                                    class="px-2 py-1 text-xs border border-gray-300 rounded hover:border-pln-blue hover:text-pln-blue focus:outline-none focus:ring-1 focus:ring-pln-blue disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                >
                                    {{ tag.name }}
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="form.errors.tags"
                            class="mt-2 text-sm text-pln-red"
                        >
                            {{ form.errors.tags }}
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-pln-gray flex items-center justify-between"
                >
                    <a
                        :href="route('files.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue transition-colors duration-200"
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
                                d="M11 17l-5-5m0 0l5-5m-5 5h12"
                            />
                        </svg>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2 bg-pln-blue text-white text-sm font-medium rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                            />
                        </svg>
                        {{ form.processing ? "Mengupload..." : "Upload File" }}
                    </button>
                </div>
            </form>
        </div>
    </component>
</template>
