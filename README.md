# CSB7.0
An open source implementation of CosmoQuest's Citizen Science Builder software. 

####Step 1: Setup an Apache 2 / MySQL 8 / PHP 7 environment with SASS.
If you don't have a LAMP (or Win AMP) setup, find instructions to install 
Wordpress. This software requires the same kind of server configuration! 
For ubuntu, the Digital Ocean tutorials are among the best.

To install SASS, follow instructions on sass-lang.com/install. Once things
are installed, you'll need to compile your sass files into css files whenever
the sass is edited. 

PHPstorm can watch and compile sass (see https://www.jetbrains.com/help/phpstorm/transpiling-sass-less-and-scss-to-css.html).

Instructions on compiling SASS in ubuntu are here: https://webdesign.tutsplus.com/tutorials/watch-and-compile-sass-in-five-quick-steps--cms-28275

####Step 2: Copy csb-settings-example.php csb-settings.php and edit the file to match your systems settings

####Step 3: Go to http://yourdir/csb-installer/installer.php in your browser. 
  This will install databases and setup an admin user using the settings in csb-settings.php