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

class WR360WooCommerce
{
    private $wooVersion;

    public function __construct()
    {
        $this->wooVersion = $this->get_woocommerce_version();

        if (is_admin())
        {
            add_action("woocommerce_product_write_panel_tabs", array($this, "webrotate360_tab_options_tab"));
            add_action("woocommerce_product_write_panels", array($this, "webrotate360_tab_options"));
            add_action("woocommerce_process_product_meta", array($this, "webrotate360_save_product"));
        }
        else
        {
            if (version_compare($this->wooVersion, "3.0", ">=" ))
                add_filter("woocommerce_single_product_image_thumbnail_html", array($this, "webrotate360_replace_product_image"), 100, 2);
            else
                add_filter("woocommerce_single_product_image_html", array($this, "webrotate360_replace_product_image_old"), 100, 2);
        }
    }

    public function webrotate360_replace_product_image($content, $thumbId)
    {
        global $post;

        if (empty($post->ID))
            return ($content);

        $wr360config = esc_url(get_post_meta($post->ID, "_wr360config", true));
        $wr360root = esc_url(get_post_meta($post->ID, "_wr360root", true));

        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_shortcode(array("name" => $post->ID, "config" => $wr360config, "rootpath" => $wr360root));

        $isViewConfigured = !(empty($wr360config) && empty($wr360root));
        if (!$isViewConfigured && !$defConfig->useWooGalleryForAll)
            return ($content);

        if ($thumbId !== get_post_thumbnail_id($post->ID))
            return ("");

        $html = "<div class='wr360woowrap'>";
        if ($isViewConfigured)
            $html .= $this->get_view_html($defConfig);

        $html .= $this->get_thumbs_html($isViewConfigured);
        $html .= "</div>";

        return $html;
    }

    private function get_view_html($defConfig)
    {
        global $post, $product;

        $viewAlias = preg_replace('/\s+/', '', $product->get_sku());
        if (empty($viewAlias))
            $viewAlias = $post->ID;

        $html = "<div id='%s' class='webrotate360 wr360embed' style='width:%s; height:%s;' data-imagerotator='{";
        $html .= '"onready":"%s", "graphics":"%s", "licfile":"%s", "rootpath":"%s", "xmlfile":"%s", "basewidth":%s, "events":%s, "name":"%s", "minheight":%s, "background":"%s"';
        $html .= "}'></div>";

        $html = sprintf(
            $html,
            'wr360woo_' . $defConfig->name,
            (strpos($defConfig->viewerWidth, '%') === FALSE) ? $defConfig->viewerWidth . 'px' : $defConfig->viewerWidth,
            $defConfig->viewerHeight . "px",
            $defConfig->callback,
            $defConfig->graphicsPath,
            $defConfig->licensePath,
            $defConfig->rootPath,
            $defConfig->config,
            $defConfig->baseWidth,
            $defConfig->useGoogleEvents ? "true" : "false",
            $viewAlias,
            $defConfig->minHeight,
            $defConfig->background);

        return $html;
    }

    private function get_thumbs_html($isViewConfigured)
    {
        global $post, $product;
        $html = "";

        $attachments = $product->get_gallery_image_ids();
        if (!$attachments || !has_post_thumbnail())
            return $html;

        $postThumbId = get_post_thumbnail_id($post->ID);

        if ($isViewConfigured)
        {
            array_unshift($attachments, $postThumbId);
        }
        else if (has_post_thumbnail())
        {
            $fullImage = wp_get_attachment_image_src($postThumbId, 'full');
            $imageTitle = "";// get_the_title($postThumbId);
            $html .= '<div class="wr360wooimage"><a href="' . esc_url($fullImage[0]) . '" data-rel="prettyPhoto[]" title="' . $imageTitle . '">'. get_the_post_thumbnail($post->ID, 'shop_single') . '</a></div>';
        }

        if (count($attachments) == 0)
            return $html;

        $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
        $html .= "<div class='wr360woothumbs'>";

        foreach ($attachments as $id)
        {
            $image = wp_get_attachment_image_src($id, 'full');
            $thumbnail = wp_get_attachment_image_src($id, 'shop_thumbnail');
            $imageTitle = ""; //get_the_title($id);
            $thumbHtml = '<div class="wr360woothumb" style="width:' . 100/$columns . '%;"><a href="' . esc_url($image[0]) . '" data-rel="prettyPhoto[]" title="' . $imageTitle . '">';
            $thumbHtml .= '<img src="' . esc_url($thumbnail[0]) . '"/></a></div>';
            $html .= $thumbHtml;
        }

        return $html . "</div>";
    }

