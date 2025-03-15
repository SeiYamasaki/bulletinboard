import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import PostList from './components/bulletinboard/PostList.vue';
import PostForm from './components/bulletinboard/PostForm.vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const app = createApp({});
    app.component('post-list', PostList);
    app.component('post-form', PostForm);
    app.mount('#app');
});

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    encrypted: true
});
