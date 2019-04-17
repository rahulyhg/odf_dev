<?php

class Ajaxzoom
{
	static $axzmh;
	static $zoom;
	static $show_az_save;
	static $frontend_ouput;
	static $mouseover_settings;
	static $config_vendor;
	static $categories;
	static $fields_list;
	static $thumb_img_preview_size = 'width=128&height=128&qual=100'; // &thumbMode=contain
	static $product_has_video_html5 = false;
	static $frontend_2d_ids = array();
	static $frontend_id_product = 0;
	static $az_srcripts = array();
	static $az_data_on_save = array();
	static $write_log = false;
	static $pre_scripts = array();
	static $pre_css = array();

	/**
	* It is for internal debugging and is turned off
	*/
	public static function az_write_log($log_str, $append = true) {
		global $ajaxzoom_folder_permissions;
		if (!self::$write_log === true) {
			return;
		}

		$dir = dirname(__DIR__) . '/logs';

		if ( !is_dir( $dir ) ) {
			@mkdir( $dir, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $dir, octdec( $ajaxzoom_folder_permissions ) );
		}

		if ( is_array($log_str) ) {
			$log_str = json_encode($log_str, JSON_FORCE_OBJECT);
		}

		$log_str = date('Y-m-d H:i:s') . ' ' . $log_str . "\n\n";
		$log_file = $dir. '/'.date('Y-m-d').'-log.txt';

		if ($append === true) {
			@file_put_contents($log_file, $log_str, FILE_APPEND);
		} else {
			@file_put_contents($log_file, $log_str);
		}
	}

	public static function format_bytes($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	public static function backup_time() {
		return date('Y-m-d_h-i-s');
	}

	public static function woo_version() {
		if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;
			return $woocommerce->version;
		}

		return false;
	}

	public static function pre_run() {
		global $ajaxzoom_db_version, $ajaxzoom_config_version;

		$ajaxzoom_db_version_db = get_option( 'ajaxzoom_db_version' );
		$ajaxzoom_config_version_config = get_option( 'ajaxzoom_config_version' );

		if ( $ajaxzoom_db_version_db && $ajaxzoom_db_version_db != $ajaxzoom_db_version ) {
			self::install_db();
		}

		if ( $ajaxzoom_config_version_config && $ajaxzoom_config_version_config != $ajaxzoom_config_version ) {
			self::install_config();
		}
	}

	public static function loader_installed() {
		$extensions = get_loaded_extensions();
		$ioncube = false;
		$sourceguardian = false;

		foreach ( $extensions as $v ) {
			if ( stristr($v, 'ioncube') ) {
				$ioncube = true;
			}

			if ( stristr($v, 'sourceguardian') ) {
				$sourceguardian = true;
			}
		}

		if ( $ioncube || $sourceguardian ) {
			return true;
		}

		return false;
	}

	public static function add_to_key_val_array($arr, $add) {
		if (!isset($arr[key($add)])) {
			$arr += $add;
		}

		return $arr;
	}

	public static function get_az_version_number() {
		global $wp_version;
		$az_version_test = self::get_az_version(true);
		$az_ver = $wp_version;
		if (!empty($az_version_test) && is_array($az_version_test) && $az_version_test['version']) {
			$az_ver = $az_version_test['version'];
		}

		return $az_ver;
	}

	public static function get_az_version($return_as_arr = false) {
		$txt_file = self::dir() . 'axZm/readme.txt';
		$version = '';
		$date = '';
		$review = '';

		if (file_exists($txt_file)) {
			$handle = fopen($txt_file, 'r');
			while (($line = fgets($handle)) !== false) {
				if (strstr($line, 'Version:')) {
					$version = explode(':', $line);
					$version = trim($version[1]);
				}

				if (strstr($line, 'Date:')) {
					$date = explode(':', $line);
					$date = trim($date[1]);
				}

				if (strstr($line, 'Review:')) {
					$review = explode(':', $line);
					$review = trim($review[1]);
				}
			}
		}

		$return_arr = array(
			'version' => $version,
			'date' => $date,
			'review' => $review
		);

		if ($return_as_arr === true) {
			return $return_arr;
		}

		wp_send_json($return_arr);
	}

	public static function print_az_scripts($arr) {
		$html = '';

		if (!empty($arr)) {
			foreach ($arr as $src) {
				if (!in_array($src, self::$az_srcripts)) {
					if (!isset($az_ver)) {
						$az_ver = self::get_az_version_number();
						$dir = self::dir();
					}

					$html .= '<script type="text/javascript" src="'.plugins_url($src, $dir).'?ver='.$az_ver.'"></script>'."\n";
				}
			}
		}

		return $html;
	}

	public static function get_theme_name() {
		if ( function_exists( 'wp_get_theme' ) ) {
			$themeinfo = wp_get_theme();
			if ( $themeinfo && $themeinfo->get( 'Name' ) ) {
				return $themeinfo->get( 'Name' );
			} else {
				return '';
			}
		}

		return '';
	}

	public static function delete_product($post_id) {
		global $wpdb;

		if (isset($_POST) && isset($_POST['action']) && strstr($_POST['action'], 'remove_variations')) {
			return;
		}

		if ( function_exists( 'wc_get_product' ) && $product = wc_get_product( (int)$post_id ) ) {

			$sets = self::get_sets( (int)$post_id );

			foreach ( $sets as $set ) {
				self::delete_set( $set['id_360set'] );
			}

			$images = self::get_2d_images( (int)$post_id );

			foreach ( $images as $image ) {
				self::delete_image_2d( $image['id'], (int)$post_id );
			}

			$pictures_lst = self::get_images_backend_hotspots( (int)$post_id );
			if (!empty($pictures_lst)) {
				foreach ( $az_pictures_lst as $id => $arr ) {
					if (isset($arr['image_name']) && $arr['image_name']) {
						Ajaxzoom::delete_image_az_cache( $arr['image_name'] );
					}
				}
			}

			$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoomimagehotspots` WHERE id_product = " . (int)$post_id );
			$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoomvideo` WHERE id_product = " . (int)$post_id );
		}
	}

	public static function array_insert_after( array $array, $key, array $new ) {
		$keys = array_keys( $array );
		$index = array_search( $key, $keys );
		$pos = false === $index ? count( $array ) : $index + 1;
		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

	public static function array_insert_before( array $array, $key, array $new ) {
		$keys = array_keys( $array );
		$index = array_search( $key, $keys );
		$pos = false === $index ? count( $array ) : $index - 1;
		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

	public static function get_num_360_product( $postid ) {
		global $wpdb;
		$q = $wpdb->get_results(" SELECT count(*) as num FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_product=" . (int)$postid);
		if (isset($q[0])) {
			return $q[0]->num;
		} else {
			return 0;
		}
	}

	public static function get_num_video_product( $postid ) {
		global $wpdb;
		$q = $wpdb->get_results(" SELECT count(*) as num FROM `{$wpdb->prefix}ajaxzoomvideo` WHERE id_product=" . (int)$postid);
		if (isset($q[0])) {
			return $q[0]->num;
		} else {
			return 0;
		}
	}

	public static function on_product_columns_register( $columns ) {
		//global $wp_query;

		$col1_txt = '<span class="tips" data-tip="AJAX-ZOOM 360 / 3D product views">' . __( '360', 'ajaxzoom' ) . '</span>';
		$col2_txt = '<span class="tips" data-tip="AJAX-ZOOM videos">' . __( 'Video', 'ajaxzoom' ) . '</span>';
		$col1 = array( 'axzm_360' => $col1_txt );
		$col2 = array( 'axzm_video' => $col2_txt );
		$columns = self::array_insert_after( $columns, 'product_type', $col1);
		$columns = self::array_insert_after( $columns, 'axzm_360', $col2);
		return $columns;
	}

	public static function on_product_column_field( $col, $postid ) {
		if ( $col == 'axzm_360' ) {
			$n = self::get_num_360_product( $postid );
			if ($n) {
				echo '<span class="az_badge">' . $n . '</span>';
			} else {
				echo '<span class="na">–</span>';
			}
		} elseif ( $col == 'axzm_video' ) {
			$n = self::get_num_video_product( $postid );
			if ($n) {
				echo '<span class="az_badge">' . $n . '</span>';
			} else {
				echo '<span class="na">–</span>';
			}
		}
	}

	public static function on_product_column_field_sort_register( $col ) {
		global $wp_version;

		if ( $wp_version && version_compare( $wp_version, '3.1.0', '>=' ) ) {
			$col['axzm_360'] = 'axzm_360';
			$col['axzm_video'] = 'axzm_video';
		}

		return $col;
	}

	public static function on_posts_clauses($clauses, $wp_query) {
		// WP_Query::get_posts
		global $wpdb;
		if ( $wp_query->get('post_type') == 'product') {
			if ($wp_query->get('orderby') == 'axzm_360') {
				$clauses['fields'] .= ", COUNT(DISTINCT ajaxzoom360.id_360) AS axzm_qty360 ";
				$clauses['join'] .= " LEFT JOIN {$wpdb->prefix}ajaxzoom360 AS ajaxzoom360 ON ajaxzoom360.id_product = {$wpdb->prefix}posts.ID ";

				if ($clauses['groupby']) {
					$clauses['groupby'] .= ", {$wpdb->prefix}posts.ID ";
				} else {
					$clauses['groupby'] = " {$wpdb->prefix}posts.ID ";
				}

				$clauses['orderby'] = ' axzm_qty360 ' . ($wp_query->get('order') == 'ASC' ? 'ASC' : 'DESC');
			} elseif ($wp_query->get('orderby') == 'axzm_video') {
				$clauses['fields'] .= ", COUNT(DISTINCT ajaxzoomvideo.id_video) AS axzm_qty_video ";
				$clauses['join'] .= " LEFT JOIN {$wpdb->prefix}ajaxzoomvideo AS ajaxzoomvideo ON ajaxzoomvideo.id_product = {$wpdb->prefix}posts.ID ";

				if ($clauses['groupby']) {
					$clauses['groupby'] .= " , {$wpdb->prefix}posts.ID ";
				} else {
					$clauses['groupby'] = " {$wpdb->prefix}posts.ID ";
				}

				$clauses['orderby'] = ' axzm_qty_video ' . ($wp_query->get('order') == 'ASC' ? 'ASC' : 'DESC');
			}
		}

		return $clauses;
	}

	public static function on_before_post_save( $content ) {
		if (isset($_POST['post_ID'])
			&& $_POST['post_ID']
			&& isset($_POST['post_type'])
			&& $_POST['post_type'] == 'product'
			&& isset( $_POST['woocommerce_meta_nonce'] )
			&& function_exists('wp_verify_nonce')
			&& wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' )
		) {
			self::$az_data_on_save = self::get_images_backend_hotspots( (int)$_POST['post_ID'] , array('single', 'gallery') );
		}

		return $content;
	}

	public static function on_after_post_save( $post_id, $post, $update ) {
		global $wpdb;

		if ( $wpdb
			&& isset($_POST['post_ID'])
			&& $_POST['post_ID']
			&& isset($_POST['post_type'])
			&& $_POST['post_type'] == 'product'
			&& isset( $_POST['woocommerce_meta_nonce'] )
			&& function_exists('wp_verify_nonce')
			&& wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) 
		) {
			$after_save_img = self::get_images_backend_hotspots( (int)$_POST['post_ID'], array('single', 'gallery') );
			$deleted_media = array();
			if ( !empty(self::$az_data_on_save) ) {
				foreach (self::$az_data_on_save as $k => $v) {
					if (!isset($after_save_img[$k]) && isset($v['image_name']) && $v['image_name']) {
						$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoomimagehotspots` WHERE id_media = '{$k}' AND id_product = " . (int)$post_id );
						Ajaxzoom::delete_image_az_cache( $v['image_name'] );
					}
				}
			}
		}
	}

	public static function validate_ip($ip)
	{
		if (filter_var($ip,
			FILTER_VALIDATE_IP,
			FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
		) === false) {
			return false;
		}

		return true;
	}

	public static function get_ip()
	{
		$ip_keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR', 
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		);

		foreach ($ip_keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip);
					if (self::validate_ip($ip)) {
						return $ip;
					}
				}
			}
		}

		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}

	public static function chk_admin() {
		$user = wp_get_current_user();
		$allowed_roles = array('editor', 'administrator', 'author');
		if ( array_intersect($allowed_roles, $user->roles ) ) {
			return true;
		} else {
			$ip = self::get_ip();
			self::az_write_log('Not admin attempt: '.($ip ? $ip : '-'));
			return false;
		}
	}

	public static function woocommerce_save_variations_before() {
		if (isset($_POST)
		&& !empty($_POST)
		&& isset($_POST['action'])
		&& $_POST['action'] == 'woocommerce_save_variations'
		&& isset($_POST['product_id'])
		&& $_POST['product_id']
		) {
			if (!self::chk_admin()) {
				return false;
			}

			self::az_write_log('woocommerce_save_variations_before');
			self::$az_data_on_save = self::get_images_backend_hotspots( (int)$_POST['product_id'] , array('variation', 'variation_add') );
		}
	}

	public static function woocommerce_save_variations_after($variation_id, $i) {
		if ( !empty(self::$az_data_on_save) && isset($_POST['product_id']) ) {
			if (!self::chk_admin()) {
				return false;
			}

			$post_id = (int)$_POST['product_id'];
			self::az_write_log('woocommerce_save_variations_after');

			if ($post_id > 0) {
				$after_save_img = self::get_images_backend_hotspots( $post_id , array('variation', 'variation_add') );
				if ( !empty(self::$az_data_on_save) ) {
					foreach (self::$az_data_on_save as $k => $v) {
						if (!isset($after_save_img[$k]) && isset($v['image_name']) && $v['image_name']) {
							Ajaxzoom::delete_image_az_cache( $v['image_name'] );
							$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoomimagehotspots` WHERE id_media = '{$k}' AND id_product = " . $post_id );
						}
					}
				}
			}
		}
	}

