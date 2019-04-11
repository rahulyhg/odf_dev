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
	
	// <button class="mgbutton moove-gdpr-infobar-cancel" rel="nofollow">I do not accept</button> 
	/*jQuery('.moove-gdpr-info-bar-content .moove-gdpr-button-holder').append('<button data-href="#moove_gdpr_cookie_modal" class="mgbutton">Manage my cookies</button>');
    jQuery('.moove-gdpr-infobar-cancel').live('click', function(){
    	jQuery('#moove_gdpr_cookie_info_bar').hide();
    });*/
	jQuery(".change-settings-button").appendTo(".moove-gdpr-info-bar-content .moove-gdpr-button-holder");
    jQuery('.change-settings-button').replaceWith(jQuery('<button data-href="#moove_gdpr_cookie_modal" class="mgbutton">' + jQuery('.change-settings-button').text() + '</button>'));
	
});

