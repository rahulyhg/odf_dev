<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<table cellspacing="0" class="az-form-list az_container">
	<tbody>
		<tr>
			<td class="label"><label for="meta_title"><?php echo __('AJAX-ZOOM enabled') ?></label></td>
			<td class="value">
				<input type="radio" name="az_active" id="az_active_on" value="1" <?php if($active == 1):?>checked="checked"<?php endif ?>/>
				<label class="t" for="az_active_on"><?php echo __('Yes') ?></label>
				<input type="radio" name="az_active" id="az_active_off" value="0" <?php if($active == 0):?>checked="checked"<?php endif ?>/>
				<label class="t" for="az_active_off"><?php echo __('No') ?></label>
				<p class="note">
					<?php echo __('Enable / disable AJAX-ZOOM for this products detail view. '); ?>
					<?php echo __('You can also enable it for certain, already existing products by filling one ore more product IDs into "displayOnlyForThisProductID" option in AJAX-ZOOM plugin settings. '); ?>
					<?php echo __('If you want to keep your templates product detail view but add 360s to e.g. long description of the product, you can do so by disabling  "enableInFrontDetail" option in AJAX-ZOOM plugin settings, '); ?>
					<?php echo __('creating a 360 / 3D and use the short code to insert it where you want. '); ?>
				</p>
			</td>
			<td class="scope-label"><span class="nobr"></span></td>
		</tr>
		<tr>
			<td class="label"><label for="meta_title"> </label></td>
			<td class="value">
				<a href="javascript: void(0)" id="az_toggle_az_settings" style="text-decoration: none">
					<span class="az_arrowhead_down az_arrowhead_span"></span>
					<?php echo __('Individual module settings for this product') ?>
				</a>
			</td>
			<td class="scope-label"><span class="nobr"></span></td>
		</tr>
		<tr><td colspan="2">
			<div style="display: none;" id="az_az_settings">
				<div class="az-form">
					<div class="notice-info notice inline">
						<?php echo __('Override module settings for this product only.') ?>
						<?php echo __('It is not needed and you do not have to set them here individually.') ?>
						<?php echo __('This is just for testing / experimenting and demo.') ?>
						<?php echo __('For reference see in module settings or visit www.ajax-zoom.com/examples/example32.php') ?>
					</div>

					<table style="width: 100%">
						<thead>
							<tr>
								<th style="text-align: left;"><?php echo __('Name') ?></th>
								<th></th>
								<th style="text-align: left;"><?php echo __('Value') ?></th>
								<th style="width: 120px;"></th>
							</tr>
						</thead>
						<tbody id="az_pairRows_module">
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4">
									<div class="row_">
										<button class="button" id="az_link_add_option_module" style="margin-top: 3px;">
											<?php echo __('Add an option') ?>
										</button>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>

					<table id="az_pairTemplate_module" style="display: none">
						<tr>
							<td><input type="text" name="az_pair_name_module[]" value="name_placeholder" 
								class="az_pair_names_module form-control" data-list="az_pair_list_names" style="width: 100%;">
							</td>
							<td style="width: 20px;">&nbsp; : &nbsp;</td>
							<td><input type="text" name="az_pair_value_module[]" value="value_placeholder" 
								class="az_pair_values_module form-control" style="width: 100%;">
							</td>
							<td style="white-space: nowrap;">
								&nbsp;&nbsp;
								<a class="link_textarea_option_module" href="#">
									<?php echo __('Edit') ?>
								</a>
								&nbsp;&nbsp;
								<a class="link_remove_option_module" href="#">
									<?php echo __('Delete') ?>
								</a>
							</td>
						</tr>
					</table>

					<table style="width: 100%;">
						<tbody>
							<tr>
								<td style="text-align: right;">
									<button type="submit" name="az_submitSettings_module" 
										id="az_submitSettings_module" class="button-primary" style="margin-top: 10px;">
										<?php echo __('Save and stay') ?>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</td></tr>
		<tr>
			<td class="label"><label for="meta_title"> </label></td>
			<td class="value">
				<a href="javascript: void(0)" id="az_toggle_prod_shortcode" style="text-decoration: none">
					<span class="az_arrowhead_down az_arrowhead_span"></span>
					<?php echo __('Product all media short code') ?>
				</a>
			</td>
			<td class="scope-label"><span class="nobr"></span></td>
		</tr>
		<tr><td colspan="2">
			<div style="display: none" id="az_product_shortcode">
				<div class="az-form">
					<div class="notice-info notice inline">
						<?php echo __('Besides short codes, which you can generate for separate media items like 360 views or images below, this is the copy & paste short code for all media items of this product including 360 views, images and videos.') ?>
						<?php echo __('Media items assigned for variants are not included.') ?>
						<?php echo __('You can place this short code e.g. in a post in areas, where short codes in general are supported (not disabled).') ?>
					</div>
					<textarea style="width: 100%; min-height: 50px">[ajaxzoom_woo_media id_product="<?php echo $product_id; ?>" prop_w="16" prop_h="16" border_width="1"]</textarea>
				</div>
			</div>
		</td></tr>
	</tbody>
