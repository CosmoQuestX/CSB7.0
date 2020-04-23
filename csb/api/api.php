<?PHP
/**
 * We need to recreate some api calls required to run the application
 * without having the necessary backend to do so. So, basically we need to pull
 * information out of thin air and hope for the best.
 * Reminder, this is not to run forever, so that's hacky code, at best.
 * Some problems need creative solutions - for example, it's probably not the 
 * best idea to rely on the presence of a cookie to determine a user.
 */

session_start();

/** This is for development purposes and should probably be replaced by more
 * useful logging at some point. I used it mainly to make a sense of the calls
 * from the JS application. 
 * If "debug" is defined, the api renders output strings on the website.
 * If "log" is defined, some of the endpoint information is logged to a text file 
 *    with the session id in the name that is located in the api folder to help 
 *    debug endpoints that require input.
 * If "elog" is defined, the api returns strings to the server's error log.
 */

//define ("debug",true);
define ("debug",false);

//define ("log",true);
define ("log",false);

define ("elog",true);
//define ("elog",false);

// We need our basic configuration, so include the loader from the directory above
require '../csb-loader.php';

// Turn off error display, since that could accidentally output something 
// when it shouldn't - you can turn it on for debugging.
ini_set("display_errors", 0);

// If setup correctly, we get the REQUEST_URI passed, which is something
// like /csb/api/one/two/three/four - something we can easily digest.
// There's some endpoints that are - more or less - static and some that
// reqire parsing the request string, so let's switch through those.
$operation=$_SERVER['REQUEST_URI'];
$userdata=$_SESSION;

if (debug) { echo "Trying with request string " . $operation . "<br />\n"; }

// Let's start by reading how we were called
switch ($operation) {
    case "/csb/api/finish_tutorial":
        // Synchronizes finished tutorials
        if (debug) { echo "Synchronizing finished tutorials<br />\n"; }
        finish_tutorial();
        break;
    case "/csb/api/scistarter":
        // Do we need this? Otherwise we could get rid of this.
        if (debug) { echo "Posting scistarter information<br />\n"; }
        scistarter();
        break;
    case "/csb/api/ajax_login":
        // This should log in a user (using data entered in the js?)
        // I'm not sure whether this is ever called currently since we don't 
        // allow people to even see the mapping application when not logged in        
        if (debug) { echo "Attempting AJAX login<br />\n"; }
        ajax_login();
        break;
    case "/csb/api/passwordreset":
        // Just a static redirect to the password recovery page
        header("Location: /csb/csb-accounts/rescue.php");
        exit();
        break;
    default:
        // Split the REQUEST_URI into parts - some are dynamic - and then
        // redirect to the appropriate function with the dynamic part as
        // an argument
        $arg_arr=explode("/",$operation);
        if (debug) { print_r($arg_arr); print "<br />\n"; }
        if(in_array("byID",$arg_arr)){
            //  /csb/api/image/byID/<number>
            // We're passing the number as an argument. 
            getImageById($arg_arr[5]);
        }
        else if (in_array("next",$arg_arr)) {
            // /csb/api/image/<app>/next
            getNextImageForUser($arg_arr[4]);
        }
        else if (in_array("submit_data",$arg_arr)) {
            // /csb/api/image/<app>/submit_data
            submit_data($arg_arr[4]);
        }
        else {
            // If we arrived here, we should probably throw an error...
            if (debug) { echo "I still haven't found what I'm looking for..."; }
            die(json_encode(array('error'=>'Unknown operation')));
        }
}

