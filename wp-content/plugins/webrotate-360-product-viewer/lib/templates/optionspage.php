<?php
    if (!defined("ABSPATH"))
        exit;
?>

<div class="wrap">
    <h2>WebRotate 360 Product Viewer</h2>
    <form method="post" action="options.php">
        <p>Shortcode overview and examples are available on the plugin page <a href="http://www.webrotate360.com/products/cms-and-e-commerce-plugins/plugin-for-wordpress.aspx" target="_blank">here</a> and via this <a href="http://www.360-product-views.com/wordpress/" target="_blank">demo website</a>.
        If you haven't used WebRotate 360 Product Viewer before, download our <a href="http://www.webrotate360.com/products/webrotate-360-product-viewer.aspx" target="_blank">free software</a> (Windows or Mac OS X) and follow this <a href="https://www.youtube.com/watch?v=W3uFpXy1ne4" target="_blank">YouTube video</a> or check out our <a href="http://www.webrotate360.com/Downloads/Resources/Readme.pdf" target="_blank">user guide</a> on how to create the product spins on your computer which you can then upload to your WordPress installation via FTP. Note that you only need to upload a single folder that is created under 360_assets in the published folder of your SpotEditor project upon publish.</p>
        <p style="margin-bottom:50px"><b>IMPORTANT:</b> all files and folders are deleted from the WebRotate360 plugin folder by WordPress automatically upon the plugin update, so if you upload your WebRotate 360 assets (or a license file) under the plugin folder,
           they will be deleted upon the plugin update. We recommend to store these files outside of the WebRotate 360 plugin folder.
           If you have any questions or issues, email us at <a href="mailto:support@webrotate360.com">support@webrotate360.com</a> or visit our <a href="http://360-product-views.com/forum/" target="_blank">forum</a> and we would be happy to assist.
        </p>
        <h3>Default settings</h3>
        <?php settings_fields("webrotate360-settings-group") ?>
        <?php do_settings_sections("webrotate360-settings-group") ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Viewer Width (px or %)</th>
                <td>
                    <input type="text" name="wr360_viewer_width" value="<?php echo get_option('wr360_viewer_width'); ?>"/>
                    <p class="description">Default viewer width in pixel. When using wr360embed shortcode, can be relative (e.g. 100%) for responsive themes.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Viewer Height (px or %)</th>
                <td>
                    <input type="text" name="wr360_viewer_height" value="<?php echo get_option('wr360_viewer_height'); ?>"/>
                    <p class="description">Default viewer height in pixel. You can also use % for wr360embed shortcodes and if parent container height is set.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Base Width (px)</th>
                <td>
                    <input type="text" name="wr360_viewer_basewidth" value="<?php echo get_option('wr360_viewer_basewidth'); ?>"/>
                    <p class="description">Optionally force viewer to scale its height relative to your original viewer width (base width).<br/> The setting usually applies when your viewer width is relative (%), i.e when your product page is responsive. Only works with wr360embed shortcodes (and WooCommerce embeds).</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Minimum Height (px)</th>
                <td>
                    <input type="text" name="wr360_viewer_minheight" value="<?php echo get_option('wr360_viewer_minheight'); ?>"/>
                    <p class="description">If Base Width is configured, this is a minimum viewer height in pixel (e.g 300px).</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Viewer Skin</th>
                <?php
                    $viewerSkin = get_option("wr360_viewer_skin");
                    $viewerSkins = array("basic", "thin", "round", "retina", "empty", "zoom_light", "zoom_dark");
                ?>
                <td>
                    <select id="wr360_viewer_skin" name="wr360_viewer_skin">
                        <?php foreach($viewerSkins as $skin):?>
                            <?php $selected = ($viewerSkin == $skin) ? 'selected="selected"' : '' ?>
                            <option value="<?php echo $skin?>" <?php echo $selected ?> ><?php echo $skin ?></option>
                        <?php endforeach?>
                    </select>
                    <p class="description">Selection of out-of-the-box viewer skins.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Popup Skin</th>
                <?php
                    $popupSkin = get_option("wr360_popup_skin");
                    $popupSkins = array("light_clean", "dark_clean", "pp_default", "light_rounded", "dark_rounded", "dark_square", "light_square", "facebook");
                ?>
                <td>
                    <select id="wr360_popup_skin" name="wr360_popup_skin">
                        <?php foreach($popupSkins as $skin):?>
                            <?php $selected = ($popupSkin == $skin) ? 'selected="selected"' : '' ?>
                            <option value="<?php echo $skin?>" <?php echo $selected ?> ><?php echo $skin ?></option>
                        <?php endforeach?>
                    </select>
                    <p class="description">Selection of popup skins. Only applies to wr360popup shortcodes.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Graphics Path</th>
                <td>
                    <input type="text" name="wr360_graphics_path" class="regular-text" value="<?php echo get_option('wr360_graphics_path'); ?>"/>
                    <p class="description">If you have custom hotspot indicators, you can upload them to some folder in your WordPress installation and specify the URL of this folder here.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Viewer Background</th>
                <td>
                    <input type="text" name="wr360_background_color" class="regular-text" value="<?php echo get_option('wr360_background_color'); ?>"/>
                    <p class="description">Hexadecimal color of the viewer background. #FFFFFF (white) is the default.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Master Config URL (PRO)</th>
                <td>
                    <input type="text" name="wr360_master_config" class="regular-text" value="<?php echo get_option('wr360_master_config'); ?>"/>
                    <p class="description">Master Config allows having a single XML configuration file for all your product views and then use the rootpath parameter in your shortcodes, <br/>pointing to the asset folder for each view on your server (or CDN).
                        The asset folder is usually a single folder that you will find under the published folder created by SpotEditor under published/360_assets.
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Use Google Analytics (PRO)</th>
                <td>
                    <?php $checkedVal = get_option('wr360_use_googleevents');?>
                    <?php $checkedFlg = ($checkedVal) ? 'checked="checked"' : '' ?>
                    <input type="checkbox" name="wr360_use_googleevents" <?php echo $checkedFlg; ?>/>
                    <p class="description">Check here to enable Google Analytics events to track user engagement when interacting with your product views (e.g image drag & hover, toolbar clicks, hotspots, etc.)</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">License Path (PRO)</th>
                <td>
                    <input type="text" name="wr360_license_path" class="regular-text" value="<?php echo get_option('wr360_license_path'); ?>"/>
                    <p class="description">URL path to license.lic on this server (PRO and Enterprise). Click <a href="http://www.webrotate360.com/pricing.aspx" target="_blank">HERE</a> for more details.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Don't include lightbox</th>
                <td>
                    <?php $checkedVal = get_option('wr360_prettyphoto_off');?>
                    <?php $checkedFlg = ($checkedVal) ? 'checked="checked"' : '' ?>
                    <input type="checkbox" name="wr360_prettyphoto_off" <?php echo $checkedFlg; ?>/>
                    <p class="description">Check here to disable the loading of popup gallery (lightbox) that comes with this module (in case your theme already includes prettyPhoto lightbox).</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">API Ready Callback</th>
                <td>
                    <input type="text" name="wr360_api_ready_callback" class="regular-text" value="<?php echo get_option('wr360_api_ready_callback'); ?>"/>
                    <p class="description">To integrate with viewer API, enter the name of your JavaScript callback. The callback receives three parameters: API object, isFullScreen flag and a shortcode name (or WooCommerce SKU), i.e. function yourCallbackName(api, isFullScreen, name){}. </p>
                </td>
            </tr>
            <?php if (class_exists('WooCommerce')):?>
                <tr valign="top">
                    <th scope="row">Use plugin gallery for all products in WooCommerce</th>
                    <td>
                        <?php $checkedVal = get_option('wr360_gallery_woo_all');?>
                        <?php $checkedFlg = ($checkedVal) ? 'checked="checked"' : '' ?>
                        <input type="checkbox" name="wr360_gallery_woo_all" <?php echo $checkedFlg; ?>/>
                        <p class="description">The gallery that comes with this plugin will be used even if a product doesn't have a 360 view assigned in WooCommerce.</p>
                    </td>
                </tr>
            <?php endif ?>
        </table>
        <?php submit_button() ?>
    </form>
</div>