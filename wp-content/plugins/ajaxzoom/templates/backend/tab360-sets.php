<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<br>
<br>
<div id="product-images360sets" class="entry-edit az_container">
	<div class="fieldset fieldset-wide">
		<div class="hor-scroll">
			<a class="button az_btn_success" href="javascript:void(0)" id="az_link_add_360" style="width: 100%; margin-bottom: 10px; text-align: center;"> 
				<i class="icon-plus"></i> <?php echo __( 'Add a new 360/3D view', 'ajaxzoom' ) ?>
			</a>
			<div class="az-form" id="az_newForm360" style="display: none;">
				<div class="notice-info notice inline">
					<?php
					echo __('After creating a 360 view you will be able to assign it to certain variations and adjust the settings like spin direction and the like. ', 'ajaxzoom');
					echo __('Furthermore you can create stunning 360 product tours and / or place clickable hotspots. ', 'ajaxzoom');
					?>
				</div>
				<table cellspacing="0" class="az-form-list">
					<tbody>
						<tr>
							<td class="label"><label for="az_set_name_360"><?php echo __( 'Create a new', 'ajaxzoom' ) ?></label></td>
							<td class="value">
								<input type="text" id="az_set_name_360" name="az_set_name_360" value="" />
								<p class="note"><?php echo __( 'Please enter any name', 'ajaxzoom' ) ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td colspan="3"><b><?php echo __( 'OR', 'ajaxzoom' ) ?></b></td>
						</tr>
						<tr>
							<td class="label"><label for="az_existing_360"><?php echo __( 'Add to existing 3D as next row', 'ajaxzoom' ) ?></label></td>
							<td class="value">
								<select name="az_existing_360" id="az_existing_360">
									<option value="" style="min-width: 100px"><?php echo __( 'Select', 'ajaxzoom' ) ?></option>
									<?php if ($groups) { foreach ( $groups as $group ) { ?>
									<option value="<?php echo $group['id_360'] ?>"><?php echo $group['name'] ?></option>
									<?php } } ?>
								</select>
								<p class="note"><?php echo __( 'You should not select anything here unless you want to create 3D (not 360) which contains more than one row!', 'ajaxzoom' ) ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>

						<tr>
							<td class="label"><label for="az_zip360"><?php echo __( 'Add images from ZIP archive', 'ajaxzoom' ) ?></label></td>
							<td class="value">
								<input type="checkbox" id="az_zip360" name="az_zip360" value="1" />
								<p class="note"><?php echo __( 'This is the most easy and quick way of adding 360 views to your product! Upload over FTP your 360\'s zipped (each images set in one zip file) to ' . $uri . '/ajaxzoom/zip/ directory. After you did so these zip files will instantly appear in the select field below. All you have to do then is select one of the zip files and press \'add\' button. Images from the selected zip file will be instantly imported.', 'ajaxzoom' ) ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr class="az_field-arcfile_360" style="display: none;">
							<td class="label"><label for="arcfile"><?php echo __( 'Select ZIP archive or folder', 'ajaxzoom' ) ?></label></td>
							<td class="value">
								<?php if ( isset( $files ) && count( $files ) > 0 ): ?>
								<select name="az_arcfile_360" id="az_arcfile_360">
									<option value=""><?php echo __( 'Select', 'ajaxzoom' ) ?></option>
									<?php foreach ( $files as $file ): ?>
									<option value="<?php echo $file ?>"><?php echo $file ?></option>
									<?php endforeach; ?>
								</select>
								<?php else: ?>
								<p><b><?php echo __( 'There are no files found in the "/wp-content/plugins/axaxzoom/zip" folder', 'ajaxzoom' ) ?></b></p>
								<?php endif; ?>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr class="az_field-arcfile_360" style="display: none;">
							<td class="label"><label for="az_zip360_delete"><?php echo __( 'Delete Zip/Dir after import', 'ajaxzoom' ) ?></label></td>
							<td class="value">
								<input type="checkbox" id="az_zip360_delete" name="az_zip360_delete" value="1" />
								<p class="note"></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>

						<tr>
							<td></td>
							<td>
								<button type="button" id="az_save_set" class="button button-primary save"><?php echo __( 'Add', 'ajaxzoom' ) ?></button>
								<button type="button" class="button" id="az_cancel-set"><?php echo __( 'Cancel', 'ajaxzoom' ) ?></button>

								<a href="javascript: void(0)" id="az_toggle_az_notice_cache" style="text-decoration: none; float: right;">
									<span class="az_arrowhead_span az_arrowhead_down"></span>
									Notice about AJAX-ZOOM cache
								</a>
								<div class="notice-info notice inline" id="az_notice_cache" style="margin-top: 15px; display: none;">
									<a class="button" style="margin-left: 5px; float: right;" 
										href="<?php echo admin_url();?>admin.php?page=ajaxzoombatch">
										<i class="fa fa-th-large"></i> Batch Tool
									</a>
									<?php echo __( 'Info: after you have created a 360 and imported from ZIP or uploaded the images over the interface, you can press the "Preview" button to create cache for the AJAX-ZOOM player. ', 'ajaxzoom' ); ?>
									<?php echo __( 'Alternatively, even if the 360 is disabled, you can use the new batch tool to create all needed caches for many 360 objects at once and activate them automatically thereafter. ', 'ajaxzoom' ); ?>
									<?php echo __( 'If the cache is not present and a 360 object is activated for the frontend, then it will be generated on-the-fly. Depending on the size of the images, this process can take some time. ', 'ajaxzoom' ); ?>
									<?php echo __( 'To work around this, create the cache beforehand through the "Preview" or the new batch tool for many 360 / 3D at once. ', 'ajaxzoom' ); ?>
								</div>

							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>
			<br>
			<div class="row">
				<div class="grid">
				<!-- az_list_table -->
					<table class="wp-list-table widefat fixed striped posts az_list_table" id="az-image-table-sets-rows" > <!-- az-image-table-sets -->
						<thead>
							<tr class="headings">
								<th class="data-grid-th az_timg_head"> </th>
								<th class="data-grid-th" style="width: 100px;"><?php echo __( 'Active', 'ajaxzoom' ) ?></th>
								<th class="data-grid-th"><?php echo __( 'Name', 'ajaxzoom' ) ?></th>
							</tr>
						</thead>
						<!--
						<tbody id="az-image-table-sets-rows">
						</tbody>
						-->
					</table>
				</div>

				<table id="az_lineSet_360" style="display: none;">
					<tbody>
						<tr id="set_id" data-group="group_id" class="az_child_class">
							<td><img src="<?php echo $uri ?>/ajaxzoom/assets/img/image_path.gif" alt="legend" title="legend" class="img-thumbnail" /></td>
							<td valign="top">
								<span class="hide_class switch-status">
									<input type="radio" name="status_field" id="status_field_on" value="1" checked_on />
									<label for="status_field_on"><?php echo __( 'Yes', 'ajaxzoom' ) ?></label>
									<input type="radio" name="status_field" id="status_field_off" value="0" checked_off />
									<label for="status_field_off"><?php echo __( 'No', 'ajaxzoom' ) ?></label>
								</span>
							</td>
							<td valign="top" style="position: relative;">
								legend
								<div class="row-actions">
									<a class="az_360_delete_set az_delete_color" href="javascript:void(0)"><?php echo __( 'Delete', 'ajaxzoom' ) ?></a>
									&nbsp;|&nbsp;
									<a class="az_360_images_set" href="javascript:void(0)"><?php echo __( 'Images', 'ajaxzoom' ) ?></a>
									<span class="hide_class">
										&nbsp;|&nbsp;
										<a class="az_360_preview_set" href="javascript:void(0)"><?php echo __( 'Preview', 'ajaxzoom' ) ?></a>
										&nbsp;|&nbsp;
										<a class="az_360_hotspot_set" href="javascript:void(0)"><?php echo __( 'Hotspots', 'ajaxzoom'); ?></a>
										&nbsp;|&nbsp;
										<a class="az_360_crop_set" href="javascript:void(0)"><?php echo __( '360 Product Tour', 'ajaxzoom'); ?></a>
										&nbsp;|&nbsp;
										<a class="az_360_shortcode_set" href="javascript:void(0)"><?php echo __( 'Shortcode', 'ajaxzoom'); ?></a>
									</span>
								</div>
								<div class="hide_class az_shortcode_div az_shortcode_div_id_360" style="display: none;"></div>
							</td>
						</tr>
					</tbody>
				</table>
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

