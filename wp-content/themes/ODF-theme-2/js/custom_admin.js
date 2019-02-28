
var $j = jQuery.noConflict();




$j(document).ready(function() {
    "use strict";

    console.log('test custom js admin');

    

    

    /* background configuration via amdin*/
        $j('#header_background').click(function(e) {
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
                $j('#header_background').attr('value', attachment.url);
                // $j('.header_logo_url').val(attachment.url);

            })
            .open();
        });

    /* background configuration via amdin*/

    


});


