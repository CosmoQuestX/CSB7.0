<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>


</head>
<body>
    <form id="LoginForm" onsubmit="return false">
        <h1>Login Form</h1>
        <div class="FormRow">
            <label for="Username">Username:</label>
            <input type="text" size="15" id="Username" name="Username">
        </div>
        <div class="FormRow">
            <label for="Password">Password:</label>
            <input type="password" size="15" id="Password" name="Password">
        </div>
        <div class="FormRow" id="LoginButtonDiv">
            <input type="submit" value="Login">
        </div>
        <div id="BadLogin" style="display: none;">
            <p>The login information you entered does not match
                an account in our records. Please try again.</p>
        </div>
    </form>
</body>

<script>

    document.getElementById("LoginButtonDiv").addEventListener("click", function() {
            document.getElementByID("BadLogin").style.display = "inline";
        });
    }

</script>

</html>

