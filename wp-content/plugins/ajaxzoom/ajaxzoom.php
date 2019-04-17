<?php
/**
* @package Ajaxzoom
* @version 1.1.17
*/
/*
Plugin Name: AJAX-ZOOM
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
Description: Combination of responsive mouseover zoom, 360 degree / 3D player with deep zoom, extensive "Product Tours" for 360 views, hotspots for 360 views and regular images, additional variation images upload, videos (YouTube, Vimeo, Dailymotion, mp4 sources) and thumbnail slider for WooCommerce product detail view.
Author: AJAX-ZOOM
Text Domain: ajaxzoom
Domain Path: /languages
Version: 1.1.17
Author URI: http://www.ajax-zoom.com/
*/
/*
License: Commercial, http://www.ajax-zoom.com/index.php?cid=download
*/
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	global $ajaxzoom_db_version, $ajaxzoom_config_version, $ajaxzoom_folder_permissions, $ajaxzoom_files_permissions;

	/**
	* Folder and files permissions
	*/
	$ajaxzoom_folder_permissions = '0777';
	$ajaxzoom_files_permissions = '0644';

	/**
	* DB and configuration versions
	* If they differ from the values in the DB,
	* the correstpong install / update methods will run
	*/
	$ajaxzoom_db_version = '1.1.16';
	$ajaxzoom_config_version = '1.1.16';

	/**
	* Include AJAX-ZOOM classes
	*/
	include_once 'includes/class.ajaxzoom.php';
	include_once 'includes/class.ajaxzoom-ajax.php';
	Ajaxzoom::pre_run();

	set_include_path( get_include_path() . PATH_SEPARATOR . ABSPATH . 'wp-content/plugins/ajaxzoom/includes/admin' );

	/**
	* Install db schema and other things when plugin is activated
	*/
	register_activation_hook( __FILE__, array( 'ajaxzoom', 'install' ) );

	/**
	Add admin notice
	*/
	add_action( 'admin_notices', array( 'ajaxzoom', 'install_after_notices' ) );

	/**
	* Create Ajaxzoom settings in woocommerce
	*/
	add_filter( 'woocommerce_get_settings_pages', array( 'ajaxzoom', 'woocommerce_get_settings_pages' ) );

	/**
	* Special fields
	*/
	add_action( 'woocommerce_admin_field_ajaxzoom_licenses', array( 'ajaxzoom', 'woocommerce_admin_field_ajaxzoom_licenses' ) );
	add_action( 'woocommerce_admin_field_ajaxzoom_resetoptions', array( 'ajaxzoom', 'woocommerce_admin_field_ajaxzoom_resetoptions' ) );
	add_action( 'woocommerce_admin_field_ajaxzoom_themesettings', array( 'ajaxzoom', 'woocommerce_admin_field_ajaxzoom_themesettings' ) );

	/**
	* Save actions for special fields
	*/
	add_action( 'woocommerce_update_options_ajaxzoom', array( 'ajaxzoom', 'save_ajaxzoom_licenses' ) );
	add_action( 'woocommerce_update_options_ajaxzoom', array( 'ajaxzoom', 'save_ajaxzoom_themesettings' ) );

	/**
	* Load JS/CSS admin
	*/
	add_action( 'admin_enqueue_scripts', array( 'ajaxzoom', 'media_admin' ) );

	/**
	* Load JS/CSS frontend
	*/
	add_action( 'wp_enqueue_scripts', array( 'ajaxzoom', 'media_frontend' ), 9999999999999999 );
	Ajaxzoom::player_in_frame();

	/**
	* Inject the AJAX-ZOOM into product page on the Front-End
	*/
	add_action( 'woocommerce_product_thumbnails', array('ajaxzoom', 'display_example_32' ), 1 );
	add_action( 'woocommerce_after_single_product', array('ajaxzoom', 'display_example_32' ), 1 );

	/**
	* Inject AJAX-ZOOM boxes into product page on the Backend-End
	*/
	add_action( 'add_meta_boxes', array( 'ajaxzoom', 'backend_output' ), 40 );

	/**
	* Delete product hook
	*/
	add_action( 'before_delete_post', array( 'ajaxzoom', 'delete_product' ) );

	/**
	* Delete removed images on save of product
	*/
	add_filter( 'content_save_pre', array('ajaxzoom', 'on_before_post_save'), 10, 1 );
	add_action( 'save_post', array( 'ajaxzoom', 'on_after_post_save' ), 9999999999999999, 3);
	add_action( 'woocommerce_init', array('ajaxzoom', 'woocommerce_save_variations_before'), 1, 0);
	add_action( 'woocommerce_save_product_variation', array('ajaxzoom', 'woocommerce_save_variations_after'), 1, 2);

	/**
	* Add sortable axzm_360, axzm_video colums and number items value to products list for admin
	*/
	if (get_option( 'ajaxzoom_producttableadminextend' ) == 'true') {
		add_filter( 'manage_edit-product_columns', array('ajaxzoom', 'on_product_columns_register'), 10, 1 );
		add_action( 'manage_product_posts_custom_column', array('ajaxzoom', 'on_product_column_field'), 10, 2 );
		add_filter( 'manage_edit-product_sortable_columns', array('ajaxzoom', 'on_product_column_field_sort_register'), 10, 1 );
		add_filter( 'posts_clauses', array('ajaxzoom', 'on_posts_clauses'), 10, 2);
	}

	/**
	* Add shortcodes
	*/
	add_action( 'init', array('ajaxzoom', 'register_shortcodes'));

	/**
	* Add links to plugin page
	*/
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array('ajaxzoom', 'add_action_links') );
	add_filter( 'plugin_row_meta', array('ajaxzoom', 'add_meta_links'), 10, 2 );

	/**
	* Add Batch Tool to the submenu of woocommerce
	*/
	add_action('admin_menu', array('ajaxzoom', 'register_batch_submenu'), 120);
	
	/**
	* Remove DB and plugin options when plugin is removed (deleted)
	*/
	register_uninstall_hook(__FILE__, array('ajaxzoom', 'uninstall'));
}
