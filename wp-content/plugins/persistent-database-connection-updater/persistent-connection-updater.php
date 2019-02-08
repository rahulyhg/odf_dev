<?php
/**
 * Plugin Name: Persistent database connection updater
 * Plugin URI: 
 * Description: This WordPress plugin automatically updates the database connection to persistent connection when user update the WordPress version from backend.
 * Version: 1.0
 * Author: Microsoft Open Technologies, Inc.
 * Author URI: http://msopentech.com/
 * License: GPL2
 */

/*  Copyright 2014  Microsoft Open Technologies, Inc.  (email : msopentech@microsoft.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("No script kiddies please!");

/**
 * WordPress core update process complete hook
 * Update the database connection to persistent after WordPress upgrade.
 * 
 * @param object $upgrader Core Upgrader class for WordPress.
 * @param array $action Action details 
 * 
 * @return null
 */
function p_con_updater_upgrader_process_complete($upgrader, $action) {
	// we need to update wp-db.php file only for core updates.
	if ($action['action'] == 'update' && $action['type'] == 'core') {
		$wpDbFile = ABSPATH . WPINC . '/wp-db.php';
		$wpDbFileContents = file_get_contents($wpDbFile , false);
		
		// If mysqli is available wordpress uses mysqli_real_connect otherwise uses mysql_connect to connect to db.
		$patterns = array('/^(.*)(mysqli_real_connect\(\s*\$this->dbh,\s*\$host,\s*\$this->dbuser,\s*\$this->dbpassword,\s*null,\s*\$port,\s*\$socket,\s*\$client_flags\s*\))(.*)$/m', 
		    '/^(.*)(mysql_connect\(\s*\$this->dbhost,\s*\$this->dbuser,\s*\$this->dbpassword,\s*\$new_link,\s*\$client_flags\s*\))(.*)$/m'
        );

        $replacements = array('${1}mysqli_real_connect( $this->dbh, \'p:\' . $host, $this->dbuser, $this->dbpassword, null, $port, $socket, $client_flags )$3', 
		    '${1}mysql_pconnect( $this->dbhost, $this->dbuser, $this->dbpassword, $client_flags )$3'
        );
        
		$updatedWpDbFileContents = preg_replace ($patterns, $replacements, $wpDbFileContents);
		
		if ($updatedWpDbFileContents != NULL) {
			file_put_contents($wpDbFile, $updatedWpDbFileContents);
		}
	}
}

add_action( 'upgrader_process_complete', 'p_con_updater_upgrader_process_complete', 10, 10 );
?>
