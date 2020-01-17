<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */
?>

<div class="container mt-3">
    <div class="row">

        <!-- Left block --------------------------------------------------------------- -->
        <div id="app-left" class="col-md-3 p-4">
            <?php echo $menus; ?>
        </div>

        <!-- main block --------------------------------------------------------------- -->
        <div id="app-main" class="col-md-6 p-4">
            <?php echo $main; ?>
        </div>

        <!-- Right block -------------------------------------------------------------- -->
        <div id="app-right" class="col-md-3 p-4">
            <?php echo $notes; ?>
        </div>


    </div>
</div>