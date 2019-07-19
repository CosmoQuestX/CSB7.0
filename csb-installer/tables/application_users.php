<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 7/18/19
 * Time: 10:23 PM
 */

$structure = "CREATE TABLE `application_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `application_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_users_user_id_application_id_unique` (`user_id`,`application_id`),
  KEY `application_users_application_id_foreign` (`application_id`),
  CONSTRAINT `application_users_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `application_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";