<?php

// TODO Major work required for HTML form, etc.

// Standard "How the hell did you get here?" Redirect to root directory
GLOBAL $loader, $ACC_URL, $db;
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}

if (isset($_GET['go'])) {
    if ($_GET['go'] == 'submitted') {
        ?>
<h1>Set up two-factor authentication</h1>
<p>Adversarium cras partiendo vulputate morbi viderer theophrastus decore nisi lectus. Convallis falli nominavi ullamcorper possit.
    Contentiones natoque ne mucius decore atomorum habeo pellentesque morbi. Graeco veritus ligula agam hinc inani sem noster electram.
    Quas dictas eius detraxit tellus numquam putent iudicabit.</p>
<?php
        // generate 2FA secret and display QR code
?>
<p>Scan the QR code with your two-factor authenticator app. Then enter the code it displays and click <b>confirm</b>. If it matches what we think
    it should be, we'll complete your registration.</p>
<form>
    Enter value: <input name="tfa_from_user">
    <input type="submit" name="confirm" value="Confirm"
           class="form-submit-button">
</form>
<?php
        } else {
            echo "Uh oh... something went wrong :(";
        }
    } // else {
// something should be here
?>
<?php
// }
?>

