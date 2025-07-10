<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    rack: Object,
});

const form = useForm({
    name: props.rack.name,
    description: props.rack.description || '',
});

const submit = () => {
    form.patch(route('admin.racks.update', props.rack.id));
};

const deleteSubRack = (subRackId) => {
    if (confirm('Apakah Anda yakin ingin menghapus sub-rak ini? Semua file akan ikut terhapus!')) {
        form.delete(route('admin.sub-racks.destroy', subRackId), {
            preserveState: true,
        });
    }
};
</script>

<template>
    <Head title="Edit Rack - SIPUS" />

    <AdminLayout>
        <template #header>
            Edit Rack
        </template>

        <div class="max-w-4xl mx-auto">
            <!-- Edit Rack Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">📁 Edit Rack</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama Rak -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Rak *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama rak"
                            required
                        />
                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (opsional)
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan deskripsi rak (opsional)"
                        ></textarea>
                        <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4">
                        <button
                            type="button"
                            @click="$inertia.visit(route('admin.racks.index'))"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200"
                        >
                            Batal
                        </button>
                        
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            <span v-if="form.processing" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else>💾 Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sub-Racks List -->
            <div v-if="rack.sub_racks && rack.sub_racks.length > 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📂 Sub-Racks dalam {{ rack.name }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div 
                        v-for="subRack in rack.sub_racks" 
                        :key="subRack.id"
                        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ subRack.name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ subRack.description || 'No description' }}</p>
                                <p class="text-xs text-gray-400 mt-1">Created: {{ new Date(subRack.created_at).toLocaleDateString() }}</p>
                            </div>
                            <button
                                @click="deleteSubRack(subRack.id)"
                                class="ml-2 text-red-600 hover:text-red-900 p-1 rounded"
                                title="Hapus Sub-Rak"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
