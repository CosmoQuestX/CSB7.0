
// Get the alert-box
var alert = document.getElementById("alert-box");

// Get the button that opens the alert
var btn = document.getElementById("alert-botton");

// Get the <span> element that closes the alert
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    alert.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    alert.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
        if (event.target == alert) {
            alert.style.display = "none";
        }
    }
