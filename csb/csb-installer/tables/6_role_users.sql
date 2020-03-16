CREATE TABLE `role_users` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `role_id` int(10) unsigned NOT NULL,
              `user_id` int(10) unsigned NOT NULL,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `application_id` int(10) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `role_users_role_id_foreign` (`role_id`),
          KEY `role_users_user_id_foreign` (`user_id`),
          KEY `role_users_application_id_foreign` (`application_id`),
          CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=193103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;