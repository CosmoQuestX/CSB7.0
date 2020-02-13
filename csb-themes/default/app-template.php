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
            <?php $txt = $lang['app_page']['text-boxes']['app-left']; ?>
            <h2><?php echo $txt['title']; ?></h2>
            <p> <?php echo $txt['fact1-title']; ?><br/>
                <?php echo $txt['fact1-content']; ?></p>
            <p> <?php echo $txt['fact2-title']; ?><br/>
                <?php echo $txt['fact2-content']; ?></p>
            <p> <?php echo $txt['fact3-title']; ?><br/>
                <?php echo $txt['fact3-content']; ?></p>
            <p> <?php echo $txt['completed']; ?><br/>
                #####</p>
            <p><?php echo $txt['remaining']; ?><br/>
                #####</p>
            <p><?php echo $txt['dueDate']; ?><br/>
                <?php echo $txt['dueDateValue']; ?></p>

            <div id="app-examples">
                <h4><?php echo $txt['examples']; ?></h4>
                <?php
                $n = 0;

                foreach ($exampleSets as $exampleSet) {

                    if ($exampleSet['name'] == $defaultButton) $status = "";
                    else $status = ' hide';

                    echo "<div class='" . $exampleSet['name'] . $status . "''>";
                    foreach ($exampleSet['examples'] as $example) {
                        echo "<img class='example-image left' src='" . $example . "' alt='Example " . $exampleSet['name'] . " " . $n . "'>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- main block --------------------------------------------------------------- -->
        <div id="app-main" class="col-md-6 p-4">
            <div class="row">
                <div id="app-button-container" class="col-md-auto">
                    <?php

                    foreach ($buttons as $button) {
                        if ($button['name'] == $defaultButton) $status = "";
                        else $status = ' notSelected';

                        echo "<img class='app-button " . $status . "' src='" . $BASE_URL . "/csb-content/images/buttons/" . $button['img'] . "' alt='" . $button['name'] . "'><br/>";
                    }
                    ?>
                </div>

                <div id="app-canvas" class="col-md-auto">
                </div>

            </div>
        </div>

        <!-- Right block ---------------------------------------------------------------->
        <?php $txt = $lang['app_page']['text-boxes']['app-right']; ?>
        <div id="app-right" class="col-md-3 p-4">
            <h1><?php echo $txt['title']; ?></h1>
            <p><?php echo $txt['blurb']; ?></p>
            <p><?php echo $txt['footer']; ?></p>
            <input type="button" value="Discord"><input type="button" value="Twitch"><br/>
            <!-- <iframe src="https://titanembeds.com/embed/443490369443856384" height="245" width="350"></iframe> -->
        </div>
    </div>
</div>