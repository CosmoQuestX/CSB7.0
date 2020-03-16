CREATE TABLE `shared_marks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `x` double(8,2) NOT NULL,
  `y` double(8,2) NOT NULL,
  `diameter` double(8,2) DEFAULT NULL,
  `confidence` double(8,2) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `application_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shared_marks_image_id_foreign` (`image_id`),
  CONSTRAINT `shared_marks_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;