function finish_tutorial() {
    /**
     * So, what I _think_ it is supposed to do is, it _somehow_ passes the 
     * application the tutorial was just completed, reads tutorials_completed 
     * for the user from the database, explodes it, adds the application to 
     * the array, implodes it again and submits this to the database.
     * It appears that the app is posting tutorials_completed from a cookies. 
     * Unfortunately, it uses commas to delimit this, which is a poor choice, 
     * since it is not allowed per RFC, and PHP also doesn't let you set it.
     * The ideal solution would be to have a join table (?) for user_id and 
     * each application to mark that the tutorial has been completed, but
     * 		a) that doesn't solve storing this as a cookie and
     *		b) would require a major rework 
     *      c) existing installations would need to transform all the users' 
     *         tutorial information in the database to the new table.
     * Currently, this is interestingly called twice, once on load of the app 
     * and once on finishing the tutorial of the active app. Maybe this is to
     * ensure that the cookie containing the tutorials_completed is fresh.
     * 
     * My hacky solution is to urldecode/urlencode as needed, hoping I don't 
     * forget a place. 
     * Also, the api needs to know the user to query against the database. 
     * This doesn't get passed. For now, not knowing a different solution, 
     * I'll take the user id from the session and hope for the best.
     * 
     * TODO Create a table that stores information about finished tutorials
     */
    global $userdata;
    
    $raw = file_get_contents("php://input");
    $ret = json_decode(urldecode($raw),true);
    if ( elog ) { error_log(urldecode($raw)); }
    if ( log ) {
        // I assume that we are logged in since we are calling this. Otherwise
        // logging should be turned off! But better be safe than sorry. 
        if(isset($_COOKIE['PHPSESSID'])) {
            $fn=dirname(ini_get("error_log")) . DIRECTORY_SEPARATOR . $_COOKIE['PHPSESSID']. ".txt";
            $fh = fopen($fn, "w+");
            fwrite($fh,"Input value\n");
            fwrite($fh,urldecode($raw));
            fclose($fh);
        }
    }

    // Avoid not having an array here.
    if($ret['tutorials_complete']=="") {
        $ret['tutorials_complete']=array("");
    }
    
    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    // Set up database connection     
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();

    if (isset($userdata['user_id'])) {
        $user = $userdata['user_id'];
    }
    else {
        $user = getUserIDFromSessionDB($db_conn);
    }
    
    // Get the "tutorials_completed" column for the logged in user from the database.
    $user_sql="SELECT tutorials_completed from users WHERE id = " . $user . "";
    if (debug) { print $user_sql . "<br \>\n"; }
    if (elog) { error_log(" Executing SQL: SELECT tutorials_complete from usere WHERE id = " . $user); }
    
    $user_res=$db_conn->runQuery($user_sql);
    if ($user_res !== false) {
        if (debug) { 
            print "User result array: <br />\n"; 
            print_r($user_res); 
            print "<br>\n-----<br />\n"; 
        }
        // If we have an empty result because he didn't complete any tutorials,
        // add a comma to make it an empty array.
        if($user_res[0]['tutorials_completed']=="") {
            $user_res[0]['tutorials_completed']=",";
        }
    }
    else { 
            $db_conn->closeDB(); 
            die("User not found");
    }
    
    /**
     * The value stored in the database is a comma separated list of applications. 
     * Since we need to compare them to what is sent via API, we need to split
     * the array up, join the arrays and filter out the duplicates.
    */
    $user['tutorials_completed']=explode(",",$user_res[0]['tutorials_completed']);

    // If either is not an array, don't even try to merge them. 
    if (is_array($ret['tutorials_complete']) && is_array($user['tutorials_completed'])) {
        $union=array_merge($ret['tutorials_complete'],$user['tutorials_completed']);
        $sleek=array_unique($union);
    }
    else if (is_array($ret['tutorials_complete'])) {
        /* If the user return isn't an array, the database stored value isn't either,
         * so we only need to check that one if not both are an array. This would be
         * the case if a user that hadn't completed a tutorial before has now completed
         * one
         */
        $sleek=$ret['tutorials_complete'];
    }
    else {
        $sleek=array("");
    }
        
        
    if ( log ) {
        $user_json=json_encode($user_res);
        $fh=fopen($fn,"a");
        fwrite($fh,"\n----------------\n");
        fwrite($fh,"Database result\n");
        fwrite($fh,$user_json);
        fwrite($fh,"\n----------------\n");
        fwrite($fh,"User array\n");
        fwrite($fh,json_encode($user));
        fwrite($fh,"\n----------------\n");
        fwrite($fh,"Union array\n");
        fwrite($fh,json_encode($union));
        fwrite($fh,"\n----------------\n");
        fwrite($fh,"Merged array\n");
        fwrite($fh,json_encode($sleek));
        fclose($fh);
    }
    
    // This should leave us with an array of unique tutorials_completed.
    $joined = implode(",",$sleek);
    // Let's compare the result to the value stored in the database 
    if( $joined != $user_res[0]['tutorials_completed'])  {
        //There appears to be a difference, so update the database
        $sql="UPDATE users set tutorials_completed=\"". $joined . "\" WHERE id = " . $userdata['user_id'] . "";
        if ( log ) {
            $fh=fopen($fn,"a");
            fwrite($fh,"\n----------------\n");
            fwrite($fh, "Changing the completed tutorials in the database.\n");
            fwrite($fh, $sql);
            fclose($fh);
        }
        $ret=$db_conn->runQuery($sql);
        if ($ret === false) {
            error_log("Error writing tutorials_completed to the database, user id is " . $userdata['user_id'] . " and tutorials_completed is " . $joined);
        }
        // Finally, update the cookie
        $timeout = time() + 60 * 24;
        setcookie('tutorials_complete', $joined, $timeout,"/");
    }
    // Send back something nice
    echo json_encode(array('tutorials_complete'=>$joined));
    // Make sure to close the database before exiting!
    $db_conn->closeDB();

}

