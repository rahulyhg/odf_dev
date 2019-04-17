<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php $maxImageSize = ini_get( 'upload_max_filesize' ); ?>

<div class="entry-edit az_container"> <!-- id="product-images2d" -->
	<div class="row">
		<div class="form-group">
			<div class="notice-info notice inline">
				<p>
				<?php
				echo __( ' This functionality does only make sense if this product is "Variable product" and you want to assign / upload more than one images to the variations. ', 'ajaxzoom' );
				echo __( ' On default WooCommerce offers only one image for variable product without installing additional plugins, so this widget can replace these third party plugins. ', 'ajaxzoom' );
				?>
				</p><p>
				<?php
				echo __( ' However, data (images) from the official "additional-variation-images" premium Woo Plugin is supported by AJAX-ZOOM frontend product detail view too! ', 'ajaxzoom' );
				echo __( ' Other third party plugins are not supported. ', 'ajaxzoom' );
				?>
				</p><p>
				<?php
				echo __( ' If you do not assign an image uploaded here within a variable product to one ore more variations, it will display on frontend in all variations but not when a variation is not selected by the user. ', 'ajaxzoom' );
				echo __( ' For simple and other type of products these images will be displayed after the gallery images. ', 'ajaxzoom' );
				?>
				</p>
			</div>
			<label class="control-label col-lg-3 file_upload_label">
				<span class="label-tooltip" data-toggle="tooltip" title="<?php echo __( 'Format: JPG, GIF, PNG. Filesize: '.$maxImageSize.' max', 'ajaxzoom' ); ?>">
					<?php echo __( 'Add a new image', 'ajaxzoom' ) ?><br />
					<?php echo __( 'Format: JPG, GIF, PNG. Filesize: '.$maxImageSize.' max', 'ajaxzoom' ); ?>
				</span>
			</label>
			<div class="col-lg-9">
				<?php require 'uploader2d.php'; ?>
			</div>
		</div>
	</div>
	<div class="grid">
		<?php if ( !empty($variations) ) { ?>
		<div style="text-align: right;">
			<?php echo __( 'Variations bindings', 'ajaxzoom' ); ?> -> 
			(<a href="javascript: void(0)" id="az_open_2d_variations"><?php echo __( 'open all', 'ajaxzoom' ); ?></a> / 
			<a href="javascript: void(0)" id="az_close_2d_variations"><?php echo __( 'close all', 'ajaxzoom' ); ?></a>)
		</div>
		<?php } ?>
		<table class="wp-list-table widefat fixed striped posts az_list_table" id="az_imageTable2d">
			<thead>
				<tr class="headings">
					<th class="data-grid-th az_timg_head"> </th>
					<th class="data-grid-th" style="width: 100px"><?php echo __( 'Active', 'ajaxzoom' ) ?></th>
					<th class="data-grid-th"><?php echo __( 'Filename', 'ajaxzoom' ) ?></th>
				</tr>
			</thead>
			<tbody id="az_imageList2d" style="cursor: move">
			</tbody>
		</table>
		<table id="az_lineType2d" style="display: none;">
			<tr data-id="image_id">
				<td style="text-align: center;">
					<img src="<?php echo $uri ?>/ajaxzoom/assets/img/image_path.gif" alt="" title="" class="img-thumbnail" />
				</td>
				<td>
					<span class="switch prestashop-switch fixed-width-lg az_switch_status_2d_img">
						<input type="radio" name="status_field2d" id="status_field2d_on" value="1" checked_on />
						<label class="t" for="status_field2d_on"><?php echo __('Yes', 'ajaxzoom'); ?></label>
						<input type="radio" name="status_field2d" id="status_field2d_off" value="0" checked_off />
						<label class="t" for="status_field2d_off"><?php echo __('No', 'ajaxzoom'); ?></label>
					</span>
				</td>
				<td>
					image_name
					<div class="row-actions">
						<a class="az_delete_product_image2d az_delete_color" href="javascript:void(0)">
							<?php echo __( 'Delete this image', 'ajaxzoom' ) ?>
						</a>
						<?php if ( !empty($variations) ) { ?>
						&nbsp;|&nbsp;
						<a class="btn-variations scalable" data-variations="dta_variations" href="javascript:void(0)">
							<?php echo __( 'Variations', 'ajaxzoom' ) ?>
						</a>
						<?php } ?>
					</div>
					<div class="az_popup-variations" style="display: none;"></div>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="az_template2d_variations" style="display: none;">
	<ul>
		<?php foreach ($variations as $key => $value): ?>
		<li><input type="checkbox" name="variations[]" value="<?php echo $key ?>"><?php echo $value; ?></li>
		<?php endforeach; ?>
	</ul>
	<button class="button button-primary az_variations2d_save">Save</button>
	<button class="button az_variations2d_cancel">Cancel</button>
</div>

<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Author: AJAX-ZOOM
Author URI: http://www.ajax-zoom.com/
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
License URI: http://www.ajax-zoom.com/index.php?cid=download
*/

var az_lang_2d_img_enabled = '<?php echo __( 'Image has been enabled', 'ajaxzoom'); ?>';
var az_lang_2d_img_disabled = '<?php echo __( 'Image has been disabled', 'ajaxzoom'); ?>';

