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

    // Users Table ------------------------------------------------------
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
              `classroom_user_id` int(11) NOT NULL DEFAULT '0',
              `reset_password` tinyint(1) NOT NULL DEFAULT '0',
              `public_profile` tinyint(1) NOT NULL DEFAULT '1',
              `wp_id` int(11) NOT NULL DEFAULT '1',
              `gravatar_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '". $BASE_URL ."csb-content/images/profile/Default_Avatar.png',
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

    // Roles Table ------------------------------------------------------
    $table = "roles";
    if (table_exists($conn, $table)) {
        die("Table " . $table . " already exists. Cancelling install.<br/>");
    }
    else {
        $structure = "CREATE TABLE `roles` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `details` blob DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `roles_name_unique` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        if (create_table($conn, $structure)) {
            echo "created table " . $table . "<br/>";
        } else {
            die("Couldn't create table ". $table . "<br/>");
        }
    }

    $sql = "INSERT INTO roles (id, name) VALUES (1, 'SITE_ADMIN')";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create admin role with id 1 <br/>");
    }
    $sql = "INSERT INTO roles (id, name) VALUES (2, 'SITE_NONE')";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create none role with id 2 <br/>");
    }
    $sql = "INSERT INTO roles (id, name) VALUES (3, 'SITE_SCIENTIST')";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create none role with id 3 <br/>");
    }
    $sql = "INSERT INTO roles (id, name) VALUES (4, 'SITE_MOD')";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create none role with id 4 <br/>");
    }
    

    // Roles - Users Join Table -----------------------------------------
    $table = "role_users";
    if (table_exists($conn, $table)) {
        die("Table " . $table . " already exists. Cancelling install.<br/>");
    }
    else {
        $structure = "CREATE TABLE `role_users` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `role_id` int(10) unsigned NOT NULL,
              `user_id` int(10) unsigned NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `application_id` int(10) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `role_users_role_id_foreign` (`role_id`),
          KEY `role_users_user_id_foreign` (`user_id`),
          KEY `role_users_application_id_foreign` (`application_id`),
          CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=193103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        if (create_table($conn, $structure)) {
            echo "created table " . $table . "<br/>";
        } else {
            die("Couldn't create table ". $table . "<br/>");
        }
    }


/* ----------------------------------------------------------------------
   Generate Admin = CodeHerder account
   ---------------------------------------------------------------------- */

    $username = "CodeHerder";
    $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, 12);
    $hashed   = password_hash($password, PASSWORD_DEFAULT);

    // Create the user with email from settings
    $sql = "INSERT INTO users (id, name, email, password) VALUES (1, '" . $username . "', '". $rescue_email . "', '" . $hashed . "');";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create admin user <br/>");
    }

    // Create their admin role
    $sql = "INSERT INTO role_users (role_id, user_id) VALUES (1, 1);";
    if (mysqli_query($conn, $sql) == FALSE) {
        die("Couldn't create admin role <br/>");
    }

    // Tell them their info
    echo "Your Admin username is: CodeHerder</br>";
    echo "Your password is: " . $password . "<br/>";


/* ----------------------------------------------------------------------
   End Session
   ---------------------------------------------------------------------- */

    mysqli_close($conn);