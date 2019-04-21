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

class WR360ShortCodes
{
    public function __construct()
    {
        add_shortcode("wr360embed", array($this, "add_embed_shortcode_hanlder"));
        add_shortcode("wr360popup", array($this, "add_popup_shortcode_hanlder"));
        add_shortcode("wr360expand", array($this, "add_expand_shortcode_hanlder"));
    }

    public function add_popup_shortcode_hanlder($atts, $content)
    {
        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_shortcode($atts);

        $replace  = "<a id='%s' title='%s' href='%s?config=%s&amp;root=%s&amp;height=%s&amp;viewname=%s&amp;grphpath=%s&amp;analyt=%s&amp;lic=%s&amp;background=%s&amp;iframe=true&amp;width=%s&amp;height=%s' data-rel=prettyPhoto%s>";
        $replace .= $content;
        $replace .= "</a>";

        return (sprintf(
            $replace,
            'wr360_' . $defConfig->name,
			$defConfig->title,
            plugins_url("webrotate-360-product-viewer/public/viewloader_" . $defConfig->viewerSkin . ".html"),
            urlencode($defConfig->config),
            urlencode($defConfig->rootPath),
            $defConfig->viewerHeight,
            $defConfig->name,
            urlencode($defConfig->graphicsPath),
            $defConfig->useGoogleEvents ? "true" : "false",
            urlencode($defConfig->licensePath),
            urlencode($defConfig->background),
            str_ireplace("%", "", $defConfig->viewerWidth),
            $defConfig->viewerHeight,
            $defConfig->isPopupGallery ? "[iframe]" : ""
        ));
    }

    public function add_expand_shortcode_hanlder($atts, $content)
    {
        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_shortcode($atts);

        $replace  = "";
        $replace .= "<div id='%s' style='cursor:pointer;' class='webrotate360 wr360embed' data-imagerotator='{";
        $replace .= '"onready":"%s", "graphics":"%s", "licfile":"%s", "rootpath":"%s", "xmlfile":"%s", "events":%s, "name":"%s", "fsclick":true, "browserfs":%s';
        $replace .= "}'>";
        $replace .= $content;
        $replace .= "</div>";

        return (sprintf(
            $replace,
            'wr360_' . $defConfig->name,
            $defConfig->callback,
            $defConfig->graphicsPath,
            $defConfig->licensePath,
            $defConfig->rootPath,
            $defConfig->config,
            $defConfig->useGoogleEvents ? "true" : "false",
            $defConfig->name,
            $defConfig->inBrowserFullscreen === "true" ? "true" : "false"));
    }

    public function add_embed_shortcode_hanlder($atts, $content)
    {
        $defConfig = new WR360DefaultsConfig();
        $defConfig->init_shortcode($atts);

        $replace  = "";
        $replace .= "<div id='%s' class='webrotate360 wr360embed' style='width:%s; height:%s;' data-imagerotator='{";
        $replace .= '"onready":"%s", "graphics":"%s", "licfile":"%s", "rootpath":"%s", "xmlfile":"%s", "basewidth":%s, "events":%s, "name":"%s", "minheight":%s, "background":"%s"';
        $replace .= "}'></div>";

        return (sprintf(
            $replace,
            'wr360_' . $defConfig->name,
            (strpos($defConfig->viewerWidth, '%') === FALSE) ? $defConfig->viewerWidth . 'px' : $defConfig->viewerWidth,
            (strpos($defConfig->viewerHeight, "%") === FALSE) ? $defConfig->viewerHeight . "px" : $defConfig->viewerHeight,
            $defConfig->callback,
            $defConfig->graphicsPath,
            $defConfig->licensePath,
            $defConfig->rootPath,
            $defConfig->config,
            $defConfig->baseWidth,
            $defConfig->useGoogleEvents ? "true" : "false",
            $defConfig->name,
            $defConfig->minHeight,
            $defConfig->background));
    }
}
