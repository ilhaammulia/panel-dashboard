import { createWebHistory, createRouter } from "vue-router";
import PanelUser from "@/Pages/Panel/Manage-User.vue";


const routes = [
    {
      path: "/manage-user",
      name: "manage-user", // Sesuai dengan nama rute di web.php
      component: PanelUser,
    },
  ];
  

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;