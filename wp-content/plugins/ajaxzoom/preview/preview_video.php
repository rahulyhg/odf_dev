<?php
require_once __DIR__ . '/../../../../wp-config.php';

if ( !Ajaxzoom::chk_admin() ) {
	echo 'You are not allowed to access this page without beeing administrator!';
	exit;
}

function getIssetMod( $par ) {
	return isset( $par );
}

function getValueMod( $par ) {
	return isset( $_GET[$par] ) ? $_GET[$par] : '';
}

function getVideo( ) {
	global $wpdb;
	return $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoomvideo` WHERE id_video = " . (int)getValueMod( 'id_video' ), ARRAY_A );
}

$conf = Ajaxzoom::config();

$videojs_src = (array)json_decode($conf['AJAXZOOM_DEFAULTVIDEOVIDEOJSJS'], true);

$video = getVideo();

?>

<!DOCTYPE html>
<html>
<head>
<title>AJAX-ZOOM Preview</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta name="viewport" content="width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no">

<style type="text/css"> 
    html {height: 100%; width: 100%; font-family: Tahoma, Arial; font-size: 10pt; margin: 0; padding: 0;}
    body {height: 100%; width: 100%; overflow: hidden; margin: 0; padding: 0;} 
    body:-webkit-fullscreen {width: 100%; height: 100%;}
    body:-ms-fullscreen {width: 100%; height: 100%;}
    iframe, video {width: 100%; height: 100%;}
</style>

<script src="../axZm/plugins/jquery-1.8.3.min.js"></script>
<?php
if ($video['type'] == 'videojs' && $conf['AJAXZOOM_VIDEOHTML5VIDEOJS'] == 'true') {
    if (isset($videojs_src['css1']) && $videojs_src['css1']) {
        echo('<link href="'.$videojs_src['css1'].'" rel="stylesheet">');
    }

    if (isset($videojs_src['css2']) && $videojs_src['css2']) {
        echo('<link href="'.$videojs_src['css2'].'" rel="stylesheet">');
    }
}
?>

</head>

<body>
<?php switch ($video['type']): ?>
<?php case 'youtube': ?>
<iframe src="https://www.youtube.com/embed/<?php echo($video['uid']); ?>" style="width: 100%; height: 100%;" 
    width="100%" height="100%" frameborder="0" 
    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php break;?>

<?php case 'vimeo': ?>
<iframe src="https://player.vimeo.com/video/<?php echo($video['uid']); ?>?color=ff6944&title=0&byline=0&portrait=0" 
    style="width: 100%; height: 100%;" width="100%" height="100%" frameborder="0" 
    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php break;?>

<?php case 'dailymotion': ?>
<iframe frameborder="0" src="//www.dailymotion.com/embed/video/<?php echo($video['uid']); ?>" 
    allowfullscreen></iframe>
<?php break;?>

<?php case 'videojs': ?>
<video id="my-video" class="video-js" controls preload="auto" width="100%" height="100%" 
    data-setup="{}" style="width: 100%; height: 100%;">

<source src="<?php echo($video['uid']); ?>" 
    type='video/<?php echo(pathinfo($video['uid'], PATHINFO_EXTENSION)); ?>'>
</video>
<?php break;?>
<?php endswitch; ?>

<?php
if ($video['type'] == 'videojs' && $conf['AJAXZOOM_VIDEOHTML5VIDEOJS'] == 'true') {
    if (isset($videojs_src['js1']) && $videojs_src['js1']) {
        echo('<script src="'.$videojs_src['js1'].'"></script>');
    }

    if (isset($videojs_src['js2']) && $videojs_src['js2']) {
        echo('<script src="'.$videojs_src['js2'].'"></script>');
    }

    if (isset($videojs_src['js3']) && $videojs_src['js3']) {
        echo('<script src="'.$videojs_src['js3'].'"></script>');
    }
}
?>

<script type="text/javascript">
window.HELP_IMPROVE_VIDEOJS = false;
</script>
</body>
</html>