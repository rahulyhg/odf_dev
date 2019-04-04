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

	// catalog: duplicat pagination block
	jQuery('.products').prepend(jQuery('.woocommerce-pagination').clone().css('width','100%'));

	jQuery('.wr360_player').bind("DOMSubtreeModified", function(){
		jQuery('[id^="wr360placer_wr360_view01_playerid"]').remove();
	});
	
});

