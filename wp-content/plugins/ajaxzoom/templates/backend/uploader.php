<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="az_filelist_360">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />

<div id="container360_upload">
	<a id="az_pickfiles360" class="button az_btn_success" href="javascript:;"><?php echo __( 'Add Images', 'ajaxzoom' ); ?></a>
	<a class="button az_btn_cancel" href="javascript:;"><?php echo __( 'Cancel', 'ajaxzoom' ); ?></a>
	<a id="az_uploadfiles360" href="javascript:;" style="display: none;">[Upload files]</a>
</div>

<br />
<pre id="console"></pre>

<script type="text/javascript">
var uploader360;
var uploader360Obj = function() {
	return {
		runtimes : 'html5,flash,silverlight,html4',

		browse_button : 'az_pickfiles360',
		container: document.getElementById( 'container360_upload' ),

		url : ajaxzoomData.ajaxUrl,

		filters : {
			max_file_size : '50mb',
			mime_types: [
				{ title : "Image files", extensions : "jpg,gif,png" }
			]
		},

		multipart_params : {
			"action" : "ajaxzoom_upload_image",
			"id_product": ajaxzoomData.id_product,
			"id_360set" : jQuery( '#id_360set' ).val()
		},

		flash_swf_url : '<?php echo includes_url( 'js/plupload/plupload.flash.swf' ); ?>',

		silverlight_xap_url : '<?php echo includes_url( 'js/plupload/plupload.silverlight.xap' ); ?>',

		init: {
			PostInit: function() {
				document.getElementById( 'az_filelist_360' ).innerHTML = '';

				document.getElementById( 'az_uploadfiles360' ).onclick = function() {
					uploader360.start();
					return false;
				};
			},

			FilesAdded: function( up, files ) {
				uploader360.settings.multipart_params.id_360set = jQuery( '#id_360set' ).val();

				document.getElementById( 'az_filelist_360' ).innerHTML = '';
				plupload.each( files, function( file ) {
					document.getElementById( 'az_filelist_360' ).innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize( file.size ) + ') <b></b></div>';
				} );

				az_addLoadingLayer();

				up.start();
			},

			UploadComplete:  function( up, files ) {
				az_removeLoadingLayer();
				document.getElementById( 'az_filelist_360' ).innerHTML = '<span class="az-status"><?php echo __( 'The files has been uploaded successfully', 'ajaxzoom' ); ?></span>';
			},

			FileUploaded: function( up, file, response ) {
				var r = jQuery.parseJSON( response.response );

				// set image
				var tr = jQuery( 'tr[data-group=' + r.id_360 + ']' );
				if( tr.find( 'img[src*=no_image-100x100]' ) ) {
					tr.find( 'img[src*=no_image-100x100]' ).attr( 'src', r.path );
				}

				az_imageLine360( r.id, r.path, r.filename );
			},

			UploadProgress: function( up, file ) {
				document.getElementById( file.id ).getElementsByTagName( 'b' )[0].innerHTML = '<span>' + file.percent + "%</span>";
			},

			Error: function( up, err ) {
				az_removeLoadingLayer();
				document.getElementById( 'console' ).innerHTML += "\nError #" + err.code + ": " + err.message;
			}
		}
	};
};

</script>