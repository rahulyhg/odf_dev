<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage ODF-theme-1
 * @since ODF-theme-1 1.0
 */

?>
<?php global $test_theme; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" href="<?php echo get_option( 'favicon_url' ) ?>" />
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

	<style type="text/css">
		:root {
	    	--odf-display-botton-buy: <?php 
	    		if(get_option( 'enable_button_buy' ) == "on") {
		    		echo 'block;';
		    	}else{
		    		echo 'none;';
		    	}
	    	 ?>; 
	    	--odf-display-rating: <?php 
	    		if(get_option( 'enable_rating' ) == "on") {
		    		echo 'block;';
		    	}else{
		    		echo 'none;';
		    	}
	    	 ?>;

	    	--header-color-theme-1: <?php
	    							if(!empty(get_option('header_color_theme_1'))){
	    								echo get_option('header_color_theme_1');
	    							}else{
	    								update_option( 'header_color_theme_1', '#7100e2' );
	    								echo '#7100e2';
	    							}
	    							?>;

			--header-color-multilang-gradient-left-theme-1: <?php
	    							if(!empty(get_option('header_color_multilang_gradient_left_theme_1'))){
	    								echo get_option('header_color_multilang_gradient_left_theme_1');
	    							}else{
	    								update_option( 'header_color_multilang_gradient_left_theme_1', '#7100e2' );
	    								echo '#7100e2';
	    							}
	    							?>;

			--header-color-multilang-gradient-right-theme-1: <?php
	    							if(!empty(get_option('header_color_multilang_gradient_right_theme_1'))){
	    								echo get_option('header_color_multilang_gradient_right_theme_1');
	    							}else{
	    								update_option( 'header_color_multilang_gradient_right_theme_1', '#964ae2' );
	    								echo '#d09994';
	    							}
	    							?>;

			--product-slider-left-side-color-theme-1: <?php
	    							if(!empty(get_option('product_slider_left_side_color_theme_1'))){
	    								echo get_option('product_slider_left_side_color_theme_1');
	    							}else{
	    								update_option( 'product_slider_left_side_color_theme_1', '#028cd0 ' );
	    								echo '#028cd0 ';
	    							}
	    							?>;

			--product-slider-right-side-color-gradient-in-theme-1: <?php
	    							if(!empty(get_option('product_slider_right_side_color_gradient_in_theme_1'))){
	    								echo get_option('product_slider_right_side_color_gradient_in_theme_1');
	    							}else{
	    								update_option( 'product_slider_right_side_color_gradient_in_theme_1', '#000000' );
	    								echo '#fff';
	    							}
	    							?>;

			--product-slider-right-side-color-gradient-out-theme-1: <?php
	    							if(!empty(get_option('product_slider_right_side_color_gradient_out_theme_1'))){
	    								echo get_option('product_slider_right_side_color_gradient_out_theme_1');
	    							}else{
	    								update_option( 'product_slider_right_side_color_gradient_out_theme_1', '#ffffff' );
	    								echo '#000';
	    							}
	    							?>;
	    
	    }
		
		/************ loader **********/
		#preloader {
		    position: fixed;
		    top: 0;
		    left: 0;
		    width: 100%;
		    height: 100%;
			z-index: 9999999999999;			
		    background: #ffffff;
		    opacity: 0.96;
		}
		#loader {
		    display: block;
		    position: relative;
		    left: 50%;
		    top: 50%;
		    width: 150px;
		    height: 150px;
		    margin: -75px 0 0 -75px;
		    border-radius: 50%;
		    border: 3px solid transparent;
		    border-top-color: #9370DB;
		    -webkit-animation: spin 2s linear infinite;
		    animation: spin 2s linear infinite;
		}
		#loader:before {
		    content: "";
		    position: absolute;
		    top: 5px;
		    left: 5px;
		    right: 5px;
		    bottom: 5px;
		    border-radius: 50%;
		    border: 3px solid transparent;
		    border-top-color: #BA55D3;
		    -webkit-animation: spin 3s linear infinite;
		    animation: spin 3s linear infinite;
		}
		#loader:after {
		    content: "";
		    position: absolute;
		    top: 15px;
		    left: 15px;
		    right: 15px;
		    bottom: 15px;
		    border-radius: 50%;
		    border: 3px solid transparent;
		    border-top-color: #FF00FF;
		    -webkit-animation: spin 1.5s linear infinite;
		    animation: spin 1.5s linear infinite;
		}
		@-webkit-keyframes spin {
		    0%   {
		        -webkit-transform: rotate(0deg);
		        -ms-transform: rotate(0deg);
		        transform: rotate(0deg);
		    }
		    100% {
		        -webkit-transform: rotate(360deg);
		        -ms-transform: rotate(360deg);
		        transform: rotate(360deg);
		    }
		}
		@keyframes spin {
		    0%   {
		        -webkit-transform: rotate(0deg);
		        -ms-transform: rotate(0deg);
		        transform: rotate(0deg);
		    }
		    100% {
		        -webkit-transform: rotate(360deg);
		        -ms-transform: rotate(360deg);
		        transform: rotate(360deg);
		    }
		}
		/**************************************************************************/

	</style>
	
	<?php wp_head(); ?>

	<?php //if(file_exists ( "/wp-content/plugins/js_composer/assets/css/js_composer.min.css" )){ ?>
		<link rel="stylesheet" id="js_composer_front-css" href="<?php echo get_site_url(); ?>/wp-content/plugins/js_composer/assets/css/js_composer.min.css?ver=5.6" type="text/css" media="all">
	<?php //} ?>
	
	<?php echo wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css' ); ?>
	<?php echo wp_enqueue_style( 'custom2', get_template_directory_uri() . '/css/custom_2.css' ); ?>

