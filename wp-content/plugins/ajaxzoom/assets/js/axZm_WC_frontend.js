/*!
Plugin Name: AJAX-ZOOM
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
Description: Combination of responsive mouseover zoom, 360 degree player with deep zoom, thumbnail slider and 360 degree "Product Tour" for WooCommerce product details view.
Author: AJAX-ZOOM
Version: 1.1.10
Author URI: http://www.ajax-zoom.com/
*/

jQuery(function($) {
	if (!$.axZm_psh) {
		$.axZm_psh = {} ;
	}

	var lock_image_update = 0;
	var parseUrl = function(url) {
		var a = document.createElement('a');
		a.href = url;
		return a;
	};

	var addImgHotspots = function(o) {
		var hs = $.axZm_psh.IMAGES_HOTSPOTS;
		if ($.isPlainObject(hs) && !$.isEmptyObject(hs) && $.isPlainObject(o)) {
			$.each(o, function(k, v) {
				if (hs[k]) {
					o[k]['hotspotFilePath'] = $.parseJSON(hs[k]);
				}
			} );
		}

		return o;
	};

	$.axZm_psh.addVideos = function(id) {
		var r = {};
		var vs = $.axZm_psh.VIDEOS_JSON;
		if ($.isPlainObject(vs) && !$.isEmptyObject(vs)) {
			$.each(vs, function(k, v) {
				if ( $.isEmptyObject(v.combinations) || (id && v.combinations.indexOf(id+'') != -1) ) {
					r[k] = v;
				}
			} );
		}

		return r;
	};

	$.axZm_psh.place_az_layer = function() {
		if ($($.axZm_psh.appendToContainer).length) {
			if ($.axZm_psh.appendToContainer == '.images') {
				$($.axZm_psh.appendToContainer+':eq(0)').removeAttr('class').addClass('images').addClass('azfix_clearfix');
				$('#axZm_mouseOverWithGalleryContainer').prependTo('.images:eq(0)').css('display', 'block');
			} else {
				$($.axZm_psh.appendToContainer+':eq(0)').addClass('azfix_clearfix').css('height', 'auto');
				$('#axZm_mouseOverWithGalleryContainer').prependTo($.axZm_psh.appendToContainer+':eq(0)').css('display', 'block');
			}

			if ($.axZm_psh.appendToContCss) {
				try {
					$($.axZm_psh.appendToContainer+':eq(0)').css($.parseJSON($.axZm_psh.appendToContCss));
				} catch(er) {
					console.log('AJAX-ZOOM: appendToContCss option "'+$.axZm_psh.appendToContCss+'" is not valid JSON.');
				}
			}
		} else {
			console.log('AJAX-ZOOM: container selector "'+$.axZm_psh.appendToContainer+'" not present in this theme.');
		}
	};

	$.axZm_psh.start_mouseOverZoomInit = function() {
		var initParam = $.extend(true, {}, $.axZm_psh.initParam);
		initParam.images = addImgHotspots(initParam.images);
		$.mouseOverZoomInit( initParam );
	};

	$.axZm_psh.wc_variations_image_update = function(variation) {
		$.axZm_psh.variation_triggered = true;
		if (lock_image_update) {
			return;
		} else {
			lock_image_update = 1;
			setTimeout(function() {
				lock_image_update = 0;
			}, 10);
		}

		if ($.axZm_psh.initTO) {
			clearTimeout($.axZm_psh.initTO);
			$.axZm_psh.initTO = null;
		}

		var variation_id = false;
		if (variation && variation.variation_id) {
			variation_id = variation.variation_id;
		}

		if (variation && variation.variation_id && $.axZm_psh.ajaxzoom_variations_wpml_map) {
			if ($.axZm_psh.ajaxzoom_variations_wpml_map[variation.variation_id + '']) {
				variation.variation_id = $.axZm_psh.ajaxzoom_variations_wpml_map[variation.variation_id + ''];
			}
		}

		var new_images = $.parseJSON(window.ajaxzoom_imagesJSON);
		var new_360_images = $.parseJSON(window.ajaxzoom_images360JSON);
		var new_videos = $.axZm_psh.addVideos(variation_id);

		// Remove default images
		if ($.axZm_psh.showDefaultForVariation == false && variation) {
			new_images = {};
		}

		// 360
		if (variation && $.axZm_psh.ajaxzoom_variations_360[variation.variation_id]) {
			new_360_images = $.axZm_psh.ajaxzoom_variations_360[variation.variation_id];
		} else if (variation && $.axZm_psh.showDefaultForVariation360) {
			new_360_images = $.parseJSON( window.ajaxzoom_images360JSON );
		} else if (!variation && $.axZm_psh.show360noVariationSelected) {
			new_360_images = $.parseJSON( window.ajaxzoom_images360JSON );
		} else {
			new_360_images = {};
		}

		// Additional AJAX-ZOOM variation images
		if (variation && $.isPlainObject($.axZm_psh.ajaxzoom_variations_2d) && $.axZm_psh.ajaxzoom_variations_2d[variation.variation_id]) {
			$.extend( new_images, $.axZm_psh.ajaxzoom_variations_2d[variation.variation_id] );
		}

		if ($.toJSON(new_images) != $.toJSON($.axZm_psh.IMAGES_JSON_current)
			|| !$.axZm_psh.start_load
			|| $.toJSON(new_360_images) != $.toJSON($.axZm_psh.IMAGES_360_JSON_current)
			|| $.toJSON(new_videos) != $.toJSON($.axZm_psh.VIDEOS_current)
			|| ( $.isEmptyObject(new_images)
				&& $.isEmptyObject(new_360_images)
				&& $.isEmptyObject(new_videos)
			)
		) {

			var to_load_first = true;

			if ($.axZm_psh.start_load) {
				to_load_first = false;
			}

			$.axZm_psh.start_load = 1;

			setTimeout(function() {
				$.axZm_psh.IMAGES_JSON_current = $.extend(true, {}, new_images);
				$.axZm_psh.IMAGES_360_JSON_current = $.extend(true, {}, new_360_images);
				$.axZm_psh.VIDEOS_current = $.extend(true, {}, new_videos);

				if (to_load_first) {
					$.axZm_psh.initParam.images = new_images;
					$.axZm_psh.initParam.images360 = new_360_images;
					$.axZm_psh.initParam.videos = new_videos;
					$.axZm_psh.start_mouseOverZoomInit();
				} else {
					new_images = addImgHotspots(new_images);
					$.mouseOverZoomInit.replaceImages( {
						divID: $.axZm_psh.divID,
						galleryDivID: $.axZm_psh.galleryDivID,
						images: new_images,
						images360: new_360_images,
						videos: new_videos
					} );
				}

			}, 0);
		}
	};

} );
