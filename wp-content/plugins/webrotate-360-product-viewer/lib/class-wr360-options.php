<?php
/*
    Plugin Name: WebRotate 360 Product Viewer for WordPress
    Plugin URI: http://www.webrotate360.com/360-product-viewer.html
    Description: WebRotate 360 Product Viewer WordPress Integration
    Version: 3.1.5
    Author: WebRotate 360 LLC
    Author URI: http://www.webrotate360.com
    License: GPLv2
*/

if (!defined("ABSPATH"))
    exit;

class WR360Options
{
    public function __construct()
    {
        add_action("admin_init", array($this, "admin_register_webrotate360_settings"));
        add_action("admin_menu", array($this, "admin_webrotate360_menu"));
    }

    public function admin_webrotate360_menu()
    {
        add_options_page(
            "WebRotate 360 Product Viewer Options",
            "WebRotate 360",
            "manage_options",
            "webrotate360-menu",
            array($this, "admin_webrotate360_options"));
    }

    public function admin_webrotate360_options()
    {
        include (plugin_dir_path(__FILE__) . "templates/optionspage.php");
    }

    public function admin_register_webrotate360_settings()
    {
        register_setting("webrotate360-settings-group", "wr360_viewer_width");
        register_setting("webrotate360-settings-group", "wr360_viewer_height");
        register_setting("webrotate360-settings-group", "wr360_viewer_basewidth");
        register_setting("webrotate360-settings-group", "wr360_viewer_minheight");
        register_setting("webrotate360-settings-group", "wr360_viewer_skin");
        register_setting("webrotate360-settings-group", "wr360_popup_skin");
        register_setting("webrotate360-settings-group", "wr360_prettyphoto_off");
        register_setting("webrotate360-settings-group", "wr360_master_config");
        register_setting("webrotate360-settings-group", "wr360_license_path");
        register_setting("webrotate360-settings-group", "wr360_use_googleevents");
        register_setting("webrotate360-settings-group", "wr360_graphics_path");
        register_setting("webrotate360-settings-group", "wr360_api_ready_callback");
        register_setting("webrotate360-settings-group", "wr360_background_color");
        register_setting("webrotate360-settings-group", "wr360_gallery_woo_all");
    }
}
