<script setup>
import { Head, router } from '@inertiajs/vue3';
</script>

<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer">
        <v-card
            class="mx-auto card"
            max-width="300"
            min-height="100vh"
        >
            <v-list density="compact">
            <v-list-subheader class="sidebar-title">HOME</v-list-subheader>

            <v-list-item
                v-for="(item, i) in items"
                :key="i"
                :value="item"
                color="primary"
            >
                <template v-slot:prepend>
                <v-icon :icon="item.icon"></v-icon>
                </template>

                <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item>
            <v-list-subheader class="sidebar-title">MANAGE</v-list-subheader>
            <v-list-item
                v-for="(item, i) in manages"
                :key="i"
                :value="item"
                color="primary"
                @click="to(item.link)"
            >
                <template v-slot:prepend>
                <v-icon :icon="item.icon"></v-icon>
                </template>

                <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item>

            <v-list-subheader class="sidebar-title">PANEL</v-list-subheader>
            <v-list-item
                v-for="(item, i) in panel"
                :key="i"
                :value="item"
                color="primary"
            >
                <template v-slot:prepend>
                    <v-icon :icon="item.icon"></v-icon>
                </template>

                <v-list-item-title v-text="item.text"></v-list-item-title>
            </v-list-item>

            </v-list>
        </v-card>
    </v-navigation-drawer>

    <v-app-bar>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-app-bar-title>Panel Dashboard</v-app-bar-title>
    </v-app-bar>

    <v-main>
        <slot />
    </v-main>
  </v-app>
</template>

<script>
    import { router } from '@inertiajs/vue3';
  export default {
    data: () => ({ 
        drawer: null,
        items: [
            { text: 'Real-Time', icon: 'mdi-clock' },
            { text: 'Audience', icon: 'mdi-account' },
            { text: 'Conversions', icon: 'mdi-flag' },
        ],
        manages:[
        { text: 'User', icon: 'mdi-clock', link: 'manage-user' },
        { text: 'Role', icon: 'mdi-account' },
        ],
        panel:[
        { text: 'Panel', icon: 'mdi-clock' },
        { text: 'User Panel', icon: 'mdi-account' },
        ],
    }),
    methods: {
    to(link) {
      router.visit(route(link))
    }
  }

  }
</script>
