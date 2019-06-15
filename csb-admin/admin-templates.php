<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/12/19
 * Time: 5:14 PM
 */

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


/**
 * @param $THEME_DIR
 * @param $user
 */
function loadHeader($BASE_URL, $THEME_DIR, $user) {
    require ($THEME_DIR . "/header.php");
    ?>
    <h3> Citizen Science Builder Admin Dashboard</h3>

    <!-- logout form -->
    <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
        <input type="submit" name="status" value="logout">
    </form>
    <?php
}

/**
 * @param $THEME_DIR
 */
function loadFooter($THEME_DIR) {
    require ($THEME_DIR . "/footer.php");
}