<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:44 PM
 */


// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader, $ACC_URL, $db;
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

if (isset($_GET['go'])) {
    if ($_GET['go'] == 'submitted') {
        ?>
        <h1>Rescue is coming</h1>
        <p>We've emailed you a link you can use to reset your password.</p>
        <?php
    } elseif ($_GET['go'] == 'success') {
        echo "Thanks! You should now be able to login.";

    } else {
        // Check if stored password = hash use password_verify($user['password'], $chkuser['password'])
        $query  = "SELECT * from password_resets WHERE email='". $_GET['go'] ."' ORDER BY created_at DESC LIMIT 1";
        $result = $db->runQuery($query)[0];
        if(password_verify($_GET['token'], $result['token'])) {
            ?>
            <h1>Please enter your password</h1>
            <form action="<?php echo($ACC_URL."auth-login.php"); ?>" method="post">
                <input type="hidden" name="go" value="passwordReset">
                <input type="hidden" name="email" value="<?php echo $_GET['go']; ?>">
                <div class="error-msg"><?php if (isset($_SESSION['errMsg'])) {
                        echo "<span style=\"color: red;\">" . $_SESSION['errMsg'] . "</span>";
                        unset($_SESSION['errMsg']);
                    } ?>
                </div>

                <div class="clear"></div>
                <div class="form-input-row">
                    <div class="form-input-left"><label for="password">Enter Password</label></div>
                    <div class="form-input-right"><input name="password" type="password"></div>
                </div>
                <div class="clear"></div>
                <div class="form-input-row">
                    <div class="form-input-left"><label for="confirm">Confirm Password</label></div>
                    <div class="form-input-right"><input name="confirm" type="password"></div>
                </div>
                <div class="clear"></div>
                <div class="field-submit">
                    <input type="submit" name="register" value="Register"
                           class="form-submit-button">
                </div>
            </form>
            <?php
        } else {
            echo "invalid rescue link";
        }
    }
} else {

    /* ----------------------------------------------------------------------
       Where should they go to?
       ---------------------------------------------------------------------- */

// Are they on this site?
    $referringURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    ?>

    <!-----------------------------------------------------------------------
       Registration form
            1) Tell them if there is an error (check with JS) TODO
            2) Get their info
            3) Send their info and the return to URL in the form

            Also include:
                - signin instead TODO

       ---------------------------------------------------------------------->
    <h3 class="font-weight-bold">Rescue Password</h3>
    <div id="form-input-box">
        <p>Please enter either the username or email address you use with this site.
            We will send you an email with a new password that you can change (or note). </p>

        <form action="<?php echo($ACC_URL . "auth-login.php"); ?>" method="post">
            <input type="hidden" name="referringURL" value="<?php echo $BASE_URL; ?>">
            <input type="hidden" name="go" value="rescueForm">

            <div class="error-msg"><?php if (isset($_SESSION['errMsg'])) {
                    echo "<span style=\"color: red;\">" . $_SESSION['errMsg'] . "</span>";
                    unset($_SESSION['errMsg']);
                } ?>
            </div>

            <div class="form-input-row">
                <div class="form-input-left"><label for="name">Username or Email</label></div>
                <div class="form-input-right"><input name="nameORemail" type="text"></div>
            </div>

            <div class="clear"></div>
            <div class="field-submit">
                <input type="submit" name="rescue" value="Reset Password"
                       class="form-submit-button">
            </div>
        </form>
    </div>

    <?php
}?>



