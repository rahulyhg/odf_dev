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

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>

	<?php //if(file_exists ( "/wp-content/plugins/js_composer/assets/css/js_composer.min.css" )){ ?>
		<link rel="stylesheet" id="js_composer_front-css" href="/wp-content/plugins/js_composer/assets/css/js_composer.min.css?ver=5.6" type="text/css" media="all">
	<?php //} ?>
	
	<?php echo wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css' ); ?>
	<?php echo wp_enqueue_style( 'custom2', get_template_directory_uri() . '/css/custom_2.css' ); ?>

</head>

<?php global $test_theme; ?>

<body <?php body_class(); ?>>

<!-- <div id="preloader" style="">
      <div id="loader"></div>
    </div>
<script>
	jQuery(document).ready(function() {
	 	jQuery("#preloader").hide();
	});
</script> -->


<div id="page" class="site">
		
	<div class="site-inner">
		<div class="block_header">
			<hr class="hr_costom_header">
			<div class="site-branding">
				<?php twentysixteen_the_custom_logo(); ?>

				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo $test_theme['logo-ontex']['url']; ?>" /></a></p>
				<?php endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->
			<div class="vc_row vc_row_header">
				<?php 
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					if ( !is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
						echo '<div class="vc_col-sm-2"></div>';
					}
				?>
				<div class="vc_col-sm-4">
					<div class="vc_row">
						<a class="vc_col-sm-4 header_appli" href="https://www.apple.com/fr/ios/app-store/" target="_blanc">
							<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-blue.png">
							<span>IOT</span>
						</a>
						<a class="vc_col-sm-4 header_appli" href="https://play.google.com/store/apps?hl=fr" target="_blanc">
							<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-blue.png">
							<span>Appli 1</span>
						</a>
						<a class="vc_col-sm-4 header_appli" href="https://www.microsoft.com/en-us/store/apps" target="_blanc">
							<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-blue.png">
							<span>Appli 2</span>
						</a>
					</div>							
				</div>
				<div class="vc_col-sm-4 header_social">
					<div class="vc_row">
						<div class="vc_col-sm-3 header_appli">
							<?php if($test_theme['linkedin-check-button']==1) {?>
								<a href="<?php echo $test_theme['linkedin-header-ontex'] ?>" target="_blank">
									<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-mail-move.png">
								</a>
							<?php } ?>
						</div>
						<div class="vc_col-sm-3 header_appli">
							<?php if($test_theme['twitter-check-button']==1) {?>
								<a href="<?php echo $test_theme['twitter-header-ontex'] ?>" target="_blank">
									<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-youtube-move.png">
								</a>
							<?php } ?>
						</div>
						<div class="vc_col-sm-3 header_appli">
							<?php if($test_theme['facebook-check-button']==1) {?>
								<a href="<?php echo $test_theme['facebook-header-ontex'] ?>" target="_blank">
									<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-facebook-move.png">
								</a>
							<?php } ?>
						</div>
						<div class="vc_col-sm-3 header_appli">
							<?php if($test_theme['instagram-check-button']==1) {?>
								<a href="<?php echo $test_theme['instagram-header-ontex'] ?>" target="_blank">
									<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-instagram-move.png">
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
						echo '<div class="vc_col-sm-2 header-right">';
						echo do_shortcode('[wpml_language_switcher][/wpml_language_switcher]');
						echo'</div>';
					}
				?>				
				<div class="vc_col-sm-2 header-right header-search">
					<a href="#" id="div_header_mon_compte">
						<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-search-move.png">
					</a>
				</div>
				
				<div id="div_searchform" style="display: none;">
					<form role="search" method="get" id="searchform" action="/">
						<div>

							<i class="fa fa-times ic_search" aria-hidden="true" style="display: inline;"></i>
							<input type="text" value="" name="s" id="s" placeholder="Search">
							<input type="submit" id="searchsubmit" value="">
							<input type="hidden" name="post_type" value="product">
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
