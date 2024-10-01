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

require_once "csb-loader.php";
require_once($DB_class);
require_once($BASE_DIR . "csb-accounts/auth.php");

/* ----------------------------------------------------------------------
   Is the person logged in?
   ---------------------------------------------------------------------- */

$db = new DB($db_servername, $db_username, $db_password, $db_name, $db_port);

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
if (!isset($_GET) || !isset($_GET['app']) || !is_dir(realpath($BASE_DIR . 'csb-apps/' . filter_input(INPUT_GET, 'app', FILTER_SANITIZE_FULL_SPECIAL_CHARS)))) {
    header("Location: " . $BASE_URL . "error/error.php?error=404");
    exit();
}

$app = filter_input(INPUT_GET, 'app', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

/** Get the setup files for the app dynamically */
require_once($BASE_DIR . "/csb-apps/" . $app .  "/template.php");

$lang = $BASE_DIR . "csb-apps/" . $app . "/lang/en.json";

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

<!-- Common libraries -->
<script src="/csb/csb-content/js/network.js"></script>

<!-- CSB tool types  -->
<script src="/csb/csb-content/js/tools/Tool.js"></script>
<script src="/csb/csb-content/js/tools/CircleTool.js"></script>
<script src="/csb/csb-content/js/tools/DarkenScreenTool.js"></script>
<script src="/csb/csb-content/js/tools/EraserTool.js"></script>
<script src="/csb/csb-content/js/tools/FeatureTool.js"></script>
<script src="/csb/csb-content/js/tools/LinearTool.js"></script>
<script src="/csb/csb-content/js/tools/MarkerTool.js"></script>
<script src="/csb/csb-content/js/tools/PaintingTool.js"></script>
<script src="/csb/csb-content/js/tools/ShowHideTool.js"></script>
<script src="/csb/csb-content/js/tools/ZoomWindowTool.js"></script>

<!-- CSB mark types -->
<script src="/csb/csb-content/js/Marks/Blanket.js"></script>
<script src="/csb/csb-content/js/Marks/CheckMark.js"></script>
<script src="/csb/csb-content/js/Marks/Crater.js"></script>
<script src="/csb/csb-content/js/Marks/Feature.js"></script>
<script src="/csb/csb-content/js/Marks/LinearFeature.js"></script>
<script src="/csb/csb-content/js/Marks/PaintMark.js"></script>
<script src="/csb/csb-content/js/Marks/Rock.js"></script>
<script src="/csb/csb-content/js/Marks/Segment.js"></script>
<script src="/csb/csb-content/js/Marks/Transient.js"></script>
<script src="/csb/csb-content/js/Marks/XMark.js"></script>
<script src="/csb/csb-content/js/Marks/Mark.js"></script>

<!-- CSB core libraries -->
<script src="/csb/csb-content/js/AppInterface.js"></script>
<script src="/csb/csb-content/js/AppImage.js"></script>
<script src="/csb/csb-content/js/Tutorial.js"></script>
<script src="/csb/csb-content/js/TutorialStep.js"></script>
<script src="/csb/csb-content/js/Application.js"></script>
<script src="/csb/csb-content/js/CsbApp.js"></script>

<!-- Application definitions -->
<script src="/csb/csb-apps/deprecated/bennu_mappers/js/bennu_mappers.js"></script>
<script src="/csb/csb-apps/deprecated/moon_mappers/js/moon_mappers.js"></script>
<script src="/csb/csb-apps/deprecated/mars_mappers/js/mars_mappers.js"></script>
<script src="/csb/csb-apps/deprecated/mercury_mappers/js/mercury_mappers.js"></script>

<!-- Following is the code to launch -->
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
            const mapper = urlParams.get('app');

            // setup and start the mapping app
            csbApp.initialize([], true);
            csbApp.startApp(mapper);
        });
    }
</script>

<?php
loadFooter();
