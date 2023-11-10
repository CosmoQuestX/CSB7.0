<?php
//require_once('../csb-loader.php');
//$defaultAvatar = urlencode($BASE_URL . "csb-content/images/profile/Default_Avatar.png");
//print($defaultAvatar);
//https%3A%2F%2Fcsb.trfa.xyz%2Fcsb%2Fcsb-content%2Fimages%2Fprofile%2FDefault_Avatar.png

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar(string $email, string $s = "80", string $d = 'identicon', string $r = 'g', bool $img = false, array $atts = array()): string
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
