![CI](https://github.com/CosmoQuestX/CSB7.0/workflows/CI/badge.svg) [![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-v2.0%20adopted-ff69b4.svg)](code_of_conduct.md)

# CSB7.0
An open source implementation of CosmoQuest's Citizen Science Builder software.

## About
This is an open source image annotation platform suitable for citizen science, complete with native machine learning tools that can be trained through contributed data.

## License
> Copyright © 2012-2020 CosmoQuest X Team is led by Pamela Gay with the Development team and maintained through community collaboration. **All rights reserved.**





> Images, videos and other media belong to their respective owners.

> Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

> http://www.apache.org/licenses/LICENSE-2.0

> Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.


## Construction and Installation

To get a production build of the software, go the [latest `master` CI run](https://github.com/CosmoQuestX/CSB7.0/actions?query=branch%3Amaster) and download the `csb-build` artifact.

### Step 1: Setup the Server

#### 1.1 Setup LAMP Server

Setup an Apache 2 / MySQL 8 / PHP 7 environment. If we don't have specific instructions you need below, look for
instructions for Wordpress. Our setup should be the same.

* OSX
    * Enable Apache `apachectl start`
    * In `/etc/apache2/httpd.conf`
        * Uncomment `LoadModule` statements for php7 and mod_rewrite _Use Legacy Password Encryption_
        * Set `AllowOverride All`
    * Restart Apache `sudo apachectl restart`
    * [Install MySQL](https://dev.mysql.com/downloads/mysql)
    * Add mysql to your `.bash_profile` by adding `export PATH="/usr/local/mysql/bin:$PATH"`
- Ubuntu: find the Digital Ocean Tutorial for your version of Ubuntu
- Windows: _to be determined_

If you don't have a LAMP (or Win AMP) setup, find instructions to install
Wordpress. This software requires the same kind of server configuration!
For ubuntu, the Digital Ocean tutorials are among the best.


#### 1.2 Add SASS support
To install SASS, follow instructions on https://sass-lang.com/install. Once things
are installed, you'll need to compile your sass files into css files whenever
the sass is edited.

To watch and compile sass on the command line run:
```shell
sass --watch csb-themes/default/sass/style.scss:csb-themes/default/style.css
```

PHPstorm can watch and compile sass (see https://www.jetbrains.com/help/phpstorm/transpiling-sass-less-and-scss-to-css.html).

Instructions on compiling SASS in ubuntu are here: https://webdesign.tutsplus.com/tutorials/watch-and-compile-sass-in-five-quick-steps--cms-28275

#### 1.3 If you can, add a security certificate
You should always use a certificate. If you don't have one, try using the free
[Let's Encrypt: CertBot](https://letsencrypt.org/getting-started/).

#### 1.4 Going to email folks? Add PEAR
macOS Mojave use https://tobschall.de/2018/08/07/pear-on-mojave/
General installation use https://pear.php.net/manual/en/installation.getting.php

### Step 2: Launch the installer
Go to http://yourhost/csb-installer/ in your browser.
  This will let you configure your installation and then install databases and setup an admin user using the settings in csb-settings.php

### Optional - Using Docker

#### 1. Install Docker Desktop for your platform
Download and install the corresponding docker desktop version for your OS from [Docker](https://hub.docker.com/search?q=&type=edition&offering=community)

#### 2. Build and Start CSB
For Windows - open either Command Console or Powershell\
For Mac or Linux - open your command shell

Execute the following command from the CSB directory:
```docker-compose up```

To stop docker and close the app, press Ctrl+C

#### 3. Launch the installer
Go to http://localhost:8080/csb/csb-installer/ in your browser. 
  To use the docker MariaDB, use ```db``` as your database hostname
  Default docker username, password, and database are all ```csb```