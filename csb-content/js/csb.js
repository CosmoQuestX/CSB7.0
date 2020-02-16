// Get the button that opens the alert. When it is clicked
// change the display: none; to display: blocl for related alert
var logonBtn = document.getElementById("alert-login");
if (logonBtn) {
    logonBtn.onclick = function () {
        alert.style.display = "block";
    }
}

// Get the alert-box
var alert = document.getElementById("alert-box");

// Get the button that opens the alert
var btnHome = document.getElementById("alert-botton-home");

// Get the <span> element that closes the alert
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal

if (btnHome) {
    btnHome.onclick = function () {
        if (alert) {
            alert.style.display = "block";
        }
    }
}

// When the user clicks on <span> (x), close the modal
if (span) {
    span.onclick = function () {
        alert.style.display = "none";
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target && event.target == alert) {
        if (alert) {
            alert.style.display = "none";
        }
    }
}

function login(form) {
    var un = form.Username.value;
    var pw = form.Password.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("post", "Login", true);
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            loginResults();
        }
    }
}

window.addEventListener("load", window, function () {
    var loginForm = document.getElementById("LoginForm");
    window.addEventListener(loginForm, "submit", function () {
        login(loginForm);
    });
});


function loginResults() {
    var loggedIn = document.getElementById("LoggedIn");
    var badLogin = document.getElementById("BadLogin");
    if (xmlhttp.responseText.indexOf("failed") == -1) {
        loggedIn.innerHTML = "Logged in as " + xmlhttp.responseText;
        loggedIn.style.display = "block";
        form.style.display = "none";
    } else {
        badLogin.style.display = "block";
        form.Username.select();
        form.Username.className = "Highlighted";
        setTimeout(function () {
            badLogin.style.display = 'none';
        }, 3000);
    }
}