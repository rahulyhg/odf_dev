<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="az_filelist2d">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />

<div id="container2d">
	<a id="az_pickfiles2d" class="button az_btn_success" href="javascript:;"><?php echo __( 'Add Images', 'ajaxzoom' ); ?></a>
	<a id="az_uploadfiles2d" href="javascript:;" style="display:none;">[Upload files]</a>
</div>

<br />
<pre id="console"></pre>

<script type="text/javascript">

var uploader2d = new plupload.Uploader( {
	runtimes : 'html5,flash,silverlight,html4',

	browse_button : 'az_pickfiles2d',
	container: document.getElementById( 'container2d' ),

	url : ajaxzoomData.ajaxUrl,

	filters : {
		max_file_size : '50mb',
		mime_types: [
			{ title : "Image files", extensions : "jpg,gif,png" }
		]
	},

	multipart_params : {
		"action" : "ajaxzoom_upload_image2d",
		"id_product": ajaxzoomData.id_product
	},

	flash_swf_url : '<?php echo includes_url( 'js/plupload/plupload.flash.swf' ); ?>',

	silverlight_xap_url : '<?php echo includes_url( 'js/plupload/plupload.silverlight.xap' ); ?>',

	init: {
		PostInit: function() {
			document.getElementById( 'az_filelist2d' ).innerHTML = '';

			document.getElementById( 'az_uploadfiles2d' ).onclick = function() {
				uploader2d.start();
				return false;
			};
		},

		FilesAdded: function( up, files ) {
			document.getElementById( 'az_filelist2d' ).innerHTML = '';
			plupload.each( files, function( file ) {
				document.getElementById( 'az_filelist2d' ).innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize( file.size ) + ') <b></b></div>';
			} );

			az_addLoadingLayer();

			up.start();
		},

		UploadComplete:  function( up, files ) {
			az_removeLoadingLayer();
			document.getElementById( 'az_filelist2d' ).innerHTML = '<span class="az-status"><?php echo __( 'The files has been uploaded successfully', 'ajaxzoom' ); ?></span>';
		},

		FileUploaded: function( up, file, response ) {
			var r = jQuery.parseJSON( response.response );
			az_imageLine2d( r.id, r.path , r.image_name, 1);
		},

		UploadProgress: function( up, file ) {
			document.getElementById( file.id ).getElementsByTagName( 'b' )[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function( up, err ) {
			az_removeLoadingLayer();
			document.getElementById( 'console' ).innerHTML += "\nError #" + err.code + ": " + err.message;
		}
	}
});

uploader2d.init();

</script>
