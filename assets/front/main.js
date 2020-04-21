import Vue from "vue";
import "./plugins/axios";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
// import vuetify from "./plugins/vuetify";

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");