<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/home/site/wwwroot/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/home/site/wwwroot/wp-content/wflogs/');
	include_once '/home/site/wwwroot/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>