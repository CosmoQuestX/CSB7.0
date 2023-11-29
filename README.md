# CSB7.0

![CI](https://github.com/CosmoQuestX/CSB7.0/workflows/CI/badge.svg) [![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-v2.0%20adopted-ff69b4.svg)](code_of_conduct.md)

An opensource implementation of CosmoQuest's Citizen Science Builder software.

<details open>
<summary>Table of Contents</summary>

---------------------------------------------

- [About](#about)
- [License](#license)
- [Compiling and Installing](#compiling-and-installing)
  - [Minimum PHP requirements](#minimum-php-requirements)
  - [Add SASS support](#add-sass-support)
  - [Optional - Error Documents](#optional---error-documents)
  - [Method 1 - No Docker](#method-1---no-docker)
    - [Step 1: Setup the Server](#step-1-setup-the-server)
      - [1.1 Setup LAMP Server](#11-setup-lamp-server)
      - [1.2 If you can, add a security certificate](#12-if-you-can-add-a-security-certificate)
      - [1.3 Going to email folks? Add PEAR](#13-going-to-email-folks-add-pear)
    - [Step 2: Launch the installer](#step-2-launch-the-installer)
  - [Method 2 - Using Docker](#method-2---using-docker)
    - [Step 1: Setup Docker](#step-1-setup-docker)
    - [Step 2: Build and Start CSB](#step-2-build-and-start-csb)
    - [Step 3: Launch the installer](#step-3-launch-the-installer)

---------------------------------------------

</details>

## About

This is an open source image annotation platform suitable for citizen science, complete with native machine learning tools that can be trained through contributed data.

## License

> Copyright © 2012-2023 CosmoQuest X Team is led by Pamela Gay with the Development team and maintained through community collaboration. **All rights reserved.**

<!--  -->
> Images, videos and other media belong to their respective owners.

<!--  -->
> Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at [<http://www.apache.org/licenses/LICENSE-2.0>](http://www.apache.org/licenses/LICENSE-2.0).

<!--  -->
> Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

## Compiling and Installing

To get a production build of the software, go the [latest `master` CI run](https://github.com/CosmoQuestX/CSB7.0/actions?query=branch%3Amaster) and download the `csb-build` artifact.

### Minimum PHP requirements

CSB 7.0 has been tested in our sandbox environment, which uses PHP 8.2. The [Docker configuration](#method-2---using-docker) is configured to use PHP 8.2, as well.

If you have not yet updated to PHP 8.2 from PHP 7.4 or earlier, you may want to consider doing this, however there is no requirement to do this. You can find out more information about PHP's supported versions at [https://www.php.net/supported-versions.php](https://www.php.net/supported-versions.php).

### Add SASS support

To install SASS, follow instructions on <https://sass-lang.com/install>. Once things
are installed, you'll need to compile your sass files into css files whenever
the sass is edited.

To watch and compile sass on the command line run:

```shell
sass --watch csb-themes/default/sass/style.scss:csb-themes/default/style.css
```

PHPstorm can watch and compile sass (see <https://www.jetbrains.com/help/phpstorm/transpiling-sass-less-and-scss-to-css.html>).

Instructions on compiling SASS in ubuntu are here: <https://webdesign.tutsplus.com/tutorials/watch-and-compile-sass-in-five-quick-steps--cms-28275>

### Optional - Error Documents

CSB defines Apache error documents. If you want them to extend to the whole server, move the .htaccess file to the document root.

---------------------------------------------

Note: You'll need the repo accessible to apache. This means either
clone it into a directory Apache sees, or sim link it there.

### Clone repo

```bash
cd <directory for apache>
git clone https://github.com/CosmoQuestX/CSB7.0.git

```

### Method 1 - No Docker

#### Step 1: Setup the Server

##### 1.1 Setup LAMP Server

- Setup an Apache 2 / MySQL 8 / PHP 8 environment. If we don't have specific instructions you need below, look for instructions for Wordpress. Our setup should be the same.
  - OSX
    - Enable Apache `apachectl start`
    - In `/etc/apache2/httpd.conf`
      - Uncomment `LoadModule` statements for php7 and mod_rewrite _Use Legacy Password Encryption_
      - Set `AllowOverride All`
    - Restart Apache `sudo apachectl restart`
    - [Install MySQL](https://dev.mysql.com/downloads/mysql)
    - Add mysql to your `.bash_profile` by adding `export PATH="/usr/local/mysql/bin:$PATH"`
  - Ubuntu: find the Digital Ocean Tutorial for your version of Ubuntu
  - Windows: _to be determined_

> If you don't have a LAMP (or Win AMP) setup, find instructions to install Wordpress. This software requires the same kind of server configuration! For ubuntu, the Digital Ocean tutorials are among the best.

##### 1.2 If you can, add a security certificate

- You should always use a certificate. If you don't have one, try using the free [Let's Encrypt: CertBot](https://letsencrypt.org/getting-started/).

##### 1.3 Going to email folks? Add PEAR

- MacOS Mojave use: <https://tobschall.de/2018/08/07/pear-on-mojave/>
- General installation use: <https://pear.php.net/manual/en/installation.getting.php>

#### Step 2: Launch the installer

- Go to <http://yourhost/csb-installer/> in your browser. This will let you configure your installation and then install databases and setup an admin user using the settings in csb-settings.php

---------------------------------------------

### Method 2 - Using Docker

#### Step 1: Setup Docker

- Download and install the corresponding docker desktop version for your OS from [Docker](https://hub.docker.com/search?q=&type=edition&offering=community)

#### Step 2: Build and Start CSB

- For Windows - open either Command Console or Powershell. For Mac or Linux - open your command shell.

- Execute the following command from the CSB directory:
```docker-compose up```

- To stop docker and close the app, press Ctrl+C

#### Step 3: Launch the installer

- Go to <http://localhost:8080/csb/csb-installer/> in your browser.
  - To use the docker MariaDB, use ```db``` as your database hostname
  - Default docker username, password, and database are all ```csb```
