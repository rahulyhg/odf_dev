<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php $maxImageSize = ini_get( 'upload_max_filesize' ); ?>

<input type="hidden" name="id_360set" id="id_360set" value="" />

<div id="product-images360" class="entry-edit" style="display:none">
	<div class="fieldset-wrapper">
		<div class="fieldset-wrapper-title admin__fieldset-wrapper-title">
			<h1><?php echo __( 'Images', 'ajaxzoom' ) ?></h1>
		</div>
	</div>
	<div class="fieldset fieldset-wide" id="group_fields9">
		<div class="hor-scroll">
			<input type="hidden" name="submitted_tabs[]" value="Images360" />

			<div class="row">
				<div class="form-group">
					<label class="control-label col-lg-3 file_upload_label">
						<?php echo __( 'Add a new image to this image set', 'ajaxzoom' ) ?><br />
						<?php echo __( 'Format: JPG, GIF, PNG. Filesize: '.$maxImageSize.' max', 'ajaxzoom' ); ?>
					</label>
					<div class="col-lg-9">
						<?php require "uploader.php"; ?>
					</div>
				</div>
			</div>
			<div class="grid">
				<table class="wp-list-table widefat fixed striped posts" id="az_imageTable360">
					<thead>
						<tr class="headings">
							<th class="data-grid-th az_timg_head"><?php echo __( 'Image', 'ajaxzoom' ) ?></th>
							<th class="data-grid-th"> </th>
						</tr>
					</thead>
					<tbody id="imageList360">
					</tbody>
				</table>
			</div>
			<table id="az_lineType360" style="display: none;">
				<tr id="image_id">
					<td>
						<img src="<?php echo $uri ?>/ajaxzoom/assets/img/image_path.gif" alt="legend" title="legend" class="img-thumbnail" />
					</td>
					<td>
						file_name
						<div class="row-actions">
							<a class="delete_product_image360 az_delete_color" href="javascript:void(0)">
								<?php echo __( 'Delete this image', 'ajaxzoom' ) ?>
							</a>
						</div>
					</td>
				</tr>
			</table>
			<div class="az-panel-footer">
				<button class="button az_btn_cancel"><?php echo __( 'Cancel', 'ajaxzoom' ) ?></button>
			</div>
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

function az_imageLine360(id, path, filename) {
	line = jQuery( "#az_lineType360" ).html();
	line = line.replace( /image_id/g, id );
	line = line.replace( /file_name/g, filename );
	line = line.replace( /"(.*?)path\.gif"/g, path );
	line = line.replace( /<tbody>/gi, "" );
	line = line.replace( /<\/tbody>/gi, "" );

	jQuery( "#imageList360" ).append( line );
}

jQuery(function ($) {

	function afterDeleteProductImage360(data) {
		if ( data ) {
			id = data.content.id;
			if ( data.status == 'ok' ) {
				$( "#" + id ).remove();
			}

			az_showSuccessMessage( data.confirmations );
		}
	}

	$( '.az_btn_cancel' ).on( 'click', function(e) {
		e.preventDefault();
		$('#product-images360').hide();
		$( '#az-image-table-sets-rows' ).find( 'tr' ).removeClass( 'az_active_row' );
		if ($.scrollTo){
			$.scrollTo($('#ajaxzoom').position().top - 20, 300);
		}
	} );

	$( 'body' ).on( 'click', '.delete_product_image360', function(e) { 
		e.preventDefault();
		var id = $(this).closest('tr').attr( 'id' );
		var id_360set = $( '#id_360set' ).val();
		var ext = $( this ).closest('tr').find( 'img' ).attr( 'src' ).split( '.' ).pop();
		var params = {
			"action": "ajaxzoom_delete_product_image_360",
			"id_image": id,
			'id_360set': id_360set,
			"ext": ext,
			"id_product": ajaxzoomData.id_product
		};

		if ( confirm( "<?php echo __( 'Are you sure?', 'ajaxzoom' ) ?>" ) ) {
			az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, afterDeleteProductImage360 );
		}
	} );

} );
</script>