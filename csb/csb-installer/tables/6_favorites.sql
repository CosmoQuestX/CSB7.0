CREATE TABLE `favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `favorable_id` int(10) unsigned NOT NULL,
  `favorable_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_favorable_id_favorable_type_unique` (`user_id`,`favorable_id`,`favorable_type`),
  KEY `favorites_user_id_index` (`user_id`),
  KEY `favorites_favorable_id_index` (`favorable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;