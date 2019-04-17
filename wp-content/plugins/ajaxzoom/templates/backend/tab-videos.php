<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="entry-edit az_container">
	<div class="fieldset fieldset-wide">
		<div class="notice-info notice inline">
			<?php
			echo __('With this widget you can define videos from YouTube, Vimeo, Dailymotion or link to mp4 videos located else where. ', 'ajaxzoom');
			echo __('For variable product, you are able to associate videos only with certain product variations. ', 'ajaxzoom');
			echo __('Also you can, but not obligated to, define alternative video sources for shop languages. ', 'ajaxzoom');
			?>
		</div>

		<div class="hor-scroll">
			<table cellspacing="0" class="az-form-list">
				<tbody>
					<tr>
						<td class="label"><label><?php echo __('Existing videos', 'ajaxzoom'); ?></label></td>
						<td class="value">
							<select id="az_id_video" name="az_id_video" style="" class="form-control">
								<option value=""><?php echo __('Select', 'ajaxzoom'); ?></option>
							</select>
							<div class="az_closeSelect"></div>
							<p class="note">
								<?php echo __('Select a video to change the settings', 'ajaxzoom'); ?>
							</p>
						</td>
						<td class="scope-label"><span class="nobr"></span></td>
					</tr>
				</tbody>
			</table>

			<div id="az_settings_video" class="form-list az-form" style="display: none; position: relative;">
				<div id="az_settings_video_img"></div>
				<table cellspacing="0" class="az-form-list">
					<tbody>
						<tr>
							<td class="label"><label><?php echo __('Name', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<input type="text" id="az_video_name_edit" name="az_video_name_edit" value="" class="form-control" />
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td class="label"><label><?php echo __('Type', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<select class="form-control" id="az_video_type_edit" name="az_video_type_edit">
									<option value="youtube">YouTube</option>
									<option value="vimeo">Vimeo</option>
									<option value="dailymotion">Dailymotion</option>
									<option value="videojs">HTML5 / videojs</option>
								</select>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td class="label"><label><?php echo __('Key / Url', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<input type="text" id="az_video_uid_edit" name="az_video_uid_edit" value="" class="form-control" /> 
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<?php if (count($az_languages)): ?>
							<tr>
								<td class="label"><label><?php echo __('Key / Url (international)', 'ajaxzoom'); ?></label></td>
								<td class="value">
									<?php foreach ($az_languages as $id => $name): ?>
										<div class="row">
											<span style="min-width: 50px; display: inline-block;"><?php echo strtoupper($name); ?></span>
											<input type="text" name="az_video_lang_edit[<?php echo $id; ?>]"
												data-lang="<?php echo $id; ?>" 
												value="" class="az_video_lang_edit form-control" style="margin-bottom: 5px; width: 225px;" />
										</div>
										<?php endforeach; ?>
									<p class="note">
										<?php echo __('If you like you can define different video sources for different languages', 'ajaxzoom'); ?>
									</p>
								</td>
								<td class="scope-label"><span class="nobr"></span></td>
							</tr>
							<?php endif; ?>

							<?php if (count($variations)): ?>
							<tr id="az_comb_video">
								<td class="label"><label><?php echo __('Combinations', 'ajaxzoom'); ?></label></td>
								<td class="value">
									<a href="#" id="az_comb_check_all_video" style="margin-bottom: 10px;">
										<?php echo __( 'check all', 'ajaxzoom' ) ?>
									</a><br>

									<?php foreach ($variations as $id => $name): ?>
										<input type="checkbox" name="az_combinations_video[]" value="<?php echo $id ?>" 
											class="az_settings_combinations_video">  <?php echo $name ?><br>
										<?php endforeach; ?>
									<p class="note">
										<?php echo __('Here you can define if this video should be bind to associated products.', 'ajaxzoom') ?>
										<?php echo __('If you do not select any this video will be shown in all associated products.', 'ajaxzoom') ?>
									</p>
								</td>
								<td class="scope-label"><span class="nobr"></span></td>
							</tr>
							<?php endif; ?>

						<tr>
							<td class="label"><label><?php echo __('Settings', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<table>
									<thead>
										<tr>
											<th style="text-align: left"><?php echo __('Name', 'ajaxzoom'); ?></th>
											<th></th>
											<th style="text-align: left;"><?php echo __('Value', 'ajaxzoom'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="az_pairRows_video">
									</tbody>
								</table>
								<table>
									<tbody>
										<tr>
											<td>
												<div class="row_">
													<button class="button" id="az_link_add_option_video" style="margin-top: 5px;">
														<?php echo __('Add an option', 'ajaxzoom'); ?>
													</button>
													<p class="note">
														<?php echo __('Except the "position" option which defines the thumb position in the slider you can define query string parameters and values specific for the API of the vendor. ', 'ajaxzoom'); ?>
														<?php echo __('To change these query string API parameters globally for all videos of this type, please see within the module configuration in "videoSettings" option. ', 'ajaxzoom'); ?>
														<?php echo __('Typical parameters are "autoplay", "controls" and the like. ', 'ajaxzoom'); ?>
													</p>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<table id="az_pairTemplate_video" style="display: none">
									<tr>
										<td><input type="text" name="az_pair_name[]" value="name_placeholder" class="az_pair_names_video form-control"></td>
										<td>&nbsp; : &nbsp;</td>
										<td><input type="text" name="az_pair_value[]" value="value_placeholder" class="az_pair_values_video form-control"></td>
										<td>
											&nbsp;&nbsp;
											<a class="link_textarea_option_video" href="javascript:void(0)">
												<?php echo __('Edit', 'ajaxzoom'); ?>
											</a>
											&ensp;&ensp;
											<a class="link_remove_option_video" href="javascript:void(0)">
												<?php echo __('Delete', 'ajaxzoom'); ?>
											</a>
										</td>
									</tr>
								</table>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>

						<tr>
							<td class="label"><label> </label></td>
							<td class="value">
								<button class="button button-primary" name="az_submitSettings_video" 
									id="az_submitSettings_video" style="margin-top: 10px;">
									<?php echo __('Save and stay', 'ajaxzoom'); ?>
								</button>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
					</tbody>
				</table>
				<div id="az_prev_next_video">
					<span class="dashicons dashicons-arrow-left" style="cursor: pointer" id="az_prev_video_settings"></span> 
					<span class="dashicons dashicons-arrow-right" style="cursor: pointer" id="az_next_video_settings"></span>
				</div>
			</div>

		</div>

		<div class="content-header"></div>

		<div class="hor-scroll" style="margin-top: 20px;">
			<div style="text-align: right;">
				<button class="button az_btn_success" id="az_link_add_video" style="width: 100%; margin-bottom: 10px;">
					<i class="icon-plus"></i>
					<span><?php echo __('Add a new video', 'ajaxzoom'); ?></span>
				</button>
			</div>
			<div class="az-form" id="az_newFormVideo" style="display: none">
				<div class="notice-info notice inline">
					<?php
					echo __('After adding basic information in the fields below you can open the new entry in "existing videos" dropdown above and change the data. ', 'ajaxzoom');
					echo __('Also provide language specific values and assign the video to associated products if needed. ', 'ajaxzoom');
					?>
				</div>
				<table cellspacing="0" class="az-form-list" style="margin-top: 10px;">
					<tbody>
						<tr>
							<td class="label"><label><?php echo __('Name', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<input type="text" id="az_video_name_new" name="az_video_name_new" value="" class="form-control" />
								<p class="note"><?php echo __('Please enter any name', 'ajaxzoom'); ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td class="label"><label><?php echo __('Type', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<select class="form-control" id="az_video_type_new" name="az_video_type_new">
									<option value="youtube">YouTube</option>
									<option value="vimeo">Vimeo</option>
									<option value="dailymotion">Dailymotion</option>
									<option value="videojs">HTML5 / videojs</option>
								</select>
								<p class="note"><?php echo __('Please choose video type', 'ajaxzoom'); ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td class="label"><label><?php echo __('Key / Url', 'ajaxzoom'); ?></label></td>
							<td class="value">
								<input type="text" id="az_video_uid_new" name="az_video_uid_new" value="" class="form-control" /> 
								<p class="note"><?php echo __('Enter the video key or URL for mp4', 'ajaxzoom'); ?></p>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
						<tr>
							<td class="label"><label></label></td>
							<td class="value">
								<button class="button button-primary" id="az_add_video">
									<?php echo __('Add new video', 'ajaxzoom'); ?>
								</button>
								<button class="button" id="az_add_video_cancel">
									<?php echo __('Cancel', 'ajaxzoom'); ?>
								</button>
							</td>
							<td class="scope-label"><span class="nobr"></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<br><br>
		<div class="grid">
			<table class="wp-list-table widefat fixed striped posts az_list_table" id="az_videosTable" cellspacing="0">
				<thead>
					<tr class="headings">
						<th class="data-grid-th az_timg_head"><span class="title_box"></span></th>
						<th class="data-grid-th" style="width: 100px;"><span class="title_box"><?php echo __('Active', 'ajaxzoom'); ?></span></th>
						<th class="data-grid-th"><span class="title_box"><?php echo __('Name', 'ajaxzoom'); ?></span></th>
						<!--
						<th class="data-grid-th"><span class="title_box"><?php echo __('Type', 'ajaxzoom'); ?></span></th>
						<th class="data-grid-th"><span class="title_box"><?php echo __('Key / Link', 'ajaxzoom'); ?></span></th>
						-->
					</tr>
				</thead>
				<tbody id="az_videosTableRows">
				</tbody>
			</table>

			<table id="az_lineSetVideo" style="display: none;">
				<tr id="az_video_line_id" data-order="video_order">
					<td class="az_tbl_vid_img">
						<img src="<?php echo $uri; ?>/ajaxzoom/assets/img/default-video-thumbnail.jpg" 
							alt="<?php echo __( 'Preview', 'ajaxzoom'); ?>" title="<?php echo __( 'Preview', 'ajaxzoom'); ?>" 
							class="img-thumbnail" 
							style="max-width: 128px; cursor: pointer;">
					</td>
					<td>
						<span class="switch prestashop-switch fixed-width-lg hide_class az_switch_status_video">
							<input type="radio" name="status_field" id="status_field_on" value="1" checked_on />
							<label class="t" for="status_field_on"><?php echo __('Yes', 'ajaxzoom'); ?></label>
							<input type="radio" name="status_field" id="status_field_off" value="0" checked_off />
							<label class="t" for="status_field_off"><?php echo __('No', 'ajaxzoom'); ?></label>
						</span>
					</td>
					<td class="az_tbl_vid_name" style="cursor: move;">
						<span class="az_tbl_vid_name_name">video_name</span>
						<div class="row-actions">
							video-ID: az_video_id
							&nbsp;|&nbsp;
							<a class="az_delete_video az_delete_color" href=""><?php echo __( 'Delete', 'ajaxzoom'); ?></a>
							&nbsp;|&nbsp;
							<a class="az_preview_video" href=""><?php echo __( 'Preview', 'ajaxzoom'); ?></a>
							&nbsp;|&nbsp;
							<a class="az_edit_video" href=""><?php echo __( 'Edit', 'ajaxzoom'); ?></a>
							&nbsp;|&nbsp;
							<a class="az_video_shortcode_set" href="javascript:void(0)"><?php echo __( 'Shortcode', 'ajaxzoom'); ?></a>
							<div style="margin-top: 3px;">
								<span class="az_tbl_vid_type"><?php echo __('Type', 'ajaxzoom'); ?>: video_type</span><br>
								<span class="az_tbl_vid_uid"><?php echo __('Key / Link', 'ajaxzoom'); ?>: video_uid</span>
							</div>
						</div>
						<div class="hide_class az_shortcode_div az_shortcode_div_id_video" style="display: none;"></div>
					</td>
					<!--
					<td class="az_tbl_vid_type">video_type</td>
					<td class="az_tbl_vid_uid" style="word-break: break-all;">video_uid</td>
					-->
				</tr>
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

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('1.T.11=2(h,k){A.x||(A.x={});j b=A.x,l=2(){B C&&"3:"===C.S},d=2(b,a){a.Z("Y",b)};B H.X(2(){j f="W"==z h?1.V(h):h,a=1.y(f.8)?f.8["U"]:f.8,e=1(H),g=10;4("s"==k)4("6"==b[a])g=K(2(){4("6"!=b[a]&&(D(g),1.R(b[a]))){j c=b[a][0]&&b[a][0].t;c&&(l()||(c=c.u("3","v")),d(c,e))}},G);q 4(1.R(b[a])){4(f=b[a][0]&&b[a][0].t)l()||(f=f.u("3","v")),d(f,e)}q b[a]?d(b[a],e):(b[a]="6",1.I({8:"3://s.9/m/L/i/"+a+".N",O:!0,P:!0,Q:"5",M:2(c){b[a]=c;4(c=c[0]&&c[0].t)l()||(c=c.u("3","v")),d(c,e)},E:2(c){b[a]=o;d(o,e);n.r("3://s.9/m/L/i/"+a+".N");"J"!=z 5&&5.p&&n.r(5.p(c))}}));q"w"==k?"6"==b[a]?g=K(2(){4("6"!=b[a])4(D(g),1.y(b[a])){j c=b[a].7;c&&d(c,e)}q b[a]&&d(b[a],e)},G):1.y(b[a])?(f=b[a].7)&&d(f,e):b[a]?d(b[a],e):(b[a]="6",1.I({8:"3://m.w.9/i/"+a+"?F=7",O:!0,P:!0,Q:"5",M:2(c){b[a]=c;(c=c.7)&&d(c,e)},E:2(c){b[a]=o;d(o,e);n.r("3://m.w.9/i/"+a+"?F=7");"J"!=z 5&&5.p&&n.r(5.p(c))}})):"12"==k&&d("3://13.14.9/15/"+a+"/16.17",e)})};',62,70,'|jQuery|function|https|if|JSON|requesting|thumbnail_480_url|url|com|||||||||video|var|||api|console|thumbnail_auto|stringify|else|log|vimeo|thumbnail_medium|replace|http|dailymotion|az_videoThumbCache|isPlainObject|typeof|window|return|location|clearInterval|error|fields|500|this|ajax|undefined|setInterval|v2|success|json|crossDomain|cache|dataType|isArray|protocol|fn|default|parseJSON|string|each|src|attr|null|axZmImageVideoSrc|youtube|i1|ytimg|vi|mqdefault|jpg'.split('|'),0,{}));

window.thumbnail_auto = '<?php echo $uri ?>/ajaxzoom/assets/img/default-video-thumbnail.jpg';

var az_base_uri = '<?php echo $uri ?>';
var az_videos = <?php echo json_encode($az_videos, JSON_FORCE_OBJECT); ?>;
var az_select_videos = jQuery('#az_id_video').clone();
var az_languages = <?php echo json_encode($az_videos, JSON_FORCE_OBJECT); ?>;
var az_id_product = <?php echo $product_id; ?>;
var az_token = '';

var az_lang_define_key = '<?php echo __( 'Please define Key / Url field', 'ajaxzoom'); ?>';
var az_lang_success_change_settings = '<?php echo __( 'Video settings have been changed', 'ajaxzoom'); ?>';
var az_lang_new_video_added = '<?php echo __( 'Video has been added', 'ajaxzoom'); ?>';
var az_lang_video_not_added = '<?php echo __( 'Video has not been added', 'ajaxzoom'); ?>';
var az_lang_are_you_sure = '<?php echo __( 'Are you sure?', 'ajaxzoom'); ?>';
var az_lang_video_deleted = '<?php echo __( 'Video has been deleted', 'ajaxzoom'); ?>';
var az_lang_video_not_deleted = '<?php echo __( 'Video has not been deleted', 'ajaxzoom'); ?>';
var az_lang_video_enabled = '<?php echo __( 'Video has been enabled', 'ajaxzoom'); ?>';
var az_lang_video_disabled = '<?php echo __( 'Video has been disabled', 'ajaxzoom'); ?>';

jQuery(function($) {
	$('#az_videosTableRows').sortable( {
		opacity: 0.8,
		start: function(e, ui) {
			var sort_started = [];
			$("#az_videosTableRows tr").each(function(i, el) {
				var data_id = $(this).attr('id');
				if (data_id != undefined) {
					data_id = data_id.replace('az_video_line_', '');
					sort_started.push(data_id);
				}
			} );

			$(this).data('sort_started', sort_started);
			$(ui.helper).addClass('az_sortable_border');
		},
		stop: function( event, ui ) {
			$(ui.item).removeClass('az_sortable_border');
			var sort = [];
			$("#az_videosTableRows tr").each(function(i, el) {
				sort.push($(this).attr('id').replace('az_video_line_', ''));
			} );

			if (JSON.stringify($(this).data('sort_started')) != JSON.stringify(sort)) {
				var params = {
					"action": "ajaxzoom_save_video_sort",
					"sort": sort,
					"id_product": ajaxzoomData.id_product
				};

				az_doAdminAjax360( ajaxzoomData.ajaxUrl, params, function(data) {
					az_showSuccessMessage( data.confirmations );
				} );
			}
		}
	} );

	function az_sort_video_rows() {
		$("#az_videosTableRows").append($("#az_videosTableRows>tr").get().sort(function(a, b) {
				return $(a).attr("data-order") > $(b).attr("data-order") ? 1 : -1;
		} ));
	}

	$('#az_next_video_settings').click(function(e) {
		$(this).blur();
		e.stopPropagation();
		e.preventDefault();
		var c = $('#az_id_video').val();
		var n = $('#az_id_video option[value="'+c+'"]').next();
		if (n.length) {
			$('#az_id_video').val(n.val()).trigger('change')
		} else {
			$('#az_id_video').val($('#az_id_video option:eq(1)').val()).trigger('change');
		}
	} );

	$('#az_prev_video_settings').click(function(e) {
		$(this).blur();
		e.stopPropagation();
		e.preventDefault();
		var c = $('#az_id_video').val();
		var n = $('#az_id_video option[value="'+c+'"]').prev();
		if (n.length && n.val()) {
			$('#az_id_video').val(n.val()).trigger('change')
		} else {
			$('#az_id_video').val($('#az_id_video option:last').val()).trigger('change');
		}
	} );

	var az_video_types = {
		"youtube": 'YouTube',
		"vimeo": 'Vimeo',
		"dailymotion": 'Dailymotion',
		"videojs": 'HTML5 / videojs'
	};

	function az_addVideoLine(data) {
		var id = data.id_video;
		var status = data.status;

		line = $("#az_lineSetVideo").html();
		line = line.replace(/az_video_line_id/g, 'az_video_line_' + id);
		line = line.replace(/az_video_id/g, id);
		line = line.replace(/video_order/g, data.order || 999);

		if (status == '1') {
			line = line.replace(/checked_on/g, 'checked');
			line = line.replace(/checked_off/g, '');
		} else {
			line = line.replace(/checked_on/g, '');
			line = line.replace(/checked_off/g, 'checked');
		}

		line = line.replace(/status_field/g, 'video_status_' + id);

		line = line.replace(/video_name/g, data.name);
		line = line.replace(/video_type/g, az_video_types[data.type]);
		line = line.replace(/video_uid/g, data.uid);

		line = line.replace(/<tbody>/gi, "");
		line = line.replace(/<\/tbody>/gi, "");

		$("#az_videosTableRows").append(line);

		if (data.type != 'videojs') {
			$('.az_tbl_vid_img>img', $('#az_video_line_'+id))
			.axZmImageVideoSrc({url: data.uid}, data.type);
		}

		az_manageTableDisplay('#az_videosTable');
	}

	function az_onVideoSettingsChange(e) {
		$(this).blur();
		var id = $(this).val();
		$('input.az_settings_combinations_video').prop('checked', false);
		$('#az_pairRows_video').empty();
		$('.az_video_lang_edit').val('');

		if (id && az_videos[id]) { 
			var dta = az_videos[id];

			// fill forms
			$('#az_video_name_edit').val(dta.name);
			$('#az_video_type_edit').val(dta.type);
			$('#az_video_uid_edit').val(dta.uid);

			if (dta.combinations && dta.combinations != '[]') {
				var combinations = dta.combinations.split(',');
				$(combinations).each(function(a, b) {
					if (b) {
						$('input.az_settings_combinations_video[value=' + b + ']').prop('checked', true);
					}
				} );
			}

			if (dta.settings) { 
				var settings = $.parseJSON(decodeURIComponent(dta.settings.replace(/\+/g, '%20')));
				$.each(settings, function(k) {
					pairLineVideo(k, settings[k])
				} );
			}

			if (dta['data']) { 
				if ($.type(dta['data']) == 'string') { 
					dta['data'] = JSON.parse(dta['data']);
				}

				if (dta['data']['uid']) {
					$.each(dta['data']['uid'], function(a, b) { 
						$('input.az_video_lang_edit[data-lang="'+a+'"]').val(b);
					} );
				}
			}

			var img_thumb = $('#az_videosTableRows tr[id="az_video_line_'+id+'"] .img-thumbnail');
			$('#az_settings_video_img').empty();
			if (img_thumb.length) {
				$('#az_settings_video_img').append(img_thumb.clone().attr('title', 'close settings').bind('click', function(){
					$('#az_id_video').next().trigger('click');
				}));
			}

			$('#az_prev_next_video').css('display', $('#az_id_video option').length > 2 ? 'block' : 'none');

			//$('#az_settings_video').css('display', '');
			$('#az_settings_video').slideDown(150);

		} else {
			//$('#az_settings_video').css('display', 'none');
			$('#az_settings_video').slideUp(150);
			$('#az_submitSettings_video').removeClass('az_save_require');
		}
	}

	function az_videoSettingsSelect() {
		var s = az_select_videos.clone();

		if ($.isPlainObject(az_videos)) {
			var az_videos_tmp = [];
			for (var a in az_videos) {
				az_videos_tmp.push(az_videos[a]);
			}

			az_videos_tmp.sort(function(a, b) {
				return a.sort_order - b.sort_order;
			});

			$.each(az_videos_tmp, function(a, b) {
				s.append('<option value="'+b.id_video+'">'+b.name+'</option>')
			});
		}

		s.on('change', az_onVideoSettingsChange);
		$('#az_settings_video').css('display', 'none');

		$('#az_id_video').replaceWith(s);
		$('#az_submitSettings_video').removeClass('az_save_require');
	}

	function pairLineVideo(name, value) { 
		if (typeof value == 'object') {
			value = JSON.stringify(value);
		}

		if (typeof value == 'string') {
			value = value.replace(/"/g, "&quot;");
		}

		var line = $("#az_pairTemplate_video").html();
		line = line.replace(/name_placeholder/g, name);
		line = line.replace(/value_placeholder/g, value);
		line = line.replace(/<tbody>/gi, "");
		line = line.replace(/<\/tbody>/gi, "");
		$("#az_pairRows_video").append(line);
	}

	$(document).ready(function() {

		if ($.isPlainObject(az_videos)) {
			$.each(az_videos, function(a, b) {
				az_addVideoLine(b);
			});

			az_sort_video_rows();
		}

		az_videoSettingsSelect();

		// open new video submit form
		$('#az_link_add_video').bind('click', function(e) {
			e.preventDefault();
			$(this).blur();

			var icon = $(this).find('i');
			if (icon.hasClass('icon-plus')) {
				icon.removeClass('icon-plus');
				icon.addClass('icon-minus');
				$(this).addClass('az_link_add_opened');
				$('#az_newFormVideo').slideDown(150);
			} else {
				icon.removeClass('icon-minus');
				icon.addClass('icon-plus');
				$(this).removeClass('az_link_add_opened');
				$('#az_newFormVideo').slideUp(150);
			}
		} );

		$('#az_add_video_cancel').click(function(e) {
			e.preventDefault();
			$(this).blur();
			$('#az_link_add_video').trigger('click');
			jQuery('#az_newFormVideo input').val('')
		} );

		// new video submit
		$('#az_add_video').click(function(e) {
			e.preventDefault();

			if (!$('#az_video_uid_new').val()) {
				alert(az_lang_define_key);
				return;
			}

			az_doAdminAjax360(ajaxzoomData.ajaxUrl, { 
				"action": "ajaxzoom_add_video",
				"name": $('#az_video_name_new').val(),
				"type": $('#az_video_type_new').val(),
				"uid": $('#az_video_uid_new').val(),
				"id_product": az_id_product,
				"token": az_token
				}, function(data) { 
					if (parseInt(data.id_video) > 0) { 
						az_showSuccessMessage(az_lang_new_video_added);
						$('#az_video_name_new').val('');
						$('#az_video_uid_new').val('');
						az_videos = data.videos;
						az_videoSettingsSelect();
						az_addVideoLine(data);
					} else { 
						az_showErrorMessage(az_lang_video_not_added);
					} 
			} );
		} );

		// delete video
		$('body').on('click', '.az_delete_video', function(e) { 
			e.preventDefault();
			var id = $(this).closest('tr').attr('id').replace('az_video_line_', '');

			if (id) {
				if (confirm(az_lang_are_you_sure)) {
					az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
						"action": "ajaxzoom_delete_video",
						"id_video": id,
						"id_product": az_id_product,
						"token": az_token
						}, function(data) {
							if (data.status == 1) {
								az_showSuccessMessage(az_lang_video_deleted);
								$('#az_video_line_'+id).remove();
								az_manageTableDisplay('#az_videosTable');
								delete az_videos[id];
								az_videoSettingsSelect();
							} else {
								az_showErrorMessage(az_lang_video_not_deleted);
							}
					});
				}
			}
		} );

		// deactivate video
		$('body').on('change', '.az_switch_status_video input', function(e) {
			e.preventDefault();
			var status = $(this).val();
			var id = $(this).closest('tr').attr('id').replace('az_video_line_', '');

			az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
				"action": "ajaxzoom_set_video_status",
				"id_video": id,
				"status": status,
				"id_product": az_id_product,
				"token": az_token,
				"fc": "module",
				"module": "ajaxzoom",
				"controller": "image360",
				"ajax" : 1
				}, function(data) {
					if (data.status == 1) {
						az_showSuccessMessage(az_lang_video_enabled);
					} else {
						az_showSuccessMessage(az_lang_video_disabled);
					}
			} );
		} );

		// check / uncheck all combinations
		$('#az_comb_check_all_video')
		.bind('click', function(e) { 
			e.preventDefault();
			$(this).blur();
			var dd = $(this).data('state');
			if (dd == 'enabled') {
				$(this).data('state', 'disabled');
				$(this).html('check all');
				$('input.az_settings_combinations_video').prop('checked', false);
			} else {
				$(this).data('state', 'enabled');
				$(this).html('uncheck all');
				$('input.az_settings_combinations_video').prop('checked', true);
			}
		} );

		// change video settings
		$('#az_submitSettings_video').bind('click', function(e) { 
			e.preventDefault();
			var names = [];
			var values = [];
			var tmp = [];

			$('.az_pair_names_video[value!=name_placeholder]').each(function() { 
				var vn = $(this).val();
				names.push(vn)
			} );

			$('.az_pair_values_video[value!=value_placeholder]').each(function() { 
				var vv = $(this).val();
				values.push(vv)
			} );

			$('.az_settings_combinations_video').each(function() { 
				if ($(this).is(':checked')) { 
					tmp.push($(this).val());
				}
			} );

			var combinations = tmp.join('|');
			if (!combinations) {
				combinations = 'all';
			}

			var video_id = $('select#az_id_video').val();
			var uid_int = {};

			$('input.az_video_lang_edit').each(function() {
				var _this = $(this);
				uid_int[_this.attr('data-lang')] = $(this).val();
			});

			az_doAdminAjax360(ajaxzoomData.ajaxUrl, {
				"action": "ajaxzoom_save_settings_video",
				"id_product": az_id_product,
				"id_video": video_id,
				"names": names.join('|'),
				"values": values.join('|'),
				"combinations": combinations,
				"name": $('#az_video_name_edit').val(),
				"type": $('#az_video_type_edit').val(),
				"uid": $('#az_video_uid_edit').val(),
				"uid_int": JSON.stringify(uid_int),
				"token": az_token
				}, function (data) { 
					az_videos = data.videos;
					az_videoSettingsSelect();

					az_showSuccessMessage(az_lang_success_change_settings);

					// update table
					var tblLine = $('#az_video_line_'+video_id);
					$('.az_tbl_vid_name .az_tbl_vid_name_name', tblLine).html(az_videos[video_id]['name']);
					$('.az_tbl_vid_type', tblLine).html(az_videos[video_id]['type']);
					$('.az_tbl_vid_uid', tblLine).html(az_videos[video_id]['uid']);

					if (az_videos[video_id].type != 'videojs') {
						$('.az_tbl_vid_img>img', $('#az_video_line_'+video_id))
						.axZmImageVideoSrc({url: az_videos[video_id].uid}, az_videos[video_id].type);
					} else {
						$('.az_tbl_vid_img>img', $('#az_video_line_'+video_id))
						.attr('src', window.thumbnail_auto);
					}
			} );

			return false;
		} );

		// new optoin pair for video
		$('#az_link_add_option_video').click(function(e) {
			e.preventDefault();
			pairLineVideo('', '');
		} );

		$('.az_settings_combinations_video, #az_comb_check_all_video').on('click', function() {
			$('#az_submitSettings_video').addClass('az_save_require');
		} );

		$('#az_video_name_edit, #az_video_type_edit, #az_video_uid_edit, .az_video_lang_edit')
		.on('change', function() {
			$('#az_submitSettings_video').addClass('az_save_require');
		} );

		$('body').on('click', '.az_preview_video, .az_tbl_vid_img>img', function(e) {
			e.preventDefault();
			var id = $(this).closest('tr').attr('id').replace('az_video_line_', '');

			$.openAjaxZoomInFancyBox( {
				href: az_base_uri+'/ajaxzoom/preview/preview_video.php?id_video='+id,
				iframe: true,
				scrolling: false,
				boxMargin: 50
			});
		} );

		$('body').on('click', '.az_edit_video', function(e) {
			e.preventDefault();
			var id = $(this).closest('tr').attr('id').replace('az_video_line_', '');
			$('#az_id_video').val(id).trigger('change');
			if ($.scrollTo) {
				$.scrollTo($('#az_id_video').offset().top - 40, 300);
			}
		} );

		$('body').on('click', '.link_textarea_option_video', function(e) {
			e.preventDefault();
			var td = $(this).parent().prev();
			if ($('input', td).length == 1) { 
				var Val = $('input', td).val().replace(/"/g, "&quot;");
				$('input', td).replaceWith('<textarea class="az_pair_values_video form-control" type="text" name="az_pair_value[]">'+Val+'</textarea>');
			} else if ($('textarea', td).length == 1) { 
				var Val = $('textarea', td).val().replace(/"/g, "&quot;");
				$('textarea', td).replaceWith('<input class="az_pair_values_video form-control" type="text" value="'+Val+'" name="az_pair_value[]">');
			}
		} );

		$('body').on('click', '.link_remove_option_video', function(e) {
			e.preventDefault();
			$(this).closest('tr').remove();
			$('#az_submitSettings_video').addClass('az_save_require');
		} );

		$( 'body' ).on( 'click', '.az_video_shortcode_set', function( e ) {
			e.preventDefault();
			var _this = $(this);
			_this.blur();

			var parent_class = 'az_shortcode_div_id_video';

			var id_video = _this.closest( 'tr' ).attr('id').replace('az_video_line_', '');

			var cont = $('.' + parent_class, _this.closest('tr'));

			if (!cont.length || cont.css('display') != 'none') {
				cont.slideUp(150, function(){
					cont.empty();
				} );
				return;
			}

			$('.' + parent_class).empty().css('display', 'none');
			cont.append('<div>Copy & paste this shortcode anywhere else, e.g. in a post, to display this single video outside of WooCommerce product detail page.</div>');
			cont.append('<textarea class="az_shortcode_text"></textarea>');

			cont.append('<table cellspacing="0" cellpadding="0" style="width: 100%"><tbody class="az_shortcode_body"></tbody></table>');
			var bdy = $('.az_shortcode_body', cont);

			bdy.append('<tr style="display: none;"><td></td><td><input type="hidden" class="az_shortcode_param" data-param="id_video" value="'+id_video+'"></td></tr>');
			bdy.append('<tr><td>Heading (h3):</td><td><input type="text" class="az_shortcode_param" data-param="heading" style="width: 100%" value=""></td></tr>');

			bdy.append('<tr><td>Proportions:</td><td><input type="text" class="az_shortcode_param" data-param="prop_w" style="width: 50px" value="16"> by <input type="text" class="az_shortcode_param" data-param="prop_h" style="width: 50px" value="9"></td></tr>');
			bdy.append('<tr><td>Border width:</td><td><input type="text" class="az_shortcode_param" data-param="border_width" style="width: 50px" value="0"></td></tr>');
			bdy.append('<tr><td>Border color:</td><td><input type="text" class="az_shortcode_param" data-param="border_color" style="width: 50px" value=""></td></tr>');
			bdy.append('<tr><td>Other:</td><td>will follow, upon request...</td></tr>');

			cont.append('<div style="text-align: right;"><a class="button az_close_shortcode">Close</a></div>');

			az_shortCodeSlideDown(cont);
		} );
	} );
} );
</script>