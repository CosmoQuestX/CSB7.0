<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:44 PM
 */


// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader;
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


/* ----------------------------------------------------------------------
   Where should they go to?
   ---------------------------------------------------------------------- */

// Are they on this site?
$referringURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



/* -----------------------------------------------------------------------
   Registration form
        1) Tell them if there is an error (check with JS) TODO
        2) Get their info
        3) Send their info and the return to URL in the form

        Also include:
            - signin instead TODO

   ---------------------------------------------------------------------- */
GLOBAL $ACC_URL;

?>

<h3 class="font-weight-bold">Register</h3>

<form id="registration" class="px-4" action="<?php echo($ACC_URL."auth-login.php"); ?>" method="post">

    <input type="hidden" name="referringURL" value="<?php echo $BASE_URL; ?>">
    <input type="hidden" name="go" value="regForm">

    <div class="error-msg">
        <?php if (isset($_SESSION['errMsg'])) {
            echo "<span style=\"color: red;\">" . $_SESSION['errMsg'] . "</span>";
            unset($_SESSION['errMsg']);
        } ?>
    </div>


    <label for="name">Username</label>
    <input name="name" id="name" class="form-control" type="text">

    <label for="email">Email</label>
    <input name="email" id="email" class="form-control" type="text">

    <label for="password">Enter Password</label>
    <input name="password" id="registerPassword" class="form-control" type="password">

    <label for="confirm">Confirm Password</label>
    <input name="confirm" id="confirm" class="form-control" type="password">

    <input type="checkbox" name="remember" class="mr-3" style="vertical-align:middle;" id="remember"><label for="remember">Remember me</label>
    
    <input type="submit" name="register" value="Register" class="btn btn-cq btn-block mt-3">

</form>

<!-- Validation -->
<script src="<?php echo $BASE_URL; ?>csb-themes/default/js/bs4-form-validation.min.js"></script>
<script>
    let registration = new Validation("registration");
    registration.requireText("name", 0, 50, [], []);
    registration.requireEmail("email", 4, 99, [], []);
    registration.registerPassword("registerPassword", 6, 50, [], [], "confirm");
</script>

