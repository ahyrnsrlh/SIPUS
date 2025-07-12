<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    file: Object,
    racks: Array,
    subRacks: Array,
    tags: Array,
});

const form = useForm({
    title: props.file.title,
    description: props.file.description || "",
    rack_id: props.file.rack_id || "",
    sub_rack_id: props.file.sub_rack_id || "",
    tags: props.file.tags?.map((tag) => tag.id) || [],
});

const submit = () => {
    form.patch(route("files.update", props.file.id));
};
</script>

<template>
    <Head title="Edit File - SIPUS" />

    <AdminLayout>
        <template #header> Edit File </template>

        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    📝 Form Edit Metadata File
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Edit File (Hanya Uploader atau Admin)
                </p>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- File Info Display -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-light-blue rounded-lg flex items-center justify-center mr-4"
                            >
                                <svg
                                    class="w-6 h-6 text-pln-blue"
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
                                <div class="text-sm font-medium text-gray-900">
                                    {{ file.filename }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ file.formatted_size }} • Uploaded
                                    {{
                                        new Date(
                                            file.created_at
                                        ).toLocaleDateString()
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul File -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Judul File *</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan judul file"
                                required
                            />
                            <div
                                v-if="form.errors.title"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Rak -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Rak</label
                            >
                            <select
                                v-model="form.rack_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">-- Pilih Rak --</option>
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
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.rack_id }}
                            </div>
                        </div>

                        <!-- Sub-Rak -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Sub-Rak</label
                            >
                            <select
                                v-model="form.sub_rack_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :disabled="!form.rack_id"
                            >
                                <option value="">-- Pilih Sub-Rak --</option>
                                <option
                                    v-for="subRack in subRacks"
                                    :key="subRack.id"
                                    :value="subRack.id"
                                >
                                    {{ subRack.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.sub_rack_id"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.sub_rack_id }}
                            </div>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Tag</label
                            >
                            <select
                                v-model="form.tags"
                                multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                size="4"
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

                    <!-- Deskripsi -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Deskripsi</label
                        >
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan deskripsi file"
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <!-- Selected Tags Display -->
                    <div v-if="form.tags.length > 0" class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >Tag yang Dipilih:</label
                        >
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tagId in form.tags"
                                :key="tagId"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium bg-light-blue text-pln-blue rounded-full"
                            >
                                {{ tags.find((tag) => tag.id === tagId)?.name }}
                                <button
                                    type="button"
                                    @click="
                                        form.tags = form.tags.filter(
                                            (id) => id !== tagId
                                        )
                                    "
                                    class="ml-1 text-pln-blue hover:text-dark-blue"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex items-center justify-between pt-6 border-t border-gray-200"
                    >
                        <button
                            type="button"
                            @click="$inertia.visit(route('files.index'))"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-pln-blue text-white rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            <span
                                v-if="form.processing"
                                class="flex items-center"
                            >
                                <svg
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
                                Saving...
                            </span>
                            <span v-else>💾 Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
