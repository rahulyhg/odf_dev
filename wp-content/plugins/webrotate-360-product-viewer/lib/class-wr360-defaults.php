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

class WR360DefaultsConfig
{
    public $viewerWidth;
    public $viewerHeight;
    public $baseWidth;
    public $minHeight;
    public $config;
    public $viewerSkin;
    public $popupSkin;
    public $licensePath;
    public $name;
    public $callback;
    public $rootPath;
    public $isPopupGallery;
    public $includePrettyPhoto = true;
    public $useGoogleEvents = false;
    public $background;
    public $graphicsPath;
    public $inBrowserFullscreen = false;
    public $useWooGalleryForAll = false;
	public $title;

    public function init_header()
    {
        $this->viewerSkin = sanitize_text_field(get_option("wr360_viewer_skin"));
        if (empty($this->viewerSkin))
            $this->viewerSkin = "basic";

        $this->popupSkin = sanitize_text_field(get_option("wr360_popup_skin"));
        if (empty($this->popupSkin))
            $this->popupSkin = "light_clean";
        else if ($this->popupSkin == "default")
        {
            // This is for backwards compatibility if user had this value (default) saved in older version.
            $this->popupSkin = "pp_default";
        }

        $this->licensePath = esc_url(get_option("wr360_license_path"));
        if (empty($this->licensePath))
            $this->licensePath = plugins_url("webrotate-360-product-viewer/license.lic");

        $dontIncludePrettyPhoto = get_option("wr360_prettyphoto_off");
        if (!empty($dontIncludePrettyPhoto))
            $this->includePrettyPhoto = false;

        $useGoogleEvents = get_option("wr360_use_googleevents");
        if (!empty($useGoogleEvents))
            $this->useGoogleEvents = true;

        $useWooGalleryForAll = get_option("wr360_gallery_woo_all");
        if (!empty($useWooGalleryForAll))
            $this->useWooGalleryForAll = true;
    }

    public function init_shortcode($atts)
    {
        $this->init_header();

        $extract = shortcode_atts(
            array(
                "name"         => "",
                "width"        => "",
                "height"       => "",
                "rootpath"     => "",
                "config"       => "",
                "basewidth"    => "",
                "minheight"    => "",
                "gallery"      => "false",
                "imageopacity" => "",
                "viewerskin"   => "",
                "background"   => "",
                "callback"     => "",
                "browserfs"    => "",
				"title"        => ""), $atts);

        $this->viewerWidth  = str_ireplace("px", "", sanitize_text_field(get_option("wr360_viewer_width")));
        $this->viewerHeight = str_ireplace("px", "", sanitize_text_field(get_option("wr360_viewer_height")));
        $this->baseWidth    = str_ireplace("px", "", sanitize_text_field(get_option("wr360_viewer_basewidth")));
        $this->minHeight    = str_ireplace("px", "", sanitize_text_field(get_option("wr360_viewer_minheight")));
        $this->callback     = sanitize_text_field(get_option("wr360_api_ready_callback"));
        $this->background   = sanitize_text_field(get_option("wr360_background_color"));
        $this->config       = esc_url(get_option("wr360_master_config"));
        $this->graphicsPath = esc_url(get_option("wr360_graphics_path"));

        if (empty($this->viewerWidth))
            $this->viewerWidth = 300;
        if (empty($this->viewerHeight))
            $this->viewerHeight = 300;
        if (empty($this->baseWidth))
            $this->baseWidth = 0;
        if (empty($this->minHeight))
            $this->minHeight = 0;
        if (empty($this->graphicsPath))
            $this->graphicsPath = plugins_url("webrotate-360-product-viewer/graphics");

        if (!empty($extract["width"]))
            $this->viewerWidth = str_ireplace("px", "", sanitize_text_field($extract["width"]));
        if (!empty($extract["height"]))
            $this->viewerHeight = str_ireplace("px", "", sanitize_text_field($extract["height"]));
        if (!empty($extract["basewidth"]))
            $this->baseWidth = str_ireplace("px", "", sanitize_text_field($extract["basewidth"]));
        if (!empty($extract["minheight"]))
            $this->minHeight = str_ireplace("px", "", sanitize_text_field($extract["minheight"]));
        if (!empty($extract["config"]))
            $this->config = esc_url($extract["config"]);
        if (!empty($extract["viewerskin"]))
            $this->viewerSkin = sanitize_text_field($extract["viewerskin"]);
        if (!empty($extract["callback"]))
            $this->callback = sanitize_text_field($extract["callback"]);
        if (!empty($extract["background"]))
            $this->background = sanitize_text_field($extract["background"]);
        if (!empty($extract["browserfs"]))
            $this->inBrowserFullscreen = sanitize_text_field($extract["browserfs"]);
		if (!empty($extract["title"]))
            $this->title = sanitize_text_field($extract["title"]);

        // Just to keep things short in script.
        if (empty($this->callback))
            $this->callback = "wr360_callback_not_set";

        $this->name = preg_replace('/\s+/', '', $extract["name"]);
        $this->rootPath = esc_url($extract["rootpath"]);

        $this->isPopupGallery = $extract["gallery"];
        if (!empty($this->isPopupGallery))
            $this->isPopupGallery = ($this->isPopupGallery === "true");
    }
}
