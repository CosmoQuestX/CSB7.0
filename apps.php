<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 1:49 PM
 */
/* ----------------------------------------------------------------------
   Get the settings and check if the person is logged in
   ---------------------------------------------------------------------- */

require_once("./csb-loader.php");
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name);

global $user;
$user = isLoggedIn($db);

if (!$user) {
    header("Location: $BASE_URL");
    exit();
}

/* ----------------------------------------------------------------------
   Check if app query parameter was provided and points to an app
   ---------------------------------------------------------------------- */
   // TODO if projects are made to be dynamic as opposed to directories, this will need refactored
if (!isset($_GET) || !isset($_GET['app']) || !is_dir(realpath($BASE_DIR . 'csb-apps/' . $_GET['app']))) {
    // TODO this could probably redirect to a 404 page
    header("Location: $BASE_URL");
    exit();
}

$app = $_GET['app'];

/** Get the setup files for the app dynamically */
require_once($BASE_DIR . "/csb-apps//" . $app .  "/template.php");
$lang = $BASE_DIR . "csb-apps//" . $app . "/lang/en.json";

$lang = file_get_contents($lang);
$lang = json_decode($lang, true);

/* ----------------------------------------------------------------------
   Load the view
   ---------------------------------------------------------------------- */
global $page_title, $header_title, $SITE_TITLE;

require_once($BASE_DIR . "/csb-content/template_functions.php");

loadHeader($page_title);
require_once($THEME_DIR . "/app-template.php");
?>
<script src="/csb-content/js/network.js"></script>
<script src="/csb-content/js/tools/Tool.js"></script>
<script src="/csb-content/js/tools/CircleTool.js"></script>
<script src="/csb-content/js/tools/DarkenScreenTool.js"></script>
<script src="/csb-content/js/tools/EraserTool.js"></script>
<script src="/csb-content/js/tools/FeatureTool.js"></script>
<script src="/csb-content/js/tools/LinearTool.js"></script>
<script src="/csb-content/js/tools/MarkerTool.js"></script>
<script src="/csb-content/js/tools/PaintingTool.js"></script>
<script src="/csb-content/js/tools/ShowHideTool.js"></script>
<script src="/csb-content/js/tools/ZoomWindowTool.js"></script>

<script src="/csb-content/js/Marks/Blanket.js"></script>
<script src="/csb-content/js/Marks/CheckMark.js"></script>
<script src="/csb-content/js/Marks/Crater.js"></script>
<script src="/csb-content/js/Marks/Feature.js"></script>
<script src="/csb-content/js/Marks/LinearFeature.js"></script>
<script src="/csb-content/js/Marks/PaintMark.js"></script>
<script src="/csb-content/js/Marks/Rock.js"></script>
<script src="/csb-content/js/Marks/Segment.js"></script>
<script src="/csb-content/js/Marks/Transient.js"></script>
<script src="/csb-content/js/Marks/XMark.js"></script>
<script src="/csb-content/js/Marks/Mark.js"></script>


<script src="/csb-content/js/AppInterface.js"></script>
<script src="/csb-content/js/AppImage.js"></script>
<script src="/csb-content/js/Tutorial.js"></script>
<script src="/csb-content/js/TutorialStep.js"></script>
<script src="/csb-content/js/Application.js"></script>
<script src="/csb-content/js/CsbApp.js"></script>
<script src="/csb-content/js/applications/BennuMappers.js"></script>
<script src="/csb-content/js/applications/MarsMappers.js"></script>
<script src="/csb-content/js/applications/MercuryMappers.js"></script>
<script src="/csb-content/js/applications/MoonMappers.js"></script>
<script type="text/javascript">
    const urlParams = new URLSearchParams(window.location.search);
    const launchButton = document.getElementById('app-launcher');

    if (launchButton && urlParams.has('app')) {
        launchButton.addEventListener("click", () => {
            // show the mapping app
            let cqMappingToolDiv = document.getElementById('cq-mapping-tool');
            cqMappingToolDiv.style = '';

            // dynamically determine which mapping app to load
            let csbApp = new CsbApp('project_canvas', true);
            const mapper = urlParams.get('app').toLowerCase() + '_mappers';

            // setup and start the mapping app
            csbApp.initialize([], true);
            csbApp.startApp(mapper);
        });
    }
</script>

<?php
loadFooter();