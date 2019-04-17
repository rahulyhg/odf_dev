<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset($ajaxzoom_has_video_html5) && $ajaxzoom_has_video_html5 === true ) {
	$videojs_src = (array)json_decode($ajaxzoom_config['AJAXZOOM_DEFAULTVIDEOVIDEOJSJS'], true);
	foreach ($videojs_src as $k => $v) {
		if (strstr($k, 'css') && $v) {
			echo '<link rel="stylesheet" type="text/css" href="'.$v.'" />';
		} elseif (strstr($k, 'js') && $v) {
			echo '<script type="text/javascript" src="'.$v.'"></script>';
		}
	}
}
?>

<!-- AJAX-ZOOM mouseover block  -->
<div class="axZm_mouseOverWithGalleryContainer" id="axZm_mouseOverWithGalleryContainer" style="display: none;">

	<!-- Parent container for offset to the left or right -->
	<div class="axZm_mouseOverZoomContainerWrap">

		<!-- Alternative container for title of the images -->
		<div class="axZm_mouseOverTitleParentAbove"></div>

		<!-- Container for mouse over image -->
		<div id="<?php echo $ajaxzoom_config['AJAXZOOM_DIVID']; ?>" class="axZm_mouseOverZoomContainer">
			<!-- Optional CSS aspect ratio and message to preserve layout before JS is triggered -->
			<div class="axZm_mouseOverAspectRatio">
				<div>
					<span><?php echo $is_iframe ? __('Loading', 'ajaxzoom') : __('Image loading', 'ajaxzoom'); ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- gallery with thumbs (will be filled with thumbs by javascript) -->
	<div id="<?php echo $ajaxzoom_config['AJAXZOOM_GALLERYDIVID']; ?>" class="axZm_mouseOverGallery"></div>

</div>

<?php
// test different container
if ( isset($_GET['appendToContainer']) && $_GET['appendToContainer'] ) {
	$ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER'] = $_GET['appendToContainer'];
}

if ( isset( $ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER'] ) && $ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER'] != '.images' ) {
?>
<style type="text/css">
	<?php echo $ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER']; ?> >*:not(.images):not(#axZm_mouseOverWithGalleryContainer){
		display: none !important;
	}
</style>
<?php
}
?>

<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
Author: AJAX-ZOOM
Version: 1.1.16
Author URI: http://www.ajax-zoom.com/
*/

;(function(g,b){function c(){if(!e){e=!0;for(var a=0;a<d.length;a++)d[a].fn.call(window,d[a].ctx);d=[]}}function h(){
"complete"===document.readyState&&c()}b=b||window;var d=[],e=!1,f=!1;b[g||"docReady"]=function(a,b){
e?setTimeout(function(){a(b)},1):(d.push({fn:a,ctx:b}),"complete"===document.readyState?setTimeout(c,1):f||
(document.addEventListener?(document.addEventListener("DOMContentLoaded",c,!1),window.addEventListener("load",c,!1))
:(document.attachEvent("onreadystatechange",h),window.attachEvent("onload",c)),f=!0))}})("az_docReady",window);
window.HELP_IMPROVE_VIDEOJS = false;

