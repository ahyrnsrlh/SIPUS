<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    rack: Object,
});

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('admin.racks.sub-racks.store', props.rack.id), {
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
            <div class="bg-white rounded-xl shadow-sm border border-pln-gray p-6">
                <h3 class="text-lg font-semibold text-charcoal mb-6">📂 Form Tambah Sub-Rak Baru</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Pilih Rak Induk -->
                    <!-- Info Rak Induk -->
                    <div class="bg-pln-gray p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-charcoal mb-1">Rak Induk</h3>
                        <p class="text-lg font-semibold text-pln-blue">{{ rack.name }}</p>
                        <p class="text-sm text-gray-600" v-if="rack.description">{{ rack.description }}</p>
                    </div>

                    <!-- Nama Sub-Rak -->
                    <div>
                        <label class="block text-sm font-medium text-charcoal mb-2">
                            Nama Sub-Rak *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-transparent"
                            placeholder="Masukkan nama sub-rak"
                            required
                        />
                        <div v-if="form.errors.name" class="mt-1 text-sm text-pln-red">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-charcoal mb-2">
                            Deskripsi (opsional)
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pln-blue focus:border-transparent"
                            placeholder="Masukkan deskripsi sub-rak (opsional)"
                        ></textarea>
                        <div v-if="form.errors.description" class="mt-1 text-sm text-pln-red">
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
                            class="px-6 py-2 bg-pln-blue text-white rounded-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-pln-blue disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            <span v-if="form.processing" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                            <span v-else">Tambah Sub-Rak</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
