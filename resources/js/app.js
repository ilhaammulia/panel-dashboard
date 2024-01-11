import "./bootstrap";
import "../css/app.css";

import PrimeVue from "primevue/config";
import "primevue/resources/themes/lara-light-green/theme.css";
import "primevue/resources/primevue.min.css";

import Sidebar from "primevue/sidebar";
import Button from "primevue/button";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Register the Sidebar component globally
        app.component("Sidebar", Sidebar);
        app.component("Button", Button);

        // Use PrimeVue globally
        app.use(PrimeVue);

        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
