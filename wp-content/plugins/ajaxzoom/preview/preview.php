<?php
require_once __DIR__ . '/../../../../wp-config.php';

if ( !Ajaxzoom::chk_admin() ) {
	echo 'You are not allowed to access this page without beeing administrator!';
	exit;
}

function get360( $id ) {
	global $wpdb;
	return $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ajaxzoom360` WHERE id_360 = " . intval( $id ), ARRAY_A );
}

function getIssetMod( $par ) {
	return isset( $par );
}

function getValueMod( $par ) {
	return isset( $_GET[$par] ) ? $_GET[$par] : '';
}

$conf = Ajaxzoom::config();

if ( !( getIssetMod( '3dDir' ) || getIssetMod( 'zoomData' ) || getIssetMod( 'zoomDir' ) ) ){
	echo 'No parameters passed';
	exit;
}

$conf360 = get360( getValueMod( 'group' ) );

$example = getValueMod( 'example' );

if ( !getIssetMod( 'example' ) ) {
	if ( $conf['AJAXZOOM_MAIN_IMAGES360PREVIEW'] ) {
		$example = $conf['AJAXZOOM_MAIN_IMAGES360EXAMPLEPREVIEW'];
	} else {
		$example = $conf['AJAXZOOM_MAIN_EXAMPLEFANCYBOXFULLSCREEN'];
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>AJAX-ZOOM Preview</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta name="viewport" content="width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no">

<style type="text/css" media="screen"> 
	html {height: 100%; width: 100%; font-family: Tahoma, Arial; font-size: 10pt; margin: 0; padding: 0;}
	body {height: 100%; width: 100%; overflow: hidden; margin: 0; padding: 0;} 
	body:-webkit-fullscreen {width: 100%; height: 100%;}
	body:-ms-fullscreen {width: 100%; height: 100%;}
	a {color: blue; outline: 0; outline-style: none; text-decoration: none;} a:visited {color: blue;} a:hover {color: green;}
	h2 {padding:0px; margin: 35px 0px 15px 0px; font-size: 22px;}
	h3 {font-family: Arial; color: #1A4A7A; font-size: 18px; padding: 20px 0px 3px 0px; margin: 0;}
	p {text-align: justify; text-justify: newspaper;}
</style>

<script src="../axZm/plugins/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="../axZm/axZm.css" media="all" />
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
</head>
<body>
<div id="azTargetDiv" style="width: 100%; height: 100%;"></div>

<!--  Init AJAX-ZOOM player -->
<script type="text/javascript">

// Create empty jQuery object

var ajaxZoom = {}; 

// Parameters passed query string
var pathParameter = "<?php if ( getIssetMod( '3dDir' ) ){ echo '3dDir=' . getValueMod( '3dDir' ); } elseif ( getIssetMod( 'zoomData' ) ) { echo 'zoomData=' . getValueMod( 'zoomData' ); } elseif ( getIssetMod( 'zoomDir' ) ) { echo 'zoomDir=' . getValueMod( 'zoomDir' ); } ?>";
pathParameter += "<?php if ( getIssetMod( 'zoomFile' ) ){ echo '&zoomFile=' . getValueMod( 'zoomFile' ); } ?>";

var parseJsonTest = function( a ) {
try { return JSON.parse( a ); }
catch( err ) { return a; } }

// Define callbacks, for complete list check the docs
ajaxZoom.opt = {
	onBeforeStart: function() {
		// Set backgrounf color, can also be done in css file
		$('.axZm_zoomContainer').css({backgroundColor: '#FFFFFF'});	
		
		// Do not display exit text
		$.axZm.fullScreenExitText = false;
		
		// Disable left/right buttons
		$.axZm.gallerySlideNavi = false;
		
		var fsSupport = document.documentElement.requestFullscreen || ( document.documentElement.msRequestFullscreen && window == window.top ) || document.documentElement.mozRequestFullScreen || document.documentElement.webkitRequestFullscreen;
		var fullscreenEnabled = document.fullscreenEnabled || ( document.msFullscreenEnabled && window == window.top ) || document.mozFullScreenEnabled || document.webkitFullscreenEnabled;

		// Enable fullscreen button depending on fullscreen support
		$.axZm.fullScreenCornerButton = ( fsSupport && fullscreenEnabled ) ? true : false;
		$.axZm.gallerySlideNaviMargin = 5;
		
		<?php
		// Enable left/right buttons in certain cases
		if ( getIssetMod( 'zoomDir' ) || ( getIssetMod( 'zoomData' ) && count( explode( '|', getValueMod( 'zoomData' ) ) ) > 1 ) ) {
			echo '$.axZm.gallerySlideNavi=true;';
		} else {
			echo '$.axZm.gallerySlideNavi=false;';
		}
		 
		// Enable spinmode of 3dDir is passed!
		if ( getIssetMod( '3dDir' ) ) { 
			echo '$.axZm.spinMode=true; ';
		} else {
			echo '$.axZm.spinMode=false; ';
		}
		
		// Some other parameters which can be passed over query string and set in onBeforeStart callback
		if ( getIssetMod( 'spinBounce' ) ) {
			echo '$.axZm.spinBounce=\'bounce\'; ';
		}
		
		if ( getIssetMod( 'spinReverse') ) {
			echo '$.axZm.spinReverse=true; ';
		} else {
			echo '$.axZm.spinReverse=false; ';
		}
		
		if ( getIssetMod( 'stepZoom' ) ) {
			echo "
				$.axZm.scrollAnm=false; 
				$.axZm.scrollZoom=11; 
				$.axZm.scrollAjax=200; 
				$.axZm.pyrTilesFadeInSpeed=300; 
				$.axZm.pyrTilesFadeLoad=30; 
			";
		}
		
		if ( getIssetMod( 'spinNoInit' ) ) {
			echo "
				$.axZm.spinNoInit.enabled=true;
			";
		}
		?>

		// mNavi hook
		jQuery.axZm.mNavi = {
			enabled: true, 
			gravity: 'bottomLeft', //topLeft, topRight, bottomRight, bottomLeft, bottom, top, right, left
			
			offsetHorz: 5, // horizontal from player edge if parentID is not defined
			offsetVert: 5, // vertical offset from player edge if parentID is not defined
			offsetVertFS: 10, // vertical offset in fullscreen mode
			offsetHorzFS: 10, // horizontal offset in fullscreen mode
			
			parentID: false, // put mNavi in none fullscreen mode outside of the player
			setParentWidth: false, // sets width of the parent container same as navi container
			setParentHeight: false, // sets height of the parent container same as navi container
			fullScreenShow: true, // append mNavi to the player in fullscreen mode; you can also enable fullScreenNaviBar option instead
			
			hover: true, // looks for button like mPan.file + '_over' on mouse over or touch
			down: true, // looks for button like mPan.file + '_down' on mouse over or touch
			
			alignment: 'horz', // horz, vert (if gravity is 'right' or 'left' defaults to 'vert')
			//padding: 0, // container padding (css class .axZm_zoomCustomNavi)
			mouseOver: true, // hides when mouse is not over the player on not touch devices
			firstEllMargin: 0, // margin left for first button in orderDefault / order
			ellementRows: 1, // num raws of ellements, if > 1 alignment defaults to 'horz'
			rowMargin: 5, // if ellementRows > 1 - margin between the rows
			
			containerFixedWidth: false,
			containerFixedHeight: false,
			
			buttonDescr: false, // same behaviour as old navi for buttons description
			
			alt: { // tooltip
				enabled: false,
				timeout: 300,
				fadeIn: 200,
				parentID: false,
				
				gravity: 'bottom', // top, bottom
				offset: 5,
				
				pos: false, // false, topLeft, topRight, bottomRight, bottomLeft, bottom, top, right, left, center
				posMarginX: 10,
				posMarginY: 10,
				
				opacity: 1.0,
				mouseFollow: true
			},

			cssClass: 'zoomCustomNavi', // css class for container
			cssClassFS: 'zoomCustomNaviFS', // css class fullscreen view
			cssClassParentID: 'zoomCustomNaviParentID', // css class if parentID is defined
			
			// Notes: mSpin is instantly removed if not spinMod or 3d; mSpin replaced by m3D when zAchsis is defined
			// orderDefault is completly replaced with order if not empty object
			orderDefault: {mZoomOut: 5, mZoomIn: 15, mReset: 15, mPan: 5, mSpin: 5, mCrop: 0}, // buttonName: distance to next button
			order: {},
			
			customPos: {
				//mReset: {css: {left: 5, top: 5, position: 'absolute', zIndex: 123}, parentID: '', mouseOver: true}
			},
			
			// can be a stringified function if passed as JSON
			mCustomBtn1: function(){jQuery.fn.axZm.fillArea();},
			mCustomBtn2: function(){alert('Hello, I\'m custom button two.')}
		};
		
		<?php
			if (getIssetMod('3dDir')){
				echo 'jQuery.axZm.mNavi.order = {mPan: 5, mSpin: 0};';
			}
			elseif (getIssetMod('zoomDir'))
				echo 'jQuery.axZm.mNavi.order = {mGallery: 5, mReset: 0};';
			elseif (getIssetMod('zoomData')){
				if (count(explode('|', getValueMod('zoomData'))) > 1){
					echo 'jQuery.axZm.mNavi.order = {mGallery: 5, mReset: 0};';
				}else{
					echo 'jQuery.axZm.mNavi.order = {mReset: 0};';
				}
			}
		?>
		
		var moduleSettings = <?php echo $conf360['settings']; ?>;
		$.each(moduleSettings, function(k, v){
			if ($.axZm[k] !== undefined){
				$.axZm[k] = parseJsonTest(v);
			}
		});
		
	}
};


var adjustHeight = function() {
	//var a = (window.innerHeight ? window.innerHeight : $(window).height());
	//$('#azTargetDiv').css('height', a);
	window.scrollTo(0,0); // ios7
};

$(document).ready(function(){
	adjustHeight();
	$(document).bind('resize', adjustHeight);
});

// Define the path to the axZm folder, adjust the path if needed!
ajaxZoom.path = "../axZm/"; 

// Define your custom parameter query string
// example=spinIpad has many presets for 360 images
// 3dDir - best of all absolute path to the folder with 360/3D images
// ajaxZoom.parameter = "example=spinIpad&3dDir=/pic/zoom3d/Uvex_Occhiali"; 
ajaxZoom.parameter = "example=<?php echo $example ? $example : 'mouseOverExtension360'; ?>&"+pathParameter; 

// Init fullscreen
$.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.parameter, ajaxZoom.opt, 'azTargetDiv', true);

</script>

</body>
</html>