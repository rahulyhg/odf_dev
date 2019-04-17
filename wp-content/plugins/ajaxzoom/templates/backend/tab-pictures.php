<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="entry-edit az_container">
	<div class="fieldset fieldset-wide">
		<div class="notice-info notice inline">
			<?php
			echo __('You can add hotspots to you normal product images. ', 'ajaxzoom');
			echo __('The image file itself will be not changed! ', 'ajaxzoom');
			echo __('At front view, your users will see the hotspots at full screen or Fancybox. ', 'ajaxzoom');
			echo __('For immediate visibility activate "axZmMode" option in AJAX-ZOOM module settings for all products or set it in "Individual module settings for this product" above.', 'ajaxzoom');
			?>
		</div>

		<div class="grid">
			<div style="margin-bottom: 5px;">
				<a href="javascript:void(0)" id="az_refresh_pictures_list" style="text-decoration: none">&#x21bb;&ensp;<?php echo __('Refresh list', 'ajaxzoom'); ?></a>
			</div>
			<table class="wp-list-table widefat fixed striped posts az_list_table" id="az_picturesTable">
				<thead>
					<tr class="headings">
						<th class="az_timg_head"><span class="title_box"></span></th>
						<th style="width: 120px"><span class="title_box"><span class="title_box"><?php echo __('Hotspots active', 'ajaxzoom'); ?></span></th>
						<th><span class="title_box"><span class="title_box"><?php echo __('Filename', 'ajaxzoom'); ?></span></th>
					</tr>
				</thead>
				<tbody id="az_picturesTableRows">
				</tbody>
			</table>

			<table style="display: none;">
				<tbody id="az_lineSetPictures">
					<tr id="az_picture_line_id" data-order="picture_order">
						<td class="az_tbl_picture_img">
							<img picture_src 
								class="img-thumbnail" 
								style="max-width: 128px; max-height: 128px; cursor: pointer;">
						</td>
						<td>
							<span class="switch prestashop-switch fixed-width-lg hide_class az_switch_status_picture">
								<input type="radio" name="status_field" id="status_field_on" value="1" checked_on />
								<label class="t" for="status_field_on"><?php echo __('Yes', 'ajaxzoom'); ?></label>
								<input type="radio" name="status_field" id="status_field_off" value="0" checked_off />
								<label class="t" for="status_field_off"><?php echo __('No', 'ajaxzoom'); ?></label>
								<a class="slide-button btn"></a>
							</span>
						</td>
						<td style="word-break: break-all;">
							picture_name
							<div class="row-actions">
								<?php echo __('media-ID', 'ajaxzoom'); ?>: 
								picture_mid
								&nbsp;|&nbsp;
								<a class="az_edit_picture_hotspots" href="javascript:void(0)">
									<?php echo __('Create / edit hotspots', 'ajaxzoom'); ?>
								</a>
								&nbsp;|&nbsp;
								<a class="az_image_shortcode_set" href="javascript:void(0)"><?php echo __( 'Shortcode', 'ajaxzoom'); ?></a>
							</div>
							<div class="hide_class az_shortcode_div az_shortcode_div_id_image" style="display: none;"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Author: AJAX-ZOOM
Author URI: http://www.ajax-zoom.com/
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
License URI: http://www.ajax-zoom.com/index.php?cid=download
*/

