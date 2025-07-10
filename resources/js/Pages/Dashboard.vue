<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    stats: Object,
    recentFiles: Array,
});

// Check if current user is admin
const isAdmin = computed(() => page.props.auth.user.role === 'admin');

// Get appropriate layout component
const LayoutComponent = computed(() => isAdmin.value ? AdminLayout : UserLayout);

// Stats for Admin
const adminStatCards = computed(() => [
    {
        title: 'Total Files',
        subtitle: 'In System',
        value: props.stats?.totalFiles || 0,
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        color: 'blue',
        badge: 'Total'
    },
    {
        title: 'Total Racks',
        subtitle: 'Available',
        value: props.stats?.totalRacks || 0,
        icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        color: 'green'
    },
    {
        title: 'Total Storage',
        subtitle: 'Used',
        value: props.stats?.totalStorage || '0 MB',
        icon: 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
        color: 'purple'
    },
    {
        title: 'Active Users',
        subtitle: 'Approved',
        value: props.stats?.activeUsers || 0,
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        color: 'yellow'
    },
    {
        title: 'Pending Users',
        subtitle: 'Need Approval',
        value: props.stats?.pendingUsers || 0,
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'red',
        badge: (props.stats?.pendingUsers || 0) > 0 ? 'Action Required' : null
    }
]);

// Stats for User
const userStatCards = computed(() => [
    {
        title: 'My Files',
        subtitle: 'Uploaded',
        value: props.stats?.myFiles || 0,
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        color: 'blue',
        badge: 'Mine'
    },
    {
        title: 'Storage Used',
        subtitle: 'By Me',
        value: props.stats?.myStorage || '0 MB',
        icon: 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
        color: 'green'
    },
    {
        title: 'Recent Uploads',
        subtitle: 'This Week',
        value: props.stats?.recentUploads || 0,
        icon: 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12',
        color: 'purple'
    },
    {
        title: 'Downloads',
        subtitle: 'My Files',
        value: props.stats?.downloadCount || 0,
        icon: 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        color: 'yellow'
    }
]);

// Choose appropriate stats based on role
const statCards = computed(() => isAdmin.value ? adminStatCards.value : userStatCards.value);
</script>

<template>
    <Head :title="isAdmin ? 'Admin Dashboard - SIPUS' : 'Dashboard - SIPUS'" />

    <component :is="LayoutComponent">
        <template #header>
            {{ isAdmin ? 'Admin Dashboard' : 'My Dashboard' }}
        </template>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div
                v-for="(card, index) in statCards"
                :key="index"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div :class="[
                            'w-12 h-12 rounded-lg flex items-center justify-center',
                            card.color === 'blue' ? 'bg-blue-100' : '',
                            card.color === 'green' ? 'bg-green-100' : '',
                            card.color === 'purple' ? 'bg-purple-100' : '',
                            card.color === 'yellow' ? 'bg-yellow-100' : '',
                            card.color === 'red' ? 'bg-red-100' : ''
                        ]">
                            <svg :class="[
                                'w-6 h-6',
                                card.color === 'blue' ? 'text-blue-600' : '',
                                card.color === 'green' ? 'text-green-600' : '',
                                card.color === 'purple' ? 'text-purple-600' : '',
                                card.color === 'yellow' ? 'text-yellow-600' : '',
                                card.color === 'red' ? 'text-red-600' : ''
                            ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
                            </svg>
                        </div>
                    </div>
                    <div v-if="card.badge" :class="[
                        'px-2 py-1 text-xs font-medium rounded-full',
                        card.badge === 'New' ? 'bg-green-100 text-green-800' : '',
                        card.badge === 'Action Required' ? 'bg-red-100 text-red-800' : ''
                    ]">
                        {{ card.badge }}
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-2xl font-bold text-gray-900">
                        {{ card.value }}
                    </div>
                    <div class="text-sm text-gray-600 font-medium">{{ card.title }}</div>
                    <div class="text-xs text-gray-500">{{ card.subtitle }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            
            <!-- Admin Actions -->
            <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <Link
                    :href="route('files.index')"
                    class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Manage Files</div>
                        <div class="text-xs text-gray-500">View all files</div>
                    </div>
                </Link>
                
                <Link
                    :href="route('admin.users.index')"
                    class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Manage Users</div>
                        <div class="text-xs text-gray-500">User approval</div>
                    </div>
                </Link>
                
                <Link
                    :href="route('admin.racks.index')"
                    class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Manage Racks</div>
                        <div class="text-xs text-gray-500">Categories</div>
                    </div>
                </Link>
                
                <Link
                    :href="route('files.create')"
                    class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Upload File</div>
                        <div class="text-xs text-gray-500">Add new file</div>
                    </div>
                </Link>
            </div>
            
            <!-- User Actions -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Link
                    :href="route('files.index')"
                    class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">My Files</div>
                        <div class="text-xs text-gray-500">View your files</div>
                    </div>
                </Link>
                
                <Link
                    :href="route('files.create')"
                    class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200"
                >
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Upload File</div>
                        <div class="text-xs text-gray-500">Add new file</div>
                    </div>
                </Link>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Need Help?</div>
                        <div class="text-xs text-gray-500">Contact admin</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Files Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isAdmin ? 'Recent Files (All Users)' : 'My Recent Files' }}
                </h3>
                <Link 
                    :href="route('files.index')"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                    See all files
                </Link>
            </div>

            <div v-if="props.recentFiles && props.recentFiles.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(file, index) in props.recentFiles.slice(0, 10)" :key="file.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ file.title }}</div>
                                <div class="text-sm text-gray-500">{{ file.uploader?.name || 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ file.rack?.name || 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ file.formatted_size || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    index === 0 ? 'bg-green-100 text-green-800' : '',
                                    index === 1 ? 'bg-blue-100 text-blue-800' : '',
                                    index === 2 ? 'bg-yellow-100 text-yellow-800' : '',
                                    index > 2 ? 'bg-gray-100 text-gray-800' : ''
                                ]">
                                    Top {{ index + 1 }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-else class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No files uploaded</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by uploading your first file.</p>
            </div>
        </div>
    </component>
</template>
