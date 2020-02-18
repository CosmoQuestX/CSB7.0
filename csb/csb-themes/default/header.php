<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:45 PM
 */
?>
<!DOCTYPE html>
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



<nav class="navbar navbar-expand-lg navbar-dark" style="background: none;">
    <div class="container px-0">


        <a class="" href="<?php echo $BASE_URL; ?>">
            <img width="280" src="<?php echo $THEME_URL; ?>/images/header-logo.png">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active" <?php if (strcmp(basename($_SERVER['PHP_SELF']), $BASE_URL)) { echo "active"; }?>>
                    <a class="nav-link" href="<?php echo $BASE_URL; ?>">Home</a>
                </li>
                <?php
                loadNavLinks();
                ?>
            </ul>
            <ul class="navbar-nav">
                

            <?php
                // Check if this is the registration page. If it is, don't show this
                if (strcmp(basename($_SERVER['PHP_SELF']), "register.php") != 0 &&
                    strcmp(substr($_SERVER['PHP_SELF'], -19), "installer/index.php") != 0 ) {
                    loadUser();
                }
            ?>


            </ul>
        </div>


    </div>
</nav>



