<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage ODF-theme-2
 * @since ODF-theme-2 1.0
 */
?>

		</div><!-- .site-content -->
 
	</div><!-- .site-inner -->

			<?php global $test_theme; ?>

			<div class="div_footer">
				<div class="vc_row">
					<div class="vc_col-sm-1">
						<?php //if($test_theme['facebook-check-botton']==1) {
							if(!empty($test_theme['ontex-footer-logo']['url'])){
								echo '<img class="logo_footer" src="'.$test_theme['ontex-footer-logo']['url'].'" width="140">';
							}

							?>
					</div>
					<div class="vc_col-sm-2">
						<h5>Our details</h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<div class="vc_col-sm-2">
						<h5>Products</h5>
						<ul>
							<li>Product 1</li>
							<li>Product 2</li>
							<li>Product 3</li>
							<li>Product 4</li>
						</ul>
					</div>
					<div class="vc_col-sm-2">
						<h5>Our firm</h5>
						<ul>
							<li>Page 1</li>
							<li>Page 2</li>
							<li>Page 3</li>
							<li>Page 4</li>
						</ul>
					</div>
					<div class="vc_col-sm-2">
						<h5>Blog</h5>
						<ul>
							<li>Legal notice</li>
							<li>Cookies</li>
							<li>Provacy policy</li>
						</ul>
					</div>
					<div class="vc_col-sm-2">				
						<div class="vc_row">
							<div class="vc_col-sm-4 footer_appli">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-white.png">
								<span>IOT</span>
							</div>
							<div class="vc_col-sm-4 footer_appli">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-white.png">
								<span>Appli 1</span>
							</div>
							<div class="vc_col-sm-4 footer_appli">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-phone-white.png">
								<span>Appli 2</span>
							</div>
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
						<?php if($test_theme['twitter-check-button']==1) {?>
							<a href="<?php echo $test_theme['twitter-header-ontex'] ?>" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-youtube-white.png">
							</a>
						<?php } ?>
						<?php if($test_theme['facebook-check-button']==1) {?>
							<a href="<?php echo $test_theme['facebook-header-ontex'] ?>" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-facebook-white.png">
							</a>
						<?php } ?>
						<?php if($test_theme['instagram-check-button']==1) {?>
							<a href="<?php echo $test_theme['instagram-header-ontex'] ?>" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-instagram-white.png">
							</a>
						<?php } ?>
						<?php if($test_theme['linkedin-check-button']==1) {?>
							<a href="<?php echo $test_theme['linkedin-header-ontex'] ?>" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri() ; ?>/images/icon-linkedin-white.png">
							</a>
						<?php } ?>
					</div>
				</div>
				<div class="vc_row">
					<div class="vc_col-sm-12">
						<hr>
						<p class="copyright">Copyright ©<?php echo date("Y");?>, ID Ontex</p>
					</div>
				</div>

			</div>
</div><!-- .site -->

<?php wp_footer(); ?>


<script>
	jQuery(document).ready(function() {
		"use strict";

		jQuery('#div_header_mon_compte .fa-search').click(function(){
		    // jQuery(this).hide();
		    jQuery('#div_searchform').show('fade',500);
		    jQuery('.fa-times').show('fade',500);
		    jQuery(this).css('color','#fcb532');

		});

		jQuery('.fa-times').click(function(){
		    jQuery(this).hide();
		    jQuery('#div_searchform').hide();
		    jQuery('#div_header_mon_compte .fa-search').css('color','#ffffff');
		    jQuery('#div_header_mon_compte .fa-search').show('fade',500);
		    jQuery('#searchform .section_ic_search').css('background','none');
		});		

	});
</script>

</body>
</html>
