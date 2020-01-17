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
</head>
<body>

<!-----------------------------------------------------------------------
   Text for the Login Box
  ------------------------------------------------------>
<?php
loadLoginBox();
?>

<div id="header" class="container d-flex justify-content-between align-items-center">

    <!-----------------------------------------------------------------------
        Load Logo TODO Make logo uploadable
       ---------------------------------------------------------------------->
    <div id="logo" class="left">
        <a href="<?php echo $BASE_URL; ?>">
            <img src="<?php echo $THEME_URL; ?>/images/header-logo.png">
        </a>
    </div>

    <!-----------------------------------------------------------------------
        Load Title
       ---------------------------------------------------------------------->
    <div id="title">
        <h1> <?php echo $header_title; ?></h1>
    </div>

    <!-----------------------------------------------------------------------
        Load User Area TODO ADD LOGIC TO LOGIN OR LOGOUT AS MAKES SENSE
      ---------------------------------------------------------------------->
    <div id="user">
        <?php
        // Check if this is the registration page. If it is, don't show this
        if (strcmp(basename($_SERVER['PHP_SELF']), "register.php")) {
            loadUser();
        }
        ?>

    </div>

</div>

<div class="clear"></div>


