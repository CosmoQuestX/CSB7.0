<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 7/19/19
 * Time: 10:10 AM
 */

$structure = "CREATE TABLE `roles` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `details` blob DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `roles_name_unique` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$inserts = array("INSERT INTO roles (id, name) VALUES (1, 'SITE_ADMIN')",
    "INSERT INTO roles (id, name) VALUES (2, 'SITE_NONE')",
    "INSERT INTO roles (id, name) VALUES (3, 'SITE_SCIENTIST')",
    "INSERT INTO roles (id, name) VALUES (4, 'SITE_MOD')");