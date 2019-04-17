<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajaxzoom_AJAX
{
	static $thumb_img_preview_size = 'width=128&height=128&qual=100'; // &thumbMode=contain

	/**
	* Hook in ajax handlers.
	*/
	public static function init() {
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_ajaxzoom_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	* Set AJAXZOOM AJAX constant and headers.
	*/
	public static function define_ajax() {
		if ( ! empty( $_GET['ajaxzoom-ajax'] ) ) {
			if ( ! defined( 'DOING_AJAX' ) ) {
				define( 'DOING_AJAX', true );
			}

			if ( ! defined( 'AJAXZOOM_DOING_AJAX' ) ) {
				define( 'AJAXZOOM_DOING_AJAX', true );
			}

			// Turn off display_errors during AJAX events to prevent malformed JSON
			if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
				@ini_set( 'display_errors', 0 );
			}

			$GLOBALS['wpdb']->hide_errors();
		}
	}

	/**
	* Send headers for AJAXZOOM Ajax Requests
	*/
	private static function ajaxzoom_ajax_headers() {
		send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();
		status_header( 200 );
	}

	/**
	* Check for AJAXZOOM Ajax request and fire action.
	*/
	public static function do_ajaxzoom_ajax() {
		global $wp_query;

		if ( ! empty( $_GET['ajaxzoom-ajax'] ) ) {
			$wp_query->set( 'ajaxzoom-ajax', sanitize_text_field( $_GET['ajaxzoom-ajax'] ) );
		}

		if ( $action = $wp_query->get( 'ajaxzoom-ajax' ) ) {
			self::ajaxzoom_ajax_headers();
			do_action( 'ajaxzoom_ajax_' . sanitize_text_field( $action ) );
			die();
		}
	}

	/**
	* Hook in methods - uses WordPress ajax handlers (admin-ajax).
	*/
	public static function add_ajax_events() {
		// ajaxzoom_EVENT => nopriv
		$ajax_events = array(
			'add_set' => false,
			'save_set_sort' => false,
			'get_images' => false,
			'delete_set' => false,
			'set_360_status' => false,
			'save_settings' => false,
			'upload_image' => false,
			'upload_image2d' => false,
			'get_crop_json' => false,
			'save_crop_json' => false,
			'get_hotspot_json' => false,
			'save_hotspot_json' => false,
			'delete_product_image_360' => false,
			'delete_product_image_2d' => false,
			'save_2d_variations' => false,
			'save_2d_sort' => false,
			'set_2d_img_status' => false,
			'reset_options' => false,
			'del_htaccess' => false,
			'add_video' => false,
			'save_video_sort' => false,
			'delete_video' => false,
			'set_video_status' => false,
			'save_settings_video' => false,
			'save_product_az_settings' => false,
			'refresh_pictures_list' => false,
			'get_hotspot_img_json' => false,
			'set_hotspot_img_json' => false,
			'set_hotspot_status' => false,
			'get_az_version' => false,
			'get_az_avail_version' => false,
			'download_axzm' => false,
			'delete_post_cache' => false,
			'update_plugin' => false,
			'backup_plugin' => false,
			'backup_axzm' => false,
			'del_transient' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_ajaxzoom_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_ajaxzoom_' . $ajax_event, array( __CLASS__, $ajax_event ) );

				// AJAXZOOM AJAX can be used for frontend ajax requests
				add_action( 'ajaxzoom_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	public static function add_video() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id_product = (int)$request['id_product'];
		$name = $request['name'];

		if ( empty( $name ) ) {
			$name = 'Unnamed '.uniqid(getmypid());
		}

		$type = $request['type'];
		$uid = $request['uid'];

		$wpdb->query( 'INSERT INTO `'.$db_prefix.'ajaxzoomvideo` 
			(id_product, name, uid, type, status, settings, combinations, sort_order) 
			VALUES(\''.$id_product.'\', \''.$name.'\', \''.$uid.'\', \''.$type.'\', 1, \'{"position":"last"}\', \'[]\', 999)' );

		$id_video = $wpdb->insert_id;

		$r = array();
		$sql = $wpdb->get_results( 'SELECT * 
			FROM `'.$db_prefix.'ajaxzoomvideo` 
			WHERE id_product = '.(int)$id_product.' 
			ORDER BY sort_order ASC, id_video ASC
		', ARRAY_A );

		$i = 1;
		foreach ($sql as $row) {
			$row['order'] = $i++;
			$r[$row['id_video']] = $row;
		}

		wp_send_json(array(
			'status' => '1',
			'name' => $name,
			'uid' => $uid,
			'id_video' => (int)$id_video,
			'type' => $type,
			'id_product' => $id_product,
			'videos' => $r,
			'confirmations' => array('New video entry was added.')
		) );
	}

	public static function save_video_sort() {
		global $wpdb;

		$i = 1;
		foreach ($_POST['sort'] as $id) {
			$wpdb->get_row( "UPDATE `{$wpdb->prefix}ajaxzoomvideo` SET sort_order = '$i' WHERE id_video = " . (int)$id);
			$i++;
		}

		wp_send_json( array(
			'status' => 'ok',
			'confirmations' => array( __( 'The sort order of videos has been updated.', 'ajaxzoom' ) )
		) );
	}

	public static function delete_video() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id_product = (int)$request['id_product'];
		$id_video = (int)$request['id_video'];
		$del_video = $wpdb->query('DELETE FROM `'.$db_prefix.'ajaxzoomvideo` WHERE 
			id_product = '.(int)$id_product.' AND id_video='.(int)$id_video );

		wp_send_json(array(
			'status' => $del_video ? 1 : 0,
			'id_video' => $id_video,
			'id_product' => $id_product,
			'confirmations' => array('Video with ID - '.$id_video.' - deleted.')
		) );
	}

	public static function set_video_status() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id_product = (int)$request['id_product'];
		$id_video = (int)$request['id_video'];
		$status = (int)$request['status'];
		$wpdb->query('UPDATE `'.$db_prefix.'ajaxzoomvideo` SET status = '.(int)$status.' 
			WHERE id_product = '.(int)$id_product.' AND id_video = '.(int)$id_video
		);

		wp_send_json(array(
			'status' => $status,
			'id_video' => $id_video,
			'id_product' => $id_product,
			'confirmations' => array('Status of video with - '.$id_video.' - has been changed.')
		) );
	}

	public static function save_settings_video() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id_product = (int)$request['id_product'];
		$id_video = (int)$request['id_video'];
		$names = explode('|', $request['names']);
		$values = explode('|', $request['values']);

		$combinations = $request['combinations'];
		if ($combinations && $combinations != 'all') {
			$combinations = explode('|', $combinations);
		} else {
			$combinations = '';
		}

		$count_names = count($names);
		$settings = array();

		for ($i = 0; $i < $count_names; $i++) {
			$key = $names[$i];
			$value = $values[$i];
			if ($key != 'name_placeholder' && !empty($key)) {
				$settings[$key] = $value;
			}
		}

		$name = $request['name'];
		$uid = $request['uid'];
		$type = $request['type'];
		$uid_int = $request['uid_int'];

		$data = array(
			'uid' => json_decode($uid_int, true)
		);

		$wpdb->query('
			UPDATE `'.$db_prefix.'ajaxzoomvideo` 
			SET 
			settings = \''.json_encode($settings).'\',
			combinations = \''.(empty($combinations) ? '' : implode(',', $combinations)).'\',
			name = \''.$name.'\',
			uid = \''.$uid.'\',
			type = \''.$type.'\',
			data = \''.json_encode($data).'\' 
			WHERE 
			id_video = '.(int)$id_video.' 
			AND id_product='.(int)$id_product.' 
			LIMIT 1
		');

		$r = array();
		$sql = $wpdb->get_results('SELECT * 
			FROM `'.$db_prefix.'ajaxzoomvideo` 
			WHERE id_product = '.(int)$id_product.' 
			ORDER BY id_video ASC
		', ARRAY_A);

		foreach ($sql as $row) {
			$r[$row['id_video']] = $row;
		}

		wp_send_json(array(
			'status' => 'ok',
			'id_product' => $id_product,
			'id_video' => $id_video,
			'videos' => $r,
			'confirmations' => array('The settings have been updated.')
		) );
	}

	public static function save_product_az_settings()
	{
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id_product = (int)$request['id_product'];

		$names = explode('|', $request['names']);
		$values = explode('|', $request['values']);
		$count_names = count($names);
		$settings = array();

		for ($i = 0; $i < $count_names; $i++) {
			$key = $names[$i];
			$value = $values[$i];
			if ($key != 'name_placeholder' && !empty($key)) {
				$settings[$key] = $value;
			}
		}

		$wpdb->query( 'DELETE FROM `'.$db_prefix.'ajaxzoomproductsettings` 
			WHERE id_product = '.(int)$id_product );

		if ( !empty( $settings ) ) {
			$settings = serialize( $settings );

			$wpdb->query('INSERT INTO `'.$db_prefix.'ajaxzoomproductsettings` 
				SET psettings = \''.$settings.'\', 
				id_product = '.(int)$id_product);
		}

		wp_send_json(array(
			'moduleSettings' => Ajaxzoom::get_product_plugin_opt($id_product),
		) );
	}

	public static function reset_options() {
		$cfg = Ajaxzoom::settings_data();
		$excl = array('AJAXZOOM_LICENSES', 'ajaxzoom_db_version', 'ajaxzoom_plugin_update_check');
		$n = 0;
		$nn = 0;

		foreach ($cfg as $k => $v) {
			if ( !in_array($k, $excl) && isset($v['default']) ) {
				if ( update_option($k, $v['default']) ) {
					$n++;
				}

				$nn++;
			}
		}

		wp_send_json( array(
			'n' => $n,
			'nn' => $nn
		) );
	}

	public static function del_htaccess() {
		$dir = Ajaxzoom::dir();
		$n = 0;
		$nn = 0;
		$error = 0;

		if ( current_user_can( 'install_plugins' ) ) {
			$iterator1 = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir . 'pic/360' ) );

			foreach ( $iterator1 as $file ) {
				if ( $file->getFilename() === '.htaccess' ) {
					$filepath = $file->getPathname();
					$filepath = str_replace( '\\', '/', $filepath );
					if ( strstr( $filepath, 'ajaxzoom' ) && $filepath !=  $dir . 'pic/360/.htaccess' ) {
						$n++;
						if ( @unlink( $filepath ) ) {
							$nn++;
						}
					}
				}
			}

			$iterator2 = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir . 'pic/2d' ) );

			foreach ( $iterator2 as $file ) {
				if ( $file->getFilename() === '.htaccess' ) {
					$filepath = $file->getPathname();
					$filepath = str_replace( '\\', '/', $filepath );
					if ( strstr( $filepath, 'ajaxzoom' ) && $filepath !=  $dir . 'pic/2d/.htaccess' ) {
						$n++;
						if ( @unlink( $filepath ) ) {
							$nn++;
						}
					}
				}
			}
		} else {
			$error = 1;
		}

		wp_send_json( array(
			'n' => $n,
			'nn' => $nn,
			'error' => $error
		) );
	}

	public static function save_crop_json() {
		echo Ajaxzoom::set_crop_json( $_GET['id_360'], $_POST['json'] );
		exit;
	}

	public static function get_crop_json() {
		echo AjaxZoom::get_crop_json( absint( $_GET['id_360'] ) );
		exit;
	}

	public static function save_hotspot_json() {
		echo Ajaxzoom::set_hotspot_json( $_GET['id_360'], $_POST['json'] );
		exit;
	}

	public static function get_hotspot_json() {
		echo AjaxZoom::get_hotspot_json( absint( $_GET['id_360'] ) );
		exit;
	}

	public static function delete_set() {
		global $wpdb;

		ob_start();

		$id_360set = absint( $_POST['id_360set'] );
		$id_360 = Ajaxzoom::get_set_parent( $id_360set );
		$id_product = Ajaxzoom::get_set_product( $id_360 );

		Ajaxzoom::delete_set( $id_360set );

		wp_send_json( array(
			'id_360set' => $id_360set,
			'id_360' => $id_360,
			'path' => Ajaxzoom::uri() . '/ajaxzoom/pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set,
			'removed' => ( $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . $id_360 ) ? 0 : 1 ),
			'confirmations' => array( __( 'The 360 image set was successfully removed.', 'ajaxzoom' ) )
		) );
	}

	public static function set_360_status() {
		global $wpdb;

		ob_start();

		$status = absint( $_POST['status'] );
		$id_360 = absint( $_POST['id_360'] );
		Ajaxzoom::set_360_status( $id_360, $status );

		wp_send_json( array(
			'status' => 'ok',
			'confirmations' => array( __( 'The status has been updated.', 'ajaxzoom' ) )
		) );
	}

	public static function upload_image() {
		global $wpdb, $ajaxzoom_files_permissions;

		ob_start();

		$id_product = absint( $_POST['id_product'] );
		$id_360set = absint( $_POST['id_360set'] );

		$id_360 = Ajaxzoom::get_set_parent( $id_360set );
		$folder = Ajaxzoom::create_product_360_folder( $id_product, $id_360set );

		$file = $_FILES['file'];

		$file['name'] = $id_product . '_' . $id_360set . '_' . $file['name'];
		$file['name'] = Ajaxzoom::img_name_filter( $file['name'] );

		$tmp = explode( '.', $file['name'] );
		$ext = end( $tmp );
		$name = preg_replace( '|\.' . $ext . '$|', '', $file['name'] );
		$dst = $folder . '/' . $file['name'];
		rename( $file['tmp_name'], $dst );
		chmod( $dst, octdec( $ajaxzoom_files_permissions ) );

		$thumb = Ajaxzoom::uri() . '/ajaxzoom/axZm/zoomLoad.php';
		$thumb .= '?azImg=' . Ajaxzoom::uri() .'/ajaxzoom/pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set . '/' . $file['name'] . '&' . self::$thumb_img_preview_size;

		wp_send_json( array(
			'status' => 'ok',
			'id' => $name,
			'id_product' => $id_product,
			'id_360' => $id_360,
			'id_360set' => $id_360set,
			'path' => $thumb,
			'filename' => $file['name'],
			'confirmations' => array( __( 'The file has been uploaded.', 'ajaxzoom' ) )
		) );
	}

	public static function upload_image2d() {
		global $wpdb, $ajaxzoom_folder_permissions, $ajaxzoom_files_permissions;

		ob_start();

		$id_product = absint( $_POST['id_product'] );

		$wpdb->query( "INSERT INTO `{$wpdb->prefix}ajaxzoom2dimages` (id_product) VALUES('$id_product')" );
		$id = $wpdb->insert_id;

		$file = $_FILES['file'];

		$file['name'] = $id . '_' . $id_product . '_' . $file['name'];
		$file['name'] = Ajaxzoom::img_name_filter( $file['name'] );

		$dst_dir = Ajaxzoom::dir() . 'pic/2d/' . $id_product;

		if ( !is_dir( $dst_dir ) ) {
			mkdir( $dst_dir, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $dst_dir, octdec( $ajaxzoom_folder_permissions ) );
		}

		$dst = $dst_dir . '/' . $file['name'];
		rename( $file['tmp_name'], $dst );
		chmod( $dst, octdec( $ajaxzoom_files_permissions ) );

		$thumb = Ajaxzoom::uri() . '/ajaxzoom/axZm/zoomLoad.php';
		$thumb .= '?azImg=' . Ajaxzoom::uri() .'/ajaxzoom/pic/2d/' . $id_product . '/' . $file['name'] . '&' . self::$thumb_img_preview_size;

		$wpdb->query( "UPDATE `{$wpdb->prefix}ajaxzoom2dimages` SET image = '" . $file['name'] . "' WHERE id = $id" );

		wp_send_json( array(
			'status' => 'ok',
			'id' => $id,
			'id_product' => $id_product,
			'path' => $thumb,
			'image_name' => $file['name'],
			'confirmations' => array( __( 'The file has been uploaded.', 'ajaxzoom' ) )
		) );
	}

	public static function get_images() {
		global $wpdb;

		$id_product = absint( $_POST['id_product'] );
		$id_360set = absint( $_POST['id_360set'] );
		$images = Ajaxzoom::get_360_images( $id_product, $id_360set );

		wp_send_json( array( 
			'status' => 'ok',
			'id_product' => $id_product,
			'id_360set' => $id_360set,
			'images' => $images
		) );
	}

	public static function add_set() {
		global $wpdb;

		ob_start();

		$id_product = absint( $_POST['id_product'] );
		$name = sanitize_text_field( $_POST['name'] );

		if ( empty( $name ) ) {
			$name = 'Unnamed '.uniqid( getmypid() );
		}

		$existing = isset( $_POST['existing'] ) ? absint( $_POST['existing'] ) : 0;
		$zip = sanitize_text_field( $_POST['zip'] );
		$delete = isset( $_POST['delete'] ) ? $_POST['delete'] : '';
		$arcfile = isset( $_POST['arcfile'] ) ? $_POST['arcfile'] : '';
		$new_id = '';
		$new_name = '';
		$new_settings = '';

		if ( !empty( $existing ) ) {
			$id_360 = $existing;
			$tmp = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = '{$id_360}'" );
			$name = $tmp->name;

		} else {
			$new_settings = get_option( 'AJAXZOOM_DEFAULT360SETTINGS' );
			$settings = $new_settings ? $new_settings : '{"position":"first","spinReverse":"true","spinBounce":"false","spinDemoRounds":"3","spinDemoTime":"4500"}';

			$wpdb->query( "INSERT INTO `{$wpdb->prefix}ajaxzoom360` (id_product, name, settings, status, sort_order) 
				VALUES('{$id_product}', '" . esc_sql( $name ) . "', '{$settings}', '" . ( $zip == 'true' ? 1 : 0 ) . "', 999)" );

			$id_360 = $wpdb->insert_id;
			$new_id = $id_360;
			$new_name = $name;
		}

		$wpdb->query( "INSERT INTO `{$wpdb->prefix}ajaxzoom360set` (id_360, sort_order) 
			VALUES('{$id_360}', 0)" );

		$id_360set = $wpdb->insert_id;

		$sets = array();

		if ( $zip == 'true' ) {
			$sets = Ajaxzoom::add_images_arc( $arcfile, $id_product, $id_360, $id_360set, $delete );
		}

		wp_send_json( array(
			'status' => '0',
			'name' => $name,
			'path' => Ajaxzoom::uri() . '/ajaxzoom/assets/img/no_image-100x100.jpg',
			'sets' => $sets,
			'id_360' => $id_360,
			'id_product' => $id_product,
			'id_360set' => $id_360set,
			'confirmations' => array( __( 'The image set was successfully added.' , 'ajaxzoom' ) ),
			'new_id' => $new_id,
			'new_name' => $new_name,
			'new_settings' => $new_settings
		) );

		die();
	}
	
	public static function save_set_sort() {
		global $wpdb;

		$i = 1;
		foreach ($_POST['sort'] as $id) {
			$wpdb->get_row( "UPDATE `{$wpdb->prefix}ajaxzoom360` SET sort_order = '$i' WHERE id_360 = " . (int)$id);
			$i++;
		}

		wp_send_json( array(
			'status' => 'ok',
			'confirmations' => array( __( 'The sort order of 360/3D has been updated.', 'ajaxzoom' ) )
		) );
	}

	public static function save_settings() {
		global $wpdb;

		ob_start();

		$id_product = absint( $_POST['id_product'] );
		$id_360 = absint( $_POST['id_360'] );
		$active = absint( $_POST['active'] );
		$names = explode( '|', sanitize_text_field( $_POST['names'] ) );
		$values = explode( '|', sanitize_text_field( $_POST['values'] ) );
		$combinations = explode( '|', sanitize_text_field( $_POST['combinations'] ) );
		$count_names = count( $names );
		$settings = array();

		for ( $i = 0; $i < $count_names; $i++ ) {
			$key = $names[$i];
			$value = $values[$i];

			if ( $key != 'name_placeholder' && !empty( $key ) ) {
				$settings[ $key ] = $value;
			}
		}

		$wpdb->query( "UPDATE `{$wpdb->prefix}ajaxzoom360` 
			SET settings = '" . json_encode($settings) . "', combinations = '" . implode( ',', $combinations ) . "' 
			WHERE id_360 = $id_360" );

		// update dropdown
		$sets_groups = Ajaxzoom::get_groups( $id_product );
		$select = '<select id="az_select_id_360" name="az_select_id_360"><option value="">Select</option>';
		foreach ( $sets_groups as $group ) {
			$select .= '<option value="' . $group['id_360'] . '" ';
			$select .= 'data-settings="' . urlencode( $group['settings'] ) . '" ';
			$select .= 'data-combinations="[' . urlencode( $group['combinations'] ) . ']">' . $group['name'] . '</option>';
		}

		$select .= '</select>';

		// active/not active
		$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoomproducts` WHERE id_product = $id_product" );

		if ( $active == 0 ) {
			$wpdb->query( "INSERT INTO `{$wpdb->prefix}ajaxzoomproducts` (id_product) VALUES ($id_product)" );
		}

		wp_send_json( array(
			'status' => 'ok',
			'select' => $select,
			'id_product' => $id_product,
			'id_360' => $id_360,
			'confirmations' => array( __( 'The settings has been updated.', 'ajaxzoom' ) )
		) );

		die();
	}

	public static function delete_product_image_360() {

		$id_image = sanitize_text_field( $_POST['id_image'] );
		$id_product = absint( $_POST['id_product'] );
		$id_360set = absint( $_POST['id_360set'] );
		$id_360 = Ajaxzoom::get_set_parent( $id_360set );
		$tmp = explode( '&', $_POST['ext'] );
		$ext = reset( $tmp );
		$filename = $id_image.'.'.$ext;

		$dst = Ajaxzoom::dir() . 'pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set . '/' . $filename;
		unlink( $dst );

		AjaxZoom::delete_image_az_cache( $filename );

		wp_send_json( array(
			'status' => 'ok',
			'content' => (object)array( 'id' => $id_image ),
			'confirmations' => array( __( 'The image was successfully deleted.', 'ajaxzoom' ) )
		) );
	}

	public static function delete_product_image_2d() {

		global $wpdb;

		$id = intval( $_POST['id_image'] );
		$id_product = absint( $_POST['id_product'] );

		$image = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom2dimages` WHERE id = " . $id . " ORDER BY sort_order", ARRAY_A );

		$dst = Ajaxzoom::dir() . 'pic/2d/' . $id_product . '/' . $image['image'];
		if ( file_exists( $dst) ) {
			unlink( $dst );
			AjaxZoom::delete_image_az_cache( $image['image'] );
		}

		$wpdb->get_row( "DELETE FROM `{$wpdb->prefix}ajaxzoom2dimages` WHERE id = " . $id );

		wp_send_json( array(
			'status' => 'ok',
			'content' => (object)array( 'id' => $id),
			'confirmations' => array( __( 'The image was successfully deleted.', 'ajaxzoom' ) )
		) );
	}

	public static function save_2d_variations() {
		global $wpdb;

		$id = intval( $_POST['id_image'] );
		$id_product = absint( $_POST['id_product'] );

		$wpdb->get_row( "UPDATE `{$wpdb->prefix}ajaxzoom2dimages` SET variations = '" . implode( ',', $_POST['variations'] ) . "' WHERE id = " . (int)$id);

		wp_send_json( array(
			'status' => 'ok',
			'id' => $id,
			'confirmations' => array( __( 'The variations has been updated for this image.', 'ajaxzoom' ) )
		) );
	}

	public static function save_2d_sort() {
		global $wpdb;

		$i = 0;
		foreach ($_POST['sort'] as $id) {
			$wpdb->get_row( "UPDATE `{$wpdb->prefix}ajaxzoom2dimages` SET sort_order = '$i' WHERE id = " . (int)$id);
			$i++;
		}

		wp_send_json( array(
			'status' => 'ok',
			'id' => $id,
			'confirmations' => array( __( 'The sort order of images has been updated.', 'ajaxzoom' ) )
		) );
	}

	public static function set_2d_img_status() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$request = $_POST;

		$id = (int)$request['id'];
		$status = (int)$request['status'];
		$wpdb->query('UPDATE `'.$db_prefix.'ajaxzoom2dimages` SET status = '.(int)$status.' 
			WHERE id = '.(int)$id
		);

		wp_send_json(array(
			'status' => $status,
			'id' => $id_video,
			'confirmations' => array('Status of image with - '.$id.' - has been changed.')
		) );
	}

	public static function refresh_pictures_list() {
		$id_product = absint( $_POST['id_product'] );
		wp_send_json( Ajaxzoom::get_images_backend_hotspots( $id_product ) );
	}

	public static function get_hotspot_img_json()
	{
		global $wpdb;
		$db_prefix = $wpdb->prefix;

		$request = $_GET;

		$id_media = Ajaxzoom::test_id_media($request['id_media']);
		$id_product = (int)$request['id_product'];
		$image_name = $request['image_name'];

		$row = $wpdb->get_row('SELECT * FROM `'.$db_prefix.'ajaxzoomimagehotspots` WHERE id_media=\''.$id_media.'\' 
			AND id_product='.$id_product.' LIMIT 1', ARRAY_A);

		if ( isset( $row['hotspots'] ) && $row['hotspots'] ) {
			echo stripslashes($row['hotspots']);
		} else {
			echo '{}';
		}

		exit;
	}

	public static function set_hotspot_img_json() {
		global $wpdb;
		$db_prefix = $wpdb->prefix;

		$request = $_GET;

		$id_media = Ajaxzoom::test_id_media($request['id_media']);
		$id_product = (int)$request['id_product'];
		$image_name = $request['image_name'];
		$json = $_POST['json'];

		if ($json) {
			if (Ajaxzoom::has_img_hotspots($id_media) === 1) {
				$query = 'UPDATE `'.$db_prefix.'ajaxzoomimagehotspots` SET ';
				$query .= 'hotspots=\''.$json.'\' ';
				$query .= 'WHERE id_media=\''.(string)$id_media.'\' LIMIT 1';
			} else {
				$query = 'INSERT INTO `'.$db_prefix.'ajaxzoomimagehotspots` SET ';
				$query .= 'hotspots=\''.$json.'\', ';
				$query .= 'id_media=\''.(string)$id_media.'\', ';
				$query .= 'id_product='.(int)$id_product.', ';
				$query .= 'image_name=\''.(string)$image_name.'\', ';
				$query .= 'hotspots_active=1 ';
			}
		} else {
			$query = 'DELETE FROM `'.$db_prefix.'ajaxzoomimagehotspots` WHERE id_media=\''.(string)$id_media.'\' LIMIT 1';
		}

		$result = $wpdb->query($query);

		wp_send_json(
			array(
				'status' => $result
			)
		);
	}

	public static function set_hotspot_status() {
		$request = $_POST;
		global $wpdb;
		$db_prefix = $wpdb->prefix;

		$id_media = Ajaxzoom::test_id_media($request['id_media']);
		$id_product = (int)$request['id_product'];
		$status = (int)$request['status'];

		$wpdb->query('UPDATE `'.$db_prefix.'ajaxzoomimagehotspots` 
			SET hotspots_active='.(int)$status.' 
			WHERE id_media=\''.$id_media.'\''
		);

		wp_send_json(
			array(
				'status' => $status,
				'id_media' => $id_media,
				'id_product' => $id_product,
				'confirmations' => array('Status of image with id - '.$id_media.' - has been changed.')
			)
		);
	}

	public static function get_az_version() {
		return Ajaxzoom::get_az_version();
	}

	public static function get_az_avail_version() {
		$output_az = file_get_contents('https://www.ajax-zoom.com/getlatestversion.php');
		if ($output_az != false) {
			wp_send_json(json_decode($output_az, true));
		} else {
			wp_send_json(array(
				'error' => 1
			));
		}
	}

	public static function download_axzm() {
		Ajaxzoom::install_axzm(true);
	}

	public static function backup_axzm() {
		Ajaxzoom::backup_axzm(true, false);
	}

	public static function update_plugin() {
		Ajaxzoom::update_plugin();
	}

	public static function backup_plugin() {
		Ajaxzoom::backup_plugin(true);
	}

	public static function del_transient() {
		$request = $_GET;
		if ( $request['transient'] && stristr($request['transient'], 'ajaxzoom') && get_transient($request['transient']) !== false) {
			delete_transient($request['transient']);
			wp_send_json(array(
				'status' => 1
			));
		}

		wp_send_json(array(
			'status' => 0
		));
	}
}

Ajaxzoom_AJAX::init();
