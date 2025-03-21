import '../css/app.css';
import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
//console.log("Pusher Key:", import.meta.env.VITE_PUSHER_APP_KEY);
//console.log("Pusher Cluster:", import.meta.env.VITE_PUSHER_APP_CLUSTER);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    /*key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,*/
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