</table>

<br>
<div class="entry-edit az_container">

	<div class="fieldset fieldset-wide">
		<div class="hor-scroll">
			<table cellspacing="0" class="az-form-list">
				<tbody>
					<tr class="">
						<td class="label"><label><?php echo __('Existing 360/3D'); ?></label></td>
						<td class="value">
							<?php foreach ( $groups as $group ): ?>
							<input type="hidden" name="settings[<?php echo $group['id_360'] ?>]" id="settings_<?php echo $group['id_360'] ?>" value="<?php echo urlencode( $group['settings'] ) ?>">
							<input type="hidden" name="comb[<?php echo $group['id_360'] ?>]" id="settings_comb_<?php echo $group['id_360'] ?>" value="<?php echo urlencode( $group['combinations'] ) ?>">
							<?php endforeach; ?>
							<select id="az_select_id_360" name="az_select_id_360">
								<option value=""><?php echo __( 'Select', 'ajaxzoom' ); ?></option>
								<?php foreach ( $groups as $group ): ?>
								<option value="<?php echo $group['id_360'] ?>" data-settings="<?php echo urlencode( $group['settings'] ) ?>" data-combinations="[<?php echo urlencode( $group['combinations'] ) ?>]"><?php echo $group['name'] ?></option>
								<?php endforeach; ?>
							</select>
							<div class="az_closeSelect"></div>
							<p class="note">
								<?php echo __( 'Settings for existing 360/3D views', 'ajaxzoom' ); ?>
							</p>
						</td>
						<td class="scope-label"><span class="nobr"></span></td>
					</tr>
				</tbody>
			</table>

			<div class="az-form" id="az-settings-form" style="display: none; position: relative;">
				<div id="az-settings-form-img"></div>
				<?php if ( count( $variations ) ): ?>
				<table cellspacing="0" class="az-form-list" style="margin-right: 50px;">
					<tbody>
						<tr>
							<td class="label"><?php echo __( 'Variations', 'ajaxzoom' ) ?></td>
							<td class="value">
								<a href="#" id="az_comb-check-all-360" style="margin-bottom: 10px;"><?php echo __( 'check all', 'ajaxzoom' ) ?></a><br>

								<?php foreach ( $variations as $id => $name ): ?>
								<input type="checkbox" name="combinations[]" value="<?php echo $id ?>" class="az_settings-combinations-360"> <?php echo $name ?><br>
								<?php endforeach; ?>

								<p class="note">
									<?php echo __( 'Same as with images you can define which 360 should be shown in conjunction with which combinations.', 'ajaxzoom' ) ?>
									<?php echo __( 'If you do not select any this 360 will be shown for all combinations.', 'ajaxzoom' ) ?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php endif; ?>
			
				<table cellspacing="0" class="az-form-list">
					<thead>
						<tr>
							<td class="label"><label><?php echo __('Settings'); ?></label></td>
							<td><?php echo __( 'Name', 'ajaxzoom' ); ?></td>
							<td></td>
							<td><?php echo __( 'Value', 'ajaxzoom' ); ?></td>
							<td></td>
						</tr>
					</thead>
					<tbody id="az-pair-rows">
					</tbody>
					<tbody>
						<tr>
							<td class="label"></td>
							<td class="value" colspan="4">
								<div>
									<button id="az_link_add_option_360" class="button add">
										<?php echo __( 'Add an option', 'ajaxzoom' ); ?>
									</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<table cellspacing="0" class="az-form-list">
					<tbody>
						<tr>
							<td class="label"></td>
							<td class="value" colspan="4">
								<div style="margin-top: 15px;">
									<button id="az_save_settings_360" class="button button-primary"><?php echo __( 'Save Settings', 'ajaxzoom' ) ?></button>
									<button id="cancel_settings" class="button"><?php echo __( 'Cancel', 'ajaxzoom' ) ?></button>
									<p class="note">
										<?php echo __('Except the "position" all other options you can set here are from AJAX-ZOOM main script. ', 'ajaxzoom'); ?>
										<?php echo __('The defaults are defined in /axZm/zoomConfig.inc.php but might be overwritten in /axZm/zoomConfigCustom.in.php or zoomConfigCustomAZ.inc.php located in plugin directory. ', 'ajaxzoom'); ?>
										<?php echo __('If the option value is an array, you can write it as JSON object type, e.g. if you want to enable "spinNoInit" option, write spinNoInit for name and {"enabled":true} for the value. ', 'ajaxzoom'); ?>
									</p>
								</div>
								<div id="az_prev_next_360">
									<span class="dashicons dashicons-arrow-left" style="cursor: pointer" id="az_prev_360_settings"></span> 
									<span class="dashicons dashicons-arrow-right" style="cursor: pointer" id="az_next_360_settings"></span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<table id="az-pair-template" style="display: none">
					<tr>
						<td class="label"></td>
						<td><input type="text" name="name[]" value="name_placeholder" class="pair-names"></td>
						<td>&nbsp; : &nbsp;</td>
						<td><input type="text" name="value[]" value="value_placeholder" class="pair-values"></td>
						<td>
							&nbsp;&nbsp;
							<a class="link_textarea_option" href="javascript:void(0)">
								<?php echo __( 'Edit', 'ajaxzoom' ); ?>
							</a>
							&nbsp;&nbsp;
							<a class="link_remove_option" href="javascript:void(0)">
								<?php echo __( 'Delete', 'ajaxzoom' ); ?>
							</a>
						</td>
					</tr>
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

