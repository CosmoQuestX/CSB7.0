<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 7/17/19
 * Time: 12:02 PM
 */

/**
 * Outputs the base headers for building the website output depending on the 
 * selected theme
 * 
 * @return void
 */
function loadHeader() {
    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers, $page_title;

    require_once($THEME_DIR . "header.php");
}

function openMain()
{
    echo "<div id='content'>";
}

function closeMain()
{
    echo "</div>";
}
/**
 * Outputs the base footer for completing the website output depending on the
 * selected theme
 * 
 * @return void
 */
function loadFooter() {
    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers, $page_title;

    require_once($THEME_DIR . "footer.php");
}


/**
 * This function is called for including the csb-specific headers when 
 * loading the header file from the theme
 * 
 * @return void
 */
function loadMeta() {

    $csb_headers  = "<link rel='stylesheet' type='text/css' href='". $BASE_URL ."csb-content/csb.css'>";

    echo $csb_headers;
}

/**
 * Displays a greeting and logout button when logged in and a login button
 * when not
 * 
 * @return void
 */
function loadUser() {
    global $BASE_URL, $ACC_URL, $user, $adminFlag;

    if ( $user === FALSE) {         // NOT LOGGED IN
        if ($adminFlag === FALSE) {
            ?>
            <button class="btn btn-cq" data-toggle="modal" data-target="#loginModal">Login</button>
            <button class="btn btn-secondary" onclick="location.href='<?PHP echo $ACC_URL;?>register.php';">Register</button>
            <?php
        }
        else {
            echo "not logged in";
        }
    }
    else {                           // LOGGED IN
        echo "Hello, " . $user['name'];
        ?>
        
        <form action="<?php echo($BASE_URL); ?>csb-accounts/auth-login.php" method="get" id="form-logout">
            <input type="submit" name="go" class="btn btn-cq float-right" value="logout">
        </form>
        <?php
    }
}

/**
 * Output HTML for displaying a modal login box when pressing the "login"
 * button 
 * 
 * @return void
 */
function loadLoginBox() {
    global $BASE_URL, $ACC_URL;
    ?>


    <div id="loginModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
            <form action="<?php echo($BASE_URL . "csb-accounts/auth-login.php"); ?>" method="post" id="form-login">

                <input type="hidden" name="referringURL" value="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
		        <input type="hidden" name="go" value="login">

                <div class="error-msg"><?php if(isset($_SESSION['errmsg'])) { $_SESSION['showmodal']=TRUE; echo "<span style=\"color: red;\">" . $_SESSION['errmsg'] ."</span>"; } ?></div>
            
                <label for="username">Username</label>
                <input id="username" name="name" type="text" class="form-control" value="<?php if (isset($_COOKIE["name"])) { echo $_COOKIE["name"]; } ?>">

                <label for="password">Password</label>
                <input id="password" name="password" class="form-control" type="password">

                <input type="checkbox" name="remember" id="remember-me" class="mt-3" <?php if (isset($_COOKIE["member_login"])) { echo " checked"; } ?>/>
                <label for="remember-me" class="ml-1">Remember me</label>

                <div class="text-center">
                    <input type="submit" name="login" class="btn btn-block btn-cq mt-3" value="Login">
                    <a href="<?PHP echo $ACC_URL;?>register.php">Dont have an account?</a>
                </div>
            
            </form>

        </div>
            
        </div>
    </div>
    </div>

    <script>
        
        // Autofocus on the login input
        $('#loginModal').on('shown.bs.modal', function ()
        {
            $('#username').trigger('focus');
        })

        // Re-open login modal if login fails
        if ('<?php echo $_SESSION['errmsg']; ?>' !== '')
        {
            $('#loginModal').removeClass('fade');
            $('#loginModal').modal('show');
        }
        
        // Add fade class back to modal if class was removed
        $('#loginModal').on('hide.bs.modal', function (e)
        {
            if ( !$('#loginModal').hasClass('fade') )
            {
                $('#loginModal').addClass('fade');
            }
        })
        

    </script>



    <?php

}
        