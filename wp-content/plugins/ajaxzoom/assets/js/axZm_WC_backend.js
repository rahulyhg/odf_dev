/*!
Plugin Name: AJAX-ZOOM
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
Description: Combination of responsive mouseover zoom, 360 degree player with deep zoom, thumbnail slider and 360 degree "Product Tour" for WooCommerce product details view.
Author: AJAX-ZOOM
Version: 1.1.14
Author URI: http://www.ajax-zoom.com/
*/

function az_getParameterByName(name, url)
{
	if (!url) {
		url = window.location.href;
	}

	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	results = regex.exec(url);
	if (!results) {
		return null;
	}

	if (!results[2]) {
		return '';
	}

	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function az_doAdminAjax360(url, data, success_func, error_func)
{
	az_addLoadingLayer();
	jQuery.ajax( {
		url: url,
		data: data,
		type: 'POST',
		success: function( data ) {
			az_removeLoadingLayer();

			if ( success_func ) {
				return success_func( data );
			}

			//data = jQuery.parseJSON( data );
			if ( data.confirmations && data.confirmations.length != 0 ) {
				az_showSuccessMessage( data.confirmations );
			} else {
				az_showErrorMessage( data.error );
			}

			// hook delete supercache
			if (window.az_id_product) {
				// todo
			}
		},
		error: function( data ) {
			az_removeLoadingLayer();

			if ( error_func ) {
				return error_func( data );
			}

			alert( "[TECHNICAL ERROR]" );
		}
	} );
}

function az_addLoadingLayer(s, c)
{
	var ell = 'body';
	if (s && jQuery(s).length) {
		ell = jQuery(s);
	}

	jQuery('<div />')
	.addClass('az_loading')
	.addClass('az_loading_layer az_loading_layer_cssloader')
	.attr('id', 'az_ajax_loading_layer')
	.css(c || {})
	.appendTo(ell);
}

function az_removeLoadingLayer(clb)
{
	if (jQuery('#az_ajax_loading_layer').length) {
		jQuery('#az_ajax_loading_layer')
		.fadeOut(50, function() {
			jQuery(this).remove();
			if (jQuery.isFunction(clb)) {
				clb();
			}
		} );
	} else if (jQuery.isFunction(clb)) {
		clb();
	}
}

function az_addBodyMessageContainer()
{
	if (!jQuery('body>#az_body_message_container').length) {
		jQuery('<div />').attr('id', 'az_body_message_container').appendTo('body');
	}
}

function az_showSuccessMessage(message, to, err)
{
	if (jQuery.isArray(message) && message[0]) {
		message = message[0];
	}

	if (typeof message == 'string') {
		az_addBodyMessageContainer();

		var msgDiv = jQuery('<div />')
		.addClass(err ? 'az_error_message' : 'az_success_message')
		.html(message)
		.appendTo('#az_body_message_container');

		setTimeout(function() {
			msgDiv
			.fadeOut(200, function() {
				jQuery(this).remove();
			} );
		}, (to ? parseInt(to) : 2500));
	}
}

function az_showErrorMessage(message, to)
{
	az_showSuccessMessage(message, to, 1);
}

function az_setShortCodeText(cont)
{
	var txtArea = jQuery('.az_shortcode_text', cont);
	var str = '[ajaxzoom_woo_media';
	jQuery('.az_shortcode_param', cont).each(function() {
		var _this = jQuery(this);
		var v = '';
		var el_type = _this[0].type || _this[0].tagName.toLowerCase();

		if (el_type == 'text' || el_type == 'hidden') {
			v = _this.val();
		} else if (el_type == 'checkbox' && _this.is(':checked')) {
			v = _this.val();
		}

		if (v) {
			var param = jQuery(this).attr('data-param');
			v = v.replace(/"(.*?)"/g, '«$1»');
			v = v.replace(/\"/g, '');
			if (param && v) {
				str += ' ' + param + '="' + v + '"';
				if (window.az_id_product && param == 'id_image') {
					str += ' pid="' + window.az_id_product + '"';
				}
			}
		}
	} );
	str += ']';
	//txtArea.val(str);
	txtArea[0].innerHTML = str;
}

function az_shortCodeSlideDown(cont)
{
	cont.slideDown(150, function() {
		az_setShortCodeText(cont);

		jQuery('.az_close_shortcode', cont).on('click', function(e) {
			e.preventDefault();
			jQuery(this).blur();
			cont.slideUp(150, function() {
				cont.empty();
			} );
		} );

		jQuery('.az_shortcode_param', cont).on('change', function() {
			az_setShortCodeText(cont);
		} );

		jQuery('.az_shortcode_text', cont).on("focus", function() {
			jQuery(this).select();
		} );
	} );
}

function az_manageTableDisplay(sel, tbody)
{
	var t = jQuery(sel);
	var tbc = jQuery.trim(jQuery('tbody', t).html());
	if (tbc == '' || tbc === undefined) {
		t.addClass('az_list_table');
	} else {
		t.removeClass('az_list_table');
	}
}

function az_versionCompare(b, c, a)
{
	function d(a){
	return(e?/^\d+[A-Za-z]*$/:/^\d+$/).test(a)}var e=a&&a.lexicographical;a=a&&a.zeroExtend;
	b=b.split(".");c=c.split(".");if(!b.every(d)||!c.every(d))return NaN;
	if(a){for(;b.length<c.length;)b.push("0");
	for(;c.length<b.length;)c.push("0")}e||(b=b.map(Number),c=c.map(Number));
	for(a=0;a<b.length;++a){if(c.length==a)return 1;
	if(b[a]!=c[a])return b[a]>c[a]?1:-1}
	return b.length!=c.length?-1:0
}

function az_resetSettingsAjax(e, admin_ajax_url)
{
	e.preventDefault();

	az_addLoadingLayer();

	jQuery.ajax( {
		url: admin_ajax_url,
		data: {
			action: 'ajaxzoom_reset_options'
		},
		type: 'POST',
		success: function( data ) {
			az_removeLoadingLayer();
			alert(data.n+' options from '+data.nn+' have been reset.');
		},
		error: function( data ) {
			az_removeLoadingLayer();
			alert( "[TECHNICAL ERROR]" );
		}
	} );
}

function az_openBatchTool(e, url)
{
	e.preventDefault();
	jQuery.openAjaxZoomInFancyBox( {
		'href': url + '?batch_start=1',
		'iframe': true,
		'boxMargin': 50
	} );
}

function az_deleteHtaccessFiles(e, admin_ajax_url)
{
	e.preventDefault();
	az_addLoadingLayer();

	jQuery.ajax( {
		url: admin_ajax_url,
		data: {
			action: 'ajaxzoom_del_htaccess'
		},
		type: 'POST',
		success: function(data) {
			az_removeLoadingLayer();
			if (data.error == 1) {
				alert('You are missing the permissions to install plugins to proceed with this operation.');
			} else {
				alert(data.n + ' .htaccess files were found and ' + data.nn + ' successfully deleted!');
			}
		},
		error: function(data) {
			az_removeLoadingLayer();
			alert( "[TECHNICAL ERROR]" );
		}
	} );
}

function az_updateCoreAjax(e, admin_ajax_url)
{
	e.preventDefault();
	az_addLoadingLayer();

	var dta_installed;
	var dta_avail;

	jQuery.ajax( {
		url: admin_ajax_url,
		data: {
			action: 'ajaxzoom_get_az_version'
		},
		type: 'POST',
		success: function(data) {
			dta_installed = data;
			if (typeof dta_installed == "string") {
				dta_installed = JSON.parse(dta_installed);
			}

			if (dta_installed.version) {
				var info_installed = "Version: "+dta_installed.version+"<br>";
				info_installed += "Date: "+dta_installed.date+"<br>";
				info_installed += "Review: "+dta_installed.review+"<br>";

				jQuery("#az_updateTdInstalled").html(info_installed);
				jQuery("#az_updateTable").slideDown(300);

				jQuery.ajax( {
					url: admin_ajax_url,
					data: {
						action: 'ajaxzoom_get_az_avail_version'
					},
					type: 'POST',
					success: function(data) {
						az_removeLoadingLayer();
						dta_avail = data;
						if (typeof dta_avail == "string") {
							dta_avail = JSON.parse(dta_avail);
						}

						var info_avail = "Version: "+dta_avail.version+"<br>";
						info_avail += "Date: "+dta_avail.date+"<br>";
						info_avail += "Review: "+dta_avail.review+"<br>";

						jQuery("#az_updateTdAvail").html(info_avail);

						if (dta_installed.version != dta_avail.version) {
							var msg_install = "";
							msg_install = "<p class=\"note\" style=\"width: auto\">";
							msg_install += "In order the AJAX-ZOOM plugin for WooCommerce to work it requires that the core ";
							msg_install += "AJAX-ZOOM files are present in /wp-content/plugins/ajaxzoom/axZm folder. When you update the module, ";
							msg_install += "the core files do not get updated instantly! You can download the latest AJAX-ZOOM core files ";
							msg_install += "(without examples and test images) from https://www.ajax-zoom.com/index.php?cid=download ";
							msg_install += "and unzip the axZm folder (containing in this download) into /wp-content/plugins/ajaxzoom/axZm, ";
							msg_install += "previously renaming the old axZm folder to e.g. axZm_old or other name in order to backup it. ";
							msg_install += "This is how you could do it manually over FTP...";
							msg_install += "<br><br>";
							msg_install += "Since AJAX-ZOOM plugin for WooCommerce version 1.1.0 you can update these core files by ";
							msg_install += "pressing on the button above. Before updating, make sure that you or your developer did not make ";
							msg_install += "any substantial changes to AJAX-ZOOM core files, which is mostly bad practice. ";
							msg_install += "Anyway, while updating, the currently installed AJAX-ZOOM core version, located in /wp-content/plugins/ajaxzoom/axZm, ";
							msg_install += "will be backed up into /wp-content/plugins/ajaxzoom/backups/axZm_[version]_[timestamp].zip file, so in case ";
							msg_install += "the result after updating causes any issues you can always restore the previous version manually. ";
							msg_install += "Before restoring please make sure that an eventual problem is not caused by template and ";
							msg_install += "js / css files caching - refresh if needed!";
							msg_install += "</p>";

							var txt_notes = "";
							txt_notes += "<button class=\"button-primary\" type=\"button\" style= \"margin-top: 10px\" onsubmit=\"return false;\" ";
							txt_notes += "id=\"axzoom_updateazcore\" style=\"width: 100%;\">";
							txt_notes += "Perform update to version "+dta_avail.version+"</button>" + msg_install;
							txt_notes += "<div class=\"content-header\" style=\"margin-top: 20px\">";
							txt_notes += "<h3>Release notes</h3></div>";
							txt_notes += "<table class=\"wp-list-table widefat striped\"><tbody>";
							jQuery.each(dta_avail.notes, function(v, t) {
								if (az_versionCompare(dta_installed.version, v) == -1) {
									txt_notes += "<tr><td style=\"vertical-align: top; width: 100px;\">"+v;
									txt_notes += "</td><td style=\"vertical-align: top;\"><ul><li>"+t.join('</li><li>');
									txt_notes += "</li><ul></td></tr>";
								}
							} );

							txt_notes += "</tbody></table>";
							jQuery("#az_updateTdAvail").append(txt_notes);

							jQuery("#axzoom_updateazcore")
							.unbind()
							.bind("click", function(e) {
								var _this = jQuery(this);
								_this.blur();
								az_addLoadingLayer();

								var new_html = "<div id=\"az_update_status\" style=\"margin-top: 5px;\"><div class=\"notice-info notice inline\">";
								new_html += "Performing update, please wait. This can last a while...</div></div>";

								_this.addClass('disabled').prop('disabled', true);
								jQuery(new_html).insertAfter(_this);
								jQuery("#axzoom_updateaz").addClass("disabled");

								jQuery.ajax( {
									url: admin_ajax_url,
									data: {
										action: 'ajaxzoom_download_axzm'
									},
									type: 'POST',
									success: function(data) {
										az_removeLoadingLayer();
										if (typeof data == "string") {
											data = JSON.parse(dta_avail);
										}

										jQuery("#axzoom_updateaz").removeClass("disabled");

										if (data.success) {
											/*
											var location_reload_to = setTimeout(function() {
												jQuery("#axzoom_updateaz").trigger("click");
											}, 100);
											*/
											jQuery('#axzoom_updateaz').trigger("click");
										} else {
											jQuery("#az_update_status").html(
												"<div class=\"notice-info notice inline\">Update failed</div>"
											);
										}
									},
									error: function(data) {
										az_removeLoadingLayer();
										jQuery("#axzoom_updateaz").removeClass("disabled");
										jQuery("#az_update_status").html(
											"<div class=\"notice-error notice inline\">Technical error</div>"
										);
									}
								} );
							} );
						} else {
							var txt_notes = "<br><span style=\"color: #3d6611; font-weight: bold;\">";
							txt_notes += "You have latest AJAX-ZOOM core version installed.";
							txt_notes += "</span>";
							jQuery("#az_updateTdAvail").append(txt_notes);
						}
					},
					error: function(data) {
						az_removeLoadingLayer();
						alert("[TECHNICAL ERROR]");
					}
				} );
			} else {
				az_removeLoadingLayer();
				jQuery('#az_updateTable').show();

				var err_notes = '<div class="notice-error notice inline">';
				err_notes += 'Technical error or axZm folder does not not exist. ';
				err_notes += 'Please download the latest AJAX-ZOOM version manually from ';
				err_notes += 'https://www.ajax-zoom.com/index.php?cid=download ';
				err_notes += ', unzip and upload the axZm folder with files to /wp-content/plugins/ajaxzoom/';
				err_notes += '</div>';

				jQuery('#az_updateTdInstalled').html(err_notes);
			}
		},
		error: function(data) {
			az_removeLoadingLayer();
			alert("[TECHNICAL ERROR]");
		}
	} );
}

function az_dismiss_notice(a)
{
	var ref = jQuery(a);
	var dismiss = "<button type=\"button\" class=\"notice-dismiss\"></button>";

	jQuery(".notice-dismiss", ref).remove();
	ref.append(dismiss);

	jQuery(".notice-dismiss", ref)
	.off()
	.on("click", function(e) {
		jQuery(this).blur();
		e.preventDefault();
		ref.slideUp(300, function() {
			ref.empty().css("display", "none");
		} );
	} );
}

function az_backupCoreAjax(e, admin_ajax_url)
{
	e.preventDefault();
	var _this = jQuery(this);

	_this.blur().addClass("disabled").prop("disabled", true);
	az_addLoadingLayer();

	jQuery.ajax( {
		url: admin_ajax_url,
		data: {
			"action": "ajaxzoom_backup_axzm"
		},
		type: 'POST',
		success: function(data) {
			az_removeLoadingLayer();
			_this.removeClass("disabled").prop("disabled", false);
			var msg = "";
			if (!data.errors.length) {
				box_class = "notice notice-success is-dismissible";
				msg += "<h3>Backup results: OK!</h3>";
				msg += "The backup was created in: " + data.zip_filename;
				msg += "<br>Filesize: " + data.filesize;
			} else {
				box_class = "notice notice-error is-dismissible";
				msg += "<h3>Backup results: some errors</h3>";
				msg += "Sorry, but there are few things which went wrong while trying to make a backup of the AJAX-ZOOM core files. ";
				msg += "Maybe the following hints will help you to resolve the problem: ";
				msg += "<ul><li>";
				msg += data.errors.join("</li><li>");
				msg += "</li></ul>";
			}

			jQuery("#az_axzm_backup_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);
			az_dismiss_notice("#az_axzm_backup_results");
		},
		error: function(data) {
			az_removeLoadingLayer();
			_this.removeClass("disabled").prop("disabled", false);
			box_class = "notice-error is-dismissible";
			var msg = "Ajax error occured while the backup request. AJAX-ZOOM core files are not backed up.";

			jQuery("#az_axzm_backup_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);
			az_dismiss_notice("#az_axzm_backup_results");
		}
	} );
}

function az_backupPlugin(e, admin_ajax_url)
{
	e.preventDefault();
	e.stopPropagation();

	var _this = jQuery(this);
	_this.blur().addClass("disabled").prop("disabled", true);

	az_doAdminAjax360( 
		admin_ajax_url,
		{"action": "ajaxzoom_backup_plugin"},
		function(data) {
			_this.removeClass("disabled").prop("disabled", false);
			var box_class;
			var msg = "";
			if (!data.errors.length) {
				box_class = "notice-success is-dismissible";
				msg += "<h3>Backup results: OK!</h3>";
				msg += "The backup was created in: " + data.zip_filename;
				msg += "<br>Filesize: " + data.filesize;
			} else {
				box_class = "notice-error is-dismissible";
				msg += "<h3>Backup results: some errors</h3>";
				msg += "Sorry, but there are few things which went wrong while trying to make a backup of the plugin. ";
				msg += "Maybe the following hints will help you to resolve the problem: ";
				msg += "<ul><li>";
				msg += data.errors.join("</li><li>");
				msg += "</li></ul>";
			}

			jQuery("#az_backup_woo_plugin_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);
			az_dismiss_notice("#az_backup_woo_plugin_results");
		},
		function(data) {
			_this.removeClass("disabled").prop("disabled", false);
			box_class = "notice-error is-dismissible";
			var msg = "Ajax error occured while the update request. Plugin not updated.";

			jQuery("#az_backup_woo_plugin_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);
			az_dismiss_notice("#az_backup_woo_plugin_results");
		}
	);
}

function az_updatePlugin(e, admin_ajax_url)
{
	e.preventDefault();
	e.stopPropagation();

	var _this = jQuery(this);
	_this.blur().addClass("disabled").prop("disabled", true);

	az_doAdminAjax360( 
		admin_ajax_url,
		{"action": "ajaxzoom_update_plugin"},
		function(data) {
			var box_class;
			var msg = "";
			window.az_to_reload = null;
			if (!data.errors.length && !data.files_err.length) {
				box_class = "notice-success";
				window.az_to_reload = setTimeout(function() {
					location.reload();
				}, 10000);
				msg += "<h3>Update results: OK!</h3>"
				msg += "<p>";
				msg += "Seems that no errors occured while updating the files. ";
				msg += "This page will be reloaded in a 10 seconds ";
				msg += "<a href=\"javascript:void(0)\" id=\"az_update_abort_reload\">(abort)</a>.";
				msg += "</p>";

			} else {
				_this.removeClass("disabled").prop("disabled", false);
				box_class = "notice-error";
				msg += "<h3>Update results: some errors </h3>"
				msg += "Sorry, but there are few things which went wrong while trying to update the plugin files. ";
				msg += "Maybe the following hints will help you to resolve the problem. ";
				if (data.errors.length) {
					msg += "<h4>Errors:</h4>";
					msg += "<ul><li>";
					msg += data.errors.join("</li><li>");
					msg += "</li></ul>";
				}

				if (data.files_err.length) {
					msg += "<h4>Following files or folders could not be overwritten by PHP:</h4>";
					msg += "<p>Most likely owner / chmod problem. Happens when you have previously uploaded the plugin over FTP.</p>";
					msg += "<ul><li>";
					msg += data.files_err.join("</li><li>");
					msg += "</li></ul>";
				}
			}

			jQuery("#az_update_woo_plugin_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);

			jQuery("#az_update_abort_reload").on("click", function(e) {
				jQuery(this).blur();
				e.preventDefault();
				if (window.az_to_reload) {
					clearTimeout(window.az_to_reload);
					window.az_to_reload = null;
				}
			} );
		},
		function(data) {
			_this.removeClass("disabled").prop("disabled", false);
			box_class = "notice-error";
			var msg = "Ajax error occured while the update request. Plugin not updated.";

			jQuery("#az_update_woo_plugin_results")
			.removeAttr("class")
			.addClass("notice " + box_class)
			.html(msg).slideDown(300);
		} 
	);
}

jQuery(document).ready(function($) {

	if (az_getParameterByName('page') == 'wc-settings' && az_getParameterByName('tab') == 'ajaxzoom') {
		jQuery('table.form-table').addClass('az_form_table');

		var cnt = jQuery('ul.subsubsub')
		.addClass('az-settings-subtabs')
		.css({'visibility': 'visible', 'height': 'auto', 'opacity': 0});

		var htm = cnt.html();
		htm = htm.replace(/\|/g, '');
		cnt.html(htm);

		jQuery('li', cnt).addClass('nav-tab');

		if (!az_getParameterByName('section')) {
			jQuery('li:eq(0)>a', cnt).addClass('current').parent().addClass('nav-tab-active');
		} else {
			jQuery('a.current', cnt).parent().addClass('nav-tab-active');
		}

		if (az_getParameterByName('section') == 'reset') {
			jQuery( '.woocommerce-save-button' ).remove(); 
		}

		cnt.fadeTo(150, 1);

		jQuery('label', jQuery('.ajaxzoom-useful').parent().parent()).addClass('az-ec971f').prepend('<i class="az-icon-gear" style="margin-right: 5px;"></i>');
		jQuery('label', jQuery('.ajaxzoom-important').parent().parent()).addClass('az-c9302c').prepend('<i class="az-icon-hand" style="margin-right: 5px;"></i>');
	} else if (az_getParameterByName('post') && az_getParameterByName('action') == 'edit') {
		jQuery('body').on('keydown', '.az_container input[type="text"]', function(e) {
			if (event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		} );
	}
} );
