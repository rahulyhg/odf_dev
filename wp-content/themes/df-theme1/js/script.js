$(document).ready(function(){
	$('.search-btn').on("click",function(){
		$('.search-div').show();
		$('.search-btn').hide();
	});

	$('.model1').each(function() {
        var cw = $(this).width();
        var mw = cw / 2;
        // console.log(cw);
        // console.log(mw);
        $(this).css({
        'height': mw + 'px'
        });
    });

    $('.model4').each(function() {
        var cw = $(this).width();
        var mw = cw / 2;
        // console.log(cw);
        // console.log(mw);
        $(this).css({
        'height': mw + 'px'
        });
    });

    $('.model2').each(function() {
        var cw = $(this).width();
        var mw = cw / 2;
        // console.log(cw);
        // console.log(mw);
        $(this).css({
        'height': cw + 'px !important'
        });
    });

    $('.model3').each(function() {
        var cw = $(this).width();
        var mw = cw / 2;
        // console.log(cw);
        // console.log(mw);
        $(this).css({
        'height': cw + 'px !important'
        });
    });

    $(".vc_col-sm-6 .model2 .latest_post h2 a").each(function( index ) {

        var l =$(this).text().length;
        // console.log("index : "+l);
        if( l > 30 ){
            $(this).css('font-size','10px');
        }

    });
    $(".vc_col-sm-6 .model3 .latest_post h2 a").each(function( index ) {

        var l =$(this).text().length;
        // console.log("index : "+l);
        if( l > 30 ){
            $(this).css('font-size','10px');
        }

    });
});
