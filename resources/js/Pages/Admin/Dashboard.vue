<template>
  <Head title="Dashboard" />

  <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total Users</span>
                        <div class="text-900 font-medium text-xl">{{ meta.total_users.total }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-users text-blue-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{ meta.total_users.new }} new </span>
                <span class="text-500">since yesterday</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total Panels</span>
                        <div class="text-900 font-medium text-xl">{{ meta.total_panels.total }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-desktop text-orange-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{ meta.total_panels.active }}</span>
                <span class="text-500"> active panels</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total User Panels</span>
                        <div class="text-900 font-medium text-xl">{{ meta.total_user_panels.total }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-sitemap text-cyan-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{ meta.total_user_panels.new }}</span>
                <span class="text-500"> newly registered</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Expired Panels</span>
                        <div class="text-900 font-medium text-xl">{{ meta.total_user_panels_expired.total }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-red-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-clock text-red-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">{{ meta.total_user_panels_expired.new }}</span>
                <span class="text-500"> more than a week</span>
            </div>
        </div>

        <div class="col-12 xl:col-6">
            <div class="card">
                <div class="flex justify-content-between align-items-center mb-2">
                    <h5>Best Selling Panels</h5>
                </div>
                <ul class="list-none p-0 m-0">
                    <li v-for="panel in top_panels" :key="panel" class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">
                        <div>
                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">{{ panel.name }}</span>
                            <div class="mt-1 text-600">{{ panel.domain }}</div>
                        </div>
                        <div class="mt-2 md:mt-0 flex align-items-center">
                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height: 8px">
                                <div class="bg-blue-500 h-full" :style="`width: ${panel.panel_percentage}%`"></div>
                            </div>
                            <span class="text-blue-500 ml-3 font-medium">{{ panel.panel_count }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12 xl:col-6">
            <div class="card">
                <div class="flex align-items-center justify-content-between mb-1">
                    <h5>Notifications <Badge :value="notifications.length" severity="primary"></Badge></h5>
                </div>

                <ul class="p-0 mx-0 mt-0 mb-4 list-none max-h-[30rem] overflow-y-auto">
                    <li v-for="notification in notifications" :key="notification.id" class="flex align-items-center py-3 border-bottom-1 surface-border">
                        <div :class="`bg-${notification.color}-100`" class="w-3rem h-3rem flex align-items-center justify-content-center border-circle mr-3 flex-shrink-0">
                            <i :class="`${notification.icon} text-${notification.color}-500`" class="text-xl"></i>
                        </div>
                        <div class="flex justify-between items-center w-full">
                            <span class="text-700 line-height-3">{{ notification.message }}</span>
                            <span class="text-700 line-height-3">{{ formatDate(notification.created_at) }}</span>
                        </div>
                    </li>
                    <li v-if="!notifications.length">Notifications are empty.</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Badge from 'primevue/badge';

export default {
  layout: AppLayout,
  components: { Head, DataTable, Column, Badge, },
  props: {
    notifications: {
        type: Array,
        default: () => []
    },
    meta: {
        type: Object,
        default: () => {}
    },
    top_panels: {
        type: Array,
        default: () => []
    }
  },
  methods: {
    formatDate(date) {
        const format = new Date(date);
        return Intl.DateTimeFormat('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }).format(format);
    }
  }
}

</script>