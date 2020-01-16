<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/11/19
 * Time: 9:46 PM
 */
?>

<div id="footer">
    <div class="container">
        Powered by CosmoQuest's Citizen Science Builder.
        This is a product of the Planetary Science Institute.
    </div>
</div>

</body>


<script src="<?php echo $BASE_URL; ?>/csb-content/js/csb.js"></script>

<?php

// Try to display the alert box when there is an error message when logging in through the modal
if (isset($_SESSION['showmodal']) && $_SESSION['showmodal'] == TRUE) {
    echo "<script>document.getElementById(\"alert-box\").style.display=\"block\";</script>";
    unset ($_SESSION['showmodal']);
}
// cleanup
if (isset($_SESSION['errmsg'])) {
    unset($_SESSION['errmsg']);

}
?>


</html>