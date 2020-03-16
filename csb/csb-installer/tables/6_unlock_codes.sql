CREATE TABLE `unlock_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expiration_date` datetime NOT NULL,
  `badge_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unlock_codes_badge_id_foreign` (`badge_id`),
  CONSTRAINT `unlock_codes_badge_id_foreign` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;