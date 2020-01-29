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

<?php
/*
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
*/
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
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo $BASE_URL; ?>">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Community</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sample</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                

            <?php
                // Check if this is the registration page. If it is, don't show this
                if (strcmp(basename($_SERVER['PHP_SELF']), "register.php")) {
                    loadUser();
                }
            ?>


            </ul>
        </div>


    </div>
</nav>



