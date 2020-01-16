$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

window.Vue = require('vue');
if ((document.all && !window.atob) == true) {
    document.getElementById('gallery').innerHTML = `
  <div class="IE9">
    It appears you are using an older version of Internet Explorer.
    Things have changed a lot, and in order to use this page we need you to update to a newer browser.
    </div>`;
} else {
    const app = new Vue({
        el: '#gallery',
        data: {},
        components: {
            'gallery': require('./components/Gallery.vue'),
        },
        created() {
        },
        methods: {},
    });
}