	public static function delete_image_2d($id, $id_product) {
		global $wpdb;

		$image = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom2dimages` WHERE id = " . (int)$id . " ORDER BY sort_order", ARRAY_A );

		$dst = self::dir() . 'pic/2d/' . $id_product . '/' . $image['image'];
		if ( file_exists( $dst) ) {
			unlink( $dst );
		}

		Ajaxzoom::delete_image_az_cache( $image['image'] );

		$wpdb->get_row( "DELETE FROM `{$wpdb->prefix}ajaxzoom2dimages` WHERE id = " . (int)$id );
	}

	public static function meta_box() {
		global $post;

		$product_id = $post->ID;

		if ( $post->filter == 'edit' ) { // only when edit (not new)
			$groups = self::get_groups( $product_id );
			$sets = self::get_sets( $product_id );
			$files = self::get_arc_list();
			$active = self::is_active( $product_id );
			$uri = self::uri();
			$variations = self::get_variations( $product_id );
			$az_plugin_opt = self::get_plugin_opt_list();
			ksort($az_plugin_opt);
			$az_plugin_prod_opt = self::get_product_plugin_opt( $product_id );

			include self::dir() . 'templates/backend/tab.php';
		}
	}

	public static function meta_box_2d() {
		global $post;

		$product_id = $post->ID;

		if ( $post->filter == 'edit' ) { // only when edit (not new)
			$uri = self::uri();
			$images = self::get_2d_images( $product_id );
			$variations = self::get_variations( $product_id );

			include self::dir() . 'templates/backend/tab2d.php';
		}
	}

	public static function meta_box_video() {
		global $post;

		$product_id = $post->ID;
		$az_languages = array();

		if ( $post->filter == 'edit' ) { // only when edit (not new)
			$uri = self::uri();
			$variations = self::get_variations( $product_id );
			$az_videos =  self::get_videos( $product_id );

			if ( function_exists( 'icl_get_languages' ) ) {
				$langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
				foreach ($langs as $l) {
					$az_languages[$l['language_code']] = $l['language_code'];
				}
			}

			include self::dir() . 'templates/backend/tab-videos.php';
		}
	}

	public static function meta_box_images() {
		global $post;
		$product_id = $post->ID;

		if ( $post->filter == 'edit' ) {
			$az_pictures_lst = self::get_images_backend_hotspots($product_id);
			include self::dir() . 'templates/backend/tab-pictures.php';
		}
	}

	public static function get_images_backend_hotspots( $product_id = 0, $types = array())
	{
		global $wpdb, $product;
		if (!$product) {
			$product = self::get_product_variable($product_id);
		}

		if (!$product || !is_object($product) || !$product->get_id()) {
			return array();
		}

		$order = 1;
		$upload_info = wp_upload_dir();
		$upload_baseurl = $upload_info['baseurl'];
		$site_url = get_site_url(); // without slash at the end including folder
		$az_az_load = self::uri().'/ajaxzoom/axZm/zoomLoad.php?azImg=';

		$all_images_woo = array();
		$all_images_az = array();
		$az_images = array();
		$variation_ids = array();
		$find_in_set = array();

		// single image
		if ( empty($types) || in_array( 'single', $types ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( (int)$product_id );
			$main_img_info = wp_get_attachment_image_src( $post_thumbnail_id, 'single-post-thumbnail' );
			if ( isset( $main_img_info[0] ) && $main_img_info[0] ) {
				$all_images_woo[$post_thumbnail_id] = $main_img_info[0];
				array_push($find_in_set, $post_thumbnail_id);
			}
		}

		// Gallery images
		if ( empty($types) || in_array( 'gallery', $types ) ) {
			$gal_ids = get_post_meta( $product_id, '_product_image_gallery', true );

			if (!empty($gal_ids)) {
				$gal_ids = explode( ',', $gal_ids );
				$gal_ids = array_map( 'absint', $gal_ids );
				foreach( $gal_ids as $attach_id ) {
					$img = wp_get_attachment_image_src( $attach_id, 'single-post-thumbnail' );
					if (isset($img[0])) {
						$all_images_woo[$attach_id] = $img[0];
						array_push($find_in_set, $attach_id);
					}
				}
			}
		}

		// Single variation images
		$variations = $product->get_available_variations();
		if (!empty($variations)) {
			foreach ( $variations as $variation ) {
				$variation_id = $variation['variation_id'];
				if (isset($variation['image_id'])) {
					$image_id = $variation['image_id'];
				} else {
					$image_id = $variation_id;
				}

				array_push( $variation_ids, $variation_id );

				if ( empty($types) || in_array( 'variation', $types ) ) {
					array_push( $find_in_set, $image_id );

					if ((int)self::woo_version() >= 3) {
						if (isset($variation['image_src'])) {
							$all_images_woo[$image_id] = $variation['image_src'];
						} elseif (isset($variation['image'])
							&& is_array($variation['image'])
							&& (isset($variation['image']['full_src']) || isset($variation['image']['url']))
						) {
							if (isset($variation['image']['full_src'])) {
								$all_images_woo[$image_id] = $variation['image']['full_src'];
							} else {
								$all_images_woo[$image_id] = $variation['image']['url'];
							}
						}
					} elseif (isset($variation['image_link'])) {
						$all_images_woo[$image_id] = $variation['image_link'];
					}
				}
			}
		}

		// woocommerce-additional-variation-images (paid plugin)
		if ( empty($types) || in_array( 'variation_add', $types ) ) {
			if (!empty($variation_ids) && class_exists('WC_Additional_Variation_Images')) {
				$variation_ids = array_map( 'absint', $variation_ids );
				foreach( $variation_ids as $id ) {
					if ((int)$id > 0) {
						// $id is variation_id
						$ids = get_post_meta( (int)$id, '_wc_additional_variation_images', true );
						if ($ids) {
							foreach( explode( ',', $ids ) as $attach_id ) {
								$img = wp_get_attachment_image_src( $attach_id, 'single-post-thumbnail' );
								if (isset($img[0])) {
									$all_images_woo[$attach_id] = $img[0];
									array_push( $find_in_set, $attach_id );
								}
							}
						}
					}
				}
			}
		}

		// AJAX-ZOOM variation images (only WOO / WP)
		if ( empty($types) || in_array( 'ajaxzoom_variation', $types ) ) {
			$images2d = $wpdb->get_results( "SELECT * 
				FROM `{$wpdb->prefix}ajaxzoom2dimages`
				WHERE id_product = '{$product_id}' 
				ORDER BY sort_order", ARRAY_A );

			foreach ($images2d as &$image) {
				$key = $image['id'].'_'.$image['id_product'];
				$all_images_az[$key] = self::uri() . '/ajaxzoom/pic/2d/' . $product_id . '/' . $image['image'];
			}
		}

		if ( !empty($all_images_woo) ) {
			foreach( $all_images_woo as $k => $v ) {
				$urli = parse_url($v);
				$pathi = pathinfo($urli['path']);
				$all_images_woo[$k] = array(
					'id_media' => (int)$k,
					'id_product' => (int)$product_id,
					'image_name' => $pathi['basename'],
					'path' => $urli['path'],
					'order' => $order++,
					'thumb' => $az_az_load.$urli['path'] . '&' . self::$thumb_img_preview_size
				);
			}
		}

		if ( !empty( $all_images_az ) ) {
			foreach( $all_images_az as $k => $v ) {
				$urli = parse_url( $v );
				$pathi = pathinfo( $urli['path'] );
				array_push( $find_in_set, $k );
				$all_images_woo[$k] = array(
					'id_media' => $k,
					'id_product' => (int)$product_id,
					'image_name' => $pathi['basename'],
					'path' => $urli['path'],
					'order' => $order++,
					'thumb' => $az_az_load.$urli['path'] . '&' . self::$thumb_img_preview_size
				);
			}
		}

		// Hotspots
		if ( !empty( $find_in_set ) ) {
			$rows = $wpdb->get_results('SELECT id_media, hotspots_active FROM `'.$wpdb->prefix.'ajaxzoomimagehotspots` 
				WHERE FIND_IN_SET(`id_media`, \''.implode(',', $find_in_set).'\')', ARRAY_A );

			if ( !empty( $rows ) ) {
				foreach ( $rows as &$row ) {
					if ( isset( $all_images_woo[$row['id_media']] ) ) {
						$all_images_woo[$row['id_media']]['hotspots'] = (int)$row['hotspots_active'];
					}
				}
			}
		}

		return $all_images_woo;
	}

	public static function backend_output() {
		global $post;
		if ( $post->filter == 'edit' ) {
			add_meta_box( 'ajaxzoom', '<span class="dashicons dashicons-image-rotate" style="margin-right: 10px"></span>' . __( 'AJAX-ZOOM: 360 Product Views', 'ajaxzoom' ), 'Ajaxzoom::meta_box', 'product', 'normal' );
			add_meta_box( 'ajaxzoomvideos', '<span class="dashicons dashicons-video-alt2" style="margin-right: 10px"></span>' . __( 'AJAX-ZOOM: Videos - YouTube, Vimeo, Dailymotion, MP4', 'ajaxzoom' ), 'Ajaxzoom::meta_box_video', 'product', 'normal' );
			add_meta_box( 'ajaxzoom2d', '<span class="dashicons dashicons-images-alt2" style="margin-right: 10px"></span>' . __( 'AJAX-ZOOM: Product Variation Images', 'ajaxzoom' ), 'Ajaxzoom::meta_box_2d', 'product', 'normal' );
			add_meta_box( 'ajaxzoomimghotspots', '<span class="dashicons dashicons-location" style="margin-right: 10px"></span>' . __( 'AJAX-ZOOM: Image Hotspots', 'ajaxzoom' ), 'Ajaxzoom::meta_box_images', 'product', 'normal' );
		}
	}

	public static function get_2d_images( $id_product ) {
		global $wpdb;

		$images = $wpdb->get_results( "SELECT * 
			FROM `{$wpdb->prefix}ajaxzoom2dimages`
			WHERE id_product = '{$id_product}' 
			ORDER BY sort_order", ARRAY_A );

		foreach ($images as &$image) {
			$thumb = self::uri().'/ajaxzoom/axZm/zoomLoad.php';
			$thumb .= '?qq=1&azImg=' . self::uri() . '/ajaxzoom/pic/2d/' . $id_product . '/' . $image['image'];
			$thumb .= '&' . self::$thumb_img_preview_size;
			$image['thumb'] = $thumb;
			$image['id'] = $image['id'];
			$image['image_name'] = $image['image'];
			$image['path'] = self::uri() . '/ajaxzoom/pic/2d/' . $id_product . '/' . $image['image'];
		}

		return $images;
	}

	public static function get_videos($id_product)
	{
		global $wpdb;

		$r = array();
		$sql = $wpdb->get_results('SELECT * 
			FROM `'.$wpdb->prefix.'ajaxzoomvideo` 
			WHERE id_product = '.(int)$id_product.' 
			ORDER BY sort_order ASC, id_video ASC
		', ARRAY_A);

		$i = 1;
		foreach ($sql as $row) {
			$row['order'] = $i++;
			$r[$row['id_video']] = $row;
		}

		return $r;
	}

	public static function theme_settings( $ajaxzoom_config ) {
		$ajaxzoom_themesettings = array();

		if (isset($ajaxzoom_config['AJAXZOOM_THEMESETTINGS']) && $ajaxzoom_config['AJAXZOOM_THEMESETTINGS'] != '') {
			if (is_array($ajaxzoom_config['AJAXZOOM_THEMESETTINGS'])) {
				return $ajaxzoom_config['AJAXZOOM_THEMESETTINGS'];
			}

			$ajaxzoom_config['AJAXZOOM_THEMESETTINGS'] = unserialize($ajaxzoom_config['AJAXZOOM_THEMESETTINGS']);

			if (isset($ajaxzoom_config['AJAXZOOM_THEMESETTINGS'])
				&& !empty($ajaxzoom_config['AJAXZOOM_THEMESETTINGS'])
				&& is_array($ajaxzoom_config['AJAXZOOM_THEMESETTINGS'])
			) {
				foreach ($ajaxzoom_config['AJAXZOOM_THEMESETTINGS'] as $k => $v) {
					if (isset($v['theme']) && $v['theme']) {
						$ajaxzoom_themesettings[strtolower($v['theme'])] = $v;
					}
				}

				$ajaxzoom_config['AJAXZOOM_THEMESETTINGS'] = $ajaxzoom_themesettings;
			}
		}

		return $ajaxzoom_themesettings;
	}

	public static function extend_theme_settings( $cfg, $ccfg ) {
		if ($ccfg && is_array($ccfg)) {
			foreach ($ccfg as $k => $v) {
				if (is_array($v)) {
					if (!isset($v['k']) || !isset($v['v'])) {
						continue;
					}

					$k = $v['k'];
					$v = $v['v'];
				}

				$k = strtoupper($k);
				$v = stripslashes($v);

				if (isset($cfg['AJAXZOOM_'.$k])) {
					$cfg['AJAXZOOM_'.$k] = $v;
				} elseif (isset($cfg['AZ_'.$k])) {
					$cfg['AZ_'.$k] = $v;
				} elseif (isset($cfg['AJAXZOOM_MOZP_'.$k])) {
					$cfg['AJAXZOOM_MOZP_'.$k] = $v;
				}
			}
		}

		return $cfg;
	}

	public static function get_wpml_convert_map( $product_id ) {
		$variations_ids = self::get_variations_ids( $product_id );
		$variations_wpml_map = array();

		foreach ( $variations_ids as $variation_id ) {
			$variation_id_c = self::convert_wpml_id($variation_id);
			$variations_wpml_map[$variation_id] = $variation_id_c;
		}

		return $variations_wpml_map;
	}

	public static function convert_wpml_ids($arr = array()) {
		$ret = array();

		if ( is_string($arr) ) {
			$arr = implode(',', $arr);
		}

		foreach ($arr as $k => $v) {
			array_push( $ret, self::convert_wpml_id($v));
		}

		return $ret;
	}

	public static function convert_wpml_id($id = null, $def = false) {
		global $wp_filter;

		if ( !$def 
			&& ( isset( $wp_filter['wpml_object_id'] ) || function_exists('icl_object_id') )
			&& get_option( 'ajaxzoom_wpmltranslateids' ) == 'true'
		) {
			global $sitepress;

			if (!$sitepress) {
				return self::convert_wpml_id($id, true);
			}

			if ( isset( $wp_filter['wpml_object_id'] ) ) {
				return apply_filters(
					'wpml_object_id',
					$id ? $id : get_the_ID(),
					'product',
					true,
					$sitepress->get_default_language()
				);
			} else {
				return icl_object_id( $id ? $id : get_the_ID(), 'product', true, $sitepress->get_default_language() );
			}
		} elseif ($id) {
			return (int)$id;
		} else {
			global $product;
			return $product->get_id();
		}
	}

	public static function display_example_32($op = array()) {
		global $product, $wpdb;
		$is_iframe = self::is_iframe();

		if ( !$is_iframe && (self::$frontend_ouput === true || ! $product || ! self::show_az()) ) {
			return;
		}

		$ajaxzoom_config = self::config(); // with prefix
		self::init_az_mouseover_settings(); // self::$mouseover_settings is class

		self::$frontend_ouput = true;

		if ( !$is_iframe ) {
			$ajaxzoom_themeName = self::get_theme_name();
			$ajaxzoom_themeName = strtolower($ajaxzoom_themeName);
			$ajaxzoom_themesettings = self::theme_settings($ajaxzoom_config);

			if ($ajaxzoom_themeName
				&& isset($ajaxzoom_themesettings[$ajaxzoom_themeName])
				&& $ajaxzoom_themesettings[$ajaxzoom_themeName]['act']
			) {
				$ajaxzoom_themesettings = $ajaxzoom_themesettings[$ajaxzoom_themeName];

				if ($ajaxzoom_themesettings['atoo']) {
					$ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER'] = $ajaxzoom_themesettings['atoo'];
				}

				if ($ajaxzoom_themesettings['atoocss']) {
					$ajaxzoom_config['AJAXZOOM_APPENDTOCONTCSS'] = $ajaxzoom_themesettings['atoocss'];
				}

				if ($ajaxzoom_themesettings['zoomw']) {
					$ajaxzoom_config['AJAXZOOM_MOZP_ZOOMWIDTH'] = $ajaxzoom_themesettings['zoomw'];
				}

				if ($ajaxzoom_themesettings['zoomh']) {
					$ajaxzoom_config['AJAXZOOM_MOZP_ZOOMHEIGHT'] = $ajaxzoom_themesettings['zoomh'];
				}

				if (isset($ajaxzoom_themesettings['opt']) && is_array($ajaxzoom_themesettings['opt'])) {
					$ajaxzoom_config = self::extend_theme_settings($ajaxzoom_config, $ajaxzoom_themesettings['opt']);
				}
			}
		} else {
			$ajaxzoom_config['AJAXZOOM_FULLSCREENAPI'] = 'true';
		}

		$axZmPath = self::uri() . '/ajaxzoom/axZm/';

		$ajaxzoom_imagesJSON = '[]';
		$ajaxzoom_images360JSON = '[]';
		$variations_360_json = '$.axZm_psh.ajaxzoom_variations_360 = {};';
		$variations_2d_json = '{};';
		$variations_2d_hotspots = '{}';
		$ajaxzoom_videos_json = '[]';
		$ajaxzoom_has_video_html5 = false;
		$ajaxzoom_variable_product = false;

		// entire product
		if (isset($op['id_product'])
			&& $op['id_product'] != '0'
			&& !(isset($op['id_360']) || isset($op['id_image']) || isset($op['id_video']))
		) {

			if (!$product) {
				$product = self::get_product_variable((int)$op['id_product']);
			}

			if ($product && is_object($product) && $product->get_id() ) {
				$ajaxzoom_config['AJAXZOOM_AXZMMODE'] = 'true';
				$go_for_product = 1;
			}
		}

		if ( !$is_iframe || isset($go_for_product)) {
			if ( !isset($go_for_product) ) {
				$product_id = self::convert_wpml_id();
			} else {
				$product_id = $product->get_id();
			}

			$ajaxzoom_imagesJSON = self::images_json( $product_id );
			$ajaxzoom_images360JSON = self::images_360_json( $product_id );
			$variations_360_json = self::images_360_json_per_variation( $product_id );
			if ( !isset($go_for_product) && get_option( 'ajaxzoom_wpmltranslateids' ) == 'true' ) {
				$ajaxzoom_variations_wpml_map = self::get_wpml_convert_map( $product_id );
			}

			$variations_2d_json = self::images_json_per_variation( $product->get_id() );
			$variations_2d_hotspots = self::images_hotspot_json( $product_id, self::$frontend_2d_ids );
			$ajaxzoom_videos_json = self::videos_json( $product_id );
			$ajaxzoom_has_video_html5 = self::$product_has_video_html5;

			if (!isset($go_for_product)) {
				$ajaxzoom_variable_product = $product->is_type( 'variable' );
			}
		} else {
			if (isset($op['pid']) && (int)$op['pid'] > 0) {
				$product_id = (int)$op['pid'];
			}

			$ajaxzoom_config['AJAXZOOM_AXZMMODE'] = 'true';
			if (isset($op['id_360']) && $op['id_360'] != '0') {
				$ajaxzoom_images360JSON = self::images_360_json( false, false, (int)$op['id_360'] );
			} elseif (isset($op['id_image']) && $op['id_image'] != '0') {
				$ajaxzoom_imagesJSON = json_encode(self::get_images( $op['id_image'] ), JSON_FORCE_OBJECT);
			} elseif (isset($op['id_video']) && $op['id_video'] != '0') {
				$ajaxzoom_videos_json = self::video_json( (int)$op['id_video'] );
				$ajaxzoom_has_video_html5 = self::$product_has_video_html5;
			} else {
				die('Wrong parameters');
			}
		}

		if ( $is_iframe && isset($op['no_gallery']) && (int)$op['no_gallery'] === 1) {
			$ajaxzoom_config['AJAXZOOM_GALLERYDIVID'] = 'false';
		}

		// hook config
		$id_product = '';
		if (isset($product_id) && $product_id) {
			$id_product = $product_id;
		} elseif (self::$frontend_id_product) {
			$id_product = self::$frontend_id_product;
		}

		if ($id_product) {
			if ($is_iframe && isset($op['id_image']) && $op['id_image'] != '0') {
				$variations_2d_hotspots = self::images_hotspot_json( $id_product, self::$frontend_2d_ids );
			}

			$ajaxzoom_config = self::extend_product_individual_settings($ajaxzoom_config, $id_product);
		}

		if ($is_iframe) {
			$ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER'] = '#az_inner_body_iframe';
			$ajaxzoom_config['AJAXZOOM_HEIGHTRATIO'] = 5;
			$ajaxzoom_config['AJAXZOOM_HEIGHTRATIOONEIMG'] = 5;
			$ajaxzoom_config['AJAXZOOM_HEIGHTMAXWIDTHRATIO'] = 'false';
			$ajaxzoom_config['AJAXZOOM_MAXSIZEPRC'] = '1.0|auto|-30';
			$ajaxzoom_config['AJAXZOOM_AJAXZOOMOPENMODE'] = 'fullscreen';
			$ajaxzoom_config['AJAXZOOM_AJAXZOOMOPENMODETOUCH'] = 'fullscreen';
		}

		$ajaxzoom_init_param = self::$mouseover_settings->getInitJs(array(
			'cfg' => $ajaxzoom_config,
			'window' => 'window.',
			'holder_object' => '$.axZm_psh',
			'exclude_opt' => array(),
			'exclude_cat' => array(),
			'differ' => true,
			'min' => true
		));

		include_once self::dir() . 'templates/frontend/ajaxzoom.php';
	}

	public static function get_variations_ids( $product_id ) {
		$variations = array();
		$res = array();

		$args = apply_filters( 'woocommerce_ajax_admin_get_variations_args', array(
			'post_type' => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'posts_per_page' => 100,
			'paged' => 1,
			'orderby' => array( 'menu_order' => 'ASC', 'ID' => 'DESC' ),
			'post_parent' => $product_id
		), $product_id );

		$variations = get_posts( $args );
		foreach ( $variations as $variation ) {
			array_push( $res, $variation->ID );
		}

		return $res;
	}

	public static function get_variations( $product_id ) {

		$variations = array();

		$args = apply_filters( 'woocommerce_ajax_admin_get_variations_args', array(
			'post_type' => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'posts_per_page' => 100,
			'paged' => 1,
			'orderby' => array( 'menu_order' => 'ASC', 'ID' => 'DESC' ),
			'post_parent' => $product_id
		), $product_id );

		$variations = get_posts( $args );

		$loop = 0;

		if ( $variations ) {

			foreach ( $variations as $variation ) {
				$variation_id = absint( $variation->ID );
				$variation_meta = get_post_meta( $variation_id );
				$variation_data = array();
				$variation_fields = array(
					'_sku' => '',
					'_variation_description' => ''
				);

				foreach ( $variation_fields as $field => $value ) {
					$variation_data[ $field ] = isset( $variation_meta[ $field ][0] ) ? maybe_unserialize( $variation_meta[ $field ][0] ) : $value;
				}

				$variation_data = array_merge( $variation_data, wc_get_product_variation_attributes( $variation_id ) );

				$variation->data = $variation_data;
				$variation->attr = implode('-', wc_get_product_variation_attributes( $variation_id ));

				$loop++;
			}
		}

		$res = array();

		foreach ( $variations as $variation ) {
			$res[ $variation->ID ] = 'Variation #' . $variation->ID . ' ' . $variation->data['_sku'] . ' ' . $variation->attr;
		}

		return $res;
	}

	public static function get_arc_list() {
		$files = array();

		if ( $handle = opendir( self::dir() . 'zip/' ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( $entry != '.'
					&& $entry != '..'
					&& ( strtolower( substr( $entry, -3) ) == 'zip' || is_dir( self::dir() . 'zip/' . $entry ) ) 
				) {
					array_push( $files, $entry );
				}
			}

			closedir( $handle );
		}

		return $files;
	}

	public static function add_images_arc( $arcfile, $id_product, $id_360, $id_360set, $delete = '' ) {
		global $wpdb;

		set_time_limit( 0 );

		$path = self::dir() . 'zip/' . $arcfile;
		$dst = is_dir( $path ) ? $path : self::extract_arc( $path );

		// when extract zip archive return false
		if ( $dst == false ) {
			return false;
		}

		if ( !is_writable( $dst ) ) {
			@chmod( $dst, 0777 );
		}

		$data = self::get_folder_data( $dst );

		$name = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = ".(int)$id_360 )->name;

		$thumb = self::uri().'/ajaxzoom/axZm/zoomLoad.php';
		$thumb .= '?qq=1&azImg360=' . self::uri() . '/ajaxzoom/pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set;
		$thumb .= '&' . self::$thumb_img_preview_size;

		$sets = array( array(
			'name' => $name,
			'path' => $thumb,
			'id_360set' => $id_360set,
			'id_360' => $id_360,
			'status' => '1'
		) );

		$count_data_folders = count( $data['folders'] );

		$move = is_dir( $path ) ? false : true;

		if ( $count_data_folders == 0 ) { // files (360)
			self::copy_images( $id_product, $id_360, $id_360set, $dst, $move );
		} elseif ( $count_data_folders == 1 ) { // 1 folder (360)
			self::copy_images( $id_product, $id_360, $id_360set, $dst.'/'.$data['folders'][0], $move );
		} else {
			// 3d
			self::copy_images( $id_product, $id_360, $id_360set, $dst.'/'.$data['folders'][0], $move );

			// checkr - $i <= $count_data_folders
			for ( $i = 1; $i < $count_data_folders; $i++ ) {
				$wpdb->query("INSERT INTO `{$wpdb->prefix}ajaxzoom360set` (id_360, sort_order) VALUES('{$id_360}', 0)");

				$id_360set = $wpdb->insert_id;

				self::copy_images( $id_product, $id_360, $id_360set, $dst . '/' . $data['folders'][$i], $move );

				$thumb = self::uri() . '/ajaxzoom/axZm/zoomLoad.php';
				$thumb .= '?qq=1&azImg360=' . self::uri() . '/ajaxzoom/pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set;
				$thumb .= '&' . self::$thumb_img_preview_size;

				$sets[] = array(
					'name' => $name,
					'path' => $thumb,
					'id_360set' => $id_360set,
					'id_360' => $id_360,
					'status' => '1'
				);
			}
		}

		// delete temp directory which was created when zip extracted
		if ( !is_dir( $path ) ) {
			self::delete_directory( $dst );
		}

		// delete the sourece file (zip/dir) if checkbox is checked
		if ( $delete == 'true' ) {
			if ( is_dir( $path ) ) {
				self::delete_directory( $dst );
			} else {
				unlink( $path );
			}
		}

		return $sets;
	}

	public static function copy_images( $id_product, $id_360, $id_360set, $path, $move ) {
		global $ajaxzoom_files_permissions;

		if ( !$id_360 && !$id_360set ) { // useless code to validate
			return;
		}

		$files = self::get_files_from_folder( $path );
		$folder = self::create_product_360_folder( $id_product, $id_360set );

		foreach ( $files as $file ) {
			$name = $id_product . '_' . $id_360set . '_' . self::img_name_filter( $file );
			$dst = $folder.'/'.$name;

			if ( $move ) {
				if ( !(@rename( $path.'/'.$file, $dst )) ) {
					copy( $path.'/'.$file, $dst );
				}
			} else {
				copy( $path . '/' . $file, $dst );
			}

			chmod( $dst, octdec( $ajaxzoom_files_permissions ) );
		}
	}

	public static function img_name_filter( $filename ) {
		$filename = preg_replace( '/[^A-Za-z0-9_\.-]/', '-', $filename );
		return $filename;
	}

	public static function get_files_from_folder( $path ) {

		$files = array();

		if ( $handle = opendir( $path ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ($entry != '__MACOSX' && substr( $entry, 0, 1 ) != '.') {
					$files[] = $entry;
				}
			}

			closedir( $handle );
		}

		return $files;
	}

	public static function create_product_360_folder( $id_product, $id_360set ) {
		global $ajaxzoom_folder_permissions;

		$id_product = (int)$id_product;
		$id_360set = (int)$id_360set;
		$id_360 = self::get_set_parent( $id_360set );

		$img_dir = self::dir() . 'pic/360/';

		if ( !file_exists( $img_dir . $id_product ) ) {
			mkdir( $img_dir . $id_product, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $img_dir . $id_product, octdec( $ajaxzoom_folder_permissions ) );
		}

		if ( !file_exists( $img_dir . $id_product . '/' . $id_360 ) ) {
			mkdir( $img_dir . $id_product . '/' . $id_360, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $img_dir . $id_product . '/' . $id_360, octdec( $ajaxzoom_folder_permissions ) );
		}

		$folder = $img_dir . $id_product . '/' . $id_360 . '/' . $id_360set;

		if ( !file_exists( $folder ) ) {
			mkdir( $folder, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $folder, octdec( $ajaxzoom_folder_permissions ) );
		} else {
			@chmod( $folder, octdec( $ajaxzoom_folder_permissions ) );
		}

		return $folder;
	}

	public static function get_folder_data( $path ) {
		$files = array();
		$folders = array();

		if ( $handle = opendir( $path ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( $entry != '__MACOSX' && substr( $entry, 0, 1 ) != '.') {
					if ( is_dir( $path . '/' . $entry ) ) {
						array_push( $folders, $entry );
					} else {
						array_push( $files, $entry );
					}
				}
			}

			closedir( $handle );
		}

		sort( $folders );
		sort( $files );

		return array(
			'folders' => $folders,
			'files' => $files
		);
	}

	public static function extract_arc( $file ) {
		global $ajaxzoom_folder_permissions;
		$zip = new ZipArchive;
		$res = $zip->open( $file );

		if ( $res === true ) {
			$folder = uniqid( getmypid() );
			$path = self::dir() . 'pic/tmp/' . $folder;
			mkdir( $path, octdec( $ajaxzoom_folder_permissions ) );
			@chmod( $path, octdec( $ajaxzoom_folder_permissions ) );
			$zip->extractTo( $path );
			$zip->close();
			return $path;
		} else {
			return false;
		}
	}

	public static function get_crop_json( $id_360 ) {
		global $wpdb;

		if ( $crop = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . (int)$id_360 )->crop ) {
			if ( !empty( $crop ) ) {
				return stripslashes( $crop );
			}
		}

		return '[]';
	}

	public static function set_crop_json( $id_360, $json ) {
		global $wpdb;

		$aff_rows = $wpdb->query( 
			$wpdb->prepare("UPDATE `{$wpdb->prefix}ajaxzoom360` SET crop = %s WHERE id_360 = %d", $json, $id_360 )
		);

		return json_encode( array('status' => $aff_rows ) );
	}

	public static function get_hotspot_json( $id_360 ) {
		global $wpdb;

		$check_hotspot_field = $wpdb->get_col( "DESC `{$wpdb->prefix}ajaxzoom360`" );

		if ( !in_array('hotspot', $check_hotspot_field) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( "ALTER TABLE `{$wpdb->prefix}ajaxzoom360` ADD `hotspots` TEXT NOT NULL" );
		}

		if ( $hotspot = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . (int)$id_360 )->hotspots ) {
			if ( !empty( $hotspot ) ) {
				return stripslashes( $hotspot );
			}
		}

		return '{}';
	}

	public static function set_hotspot_json( $id_360, $json ) {
		global $wpdb;

		$aff_rows = $wpdb->query( 
			$wpdb->prepare("UPDATE `{$wpdb->prefix}ajaxzoom360` SET hotspots = %s WHERE id_360 = %d", $json, $id_360 )
		);

		return json_encode( array('status' => $aff_rows ) );
	}

	public static function get_sets( $id_product ) {
		global $wpdb;

		$sets = $wpdb->get_results( "SELECT s.*, g.name, g.id_360, g.status, g.sort_order 
			FROM `{$wpdb->prefix}ajaxzoom360set` s, `{$wpdb->prefix}ajaxzoom360` g 
			WHERE g.id_360 = s.id_360 AND g.id_product = '{$id_product}' 
			ORDER BY g.sort_order, g.id_360, s.id_360set", ARRAY_A ); // ORDER BY g.name, s.sort_order", ARRAY_A );

		foreach ( $sets as &$set ) {
			$thumb = self::uri() . '/ajaxzoom/axZm/zoomLoad.php?';
			$thumb .= '?qq=1&azImg360=' . self::uri() . '/ajaxzoom/pic/360/' . $id_product . '/' . $set['id_360'] . '/' . $set['id_360set'];
			$thumb .= '&' . self::$thumb_img_preview_size;

			$set_images = self::get_360_images( $id_product, $set['id_360set'] );

			if ( file_exists( self::dir() . 'pic/360/' . $id_product . '/' . $set['id_360'] . '/' . $set['id_360set'] ) && count( $set_images ) > 0 ) {
				$set['path'] = $thumb;
			} else {
				$set['path'] = self::uri() . '/ajaxzoom/assets/img/no_image-100x100.jpg';
			}
		}

		return $sets;
	}

	public static function get_set_parent( $id_360set ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360set` WHERE id_360set = " . (int)$id_360set )->id_360;
	}

