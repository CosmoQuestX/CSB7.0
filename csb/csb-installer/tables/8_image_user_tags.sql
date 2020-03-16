CREATE TABLE `image_user_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `image_user_id` int(10) unsigned NOT NULL,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `validated_bin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `image_user_tags_tag_id_image_user_id_unique` (`tag_id`,`image_user_id`),
  KEY `image_user_tags_image_user_id_foreign` (`image_user_id`),
  CONSTRAINT `image_user_tags_image_user_id_foreign` FOREIGN KEY (`image_user_id`) REFERENCES `image_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `image_user_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;