window.az_init_wp_frontend = function() {
	jQuery(function ($) {
		if (!$.axZm_psh) {
			$.axZm_psh = { } ;
		}

		$.axZm_psh.appendToContainer = '<?php echo $ajaxzoom_config['AJAXZOOM_APPENDTOCONTAINER']; ?>';
		$.axZm_psh.appendToContCss = '<?php echo $ajaxzoom_config['AJAXZOOM_APPENDTOCONTCSS']; ?>';

		if (!$.axZm_psh.appendToContainer) {
			$.axZm_psh.appendToContainer = '.images';
		}

		if ( $($.axZm_psh.appendToContainer).length ) {
			$.axZm_psh.place_az_layer();
		} else {
			$( document ).ready(function() {
				$.axZm_psh.place_az_layer();
			} );
		}

		<?php if (isset($ajaxzoom_themesettings) && isset($ajaxzoom_themesettings['js'])) { ?>
		$(document).ready(function() {
			try {
			<?php echo stripslashes($ajaxzoom_themesettings['js']); ?>
			} catch(er) {
				console.log('AJAX-ZOOM: error in custom theme js');
			}
		} );
		<?php } ?>
		window.ajaxzoom_imagesJSON = '<?php echo $ajaxzoom_imagesJSON; ?>';
		window.ajaxzoom_images360JSON = '<?php echo $ajaxzoom_images360JSON; ?>';
		$.axZm_psh.IMAGES_JSON = $.parseJSON(window.ajaxzoom_imagesJSON);
		$.axZm_psh.IMAGES_360_JSON = $.parseJSON(window.ajaxzoom_images360JSON);

		$.axZm_psh.ajaxzoom_variations_360 = [];
		<?php
		// $.axZm_psh.ajaxzoom_variations_360
		echo $variations_360_json; 

		if ( isset( $ajaxzoom_variations_wpml_map ) && !empty( $ajaxzoom_variations_wpml_map ) ) {
			echo '$.axZm_psh.ajaxzoom_variations_wpml_map = ' . json_encode($ajaxzoom_variations_wpml_map, JSON_FORCE_OBJECT) . ";\n";
		}
		?>
		$.axZm_psh.product_variable = <?php echo $ajaxzoom_variable_product ? 'true' : 'false'; ?>;
		$.axZm_psh.ajaxzoom_variations_2d = <?php echo $variations_2d_json; ?>;
		$.axZm_psh.IMAGES_HOTSPOTS = <?php echo $variations_2d_hotspots; ?>;
		$.axZm_psh.VIDEOS_JSON = <?php echo $ajaxzoom_videos_json; ?>;

		$.axZm_psh.IMAGES_JSON_current = $.extend(true, {}, $.axZm_psh.IMAGES_JSON);
		$.axZm_psh.IMAGES_360_JSON_current = $.extend(true, {}, $.axZm_psh.IMAGES_360_JSON);

		$.axZm_psh.axZmPath = '<?php echo $axZmPath; ?>';
		$.axZm_psh.shopLang = '<?php echo substr(get_bloginfo( 'language' ), 0, 2); ?>';
		$.axZm_psh.showDefaultForVariation = <?php echo $ajaxzoom_config['AJAXZOOM_SHOWDEFAULTFORVARIATION'] ?>;
		$.axZm_psh.showDefaultForVariation360 = <?php echo $ajaxzoom_config['AJAXZOOM_SHOWDEFAULTFORVARIATION360'] ?>;
		$.axZm_psh.show360noVariationSelected = <?php echo isset($ajaxzoom_config['AJAXZOOM_SHOW360NOVARIATIONSELECTED']) ? $ajaxzoom_config['AJAXZOOM_SHOW360NOVARIATIONSELECTED'] : 'false'; ?>;

		$.axZm_psh.initParam = <?php echo $ajaxzoom_init_param; ?>;
		$.axZm_psh.divID = $.axZm_psh.initParam.divID;
		$.axZm_psh.galleryDivID = $.axZm_psh.initParam.galleryDivID;
		$.axZm_psh.start_load = false;

		$.axZm_psh.initParam.videos = $.axZm_psh.addVideos(false);
		$.axZm_psh.VIDEOS_current = $.extend(true, {}, $.axZm_psh.VIDEOS_current);

		$.fn.wc_variations_image_update = $.axZm_psh.wc_variations_image_update; 

		if ($.axZm_psh.product_variable === false) {
			$.axZm_psh.initTO = setTimeout(function() {
				if (!$.axZm_psh.start_load) {
					$.axZm_psh.start_load = 1;
					$.axZm_psh.start_mouseOverZoomInit();
				}
			}, 1);
		} else {
			$.axZm_psh.variation_triggered = false;
		}
	} );
};

if (window.jQuery) {
	window.az_init_wp_frontend();
} else {
	window.az_docReady( window.az_init_wp_frontend );
}
</script>