	public static function get_set_product( $id_360 ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . (int)$id_360 )->id_product;
	}

	public static function get_groups( $product_id ) {
		global $wpdb;

		return $wpdb->get_results( "SELECT g.*, COUNT(g.id_360) AS qty, s.id_360set 
			FROM `{$wpdb->prefix}ajaxzoom360` g 
			LEFT JOIN `{$wpdb->prefix}ajaxzoom360set` s ON g.id_360 = s.id_360 
			WHERE g.id_product = '{$product_id}' 
			GROUP BY g.sort_order, g.id_360", ARRAY_A );
	}

	public static function is_active( $product_id ) {
		global $wpdb;

		return !$wpdb->get_results( "SELECT * 
			FROM `{$wpdb->prefix}ajaxzoomproducts` 
			WHERE id_product = '{$product_id}'", ARRAY_A );
	}

	public static function set_360_status( $id_360, $status ) {
		global $wpdb;

		$wpdb->query( "UPDATE `{$wpdb->prefix}ajaxzoom360` SET status = '{$status}' WHERE id_360 = '{$id_360}'" );
	}

	public static function delete_set( $id_360set ) {
		global $wpdb;

		$id_360 = self::get_set_parent( $id_360set );
		$id_product = self::get_set_product( $id_360 );

		// clear AZ cache
		$images = self::get_360_images( $id_product, $id_360set );

		foreach ( $images as $image ) {
			self::delete_image_az_cache( $image['filename'] );
		}

		$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoom360set` WHERE id_360set = " . $id_360set );

		$path = self::dir() . 'pic/360/' . $id_product . '/' . $id_360;

		$tmp = $wpdb->query( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360set` WHERE id_360 = " . $id_360 );
		if ( !$tmp ) {
			$wpdb->query( "DELETE FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . $id_360 );
		} else {
			$path .= '/' . $id_360set;
		}

		self::delete_directory($path);
	}

	public static function delete_directory( $dirname, $delete_self = true ) {
		$dirname = rtrim( $dirname, '/' ) . '/';

		if ( !(strstr($dirname, '/ajaxzoom/') || strstr($dirname, '\\ajaxzoom\\') ) ) {
			return false;
		}

		if ( file_exists( $dirname ) ) {
			@chmod( $dirname, 0777 ); // NT ?
			if ( $files = scandir( $dirname ) ) {
				foreach ( $files as $file ) {
					if ( $file != '.' && $file != '..' && $file != '.svn' ) {
						if ( is_dir( $dirname . $file ) ) {
							self::delete_directory( $dirname . $file, true );
						} elseif ( file_exists( $dirname . $file ) ) {
							@chmod( $dirname . $file, 0777 ); // NT ?
							@unlink( $dirname . $file );
						}
					}
				}

				if ( $delete_self && file_exists( $dirname ) ) {
					if ( !(@rmdir( $dirname) ) ) {
						@chmod( $dirname, 0777 ); // NT ?
						return false;
					}
				}

				return true;
			}
		}
		return false;
	}

	public static function delete_image_az_cache( $file ) {
		// Validator issue
		$axzmh = '';
		$zoom = array();

		// Include all classes
		if ( file_exists( self::dir() . 'axZm/zoomInc.inc.php' ) ) {
			include_once self::dir() . 'axZm/zoomInc.inc.php';
		} else {
			self::az_write_log('zoomInc.inc.php not present.');
			return;
		}

		if ( !Ajaxzoom::$axzmh ) {
			Ajaxzoom::$axzmh = $axzmh; // cannot change name as it come from external app (include above)
			Ajaxzoom::$zoom = $zoom;
		}

		// What to delete
		$arr_del = array( 'In' => true, 'Th' => true, 'tC' => true, 'mO' => true, 'Ti' => true );

		// Remove all cache
		if ( is_object( Ajaxzoom::$axzmh ) && $file) {
			self::az_write_log('Start deleting cache for: ' . $file);
			Ajaxzoom::$axzmh->removeAxZm( Ajaxzoom::$zoom, $file, $arr_del, false );
			self::az_write_log('Finish deleting cache for: ' . $file);
		} elseif ($file) {
			self::az_write_log('Error deleting cache for: ' . $file . '; axZmH class not initialized;');
		} elseif (!$file) {
			self::az_write_log('Passed empty file name for deleting cache!');
		}
	}

	public static function get_360_images( $id_product, $id_360set = '' ) {
		$files = array();
		$id_360 = Ajaxzoom::get_set_parent( $id_360set );
		$dir = self::dir() . 'pic/360/' . $id_product . '/' . $id_360 . '/' . $id_360set;

		if ( file_exists( $dir ) && $handle = opendir( $dir ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( $entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '__MACOSX' ) {
					$files[] = $entry;
				}
			}

			closedir( $handle );
		}

		sort( $files );

		$res = array();

		foreach ( $files as $entry ) {
			$tmp = explode( '.', $entry );
			$ext = end( $tmp );
			$name = preg_replace( '|\.' . $ext . '$|', '', $entry );

			$thumb = self::uri() . '/ajaxzoom/axZm/zoomLoad.php?azImg=' . self::uri() . '/ajaxzoom/pic/360/'
			. $id_product . '/' . $id_360 . '/' . $id_360set . '/' . $entry 
			. '&' . self::$thumb_img_preview_size;

			$res[] = array(
				'thumb' => $thumb,
				'filename' => $entry,
				'id' => $name,
				'ext' => $ext
			);
		}

		return $res;
	}

	public static function images_360_json_per_variation( $product_id ) {
		$js = '';
		$variations_ids = self::get_variations_ids( $product_id );

		foreach ( $variations_ids as $variation_id ) {
			$variation_id_c = self::convert_wpml_id( $variation_id );

			$json = self::images_360_json( $product_id, $variation_id_c );
			if ( $json != '{}' ) {
				$js .= "$.axZm_psh.ajaxzoom_variations_360[$variation_id_c] = " . $json . ";\n";
			}
		}

		return $js;
	}

	public static function images_json_per_variation( $product_id, $filter_id = array()) {
		global $wpdb, $product;
		$variation_ids = array();
		$find_in_set = array();
		$all_images_woo = array();
		$order = 1;

		if ($product->is_type( 'variable' )) {
			$variations = $product->get_available_variations();
		} else {
			return '$.axZm_psh.ajaxzoom_variations_2d = [];';
		}

		if (!empty($variations)) {
			foreach ( $variations as $variation ) {
				$imgsrc = '';
				if (isset($variation['image_id'])) {
					$image_id = $variation['image_id'];
				} else {
					$image_id = (int)$variation['variation_id'];
				}

				$variation_id = (int)$variation['variation_id'];
				array_push( $variation_ids, $variation_id );
				array_push( $find_in_set, $image_id );

				// Single variation images
				if ((int)self::woo_version() >= 3) {
					if (isset($variation['image_src'])) {
						$imgsrc = $variation['image_src'];
					} elseif (isset($variation['image'])
						&& is_array($variation['image'])
						&& (isset($variation['image']['full_src']) || isset($variation['image']['url']))
					) {
						if (isset($variation['image']['full_src'])) {
							$imgsrc = $variation['image']['full_src'];
						} else {
							$imgsrc = $variation['image']['url'];
						}
					}
				} elseif (isset($variation['image_link'])) {
					$imgsrc = $variation['image_link'];
				} else {
					continue;
				}

				if ($imgsrc) {
					if ( !isset($all_images_woo[$variation_id]) ) {
						$all_images_woo[$variation_id] = array();
					}

					$urli = parse_url($imgsrc);

					$all_images_woo[$variation_id][$image_id] = array(
						'img' => $urli['path'],
						'title' => '',
						'order' => $order++
					);
				}
			}

			// woocommerce-additional-variation-images (paid plugin)
			if ( !empty($variation_ids) && class_exists('WC_Additional_Variation_Images') ) {
				foreach( $variation_ids as $variation_id ) {
					$ids = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
					if ( $ids ) {
						foreach( explode( ',', $ids ) as $attach_id ) {
							if ( (int)$attach_id > 0 ) {
								$img = wp_get_attachment_image_src( (int)$attach_id, 'single-post-thumbnail' );
								if ( isset( $img[0] ) ) {
									if ( !isset($all_images_woo[$variation_id]) ) {
										$all_images_woo[$variation_id] = array();
									}

									$urli = parse_url($img[0]);
									$all_images_woo[$variation_id][$attach_id] = array(
										'img' => $urli['path'],
										'title' => '',
										'order' => $order++
									);

									array_push( $find_in_set, $attach_id );
								}
							}
						}
					}
				}
			}

			// AJAX-ZOOM variation images (only WOO / WP)
			$images2d = $wpdb->get_results( "SELECT * 
				FROM `{$wpdb->prefix}ajaxzoom2dimages`
				WHERE id_product = '{$product_id}' AND status = 1
				ORDER BY sort_order", ARRAY_A );

			if ( !empty($images2d) ) {
				foreach ($images2d as &$image) {
					$key = $image['id'].'_'.$image['id_product'];
					array_push( $find_in_set, $key );

					$img = self::uri() . '/ajaxzoom/pic/2d/' . $product_id . '/' . $image['image'];
					$variation_field = $image['variations'];
					if (!$variation_field) {
						$variation_field = implode(',', $variation_ids);
					}

					foreach (explode(',', $variation_field) as $variation_id) {
						if ( !isset($all_images_woo[$variation_id]) ) {
							$all_images_woo[$variation_id] = array();
						}

						$all_images_woo[$variation_id][$key] = array(
							'img' => $img,
							'title' => '',
							'order' => $order++
						);
					}
				}
			}
		}

		if ( !empty($find_in_set) ) {
			self::$frontend_2d_ids = array_merge( self::$frontend_2d_ids, $find_in_set );
		}

		return json_encode($all_images_woo, JSON_FORCE_OBJECT);
	}

	public static function images_hotspot_json( $product_id, $find_in_set = array() ) {
		global $wpdb;
		$hotspots = array();

		/*
		$product_id = (int)$product_id;

		array_push( $find_in_set, (string)$product_id );
		$gal_ids = get_post_meta( $product_id, '_product_image_gallery', true );

		if ( !empty($gal_ids) ) {
			$gal_ids = explode( ',', $gal_ids );

			foreach ($gal_ids as $id) {
				array_push( $find_in_set, (string)$id );
			}
		}
		*/

		if ( !empty( $find_in_set ) ) {
			$rows = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'ajaxzoomimagehotspots` 
				WHERE 
				id_product='.$product_id.' 
				AND hotspots_active=1 
				AND FIND_IN_SET(`id_media`, \''.implode(',', $find_in_set).'\')', ARRAY_A );

			if ( !empty( $rows ) ) {
				foreach ( $rows as $row ) {
					$hotspots[(string)$row['id_media']] = trim( preg_replace( '/\s+/', ' ', stripslashes( $row['hotspots'] ) ) );
				}
			}
		}

		return json_encode($hotspots, JSON_FORCE_OBJECT);
	}

	public static function video_json($id_video, $tojson = true) {
		global $wpdb;
		$db_prefix = $wpdb->prefix;

		$video = $wpdb->get_row('SELECT * 
			FROM `'.$db_prefix.'ajaxzoomvideo` 
			WHERE id_video = '.(int)$id_video.' 
		', ARRAY_A);

		if ($video['id_product']) {
			if ((int)$video['id_product'] > 0) {
				self::$frontend_id_product = (int)$video['id_product'];
			}

			return self::videos_json((int)$video['id_product'], $tojson, array((int)$video['id_video']));
		} else {
			$ret = array();
			if ($tojson == true) {
				$ret = json_encode($ret, true);
			}

			return $ret;
		}
	}

	public static function videos_json($product_id, $tojson = true, $filter_id = array()) {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$staus_sql = 'AND status=1';

		if (!empty($filter_id)) {
			$staus_sql = '';
		}

		$videos = array();
		$sql = $wpdb->get_results('SELECT * 
			FROM `'.$db_prefix.'ajaxzoomvideo` 
			WHERE id_product = '.(int)$product_id.' 
				'.$staus_sql.'
			ORDER BY sort_order ASC, id_video ASC
		', ARRAY_A);

		foreach ($sql as $row) {
			$videos[$row['id_video']] = $row;
		}

		$ret = array();
		$i = 0;

		$lang = substr(get_bloginfo( 'language' ), 0, 2);
		if ($lang) {
			$lang = strtolower($lang);
		}

		foreach ($videos as $k => $v) {
			if (!empty($filter_id) && !in_array((int)$v['id_video'], $filter_id)) {
				continue;
			}

			$i++;
			$uid = $videos[$k]['uid'];
			$data = (array)json_decode($videos[$k]['data']);

			if ($lang && !empty($data) && is_array($data) && isset($data['uid']) && is_object($data['uid'])) {
				if (!empty($data['uid']->{$lang}) && trim($data['uid']->{$lang}) != '') {
					$uid = trim($data['uid']->{$lang});
				}
			}

			if ($videos[$k]['type'] == 'videojs') {
				self::$product_has_video_html5 = true;
			}

			$ret[$i] = array(
				'key' => $uid,
				'id_video' => $videos[$k]['id_video'],
				'settings' => (array)json_decode($videos[$k]['settings']),
				'combinations' => (!$videos[$k]['combinations'] || $videos[$k]['combinations'] == '[]' || !empty($filter_id)) ? array() : explode(',', $videos[$k]['combinations']),
				'type' => $videos[$k]['type'],
				'order' => $i
			);
		}

		if ($tojson == true) {
			$ret = json_encode($ret, true);
		}

		return $ret;
	}

	public static function images_360_json( $product_id, $combination_id = false, $id_360 = false ) {

		$tmp = array();
		$order = 1;

		if ($id_360) {
			$id_360 = (int)$id_360;
			$sets_groups = self::get_sets_group( $id_360 );
		} else {
			// returns array
			$variations_all_ids = self::get_variations_ids( $product_id );
			if ( !empty( $variations_all_ids ) && get_option( 'ajaxzoom_wpmltranslateids' ) == 'true' ) {
				$variations_all_ids = self::convert_wpml_ids( $variations_all_ids );
			}

			$sets_groups = self::get_sets_groups( $product_id );
		}

		foreach ( $sets_groups as $group ) {
			if (!$id_360) {
				if ( $combination_id ) {
					$combinations = explode( ',', $group['combinations'] );
					if ( count( $combinations ) > 0 && !in_array( $combination_id, $combinations ) ) {
						continue;
					}
				} else {
					// by default show 360 with checked/unchecked all variations
					if ( !( $group['combinations'] == '' || $group['combinations'] == implode(',', $variations_all_ids) ) ) {
						continue;
					}
				}

				if ( $group['status'] == 0 ) {
					continue;
				}
			} else {
				$product_id = $group['id_product'];
				if ((int)$product_id > 0) {
					self::$frontend_id_product = $product_id;
				}
			}

			$settings = self::prepare_settings( $group['settings'] );

			if ( !empty( $settings ) ) {
				$settings = ", $settings";
			}

			if ( $group['qty'] > 0 ) {

				if ( !isset( $group['hotspots'] ) ) {
					$group['hotspots'] = '';
				}

				$crop = empty( $group['crop'] ) ? '[]' : trim( preg_replace( '/\s+/', ' ', stripslashes( $group['crop'] ) ) );
				$hotspots = empty( $group['hotspots'] ) ? '{}' : trim( preg_replace( '/\s+/', ' ', stripslashes( $group['hotspots'] ) ) );

				$jsonStr = '';

				if ( $group['qty'] == 1 ) {
					$jsonStr = '"' . $group['id_360'] . '"' . ': {"order": '.$order++.', "path": "' . self::uri() . "/ajaxzoom/pic/360/" . $product_id . "/" . $group['id_360'] . "/" . $group['id_360set'] . '"' . $settings . ', "combinations": [' . $group['combinations'] . ']';
				} else {
					$jsonStr = '"' . $group['id_360'] . '"' . ': {"order": '.$order++.', "path": "' . self::uri() . "/ajaxzoom/pic/360/" . $product_id . "/" . $group['id_360'] . '"' . $settings . ', "combinations": [' . $group['combinations'] . ']';
				}

				if ( $crop && $crop != '[]' ) {
					$jsonStr .= ', "crop": ' . $crop;
				}

				if ( $hotspots && $hotspots != '{}' ) {
					$jsonStr .= ', "hotspotFilePath": ' . $hotspots;
				}

				$jsonStr .= '}';

				$tmp[] = $jsonStr;
			}
		}

		return '{' . implode( ',', $tmp ) . '}';
	}

	public static function get_sets_groups( $product_id ) {
		global $wpdb;

		return $wpdb->get_results( "SELECT g.*, COUNT(g.id_360) AS qty, s.id_360set, g.sort_order 
			FROM `{$wpdb->prefix}ajaxzoom360` g 
			LEFT JOIN `{$wpdb->prefix}ajaxzoom360set` s ON g.id_360 = s.id_360 
			WHERE g.id_product = '{$product_id}' 
			GROUP BY g.sort_order, g.id_360", ARRAY_A );
	}

	public static function get_sets_group( $id_360 ) {
		global $wpdb;

		return $wpdb->get_results( "SELECT g.*, COUNT(g.id_360) AS qty, s.id_360set 
			FROM `{$wpdb->prefix}ajaxzoom360` g 
			LEFT JOIN `{$wpdb->prefix}ajaxzoom360set` s ON g.id_360 = s.id_360 
			WHERE g.id_360 = '{$id_360}' 
			GROUP BY g.sort_order, g.id_360", ARRAY_A );
	}

	public static function prepare_settings( $str ) {

		$res = array();

		$settings = (array)json_decode( $str );
		foreach ( $settings as $key => $value ) {
			if ( $value == 'false' || $value == 'true' || $value == 'null' || is_numeric( $value ) ||  substr( $value, 0, 1 ) == '{' ||  substr( $value, 0, 1 ) == '[' ) {
				$res[] = '"' . $key . '": ' . $value;
			} else {
				$res[] = '"' . $key . '": "' . $value . '"';
			}
		}

		return implode( ', ', $res );
	}

	public static function get_images( $arr ) {
		$images_woo = array();
		$order = 1;
		if ( !is_array($arr) ) {
			$arr = explode( ',', $arr );
		}

		if ( !empty($arr) ) {
			foreach ( $arr as $id ) {
				$id = trim($id);
				$result = self::get_image($id);

				if ( !empty($result) ) {
					$result[key($result)]['order'] = $order++;
					$images_woo = self::add_to_key_val_array($images_woo, $result);
				}
			}
		}

		return $images_woo;
	}

	public static function get_image( $id_image ) {
		global $wpdb;

		$picture = array();
		$find_in_set = array();

		if ( strstr((string)$id_image, '_') ) {
			$arr_img_id = split('_', $id_image);
			if ( count($arr_img_id) == 2 && (int)$arr_img_id[0] > 0 && (int)$arr_img_id[1] > 0 ) {
				$key = (int)$arr_img_id[0] . '_' . (int)$arr_img_id[1];
				$id = (int)$arr_img_id[0];
				$product_id = (int)$arr_img_id[1];

				$image = $wpdb->get_row( "SELECT * 
					FROM `{$wpdb->prefix}ajaxzoom2dimages`
					WHERE id_product = '{$product_id}' AND id = '{$id}'
					ORDER BY sort_order", ARRAY_A );

				if (is_array($image) && $image['id']) {
					array_push( $find_in_set, $key );
					if (!self::$frontend_id_product && $product_id > 0) {
						self::$frontend_id_product = $product_id;
					}

					$img = self::uri() . '/ajaxzoom/pic/2d/' . $product_id . '/' . $image['image'];
					$picture[$key] = array(
						'img' => $img,
						'title' => ''
					);
				}
			}
		} else {
			$main_img_info = wp_get_attachment_image_src( (int)$id_image, 'single-post-thumbnail' );

			if ( isset( $main_img_info[0] ) && $main_img_info[0] ) {
				$urli = parse_url($main_img_info[0]);
				array_push( $find_in_set, (int)$id_image );
				$picture[(int)$id_image] = array(
					'img' => $urli['path'],
					'title' => ''
				);
			}
		}

		if ( !empty($find_in_set) ) {
			self::$frontend_2d_ids = array_merge( self::$frontend_2d_ids, $find_in_set );
		}

		return $picture;
	}

	public static function get_product_variable($product_id) {
		if (get_post((int)$product_id)) {
			return @new WC_Product_Variable( $product_id );
		} else {
			return null;
		}
	}

	public static function images_json( $product_id ) {
		global $wpdb, $product;
		$find_in_set = array();
		$order = 1;

		if ( !$product ) {
			$product = self::get_product_variable($product_id);
		}

		if ( !$product ) {
			return json_encode(array(), JSON_FORCE_OBJECT);
		}

		$all_images_woo = array();
		$post_thumbnail_id = get_post_thumbnail_id( (int)$product_id );
		$main_img_info = wp_get_attachment_image_src( $post_thumbnail_id, 'single-post-thumbnail' );

		if ( isset( $main_img_info[0] ) && $main_img_info[0] ) {
			$urli = parse_url($main_img_info[0]);
			array_push( $find_in_set, $post_thumbnail_id );
			$all_images_woo[$post_thumbnail_id] = array(
				'img' => $urli['path'],
				'title' => '',
				'order' => $order++
			);
		}

		// Gallery images
		$gal_ids = get_post_meta( $product_id, '_product_image_gallery', true );

		if (!empty($gal_ids)) {
			$gal_ids = explode( ',', $gal_ids );
			$gal_ids = array_map( 'absint', $gal_ids );
			foreach( $gal_ids as $attach_id ) {
				$img = wp_get_attachment_image_src( $attach_id, 'single-post-thumbnail' );
				if (isset($img[0])) {
					$urli = parse_url($img[0]);
					array_push( $find_in_set, $attach_id );
					$all_images_woo[$attach_id] = array(
						'img' => $urli['path'],
						'title' => '',
						'order' => $order++
					);
				}
			}
		}

		// Add AJAX-ZOOM uploaded images for product if it is not variable
		if ( !$product->is_type( 'variable' ) ) {
			$images2d = $wpdb->get_results( "SELECT * 
				FROM `{$wpdb->prefix}ajaxzoom2dimages`
				WHERE id_product = '{$product_id}' AND status = 1
				ORDER BY sort_order", ARRAY_A );

			if ( !empty($images2d) ) {
				foreach ($images2d as &$image) {
					$key = $image['id'].'_'.$image['id_product'];
					array_push( $find_in_set, $key );

					$img = self::uri() . '/ajaxzoom/pic/2d/' . $product_id . '/' . $image['image'];
					$all_images_woo[$key] = array(
						'img' => $img,
						'title' => '',
						'order' => $order++
					);
				}
			}
		}

		if ( !empty($find_in_set) ) {
			self::$frontend_2d_ids = array_merge( self::$frontend_2d_ids, $find_in_set );
		}

		return json_encode($all_images_woo, JSON_FORCE_OBJECT);
	}

	public static function media_admin() {
		global $post, $wp_query, $wp_version;
		$plugin_ver = self::get_plugin_version();
		$az_ver = self::get_az_version_number();
		$dir = self::dir();
		$screen = get_current_screen();

		if ( ( isset( $post ) && ($post->post_type == 'product') )
			|| ( isset( $_GET['page'] ) && $_GET['page'] == 'ajaxzoombatch' )
		) {
			wp_register_style( 'axZm_fancybox_css', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css', $dir ), array(), $az_ver );
			wp_enqueue_style( 'axZm_fancybox_css' );

			wp_register_script( 'axZm_fancybox_js', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.js', $dir ), array( 'jquery' ), $az_ver );
			wp_enqueue_script( 'axZm_fancybox_js' );

			wp_register_script( 'axZm_openAjaxZoomInFancyBox_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.openAjaxZoomInFancyBox.js', $dir ), array( 'jquery' ), $az_ver );
			wp_enqueue_script( 'axZm_openAjaxZoomInFancyBox_js' );

			wp_register_script( 'jquery_scrollTo_min_js', plugins_url( 'ajaxzoom/axZm/plugins/jquery.scrollTo.min.js', $dir ), array( 'jquery' ), $az_ver );
			wp_enqueue_script( 'jquery_scrollTo_min_js' );

			wp_register_style( 'axZm_WC_backend', plugins_url( 'ajaxzoom/assets/css/axZm_WC_backend.css', $dir ), array(), $plugin_ver );
			wp_enqueue_style( 'axZm_WC_backend' );

			wp_register_script( 'jquery_editable_select_js', plugins_url( 'ajaxzoom/assets/js/jquery.editable-select.js', $dir ), array( 'jquery' ), $plugin_ver );
			wp_enqueue_script( 'jquery_editable_select_js' );

			wp_register_script( 'jquery_multisortable_js', plugins_url( 'ajaxzoom/assets/js/jquery.multisortable.js', $dir ), array( 'jquery' ), $plugin_ver );
			wp_enqueue_script( 'jquery_multisortable_js' );

			wp_register_script( 'axZm_WC_backend_js', plugins_url( 'ajaxzoom/assets/js/axZm_WC_backend.js', $dir ), array( 'jquery' ),$plugin_ver );
			wp_enqueue_script( 'axZm_WC_backend_js' );

		} elseif ( (isset( $_GET['page'] ) &&  $_GET['page'] == 'wc-settings' && isset( $_GET['tab'] ) && $_GET['tab'] == 'ajaxzoom')
			|| $screen->id == 'plugins'
		) {
			wp_register_script( 'axZm_WC_backend_js', plugins_url( 'ajaxzoom/assets/js/axZm_WC_backend.js', $dir ), array( 'jquery' ), $plugin_ver );
			wp_enqueue_script( 'axZm_WC_backend_js' );

			wp_register_style( 'axZm_WC_backend_css', plugins_url( 'ajaxzoom/assets/css/axZm_WC_backend.css', $dir ), array(), $plugin_ver );
			wp_enqueue_style( 'axZm_WC_backend_css' );

			if ($screen && $screen->id != 'plugins') {
				wp_register_script( 'jquery_editable_select_js', plugins_url( 'ajaxzoom/assets/js/jquery.editable-select.js', $dir ), array( 'jquery' ), $plugin_ver );
				wp_enqueue_script( 'jquery_editable_select_js' );

				wp_register_style( 'axZm_fancybox_css', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css', $dir ), array(), $plugin_ver );
				wp_enqueue_style( 'axZm_fancybox_css' );

				wp_register_script( 'axZm_fancybox_js', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.js', $dir ), array( 'jquery' ), $plugin_ver );
				wp_enqueue_script( 'axZm_fancybox_js' );

				wp_register_script( 'axZm_openAjaxZoomInFancyBox_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.openAjaxZoomInFancyBox.js', $dir ), array( 'jquery' ), $plugin_ver );
				wp_enqueue_script( 'axZm_openAjaxZoomInFancyBox_js' );
			}
		}
	}

	public static function show_az() {
		global $post, $product;

		if ( !$post || !$post->ID || !$product ) {
			return false;
		}

		// returne saved value
		if (self::$show_az_save === false || self::$show_az_save === true) {
			return self::$show_az_save;
		}

		$enable_in_front = get_option('AJAXZOOM_ENABLEINFRONTDETAIL');
		if ($enable_in_front == 'false') {
			self::$show_az_save = false;
			return false;
		}

		$display_only_for_this_product =  get_option('AJAXZOOM_DISPLAYONLYFORTHISPRODUCTID');

		if ( $display_only_for_this_product ) {
			$display_only_for_this_product = array_map ( 'trim', explode(',', $display_only_for_this_product) );

			if ( !empty( $display_only_for_this_product ) ) {
				if ( !in_array( $post->ID, $display_only_for_this_product) ) {
					self::$show_az_save = false;
					return false;
				}
			}
		}

		if ( !self::is_active( $post->ID ) ) {
			self::$show_az_save = false;
			return false;
		}

		self::$show_az_save = true;

		return true;
	}

	public static function is_iframe() {
		return isset($_GET['ajaxzoom']) && $_GET['ajaxzoom'] == 'iframe' && stristr($_SERVER['PHP_SELF'], 'ajaxzoom/preview/iframe.php');
	}

	public static function player_in_frame() {
		if ( Ajaxzoom::is_iframe() ) {
			error_reporting(0);
			self::media_frontend();
			remove_all_actions('wp_footer');
			remove_all_actions('wp_header');
			remove_all_actions('wp_register_script');
			remove_all_actions('wp_enqueue_scripts');
			remove_all_actions('wp_register_style');
			remove_all_actions('wp_enqueue_style');
			remove_all_actions('wp_print_scripts');
		}
	}

	public static function get_plugin_version($strict = false) {
		global $wp_version;
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$d = get_plugin_data(dirname(__DIR__).'/ajaxzoom.php');
		if ($strict) {
			return !empty($d) && isset($d['Version']) ? $d['Version'] : false;
		} else {
			return !empty($d) && isset($d['Version']) ? $d['Version'] : $wp_version;
		}
	}

	public static function pre_register_script($handle = '', $src = '', $deps = array(), $ver = false) {
		if ($handle) {
			self::$pre_scripts[$handle] = array(
				'handle' => $handle,
				'src' => $src,
				'deps' => $deps,
				'ver' => $ver
			);
		}
	}

	public static function pre_register_style($handle = '', $src = '', $deps = array(), $ver = false) {
		if ($handle) {
			self::$pre_css[$handle] = array(
				'handle' => $handle,
				'src' => $src,
				'deps' => $deps,
				'ver' => $ver
			);
		}
	}

	public static function media_frontend() {
		global $product, $wp_scripts;

		$plugin_ver = self::get_plugin_version();
		$az_ver = self::get_az_version_number();
		$is_iframe = self::is_iframe();
		$dir = self::dir();

		if ( !$is_iframe ) {
			self::pre_register_style( 'axZm_WC_frontend_pages_css', plugins_url( 'ajaxzoom/assets/css/axZm_WC_frontend_pages.css', $dir ), array(), $plugin_ver );
			self::pre_register_script( 'axZm_embedResponsive_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.embedResponsive.js', $dir ), array( 'jquery' ), $az_ver );
			self::pre_register_script( 'axZm_iframe_js', plugins_url( 'ajaxzoom/axZm/axZm.iframe.js', $dir ), array( ), $az_ver );

			if ( empty( $product ) || ( $product && get_option('AJAXZOOM_ENABLEINFRONTDETAIL') == 'false' ) ) {
				wp_register_style( 'axZm_WC_frontend_pages_css', plugins_url( 'ajaxzoom/assets/css/axZm_WC_frontend_pages.css', $dir ), array(), $plugin_ver );
				wp_enqueue_style( 'axZm_WC_frontend_pages_css' );

				wp_register_script( 'axZm_embedResponsive_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.embedResponsive.js', $dir ), array( 'jquery' ), $az_ver );
				wp_enqueue_script( 'axZm_embedResponsive_js' );

				wp_register_script( 'axZm_iframe_js', plugins_url( 'ajaxzoom/axZm/axZm.iframe.js', $dir ), array( ), $az_ver );
				wp_enqueue_script( 'axZm_iframe_js' );
			}
		}

		if ( !$is_iframe ) {
			if ( empty( $product ) || !self::show_az()) {
				return;
			}
		}

		$config = self::config();
		$ajaxzoom_themeName = self::get_theme_name();
		$ajaxzoom_themeName = strtolower($ajaxzoom_themeName);
		$ajaxzoom_themesettings = self::theme_settings($config);

		if ( $is_iframe ) {
			self::pre_register_style( 'axZm_WC_frontend_iframe_css', plugins_url( 'ajaxzoom/assets/css/axZm_WC_frontend_iframe.css', $dir ), array(), $plugin_ver );
		}

		self::pre_register_style( 'axZm_WC_frontend_css', plugins_url( 'ajaxzoom/assets/css/axZm_WC_frontend.css', $dir ), array(), $plugin_ver );

		self::pre_register_style( 'axZm_css', plugins_url( 'ajaxzoom/axZm/axZm.css', $dir ), array(), $az_ver);

		if ( $is_iframe ) {
			self::pre_register_script( 'jquery-core', plugins_url( 'ajaxzoom/axZm/plugins/jquery-2.2.4.min.js', $dir ), array(), '2.2.4' );
		}

		self::pre_register_script( 'axZm_js', plugins_url( 'ajaxzoom/axZm/jquery.axZm.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_style( 'axZm_expButton_css', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.expButton.css', $dir ), array(), $az_ver );

		self::pre_register_script( 'axZm_expButton_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.expButton.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_script( 'axZm_imageCropLoad_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.imageCropLoad.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_script( 'axZm_mousewheel_js', plugins_url( 'ajaxzoom/axZm/extensions/axZmThumbSlider/lib/jquery.mousewheel.min.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_style( 'axZm_thumbSlider_css', plugins_url( 'ajaxzoom/axZm/extensions/axZmThumbSlider/skins/default/jquery.axZm.thumbSlider.css', $dir ), array(), $az_ver );

		self::pre_register_script( 'axZm_thumbSlider_js', plugins_url( 'ajaxzoom/axZm/extensions/axZmThumbSlider/lib/jquery.axZm.thumbSlider.js', $dir ), array( 'jquery' ), $az_ver );

		if ( $config['AJAXZOOM_SPINNER'] == 'true' ) {
			self::pre_register_script( 'axZm_spin_js', plugins_url( 'ajaxzoom/axZm/plugins/spin/spin.min.js', $dir ), array( 'jquery' ), $az_ver );
		}

		self::pre_register_style( 'axZm_mouseOverZoom_css', plugins_url( 'ajaxzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.css', $dir ), array(), $az_ver );

		self::pre_register_script( 'axZm_mouseOverZoom_js', plugins_url( 'ajaxzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_script( 'axZm_mouseOverZoomInit_js', plugins_url( 'ajaxzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoomInit.5.js', $dir ), array( 'jquery' ), $az_ver );

		if ( $config['AJAXZOOM_AJAXZOOMOPENMODE'] == 'fancyboxFullscreen') {
			self::pre_register_style( 'axZm_fancybox_css', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css', $dir ), array(), $az_ver );

			self::pre_register_script( 'axZm_fancybox_js', plugins_url( 'ajaxzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.js', $dir ), array( 'jquery' ), $az_ver );

			self::pre_register_script( 'axZm_openAjaxZoomInFancyBox_js', plugins_url( 'ajaxzoom/axZm/extensions/jquery.axZm.openAjaxZoomInFancyBox.js', $dir ), array( 'jquery' ), $az_ver );
		}

		self::pre_register_script( 'axZm_JSON_js', plugins_url( 'ajaxzoom/axZm/plugins/JSON/jquery.json-2.3.min.js', $dir ), array( 'jquery' ), $az_ver );

		self::pre_register_script( 'axZm_WC_frontend_js', plugins_url( 'ajaxzoom/assets/js/axZm_WC_frontend.js', $dir ), array( 'jquery' ), $plugin_ver );

		if ( !$is_iframe ) {
			foreach ( self::$pre_scripts as $k => $v ) {
				wp_register_script( $k, $v['src'], $v['deps'], $v['ver'] );
				wp_enqueue_script( $k );
			}

			foreach ( self::$pre_css as $k => $v ) {
				wp_register_style( $k, $v['src'], $v['deps'], $v['ver'] );
				wp_enqueue_style( $k );
			}
		}

		if ( !$is_iframe
			&& $ajaxzoom_themeName
			&& isset($ajaxzoom_themesettings[$ajaxzoom_themeName])
			&& isset($ajaxzoom_themesettings[$ajaxzoom_themeName]['act'])
			&& $ajaxzoom_themesettings[$ajaxzoom_themeName]['csstag']
		) {
			wp_add_inline_style( 'axZm_mouseOverZoom_css', $ajaxzoom_themesettings[$ajaxzoom_themeName]['csstag'] );
		}
	}

	public static function woocommerce_get_settings_pages( $settings ) {
		$settings[] = include __DIR__ . '/admin/settings/class-wc-settings-ajaxzoom.php';
		return $settings;
	}

	public static function save_ajaxzoom_licenses() {
		$licenses = array();

		if ( isset( $_POST['ajaxzoom_licenses'] ) ) {
			$licenses = $_POST['ajaxzoom_licenses'];
			update_option( 'ajaxzoom_licenses', $licenses );
		}
	}

	public static function save_ajaxzoom_themesettings() {
		$themesettings = array();

		if ( isset( $_POST['ajaxzoom_themesettings'] ) ) {
			$themesettings = $_POST['ajaxzoom_themesettings'];
			update_option( 'ajaxzoom_themesettings', $themesettings );
		}
	}

	public static function add_meta_links( $links, $file ) {
		$my_plugin = plugin_basename(dirname(__DIR__) . '/ajaxzoom.php');

		if ( $file == $my_plugin ) {
			$has_loader = self::loader_installed();
			$has_axzm = is_dir( dirname(__DIR__) . '/axZm' ) && file_exists( dirname(__DIR__) . '/axZm/readme.txt' );
			$err = '';

			if ( !$has_loader || !$has_axzm ) {
				$err = '<br><div class="update-nag" style="border-left-color: red;">';

				$err .= '<h2 style="margin-top: 0; margin-bottom: 5px;">Some minor problems. You can solve them, please read!</h2>';

				$err .= '<ol style="font-size: inherit;">';

				if ( !$has_loader ) {
					$err .= '<li>Ioncube loaders are not installed on this server. Please install 
					<a href="https://www.ioncube.com/loaders.php" target="_blank">Ioncube loaders</a> to make AJAX-ZOOM work!</li>';
				}

				if ( !$has_axzm ) {
					$err .= '<li>During activation the plugin should have downloaded the latest version of AJAX-ZOOM main scripts instantly. 
					For some reason this did not happen. 
					Do not worry, please <a href="http://www.ajax-zoom.com/index.php?cid=download" target="_blank">download</a> 
					AJAX-ZOOM <u>without examples and test images (around 10MB)</u> on your own and extract (e.g. upload over FTP) the contents of /axZm folder into 
					"/wp-content/plugins/ajaxzoom/axZm" manually!</li> 
					';
				}

				$err .= '</ol>';

				$err .= '<span style="display: block; margin-top: 5px;">If you have any questions do not hesitate to 
				<a href="http://www.ajax-zoom.com/index.php?cid=contact" target="_blank">contact</a> AJAX-ZOOM support. Thanks.</span>';

				$err .= '</div>';
			}

			$meta_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=ajaxzoom' ) . '">Settings</a>',
				'<a href="http://www.ajax-zoom.com/index.php?cid=download#buyLicense">License</a>', 
				'<a href="http://www.ajax-zoom.com/index.php?cid=contact">Support</a>'
			);

			if ( !$err ) {
				array_push( $meta_links, ' <a href="javascript:void(0)" id="az_please_read_link">Please read !!!</a>
				<div class="update-nag" id="az_please_read_message" style="border-left-color: #ffb900; display: none;">
					<p>If you do not see any output on product detail page or the layout is broken because a different image viewer or parts of it are visible, 
					at first pleasе check the "appendToContainer" and "appendToContCss" options under 
					<a href="admin.php?page=wc-settings&tab=ajaxzoom&section=plugin_settings#AJAXZOOM_APPENDTOCONTAINER">AJAX-ZOOM plugin settings</a>. 
					Below these options you are also able to adjust more css / js and other things depending on the theme you are using, e.g. 
					hide certain containers with CSS or change its size, margins and padding. You are also able to add additional JS / jQuery to achieve the goal. </p>
					<p>In case you are not able to accomplish it on your own, please contact AJAX-ZOOM support and provide a link to your 
					test installation and the name of the theme you are using.</p>
				</div>
				<script type="text/javascript">
				jQuery("#az_please_read_link").on("click", function(e) {
					e.preventDefault();
					jQuery(this).blur();
					jQuery("#az_please_read_message").slideToggle(150);
				});
				</script>
				');

				$meta_links = self::update_notification($meta_links);
			}

			if ($err) {
				array_push( $meta_links, $err );
			}

			return array_merge( $links, $meta_links );
		}

		return $links;
	}

	public static function add_action_links( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=ajaxzoom' ) . '">Settings</a>'
		);

		$check_every = 60*60*24;
		$plugin = 'woocommerce';
		$now = (int)microtime(true);

		$last_check = get_option('ajaxzoom_plugin_update_check');
		if ( !$last_check ) {
			update_option( 'ajaxzoom_plugin_update_check', $now );
			$last_check = get_option('ajaxzoom_plugin_update_check');
		}

		$time_diff = $now - $last_check;

		if ( $time_diff > $check_every && function_exists('curl_version') ) {
			update_option('ajaxzoom_plugin_update_check', $now);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://www.ajax-zoom.com/getlatestversion_plugin.php?plugin=' . $plugin);
			$res = curl_exec($ch);
			curl_close($ch);

			if ( $res ) {
				$arr = json_decode($res, true);

				if (!empty($arr)) {
					$a_ver = $arr[$plugin];
					$a_date = $arr['date'][$plugin];
					$a_notes = $arr['notes'][$plugin];

					$c_ver = self::get_plugin_version(true);

					if ($c_ver && $a_ver && version_compare($a_ver, $c_ver) === 1) {
						set_transient( 'ajaxzoom_new_plugin_version', $arr, 0 );
					} elseif ($c_ver && $a_ver) {
						delete_transient( 'ajaxzoom_new_plugin_version' );
					}
				}
			}
		}

		return array_merge( $links, $mylinks );
	}

	public static function update_notification($meta_links) {
		$transient = get_transient('ajaxzoom_new_plugin_version');

		if ( $transient && is_array($transient) ) {
			$plugin = 'woocommerce';
			$c_ver = self::get_plugin_version(true);
			$notes = '';

			if (version_compare($transient[$plugin], $c_ver) < 1) {
				delete_transient('ajaxzoom_new_plugin_version');
				return $meta_links;
			}

			if (isset($transient['notes'][$plugin]) && !empty($transient['notes'][$plugin])) {
				foreach (array_reverse($transient['notes'][$plugin]) as $k => $v) {
					if (version_compare($k, $c_ver) === 1) {
						$notes .= '<tr><td style="vertical-align: top; width: 100px;">'.$k.'</td><td><ul><li>';
						$notes .= implode('</li><li>', $v);
						$notes .= '</li><ul>';
					}
				}
			}

			$notification = '
			<div class="update-nag" style="display: block">
				<h2>AJAX-ZOOM plugin for WooCommerce update available, version '.$transient[$plugin].'</h2>
			';
			if ($notes) {
				$notification .= '
				<p>Please read the release notes before updating, because you might also need to update AJAX-ZOOM core files, 
					located in /axZm folder. 
					This can be done under <a href="'.admin_url( 'admin.php?page=wc-settings&tab=ajaxzoom&section=reset' ).'">plugin settings -> actions tab</a>, 
					where you will find additional information about the update process. 
					You can also update AJAX-ZOOM core files manually over FTP...
				</p>
				<h3>Release notes</h3>
				<table class="" style="margin-bottom: 10px; margin-top: 10px;">
					<tbody>'.$notes.'</tbody>
				</table>
				';
			}

			$notification .= ' 
				<button class="button button-primary" id="az_backup_woo_plugin">Backup current plugin version</button> 
				<button class="button button-primary" id="az_update_woo_plugin">Perform update to plugin version '.$transient[$plugin].'</button> 
				<p class="note"><span class="dashicons dashicons-editor-break" style="font-size: 100%; transform: rotate(90deg)"></span> It is recommended to make a backup first.
					The backup will be created as zip file in /backups folder with the timestamp. 
					Images, logs, other dynamically created content and AJAX-ZOOM core files are not backed up here. 
					The file size will be around 1MB (only AJAX-ZOOM plugin for WooCommerce / WP).</p>
				<div style="margin-top: 5px; display: none;" id="az_backup_woo_plugin_results"></div>
				<div style="margin-top: 5px; display: none;" id="az_update_woo_plugin_results"></div>
			</div>
			';

			$notification .= ' 
			<script type="text/javascript">
				jQuery("#az_update_woo_plugin").on("click", function(e) {
					window.az_updatePlugin(e, "'.admin_url( 'admin-ajax.php', 'relative' ).'");
				} );
				jQuery("#az_backup_woo_plugin").on("click", function(e) {
					window.az_backupPlugin(e, "'.admin_url( 'admin-ajax.php', 'relative' ).'");
				} );
			</script>
			';

			if ( current_user_can( 'install_plugins' ) ) {
				array_push($meta_links, $notification);
			}
		}

		return $meta_links;
	}

	public static function backup_plugin($send_json = false, $send_array = false) {
		global $ajaxzoom_folder_permissions;

		$errors = array();
		$dir = self::dir();
		$c_ver = self::get_plugin_version(true);
		if (!$c_ver) {
			$c_ver = '';
		}

		$status = 0;
		$filesize = 0;
		$zip_filename = $dir . 'backups' . DIRECTORY_SEPARATOR .'plugin_ajaxzoom_'.$c_ver.'_'.self::backup_time().'.zip';

		if (!is_dir($dir . 'backups')) {
			@mkdir($dir . 'backups', octdec( $ajaxzoom_folder_permissions ) );
			@chmod($dir . 'backups', octdec( $ajaxzoom_folder_permissions ));
		}

		if ( !is_writeable($dir . 'backups') ) {
			@chmod($dir . 'backups', octdec( $ajaxzoom_folder_permissions ) );
			if ( !is_writeable($dir . 'backups') ) {
				array_push($errors, $dir . 'backups' . __(' is not writeable by PHP. Please change owner || chmod.', 'ajaxzoom'));
			}
		}

		if (version_compare(PHP_VERSION, '5.3.0') < 0) {
			array_push($errors, __('PHP version must be at least 5.3.0 for this operation. You are using ', 'ajaxzoom') . PHP_VERSION);
		}

		if ( empty($errors) ) {
			$exclude = array('axZm', 'backups', 'pic', 'logs', 'zip', '.svn');
			$zip = new \ZipArchive();

			if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
				array_push($errors, __('Could not create new zip file at: ', 'ajaxzoom') . $zip_filename);
			} else {
				$filter = function ($file, $key, $iterator) use ($exclude) {
					if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
						return true;
					}

					return $file->isFile();
				};

				$inner_iterator = new RecursiveDirectoryIterator(
					$dir,
					RecursiveDirectoryIterator::SKIP_DOTS
				);

				$files = new RecursiveIteratorIterator(
					new RecursiveCallbackFilterIterator($inner_iterator, $filter)
				);

				$sep = dirname(__DIR__) . DIRECTORY_SEPARATOR;

				foreach ($files as $pathname => $fileInfo) {
					$file_split = explode($sep, $pathname);
					if (isset($file_split[1])) {
						$file_dest = 'ajaxzoom' . DIRECTORY_SEPARATOR . $file_split[1];
						if ( !$zip->addFile($pathname, $file_dest) ) {
							array_push($errors, __('Was not able to add file to zip: ', 'ajaxzoom') . $pathname);
						}
					}
				}

				$zip->close();
				$filesize = @filesize($zip_filename);
				$status = 1;
			}
		}

		$return = array(
			'errors' => $errors,
			'status' => $status,
			'filesize_bytes' => $filesize,
			'filesize' => self::format_bytes($filesize),
			'zip_filename' => $zip_filename
		);

		if ($send_json === true) {
			wp_send_json($return);
		} elseif ($send_array == true) {
			return $return;
		} else {
			return empty($errors) ? true : false;
		}
	}

	public static function pre_check_update($path, $excl_files, $errors) {
		$dir = self::dir();

		$files = scandir( $dir . $path );

		foreach ( $files as $file ) {
			if ( !in_array($file, $excl_files) ) {
				if ( !is_writeable( $dir . $file ) ) {
					array_push($errors, $dir . $file . __(' is now writeable by PHP.', 'ajaxzoom') );
				}

				if ( is_dir( $dir . $file ) ) {
					$folder = $dir . $file;
					$objects = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($folder), 
						RecursiveIteratorIterator::SELF_FIRST);

					foreach($objects as $name => $object) {
						$filename = $object->getFilename();
						if ($filename && $filename != '.' && $filename != '..' && $filename != '.svn') { 
							if ( !is_writeable($name) ) {
								array_push($errors, $name . __(' is now writeable by PHP.', 'ajaxzoom') );
							}
						}
					}
				}
			}
		}

		return $errors;
	}

	public static function update_plugin() {
		$errors = array();
		$log_files = array();
		$dir = self::dir();

		if ( !is_writeable($dir . 'pic/tmp') ) {
			@chmod($dir . 'pic/tmp', 0777);
			if ( !is_writeable($dir . 'pic/tmp') ) {
				array_push($errors, $dir . 'pic/tmp' . __(' is not writeable by PHP. Please change owner || chmod.', 'ajaxzoom'));
			}
		}

		if ( empty($errors) ) {
			$latest_ver = 'https://www.ajax-zoom.com/download_module.php?module=woocommerce&update=1';
			$remoteFileContents = file_get_contents( $latest_ver );

			if ($remoteFileContents != false) {
				$localFilePath = $dir . 'pic/tmp/plugin.zip';
				if (file_exists($localFilePath)) {
					@unlink($localFilePath);
				}

				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';

				if ( is_dir($dir . 'pic/tmp/ajaxzoom') ) {
					self::delete_directory($dir . 'pic/tmp/ajaxzoom', true);
				}

				file_put_contents( $localFilePath, $remoteFileContents );
				$zip = new \ZipArchive();
				$res = $zip->open( $localFilePath );
				$zip->extractTo( $dir . 'pic/tmp/' );
				$zip->close();

				$excl_files = array('backups', 'pic', 'logs', 'zip', 'zoomConfigCustomAZ.inc.php', '.', '..', '.svn');

				if ( !$files = scandir( $dir . 'pic/tmp/ajaxzoom' ) ) {
					array_push($errors, __('An error occured while extracting files into ', 'ajaxzoom') . $dir . 'pic/tmp/ajaxzoom' );
				}

				if ( empty($errors)) {
					$errors = self::pre_check_update('pic/tmp/ajaxzoom/', $excl_files, $errors);
				}

				if ( empty($errors)) {
					foreach ( $files as $file ) {
						if ( !in_array($file, $excl_files) ) {
							if ( file_exists($dir . $file) ) {
								if ( is_dir( $dir . $file ) ) {
									self::delete_directory($dir . $file, true);
								} else {
									@unlink( $dir . $file );
								}
							}

							if ( !(@rename($dir . 'pic/tmp/ajaxzoom/'. $file, $dir . $file)) ) {
								if (!(@copy($dir . 'pic/tmp/ajaxzoom/'. $file, $dir . $file))) {
									array_push($log_files, $dir . $file);
								}
							}
						}
					}
				}

				self::delete_directory($dir . 'pic/tmp/ajaxzoom', true);
				@unlink($localFilePath);
			} else {
				array_push($errors, __('The plugin was not able to download new version', 'ajaxzoom'));
			}
		}

		if (empty($errors)) {
			delete_transient('ajaxzoom_new_plugin_version');
		}

		wp_send_json(array(
			'errors' => $errors,
			'files_err' => $log_files
		));
	}

	public static function install() {
		self::install_db();
		self::install_dir();
		self::install_axzm(false);
		self::install_config();

		if (!self::loader_installed()) {
			set_transient( 'ajaxzoom_install_ioncube_error', true, 0 );
		} else {
			delete_transient( 'ajaxzoom_install_ioncube_error' );
			set_transient( 'ajaxzoom_install_ok', true, 0 );
		}
	}

	public static function install_after_notices() {
		$errors = array();

		if ( get_transient('ajaxzoom_install_ioncube_error') ) {
			array_push($errors, __('Ioncube loader is missing, please download and install the <strong>free Ioncube loader</strong> for you server.', 'ajaxzoom'));
		}

		if  (get_transient('ajaxzoom_install_pic_folder_error') ) {
			array_push($errors, 'The <strong>' . self::dir() .'pic</strong> folder is not writeable by PHP, please change chmod.');
		}

		if ( get_transient('ajaxzoom_install_pic_subfolder_error') ) {
			array_push($errors, 'The plugin was not able to create several subfolder under <strong>' . self::dir() .'pic</strong> folder. Please contact AJAX-ZOOM support.');
		}

		if ( get_transient('ajaxzoom_install_axzm_download_error') ) {
			array_push($errors, __('An error occured while downloading <strong>AJAX-ZOOM core files</strong>. Please <a href="https://www.ajax-zoom.com/index.php?cid=download" target="_blank">download</a> AJAX-ZOOM core files manually and upload only axZm folder into plugins/ajaxzoom directory.', 'ajaxzoom'));
		}

		if ( !empty($errors) ) {
			deactivate_plugins( 'ajaxzoom/ajaxzoom.php' );
			?>
			<div class="notice notice-error">
				<h2><?php echo __('AJAX-ZOOM message: some errors occured while activation. Do not worry, you can correct them.', 'ajaxzoom'); ?></h2>
				<ul style="list-style: disc; margin-left: 20px;"><li><?php echo implode('</li><li>', $errors); ?></li></ul>
				<div style="font-weight: bold; margin-bottom: 15px; color: #dc3232">
				<?php echo __('AJAX-ZOOM plugin was deactivated bei itself instantly. Please correct all the errors above and activate it again.', 'ajaxzoom'); ?>
				<?php echo __('If you have any questions do not hesitate to <a href="http://www.ajax-zoom.com/index.php?cid=contact" target="_blank">contact</a> AJAX-ZOOM support. Thanks.', 'ajaxzoom'); ?>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('.updated.notice').remove();
				});
			</script>
			<?php
			delete_transient( 'ajaxzoom_install_ok' );
		} elseif (get_transient('ajaxzoom_install_ok')) {
			delete_transient( 'ajaxzoom_install_ok' );
			update_option( 'ajaxzoom_plugin_update_check', (int)microtime(true) );
			?>
			<div class="notice notice-success is-dismissible">
				<h2><?php echo __('AJAX-ZOOM message:', 'ajaxzoom'); ?></h2>
				<p><?php echo __('It seems that everything is ok with the installation and activation of AJAX-ZOOM. If you still experience any troubles, which you are not able to resolve on your own, do not hesitate to <a href="http://www.ajax-zoom.com/index.php?cid=contact" target="_blank">contact</a> AJAX-ZOOM support. Thanks.', 'ajaxzoom')?></p>
			</div>
			<?php
		}
	}

	public static function uninstall() {
		global $wpdb;
		$prefix = $wpdb->prefix;

		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoom360`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoom360set`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoom2dimages`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoomproducts`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoomvideo`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoomproductsettings`');
		$wpdb->query('DROP TABLE IF EXISTS `'.$prefix.'ajaxzoomimagehotspots`');

		$wpdb->query('DELETE FROM `'.$prefix.'options` WHERE option_name LIKE \'AJAXZOOM_%\'');
		$wpdb->query('DELETE FROM `'.$prefix.'options` WHERE option_name LIKE \'ajaxzoom_%\'');
		$wpdb->query('DELETE FROM `'.$prefix.'options` WHERE option_name LIKE \'AZ_CROP%\'');
	}

	public static function install_db() {
		global $wpdb, $ajaxzoom_db_version;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate().';';
		$prefix = $wpdb->prefix;

		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoom360` (
			`id_360` int(11) NOT NULL AUTO_INCREMENT,
			`id_product` int(11) NOT NULL,  `name` varchar(255) NOT NULL,
			`num` int(11) NOT NULL DEFAULT \'1\',
			`settings` text NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT \'0\',
			`combinations` text NOT NULL,
			`crop` text NOT NULL,
			`hotspots` text NOT NULL,
			`sort_order` int(11) NOT NULL DEFAULT \'0\',
			PRIMARY KEY (`id_360`)) '.$charset_collate;

		dbDelta( $sql );

		$check_ajaxzoom360_fields = $wpdb->get_col( 'DESC `'.$prefix.'ajaxzoom360`' );

		if ( !in_array('hotspots', $check_ajaxzoom360_fields) ) {
			$wpdb->query( 'ALTER TABLE `'.$prefix.'ajaxzoom360` ADD `hotspots` TEXT NOT NULL AFTER `crop`' );
		}

		if ( !in_array('sort_order', $check_ajaxzoom360_fields) ) {
			$wpdb->query( 'ALTER TABLE `'.$prefix.'ajaxzoom360` ADD `sort_order` int(11) NOT NULL DEFAULT \'0\' AFTER `hotspots`' );
		}

		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoom360set` (
			`id_360set` int(11) NOT NULL AUTO_INCREMENT,
			`id_360` int(11) NOT NULL,
			`sort_order` int(11) NOT NULL,
			PRIMARY KEY (`id_360set`)) '.$charset_collate;

		dbDelta( $sql );

		// only WP
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoom2dimages` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_product` int(11) NOT NULL,
			`image` varchar(255) NOT NULL,
			`sort_order` int(11) NOT NULL,
			`variations` text NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT \'1\',
			PRIMARY KEY (`id`)) '.$charset_collate;

		dbDelta( $sql );

		$check_2dactive_field = $wpdb->get_col( 'DESC `'.$prefix.'ajaxzoom2dimages`' );
		if ( !in_array('status', $check_2dactive_field) ) {
			$wpdb->query( 'ALTER TABLE `'.$prefix.'ajaxzoom2dimages` ADD `status` tinyint(1) NOT NULL DEFAULT \'1\' AFTER `variations`' );
		}

		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoomproducts` (
			`id_product` int(11) NOT NULL) '.$charset_collate;

		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoomvideo` 
			(`id_video` int(11) NOT NULL AUTO_INCREMENT,
			`uid` varchar(255) NOT NULL,
			`id_product` int(11) NOT NULL,
			`name` varchar(255) NOT NULL,
			`type` varchar(64) NOT NULL DEFAULT \'\',
			`thumb` varchar(255) NOT NULL,
			`settings` text NOT NULL,
			`status` tinyint(1) NOT NULL DEFAULT \'1\',
			`combinations` text NOT NULL,
			`auto` tinyint(1) NOT NULL DEFAULT \'1\',
			`data` text CHARACTER SET utf16 NOT NULL,
			`sort_order` int(11) NOT NULL DEFAULT \'0\',
			PRIMARY KEY (`id_video`),
			KEY `id_product` (`id_product`),
			KEY `uid` (`uid`)) '.$charset_collate;

		dbDelta( $sql );

		$check_video_field = $wpdb->get_col( 'DESC `'.$prefix.'ajaxzoomvideo`' );

		if ( !in_array('sort_order', $check_video_field) ) {
			$wpdb->query( 'ALTER TABLE `'.$prefix.'ajaxzoomvideo` ADD `sort_order` int(11) NOT NULL DEFAULT \'0\' AFTER `data`' );
		}

		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoomproductsettings` 
			(`id_product` int(11) NOT NULL, 
			`psettings` text NOT NULL, 
			`psettings_embed` text NOT NULL, 
			PRIMARY KEY (`id_product`)) '.$charset_collate;

		dbDelta( $sql );

		// different from other plugins
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$prefix.'ajaxzoomimagehotspots` 
			(`id` int(11) NOT NULL AUTO_INCREMENT, 
			`id_media` varchar(32) NOT NULL, 
			`id_product` int(11) NOT NULL,
			`image_name` varchar(255) NOT NULL,
			`hotspots_active` int(1) NOT NULL DEFAULT 1,
			`hotspots` text NOT NULL,
			PRIMARY KEY (`id`)) '.$charset_collate;

		dbDelta( $sql );

		update_option( 'ajaxzoom_db_version', $ajaxzoom_db_version );
	}

	public static function install_dir() {
		global $ajaxzoom_folder_permissions;
		$dir = self::dir();

		if ( !is_writable($dir) ) {
			set_transient( 'ajaxzoom_install_pic_folder_error', true, 0 );
			return;
		} else {
			delete_transient( 'ajaxzoom_install_pic_folder_error' );
		}

		foreach ( array( '2d', '360', 'cache', 'zoomgallery', 'zoommap', 'zoomthumb', 'zoomtiles_80', 'tmp' ) as $folder ) {
			$path = $dir . 'pic/' . $folder;
			if ( ! file_exists( $path )) {
				@mkdir( $path, octdec( $ajaxzoom_folder_permissions ) );
				@chmod( $path, octdec( $ajaxzoom_folder_permissions ) );
			} else {
				@chmod( $path, octdec( $ajaxzoom_folder_permissions ) );
			}

			if ( $folder == '2d' || $folder == '360' ) {
				if ( is_dir($path) && is_writable($path) && !file_exists($path . '/.htaccess')) {
					file_put_contents( $path . '/.htaccess', 'deny from all' );
				}
			}
		}

		if ( !is_dir($path) || !is_writable($dir . 'pic/zoomtiles_80')) {
			set_transient( 'ajaxzoom_install_pic_subfolder_error', true, 0 );
		} else {
			delete_transient( 'ajaxzoom_install_pic_subfolder_error' );
		}
	}

	public static function install_config() {
		global $wpdb, $ajaxzoom_config_version;
		$settings = self::settings_data();
		$chk_old_opt = $wpdb->query( 'SELECT * FROM '.$wpdb->prefix.'options WHERE option_name = \'AZ_CROPSLIDERPOSITION\'' );
		if ($chk_old_opt == 1) {
			$wpdb->query( 'DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE \'AZ_CROP%\'' );
			$wpdb->query( 'DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE \'AJAXZOOM_%\' && option_name != \'AJAXZOOM_LICENSES\'' );
		}

		foreach ( $settings as $key => $option ) {
			add_option( $key, $option['default'] );
		}

		update_option( 'ajaxzoom_config_version', $ajaxzoom_config_version );
	}

	public static function config() {
		global $wpdb;
		$res = array();
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'AJAXZOOM_%'" );
		foreach ( $rows as $row ) {
			$res[$row->option_name] = $row->option_value;
		}

		return $res;
	}

	public static function backup_axzm($send_json = false, $send_array = false) {
		global $ajaxzoom_folder_permissions;
		$errors = array();
		$dir = self::dir();
		$c_ver = self::get_az_version(true);

		$status = 0;
		$filesize = 0;
		$zip_filename = $dir . 'backups' . DIRECTORY_SEPARATOR .'axZm_'.$c_ver['version'].'_'.self::backup_time().'.zip';

		if (!is_dir($dir . 'backups')) {
			@mkdir($dir . 'backups', octdec( $ajaxzoom_folder_permissions ) );
			@chmod($dir . 'backups', octdec( $ajaxzoom_folder_permissions ) );
		}

		if ( !is_writeable($dir . 'backups') ) {
			@chmod($dir . 'backups', octdec( $ajaxzoom_folder_permissions ) );
			if ( !is_writeable($dir . 'backups') ) {
				array_push($errors, $dir . 'backups' . __(' is not writeable by PHP. Please change owner || chmod.'), 'ajaxzoom');
			}
		}

		if (version_compare(PHP_VERSION, '5.3.0') < 0) {
			array_push($errors, __('PHP version must be at least 5.3.0 for this operation. You are using ', 'ajaxzoom') . PHP_VERSION);
		}

		if (!file_exists($dir . 'axZm')) {
			array_push($errors, $dir . 'axZm' . __(' is not existing. Nothing to backup.', 'ajaxzoom'));
		}

		if (empty($errors)) {
			$exclude = array('.svn');
			$zip = new \ZipArchive();
			if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
				array_push($errors, __('Could not create new zip file at: ', 'ajaxzoom') . $zip_filename);
			} else {
				$filter = function ($file, $key, $iterator) use ($exclude) {
					if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
						return true;
					}

					return $file->isFile();
				};

				$inner_iterator = new RecursiveDirectoryIterator(
					$dir . 'axZm',
					RecursiveDirectoryIterator::SKIP_DOTS
				);

				$files = new RecursiveIteratorIterator(
					new RecursiveCallbackFilterIterator($inner_iterator, $filter)
				);

				$sep = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'axZm';

				foreach ($files as $pathname => $fileInfo) {
					$file_split = explode($sep, $pathname);
					if (isset($file_split[1])) {
						$file_dest = 'axZm' . DIRECTORY_SEPARATOR . $file_split[1];
						if ( !$zip->addFile($pathname, $file_dest) ) {
							array_push($errors, __('Was not able to add file to zip: ', 'ajaxzoom') . $pathname);
						}
					}
				}

				$zip->close();
				$filesize = @filesize($zip_filename);
				$status = 1;
			}
		}

		$return = array(
			'errors' => $errors,
			'status' => $status,
			'filesize_bytes' => $filesize,
			'filesize' => self::format_bytes($filesize),
			'zip_filename' => $zip_filename
		);

		if ($send_json === true) {
			wp_send_json($return);
		} elseif ($send_array == true) {
			return $return;
		} else {
			return empty($errors) ? true : false;
		}
	}

	public static function install_axzm( $update = false ) {

		$dir = self::dir();
		if ( $update !== true ) {
			$update = false;
		}

		if ( ( !file_exists( $dir . 'axZm' ) || $update ) && ini_get( 'allow_url_fopen' ) ) {
			$latest_ver = 'http://www.ajax-zoom.com/download.php?ver=latest&module=woo';
			if ($update) {
				$latest_ver .= '&update=1';
			}

			$remoteFileContents = file_get_contents( $latest_ver );

			if ($remoteFileContents != false) {
				$localFilePath = $dir . 'pic/tmp/jquery.ajaxZoom_ver_latest.zip';
				if (file_exists($localFilePath)) {
					@unlink($localFilePath);
				}

				file_put_contents( $localFilePath, $remoteFileContents );

				$zip = new \ZipArchive();
				$res = $zip->open( $localFilePath );
				$zip->extractTo( $dir . 'pic/tmp/' );
				$zip->close();

				$bck = array();
				if ($update && is_dir($dir . 'axZm')) {
					$bck = self::backup_axzm(false, true);
					if ($bck['status'] != 1 || !empty($bck['errors'])) {
						wp_send_json(array(
							'success' => 1,
							'data' => $bck
						));
					} else {
						self::delete_directory($dir . 'axZm', true);
					}
				}

				@rename( $dir . 'pic/tmp/axZm', $dir . 'axZm' );
				@unlink( $localFilePath );

				if ($update) {
					wp_send_json(array(
						'success' => 1,
						'backupdir' => $bck['zip_filename']
					));
				}
			}
		}

		if ( !$update ) {
			if ( !is_dir($dir.'axZm') || !file_exists($dir.'axZm/jquery.axZm.js') ) {
				set_transient( 'ajaxzoom_install_axzm_download_error', true, 0 );
			} else {
				delete_transient( 'ajaxzoom_install_axzm_download_error' );
			}
		}
	}

	public static function dir() {
		return preg_replace( '|includes/$|', '', plugin_dir_path( __FILE__ ) );
	}

	public static function uri() {
		$url = plugins_url();
		$p = parse_url( $url );
		return $p['path'];
	}

	public static function woocommerce_admin_field_ajaxzoom_resetoptions ( $value ) {
		if ( current_user_can( 'install_plugins' ) ) {
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				Reset
			</th>
			<td class="forminp">
				<input type="button" class="button-primary" id="az_reset_options_btn" style="margin-bottom: 5px; display: block; width: 100%;" value="Reset options">
				<span class="description">
					<?php echo __('Reset all AJAX-ZOOM plugin for Woo options to default values. Licenses will be not reset.', 'ajaxzoom'); ?>
				</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				Remove .htaccess files
			</th>
			<td class="forminp">
				<input type="button" class="button-primary" id="az_delete_htaccess_btn" style="margin-bottom: 5px; display: block; width: 100%;" value="Delete unneeded .htaccess files">
				<span class="description">
					PLEASE READ before proceeding: <br>
					In previous versions AJAX-ZOOM plugin for WooCommerce placed .htaccess files with "deny from all" line in all directories for 360 images. 
					"deny from all" means, that the content of the folders is not accessible over http(s). 
					Starting from AJAX-ZOOM version 5.3.0 you can set AJAX-ZOOM to load the originally uploaded files instead of image tiles at frontend. 
					So in case you choose this AJAX-ZOOM option ($zoom['config']['simpleMode'] - needs to be set in /wp-content/plugins/ajax-zoom/zoomConfigCustomAZ.inc.php file) 
					and have already uploaded some 360s, you will need to remove these htaccess files. This can be also done by pressing on this "delete" button. 
					<br><br>
					After all htaccess files in ajaxzoom plugin subfolders are deleted, please open /wp-content/plugins/ajax-zoom/pic/2d/.htaccess 
					and /wp-content/plugins/ajax-zoom/pic/3d/.htaccess files and remove 
					"deny from all" line. Do not delete these .htaccess files, because in case they are missing they will be recreated with the same content again. 
					Simply leave them blank if there is no other content other than "deny from all" in them.
					<br><br>
					Do not delete .htaccess files if you do not want to enable "simpleMode" option; 
					If your server is not Apache, but e.g. NGINX, .htaccess files have no effect. 
					So if "simpleMode" AJAX-ZOOM core option is not enabled, make sure to protect the 
					/wp-content/plugins/ajax-zoom/pic/3d/ and /wp-content/plugins/ajax-zoom/pic/2d/ directories 
					in a different way.
				</span>
			</td>
		</tr>

		<!--
		<tr valign="top">
			<th scope="row" class="titledesc">
				AJAX-ZOOM batch tool
			</th>
			<td class="forminp">
				<input type="button" class="button-primary" id="az_batch_tool_btn" style="margin-bottom: 5px; display: block; width: 100%;" value="Batch tool">
				<span class="description">
					You do not necessarily need to use the AJAX-ZOOM batch tool, 
					because if image tiles and other AJAX-ZOOM caches have not been generated yet, 
					AJAX-ZOOM will process the images on-the-fly. 
					Latest, when they appear at the frontend. However, if you have thousands of images, 
					it is a good idea to batch process all existing images, 
					which you plan to show over AJAX-ZOOM, before launching the new website or before enabling AJAX-ZOOM at frontend. 
				</span>
			</td>
		</tr>
		-->

		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo __('Update AJAX-ZOOM', 'ajaxzoom'); ?>
			</th>
			<td class="forminp" id="ajaxzoom_update_check">
				<table style="display: none;" class="wc_status_table widefat" id="az_updateTable">
					<tbody>
						<tr class="headings">
							<th><?php echo __('Installed AJAX-ZOOM (core) version', 'ajaxzoom'); ?></th>
							<th><?php echo __('Available AJAX-ZOOM (core) version', 'ajaxzoom'); ?></th>
						</tr>
						<tr>
							<td id="az_updateTdInstalled" style="vertical-align: top;"></td>
							<td id="az_updateTdAvail" style="vertical-align: top;">
								Version: ? <br>
								Date: ? <br>
								Review: ? <br>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="az_axzm_backup_results" style="display: none; padding-bottom: 10px;"></div>
				<input type="button" class="button-primary" id="axzoom_updateaz" style="margin-bottom: 5px; margin-right: 5px;" 
					value="<?php echo __('Check for available updates', 'ajaxzoom')?>">
				<input type="button" class="button-primary" id="axzoom_backupaz" style="margin-bottom: 5px;" 
					value="<?php echo __('Create backup', 'ajaxzoom')?>">
				<span class="description">
					<?php echo __('Check if new AJAX-ZOOM (core files) version is available. It is located in /wp-content/plugins/ajaxzoom/axZm folder. ', 'ajaxzoom'); ?>
					<?php echo __('If you update, the backup will be created instanly. You do not need to create it separately if you want to update.', 'ajaxzoom'); ?>
				</span>
			</td>
		</tr>
		<?php } ?>
		<script type="text/javascript">
			var admin_ajax_url = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';

			jQuery('body').on('click', '#az_reset_options_btn', function(e) {
				jQuery(this).blur();
				az_resetSettingsAjax(e, admin_ajax_url);
			} );

			jQuery('body').on('click', '#az_delete_htaccess_btn', function(e) {
				jQuery(this).blur();
				az_deleteHtaccessFiles(e, admin_ajax_url);
			} );

			/*
			jQuery('body').on('click', '#az_batch_tool_btn', function(e) {
				jQuery(this).blur();
				az_openBatchTool(e, '<?php echo self::uri().'/ajaxzoom/axZm/zoomBatch.php';?>');
			} );
			*/

			jQuery('#axzoom_updateaz').bind('click', function(e) {
				jQuery(this).blur();
				az_updateCoreAjax(e, admin_ajax_url);
			} );

			jQuery('#axzoom_backupaz').bind('click', function(e) {
				jQuery(this).blur();
				az_backupCoreAjax(e, admin_ajax_url);
			} );
		</script>
		<?php
	}

	public static function get_plugin_opt_list() {
		include dirname(dirname(__FILE__)).'/classes/AzMouseoverSettings.php';
		$mouseover_settings = new AzMouseoverSettings(array(
			'vendor' => 'WooCommerce',
			'exclude_cat_vendor' => array('plugin_settings', 'contents_settings'),
			'exclude_opt_vendor' => array(
				'axZmPath',
				'lang'
			)
		) );

		$settings_list = $mouseover_settings->getOptionsList();
		$ext_arr = array(
			'showDefaultForVariation',
			'showDefaultForVariation360',
			'show360noVariationSelected',
			'appendToContainer',
			'appendToContCss'
		);

		foreach($ext_arr as $k) {
			$settings_list[$k] = 'AJAXZOOM_' . strtoupper($k);
		}

		return $settings_list;
	}

	public static function woocommerce_admin_field_ajaxzoom_themesettings( $value ) {

		$themesettings = get_option( 'ajaxzoom_themesettings' );
		$ajaxzoom_themename = self::get_theme_name();
		$ajaxzoom_themename = strtolower($ajaxzoom_themename);
		$options = self::get_plugin_opt_list();
		ksort($options);
		$options = array_flip($options);
		?>
		<tr><th scope="row" class="titledesc"></th><td>
			<h3>Define settings depending on theme loaded at frontend. </h3>
			<ul>
				<li>- <?php _e( 'Theme name', 'ajaxzoom' ); ?> is the theme name loaded (case insensitive). </li>
				<li>- appendToContainer - see same option above. </li>
				<li>- appendToContCss - see same option above.</li>
				<li>- zoomWidth - overwrites zoomWidth option from "specific options for mouseover zoom".</li>
				<li>- zoomHeight - overwrites zoomHeight option from "specific options for mouseover zoom".</li>
				<li>- Style tag - optional inline css tag appended to head section of the page.</li>
				<li>- JavaScript - optional js executed after dom is loaded.</li>
			</ul>
			<?php 
				if ($ajaxzoom_themename) {
					echo '<div class="notice-info notice inline" style="padding: 10px;">Currently enabled theme name: <strong>'.self::get_theme_name().'</strong></div>';
				}
			?>
		</td></tr>

		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Theme settings', 'ajaxzoom' ); ?>:</th>
			<td class="forminp az_container" id="az_themesettings">
				<?php
				$txt['adv_settings_txt'] = __( 'Override any other plugin settings for this theme. The above predefined fields e.g. zoomWidth or zoomHeigh are just common but you can override any other option too.', 'ajaxzoom' );
				$txt['add_new_option'] = __( 'Add new option', 'ajaxzoom' );

				$i = 0;
				$nn = 0;
				if ( $themesettings && is_array($themesettings) ) {
					usort($themesettings, function($a, $b) {
						return strtolower($a['theme']) > strtolower($b['theme']);
					} );

					$display_no = -1;
					if ( $ajaxzoom_themename ) {
						foreach ( $themesettings as $settings ) {
							if ( $ajaxzoom_themename && isset($settings['theme']) && $ajaxzoom_themename == strtolower($settings['theme']) ) {
								$display_no = $nn;
								break;
							}

							$nn++;
						}
					}

					foreach ( $themesettings as $settings ) {

						$advanced_options = '';
						if ( isset( $settings['opt'] ) && $settings['opt'] && !empty( $settings['opt'] ) ) {
							foreach ( $settings['opt'] as $k => $v ) {
								$key = isset($v['k']) ? $v['k'] : '';
								$val = isset($v['v']) ? $v['v'] : '';
								$advanced_options .= '<tr class="az_themesettings_advanced_options_line" data-index="' . $i . '">';
								$advanced_options .= '<td style="vertical-align: top;"><input type="text" class="az_themesettings_advanced_input_key" value="' 
								. $key . '" name="ajaxzoom_themesettings[' . $i . '][opt][' . $k . '][k]" data-list="az_plugin_opt_list"></td>';
								$advanced_options .= '<td></td>';
								$advanced_options .= '<td><textarea style="height: 50px;"  class="az_themesettings_advanced_input_val" name="ajaxzoom_themesettings[' . $i . '][opt][' . $k . '][v]">' 
								. stripslashes($val) . '</textarea></td>';

								$advanced_options .= '<td style="vertical-align: top;"><a href="javascript:void(0)" class="az_themesettings_advanced_options_delete button">Del</a></td>';
								$advanced_options .= '</tr>';
							}
						}

						echo '
						<table cellspacing="0" cellpadding="0" class="az_themesettings_table_item" style="width: 100%">
						<tr><td class="az_themesettings_head">
							<span class="az_themesettings_head_name" style="font-weight: '.($i == $display_no ? 'bolder' : 'normal').'">'.esc_attr( $settings['theme'] ).'</span> 
							<span class="az_tab_arrow az_tab_arrow_down"></span></td></tr>
						<tr class="az_themesettings_parent_tr"><td style="padding: 0;">
							<div class="az_themesettings_div" style="display: none;">
								<div class="az_themesettings_div_inner">
									<table class="az_themesettings">
										<tr>
											<td>Theme name <input type="text" class="az_themesettings_name" value="' . esc_attr( $settings['theme'] ) . '" name="ajaxzoom_themesettings[' . $i . '][theme]" /></td>
											<td>appendToContainer <input type="text" value="' . esc_attr( $settings['atoo'] ) . '" name="ajaxzoom_themesettings[' . $i . '][atoo]" /></td>
											<td>appendToContCss <input type="text" value="' . esc_attr( $settings['atoocss'] ) . '" name="ajaxzoom_themesettings[' . $i . '][atoocss]" /></td>
											<td>zoomWidth <input type="text" value="' . esc_attr( $settings['zoomw'] ) . '" name="ajaxzoom_themesettings[' . $i . '][zoomw]" /></td>
											<td>zoomHeight <input type="text" value="' . esc_attr( $settings['zoomh'] ) . '" name="ajaxzoom_themesettings[' . $i . '][zoomh]" /></td>
										</tr>
										<tr>
											<td colspan="3">
												<span style="text-align: right">Style tag</span>
												<textarea style="height: 250px;" name="ajaxzoom_themesettings[' . $i . '][csstag]" />' . stripslashes( $settings['csstag'] ) . '</textarea>
											</td>
											<td colspan="2">
												<span style="text-align: right">JavaScript</span>
												<textarea style="height: 250px;" name="ajaxzoom_themesettings[' . $i . '][js]" />' . stripslashes($settings['js']) . '</textarea>
											</td>
										</tr>
										<tr>
											<td colspan="3" style="vertical-align: bottom;"><a href="javascript:void(0)" class="az_themesettings_advanced_link">Advanced settings</a></td>
											<td><input type="checkbox" value="1" name="ajaxzoom_themesettings[' . $i . '][act]"' . ( isset($settings['act']) ? ' checked' : '') . '> - settings enabled</td>
											<td style="text-align: right;"><a href="javascript:void(0)" class="az_themesettings_delete button">Delete</a></td>
										</tr>
										<tr>
											<td colspan="5">
												<div style="display: ' . ($advanced_options ? 'block' : 'none') .';" class="az_themesettings_advanced_container">
													<div class="notice-info notice inline" style="margin-bottom: 20px;">
														' . $txt['adv_settings_txt'] . '
													</div>
													<table style="width: 100%" cellspacing="0" cellpadding="0">
														<tbody class="az_themesettings_advanced_options_body">
															<tr>
																<td style="width: 200px;"><strong>Option</strong></td>
																<td style="width: 5px;"></td>
																<td><strong>Value</strong></td>
																<td style="width: 50px;"></td>
															</tr>
															' . $advanced_options . '
														</tbody>
														<tbody>
															<tr>
																<td style="width: 200px;"></td>
																<td style="width: 5px;"></td>
																<td colspan="2"><a href="javascript:void(0)" class="az_themesettings_advanced_options_add button" style="margin-top: 20px;" data-index="' . $i . '">' . $txt['add_new_option'] . '</a></td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</td></tr>
						</table>
						';

						$i++;
					}
				}
				?>
			</td>
		</tr>

		<tr><th scope="row" class="titledesc"></th><td>
			<a href="javascript:void(0)" id="az_themesettings_add" class="button"><?php _e( '+ Add theme settings', 'ajaxzoom' ); ?></a>
		</td></tr>

		<tr><th scope="row" class="titledesc"></th><td>
			<a href="javascript: void(0)" id="az_serialized_themesettings_toggle">Serialized data</a>
			<div id="az_serialized_themesettings" style="padding: 5px; background-color: #FFF; word-break: break-all; display: none;">
				<div class="az-attention-js">
					<?php
						echo __( 'This data is stored in wp_options (or {prefix}options) table where option_name field is AJAXZOOM_THEMESETTINGS. ', 'ajaxzoom' );
						echo __( 'If you did some time consuming adjustments you might want to copy and save it externally in a txt file. ', 'ajaxzoom' );
						echo __( 'Below is "serialized" PHP array (converted to string). ', 'ajaxzoom' );
					?>
				</div>
				<textarea style="width: 100%; height: 300px;"><?php echo is_array($themesettings) ? serialize($themesettings) : $themesettings; ?></textarea>
			</div>
		</td></tr>

		<script type="text/javascript">
		jQuery( function() {
			var az_plugin_opt = <?php echo json_encode($options); ?>;
			if (az_plugin_opt && typeof az_plugin_opt == 'object') {
				var datalist = '<datalist id="az_plugin_opt_list">';
				jQuery.each(az_plugin_opt, function(k, v) {
					datalist += '<option value="'+v+'">';
				} );
				datalist += '</datalist>';
				jQuery('body').append(datalist);
				delete datalist;
			}

			jQuery( '#az_themesettings_add' ).on( 'click', function() {
				jQuery(this).blur();
				var size = jQuery( '#az_themesettings' ).find( 'tbody .az_themesettings_parent_tr' ).size();

				var line = '<?php echo preg_replace("/\s+/", ' ', '
					<table cellspacing="0" cellpadding="0" class="az_themesettings_table_item" style="width: 100%">
					<tr><td class="az_themesettings_head"><span class="az_themesettings_head_name">Placeholder</span> <span class="az_tab_arrow az_tab_arrow_up"></span></td></tr>
					<tr class="az_themesettings_parent_tr"><td style="padding: 0;">
						<div class="az_themesettings_div">
							<div class="az_themesettings_div_inner">
								<table class="az_themesettings">
									<tr>
										<td>Theme name <input type="text" class="az_themesettings_name" value="Placeholder" name="ajaxzoom_themesettings[iii][theme]" /></td>
										<td>appendToContainer <input type="text" value=".images" name="ajaxzoom_themesettings[iii][atoo]" /></td>
										<td>appendToContCss <input type="text" value="" name="ajaxzoom_themesettings[iii][atoocss]" /></td>
										<td>zoomWidth <input type="text" value="" name="ajaxzoom_themesettings[iii][zoomw]" /></td>
										<td>zoomHeight <input type="text" value="" name="ajaxzoom_themesettings[iii][zoomh]" /></td>
									</tr>
									<tr>
										<td colspan="3">
											<span style="text-align: right; padding-bottom: 3px;">Style tag</span>
											<textarea style="height: 250px;" name="ajaxzoom_themesettings[iii][csstag]" /></textarea>
										</td>
										<td colspan="2">
											<span style="text-align: right; padding-bottom: 3px;">JavaScript</span>
											<textarea style="height: 250px;" name="ajaxzoom_themesettings[iii][js]" /></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align: bottom;"><a href="javascript:void(0)" class="az_themesettings_advanced_link">Advanced settings</a></td>
										<td><input type="checkbox" value="1" name="ajaxzoom_themesettings[iii][act]" checked> - settings enabled</td>
										<td style="text-align: right;"><a href="javascript:void(0)" class="az_themesettings_delete button">Delete</a></td>
									</tr>
									<tr>
										<td colspan="5">
											<div style="display: none;" class="az_themesettings_advanced_container">
												<div class="az-attention-js" style="margin-bottom: 20px;">
													' . $txt['adv_settings_txt'] . '
												</div>
												<table style="width: 100%" cellspacing="0" cellpadding="0">
													<tbody class="az_themesettings_advanced_options_body">
														<tr>
															<td style="width: 200px;"><strong>Option</strong></td>
															<td style="width: 5px;"></td>
															<td><strong>Value</strong></td>
															<td style="width: 50px;"></td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td style="width: 200px;"></td>
															<td style="width: 5px;"></td>
															<td colspan="2"><a href="javascript:void(0)" class="az_themesettings_advanced_options_add button" style="margin-top: 20px;" data-index="iii">' . $txt['add_new_option'] . '</a></td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</td></tr>
					</table>'); ?>';

				line = jQuery(line.replace(/iii/g, size));

				line.appendTo( '#az_themesettings' );

				var pos = line.position();
				jQuery('body,html').animate( {
					scrollTop: pos.top
				}, 'slow');

				return false;
			} );

			var reindex_options = function(cnt) {
				var len = jQuery('.az_themesettings_advanced_options_line', cnt).length;
				if (len > 0) {
					var n = 0;
					jQuery('.az_themesettings_advanced_options_line', cnt).each(function() {
						var line = jQuery(this);
						var idx = line.attr('data-index');
						var key = jQuery('.az_themesettings_advanced_input_key', line);
						var val = jQuery('.az_themesettings_advanced_input_val', line);
						key.attr('name', 'ajaxzoom_themesettings['+idx+'][opt]['+n+'][k]');
						val.attr('name', 'ajaxzoom_themesettings['+idx+'][opt]['+n+'][v]');
						n++;
					} );
				}
			};

			jQuery( 'body' ).on( 'click', '.az_themesettings_advanced_options_delete', function() {
				var cnt = jQuery(this).closest('tbody');
				jQuery(this).closest('tr').remove();
				reindex_options(cnt);
			} );

			jQuery( 'body' ).on( 'click', '.az_themesettings_advanced_options_add', function() {
				var btn = jQuery(this).blur();
				var cnt = btn.closest('table').find('.az_themesettings_advanced_options_body');
				var idx = btn.attr('data-index');

				var new_line = '<tr class="az_themesettings_advanced_options_line" data-index="'+idx+'">';
				new_line += '<td style="vertical-align: top;"><input type="text" class="az_themesettings_advanced_input_key" name="ajaxzoom_themesettings['+idx+'][opt][indexA][k]" data-list="az_plugin_opt_list"></td>';
				new_line += '<td></td>';
				new_line += '<td><textarea style="height: 50px;" class="az_themesettings_advanced_input_val" name="ajaxzoom_themesettings['+idx+'][opt][indexB][v]"></textarea></td>';
				new_line += '<td style="vertical-align: top;"><a href="javascript:void(0)" class="az_themesettings_advanced_options_delete button">Del</a></td>';
				new_line += '</tr>';

				cnt.append(jQuery(new_line));

				// reindex
				reindex_options(cnt);
			} );

			jQuery( 'body' ).on( 'focus', '.az_themesettings_advanced_input_key', function() {
				jQuery(this).aZeditableSelect();
			} );

			jQuery( 'body' ).on( 'click', '.az_themesettings_advanced_link', function() {
				jQuery(this).blur().closest('tr').next().find('.az_themesettings_advanced_container').slideToggle(150);
			} );

			jQuery( 'body' ).on( 'click', '#az_serialized_themesettings_toggle', function() {
				jQuery(this).blur();
				jQuery( '#az_serialized_themesettings').slideToggle(150);
			} );

			jQuery( 'body' ).on( 'click', '.az_themesettings_head', function() {
				var _this = jQuery(this).blur();
				var t = jQuery(this).closest('tr').next().find('.az_themesettings_div');
				t.slideToggle(150, function() {
					if (t.css('display') == 'none') {
						jQuery('.az_tab_arrow', _this).removeClass('az_tab_arrow_up').addClass('az_tab_arrow_down');
					} else {
						jQuery('.az_tab_arrow', _this).removeClass('az_tab_arrow_down').addClass('az_tab_arrow_up');
					}
				} );
			} );

			jQuery( 'body' ).on( 'blur', '.az_themesettings_name', function() {
				var val = jQuery(this).val();
				if (!val) {
					val = 'placeholder'
				}
				jQuery(this).closest('.az_themesettings_parent_tr').prev().find('.az_themesettings_head_name').html(val);
			} );

			jQuery( 'body' ).on( 'click', '.az_themesettings_delete', function() {
				var r = confirm("Sure?");
				if (r == true) {
					jQuery(this).closest('.az_themesettings_table_item').remove();
				} 
			} );

		} );
		</script>

		<?php
	}

	public static function woocommerce_admin_field_ajaxzoom_licenses( $value ) {

		$licenses = get_option( 'ajaxzoom_licenses' );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Licenses', 'ajaxzoom' ); ?>:</th>
			<td class="forminp" id="ajaxzoom_licenses">
				<table class="widefat wc_input_table sortable" cellspacing="0">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
							<th style="min-width: 250px;"><?php _e( 'Domain', 'ajaxzoom' ); ?></th>
							<th style="width: 120px;"><?php _e( 'License Type', 'ajaxzoom' ); ?></th>
							<th><i class="az-icon-key" style="margin-right: 5px;"></i><?php _e( 'License Key', 'ajaxzoom' ); ?></th>
							<th><i class="az-icon-key" style="margin-right: 5px;"></i><?php _e( 'Error200', 'ajaxzoom' ); ?></th>
							<th><i class="az-icon-key" style="margin-right: 5px;"></i><?php _e( 'Error300', 'ajaxzoom' ); ?></th>
						</tr>
					</thead>
					<tbody class="licenses">
						<?php
						$i = -1;
						if ( $licenses ) {
							foreach ( $licenses as $license ) {
								$i++;
								echo '<tr class="license">
									<td>&nbsp;</td>
									<td><input type="text" value="' . esc_attr( $license['domain'] ) . '" name="ajaxzoom_licenses[' . $i . '][domain]" /></td>
									<td style="width: 120px;">
										<select name="ajaxzoom_licenses[' . $i . '][type]" style="width: 100%; box-sizing: border-box;">
											<option value="evaluation" ' . ( $license['type'] == 'evaluation' ? 'selected' : '' ) . '>evaluation</option>
											<option value="developer" ' . ( $license['type'] == 'developer' ? 'selected' : '' ) . '>developer</option>
											<option value="basic" ' . ( $license['type'] == 'basic' ? 'selected' : '' ) . '>basic</option>
											<option value="standard" ' . ( $license['type'] == 'standard' ? 'selected' : '' ) . '>standard</option>
											<option value="business" ' . ( $license['type'] == 'business' ? 'selected' : '' ) . '>business</option>
											<option value="simple" ' . ( $license['type'] == 'simple' ? 'selected' : '' ) . '>simple</option>
											<option value="corporate" ' . ( $license['type'] == 'corporate' ? 'selected' : '' ) . '>corporate</option>
											<option value="enterprise" ' . ( $license['type'] == 'enterprise' ? 'selected' : '' ) . '>enterprise</option>
											<option value="unlimited" ' . ( $license['type'] == 'unlimited' ? 'selected' : '' ) . '>unlimited</option>
										</select>
									</td>
									<td><input type="text" value="' . esc_attr( $license['key'] ) . '" name="ajaxzoom_licenses[' . $i . '][key]" /></td>
									<td><input type="text" value="' . esc_attr( $license['error200'] ) . '" name="ajaxzoom_licenses[' . $i . '][error200]" /></td>
									<td><input type="text" value="' . esc_attr( $license['error300'] ) . '" name="ajaxzoom_licenses[' . $i . '][error300]" /></td>
								</tr>';
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="6"><a href="#" class="add button"><?php _e( '+ Add License', 'ajaxzoom' ); ?></a> 
							<a href="#" class="remove_rows button"><?php _e( 'Remove selected license(s)', 'ajaxzoom' ); ?></a></th>
						</tr>
					</tfoot>
				</table>
				<script type="text/javascript">
					jQuery( function() {
						jQuery( '#ajaxzoom_licenses' ).on( 'click', 'a.add', function() {

							var size = jQuery( '#ajaxzoom_licenses' ).find( 'tbody .license' ).size();

							jQuery( '<tr class="license">\
									<td>&nbsp;</td>\
									<td><input type="text" name="ajaxzoom_licenses[' + size + '][domain]" /></td>\
									<td>\
										<select name="ajaxzoom_licenses[' + size + '][type]">\
											<option value="evaluation">evaluation</option>\
											<option value="developer">developer</option>\
											<option value="basic">basic</option>\
											<option value="standard">standard</option>\
											<option value="business">business</option>\
											<option value="simple">simple</option>\
											<option value="corporate">corporate</option>\
											<option value="enterprise">enterprise</option>\
											<option value="unlimited">unlimited</option>\
										</select>\
									</td>\
									<td><input type="text" name="ajaxzoom_licenses[' + size + '][key]" /></td>\
									<td><input type="text" name="ajaxzoom_licenses[' + size + '][error200]" /></td>\
									<td><input type="text" name="ajaxzoom_licenses[' + size + '][error300]" /></td>\
								</tr>' ).appendTo( '#ajaxzoom_licenses table tbody' );

							return false;
						} );
					} );
				</script>
			</td>
		</tr>
		<?php
	}

	public static function config_vendor_settings()
	{
		self::$config_vendor = array(
			'oneSrcImg' => true,
			'heightRatioOneImg' => 1.0,
			'width' => 800,
			'height' => 800,
			'maxSizePrc' => '1.0|auto|-50',
			'thumbSliderPosition' => 'bottom',
			'galleryAxZmThumbSliderParamHorz' => '{
	"thumbLiStyle": {
		"borderRadius": 3
	},
	"btn": true,
	"btnClass": "axZmThumbSlider_button_new",
	"btnHidden": true,
	"btnOver": false,
	"scrollBy": 1,
	"centerNoScroll": true
}',
			'galleryAxZmThumbSliderParamVert' => '{
	"thumbLiStyle": {
		"borderRadius": 3
	},
	"btn": true,
	"btnClass": "axZmThumbSlider_button_new",
	"btnHidden": true,
	"btnOver": false,
	"scrollBy": 1,
	"centerNoScroll": true
}',
			'axZmCallBacks' => '{
onFullScreenReady: function() {
	// Here you can place you custom code
}
}',
			'cropAxZmThumbSliderParam' => '{
	"btn": true,
	"btnClass": "axZmThumbSlider_button_new",
	"btnHidden": true,
	"centerNoScroll": true,
	"thumbImgWrap": "azThumbImgWrapRound",
	"scrollBy": 1
}',
			'zoomWidth' => '.summary|+40',
			'zoomHeight' => '.summary,#axZm_mouseOverWithGalleryContainer',
			'showTitle' => true,
			'autoFlip' => 120,
			'fullScreenApi' => true,
			'prevNextArrows' => true,
			'cropSliderThumbAutoMargin' => 7,
			'fsAxZmThumbSliderParam' => '{
	"scrollBy": 1,
	"btn": true,
	"btnClass": "axZmThumbSlider_button_new",
	"btnLeftText": null,
	"btnRightText": null,
	"btnHidden": true,
	"pressScrollSnap": true,
	"centerNoScroll": true,
	"wrapStyle": {
		"borderWidth": 0
	}
}'
		);

	}

	public static function init_az_mouseover_settings()
	{
		if (!self::$mouseover_settings) {
			include dirname(dirname(__FILE__)).'/classes/AzMouseoverSettings.php';
			self::config_vendor_settings();
			self::$mouseover_settings = new AzMouseoverSettings(array(
				'config_vendor' => self::$config_vendor,
				'vendor' => 'WooCommerce',
				'exclude_opt_vendor' => array(
					'axZmPath',
					'lang',
					'images',
					'images360',
					'videos',
					'enableNativeSlider',
					'enableCssInOtherPages',
					'default360settingsEmbed',
					'defaultVideoYoutubeSettings',
					'defaultVideoVimeoSettings',
					'defaultVideoDailymotionSettings',
					'defaultVideoVideojsSettings'
				),
				'exclude_cat_vendor' => array(),
				'config_extend' => array(
					'showDefaultForVariation' => array(
						'prefix' => 'AJAXZOOM',
						'useful' => true,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => false,
						'options' => null,
						'comment' => array(
							'EN' => '
								Show default images for selected variation.
							',
							'DE' => '
								Show default images for selected variation.
							'
						)
					),
					'showDefaultForVariation360' =>array(
						'prefix' => 'AJAXZOOM',
						'useful' => true,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => true,
						'options' => null,
						'comment' => array(
							'EN' => '
								Show 360 view for selected variation if there is no specific 360 for that variation.
							',
							'DE' => '
								Show 360 view for selected variation if there is no specific 360 for that variation.
							'
						)
					),
					'show360noVariationSelected' =>array(
						'prefix' => 'AJAXZOOM',
						'useful' => true,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => false,
						'options' => null,
						'comment' => array(
							'EN' => '
								Show 360 view for variable product also, when no variation is selected. 
								Only 360, which are not assigned to a specific variations will be shown.
							',
							'DE' => '
								Show 360 view for variable product also, when no variation is selected. 
								Only 360, which are not assigned to a specific variations will be shown.
							'
						)
					),
					'wpmlTranslateIDs' => array(
						'prefix' => 'AJAXZOOM',
						'useful' => true,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => true,
						'options' => null,
						'comment' => array(
							'EN' => '
								If WPML plugin is enabled, load 360 etc. from main language product. 
								This way you do not need to copy AJAX-ZOOM media to additional languages, 
								but define a 360 in main / default language only. 
							',
							'DE' => '
								If WPML plugin is enabled, load 360 etc. from main language product. 
								This way you do not need to copy AJAX-ZOOM media to additional languages, 
								but define a 360 in main / default language only.
							'
						)
					),
					'productTableAdminExtend' => array(
						'prefix' => 'AJAXZOOM',
						'useful' => false,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => true,
						'options' => null,
						'comment' => array(
							'EN' => '
								Add sortable columns to the product table in admin view 
								with the number of 360 / 3D and videos for each product.
							',
							'DE' => '
								Zu der Produkttabelle in der Administratoransicht sortierbare Spalten 
								mit der Anzahl von 360 und Videos für jedes Produkt hinzufügen.
							'
						)
					),
					'deleteSupercache' => array(
						'prefix' => 'AJAXZOOM',
						'useful' => false,
						'type' => 'bool',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'switch',
						'height' => null,
						'default' => false,
						'options' => null,
						'comment' => array(
							'EN' => '
								Delete cache of WP supercache plugin if new 360 is added or deleted without saving the post.
							',
							'DE' => '
								Löschen Sie den Cache des WP-Supercache-Plugins, wenn der neue 360 hinzugefügt oder gelöscht wird, ohne den Post zu speichern.
							'
						)
					),
					'appendToContainer' => array(
						'prefix' => 'AJAXZOOM',
						'important' => true,
						'type' => 'string',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'text',
						'height' => null,
						'default' => '.images',
						'options' => null,
						'comment' => array(
							'EN' => '
								Selector to append AJAX-ZOOM to a specific container on product detail page. 
								On default this is .images (container with CSS class "images"). 
								In some "fancy" themes the container with class images just does not exist. 
								In browser js console you will see
								"AJAX-ZOOM: container selector ".images" not present in this theme" message 
								and you will see no AJAX-ZOOM accordingly. So please inspect with 
								dev tools (press e.g. F12 in Chrome browser and select "Elements" tab) 
								the DOM elements and choose a container where you would like AJAX-ZOOM 
								to be appended. But even if container with CSS class images does exist, 
								you might want to attach AJAX-ZOOM to a different container just because 
								you want it this way and know what you are doing or container with CSS class 
								images is not the right one (the class name is misused for other purposes and 
								you might then want to define on of the parent containers). 
							',
							'DE' => '
								Selector to append AJAX-ZOOM to a specific container on product detail page. 
								On default this is .images (container with CSS class "images"). 
								In some "fancy" themes the container with class images just does not exist. 
								In browser js console you will see
								"AJAX-ZOOM: container selector ".images" not present in this theme" message 
								and you will see no AJAX-ZOOM accordingly. So please inspect with 
								dev tools (press e.g. F12 in Chrome browser and select "Elemets" tab) 
								the DOM elements and choose a container where you would like AJAX-ZOOM 
								to be appended. But even if container with CSS class images does exist, 
								you might want to attach AJAX-ZOOM to a different container just because 
								you want it this way and know what you are doing or container with CSS class 
								images is not the right one (the class name is misused for other purposes and 
								you might then want to define on of the parent containers).
							'
						)
					),
					'appendToContCss' => array(
						'prefix' => 'AJAXZOOM',
						'important' => true,
						'type' => 'string',
						'isJsObject' => false,
						'isJsArray' => false,
						'display' => 'text',
						'height' => null,
						'default' => '{"height": "auto"}',
						'options' => null,
						'comment' => array(
							'EN' => '
								Additional CSS to the container where AJAX-ZOOM will be appended to. 
								Must be valid JSON string, e.g. {"height": "auto", "width": "100%"}
							',
							'DE' => '
								Additional CSS to the container where AJAX-ZOOM will be appended to. 
								Must be valid JSON string, e.g. {"height": "auto", "width": "100%"}
							'
						)
					)
				)
			));
		}
	}

	public static function settings_data() {
		if ( !empty(self::$fields_list) ) {
			return self::$fields_list;
		}

		self::init_az_mouseover_settings();
		$cfg = self::$mouseover_settings->getConfig();
		$cat = self::$mouseover_settings->getCategories();

		$current_cat = '';
		$field_map = array(
			'textarea' => 'textarea',
			'text' => 'text',
			'switch' => 'switch',
			'select' => 'select'
		);

		self::$categories = array();

		foreach ($cfg as $k => $v) {
			$varname = $v['prefix'].'_'.strtoupper($k);
			$category = $v['category'];

			if ($category != $current_cat) {
				$current_cat = $category;
				if (!array_key_exists($current_cat, self::$categories)) {
					self::$categories[$current_cat] = self::$mouseover_settings->cleanComment($cat[$category]['title']['EN']);
				}
			}

			$arr_option = array();
			$arr_option['title'] = $k;
			$arr_option['category'] = $category;
			$arr_option['type'] = $field_map[$v['display']];
			$arr_option['comment'] = self::$mouseover_settings->cleanComment($v['comment']['EN']);

			if (isset($v['default'])) {
				if ($v['default'] === true || $v['default'] === false || $v['default'] === null) {
					$arr_option['default'] = strtolower(var_export($v['default'], true));
				} else {
					$arr_option['default'] = $v['default'];
				}
			} else {
				$arr_option['default'] = '';
			}

			if ($v['display'] == 'select' && is_array($v['options']) && !empty($v['options'])) {
				$arr_option['values'] = $v['options'];
			}

			if (isset($v['isJsObject']) && $v['isJsObject'] == true) {
				$arr_option['isJsObject'] = true;
			}

			if (isset($v['isJsArray']) && $v['isJsArray'] == true) {
				$arr_option['isJsArray'] = true;
			}

			if (isset($v['important']) && $v['important'] == true) {
				$arr_option['important'] = true;
			}

			if (isset($v['useful']) && $v['useful'] == true) {
				$arr_option['useful'] = true;
			}

			self::$fields_list[$varname] = $arr_option;
		}

		self::$categories['license'] = __('License', 'ajaxzoom');
		self::$categories['reset'] = __('Actions', 'ajaxzoom');

		self::$fields_list['AJAXZOOM_LICENSES'] = array(
			'category' => 'license',
			'comment' => '',
			'default' => '',
			'type' => 'ajaxzoom_licenses'
		);

		self::$fields_list['AJAXZOOM_RESETOPTIONS'] = array(
			'category' => 'reset',
			'comment' => '',
			'default' => '',
			'type' => 'ajaxzoom_resetoptions'
		);

		self::$fields_list['AJAXZOOM_THEMESETTINGS'] = array(
			'category' => 'plugin_settings',
			'comment' => '',
			'default' => maybe_unserialize(file_get_contents(dirname(__DIR__).'/themesettings/theme_settings_default.txt')),
			'type' => 'ajaxzoom_themesettings'
		);

		return self::$fields_list;
	}

	public static function get_product_plugin_opt( $id_product = 0 ) {
		global $wpdb;
		$db_prefix = $wpdb->prefix;

		$conf = $wpdb->get_row( 'SELECT * FROM `'.$db_prefix.'ajaxzoomproductsettings` 
			WHERE id_product = '.(int)$id_product, ARRAY_A
		);

		if ( isset( $conf['psettings'] ) ) {
			return unserialize( $conf['psettings'] );
		} else {
			return array();
		}
	}

	public static function extend_product_individual_settings($cfg, $id_product)
	{
		$ccfg = self::get_product_plugin_opt($id_product);
		if (is_array($ccfg) && !empty($ccfg) && is_array($cfg)) {
			foreach ($ccfg as $k => $v) {
				if (isset(self::$mouseover_settings->config[$k])) {
					$prefix = self::$mouseover_settings->config[$k]['prefix'].'_';
					$k = strtoupper($k);
					if (isset($cfg[$prefix.$k])) {
						$cfg[$prefix.$k] = $v;
					}
				}
			}
		}

		return $cfg;
	}

	public static function test_id_media( $id_media ) {
		if ( stristr( $id_media, '_' ) ) {
			$arr = explode( '_', $id_media);
			return (int)$arr[0] . '_' . (int)$arr[1];
		} else {
			return (int)$id_media . '';
		}
	}

	public static function has_img_hotspots( $id_media ) {
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$id_media = self::test_id_media($id_media);

		$row = $wpdb->get_row('SELECT id FROM `'.$db_prefix.'ajaxzoomimagehotspots` WHERE id_media = \''.$id_media.'\' LIMIT 1', ARRAY_A);
		if (isset( $row['id'] ) && $row['id']) {
			return 1;
		} else {
			return 0;
		}
	}

	public static function ajaxzoom_woo_media_scripts() {
		global $wp_version;
		$r = array();
		$r['css'] = '';
		$r['js'] = '';

		foreach( self::$pre_css as $k => $v ) {
			$r['css'] .= '<link rel="stylesheet" href="'.$v['src'].'?ver='.$v['ver'].'" type="text/css" media="all" />'."\n";
		}

		foreach( self::$pre_scripts as $k => $v ) {
			$r['js'] .= '<script type="text/javascript" src="'.$v['src'].'?ver='.$v['ver'].'"></script>'."\n";
		}

		return $r;
	}

	public static function ajaxzoom_woo_media_handler($atts, $content = null) {
		global $wpdb, $product, $wp_version;
		$html = '';

		$op = shortcode_atts( array(
			'ajaxzoom' => 'iframe',
			'pid' => '0',
			'id_360' => '0',
			'id_product' => '0',
			'id_image' => '0',
			'id_video' => '0',
			'example' => '',
			'prop_w' => '16',
			'prop_h' => '9',
			'no_gallery' => '0',
			'heading' => '',
			'border_width' => '1',
			'border_color' => '',
			'enabled' => '1'
		), $atts );

		if ($op['enabled'] == '0') {
			return '';
		}

		$null_ok = array( 'border_width' );
		$not_pass = array( 'enabled' );

		$err_txt_tpl = '<div style="color: transparent">{err_txt}</div>';

		if ( $op['id_360'] != '0' ) {
			$iframeID = 'ajaxzoom_woo_media_id_360_'.$op['id_360'];
		} elseif ( $op['id_product'] != '0' ) {
			if ( !$product ) {
				$product = self::get_product_variable( (int)$op['id_product'] );
				if ( !$product ) {
					$html .= str_replace('{err_txt}', 'Product not found', $err_txt_tpl);
				}
			}

			$iframeID = 'ajaxzoom_woo_media_id_product_'.$op['id_product'];
		} elseif ($op['id_image'] != '0') {
			$iframeID = 'ajaxzoom_woo_media_id_image_'.md5( $op['id_image'] );
		} elseif ($op['id_video'] != '0') {
			$iframeID = 'ajaxzoom_woo_media_id_video_'.$op['id_video'];
		} else {
			$html .= str_replace('{err_txt}', 'Wrong parameters in short code', $err_txt_tpl);
		}

		if ($html) {
			return $html;
		}

		if ($op['heading']) {
			$html .= '<h3>'.$op['heading'].'</h3>';
		}

		unset($op['heading']);

		/*
		$html .= self::print_az_scripts(array(
			'ajaxzoom/axZm/axZm.iframe.js',
			'ajaxzoom/axZm/extensions/jquery.axZm.embedResponsive.js'
		));
		*/

		$html .= '
<div id="'.$iframeID.'_parent" class="az_embed-responsive">
	<iframe src="{src_tag}" id="'.$iframeID.'" class="az_embed-responsive-item" border="0" scrolling="no" allowfullscreen></iframe>
</div>
<script type="text/javascript">
	if (!window.az_docReady) {
		(function(g,b){function c(){if(!e){e=!0;for(var a=0;a<d.length;a++)d[a].fn.call(window,d[a].ctx);d=[]}}function h(){
		"complete"===document.readyState&&c()}b=b||window;var d=[],e=!1,f=!1;b[g||"docReady"]=function(a,b){
		e?setTimeout(function(){a(b)},1):(d.push({fn:a,ctx:b}),"complete"===document.readyState?setTimeout(c,1):f||
		(document.addEventListener?(document.addEventListener("DOMContentLoaded",c,!1),window.addEventListener("load",c,!1))
		:(document.attachEvent("onreadystatechange",h),window.attachEvent("onload",c)),f=!0))}})("az_docReady",window);
	}

	window.az_embed_woo_media_'.$iframeID.' = function() {
		jQuery("#'.$iframeID.'_parent")
		.axZmEmbedResponsive( {
			ratio: "'.floatval($op['prop_w']).':'.floatval($op['prop_h']).'",
			heightLimit: 94
		} );
	};

	if (window.jQuery) {
		window.az_embed_woo_media_'.$iframeID.'();
	} else {
		window.az_docReady( window.az_embed_woo_media_'.$iframeID.' );
	}

</script>
';
		unset($op['heading']);

		foreach($op as $k => $v) {
			if ((!in_array($k, $null_ok) && (!$v || $v == '0')) || in_array($k, $not_pass)) {
				unset($op[$k]);
			}
		}

		$src = self::uri() . '/ajaxzoom/preview/iframe.php?' . http_build_query($op);
		$html = str_replace('{src_tag}', $src, $html);

		//wp_reset_query();
		return $html;
	}

	public static function register_shortcodes() {
		add_shortcode( 'ajaxzoom_woo_media', array( 'ajaxzoom', 'ajaxzoom_woo_media_handler' ) );
	}

	public static function show_batch_tool() {
		$uri = self::uri();
		include self::dir() . 'templates/backend/batch.php';
	}

	public static function register_batch_submenu() {
		add_submenu_page( 
			'woocommerce',
			'AJAX-ZOOM Batch Tool',
			'AJAX-ZOOM<br>Batch Tool',
			'manage_options',
			'ajaxzoombatch',
			array('ajaxzoom', 'show_batch_tool') 
		);
	}
}

Ajaxzoom::$axzmh;
Ajaxzoom::$zoom;
