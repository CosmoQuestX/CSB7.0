CREATE TABLE data_downloads (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
              `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    provider varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    success tinyint(1) NOT NULL,
    user_id int(10) unsigned DEFAULT NULL,
    link varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (id),
    KEY data_downloads_user_id_foreign (user_id),
    CONSTRAINT data_downloads_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;