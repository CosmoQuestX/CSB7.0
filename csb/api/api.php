<?PHP
/**
 * We need to recreate some api calls required to run the application
 * without having the necessary backend to do so. So, basically we need to pull
 * information out of thin air and hope for the best.
 * Reminder, this is not to run forever, so that's hacky code, at best.
 * Some problems need creative solutions - for example, it's probably not the 
 * best idea to rely on the presence of a cookie to determine a user.
 */


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

//define ("elog",true);
define ("elog",false);

// We need our basic configuration, so include the loader from the directory above
require '../csb-loader.php';

// Turn off logging, since that could accidentally output something when it shouldn't
ini_set("log_errors", 1);

// If setup correctly, we get the REQUEST_URI passed, which is something
// like /csb/api/one/two/three/four - something we can easily digest.
// There's some endpoints that are - more or less - static and some that
// reqire parsing the request string, so let's switch through those.
$operation=$_SERVER['REQUEST_URI'];
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
            die("Unknown operation");
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
     * My hacky solution is to urldecode/urlencode as needed, hoping I don't 
     * forget a place. 
     * Also, the api needs to know the user to query against the database. 
     * This doesn't get passed. For now, not knowing a different solution, 
     * I'll take the user from the login cookie and hope for the best.
     * TODO Create a table that stores information about finished tutorials
     */
    $raw = file_get_contents("php://input");
    $ret = json_decode(urldecode($raw),true);
    if ( elog ) { error_log(urldecode($raw)); }
    if ( log ) {
        // I assume that we are logged in since we are calling this. Otherwise
        // logging should be turned off! But better be safe than sorry. 
        if(isset($_COOKIE['PHPSESSID'])) {
            $fn=$_COOKIE['PHPSESSID']. ".txt";
            $fh = fopen($fn, "w+");
            fwrite($fh,"Input value\n");
            fwrite($fh,urldecode($raw));
            fclose($fh);
        }
    }
    // Variable definitions
    global $db_servername, $db_username, $db_password, $db_name;
    // Set up database connection     
    $db_conn=new DB($db_servername,$db_username,$db_password,$db_name);
    $db_conn->connectDB();
    
    // Get the "tutorials_completed" column for the logged in user from the database.
    $user_sql="SELECT tutorials_completed from users WHERE id = " . $_SESSION['user_id'] . "";
    if (debug) { print $user_sql . "<br \>\n"; }
    $user_res=$db_conn->runQuery($user_sql);
    if ($user_res !== false) {
        if (debug) { 
            print "User result array: <br />\n"; 
            print_r($user_res); 
            print "<br>\n-----<br />\n"; 
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
    $union=array_merge($ret['tutorials_complete'],$user['tutorials_completed']);
    $sleek=array_unique($union);
    
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
        fclose($fn);
    }
    
    // This should leave us with an array of unique tutorials_completed.
    $joined = implode(",",$sleek);
    // Let's compare the result to the value stored in the database 
    if( $joined != $user_res[0]['tutorials_completed'])  {
        //There appears to be a difference, so update the database
        $sql="UPDATE users set tutorials_completed=\"". $joined . "\" WHERE name = \"" . $user_name . "\"";
        if ( log ) {
            $fh=fopen($fn,"a");
            fwrite($fh,"\n----------------\n");
            fwrite($fh, "Changing the completed tutorials in the database.\n");
            fwrite($fh, $sql);
            fclose($fh);
        }
        $ret=$db_conn->runQuery($sql);
        if ($ret === false) {
            error_log("Error writing tutorials_completed to the database, user is " . $user_name . " and tutorials_completed is " . $joined);
        }
    }
    // Make sure to close the database before exiting!
    $db_conn->closeDB();
}

function getNextImageForUser($application_unsafe) {
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
        die("I don't know of the application you're speaking of: " . $application);
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
    
    $user=getUserFromCookie();
    
    
    if ($user !== false) {
        /*
         * This is one of the most infuriating queries in here. It makes
         * no sense to me. The source Laravel Query Builder code is:
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
        $sql = "SELECT images.id, file_location, sun_angle FROM images " .
            "LEFT JOIN image_users ON images.id = image_users.image_id " .
            "WHERE images.application_id = " . $app_id . " " .
            "AND image_users.user_id != " . $user . " " .
            "AND done=0 " .
            "GROUP BY images.id " .
            "LIMIT 0,50;";
        /* If we have lots of images, just get a maximum of 50.
         * I dropped the "order by priority" since we're picking a random image out
         * of 50, and ordering is a resource hog. Side effect: Since we're - in
         * contrast to Laravel - not using a cache of any sort the user will receive
         * a different imave every time he opens the application. This is different
         * than "old CSB" behaved. Nevertheless, hopefully it is adequate in
         * loading performance since it does not need to sort (ordering took 5.6*
         * the time to perform the same query - 78 ms vs. 437 ms).
         */
    }
    else {
        // The original comment was "IF the user isn't logged in, just pick one. 
        //The submitted data won't be saved". In my opinion, this should never get
        // called unless something is seriously wrong.
        $sql = "SELECT images.id, file_location, sun_angle from images " .
            "WHERE images.application_id = " . $app_id . " " .
            "AND done = 'false' " .
            "GROUP BY images.id " .
            "ORDER BY priority ASC " .
            "LIMIT 0,50;";
        // Get a maximum of 50, nevertheless.
    }
    
    if (debug) { print "Executing query " . $sql . " <br />\n"; }
    
    
    $result=$db_conn->runQuery($sql);
    if (debug) { 
        print "Result array: <br />\n"; 
        print_r($result); 
        print "-----<br />\n"; 
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
        $response=array('image'=>array('id'=>$results[0]['id'],
                        'file_location'=>$results[0]['file_location'],
                        'sun_angle'=>$results[0]['sun_angle']),
                        'user_data'=>array('needs_tutorial' => 
                                           'false','earned_badges'=>''
                                          )
                       );
    }
    else { 
        echo "Image not found"; 
        $db_conn->closeDB(); 
        exit(); 
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
    $application=filter_var($application_unsafe, FILTER_SANITIZE_FULL_SPECIAL_CHARS, 0);
    if (elog) { echo "Submitting data for application " . $application ."<br />\n"; }
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

    $user = $_SESSION['user_id'];
    if ($user === false) {
    // We didn't find a user, so let's be false
        error_log("No user found, for submitted image data, exiting");
        $response=array('error'=>'user not found');
        echo json_encode($response);
        $db_conn->closeDB();
        exit();
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
    //$app_sql = "SELECT * from applications";
    $app_res=$db_conn->runQuery($app_sql);
    
    // If we don't find the application, bug out.
    if ($app_res === false) {
        error_log("Error submitting image: Application ID not found for application " . $submit['application_name'] );
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
    
    $user=$_SESSION['user_id'];
    if ($user !== false) {
        if (elog) { error_log("Submitting scistarter information with user id " . $user); }
    
        // For now, just drop information into a text file
        $scistarter_fn="scistarter.txt";
        $scistarter_fh=fopen($scistarter_fn,"a");
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


?>