function getNextImageForUser($application_unsafe) {
    global $userdata;
    $application=filter_var($application_unsafe, FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0);
    if (debug) { echo "Here's your next image for application " . $application . "<br />\n"; }

    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();
    
    // Get the active applications from the database:
    $vapp_sql="SELECT name from applications WHERE active = 1";
    $vapp_ret=$db_conn->runQuery($vapp_sql);
    foreach ($vapp_ret as $valid_app) {
        $valid_apps[]=$valid_app['name'];
    }

    // Match the called application with an active application from the db.
    if ( in_array($application,$valid_apps)) {
        $app_name=$application;
    }
    else {
        die(json_encode(array('error'=>'Unknown application ' . $application )));
    }
    
    // Get the single application_id from the database
    $app_sql= "SELECT id from applications where name = \"" . $app_name . "\" LIMIT 0,1";
    $app_res=$db_conn->runQuery($app_sql);
    
    if (debug) { 
        print "Application result array: <br />\n"; 
        print_r($app_res); 
        print "<br />-----<br />\n"; 
    }
    
    // If we don't find the application, bug out.
    if ($app_res === false) {
        if (debug) { "Application ID not found for application " . $app_name . " <br />\n"; }
        exit();
    }
    
    $app_id = $app_res[0]['id'];
    if (debug) { print "Fetching image for application ID " . $app_id . " <br />\n"; }
    
    if (isset($userdata['user_id'])) {
        $user=$userdata['user_id'];
    }
    else if (isset ($_COOKIE[ini_get('session.name')])) {
        $user = getUserIDFromSessionDB($db_conn);
    }
    
        /*
         * This is one of the most infuriating queries in here. 
         * The source Laravel Query Builder code is:
         * $query = Image::where('images.application_id', $application->id)
         * 		->select(['images.id', 'file_location', 'sun_angle'])
         * 		->where('done', '=', false)
         *		->leftJoin('image_users', function ($join) use ($user) {
         *			return $join->on('images.id', '=', 'image_users.image_id')->where('image_users.user_id', '=',
         *				$user->id);
         *		})
         *		->groupBy('images.id')
         *		->orderBy("priority", "asc")
         *		->havingRaw('count(image_users.id) = 0');
         * To me this translates to
         * $sql =
         * "SELECT images.id, file_location, sun_angle from images " .
         * "LEFT JOIN image_users on images.id = image_users.image_id " .
         * "WHERE images.application_id = " . $app_id . " " .
         * "AND image_users.user_id = " . $user . " " .
         * "AND done = 'false' " .
         * "GROUP BY images.id " .
         * "HAVING count(image_users.id) = 0 " .
         * "ORDER BY priority ASC ";
         * which does not perfectly make sense to me.
         * Instead, what makes sense is the following query, assuming
         * we want images that the user hasn't already marked. Or maybe
         * I'm wrong here, then please correct this.
         */
         /* 
          * So that's my failed attempt, let's try this Keeper Of Maps suggested
          * $sql = "SELECT images.id, file_location, sun_angle FROM images " .
          *  "LEFT JOIN image_users ON images.id = image_users.image_id " .
          *  "WHERE images.application_id = " . $app_id . " " .
          *  "AND image_users.user_id != " . $user . " " .
          *  "AND done = 0 " .
          *  "GROUP BY images.id " .
          *  "LIMIT 0,50;";
          */
    if(isset($user) && $user !== false) {
    $sql = "SELECT images.id, file_location, sun_angle from images " .
        "WHERE images.application_id = ". $app_id ." " .
        "AND images.done = 0 " .
        "AND images.id not in " .  
        "  (SELECT DISTINCT image_id from image_users" . 
        "   WHERE application_id = ". $app_id ." " . 
        "   AND user_id = " . $user .") " .
        "GROUP BY images.id " .
        "LIMIT 20;";
        /* If we have lots of images, just get a maximum of 20.
         * I dropped the "order by priority" since we're picking a random image out
         * of 20, and ordering is a resource hog. Side effect: Since we're - in
         * contrast to Laravel - not using a cache of any sort the user will receive
         * a different imave every time he opens the application. This is different
         * than "old CSB" behaved. 
         */
    }
    
    else {
        // The original comment was "IF the user isn't logged in, just pick one. 
        // The submitted data won't be saved". In my opinion, this should never get
        // called unless something is seriously wrong.
        //$sql = "SELECT images.id, file_location, sun_angle from images " .
        //    "WHERE images.application_id = " . $app_id . " " .
        //    "AND done = 'false' " .
        //    "GROUP BY images.id " .
        //    "ORDER BY priority ASC " .
        //    "LIMIT 20;";
        // Get a maximum of 20, nevertheless.
        // If we can't determine the user, maybe it is better not to just send out 
        // an image; better send an error and finish 
        if (elog) { error_log("Could not find a user so not sending out any image"); }
        echo json_encode(array('error'=>'out_of_images'));
        $db_conn->closeDB();
        exit();
    }
    
    if (debug) { print "Executing query " . $sql . " <br />\n"; }
    
    
    $result=$db_conn->runQuery($sql);
    if (debug) { 
        print "Result array: <br />\n"; 
        print_r($result); 
        print "\n<br />-----<br />\n"; 
    }
    $db_conn->closeDB();
    
    if ($result!==false) {
        // $count the images (that this user hasn't marked yet).
        $count = count($result);
        // Randomly select one of the images in the results array
        $image_no = rand(1,$count)-1;
        $response=array('image'=>array('id'=>$result[$image_no]['id'],
                        'file_location'=>$result[$image_no]['file_location'],
                        'sun_angle'=>$result[$image_no]['sun_angle']),
                        'user_data'=>array('needs_tutorial' => 'false',
                                           'earned_badges'=>''
                        )
                  );
    }
    else {
        // We don't have an image, so return an error that we don't have an image left
        $response=array('error'=>'out_of_images');
    }
    // Return the json-encoded result
    echo json_encode($response);
}

