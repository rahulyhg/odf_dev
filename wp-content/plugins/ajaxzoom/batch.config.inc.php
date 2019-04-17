<?php

if (!headers_sent() && !session_id()) {
	session_start();
}

$axzm_vendor_hash = md5('axZmBtchVendor'.__FILE__);

if (isset($_SERVER['REQUEST_URI'])
	&& strstr($_SERVER['REQUEST_URI'], 'batch_start=1')
	&& isset($_SESSION[$axzm_vendor_hash])
) {
	// Unset session values when window is opened
	unset($_SESSION[$axzm_vendor_hash]);
}

if (!isset($_SESSION[$axzm_vendor_hash])) {
	require_once __DIR__ . '/../../../wp-config.php';

	if ( !Ajaxzoom::chk_admin() ) {
		die('You are not allowed to access this page without being administrator!');
	}

	$_SESSION[$axzm_vendor_hash] = array(
		'base_uri' => home_url('', 'relative'),
		'conf' => Ajaxzoom::config(),
		'thumb_img_preview_size' => Ajaxzoom::$thumb_img_preview_size
	);
}

if (isset($_SESSION[$axzm_vendor_hash]['base_uri'])) {

	if (!method_exists(new axZmF, 'classVer')) { // axZmF is defined else where
		die('Please update AJAX-ZOOM core files');
	} elseif (version_compare(axZmF::classVer()['ver'], '2.3') < 0) { // axZmF is defined else where
		die('Please update AJAX-ZOOM core files.');
	}

	$axzm_batch_config = array(
		'byPassBatchPass' => true,
		'filesFilter' => array(
			'/.\-\d+x\d+\.[a-z]{3,4}+$/',
			'_thumb',
			'-thumb'
		),
		'foldersFilter' => array(
			'tmp',
			'wc-logs',
			'thumb',
			'woocommerce_uploads',
			'/imported_from_./'
		),
		'arrayMake' => array(
			'In' => true,
			'Th' => false,
			'tC' => true,
			'Ti' => true
		),
		'cmsMode' => true,
		'fluid' => true,
		'afterBatchFolderEndJsClb' => 'parent.window.afterBatchFolderEndJsClb',
		'confirmBatch' => false,
		'presets' => array(
			'config' => array(
				'dropTitle' => 'Home dirs',
				'dropIcon' => 'fa-home',
				'subIcon' => 'fa-genderless'
			),
			'/wp-content/plugins/ajaxzoom/pic' => array(
				'startPic' => str_replace('//', '/', $_SESSION[$axzm_vendor_hash]['base_uri'].'/wp-content/plugins/ajaxzoom/pic/'),
			),
			'/wp-content/uploads' => array(
				'startPic' => str_replace('//', '/', $_SESSION[$axzm_vendor_hash]['base_uri'].'/wp-content/uploads/')
			)
		),
		'picBaseDir' => $_SESSION[$axzm_vendor_hash]['base_uri'].'/wp-content/plugins/ajaxzoom/pic/',
		'example' => axZmF::confPar('images360example', $axzm_vendor_hash),
		'dynImageSizes' => axZmF::getMouseOverDefault($axzm_vendor_hash),
		'enableBatchThumbs' => true,
		'batchThumbsDynString' => $_SESSION[$axzm_vendor_hash]['thumb_img_preview_size'],
		'vendorNote' => array(
			'title' => '
<img src="'.str_replace('//', '/', $_SESSION[$axzm_vendor_hash]['base_uri'].'/wp-content/plugins/ajaxzoom/assets/img/woocommerce.svg').'" style="height: 40px">
<span style="font-weight: bold; color: #000;">Commerce</span> notes',
			'text' => array(
				'In the 360 folder you will find source images for the 360 / 3D images.
	When you proceed this folder, please disable / uncheck the 
	<strong>(tC) dynamic thumbs</strong>, 
	because this AJAX-ZOOM cache type is not needed for 360 / 3D. 
</p>
<p>For regular images switch to the <button class="btn btn-xs" href="javascript:void(0)" 
	onclick="$.zoomBatch.submitStartPicBatch(\''.$_SESSION[$axzm_vendor_hash]['base_uri'].'/wp-content/uploads/'.'\')">WordPress "uploads" folder</button> 
	When proceeding this "uploads" folder / subfolders please check which images do not need to be processed 
	because they will not show in AJAX-ZOOM player. 
	Use <button class="btn btn-xs" onclick="jQuery.zoomBatch.toggleSettingTab(\'batchSettingFilter\');">filters</button>
	to exclude what you do not need to process.
</p>
'
			)
		)
	);

} else {
	die('Please reload...');
}
