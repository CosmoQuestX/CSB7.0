import clientStore from 'localforage';
import globalnotification from './components/GlobalNotification.vue';

window.Vue = require('vue');

window.events = new Vue();

window.flash = function (message, type) {
    window.events.$emit(`flash-${type}`, message);
};

window.clientStore = clientStore;


Vue.component('flash', require('./components/Flash.vue'));


const app = new Vue({
    el: '#app'
});

const globalNotifications = new Vue({
    el: '#globalNotifications',
    components: {
        globalnotification
    }
});

// Nav menu notifications
if (!!document.getElementById('usersettings')) {
    const liveUpdates = new Vue({
        el: '#usersettings',
        data: {
            lastUpdate: 0,
            badges: [],
            notifications: [],
        },
        computed: {
            notificationCount() {
                let count = 0;
                this.notifications.forEach((notification) = > {
                    if(
                !notification.read
            )
                count += 1;
            })
                ;
                return count;
            },
            badgeCount() {
                return this.badges.length;
            },
        },
        components: {},
        created() {
            let self = this;
            //  Get Badges and Notifications
            clientStore.getItem('lastUpdate', (err, value) = > {
                if(err) {
                    console.log(err);
                    return -1;
                }

                console.log(`Last Update: ${value}`);
            this.lastUpdate = value;
            if (value == null) {
                console.log("Getting Badges and Notifications from Server");
                this.getBadgesFromServer();
                this.getNotificationsFromServer();
            } else {
                console.log("Getting Badges and Notifications from Storage");
                this.getBadgesFromStorage();
                this.getNotificationsFromStorage();
            }

        })
            ;

            this.initSocketIOCallbacks();
        },
        methods: {
            badgeImageSrc(badge) {
                console.log(`/${badge.image_location}`);
                return `/${badge.image_location}`;
            },
            initSocketIOCallbacks() {
                // Setup Socket.io callbacks
                let name = window.user.name;
                if (!window.socket) {
                    window.socket = io(`${window.location.origin}:3000`, {
                        secure: true
                    });
                    window.socket.on('connect', function (socket) {
                        console.log('Connected to server');
                    });
                }
                console.log(name)
                window.socket.on(`notification:${name}`, (data) = > {
                    this.notifications.push(data.notification);
                this.saveNotifications();
            })
                ;

                window.socket.on(`clearnotifications:${name}`, () = > {
                    this.getNotificationsFromServer();
            })
                ;

                window.events.$on('cleared-notifications', () = > {
                    let readNotifications = [];
                this.notifications.forEach((notification) = > {
                    if(
                !notification.read
            )
                {
                    readNotifications.push(notification.id);
                    notification.read = true;
                }
            })
                ;
                this.updateNotifications(readNotifications);
                this.saveNotifications();
            })
                ;

            },
            getBadgesFromServer() {
                let self = this;
                let JQ = (window.jQuery) ? jQuery : $;
                JQ.getJSON("/api/badges", (data) = > {
                    this.badges = data;
                clientStore.setItem('badges', data).then((data) = > {
                    console.log("Updated Badges");
            }).
                catch((err) = > {
                    console.error(err);
            })
                ;
            }).
                fail(() = > {
                    console.error("Unable to get users badges");
            })
                ;

                this.lastUpdate = new Date();
                clientStore.setItem('lastUpdate', this.lastUpdate).then((data) = > {
                    console.log(`Updated last update to ${data}`);
            }).
                catch((err) = > {
                    console.error(err);
            })
                ;
            },
            getBadgesFromStorage() {
                let currentTime = new Date();
                // Get badge updates from the server every 30 minutes
                if (currentTime - this.lastUpdate > 1800000) {
                    this.getBadgesFromServer();
                } else {
                    clientStore.getItem('badges', (err, value) = > {
                        if(err) {
                            console.error(err);
                        }
                        if(window.jQuery
                )
                    {
                        this.badges = jQuery.map(value, (value, index) = > {
                            return [value];
                    })
                        ;
                    }
                else
                    {
                        this.badges = $.map(value, (value, index) = > {
                            return [value];
                    })
                        ;
                    }

                })
                    ;
                }
            },
            getNotificationsFromServer() {
                let self = this;
                let JQ = (window.jQuery) ? jQuery : $;
                JQ.getJSON("/api/notifications", (data) = > {
                    this.notifications = data;
                clientStore.setItem('notifications', data).then((data) = > {
                    console.log("Updated Notifications");
            }).
                catch((err) = > {
                    console.error(err);
            })
                ;
            }).
                fail(() = > {
                    console.error("Unable to get users notifications");
            })
                ;

                this.lastUpdate = new Date();
                clientStore.setItem('lastUpdate', this.lastUpdate).then((data) = > {
                    console.log(`Updated last update to ${data}`);
            }).
                catch((err) = > {
                    console.error(err);
            })
                ;
            },
            getNotificationsFromStorage() {
                clientStore.getItem('notifications', (err, value) = > {
                    if(err) {
                        console.error(err);
                    }

                    if(window.jQuery
            )
                {
                    this.notifications = jQuery.map(value, (value, index) = > {
                        return [value];
                })
                    ;
                }
            else
                {
                    this.notifications = $.map(value, (value, index) = > {
                        return [value];
                })
                    ;
                }
            })
                ;
            },
            saveNotifications() {
                clientStore.setItem('notifications', this.notifications, (err, value) = > {
                    if(err) {
                        console.error(err);
                    } else {
                        console.log("saved Notifications");
            }
            })
                ;
            },
            updateNotifications(notifications) {
                let JQ = (window.jQuery) ? jQuery : $;
                // Setup csrf protection cookie
                JQ.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': JQ('meta[name="csrf-token"]').attr('content')
                    }
                });
                JQ.ajax({
                    type: "PATCH",
                    url: "/api/notifications",
                    data: {
                        "notifications": notifications
                    },
                    success: () = > {
                    console.log("Updated Notifications");
                let name = window.user.name;
                window.socket.emit(`clearnotifications:${name}`);
            },
                error: (err) =
            >
                {
                    console.error(err);
                }
            })
                ;
            }
        }
    });
    let JQ = (window.jQuery) ? window.jQuery : window.$;
    JQ('.cq-dropdown-button').click(function () {
        JQ(this).closest('.cq-navigation-dropdown').siblings().removeClass('active').find('.cq-navigation-dropdown-content').slideUp(200);
        JQ(this).closest('.cq-navigation-dropdown').toggleClass('active').find('.cq-navigation-dropdown-content').slideToggle(200);
        return false;
    });
    window.addEventListener('mouseup', function (event) {
        var menu = document.getElementById('usersettings');
        if (!menu.contains(event.target) && JQ('#usersettings').hasClass('active')) {
            //document.getElementById("cqdropdown").style.display = "none";
            JQ('#cqdropdown').slideToggle(200);
            JQ('#usersettings').removeClass('active');
            //document.getElementById("cqdropdown").style.opacity = "0.5";
        }
    });
}
