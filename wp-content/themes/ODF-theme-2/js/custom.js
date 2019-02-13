jQuery(document).ready(function() {
	"use strict";

	jQuery('#div_header_mon_compte').click(function(){
	    jQuery('#div_searchform').show('fade',500);
	    jQuery('.fa-times').show('fade',500);
	    jQuery(this).css('color','#fcb532');

	});

	jQuery('.fa-times').click(function(){
	    jQuery(this).hide();
	    jQuery('#div_searchform').hide();
	    jQuery('#div_header_mon_compte .fa-search').css('color','#ffffff');
	    jQuery('#div_header_mon_compte .fa-search').show('fade',500);
	    jQuery('#searchform .section_ic_search').css('background','none');
	});		

});