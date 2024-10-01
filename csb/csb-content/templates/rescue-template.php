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
        <p>We've emailed you a link you can use to reset your password. If you entered what you thought was your username
           and don't receive an email, please try again by entering your email address just incase you misremembered
           your username. Still stuck? Sure you had an account? Ask for help over on Discord.</p>
        <?php
    } elseif ($_GET['go'] == 'success') {
        echo "Thanks! You should now be able to login.";

    } else {
        // Check if stored password = hash use password_verify($user['password'], $chkuser['password'])
        $query  = "SELECT * from password_resets WHERE email='". $_GET['go'] ."' ORDER BY created_at DESC LIMIT 1";
        $result = $db->runQuery($query)[0];
        if(password_verify($_GET['token'], $result['token'])) {
            $userEmail = filter_input(INPUT_GET, 'go', FILTER_SANITIZE_EMAIL);
            $userId = $db->getUserIdByEmail($userEmail);
            $user = $db->getUser(filter_var($userId, FILTER_SANITIZE_NUMBER_INT));
            ?>
            <h1>Please enter your password</h1>
            <form action="<?php echo($ACC_URL."auth-login.php"); ?>" method="post">
                <input type="hidden" name="go" value="passwordReset">
                <input type="hidden" name="email" value="<?php echo $_GET['go']; ?>">
                <input type="hidden" name="token" value="<?php echo $_GET['token'] ?>">

                <div class="error-msg"><?php if (isset($_SESSION['errMsg'])) {
                        echo "<span style=\"color: red;\">" . $_SESSION['errMsg'] . "</span>";
                        unset($_SESSION['errMsg']);
                    } ?>
                </div>

                <div class="clear"></div>
                <div class="form-input-row">
                    <div class="form-input-left"><label for="username">Username</label></div>
                    <div class="form-input-right"><input type="text" id="username" name="username"
                                                         value="<?php echo $user['name'] ?>"
                                                         class="form-control" ></div>
                </div>
                <div class="clear"></div>
                <div class="form-input-row">
                    <div class="form-input-left"><label for="password">Enter Password</label></div>
                    <div class="form-input-right"><input name="password" type="password" class="form-control"></div>
                </div>
                <div class="clear"></div>
                <div class="form-input-row">
                    <div class="form-input-left"><label for="confirm">Confirm Password</label></div>
                    <div class="form-input-right"><input name="confirm" type="password" class="form-control"></div>
                </div>
                <div class="clear"></div>
                <div class="field-submit">
                    <input type="submit" name="rescue" value="Rescue me!"
                           class="form-submit-button btn btn-cq mt-4 right">
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
                <div class="form-input-right"><input name="nameORemail" type="text" class="form-control"></div>
            </div>

            <div class="clear"></div>
            <div class="field-submit">
                <input type="submit" name="rescue" value="Reset Password"
                       class="form-submit-button btn btn-cq mt-4 right">
            </div>
        </form>
    </div>

    <?php
}?>