jQuery(function($) {
	var id_product = ajaxzoomData.id_product;
	window.az_pictures_lst = <?php echo json_encode($az_pictures_lst); ?>;

	var az_lang_msg_pictures_refresh = '<?php echo __('Pictures list has been refreshed', 'ajaxzoom'); ?>';
	var az_lang_hotspots_enabled = '<?php echo __('Hotspots for this image have been enabled', 'ajaxzoom'); ?>';
	var az_lang_hotspots_disabled = '<?php echo __('Hotspots for this image have been disabled', 'ajaxzoom'); ?>';

	window.az_refresh_pictures_list = function(msg) { 
		$('#az_picturesTableRows td').css('backgroundColor', '#f4f8fb');

		az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
			"action": "ajaxzoom_refresh_pictures_list",
			"id_product": id_product
		}, function(data) {
			$('#az_picturesTableRows').empty();
			az_pictures_lst = data;

			az_add_picture_lines();

			az_removeLoadingLayer();

			if (msg) {
				az_showSuccessMessage( msg );
			}
		} );
	};

	function az_setLinePicture(dta) {
		var line = $("#az_lineSetPictures").html();
		line = line.replace(/az_picture_line_id/g, 'az_picture_line_'+dta.id_media);

		/*
		if ( typeof dta.id_media == 'string' && dta.id_media.indexOf('_') != -1 ) {
			line = line.replace(/picture_name/g, dta.image_name);
		} else {
			line = line.replace(/picture_name/g, '<a href="'+dta.path+'" target="_blank">'+dta.image_name+'</a>');
		}
		*/

		line = line.replace(/picture_name/g, dta.image_name);
		line = line.replace(/picture_order/g, dta.order);
		line = line.replace(/picture_src/g, 'src="'+dta.thumb+'" ');

		if (typeof dta.id_media == 'string' && dta.id_media.indexOf('_') != -1) {
			line = line.replace(/picture_mid/g, dta.id_media + ' (AZ)');
		} else {
			line = line.replace(/picture_mid/g, dta.id_media);
		}

		if (dta.hotspots !== undefined) {
			if ( parseInt(dta.hotspots) == 1 ) {
				line = line.replace(/checked_on/g, 'checked');
				line = line.replace(/checked_off/g, '');
			} else {
				line = line.replace(/checked_on/g, '');
				line = line.replace(/checked_off/g, 'checked');
			}

			line = line.replace(/status_field/g, 'picture_status_' + dta.id_media);
		} else {
			line = $(line);
			$('.az_switch_status_picture', line).replaceWith(' - ');
		}

		$("#az_picturesTableRows").append(line);

		az_manageTableDisplay('#az_picturesTable');
	}

	function az_sort_picture_rows() {
		$("#az_picturesTableRows").append($("#az_picturesTableRows>tr").get().sort(function(a, b) {
				return $(a).attr("data-order") > $(b).attr("data-order") ? 1 : -1;
		} ));
	}

	function az_add_picture_lines() {
		$.each(az_pictures_lst, function(k, v) { 
			az_setLinePicture(v);
		} );

		az_sort_picture_rows();
	}

	az_add_picture_lines();

	$('#az_refresh_pictures_list')
	.bind('click', function(e) { 
		$(this).blur();
		window.az_refresh_pictures_list(az_lang_msg_pictures_refresh);
	} );

	$('body').on('click', '.az_edit_picture_hotspots', function(e) { 
		e.preventDefault();
		$(this).blur();

		var id_media = $(this).closest('tr').attr('id').replace('az_picture_line_', '');
		var hotspotHref = '';

		hotspotHref += ajaxzoomData.uri + '/ajaxzoom/preview/hotspoteditor.php?';
		hotspotHref += 'zoomData='+az_pictures_lst[id_media]['path'];
		hotspotHref += '&id_media='+id_media;
		hotspotHref += '&id_product='+az_pictures_lst[id_media]['id_product'];
		hotspotHref += '&image_name='+az_pictures_lst[id_media]['image_name'];

		$.openAjaxZoomInFancyBox( {
			href: hotspotHref,
			iframe: true,
			scrolling: 1,
			boxMargin: 50,
			boxOnClosed: window.az_refresh_pictures_list
		} );

	} );

	$('body').on('click', '.az_tbl_picture_img > img', function(e) { 
		$(this).closest('tr').find('.az_edit_picture_hotspots').trigger('click');
	} );

	// deactivate hotspots
	$('body').on('change', '.az_switch_status_picture input', function(e) {
		e.preventDefault();
		var status = $(this).val();
		var id = $(this).closest('tr').attr('id').replace('az_picture_line_', '');

		az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
			"action": "ajaxzoom_set_hotspot_status",
			"id_media": id,
			"status": status,
			"id_product": id_product
			}, function(data) {
				if (data.status == 1) {
					az_showSuccessMessage(az_lang_hotspots_enabled, 'div#ajaxzoomimghotspots h2');
				} else {
					az_showSuccessMessage(az_lang_hotspots_disabled, 'div#ajaxzoomimghotspots h2');
				}
		} );
	} );

	$( 'body' ).on( 'click', '.az_image_shortcode_set', function( e ) {
		e.preventDefault();
		var _this = $(this);
		_this.blur();

		var parent_class = 'az_shortcode_div_id_image';

		var id_image = _this.closest( 'tr' ).attr('id').replace('az_picture_line_', '');

		var cont = $('.' + parent_class, _this.closest('tr'));

		if (!cont.length || cont.css('display') != 'none') {
			cont.slideUp(150, function(){
				cont.empty();
			} );
			return;
		}

		$('.' + parent_class).empty().css('display', 'none');
		cont.append('<div>Copy & paste this shortcode anywhere else, e.g. in a post, to display this single image including hotspots if available outside of WooCommerce product detail page. The id_image supports multiple ids separated by comma (CSV).</div>');
		cont.append('<textarea class="az_shortcode_text"></textarea>');

		cont.append('<table cellspacing="0" cellpadding="0" style="width: 100%"><tbody class="az_shortcode_body"></tbody></table>');
		var bdy = $('.az_shortcode_body', cont);

		bdy.append('<tr style="display: none;"><td></td><td><input type="hidden" class="az_shortcode_param" data-param="id_image" value="'+id_image+'"></td></tr>');
		bdy.append('<tr><td>Heading (h3):</td><td><input type="text" class="az_shortcode_param" data-param="heading" style="width: 100%" value=""></td></tr>');

		bdy.append('<tr><td>Proportions:</td><td><input type="text" class="az_shortcode_param" data-param="prop_w" style="width: 50px" value="16"> by <input type="text" class="az_shortcode_param" data-param="prop_h" style="width: 50px" value="9"></td></tr>');
		bdy.append('<tr><td>Disable gallery:</td><td><input type="text" class="az_shortcode_param" data-param="no_gallery" style="width: 50px" value="1"> (if more than one image)</td></tr>');
		bdy.append('<tr><td>Border width:</td><td><input type="text" class="az_shortcode_param" data-param="border_width" style="width: 50px" value="1"></td></tr>');
		bdy.append('<tr><td>Border color:</td><td><input type="text" class="az_shortcode_param" data-param="border_color" style="width: 50px" value=""></td></tr>');
		bdy.append('<tr><td>Other:</td><td>will follow, upon request...</td></tr>');

		cont.append('<div style="text-align: right;"><a class="button az_close_shortcode">Close</a></div>');

		az_shortCodeSlideDown(cont);
	} );
	
} );

</script>