function getImageById($id_unsafe, $attach_marks=false) {
    // Don't wonder, in theory the endpoint should be able to attach marks, but
    // I don't think that was used in recent years. We might need to reactivate 
    // that at some point, though. 
    
    // Image ID is an integer, so sanitize accordingly
    $id = filter_var($id_unsafe,FILTER_SANITIZE_NUMBER_INT,0);
    if (debug) { echo "Here's the image with id " . $id . "<br />\n"; }
    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    // First, let's open a database connection
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();
    // We got an image id, which is unique, so there can only be one result.
    $sql = "SELECT * from images WHERE id = " .$id;
    $results=$db_conn->runQuery($sql);
    if ($results !== false) {
        if (debug) { print "<br />Image results: \n"; print_r($results); print "<br />\n"; }
        $response=array('image'=>array('id'             => $results[0]['id'],
                                        'file_location' => $results[0]['file_location'],
                                        'sun_angle'     => $results[0]['sun_angle']),
                        'user_data'=>array('needs_tutorial' => 'false',
                                           'earned_badges'  => ''
                                          )
                       );
    }
    else { 
        $response=array('error'=>'image_not_found');
    }
    $db_conn->closeDB();
    // Return the json-encoded image data
    echo json_encode($response);
}

/**
 * Submits marks for an image to the Database.
 * @param string $application
 */
