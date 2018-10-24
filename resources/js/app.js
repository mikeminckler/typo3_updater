import Vue from 'vue';

import axios from 'axios';    
Object.defineProperty(Vue.prototype, '$http', { value: axios });

import lodash from 'lodash';    
Object.defineProperty(Vue.prototype, '$lodash', { value: lodash });

import Editor from '@tinymce/tinymce-vue';

//let tinymceApiKey = document.head.querySelector('meta[name="tinymce-api-key"]');

Vue.component('content-item', require('./components/ContentItem.vue'));
Vue.component('content-items', require('./components/ContentItems.vue'));
Vue.component('editor', Editor);

const app = new Vue({
    el: '#app',

    methods: {
        
        logout: function() {

            this.$http.post('/logout').then( response => {
                window.location.href = '/';
            }, error => {
                console.log('There was an error during logout');
            });
        
        }

    }

});
