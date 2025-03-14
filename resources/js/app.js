import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import PostList from './components/bulletinboard/PostList.vue';
import PostForm from './components/bulletinboard/PostForm.vue';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const app = createApp({});
    app.component('post-list', PostList);
    app.component('post-form', PostForm);
    app.mount('#app');
});
