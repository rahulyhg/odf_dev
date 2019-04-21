<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage ODF-theme-1
 * @since ODF-theme-1 1.0
 */
?>

		</div><!-- .site-content -->
 
	</div><!-- .site-inner -->

			<?php global $test_theme, $wpdb; ?>

			<div class="div_footer">
				<div class="vc_row">
					<div class="vc_col-sm-1">
						<?php if( !empty(get_option( 'logo_url' ))){ ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="">
								<img src="<?php echo get_option( 'logo_url' ); ?>" />
							</a>
						<?php }else{ ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/img-logo.png" width="140" />
							</a>
						<?php } ?>
					</div>
					<div class="vc_col-sm-2 mobile_50">
						<?php dynamic_sidebar( 'footer-custom-column-1' ); ?>
					</div>
					<div class="vc_col-sm-2 mobile_50">
						<?php dynamic_sidebar( 'footer-custom-column-2' ); ?>
					</div>
					<div class="vc_col-sm-2 mobile_50">
						<?php dynamic_sidebar( 'footer-custom-column-3' ); ?>
					</div>
					<div class="vc_col-sm-2 mobile_50">
						<?php dynamic_sidebar( 'footer-custom-column-4' ); ?>
					</div>
					<div class="vc_col-sm-2 text-center">				
						<div class="vc_row">
							<?php 
								$applications  = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'application' and post_status = 'publish'", OBJECT );
								foreach($applications as $key => $application){
									if(get_field("show_hide", $application->ID) == "yes"){
										?>
										<a class="footer_appli" href="<?php echo get_field( 'appli_url', $application->ID ) ?>" target="_blanc">
											<img src="<?php echo get_field( "icon_footer", $application->ID ); ?>" width="20">
											<span><?php echo $application->post_title; ?></span>
										</a>
										<?php
									}
								}
							?>
						</div>			
						<div class="vc_row">
							<div class="vc_col-sm-12">
								<form action="" method="get" accept-charset="utf-8" class="footer_form_search" id="footer_form_search">
									<input class="footer_search" name="s" type="text" placeholder="Search">
									<span class="icon-table-search"></span>
								</form>
								<script>
									jQuery('.icon-table-search').on('click', function(){
										jQuery('#footer_form_search').submit();
									});
									</script>
							</div>
						</div>
					</div>
					<div class="vc_col-sm-1 footer_social">
						<?php if(get_option( 'youtube_check' )=="on") {?>
							<a href="<?php echo get_option( 'youtube_url' ) ?>" target="_blank">
								<i class="fa fa-youtube-play"></i>
							</a>
						<?php } ?>
						<?php if(get_option( 'facebook_check' )=="on") {?>
							<a href="<?php echo get_option( 'facebook_url' ) ?>" target="_blank">
								<i class="fa fa-facebook"></i>
							</a>
						<?php } ?>
						<?php if(get_option( 'instagram_check' )=="on") {?>
							<a href="<?php echo get_option( 'instagram_url' ) ?>" target="_blank">
								<i class="fa fa-instagram"></i>

							</a>
						<?php } ?>
					</div>
				</div>
				<div class="vc_row">
					<div class="vc_col-sm-12">
						<hr>
						<?php dynamic_sidebar( 'footer-custom-bottom' ); ?>
					</div>
				</div>

			</div>
</div><!-- .site -->

<?php wp_footer(); ?>

<script>
	jQuery(document).ready(function() {
		jQuery(window).load(function () {
			jQuery("#preloader").hide();
		});
	});		
</script>

</body>
</html>
