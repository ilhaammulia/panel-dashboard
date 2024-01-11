
<template>
    <div class="card flex justify-content-center">
        <Sidebar v-model:visible="visible">
            <template #header>
                <div class="flex align-items-center gap-2">
                    <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle" />
                    <span class="font-bold">Amy Elsner</span>
                </div>
            </template>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </Sidebar>
        <Button label="Sidebar" icon="pi pi-plus" @click="visible = true" />
    </div>
</template>

<script setup>
import Button from "primevue/button";
import { ref } from "vue";

const visible = ref(false);
</script>