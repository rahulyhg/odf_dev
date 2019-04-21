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

define("WR360_VERSION", "3.1.5");

require_once plugin_dir_path(__FILE__) . "lib/class-wr360-defaults.php";
require_once plugin_dir_path(__FILE__) . "lib/class-wr360-options.php";
require_once plugin_dir_path(__FILE__) . "lib/class-wr360-shortcodes.php";
require_once plugin_dir_path(__FILE__) . "lib/class-wr360-woocommerce.php";


class WR360Main
{
    private $wr360WooComm;
    private $wr360Options;
    private $wr360OShortCodes;

    public function __construct()
    {
        if (in_array("woocommerce/woocommerce.php", apply_filters("active_plugins", get_option("active_plugins"))))
        {
            $this->wr360WooComm = new WR360WooCommerce();
        }

        if (is_admin())
        {
            $this->wr360Options = new WR360Options();
            add_action("admin_notices", array($this, "add_action_webrotate_update_notice"));

        }
        else
        {
            $this->wr360OShortCodes = new WR360ShortCodes();
            add_action("wp_enqueue_scripts",  array($this, "add_action_webrotate_styles"));
            add_filter("the_content", array($this, "add_filter_webrotate_embed_viewer"));
        }
    }

    function add_action_webrotate_update_notice()
    {
        $updateNoteOption = "wr360_plugin_upgraded_21";
        $updateNoteValue  = get_option($updateNoteOption);

        if (empty($updateNoteValue))
            $updateNoteValue = 1;

        // Show the warning twice just in case it was missed once.
        if ($updateNoteValue <= 2)
        {
            echo "<div class='updated'><p>WebRotate 360 Product Viewer for WordPress has been installed. If you have WebRotate 360 PRO or Enterprise license, please verify that it's configured
            under Settings->WebRotate 360->License Path and that the path points to an existing license file on your server.
            <br/><br/><strong>IMPORTANT: all files and folders are deleted from the WebRotate360 plugin folder by WordPress automatically upon the plugin update, so if you upload your WebRotate 360 assets (or license file) under the plugin folder,
            they will be deleted upon automatic update of the plugin. We recommend to host these files outside of the WebRotate 360 plugin folder.</strong>
            </p></div>";

            $updateNoteValue++;
            update_option($updateNoteOption, $updateNoteValue);
        }
    }

    function add_action_webrotate_styles()
    {
        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_header();

        wp_enqueue_script("jquery");

        if ($defConfig->includePrettyPhoto == true)
        {
            wp_register_script("prettyphotojs", plugins_url("prettyphoto/js/jquery.prettyPhoto.js", __FILE__), array("jquery"), WR360_VERSION);
            wp_register_style("prettyphotocss", plugins_url("prettyphoto/css/prettyphoto.css", __FILE__), false, WR360_VERSION);
            wp_enqueue_script("prettyphotojs");
            wp_enqueue_style("prettyphotocss");
        }

        if (!empty($this->wr360WooComm))
        {
            wp_register_style("wr360overrides", plugins_url("public/woo-overrides.css", __FILE__), false, WR360_VERSION);
            wp_enqueue_style("wr360overrides");
        }

        wp_register_script("wr360wpscript", plugins_url("public/webrotate360.js", __FILE__), array("jquery"), WR360_VERSION);
        wp_enqueue_script("wr360wpscript");

        wp_register_script("wr360script", plugins_url("imagerotator/html/js/imagerotator.js", __FILE__), array("jquery"), WR360_VERSION);
		wp_register_style("wr360style", plugins_url("imagerotator/html/css/" . $defConfig->viewerSkin . ".css", __FILE__), false, WR360_VERSION);
        wp_enqueue_style("wr360style");
        wp_enqueue_script("wr360script");
    }

    function add_filter_webrotate_embed_viewer($content)
    {
        // This filter is called for every content block on a page. We just need it once to render our global functions, so can "unhook" it now.
        remove_filter("the_content", array($this, "add_filter_webrotate_embed_viewer"));

        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_header();

        if ($defConfig->includePrettyPhoto == true)
        {
            $content .= "<script language='javascript' type='text/javascript'>";
            $content .= "function getWR360PopupSkin(){return '" . $defConfig->popupSkin . "';}";
            $content .= "</script>";
        }

		return ($content);
    }
}

$wr360Main = new WR360Main();
