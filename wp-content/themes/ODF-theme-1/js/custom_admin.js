
var $j = jQuery.noConflict();

$j(document).ready(function() {
    "use strict";

    // console.log('test custom js admin');

    /*
        <div class="upload_button_div">
           <span class="button media_upload_button" id="logo-ontex-media">Upload</span>
           <span class="button remove-image" id="reset_logo-ontex" rel="logo-ontex">Remove</span>
        </div>
    */

    

    /* background configuration via amdin*/
        $j('#logo_url').click(function(e) {
            e.preventDefault();

            var custom_uploader = wp.media({
                title: 'Custom Image',
                button: {
                    text: 'Upload Image'
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $j('#logo_url').attr('value', attachment.url);
                $j('#logo_url_div .hide.screenshot').show();
                $j('#logo_url_div .screenshot').html("<img src='"+attachment.url+"' width='150'/>");
                $j('#logo_url_div .remove-image').show();
            })
            .open();
        });
        if($j('#logo_url').val() != ""){
            $j('#logo_url_div .screenshot').html("<img src='"+$j('#logo_url').val()+"' width='150'/>");
            $j('#logo_url_div .remove-image').show();
            $j('#logo_url_div .hide.screenshot').show();
        }
        $j('#logo_url_div .media_upload_button').click(function(e) {
            $j( "#logo_url" ).trigger( "click" );
        });
        $j('#logo_url_div .remove-image').click(function(e) {
            $j('#logo_url').attr('value', "");
            $j('#logo_url_div .screenshot img').remove();
            $j('#logo_url_div .screenshot').hide();
            $j(this).hide();
        });





        $j('#favicon_url').click(function(e) {
            e.preventDefault();

            var custom_uploader = wp.media({
                title: 'Custom Image',
                button: {
                    text: 'Upload Image'
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $j('#favicon_url').attr('value', attachment.url);
                $j('#favicon_url_div .hide.screenshot').show();
                $j('#favicon_url_div .screenshot').html("<img src='"+attachment.url+"' width='32'/>");
                $j('#favicon_url_div .remove-image').show();
            })
            .open();
        });
        if($j('#favicon_url').val() != ""){
            $j('#favicon_url_div .screenshot').html("<img src='"+$j('#favicon_url').val()+"' width='32'/>");
            $j('#favicon_url_div .remove-image').show();
            $j('#favicon_url_div .hide.screenshot').show();
        }
        $j('#favicon_url_div .media_upload_button').click(function(e) {
            $j( "#favicon_url" ).trigger( "click" );
        });
        $j('#favicon_url_div .remove-image').click(function(e) {
            $j('#favicon_url').attr('value', "");
            $j('#favicon_url_div .screenshot img').remove();
            $j('#favicon_url_div .screenshot').hide();
            $j(this).hide();
        });

    /* background configuration via amdin*/

    $j( '.cpa-color-picker' ).wpColorPicker();


});


