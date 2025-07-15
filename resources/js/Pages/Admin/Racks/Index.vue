<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    racks: Object, // Paginated object from Laravel
});

const showDeleteModal = ref(false);
const rackToDelete = ref(null);

const deleteRack = (rack) => {
    rackToDelete.value = rack;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (rackToDelete.value) {
        router.delete(route("admin.racks.destroy", rackToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                rackToDelete.value = null;
            },
            onError: () => {
                // Keep modal open on error
            },
        });
    }
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    rackToDelete.value = null;
};
</script>

<template>
    <Head title="Racks Management - SIPUS" />

    <AdminLayout>
        <template #header> Racks Management </template>

        <!-- Action Bar -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray p-6 mb-6"
        >
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-charcoal">
                        🗂️ Rak Berkas
                    </h2>
                    <p class="text-sm text-gray-600">
                        Kelola dan atur kategori rak penyimpanan file
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Add Rack Button -->
                    <Link
                        :href="route('admin.racks.create')"
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
                        Tambah Rak
                    </Link>
                </div>
            </div>
        </div>

        <!-- Racks Grid/List -->
        <div
            class="bg-white rounded-xl shadow-sm border border-pln-gray overflow-hidden"
        >
            <!-- Show data if exists -->
            <div v-if="racks?.data?.length > 0">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-pln-gray bg-pln-gray">
                    <div
                        class="grid grid-cols-12 gap-4 text-xs font-medium text-charcoal uppercase tracking-wider"
                    >
                        <div class="col-span-6">Rack Info</div>
                        <div class="col-span-2">Sub-Racks</div>
                        <div class="col-span-2">Files Count</div>
                        <div class="col-span-2">Actions</div>
                    </div>
                </div>

                <!-- Rack List -->
                <div class="divide-y divide-pln-gray">
                    <div
                        v-for="rack in racks.data"
                        :key="rack.id"
                        class="px-6 py-4 hover:bg-pln-gray transition-colors duration-200"
                    >
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Rack Info -->
                            <div class="col-span-6 flex items-center">
                                <div
                                    class="w-10 h-10 bg-light-blue rounded-lg flex items-center justify-center mr-3"
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
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <div
                                        class="text-sm font-medium text-charcoal"
                                    >
                                        {{ rack.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{
                                            rack.description || "No description"
                                        }}
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-Racks Count -->
                            <div class="col-span-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-blue text-pln-blue"
                                >
                                    {{ rack.sub_racks_count || 0 }} Sub-Rak
                                </span>
                            </div>

                            <!-- Files Count -->
                            <div class="col-span-2">
                                <div class="text-sm text-charcoal font-medium">
                                    {{ rack.files_count || 0 }} Files
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <!-- View Files -->
                                    <Link
                                        :href="
                                            route('admin.racks.show', rack.id)
                                        "
                                        class="text-green-600 hover:text-green-800 p-1 rounded"
                                        title="👁️ Lihat Files"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </Link>

                                    <!-- Add Sub-Rack -->
                                    <Link
                                        :href="
                                            route(
                                                'admin.racks.sub-racks.create',
                                                rack.id
                                            )
                                        "
                                        class="text-pln-blue hover:text-dark-blue p-1 rounded"
                                        title="➕ Tambah Sub-Rak"
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
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                    </Link>

                                    <!-- Edit Rack -->
                                    <Link
                                        :href="
                                            route('admin.racks.edit', rack.id)
                                        "
                                        class="text-charcoal hover:text-dark-blue p-1 rounded"
                                        title="✏️ Edit Rak"
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

                                    <!-- Delete Rack -->
                                    <button
                                        @click="deleteRack(rack)"
                                        class="text-pln-red hover:text-red-700 p-1 rounded"
                                        title="🗑️ Hapus Rak"
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
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-charcoal">
                    No racks found
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by creating your first rack.
                </p>
                <div class="mt-6">
                    <Link
                        :href="route('admin.racks.create')"
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
                        Tambah Rak Pertama
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="
                    racks?.data?.length > 0 &&
                    (racks.prev_page_url || racks.next_page_url)
                "
                class="px-6 py-4 border-t border-pln-gray bg-gray-50"
            >
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ racks.from }} sampai {{ racks.to }} dari
                        {{ racks.total }} rak
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link
                            v-if="racks.prev_page_url"
                            :href="racks.prev_page_url"
                            class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200"
                        >
                            Previous
                        </Link>
                        <span
                            v-else
                            class="px-3 py-1 text-sm text-gray-400 border border-gray-200 rounded-md bg-gray-100"
                        >
                            Previous
                        </span>

                        <span
                            class="px-3 py-1 text-sm font-medium bg-pln-blue text-white rounded-md"
                        >
                            {{ racks.current_page }}
                        </span>

                        <Link
                            v-if="racks.next_page_url"
                            :href="racks.next_page_url"
                            class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 transition-colors duration-200"
                        >
                            Next
                        </Link>
                        <span
                            v-else
                            class="px-3 py-1 text-sm text-gray-400 border border-gray-200 rounded-md bg-gray-100"
                        >
                            Next
                        </span>
                    </div>
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
                                    class="h-6 w-6 text-pln-red"
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
                                    class="text-lg leading-6 font-medium text-charcoal"
                                    id="modal-title"
                                >
                                    🗑️ Form Hapus Rak
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-3">
                                        <strong
                                            >Konfirmasi Penghapusan Rak</strong
                                        >
                                    </p>
                                    <p class="text-sm text-charcoal">
                                        Anda yakin ingin menghapus rak:
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-charcoal mt-1"
                                    >
                                        {{ rackToDelete?.name }}?
                                    </p>
                                    <div
                                        class="mt-3 bg-yellow-50 border border-pln-yellow rounded-md p-3"
                                    >
                                        <p class="text-xs text-gray-700">
                                            Menghapus rak akan menghapus semua
                                            sub-rak dan file di dalamnya. Aksi
                                            ini tidak dapat dibatalkan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-pln-gray px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            @click="confirmDelete"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-pln-red text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-red sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            🗑️ Hapus Rak
                        </button>
                        <button
                            @click="cancelDelete"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-pln-gray shadow-sm px-4 py-2 bg-white text-base font-medium text-charcoal hover:bg-pln-gray focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-blue sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            ❌ Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
