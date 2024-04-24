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

This is an open source image annotation platform suitable for citizen science.

## License

> This is a project from CosmoQuest, and is led by Pamela Gay and maintained through community collaboration.

<!--  -->
> Images, videos and other media belong to their respective owners.

<!--  -->
> Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at [<http://www.apache.org/licenses/LICENSE-2.0>](http://www.apache.org/licenses/LICENSE-2.0).

<!--  -->
> Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

## Dependencies
### Minimum PHP requirements

CSB 7.0 has been tested in our sandbox environment, which uses PHP 8.2. The [Docker configuration](#method-2---using-docker) is configured to use PHP 8.2, as well.
You can find out more information about PHP's supported versions at [https://www.php.net/supported-versions.php](https://www.php.net/supported-versions.php).

### Add SASS support

To install SASS, follow instructions on <https://sass-lang.com/install>. Once things
are installed, you'll need to compile your sass files into css files whenever
the sass is edited.

To watch and compile sass on the command line run:

```shell
sass --watch csb-themes/default/sass/style.scss:csb-themes/default/style.css &
```

PHPstorm can watch and compile sass (see <https://www.jetbrains.com/help/phpstorm/transpiling-sass-less-and-scss-to-css.html>).

Instructions on compiling SASS in ubuntu are here: <https://webdesign.tutsplus.com/tutorials/watch-and-compile-sass-in-five-quick-steps--cms-28275>

### Email setup
Going to email folks? You'll need an SMTP-relay
Most email software caps the number of emails you can send per day.
Services like SendGrid or Google's SMTP-Relay can help you get around this.
You're going to need the following information: Username, Password,
SMTP-Relay Server, and encryption method.

## Installation
### Clone repo

```bash
cd <directory for apache or Docker>
git clone https://github.com/CosmoQuestX/CSB7.0.git
cd CSB7.0
composer up

```

### Method 1 - Server Installation

#### Step 1: Setup the Server

##### 1.1 Setup LAMP Server

- Setup a standard Apache 2 / MySQL 8 / PHP 8 environment.

> If you don't have a LAMP (or Win AMP) setup and are new to this kind of an
> environment, find instructions to install Wordpress. CSB7.0 requires the
> same kind of server configuration! For Ubuntu, the Digital Ocean tutorials are among the best.

##### 1.2 If you can, add a security certificate

- You should always use a certificate. If you don't have one, try using the free [Let's Encrypt: CertBot](https://letsencrypt.org/getting-started/).

##### 1.3 Going to email folks? You'll need an SMTP-relay

- Most email software caps the number of emails you can send per day.
Services like SendGrid or Google's SMTP-Relay can help you get around this.
- You're going to need the

#### Step 2: Launch the installer

- Go to <http://yourhost/csb-installer/> in your browser. This will let you configure your installation and then install databases and setup an admin user using the settings in csb-settings.php

---------------------------------------------

### Method 2 - Using Docker

#### Step 1: Setup Docker

- Download and install the corresponding docker desktop version for your OS from [Docker](https://hub.docker.com/search?q=&type=edition&offering=community)

#### Step 2: Build and Start CSB

- For Windows - open either Command Console or Powershell. For Mac or Linux - open your command shell.

- Open Docker & execute the following command from the CSB directory:
```docker compose up```

- To stop docker and close the app, press Ctrl+C or type at a commandline ```docker compose stop```

#### Step 3: Launch the installer

- Go to <http://localhost:8080/csb/csb-installer/> in your browser.
    - To get the IP address of your docker container, run `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' csb-db-1` in your terminal.
    - All other settings are in docker-compose.yml (and change the passwords,
      especially in production)