function az_imageLine2d( id, path, image_name, status, variations) {
	var line = jQuery( "#az_lineType2d" ).html();
	line = line.replace( /image_id/g, id );
	line = line.replace( /image_name/g, image_name );

	line = line.replace( /status_field2d/g, 'status_field2d_' + id );

	if (status == 1 || status == 'undefined') {
		line = line.replace(/checked_on/g, 'checked');
		line = line.replace(/checked_off/g, '');
	} else {
		line = line.replace(/checked_on/g, '');
		line = line.replace(/checked_off/g, 'checked');
	}

	line = line.replace( /"(.*?)path\.gif"/g, path );
	line = line.replace( /<tbody>/gi, "" );
	line = line.replace( /<\/tbody>/gi, "" );

	if (variations == 'undefined') {
		line = line.replace( /dta_variations/gi, "" );
	} else {
		line = line.replace( /dta_variations/gi, variations );
	}

	jQuery( "#az_imageList2d" ).append( line );

	az_manageTableDisplay('#az_imageTable2d');
}

<?php foreach($images as $image): ?>
az_imageLine2d(<?php echo $image['id'] ?>, '<?php echo $image['thumb']; ?>', '<?php echo $image['image_name']; ?>', <?php echo $image['status']; ?>, '<?php echo $image['variations']; ?>');
<?php endforeach ?>

jQuery(function ( $ ) {

	$('#az_imageList2d').sortable( {
		opacity: 0.8,
		start: function(e, ui) {
			var sort_started = [];
			$("#az_imageList2d tr").each(function(i, el) {
				var data_id = $(this).data('id');
				if (data_id != undefined) {
					sort_started.push(data_id);
				}
			} );

			$(this).data('sort_started', sort_started);
			$(ui.helper).addClass('az_sortable_border');
		},
		stop: function( event, ui ) {
			$(ui.item).removeClass('az_sortable_border');
			var sort = [];
			$("#az_imageList2d tr").each(function(i, el) {
				sort.push($(this).data('id'));
			} );

			if (JSON.stringify($(this).data('sort_started')) != JSON.stringify(sort)) {
				var params = {
					"action": "ajaxzoom_save_2d_sort",
					"sort": sort,
					"id_product": ajaxzoomData.id_product
				};

				az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function(data) {
					az_showSuccessMessage( data.confirmations );
				} );
			}
		}
	} );

	$( 'body' ).on( 'click', '.btn-variations', function( e ) {
		e.preventDefault();
		var values = $(this).data('variations').toString().split(',');

		var v = $( "#az_template2d_variations" ).html();
		var popup = $(this).closest('tr').find('.az_popup-variations');
		if (popup.html() == '') {
			popup.html(v);

			$.each(popup.find('input[type=checkbox]'), function(index, checkbox) {
				if ($.inArray($(this).val(), values) != -1) {
					$(this).prop('checked', true);
				}
			} );

			popup.slideDown(150);

		} else {
			popup.slideUp(150, function() {
				popup.html('');
			} );
		}

	} );

	$( 'body' ).on( 'click', '.az_variations2d_cancel', function(e) {
		e.preventDefault();
		$(this).closest('tr').find('.btn-variations').trigger('click');
	} );

	$( 'body' ).on( 'click', '.az_variations2d_save', function(e) {
		e.preventDefault();

		var parent = $(this).parent().parent().parent();
		var id = parent.data('id');
		var variations = [];

		$.each(parent.find('input[name="variations[]"]:checked'), function () {
			variations.push($(this).val())
		} );

		parent.find('.btn-variations').data('variations', variations.join(','))

		var params = {
			"action": "ajaxzoom_save_2d_variations",
			"id_image": id,
			"variations": variations,
			"id_product": ajaxzoomData.id_product
		};

		az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function ( data ) {
			$('#az_imageList2d tr[data-id=' + data.id + '] td .az_popup-variations')
			.slideUp(150, function() {
				$(this).html('');
			} );

			az_showSuccessMessage( data.confirmations );
		} );
	} );

	$( 'body' ).on( 'click', '.az_delete_product_image2d', function(e) { 
		e.preventDefault();
		var id = $(this).closest('tr').data( 'id' );

		var params = {
			"action": "ajaxzoom_delete_product_image_2d",
			"id_image": id,
			"id_product": ajaxzoomData.id_product
		};

		if ( confirm( "<?php echo __( 'Are you sure?', 'ajaxzoom' ) ?>" ) ) {
			az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function ( data ) {
				if ( data ) {
					var id = data.content.id;
					if ( data.status == 'ok' ) {
						$( "#az_imageTable2d").find('tr[data-id=' + id + ']').remove();
					}

					az_showSuccessMessage( data.confirmations );
					window.az_refresh_pictures_list();
					az_manageTableDisplay('#az_imageTable2d');
				}
			} );
		}
	} );

	// deactivate 2d image
	$('body').on('change', '.az_switch_status_2d_img input', function(e) {
		e.preventDefault();
		var status = $(this).val();
		var id = $(this).closest('tr').attr('data-id');

		az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
			"action": "ajaxzoom_set_2d_img_status",
			"id": id,
			"status": status,
			"fc": "module",
			"module": "ajaxzoom",
			"controller": "image360",
			"ajax" : 1
			}, function(data) {
				if (data.status == 1) {
					az_showSuccessMessage(az_lang_2d_img_enabled);
				} else {
					az_showSuccessMessage(az_lang_2d_img_disabled);
				}
		} );
	} );

	$('#az_open_2d_variations').on('click', function(e) {
		e.preventDefault();
		$(this).blur();
		$('#az_imageList2d tr').each(function() {
			var _this = $(this);
			if ($('.az_popup-variations', _this).html() == '' ) {
				$('.btn-variations', _this).trigger('click');
			}
		} );
	} );

	$('#az_close_2d_variations').on('click', function(e) {
		e.preventDefault();
		$(this).blur();
		$('#az_imageList2d tr').each(function() {
			var _this = $(this);
			if ($('.az_popup-variations', _this).html() != '' ) {
				$('.btn-variations', _this).trigger('click');
			}
		} );
	} );
} );
</script>