<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style type="text/css">
#az_batchtool_parent {
	margin-left: -20px;
}

@media screen and (max-width: 782px) {
	#az_batchtool_parent {
		margin-left: -10px;
	}
}

.woocommerce_page_ajaxzoombatch {
	background-color: #FFF!important;
}

#wpbody-content {
	padding-bottom: 0;
}
</style>

<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Author: AJAX-ZOOM
Author URI: http://www.ajax-zoom.com/
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
License URI: http://www.ajax-zoom.com/index.php?cid=download
*/


if (window.jQuery){
	jQuery('.notice').remove();
}

az_addLoadingLayer('#wpbody-content', {
	position: 'absolute',
	width: 'calc(100% + 20px)',
	marginLeft: -20
});
</script>

<h1 class="wp-heading-inline">AJAX-ZOOM Batch Tool</h1>
<p style="padding-right: 15px">
	<?php echo __( 'You do not necessarily need to use the AJAX-ZOOM batch tool, because if image tiles and other AJAX-ZOOM caches have not been generated yet, AJAX-ZOOM will process the images on-the-fly. Latest, when they appear at the frontend. However, if you have thousands of images, it is a good idea to batch process all existing images, which you plan to show over AJAX-ZOOM, before launching the new website or before enabling AJAX-ZOOM at frontend. ', 'ajaxzoom' ); ?>
</p>
<div style="position: relative; float: right; height: 0; overflow: visible; right: 11px; top: 0; display: none;">
	<?php echo __( 'Enable 360 after all cache is done', 'ajaxzoom'); ?> - 
	<input type="checkbox" value="1" id="az_batch_act_360" checked="checked">
</div>
<div id="az_batchtool_parent">
	Loading, please wait
</div>

<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Author: AJAX-ZOOM
Author URI: http://www.ajax-zoom.com/
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
License URI: http://www.ajax-zoom.com/index.php?cid=download
*/

jQuery(function ($) {
	var cUrl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
	var frameSrc = '<?php echo $uri; ?>/ajaxzoom/axZm/zoomBatch.php';
	var stutusMsg = '<?php echo __( 'The status of the AJAX-ZOOM 360 object {msg} has been updated', 'ajaxzoom'); ?>';

	frameSrc += '?batch_start=1';

	var wh = function()
	{
		var o = $('#az_batchtool').offset();
		$('#az_batchtool').height($(window).height() - o.top - 10);
	};

	var loadBatchTool = function()
	{
		var frame = $('<iframe style="width: 100%; height: 500px; border: 0;" id="az_batchtool" src=""></iframe>');
		frame.on('load', function() {
			$('#az_batch_act_360').parent().css('display', '');
			az_removeLoadingLayer();
		});
		frame.attr('src', frameSrc);
		$('#az_batchtool_parent').empty().append(frame);
	};

	$(document).ready(function() {
		$('#wpfooter').remove();
		loadBatchTool();
		wh();
	} );

	$(window).on('resize', wh);

	// Batch tool callback
	window.afterBatchFolderEndJsClb = function(data)
	{
		if (!$('#az_batch_act_360').is(':checked')) {
			return;
		}

		if ($.isPlainObject(data) && data.picDir) {
			var dta = data.dirTreeArray;
			var key = data.key;

			if (!$.isPlainObject(dta) || !key || !$.isPlainObject(dta[key])) {
				return;
			} else {
				var keyObj = dta[key];
			}

			if (keyObj.DIR_SUB !== 0) {
				return;
			}

			var path = data.picDir.split('ajaxzoom/pic/360/');
			if (!path[1]) {
				return;
			}

			// path left after AjaxZoom/pic/360/
			path = path[1];
			if (path.slice(-1) == '/') {
				path = path.slice(0, -1);
			}

			var pp = path.split('/');
			if (pp.length != 3) {
				return;
			}

			var id_product = parseInt(pp[0]);
			var id_360 = parseInt(pp[1]);
			var idSet = parseInt(pp[2]);

			var lastKeyVal = parseInt(key.split('_').reverse()[0]);
			if (!lastKeyVal) {
				return;
			}

			var nextKey = key.slice(0, -(lastKeyVal + '').length) + ((lastKeyVal + 1) + '');
			if (dta[nextKey]) {
				// not last subfolder (3D not ready)
				return;
			}

			// Activate
			$.ajax( {
				url: cUrl,
				type: 'POST',
				data: {
					'action': 'ajaxzoom_set_360_status',
					'id_product': id_product,
					'id_360': id_360,
					'status': 1
				},
				success: function(d) {
					if ($.isPlainObject(d) && d.status == 'ok') {
						az_showSuccessMessage(stutusMsg.replace('{msg}', id_product + '-' + id_360));
					}
				}
			} );
		}
	};
} );
</script>
