CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `storage_server_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storage_server_port` int(11) NOT NULL,
  `storage_server_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storage_server_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storage_server_is_sftp` tinyint(1) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `storage_server_is_passive` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;