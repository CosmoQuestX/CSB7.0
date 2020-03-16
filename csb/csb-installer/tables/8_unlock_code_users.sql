CREATE TABLE `unlock_code_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `unlock_code_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unlock_code_user_unlock_code_id_foreign` (`unlock_code_id`),
  KEY `unlock_code_user_user_id_foreign` (`user_id`),
  CONSTRAINT `unlock_code_user_unlock_code_id_foreign` FOREIGN KEY (`unlock_code_id`) REFERENCES `unlock_codes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unlock_code_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;