function submit_data($application_unsafe) {
    global $userdata;
    
    $application=filter_var($application_unsafe, FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0);
    if (elog) { error_log("Submitting data for application " . $application ); }
    /*
     * We'll get an image that was marked and an array of all the
     * marks including the types to write into the database. Hopefully we
     * don't need to magically produce information for too many fields.
     */
    
    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    // First, let's open a database connection
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();


    if ($userdata['user_id'] === false || !isset($userdata['user_id'])) {
        // If no user id is set in the session, try to get it from the Database
        $user = getUserIDFromSessionDB($db_conn);
        if ($user = false) {
            // We didn't find a user, so let's be false
            error_log("No user found, for submitted image data, exiting");
            $response=array('error'=>'user not found');
            echo json_encode($response);
            $db_conn->closeDB();
            exit();
        }
    }
    else {
        $user = $userdata['user_id'];
    }
    
    $submit_raw=file_get_contents("php://input");
    $submit_raw_c=urldecode($submit_raw);
    $submit=json_decode($submit_raw_c,true);
    if (elog) { 
        error_log($submit_raw_c); 
    }
    if ( log ) {
        $fn=$_COOKIE['PHPSESSID']. "_s.txt";
        $fh=fopen($fn, "w+");
        fwrite($fh,"Input value\n");
        fwrite($fh,$submit_raw_c);
        fwrite($fh,"\n-----\nResolved input array:\n");
        fwrite($fh,print_r($submit));
        fclose($fh);
    }
    
    /* To submit a complete image entry to the database, following steps are required:
     * 1. Insert a row into image_users with user_id, image_id, application_id and submit_time.
     * 2. Insert the marks into the marks table
     * 3. Probably, we need to return something?
     */
    // First, get the application_id from the database
    $app_sql= "SELECT id from applications where name = \"" . $submit['application_name'] . "\"";
    $app_res=$db_conn->runQuery($app_sql);
    
    // If we can't find the application, bug out.
    if ($app_res === false) {
        error_log("Error submitting image: Application ID not found for application " . $submit['application_name'] );
        echo json_encode(array('error'=>'application_not_found'));
        $db_conn->closeDB();
        exit();
    }
    $app_id = $app_res[0]['id'];
    
    // Now that we have the two resolvables, enter into the image_user table
    $iiu_sql="INSERT INTO image_users (user_id, image_id, application_id, submit_time) " .
        "VALUES (?,?,?,?)";
    $iiu_params=array($user,$submit['image_id'],$app_id, str_replace(".","",$submit['submit_time']));
    if(elog) { error_log("Executing SQL: INSERT INTO image_users (user_id, image_id, application_id, submit_time) VALUES (".$user.",".$submit['image_id'] .",".$app_id .",".str_replace(".","",$submit['submit_time']).")"); }
    $iiu_res=$db_conn->insert($iiu_sql,"iiii",$iiu_params);
    if ($iiu_res === false) {
        // If we couldn't insert, bug out with a server error
        error_log("Could not write into image_users on submit_data : " . mysqli_errno() . ": " . mysqli_error());
        header(http_response_code(500));
        $db_conn->closeDB();
        exit();
    }
    // We need image_user.id to save the marks
    $iui_sql="SELECT id FROM image_users WHERE user_id = " . $user .
    " AND image_id = " . $submit['image_id'] . " and application_id = " . $app_id . " ORDER BY id DESC LIMIT 0,1";
    if(elog) { error_log("Executing SQL: SELECT id FROM image_users WHERE user_id = ".$user." AND image_id = ".$submit['image_id'] ." AND application_id = ".$app_id ." ORDER BY id LIMIT 0,1;"); }
    $iui_res=$db_conn->runQuery($iui_sql);
    if ($iui_res===false) {
        error_log("Could not get image_user_id!");
        // We should probably return a sensible error message here, 
        exit();
    }
    $iui=$iui_res[0]['id'];
    
    if (isset($submit['marks'])) {
        $marks=json_decode($submit['marks'],true);
        // Push each mark into the database in a separate query.
        // It would probably better to push them all at once since that should 
        // be more effective in terms of database time, but currently I'm 
        // happy it actually saves something.
        foreach ($marks as $markData) {
            $mark_sql="INSERT INTO marks (user_id, image_id, application_id, ".
                "image_user_id, x,y,diameter,submit_time, type) VALUES " .
                "(?,?,?,?,?,?,?,?,?)";
            $mark_params=array($user,$submit['image_id'],$app_id,$iui,$markData['x'],$markData['y'],$markData['diameter'],str_replace(".","",$submit['submit_time']),$markData['type']);
            if(elog) { error_log("Executing SQL: INSERT INTO marks (user_id, image_id, application_id, image_user_id, x,y,diameter,submit_time, type) VALUES (".$user.",".$submit['image_id'] .",".$app_id .",".$iui .",".$markData['x'].",".$markData['y'] .",".$markData['diameter'] .",".str_replace(".","",$submit['submit_time']).",".$markData['type'] .")"); }
            $mark_res=$db_conn->insert($mark_sql,"iiiidddis",$mark_params);
            if ($mark_res === false) {
                // If we can't write the marks, log the error!
                error_log("Could not write into marks on submit_data : " . $db_conn->getError());
            }
        }
    }
    
    // Now that we have the image marks saved, check whether the image has more than 15 views
    $done_sql="SELECT count(image_id) as total from image_users WHERE image_id = " . $submit['image_id'];
    $done_res=$db_conn->runQuery($done_sql);
    if(elog) { error_log("Executing SQL: SELECT count(image_id) as total from image_users WHERE image_id = " . $submit['image_id']); }
    if ($done_res !== false) {
        // If we have more than 15 done images, mark the image done
        if ($done_res[0]['total']>15) {
            $fin_sql="UPDATE images SET done=? WHERE id=?";
            $fin_params=array(1,$submit['image_id']);
            $fin_res=$db_conn->update($fin_sql,"ii",$fin_params);
            if(elog) { error_log("Executing SQL: UPDATE images SET done=1 WHERE id=". $submit['image_id'] ); }
            
            if ($fin_res === false) {
                // Log on error
                error_log("Could not mark image ". $submit['image_id'] ." done: " .$db_conn->getError());
            }
        }
    }
    
    // On sending an image, update the timestamp for the user session:
    updateSessionTimestamp($db_conn, $user);
    
    //Finally, return something nice.
    $returndata = array('user_data'=>array('needs_tutorial' => 'false','earned_badges'=>''));
    echo json_encode($returndata);
    
    $db_conn->closeDB();
    
}

