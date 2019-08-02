<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:45 PM
 */
?>

<html>
<head>
    <?php loadMeta(); ?>
    <link href='http://fonts.googleapis.com/css?family=Roboto:regular,bold,bolditalic,italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo $THEME_URL;?>/style.css">

</head>
<body>

<!-----------------------------------------------------------------------
   Text for the Login Box
  ------------------------------------------------------>
<div id="alert-box" class="alert">

    <!-- Modal content -->
    <div class="alert-content">
        <span class="close">&times;</span>
        <div id="form-box">
            <form action="<?php echo($BASE_URL . "csb-admin/auth-login.php"); ?>" method="post" id="form-login">

                <input type="hidden" name="referringURL" value="<?php echo $referringURL; ?>">
                <input type="hidden" name="go" value="login">

                <div class="error-msg"></div>

                <div class="field-textbox">
                    <label for="login">Username</label>
                    <input name="name" type="text"
                           value="<?php if (isset($_COOKIE["name"])) {
                               echo $_COOKIE["name"];
                           } ?>"
                    >
                    <label for="password">Password</label>
                    <input name="password" type="password">
                </div>

                <div class="fields-checkbox">
                    <input type="checkbox" name="remember" id="remember"
                        <?php if (isset($_COOKIE["member_login"])) { ?> checked
                        <?php } ?> />
                    <label for="remember-me">Remember me</label>
                </div>

                <div class="field-submit">
                    <input type="submit" name="login" value="Login"
                           class="form-submit-button">
                </div>
            </form>

            <form action="<?php echo($BASE_URL); ?>csb-admin/auth-login.php" method="get" id="form-logout">
                <input type="submit" name="go" value="register">
            </form>
        </div>
    </div>

</div>

<div id="header" class="container">

    <!-----------------------------------------------------------------------
        Load Logo TODO Make logo uploadable
       ---------------------------------------------------------------------->
        <div id="logo" class="left">
            <a href="<?php echo $BASE_URL;?>">
                <img src="<?php echo $THEME_URL;?>/images/header-logo.png">
            </a>
        </div>

    <!-----------------------------------------------------------------------
        Load Title
       ---------------------------------------------------------------------->
        <div id="title" class="left">
            <h1> <?php echo $page_title;?></h1>
        </div>

    <!-----------------------------------------------------------------------
        Load User Area TODO ADD LOGIC TO LOGIN OR LOGOUT AS MAKES SENSE
      ---------------------------------------------------------------------->
        <div id="user" class="right">

            <?php loadUser(); ?>

        </div>




</div>

<div class="clear"></div>