jQuery( function ( $ ) {
	$('#az-image-table-sets-rows').sortable({
		items: '>tbody',
		handle: 'tr:eq(0)',
		opacity: 0.8,
		start: function(e, ui) {
			var sort_started = [];
			$("#az-image-table-sets-rows>tbody").each(function(i, el) {
				var data_group = $(this).data('group');
				if (data_group != undefined) {
					sort_started.push(data_group);
				}
			} );

			$(this).data('sort_started', sort_started);
			$(ui.helper).addClass('az_sortable_border');
		},
		stop: function( event, ui ) {
			$(ui.item).removeClass('az_sortable_border');
			var sort = [];
			$("#az-image-table-sets-rows>tbody").each(function(i, el) {
				sort.push($(this).data('group'));
			} );

			if (JSON.stringify($(this).data('sort_started')) != JSON.stringify(sort)) {
				var params = {
					"action": "ajaxzoom_save_set_sort",
					"sort": sort,
					"id_product": ajaxzoomData.id_product
				};

				az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function(data) {
					az_showSuccessMessage( data.confirmations );
				} );
			}
		}
	});

	function az_regroup360_lines() {
		var groupsArr = [];
		$('#az-image-table-sets-rows>tbody').each(function(){
			var _this = $(this);
			var firstTr = $('tr:eq(0)', _this);
			var thisGroup = firstTr.attr('data-group');
			_this.attr('data-group', thisGroup);
			if (groupsArr.indexOf(thisGroup) != -1) {
				var tmp = firstTr.detach();
				_this.remove();
				tmp.appendTo('#az-image-table-sets-rows>tbody[data-group="'+thisGroup+'"]:eq(0)');
			} else {
				groupsArr.push(thisGroup);
			}
		} );
	}

	function az_setLine360( id, path, position, legend, status, group_id ) {

		line = $( "#az_lineSet_360" ).html();
		line = line.replace( /set_id/g, 'az_set_id_' +id );
		line = line.replace( /group_id/g, group_id );
		line = line.replace( /legend/g, legend );
		line = line.replace( /status_field/g, 'status_' + id );
		var re = new RegExp( ajaxzoomData.image_path, 'g' );
		line = line.replace( re, path );
		//line = line.replace( /<tbody>/gi, "" );
		//line = line.replace( /<\/tbody>/gi, "" );

		if( status == '1' ) {
			line = line.replace( /checked_on/g, 'checked' );
			line = line.replace( /checked_off/g, '' );
		} else {
			line = line.replace( /checked_on/g, '' );
			line = line.replace( /checked_off/g, 'checked' );
		}

		if( $( '#az-image-table-sets-rows tr[data-group=' + group_id + ']' ).length ) {
			line = line.replace( /hide_class/g, 'hide' );
		} else {
			line = line.replace( /az_child_class/g, '' );
		}

		$( "#az-image-table-sets-rows" ).append( line );

		az_manageTableDisplay('#az-image-table-sets-rows', true);
	}

	function afterUpdateStatus( data ) {
		az_showSuccessMessage( data.confirmations );
	}

	$( '#az_zip360' ).change( function () {
		if( $( this ).is( ':checked' ) ) {
			$( '.az_field-arcfile_360' ).show();
		} else {
			$( '.az_field-arcfile_360' ).hide();
		}
	});

	$( '#az_link_add_360' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();

		var icon = $( this ).find( 'i' );

		if( icon.hasClass( 'icon-plus' ) ) {
			icon.removeClass( 'icon-plus' ).addClass( 'icon-minus' );
			$( '#az_newForm360' ).slideDown(150);
		} else {
			icon.removeClass( 'icon-minus' ).addClass( 'icon-plus' );
			$( '#az_newForm360' ).slideUp(150);
		}
	});

	$( 'body' ).on( 'change', '.switch-status input', function( e ) {
		e.preventDefault();
		var status = $( this ).val();
		var group_id = $( this ).closest('tr').data( 'group' );
		var params = {
			"action": "ajaxzoom_set_360_status",
			"id_product": ajaxzoomData.id_product,
			"id_360": group_id,
			"status": status
		};
		az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, afterUpdateStatus );
	} );

	$( 'body' ).on( 'click', '.az_360_preview_set', function( e ) {
		e.preventDefault();
		$(this).blur();

		var id360 = $( this ).parents( 'tr' ).first().data( 'group' );
		var id360set = $( this ).parents( 'tr' ).first().attr( 'id' ).replace('az_set_id_', '');
		var qty = $( '#az-image-table-sets-rows tr[data-group=' + id360 + ']' ).length;

		var url = ajaxzoomData.uri + '/ajaxzoom/preview/preview.php?3dDir=' + ajaxzoomData.uri + 
			'/ajaxzoom/pic/360/' + ajaxzoomData.id_product + '/' + id360;

		if (qty < 2){
			url += '/' + id360set;
		}

		url += '&group=' + id360 + '&id=' + id360set;

		$.openAjaxZoomInFancyBox( {href: url, iframe: true, scrolling: false, boxMargin: 50} );
	});

	$( '.az_360_crop_set' ).die().live( 'click', function(e) {
		e.preventDefault();
		$(this).blur();

		var id360 = $( this ).parents( 'tr' ).first().data( 'group' );
		var id360set = $( this ).parents( 'tr' ).first().attr( 'id' ).replace('az_set_id_', '');
		var qty = $( '#az-image-table-sets-rows tr[data-group=' + id360 + ']' ).length;

		var url = ajaxzoomData.uri + '/ajaxzoom/preview/cropeditor.php?3dDir=' + ajaxzoomData.uri + 
			'/ajaxzoom/pic/360/' + ajaxzoomData.id_product + '/' + id360;

		if (qty < 2){
			url += '/' + id360set;
		}

		url += '&group=' + id360 + '&id=' + id360set;

		$.openAjaxZoomInFancyBox( {href: url, iframe: true, scrolling: 1, boxMargin: 50} );
	} );

	$( '.az_360_hotspot_set' ).die().live( 'click', function(e) {
		e.preventDefault();
		$(this).blur();

		var id360 = $( this ).parents( 'tr' ).first().data( 'group' );
		var id360set = $( this ).parents( 'tr' ).first().attr( 'id' ).replace('az_set_id_', '');
		var qty = $( '#az-image-table-sets-rows tr[data-group=' + id360 + ']' ).length;

		var url = ajaxzoomData.uri + '/ajaxzoom/preview/hotspoteditor.php?3dDir=' + ajaxzoomData.uri + 
			'/ajaxzoom/pic/360/' + ajaxzoomData.id_product + '/' + id360;

		if (qty < 2){
			url += '/' + id360set;
		}

		url += '&group=' + id360 + '&id=' + id360set;

		$.openAjaxZoomInFancyBox( {href: url, iframe: true, scrolling: 1, boxMargin: 50} );
	} );

	$( 'body' ).on( 'click', '.az_360_images_set', function(e) { 
		e.preventDefault();
		$(this).blur();

		$( '#az-image-table-sets-rows' ).find( 'tr' ).removeClass( 'az_active_row' );
		$( this ).closest('tr').addClass( 'az_active_row' );
		$( '#imageList360' ).html( '' );
		$( '#file360-success' ).parent().hide();

		var id = $( this ).closest('tr').attr( 'id' ).replace('az_set_id_', '');
		var params = {
			"action" : "ajaxzoom_get_images",
			"id_product" : ajaxzoomData.id_product,
			"id_360set" : id
		};

		az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function ( data ) {
			for ( var i = 0; i < data.images.length; i++ ) {
				az_imageLine360( data.images[i]['id'], data.images[i]['thumb'], data.images[i]['filename'], "", "", "" );
			};

			var pppPos = $('#product-images360').offset();

			if ($.scrollTo && pppPos && pppPos.top){
				$.scrollTo(pppPos.top - 50, 300);
			}
		} );

		$( '#id_360set' ).val( id );
		$( '#product-images360' ).show();

		$('#container360_upload>div').remove();
		uploader360 = new plupload.Uploader(uploader360Obj());
		uploader360.init();
	} );

	$( '#az_save_set' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();
		$( '#az-image-table-sets-rows' ).find( 'tr' ).removeClass( 'az_active_row' );
		var params = {
			"action": "ajaxzoom_add_set",
			"name": $('#az_set_name_360').val(),
			"existing": $('#az_existing_360').val(),
			"zip": $('#az_zip360').is(':checked'),
			"delete": $('#az_zip360_delete').is(':checked'),
			"arcfile": $('#az_arcfile_360').val(),
			"id_product": ajaxzoomData.id_product
		};

		az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function( data) {

			if( data.sets.length > 0 ) {
				for ( var i = 0; i < data.sets.length; i++ ) {
					var set = data.sets[i];
					az_setLine360( set.id_360set, set.path, "", set.name, set.status, set.id_360 );
				}
			} else {
				az_setLine360( data.id_360set, data.path, "", data.name, data.status, data.id_360 );
			}

			az_regroup360_lines();

			$( '#az_link_add_360' ).find( 'i' ).removeClass( 'icon-minus' ).addClass( 'icon-plus' );
			$( '#az_newForm360' ).hide();
			$( '#az_set_name_360' ).val( '' );
			$( '#az_existing_360' ).val( '' );

			if( data.new_id != '' ) {
				$( 'select#az_select_id_360' )
					.append( $( "<option></option>" )
						.attr( 'value', data.new_id )
						.attr( 'data-settings', data.new_settings )
						.attr( 'data-combinations', '[]' )
						.text( data.new_name ) ); 
				$( '#az_existing_360' ).append( $( "<option></option>" ).attr( 'value', data.new_id ).text( data.new_name ) );

			}

			az_showSuccessMessage( data.confirmations );
		} );
	});

	$( 'body' ).on( 'click', '.az_360_delete_set', function( e ) {

		e.preventDefault();
		$(this).blur();

		$( '#product-images360' ).hide();
		$( '#imageList360' ).html( '' );

		var id = $( this ).closest('tr').attr( 'id' ).replace('az_set_id_', '');
		var params = {
			"action": "ajaxzoom_delete_set",
			"id_360set":id,
			"id_product" : ajaxzoomData.id_product
		};

		if ( confirm( "<?php echo __( 'Are you sure?', 'ajaxzoom' ) ?>" ) ) {
			az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function(data) {

				var line = $( '#az_set_id_' + data.id_360set );
				if (line.closest('tbody').children().length == 1) {
					line.closest('tbody').remove();
				} else {
					line.remove();
				}

				az_showSuccessMessage( data.confirmations );

				// remove set option from the dropdowns
				if( data.removed == '1' ) {
					$( "select#az_select_id_360 option[value='" + data.id_360 + "']" ).remove();
					$( "#az_existing_360 option[value='" + data.id_360 + "']" ).remove();
				}

				$('#az_cancel-set').trigger('click');
				az_manageTableDisplay('#az-image-table-sets-rows', true);

			} );
		}
	} );

	$( '#az_cancel-set' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();
		$( '#az_link_add_360' ).click();
	} );

	$('#az_toggle_az_notice_cache').bind('click', function(e) {
		e.preventDefault();
		$(this).blur();
		if ($('.az_arrowhead_span', $(this)).is('.az_arrowhead_down')) {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_down').addClass('az_arrowhead_up');
		} else {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_up').addClass('az_arrowhead_down');
		}

		$('#az_notice_cache').slideToggle(150);
	} );

	$( 'body' ).on( 'click', '.az_360_shortcode_set', function( e ) {
		e.preventDefault();
		var _this = $(this);
		_this.blur();

		var parent_class = 'az_shortcode_div_id_360';

		var id360 = _this.parents( 'tr' ).first().data( 'group' );
		//var id360set = _this.parents( 'tr' ).first().attr( 'id' ).replace('az_set_id_', '');
		var cont = $('.' + parent_class, _this.closest('tr'));

		if (!cont.length || cont.css('display') != 'none') {
			cont.slideUp(150, function(){
				cont.empty();
			} );
			return;
		}

		$('.' + parent_class).empty().css('display', 'none');
		cont.append('<div>Copy & paste this shortcode anywhere else, e.g. in a post, to display this single 360 or 3D including hotspots and / or product tour outside of WooCommerce product detail page. The 360/3D can have inactive status.</div>');
		cont.append('<textarea class="az_shortcode_text"></textarea>');

		cont.append('<table cellspacing="0" cellpadding="0" style="width: 100%"><tbody class="az_shortcode_body"></tbody></table>');
		var bdy = $('.az_shortcode_body', cont);

		bdy.append('<tr style="display: none;"><td></td><td><input type="hidden" class="az_shortcode_param" data-param="id_360" value="'+id360+'"></td></tr>');
		bdy.append('<tr><td>Heading (h3):</td><td><input type="text" class="az_shortcode_param" data-param="heading" style="width: 100%" value=""></td></tr>');
		bdy.append('<tr><td>Proportions:</td><td><input type="text" class="az_shortcode_param" data-param="prop_w" style="width: 50px" value="16"> by <input type="text" class="az_shortcode_param" data-param="prop_h" style="width: 50px" value="9"></td></tr>');
		bdy.append('<tr><td>Border width:</td><td><input type="text" class="az_shortcode_param" data-param="border_width" style="width: 50px" value="1"></td></tr>');
		bdy.append('<tr><td>Border color:</td><td><input type="text" class="az_shortcode_param" data-param="border_color" style="width: 50px" value=""></td></tr>');
		bdy.append('<tr><td>Other:</td><td>will follow, upon request...</td></tr>');

		cont.append('<div style="text-align: right;"><a class="button az_close_shortcode">Close</a></div>');

		az_shortCodeSlideDown(cont);
	} );

	<?php foreach ( $sets as $set ): ?>
		az_setLine360( "<?php echo $set['id_360set'] ?>", "<?php echo $set['path'] ?>", "", "<?php echo $set['name'] ?>", "<?php echo $set['status'] ?>", "<?php echo $set['id_360'] ?>" );
	<?php endforeach; ?>

	az_regroup360_lines();
} );
</script>
