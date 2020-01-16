// Setup csrf protection cookie
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Global Variables
if (!window.socket) {
    window.socket = io(`${window.location.origin}:3000`, {secure: true});
    window.socket.on('connect', function (socket) {
        console.log('Connected to server');
    });
}

// window.socket.on('newdiscussion:all', function(data) {
//     console.log('New Discussion');
// });
//
// window.socket.on('newreply:user:admin', function(data) {
//     console.log('New Repy by Admin');
// });
//
// window.socket.on('favorited:user:admin', function(data) {
//     console.log(data);
// });

window.Vue = require('vue');
window.moment = require('moment');

// Glogal Components
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component('tinymce', require('./components/Editor.vue'));
Vue.component('favorable', require('./components/Favorable.vue'));


const app = new Vue({
    el: '#chatroom',
    data: {
        description: null,
        pageEditor: null,
        showNewDiscussionForm: false,
        categories: [],
        filterCategory: null,
        discussion: null,
    },
    components: {
        'category-list': require('./components/CategoryList.vue'),
        'discussion-list': require('./components/DiscussionList.vue'),
        'new-discussion-form': require('./components/NewDiscussionForm.vue'),
        'discussion': require('./components/Discussion.vue'),
    },
    watch: {
        categories() {
            if (window.location.search != "") {
                this.handleQueryString(window.location.search);
            }
        },
    },
    created() {
        this.fetchCategories();
        // this.fetchDiscussion();

    },
    methods: {
        toggleNewDiscussionForm() {
            this.showNewDiscussionForm = !this.showNewDiscussionForm;
        },
        fetchDiscussion() {
            $.ajax({
                url: this.url(),
                success: function (result) {
                    this.discussion = JSON.parse(result);
                },
                async: false
            });
        },
        discussionUrl() {
            let href = window.location.href;
            let origin = window.location.origin;
            return href.replace(`${origin}/chatroom`, '/api');
        },
        fetchCategories() {
            // GET the categories
            $.get("/api/categories", (resp) = > {
                let cats = JSON.parse(resp);
            cats.unshift({name: "all", id: 0});
            this.categories = cats;
            //   this.categories.concat(JSON.parse(resp));
        }).
            fail(() = > {
                flash('Unable to load discussions', 'error'
        )
            ;
        })
            ;
        },
        handleFilterEvent(cat) {
            this.filterCategory = (cat == "all") ? '' : cat;
            // this.fetchDiscussion();
            if (cat == "all" || cat.name == "all") {
                window.history.pushState({}, "", '/chatroom');
            } else {
                window.history.pushState({}, "", `/chatroom?category=${cat.name}`);
            }
        },
        handleQueryString(str) {
            console.log(`Cagegory: ${this.parseQuery('category', str)}`);
            let catName = this.parseQuery('category', str);
            if (catName != null) {
                console.log(`cat name: ${catName}`);
                console.log(this.categories);
                let category = this.categories.find((cat) = > {
                    console.log(`${cat.name} == ${catName}`);
                return cat.name == catName;
            })
                ;
                console.log(`Category: ${category}`);
                if (category) {
                    this.handleFilterEvent(category);
                }
            }
        },
        parseQuery(name, str) {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(str);
            if (results == null) {
                return null;
            } else {
                return decodeURI(results[1]) || 0;
            }
        },

    },
    mounted: function () {
    }
});

// Global JQuery event
// $('.discussion-image').click(() => {
//     if($(this).data('enlarged') == 'true')
//     {
//         console.log('isEnlarged');
//         $(this).data('enlarged', 'false');
//     }else {
//         console.log('Is Not enlarged');
//         $(this).data('enlarged', 'true');
//         $(this).width(1000);
//     }
// });
