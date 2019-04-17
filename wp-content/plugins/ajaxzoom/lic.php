<?php

if ( ! isset( $zoom ) ){
	exit;
}

$licenses = array();
@ini_set( 'display_errors', 0);
error_reporting( 0 );

// Prevent loading all classes and read licenses stored in db faster!
try {
	if (file_exists(__DIR__ . '/../../../wp-config.php') && is_readable(__DIR__ . '/../../../wp-config.php')) {
		$fileStr = php_strip_whitespace(__DIR__ . '/../../../wp-config.php');
		$fileStr = str_replace('require_once(ABSPATH . \'wp-settings.php\');', '', $fileStr);
		$fileStr = str_replace('<?php', '', $fileStr);
		$fileStr = str_replace('<?', '', $fileStr);
		$fileStr = str_replace('?>', '', $fileStr);
		$fileStr = preg_replace('/\s+/', '', $fileStr);

		function awp_get_connection_value($reg) {
			if (is_array($reg) && $reg[0]) {
				preg_match("/\'(.*?)\'/", $reg[0], $match);
				if (is_array($match) && $match[0]) {
					return trim(str_replace('\'', '', $match[0]));
				} else {
					return '';
				}
			} else {
				return '';
			}
		}

		preg_match("/(?<=DB_HOST')(.*?)(\'\))/", $fileStr, $match_host_arr_a);
		preg_match("/(?<=DB_USER')(.*?)(\'\))/", $fileStr, $match_user_arr_a);
		preg_match("/(?<=DB_PASSWORD')(.*?)(\'\))/", $fileStr, $match_password_arr_a);
		preg_match("/(?<=DB_NAME')(.*?)(\'\))/", $fileStr, $match_name_arr_a);
		preg_match("/(?<=$table_prefix=')(.*?)(\';)/", $fileStr, $match_table_prefix_a);

		if (is_array($match_table_prefix_a) && $match_table_prefix_a[0]) {
			$match_table_prefix_a[0] = '\''.$match_table_prefix_a[0];
		}

		$awp_DB_HOST = awp_get_connection_value($match_host_arr_a);
		$awp_DB_USER = awp_get_connection_value($match_user_arr_a);
		$awp_DB_PASSWORD = awp_get_connection_value($match_password_arr_a);
		$awp_DB_NAME = awp_get_connection_value($match_name_arr_a);
		$awp_table_prefix = awp_get_connection_value($match_table_prefix_a);

		if ($awp_DB_HOST && $awp_DB_USER && $awp_DB_PASSWORD && $awp_DB_NAME) {
			if ( function_exists('mysqli_connect') ) {
				$mysqli = mysqli_connect((string)$awp_DB_HOST, (string)$awp_DB_USER, (string)$awp_DB_PASSWORD, (string)$awp_DB_NAME);
				$data_query = mysqli_query($mysqli, "SELECT `option_value` FROM `" . (string)$awp_table_prefix . "options` WHERE `option_name` = 'AJAXZOOM_LICENSES'");
				$data = mysqli_fetch_array($data_query);
				$licenses = unserialize($data['option_value']);
				mysqli_close($mysqli);
				unset($mysqli, $data_query, $data);
			} else {
				$db_connect = mysql_connect((string)$awp_DB_HOST, (string)$awp_DB_USER, (string)$awp_DB_PASSWORD);
				$db = mysql_select_db((string)$awp_DB_NAME, $db_connect);
				$data_query = mysql_query("SELECT `option_value` FROM `" . (string)$awp_table_prefix . "options` WHERE `option_name` = 'AJAXZOOM_LICENSES'");
				$data = mysql_fetch_array($data_query);
				$licenses = unserialize($data['value']);
				mysql_close($db_connect);
				unset($db_connect, $db, $data_query, $data);
			}
		}

		unset($awp_DB_HOST, $awp_DB_USER, $awp_DB_PASSWORD, $awp_DB_NAME, $awp_table_prefix, $fileStr);
		unset($match_host_arr_a, $match_user_arr_a, $match_password_arr_a, $match_name_arr_a, $match_table_prefix_a);
	}
} catch (Exception $e) {

}

if (!empty($licenses)){
	foreach ($licenses as $l){
		$zoom['config']['licenses'][$l['domain']] = array(
			'licenceType' => $l['type'],
			'licenceKey' => $l['key'],
			'error200' => $l['error200'],
			'error300' => $l['error300']
		);
	}
}
