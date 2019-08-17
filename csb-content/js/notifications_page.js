import notificationlist from './components/NotificationList.vue';

console.log("Notifications Page");

const notifications = new window.Vue({
    el: '#notificationlist',
    components: {
        notificationlist,
    }
});
