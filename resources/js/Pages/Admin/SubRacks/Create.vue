<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    racks: Array,
});

const form = useForm({
    rack_id: '',
    name: '',
    description: '',
});

const submit = () => {
    if (!form.rack_id) {
        alert('Please select a rack first');
        return;
    }
    form.post(route('admin.racks.sub-racks.store', form.rack_id), {
        onSuccess: () => {
            // Success will be handled by the redirect from controller
        },
        onError: (errors) => {
            console.error('Error creating sub-rack:', errors);
        }
    });
};
</script>

<template>
    <Head title="Add Sub-Rack - SIPUS" />

    <AdminLayout>
        <template #header>
            Add Sub-Rack
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">📂 Form Tambah Sub-Rak Baru</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Pilih Rak Induk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Rak Induk *
                        </label>
                        <select
                            v-model="form.rack_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">-- Pilih Rak --</option>
                            <option v-for="rack in racks" :key="rack.id" :value="rack.id">
                                {{ rack.name }}
                            </option>
                        </select>
                        <div v-if="form.errors.rack_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.rack_id }}
                        </div>
                    </div>

                    <!-- Nama Sub-Rak -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sub-Rak *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama sub-rak"
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
                            placeholder="Masukkan deskripsi sub-rak (opsional)"
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
                            <span v-else>Tambah Sub-Rak</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
