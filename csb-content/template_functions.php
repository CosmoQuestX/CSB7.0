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
function loadHeader($page_title = "", $header_title = "")
{

    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers;

    require_once($THEME_DIR . "header.php");
}


function load3Col($menus="", $main="", $notes="", $template="") {
    global $THEME_URL, $THEME_DIR, $BASE_URL, $TEMPLATES_DIR;

    require_once($THEME_DIR . "page-3col-template.php");
}

/**
 * Outputs the base footer for completing the website output depending on the
 * selected theme
 *
 * @return void
 */
function loadFooter()
{
    global $THEME_URL, $THEME_DIR, $BASE_URL, $csb_headers, $page_title;

    require_once($THEME_DIR . "footer.php");
}


/**
 * This function is called for including the csb-specific headers when
 * loading the header file from the theme
 *
 * @return void
 */
function loadMeta()
{
    global $page_title, $BASE_URL, $THEME_URL;

    $csb_headers = "";

    // Load style sheet
    $csb_headers .=  "<link rel='stylesheet' type='text/css' href='" . $BASE_URL . "csb-content/csb.css'>\r\n";
    $csb_headers .=  "<link rel='stylesheet' type='text/css' href='" . $THEME_URL . "style.css'>\r\n";

    // Set title
    $csb_headers .= "<title>".$page_title."</title>\r\n";

    // Load libraries
    $csb_headers .=  "<script src='".$THEME_URL."js/jquery-3.4.1.slim.min.js'
            integrity='sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n'
            crossorigin='anonymous'></script>\n";
    $csb_headers .=  "<script src='".$THEME_URL."js/jquery.validate.min.js'
            crossorigin='anonymous'></script>\n";
    $csb_headers .=  "<script src='".$THEME_URL."js/popper.min.js'
            integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo'
            crossorigin='anonymous'></script>\n";
    $csb_headers .=  "<script src='".$THEME_URL."js/bootstrap.min.js'
            integrity='sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6'
            crossorigin='anonymous'></script>\n";
    $csb_headers .= "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";

    echo $csb_headers;
}

/**
 * Displays a greeting, logout, and profile button when logged in, and a login button
 * when not
 *
 * @return void
 */
function loadUser()
{
    global $BASE_URL, $ACC_URL, $user, $adminFlag, $IMAGES_URL;

    if ($user === FALSE) {         // NOT LOGGED IN
        if ($adminFlag === FALSE) {
            ?>

            <li class="nav-item">
                <a class="nav-link" href="<?PHP echo $ACC_URL; ?>register.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#loginModal" style="cursor:pointer;">Login</a>
            </li>

            <?php
        } else {
            echo "not logged in";
        }
    } else {                           // LOGGEDIN
        ?>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Hello, <?php echo $user['name'];
            if (empty($user['gravatar_url'])) {
                echo " <img src='" . $IMAGES_URL . "profile/Default_Avatar.png' alt='avatar' style='width:2em; height: 2em; margin: 0 auto;'>";
            } else {
                echo " <img src='".$user['gravatar_url']."' alt='avatar' style='width:2em; height: 2em; margin: 0 auto;'>";
            } ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo $BASE_URL;?>csb-accounts/profile.php">My Profile</a>
                <form action="<?php echo($BASE_URL); ?>csb-accounts/auth-login.php" method="get" id="form-logout">
                    <input type="submit" name="go" style="cursor:pointer;" class="dropdown-item" value="logout">
                </form>
            </div>
        </li>
        
        <?php
    }
}


/**
 * Creates navigation links depending on login state
 * 
 *
 * @return void
 */
function loadNavLinks()
{

    global $BASE_URL, $ACC_URL, $user, $adminFlag;

    if ($user == TRUE) {         // LOGGED IN
        ?>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL . 'apps.php?app=Bennu'; ?>">Bennu Mapper</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $BASE_URL; ?>">Other Mapper</a>
        </li>

        <?php
    }
}



function checkPermissions($allowed) {
    global $CQ_ROLES;

    $flag = FALSE;

    // Check if there is 1 or more fields to check and act accordingly
    if (is_string($allowed) && ($_SESSION['roles'] == $CQ_ROLES[$allowed])) {
        $flag = TRUE;
    }
    else
    {
        foreach($allowed as $level) {
            if ($_SESSION['roles'] == $CQ_ROLES[$level]) {
                $flag = TRUE;
            }
        }
    }

    return $flag;
}

/**
 * Output HTML for displaying a modal login box when pressing the "login"
 * button
 *
 * @return void
 */
function loadLoginBox()
{
    global $BASE_URL, $ACC_URL;
    ?>


    <div id="loginModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="<?php echo($BASE_URL . "csb-accounts/auth-login.php"); ?>" method="post"
                          id="form-login">

                        <input type="hidden" name="referringURL"
                               value="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
                        <input type="hidden" name="go" value="login">

                        <div class="error-msg"><?php if (isset($_SESSION['errmsg'])) {
                                $_SESSION['showmodal'] = TRUE;
                                echo "<span style=\"color: red;\">" . $_SESSION['errmsg'] . "</span>";
                            } ?></div>

                        <label for="username">Username</label>
                        <input id="username" name="name" type="text" class="form-control"
                               value="<?php if (isset($_COOKIE["name"])) {
                                   echo $_COOKIE["name"];
                               } ?>">

                        <label for="password">Password</label>
                        <input id="password" name="password" class="form-control" type="password">

                        <input type="checkbox" name="remember" id="remember-me"
                               class="mt-3" <?php if (isset($_COOKIE["member_login"])) {
                            echo " checked";
                        } ?>/>
                        <label for="remember-me" class="ml-1">Remember me</label>

                        <div class="text-center">
                            <input type="submit" name="login" class="btn btn-block btn-cq mt-3" value="Login">
                            <a href="<?PHP echo $ACC_URL; ?>register.php">Register</a> |
                            <a href="<?PHP echo $ACC_URL; ?>rescue.php">Forgot Password</a>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>

        // Autofocus on the login input
        $('#loginModal').on('shown.bs.modal', function () {
            $('#username').trigger('focus');
        })

        // Re-open login modal if login fails
        if ('<?php echo (isset($_SESSION['errmsg'])) ? $_SESSION['errmsg'] : ''; ?>' !== '') {
            $('#loginModal').removeClass('fade');
            $('#loginModal').modal('show');
        }

        // Add fade class back to modal if class was removed
        $('#loginModal').on('hide.bs.modal', function (e) {
            if (!$('#loginModal').hasClass('fade')) {
                $('#loginModal').addClass('fade');
            }
        })


    </script>


    <?php

}
        