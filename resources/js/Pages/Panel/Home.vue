<script setup>
  import Dashboard from '@/Pages/Admin/Dashboard.vue';
  import { onMounted } from 'vue'
</script>

<template>
  <Dashboard>
    <main class="p-4 md:ml-64 h-auto pt-20">
      <!-- Komponen Cards -->
      <div><Cards /></div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><Graph /></div>
        <div><RecentUser /></div>
      </div>
    </main>
  </Dashboard>
</template>

<script>
import Cards from '@/Components/Cards.vue';
import Graph from '@/Components/Graph.vue';
import RecentUser from '@/Components/RecentUser.vue';
  export default {
    data () {
      return {
        desserts: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
          },
          {
            name: 'Eclair',
            calories: 262,
          },
          {
            name: 'Cupcake',
            calories: 305,
          },
          {
            name: 'Gingerbread',
            calories: 356,
          },
          {
            name: 'Jelly bean',
            calories: 375,
          },
          {
            name: 'Lollipop',
            calories: 392,
          },
          {
            name: 'Honeycomb',
            calories: 408,
          },
          {
            name: 'Donut',
            calories: 452,
          },
          {
            name: 'KitKat',
            calories: 518,
          },
        ],
      }
    },
  }
</script>