/**
 * This function is supposed to queue up sending scistarter information via 
 * webservice. Currently it just stores the data into a text file. In future, 
 * there should be a backend service / cron job / something that regularly
 * collects the information from the text file and sends it to scistarter.
 */
function scistarter() {
    global $userdata;
    
    if ( debug) { echo "Submitting data to scistarter<br />\n"; }
    
    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    // First, let's open a database connection
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();
    
    // First, get the post data
    
    $submit_raw=file_get_contents("php://input");
    $submit_raw_c=urldecode($submit_raw);
    if (elog) { error_log($submit_raw_c); }
    $submit=json_decode($submit_raw_c,true);
    if ( log ) {
        $fn=$_COOKIE['PHPSESSID']. "_st.txt";
        $fh=fopen($fn, "w+");
        fwrite($fh,"Input value\n");
        fwrite($fh,$submit_raw_c);
        fwrite($fh,"\n-----\nResolved input array:\n");
        fwrite($fh,print_r($submit));
        fclose($fh);
    }
    
    $user=$userdata['user_id'];
    if ($user !== false) {
        if (elog) { error_log("Submitting scistarter information with user id " . $user); }
    
        // For now, just drop information into a text file
        $scistarter_fn = "scistarter.txt";
        $scistarter_fh = fopen($scistarter_fn,"a");
        $save=array('application_name'=>$submit['app'],'user_id'=>$user,'count'=>1);
        fwrite($scistarter_fh,json_encode($save));
        fclose($scistarter_fh);
    
        //Return something nice.
        $returndata = array('user_data'=>array('needs_tutorial' => 'false','earned_badges'=>''));
        echo json_encode($returndata);
    }
    
    $db_conn->closeDB();
    
}

