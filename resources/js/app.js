import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import Default from './Layouts/Default.vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/src/js';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import piniaPluginPersistedState from 'pinia-plugin-persistedstate';
import Vue3Toastify from 'vue3-toastify'
import Vuesax from 'vuesax-alpha'
import 'vuesax-alpha/theme-chalk/index.css'
import 'vuesax-alpha/theme-chalk/dark/css-vars.css'

const pinia = createPinia();
pinia.use(piniaPluginPersistedState);

createInertiaApp({
  title: (title) => `${title}`,
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    let page = pages[`./Pages/${name}.vue`];
    page.default.layout = page.default.layout || Default;
    return page;
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.config.globalProperties.$route = route
    app.use(plugin)
      .use(pinia)
      .use(ZiggyVue)
      .use(Vuesax)
      .use(Vue3Toastify, {
        "theme": "dark",
      })
      .mount(el);
    //   .use(VueDisqus, { shortname: 'zumimangas' })
  },
});
