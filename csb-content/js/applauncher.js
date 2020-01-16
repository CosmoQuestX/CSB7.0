if(!!document.getElementById('app-launcher'))
{
  const applauncher = new window.Vue({
    el: '#app-launcher',
    components: {
      'applauncher': require('./components/AppLauncher.vue')
    }
  });
}