window.az_plugin_opt = <?php echo json_encode($az_plugin_opt); ?>;
window.az_plugin_prod_opt = <?php echo json_encode($az_plugin_prod_opt, JSON_FORCE_OBJECT); ?>;

window.az_lang_module_settings_edited = '<?php echo __('Module settings for this product have been set'); ?>';

jQuery( function ( $ ) {

	function pairLine( name, value ) {
		if (typeof value == 'string') {
			value = value.replace(/"/g, "&quot;");
		}

		var line = $( "#az-pair-template" ).html();
		line = line.replace( /name_placeholder/g, name );
		line = line.replace( /value_placeholder/g, value );
		line = line.replace( /<tbody>/gi, "" );
		line = line.replace( /<\/tbody>/gi, "" );
		$( "#az-pair-rows" ).append( line );
	}

	function getFieldValues( class1 ) {
		var inputs = document.getElementsByClassName( class1 );
		var res = [];
		for ( var i = 0; i < inputs.length; i++ ) {
			res.push( inputs[i].value );
		}

		return res;
	}

	function setPairString() {
		var names = getFieldValues( 'pair-names' );
		var values = getFieldValues( 'pair-values' );
		var res = {};
		for ( var i = 0; i < names.length; i++ ) {
			if( names[i] == 'name_placeholder' ) continue;
			res[ names[i] ] = values[i];
		};

		$( '#settings_' + $( 'select#az_select_id_360' ).val() ).val( encodeURIComponent( JSON.stringify( res ) ) );
	}

	function saveSettings() {
		var active = $( 'input[name=az_active]:checked' ).val();

		var inputs = document.getElementsByClassName( 'pair-names' ), 
		names  = [].map.call( inputs, function( input ) { 
			return input.value;
		} ).join( '|' );

		var inputs = document.getElementsByClassName( 'pair-values' ), 
		values  = [].map.call( inputs, function( input ) {
			return input.value;
		} ).join( '|' );

		var tmp = [];
		$( '.az_settings-combinations-360' ).each( function() {
			if ( $( this ).is( ':checked' ) ) {
				tmp.push( $( this ).val() );
			}
		} );

		var combinations = tmp.join( '|' );
		var id_360 = $('select#az_select_id_360').val();
		var params = {
			'action': 'ajaxzoom_save_settings', 
			"id_product" : ajaxzoomData.id_product,
			"id_360" : id_360,
			"names" : names,
			"combinations" : combinations,
			"values" : values,
			"active" : active,
			'mode': 'single'
		};

		az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function ( data ) { 
			$( '#az_select_id_360' ).replaceWith( data.select );
			$( '#az-settings-form' ).slideUp(150);
			if( data.id_360 > 0 ) {
				//$( 'select#az_select_id_360' ).val( data.id_360 ).change();
				$( 'select#az_select_id_360' ).val('').change();
			}

			az_showSuccessMessage( data.confirmations );
		} );
	}

	function setComb() {
		var values = [];
		$( '.az_settings-combinations-360:checked' ).each( function () {
			values.push( $( this ).val() );
		} );

		$( '#settings_comb_' + $( 'select#az_select_id_360').val() ).val( encodeURIComponent( JSON.stringify( values ) ) );
	}

	$( 'body' ).on( 'change', '.pair-names, .pair-values', function( e ) {
		setPairString();
	} );

	$( '.az_settings-combinations-360' ).on( 'change', function( e ) {
		setComb();
	} );

	$( '#az_link_add_option_360' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();
		pairLine( '', '' );
	} );

	$( 'body' ).on( 'click', '.link_remove_option', function( e ) {
		e.preventDefault();
		$(this).blur();
		$( this ).parent().parent().remove();
		setPairString();
	} );

	$( '#az_save_settings_360' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();
		saveSettings();
	} );

	$('#az_next_360_settings').click(function(e) {
		$(this).blur();
		e.stopPropagation();
		e.preventDefault();
		var c = $('#az_select_id_360').val();
		var n = $('#az_select_id_360 option[value="'+c+'"]').next();
		if (n.length) {
			$('#az_select_id_360').val(n.val()).trigger('change')
		} else {
			$('#az_select_id_360').val($('#az_select_id_360 option:eq(1)').val()).trigger('change');
		}
	} );

	$('#az_prev_360_settings').click(function(e) {
		$(this).blur();
		e.stopPropagation();
		e.preventDefault();
		var c = $('#az_select_id_360').val();
		var n = $('#az_select_id_360 option[value="'+c+'"]').prev();
		if (n.length && n.val()) {
			$('#az_select_id_360').val(n.val()).trigger('change')
		} else {
			$('#az_select_id_360').val($('#az_select_id_360 option:last').val()).trigger('change');
		}
	} );

	$( '#cancel_settings' ).click( function ( e ) {
		e.preventDefault();
		$(this).blur();
		$( 'select#az_select_id_360' ).val( '' ).change();
	} );

	$( 'body' ).on( 'click', '.link_textarea_option', function( e ) {
		e.preventDefault();
		$(this).blur();

		var td = $( this ).parent().prev();
		if ( $( 'input', td ).length == 1 ) { 
			var val = $( 'input', td ).val();
			$( 'input', td ).replaceWith( '<textarea class="pair-values" type="text" name="value[]">' + val + '</textarea>' );
		} else if ( $( 'textarea', td ).length == 1 ) { 
			var val = $( 'textarea', td ).val();
			if (typeof val == 'string') {
				val = val.replace(/"/g, "&quot;");
			}

			$( 'textarea', td ).replaceWith( '<input class="pair-values" type="text" value="' + val + '" name="value[]">' );
		}
	} );

	$( 'body' ).on( 'change', '#az_select_id_360', function( e ) {
		$(this).blur();
		$( '#az-pair-rows' ).html( '' );

		if( $( this ).val() != '' ) { 
			// set pairs name:value
			var settings = $.parseJSON( decodeURIComponent( $( 'option:selected', $( this ) ).attr( 'data-settings' ).replace(/\+/g, '%20') ) );
			for( var k in settings ) { 
				pairLine( k, settings[k] );
			}

			// set combinations checkboxes
			var combinations = $.parseJSON( decodeURIComponent( $( 'option:selected', $( this ) ).attr( 'data-combinations' ).replace(/\+/g, '%20') ) );

			$( '.az_settings-combinations-360' ).attr( 'checked', false );
			if( combinations && combinations.length ) {
				for ( var i = combinations.length - 1; i >= 0; i-- ) {
					$( 'input.az_settings-combinations-360[value=' + combinations[i] + ']' ).attr( 'checked', true );
				};
			}

			var img_thumb = $('#az-image-table-sets-rows tbody[data-group="'+$( this ).val()+'"] .img-thumbnail');
			$('#az-settings-form-img').empty();
			if (img_thumb.length) {
				var idx = Math.floor(img_thumb.length/2);
				var img_thumb_m = $(img_thumb[idx]);
				if (img_thumb_m.attr('src').indexOf('no_image') === -1) {
					$('#az-settings-form-img').append(img_thumb_m.clone().attr('title', 'close settings').bind('click', function(){
						$('#az_select_id_360').next().trigger('click');
					}));
				}
			}

			$('#az_prev_next_360').css('display', $('#az_select_id_360 option').length > 2 ? 'block' : 'none');

			$( '#az-settings-form' ).slideDown(150);
		} else {
			$( '#az-settings-form' ).slideUp(150);
		}
	} );

	$( '#az_comb-check-all-360' )
	.toggle( function() { 
		$(this).blur();
		$( '.az_settings-combinations-360' ).attr( 'checked', 'checked' );
		$( this ).html( 'uncheck all' );
	}, function() { 
		$(this).blur();
		$( '.az_settings-combinations-360' ).removeAttr( 'checked' );
		$( this ).html( 'check all' );
	} );


	$( 'input[name=az_active]' ).change( function ( e ) {
		saveSettings();
	} );

	// PLUGIN SETTINGS FOR PRODUCT
	if (window.az_plugin_opt && typeof az_plugin_opt == 'object') {
		var datalist = '<datalist id="az_plugin_opt_list">';
		$.each(az_plugin_opt, function(k, v) {
			datalist += '<option value="'+k+'">'+k+'</option>';
		} );

		datalist += '</datalist>';
		$('body').append(datalist);
		delete datalist;
	}

	function pairLineModule(name, value) { 
		if (typeof value == 'object') {
			value = JSON.stringify(value).replace(/"/g, "&quot;");
		}

		if (typeof value == 'string') {
			value = value.replace(/"/g, "&quot;");
		}

		if (name.indexOf('/') != -1) {
			name = name.split('/')[1];
		}

		var line = $("#az_pairTemplate_module").html();
		line = line.replace(/name_placeholder/g, name);
		line = line.replace(/value_placeholder/g, value);
		line = line.replace(/az_pair_names_module/g, 'az_pair_names_module_a');
		line = line.replace(/az_pair_values_module/g, 'az_pair_values_module_a');
		line = line.replace(/az_pair_list_names/g, 'az_plugin_opt_list');
		line = line.replace(/<tbody>/gi, "");
		line = line.replace(/<\/tbody>/gi, "");
		line = $(line);

		$("#az_pairRows_module").append(line);

		setTimeout(function() {
			$('input.az_pair_names_module_a', line).aZeditableSelect();
			}, 1);
	}

	$('#az_link_add_option_module').bind('click', function(e) {
		e.preventDefault();
		$(this).blur();
		pairLineModule('', '');
	} );

	$('#az_toggle_prod_shortcode').bind('click', function(e) {
		e.preventDefault();
		$(this).blur();
		if ($('.az_arrowhead_span', $(this)).is('.az_arrowhead_down')) {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_down').addClass('az_arrowhead_up');
		} else {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_up').addClass('az_arrowhead_down');
		}

		$('#az_product_shortcode').slideToggle(150);
	} );

	$('#az_product_shortcode textarea').on("focus", function() {
		$(this).select();
	} );

	$('#az_toggle_az_settings').bind('click', function(e) {
		e.preventDefault();
		$(this).blur();
		if ($('.az_arrowhead_span', $(this)).is('.az_arrowhead_down')) {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_down').addClass('az_arrowhead_up');
		} else {
			$('.az_arrowhead_span', $(this)).removeClass('az_arrowhead_up').addClass('az_arrowhead_down');
		}

		$('#az_az_settings').slideToggle(150);
	} );

	$('body').on('click', '.link_textarea_option_module', function(e) {
		e.preventDefault();
		$(this).blur();

		var td = $(this).parent().prev();
		if ($('input', td).length == 1) { 
			var Val = $('input', td).val().replace(/"/g, "&quot;");
			$('input', td).replaceWith('<textarea class="az_pair_values_module_a form-control" type="text" style="width: 100%" name="az_pair_value[]">'+Val+'</textarea>');
		} else if ($('textarea', td).length == 1) { 
			var Val = $('textarea', td).val().replace(/"/g, "&quot;");
			$('textarea', td).replaceWith('<input class="az_pair_values_module_a form-control" type="text" style="width: 100%" value="'+Val+'" name="az_pair_value[]">');
		}
	} );

	$('body').on('click', '.link_remove_option_module', function(e) {
		e.preventDefault();
		$(this).blur();

		$(this).closest('tr').remove();
		$('#az_submitSettings_module').addClass('az_save_require');
	} );

	$('body').on('change', '.az_pair_names_module_a, .az_pair_values_module_a', function() {
		$('#az_submitSettings_module').addClass('az_save_require');
	} );

	$.each(window.az_plugin_prod_opt, function(a, b) {
		pairLineModule(a, b);
	} );

	$('#az_submitSettings_module').bind('click', function(e) {
		e.preventDefault();
		$(this).blur();

		var names = [];
		var values = [];

		$('.az_pair_names_module_a[value!=name_placeholder]').each(function() { 
			var vn = $(this).val();
			names.push(vn)
		} );

		$('.az_pair_values_module_a[value!=value_placeholder]').each(function() { 
			var vv = $(this).val();
			values.push(vv)
		} );

		$('#az_submitSettings_module').removeClass('az_save_require');

		az_doAdminAjax360(ajaxzoomData.ajaxUrl, { 
			"action": "ajaxzoom_save_product_az_settings",
			"id_product": ajaxzoomData.id_product,
			"names": names.join('|'),
			"values": values.join('|'),
			"ajax": 1
			}, function (data) { 
				//data = $.parseJSON(data);
				if (data.moduleSettings) {
					window.az_plugin_prod_opt = data.moduleSettings;
				} else {
					window.az_plugin_prod_opt = {};
				}

				$("#az_pairRows_module").empty();
				$.each(window.az_plugin_prod_opt, function(a, b) {
					pairLineModule(a, b);
				} );

				az_showSuccessMessage(az_lang_module_settings_edited);
		} );
	} );

} );
</script>