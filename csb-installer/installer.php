<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:40 PM
 */

require_once("../csb-settings.php");
require_once("installer-functions.php");

echo "You are running the Citizen Science Builder installer <br>";


/* ----------------------------------------------------------------------
   Check if Database Exists
   ---------------------------------------------------------------------- */

    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection to Database Unsuccessful. Did you create the database" . $db_name . "?");
    }
    else {
        echo "Connected to database: " . $db_name . "<br/>";
    }

/* ----------------------------------------------------------------------
   Check if Tables Exists, throw error if it does, otherwise setup
   ---------------------------------------------------------------------- */

    // Users Table first
    $table = "users";
    if (table_exists($conn, $table)) {
        die("Table " . $table . " already exists. Cancelling install.<br/>");
    }
    else {
        $structure = "CREATE TABLE `users` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `forum_id` int(10) unsigned DEFAULT NULL,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `details` text COLLATE utf8_unicode_ci,
              `finished_tutorial` tinyint(1) NOT NULL DEFAULT '0',
              `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
              `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `classroom_user_id` int(11) NOT NULL,
              `reset_password` tinyint(1) NOT NULL,
              `public_profile` tinyint(1) NOT NULL DEFAULT '1',
              `wp_id` int(11) NOT NULL,
              `gravatar_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `first_name` text COLLATE utf8_unicode_ci,
              `last_name` text COLLATE utf8_unicode_ci,
              `level` int(11) NOT NULL DEFAULT '0',
              `tutorials_completed` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
              `scistarter_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `scistarter_id` int(11) DEFAULT NULL,
              `scistarter_profile_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `facebook_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `twitter_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `users_name_unique` (`name`),
              KEY `emails_on_users` (`email`),
              KEY `users_email_index` (`email`),
              KEY `users_remember_token_index` (`remember_token`)
        ) ENGINE=InnoDB AUTO_INCREMENT=101591 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        if (create_table($conn, $structure)) {
            echo "created table " . $table . "<br/>";
        } else {
            die("Couldn't create table ". $table . "<br/>");
        }
    }



