<?php
require_once __DIR__ . '/../../../../wp-config.php';

foreach($_GET as $k => $v) {
	$_GET[$k] = preg_replace('/<[^>]*>[^<]*<[^>]*>/', '', $_GET[$k]);
	$_GET[$k] = filter_var($_GET[$k], FILTER_SANITIZE_STRING);
	if (strstr($_GET[$k], ';>') || stristr($_GET[$k], 'base64_encode') || strstr($_GET[$k], 'GLOBALS') || strstr($_GET[$k], '_REQUEST') || strstr($_GET[$k], '\.')) {
		unset($_GET[$k]);
	}
}

$op = $_GET;

function az_display_404() {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	exit();
}

if (Ajaxzoom::is_iframe()) {
	$iframeID = '';
	if (isset($op['id_360'])) {
		$iframeID = 'ajaxzoom_woo_media_id_360_'.$op['id_360'];
	} elseif ($op['id_product']) {
		$iframeID = 'ajaxzoom_woo_media_id_product_'.$op['id_product'];
	} elseif ($op['id_image']) {
		$iframeID = 'ajaxzoom_woo_media_id_image_'.md5($op['id_image']); 
	} elseif ($op['id_video']) {
		$iframeID = 'ajaxzoom_woo_media_id_video_'.$op['id_video'];
	} else {
		az_display_404();
	}
} else {
	az_display_404();
}

if (!isset($op['prop_h']) || $op['prop_h'] == '0') {
	$op['prop_h'] = 1;
}

if (!isset($op['prop_w']) || $op['prop_w'] == '0') {
	$op['prop_w'] = 1;
}

$head_scripts = Ajaxzoom::ajaxzoom_woo_media_scripts();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>AJAX-ZOOM Gallery / 360</title>
<?php
echo $head_scripts['css'];
?>
<style type="text/css">
.axZm_mouseOverAspectRatio:before {
	padding-top: calc(<?php echo number_format(((int)$op['prop_h'] / (int)$op['prop_w']) * 100, 2); ?>% - 30px);
}

#az_inner_body_iframe .axZm_mouseOverZoomContainerWrap {
	<?php if (isset($op['border_width'])) { ?>
	border-width: <?php echo (int)$op['border_width']; ?>px;
	<?php } ?>
	<?php if (isset($op['border_width'])) { ?>
	border-color: <?php echo $op['border_color']; ?>;
	<?php } ?>
}

</style>
<?php
echo $head_scripts['js'];
?>
</head>
<body>

<?php
if (!empty($iframeID)) {
	echo '
<script type="text/javascript">
if (!jQuery.axZm_psh) {
	jQuery.axZm_psh = {};
}

jQuery.axZm_psh.iframeID = "'.$iframeID.'";
jQuery.fn.axZm.setParentFrameID(jQuery.axZm_psh.iframeID);
</script>
';
}
?>
<div id="az_inner_body_iframe" style="position: fixed !important; top: 0 !important; left: 0 !important"></div>
<?php 
Ajaxzoom::display_example_32($op);
?>
</body>
</html>