</head>


<body <?php body_class(); ?>>

<div id="preloader" style="display: block">
  <div id="loader"></div>
</div>
<script>
	document.getElementById('preloader').style.display = 'block';
</script>


<div id="page" class="site">
		
	<div class="site-inner">
		<div class="block_header">

			<?php if( !empty(get_option( 'logo_url' ))){ ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo_mobile_id">
					<img src="<?php echo get_option( 'logo_url' ); ?>" />
				</a>
			<?php }else{ ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo_mobile_id">
					<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/img-logo.png" />
				</a>
			<?php } ?>

			<hr class="hr_costom_header">
			<div class="site-branding">
				<?php twentysixteen_the_custom_logo(); ?>

				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php if( !empty(get_option( 'logo_url' ))){ ?>
							<img src="<?php echo get_option( 'logo_url' ); ?>" />
						<?php }else{ ?>
							<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/img-logo.png" />
						<?php } ?>
					</a></p>
				<?php endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->
			<div class="vc_row vc_row_header">
				<div class="float_right">
					<?php 
						$applicationsh  = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'application' and post_status = 'publish'", OBJECT );
						foreach($applicationsh as $key => $applicationh){
							if(get_field("show_hide", $applicationh->ID) == "yes"){
								?>
								<a class="header_appli text-center" href="<?php echo get_field( 'appli_url', $applicationh->ID ) ?>" target="_blanc">
									<img src="<?php echo get_field( "icon_header", $applicationh->ID ); ?>" width="20">
									<span><?php echo $applicationh->post_title; ?></span>
								</a>
								<?php
							}
						}
					?>

					<?php if(get_option( 'mailto_check' )=="on") {?>
						<a href="<?php echo get_option( 'mailto_url' ) ?>" target="_blank" class="header_social text-center">
							<i class="fa fa-edit"></i>
						</a>
					<?php } ?>
					<?php if(get_option( 'youtube_check' )=="on") {?>
						<a href="<?php echo get_option( 'youtube_url' ) ?>" target="_blank" class="header_social text-center">
							<i class="fa fa-youtube-play"></i>
						</a>
					<?php } ?>
					<?php if(get_option( 'facebook_check' )=="on") {?>
						<a href="<?php echo get_option( 'facebook_url' ) ?>" target="_blank" class="header_social text-center">
							<i class="fa fa-facebook"></i>
						</a>
					<?php } ?>
					<?php if(get_option( 'instagram_check' )=="on") {?>
						<a href="<?php echo get_option( 'instagram_url' ) ?>" target="_blank" class="header_social text-center">
							<i class="fa fa-instagram"></i>
						</a>
					<?php } ?>

					<?php
						if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
							echo '<div class="header-right">';
							echo do_shortcode('[wpml_language_switcher][/wpml_language_switcher]');
							echo'</div>';
						}
					?>

					<a href="javascript:void(0)" id="div_header_mon_compte">
						<i class="fa fa-search" aria-hidden="true"></i>
					</a>
				</div>
				
				<div id="div_searchform" style="display: none;">
					<form role="search" method="get" id="searchform" action="index.php">
						<div>
							<i class="fa fa-times ic_search" id="ic_search_icon" aria-hidden="true" style="display: inline;"></i>
							<input type="text" value="" name="s" id="s" placeholder="Search">
							<input type="submit" id="searchsubmit" value="">
							<!-- <input type="hidden" name="post_type" value="product"> -->
						</div>
					</form>
				</div>
			</div>
		</div>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentysixteen' ); ?></a>

		<header id="masthead" class="site-header" role="banner">
			<div class="site-header-main">

				<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) ) : ?>
					<button id="menu-toggle" class="menu-toggle"><?php _e( 'Menu', 'twentysixteen' ); ?></button>

					<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_class'     => 'primary-menu',
									 ) );
								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>

						<?php if ( has_nav_menu( 'social' ) ) : ?>
							<nav id="social-navigation" class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social Links Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'social',
										'menu_class'     => 'social-links-menu',
										'depth'          => 1,
										'link_before'    => '<span class="screen-reader-text">',
										'link_after'     => '</span>',
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>
					</div><!-- .site-header-menu -->
				<?php endif; ?>
			</div><!-- .site-header-main -->

			<?php if ( get_header_image() ) : ?>
				<?php
					/**
					 * Filter the default twentysixteen custom header sizes attribute.
					 *
					 * @since Twenty Sixteen 1.0
					 *
					 * @param string $custom_header_sizes sizes attribute
					 * for Custom Header. Default '(max-width: 709px) 85vw,
					 * (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px'.
					 */
					$custom_header_sizes = apply_filters( 'twentysixteen_custom_header_sizes', '(max-width: 709px) 85vw, (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px' );
				?>
				<div class="header-image">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id ) ); ?>" sizes="<?php echo esc_attr( $custom_header_sizes ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					</a>
				</div><!-- .header-image -->
			<?php endif; // End header image check. ?>
		</header><!-- .site-header -->

		<div id="content" class="site-content">
			<?php
			if ( function_exists('yoast_breadcrumb') ) {
			  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
			}
			?>
