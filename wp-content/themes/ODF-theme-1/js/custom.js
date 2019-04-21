jQuery(document).ready(function() {
	"use strict";

	jQuery('#div_header_mon_compte').click(function(){
	    jQuery('#div_searchform').show();
	    jQuery('#ic_search_icon').show();

	});

	jQuery('#ic_search_icon').click(function(){
	    jQuery(this).hide();
	    jQuery('#div_searchform').hide();
	});

	jQuery('.row_grid_advice .vc_gitem-zone-img').attr('src', '');

	setTimeout(function(){ 
		jQuery('.row_grid_advice .vc_gitem-zone-img').attr('src', '');
		jQuery('.row_grid_advice .vc_gitem-zone-img').remove();
	}, 3000);

	// catalog: duplicat pagination block
	jQuery('.products').prepend(jQuery('.woocommerce-pagination').clone().css('width','100%'));

	jQuery('.wr360_player').bind("DOMSubtreeModified", function(){
		jQuery('[id^="wr360placer_wr360_view01_playerid"]').remove();
	});

	/* product 3D animate*/	
	jQuery(".change-settings-button").appendTo(".moove-gdpr-info-bar-content .moove-gdpr-button-holder");
    jQuery('.change-settings-button').replaceWith(jQuery('<button data-href="#moove_gdpr_cookie_modal" class="mgbutton">' + jQuery('.change-settings-button').text() + '</button>'));

	setTimeout(function(){ 

		jQuery('.threesixty-scrollbar').prepend('<span style=" float: right; right: -5%; ">LIQUID FULL</span>');
		jQuery('.threesixty-scrollbar').append('<span style=" float: left; left: -5%; ">LIQUID EMPTY</span>');


		var html_product_animate_images_lenght = jQuery('.threesixty-scrollbar-top .threesixty-images li').size();
		// console.log('html_product_animate_images_lenght', html_product_animate_images_lenght);
		var html_product_animate = '<span class="irs-grid" style="width: 95.549%;left: 2.12552%;">';
		for (var i = 0; i < html_product_animate_images_lenght; i++) {
			if(i > 0){
				html_product_animate +='<span class="irs-grid-pol" style="left: '+(100/html_product_animate_images_lenght)*i+'%"></span>';
				html_product_animate +='<span class="irs-grid-text js-grid-text-'+i+'" style="left: '+(100/html_product_animate_images_lenght)*i+'%; margin-left: -0.817183%;">'+i+'</span>';
				// console.log('i', i);
			}
		}
		html_product_animate +='</span>';
		// console.log('html_product_animate', html_product_animate);
		jQuery('.noUi-target').append(html_product_animate);
	}, 10000);
});

