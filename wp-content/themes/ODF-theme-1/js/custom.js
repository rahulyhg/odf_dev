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

});

