<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Digital_Factory
 */

?>

	</div><!-- #content -->

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
				<?php if($test_theme['facebook-check-botton']==1) {?>
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



	<!-- <h3 class="caption-social-media">Lorem Ipsum</h3>
	<div class="social-media">
		<?php if($test_theme['facebook-check-botton']==1) {?>
		<a href="<?php echo $test_theme['facebook-header-ontex'] ?>" target="_blank">
			<svg class="svg" width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0)">
			<path d="M18 0.499954C8.07484 0.499954 0 8.5748 0 18.5C0 28.4244 8.07484 36.5 18 36.5C27.9244 36.5 36 28.4244 36 18.5C36 8.5748 27.9259 0.499954 18 0.499954ZM22.4764 19.1336H19.548C19.548 23.8124 19.548 29.5714 19.548 29.5714H15.2086C15.2086 29.5714 15.2086 23.8682 15.2086 19.1336H13.1458V15.4446H15.2086V13.0585C15.2086 11.3495 16.0206 8.6792 19.5879 8.6792L22.8034 8.69153V12.2725C22.8034 12.2725 20.8494 12.2725 20.4695 12.2725C20.0896 12.2725 19.5494 12.4625 19.5494 13.2774V15.4453H22.8556L22.4764 19.1336Z" fill="#E42B77"/>
			</g>
			<defs>
			<clipPath id="clip0">
			<rect width="36" height="36" fill="white" transform="translate(0 0.499954)"/>
			</clipPath>
			</defs>
			</svg>
		</a>
		<?php } ?>
		<?php if($test_theme['instagram-check-button']==1) {?>
		<a href="<?php echo $test_theme['instagram-header-ontex'] ?>" target="_blank">
			<svg class="svg" width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M1.14258 8.61981V28.3449C1.14258 32.2178 4.28213 35.3574 8.15501 35.3574H27.8801C31.753 35.3574 34.8926 32.2178 34.8926 28.3449V8.61981C34.8926 4.74693 31.753 1.60738 27.8801 1.60738H8.15501C4.28213 1.60738 1.14258 4.74693 1.14258 8.61981ZM21.5926 28.045C13.2682 30.9172 5.58277 23.2318 8.45494 14.9074C9.4227 12.1017 11.6367 9.8875 14.4424 8.91974C22.767 6.04757 30.4524 13.733 27.5802 22.0576C26.6125 24.8632 24.3984 27.0773 21.5926 28.045ZM29.7285 8.27704C29.5961 8.97062 29.0222 9.37416 28.4285 9.37416C28.0672 9.37416 27.6989 9.22502 27.4169 8.90141C27.3919 8.87277 27.3687 8.84208 27.3481 8.81015C27.057 8.3652 27.0479 7.83662 27.2953 7.40795C27.4704 7.10452 27.753 6.88782 28.0915 6.79718C28.4295 6.70613 28.783 6.75289 29.086 6.92819C29.5149 7.17538 29.7712 7.63784 29.7419 8.16807C29.7399 8.20432 29.7355 8.2412 29.7285 8.27704Z" fill="#E42B77"/>
			<path d="M18.0176 10.2834C13.4964 10.2834 9.8186 13.9612 9.8186 18.4824C9.8186 23.0035 13.4964 26.6813 18.0176 26.6813C22.5387 26.6813 26.2165 23.0035 26.2165 18.4824C26.2165 13.9612 22.5387 10.2834 18.0176 10.2834Z" fill="#E42B77"/>
			</svg>

		</a>
		<?php } ?>
		<?php if($test_theme['twitter-check-button']==1) {?>
		<a href="<?php echo $test_theme['twitter-header-ontex'] ?>" target="_blank">
			<svg class="svg" width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0)">
			<path d="M18 0.499954C8.07484 0.499954 0 8.5748 0 18.5C0 28.4244 8.07484 36.5 18 36.5C27.9244 36.5 36 28.4244 36 18.5C36 8.5748 27.9259 0.499954 18 0.499954ZM26.0299 14.3802C26.0379 14.5586 26.0422 14.7384 26.0422 14.9182C26.0422 20.3931 21.8761 26.7039 14.2537 26.7039C11.914 26.7039 9.73592 26.0201 7.903 24.8441C8.2271 24.8825 8.557 24.9021 8.89124 24.9021C10.8329 24.9021 12.6187 24.2394 14.0369 23.1287C12.2243 23.0953 10.6937 21.8975 10.1666 20.2509C10.4189 20.2988 10.6792 20.3256 10.9453 20.3256C11.323 20.3256 11.6899 20.2763 12.0372 20.1813C10.1419 19.8014 8.71433 18.1273 8.71433 16.1189C8.71433 16.1015 8.71433 16.0834 8.71506 16.0667C9.27334 16.3763 9.91211 16.5634 10.5908 16.5844C9.47998 15.8427 8.74841 14.5738 8.74841 13.1368C8.74841 12.3769 8.95215 11.6649 9.30887 11.053C11.3513 13.5602 14.4052 15.209 17.8477 15.383C17.7767 15.0792 17.7412 14.7638 17.7412 14.4382C17.7412 12.1507 19.5958 10.2953 21.8833 10.2953C23.0753 10.2953 24.1506 10.7985 24.9075 11.6033C25.8523 11.4177 26.7368 11.074 27.5394 10.5977C27.2277 11.5656 26.5729 12.3769 25.7152 12.8903C26.5541 12.7902 27.3545 12.5684 28.0948 12.2385C27.5423 13.0679 26.8398 13.798 26.0299 14.3802Z" fill="#E42B77"/>
			</g>
			<defs>
			<clipPath id="clip0">
			<rect width="36" height="36" fill="white" transform="translate(0 0.499954)"/>
			</clipPath>
			</defs>
			</svg>
		</a>
		<?php } ?>
		<?php if($test_theme['linkedin-check-button']==1) {?>
			<a href="<?php echo $test_theme['linkedin-header-ontex'] ?>" target="_blank">
			<svg class="svg" width="37" height="36" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0)">
				<path d="M18.5 0C8.5595 0 0.5 8.0595 0.5 18C0.5 27.9405 8.5595 36 18.5 36C28.4405 36 36.5 27.9405 36.5 18C36.5 8.05728 28.4405 0 18.5 0ZM14 26.7187H9.5V10.9687H14V26.7187ZM11.8895 10.0035C10.724 10.0035 9.78125 9.05847 9.78125 7.89297C9.78125 6.72746 10.7263 5.78245 11.8895 5.78245C13.055 5.78467 14 6.72968 14 7.89297C14 9.05847 13.055 10.0035 11.8895 10.0035ZM29.75 26.7187H25.25V16.9807C25.25 15.84 24.9237 15.0413 23.5219 15.0413C21.1977 15.0413 20.75 16.9807 20.75 16.9807V26.7187H16.25V10.9687H20.75V12.4739C21.3934 11.9812 22.9999 10.9709 25.25 10.9709C26.708 10.9709 29.75 11.8439 29.75 17.1179V26.7187Z" fill="#E42B77"/>
				</g>
			<defs>
			<clipPath id="clip0">
			<rect width="36" height="36" fill="white" transform="translate(0.5)"/>
			</clipPath>
			</defs>
			</svg>
			</a>
		<?php } ?>
	</div>
	<hr>
	<p class="copyright">Copyright ©2017, Canbebe, Ontex Tüketim Ürünleri San. ve Tic. A.Ş.</p> -->
    </main><!-- /.container -->
</div><!-- #page -->
<?php // wp_footer(); ?>

</body>
</html>
