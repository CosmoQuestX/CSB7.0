let JQ = ($) ? $ : jQuery;

function check() {
  JQ.getJSON('/auth/check', (data) => {
    if (data.refresh == "true") {
      console.log("Must reload the page");
      console.log(data.reason);
      window.location.reload(true);
    } else {
    }
  });
}

function hasUser() {
  return window.user != null && window.user.name != "" && window.user.email != "";
}

function wpAdminPanel() {
  return !window.location.href.includes('/wp-admin') && !window.location.href.includes('preview_id') && !window.location.href.includes('preview');
}

JQ(document).ready(() => {
  // only check if there is a user object...
  if (wpAdminPanel()) {
    check();
    window.setInterval(check, 12000);
  }
});
