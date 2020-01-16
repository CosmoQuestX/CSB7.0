function checkPasswd(form) {
    var message = document.getElementById("message");
    var rt = false;

    if (form.password.value.length > 0) {
        if (form.password.value == form.confirm_password.value) {
            // We should check for sensible password rules
            rt = true;
        } else if (form.password.value != form.confirm_password.value) {
            message.innerHTML = "Passwords do not match!";
        }
    }

    return rt;
}

function fnShowHide() {
    ckbox = document.getElementsByName('pck')[0];
    infields = document.getElementsByClassName("newpass");

    if (ckbox.checked) {
        infields[0].style.display = "table-row";
        infields[1].style.display = "table-row";
    } else {
        infields[0].style.display = "none";
        infields[1].style.display = "none";

    }

}