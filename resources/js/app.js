require("./bootstrap");
// Import modules...
import { createApp, h } from "vue";
import {
    App as InertiaApp,
    plugin as InertiaPlugin,
} from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Store from "./store";
import larasearchVue from "@webcityro/larasearch-vue3";
import VueClickAway from "vue3-click-away";
import GlobalMixin from "./mixins/Global";
import SearchMixin from "./mixins/SearchMixin";

const el = document.getElementById("app");

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin(GlobalMixin)
    .use(InertiaPlugin)
    .use(Store)
    .use(larasearchVue, {
        store: Store,
        mixin: SearchMixin,
    })
    .use(VueClickAway);

InertiaProgress.init({ color: "#4B5563" });

const files = require.context("./components", true, /\.vue$/i);
files.keys().map((key) => {
    app.component(key.split("/").pop().split(".")[0], files(key).default);
});

app.mount(el);
