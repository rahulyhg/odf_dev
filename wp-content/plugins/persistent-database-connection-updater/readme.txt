=== Persistent database connection updater ===
Contributors: bibinkurian
Tags: MySQL, Persistent connection, Microsoft Azure
Requires at least: 3.9.1
Tested up to: 3.9.2
Stable tag: 1.0
License: GPL2

This WordPress plugin automatically updates the MySQL database connection to persistent connection when user update the WordPress version from backend.

== Description ==
This WordPress plugin automatically updates the MySQL database connection to persistent connection when user update the WordPress version from backend. This plugin is developed to use persistent database connection in Microsoft Azure environment for better performance with clearDB.

== Installation ==
1. Extract the Persistent database connection updater plugin persistent-connection-updater.zip to the plugins directory of the WordPress installation. 
e.g. if WordPress is installed in "C:\inetpub\wwwroot\wordpress" directory, extract the persistent-connection-updater.zip file into directory "C:\inetpub\wwwroot\wordpress\wp-content\plugins".

2. To activate the plugin, log in into the WordPress as administrator and navigate to list of plugins. Then check the associated checkbox for the plugin and click on "Activate" link.

3. If you would like to use this as a must use plugin then instead of step 1 & 2 copy the persistent-connection-updater.php file to "wp-content/mu-plugins" folder.

== Changelog ==
= 1.0 =
* First release of Persistent database connection updater.