/**
 * This function exists as an API endpoint within the JS app but is currently not used.
 */
function ajax_login() {
    // This is most likely currently unnecessary, since the apps are only
    // visible when you're logged in to CSB. If it gets called. but out.
    if (debug) { echo "Attempted to log in via AJAX<br />\n"; }
    error_log("AJAX login attempted but currently not supported");
    header(http_response_code(500));
    exit();
}

function getUserIDFromSessionDB($db_conn) {
    // Using the session parametes to get the user ID seems not to work too well
    // so we'll try storing the session information in the
    
    if (elog) { error_log("Attempting to fetch user_id from session - " . ini_get('session.name') . "is " . $_COOKIE[ini_get('session.name')]);  }
    $session_query = "SELECT user_id from sessions WHERE id = ?";
    $session_params = array($_COOKIE[ini_get('session.name')]);
    $session_result = $db_conn->runQueryWhere($session_query, "s", $session_params);
    if ($session_result !== false) {
        $ret = $session_result[0]['user_id'];
    }
    else {
        // Second try - get user id from the session table using the ip and user agent
        $session_query="SELECT user_id from sessions where ip_address = ? AND user_agent = ?";
        $session_params = array($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']);
        $session_result = $db_conn->runQueryWhere($session_query, "ss", $session_params);
        if ($session_result !== false) {
            $ret = $session_result[0]['user_id'];
        }
        else {
            error_log("Error reading user id from session, query was:  SELECT user_id from sessions where ip_address = ". $_SERVER['REMOTE_ADDR'] ." AND user_agent = ". $_SERVER['HTTP_USER_AGENT'] );
            $ret = false;
        }
    }
    return $ret;
}

function updateSessionTimestamp($db_conn, $user_id) {
    $timestamp=time();
    $session_query = "UPDATE sessions set last_activity = ? WHERE user_id = ? AND user_agent = ?";
    $session_params=array($timestamp,$user_id,$_SERVER['HTTP_USER_AGENT']);
    $session_res=$db_conn->update($session_query,"iis",$session_params);

    if ($session_res === false) {
        error_log("Error executing SQL: UPDATE session set last_activity = " . $timestamp . " WHERE user_id = " . $user_id . " AND user_agent = '". $_SERVER['HTTP_USER_AGENT'] . "'");
    }
    if ( elog ) { error_log("Result from updating the user session: ". $session_res); }
}
?>