    public function webrotate360_replace_product_image_old($content)
    {
        global $post;
        global $product;

        if (empty($post->ID))
            return ($content);

        $wr360config = esc_url(get_post_meta($post->ID, "_wr360config", true));
        $wr360root = esc_url(get_post_meta($post->ID, "_wr360root", true));

        if (empty($wr360config) && empty($wr360root))
            return ($content);

        $viewAlias = preg_replace('/\s+/', '', $product->get_sku());
        if (empty($viewAlias))
            $viewAlias = $post->ID;

        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_shortcode(array("name" => $post->ID, "config" => $wr360config, "rootpath" => $wr360root));

        $replace = "";
        $replace .= "<div class='wr360woowrap'>";
        $replace .= "<div id='%s' class='webrotate360 wr360embed' style='width:%s; height:%s;' data-imagerotator='{";
        $replace .= '"onready":"%s", "graphics":"%s", "licfile":"%s", "rootpath":"%s", "xmlfile":"%s", "basewidth":%s, "events":%s, "name":"%s", "minheight":%s, "background":"%s"';
        $replace .= "}'></div></div>";

        return (sprintf(
            $replace,
            'wr360woo_' . $defConfig->name,
            (strpos($defConfig->viewerWidth, '%') === FALSE) ? $defConfig->viewerWidth . 'px' : $defConfig->viewerWidth,
            $defConfig->viewerHeight . "px",
            $defConfig->callback,
            $defConfig->graphicsPath,
            $defConfig->licensePath,
            $defConfig->rootPath,
            $defConfig->config,
            $defConfig->baseWidth,
            $defConfig->useGoogleEvents ? "true" : "false",
            $viewAlias,
            $defConfig->minHeight,
            $defConfig->background));
    }

    public function webrotate360_save_product($post_id)
    {
        if (isset($_POST["_wr360config"]))
            update_post_meta($post_id, "_wr360config", esc_url($_POST["_wr360config"]));

        if (isset($_POST["_wr360root"]))
            update_post_meta($post_id, "_wr360root", esc_url($_POST["_wr360root"]));
    }

    public function webrotate360_tab_options_tab()
    {
        echo '<li class="custom_tab"><a href="#webrotate360woo_tab_data" id="webrotate360woo_tab"><span>WebRotate 360</span></a></li>';
    }

    public function webrotate360_tab_options()
    {
        echo '<style type="text/css">a#webrotate360woo_tab:before{content: "\f463" !important;}</style>
            <div id="webrotate360woo_tab_data" class="panel woocommerce_options_panel"><div class="options_group">';

        woocommerce_wp_text_input(array(
            "id" => "_wr360config",
            "label" => "Config File URL",
            "placeholder" => "",
            "desc_tip" => true,
            "description" => sprintf("Config File URL")
        ));

        woocommerce_wp_text_input(array(
            "id" => "_wr360root",
            "label" => "Root Path",
            "placeholder" => "",
            "desc_tip" => true,
            "description" => sprintf("Root Path")
        ));

        echo "</div></div>";
    }

    private function get_woocommerce_version()
    {
        if (!function_exists("get_plugins"))
            require_once(ABSPATH . "wp-admin/includes/plugin.php");

        $plugin_folder = get_plugins("/" . "woocommerce");
        $plugin_file = "woocommerce.php";

        $version = $plugin_folder[$plugin_file]["Version"];
        if (isset($version))
            return $version;

        return null;
    }
}