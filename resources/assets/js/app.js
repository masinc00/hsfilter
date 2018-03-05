/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window._ = require('lodash');
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
// import FilterBar from './components/filter-bar.vue'
// import ExampleComponent from './components/ExampleComponent.vue'
// const app = new Vue({
//     el: '#app'
// });

//Vue.component('filter-bar', FilterBar);


import App from './components/App'
// const filterbar = 
new Vue({

    el: '#app',
    //template: '<div> {{ h1 }} </div>',
    //data: { h1: "hello world" },
    //render: h => h(FilterBar)
    components: {
        App
    },
    template: '<App/>',
    // data: {
    //     text: "",
    //     json: "",
    // }
})