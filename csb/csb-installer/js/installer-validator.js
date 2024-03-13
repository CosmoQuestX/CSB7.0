"use strict";

const install = new Validation("installation");

install.requireText("site_name", 0, 999, [], []);
install.requireText("base_dir", 0, 999, [], []);
install.requireText("base_url", 0, 999, [], []);
install.requireEmail("rescue_email", 4, 999, [], []);
install.requireText("db_servername", 0, 999, [], []);
install.requireText("db_port", 0, 999, [], []);
install.requireText("db_username", 0, 999, [], []);
install.requireText("db_password", 0, 999, [], []);
install.requireText("db_name", 0, 999, [], []);
