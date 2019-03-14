<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); 
global $product, $wpdb;

?>
<style>
   .product-detail-1-1 .product-single-header{
   	overflow:hidden;
   	height:479px;
   	margin-bottom: 20px;
   }
   .product-detail-1-1 .product-single-header .left{
   	width:80%;
   	float:left;
   	background-image:url("wp-content/themes/ODF-theme-1/images/bg-single-product-1.png");
   	background-repeat:no-repeat;
   	height:480px;
   	
   }
   .product-detail-1-1 .product-single-header .right{
   	color:white;
   	background-image: linear-gradient(-180deg, #05c0ea 0%, #028cd0 100%);
   	width:20%;
   	float:left;
   	padding-top:20px;
   	height:100%;
   }
   .product-detail-1-1 .product-single-header .right a{
   	color:#fff;
   }
   .product-detail-1-1 .product-single-header .right hr{
   	width: 80%;
       margin: 0 auto;
   }
   .product-detail-1-1 .product-single-header .right label{
   	display:block;
   	margin:4px 0px;
   	margin-left: 25px;
   }
   .product-detail-1-1 .product-single-header .right h3{
   	text-align:center;
   }
   .product-detail-1-1 .product-single-header .right .level2{
   	margin-left: 45px;
   }
   .product-detail-1-1 .product-single-header .right .level1{
   	margin-top: 10px;
   }
   .product-detail-1-1 .product-single-header .product-signle-product1{
   	position: absolute;
       right: 410px;
       top: 280px;
   }

   .product-detail-1-1 .product-single-caracteristics{
      width: 100%;
      float: left;
      position: relative;
      color: #ffffff;
   }
   .product-detail-1-1 .product-single-caracteristics .left-side{
      background-image: linear-gradient(-180deg, #05c0ea 0%, #028cd0 100%);
      padding: 50px 120px 10px;
      width: 40%;
      height: 396px;
      float: left;
      position: relative;
   }
   .product-detail-1-1 .product-single-caracteristics .left-side:after {
      content: '';
      display: block;
      position: absolute;
      left: 100%;
      top: 50%;
      margin-top: -30px;
      width: 0;
      height: 0;
      border-top: 30px solid transparent;
      border-right: 30px solid transparent;
      border-bottom: 30px solid transparent;
      border-left: 30px solid #04a7dd;
      z-index: 99999;
   }
   .product-detail-1-1 .product-single-caracteristics .right-side{
      background-image: url(http://app-brandwebsite-webapp-pre-prod.azurewebsites.net/wp-content/uploads/2019/01/Screenshot_3-1.png?id=288) !important;
      padding: 20px;
      background-repeat: no-repeat;
      background-position: 50% 50%;
      background-size: cover;
      height: 396px;
      width: 59%;
      float: left;
   }


</style>
<div class="product-detail-1-1">
	<div class="product-single-header">
		<div class="left">
			<img class="product-signle-product1" src="wp-content/themes/ODF-theme-1/images/product-single-model1.png" />
		</div>
		<div class="right">
			<h3>ACCESS CATALOG</h3>
			<hr>
			<label class="level1"><a href="#">Recommanded products</a></label>
				<label class="level2"><a href="#">Product1</a></label>
				<label class="level2"><a href="#">Product2</a></label>
				<label class="level2"><a href="#">Product3</a></label>
			<label class="level1"><a href="#">Other products</a></label>
				<label class="level2"><a href="#">Product4</a></label>
				<label class="level2"><a href="#">Product5</a></label>
				<label class="level2"><a href="#">Product6</a></label>
		</div>
	</div>

   <div class="product-single-caracteristics">
      <div class="left-side">
         <h2>CARACTERISTICS</h2>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <div class="right-side">
         Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      </div>
   </div>


   <div class="img-medium-product">
      <img src="<?php echo get_the_post_thumbnail_url($product->get_id()); ?>" />
   </div>
   <div class="detail-product">
      <h1><?php echo $product->get_title(); ?></h1>
      <p class="product-description">
         <?php echo get_the_excerpt($product->get_id()); ?>
      </p>
      <h4>Caracteristics</h4>
      <p class="product-description">
         Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
      </p>
      <div class="details-product">
         <?php
         $products_caracteristics = get_field('product_caracteristics_multiple', $product->get_id());
         foreach ($products_caracteristics as $product_caracteristic) {
          ?>
            <div class="detail-product-icon">
               <img src="<?php echo get_field("custom_icon_product", $product_caracteristic->ID); ?>" />
               <span><?php echo $product_caracteristic->post_title; ?></span>
            </div>
         <?php } ?>
      </div>
   </div>
   <div class="product-detail-select">
      <form>
         <select class="form-control" class="selectbox">
            <option>SELECT</option>
            <option>FAQ</option>
            <option>TESTIMONIALS</option>
            <option>ADVICES</option>
         </select>
      </form>
   </div>
</div>
<div class="clear"></div>
<div class="btns-single-product">
   <a href="#modal" class="request-sample"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn1-detail-product.png" /></span><span>Request sample</span></a>
   <a href="<?php echo get_field('button_buy_url',$product->get_id()); ?>" class="buy odf_display_botton_buy"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn3-detail-product.png" /></span><span>Buy</span></a>
   <a href="#" class="find-shop"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn2-detail-product.png" /></span><span>Find shop</span></a>
</div>
<div class="clear"></div>
<h2 class="demo-product">DEMO PRODUCT</h2>
<div class="demo-product-2">
   <?php echo do_shortcode('[wr360embed name="view01" width="100%" height="500px" config="'.get_field('_wr360config', $product->get_id()).'"]'); ?>
</div>

<?php 
   $var_shortcode_details_product = '
   [vc_row full_width="stretch_row_content" css=".vc_custom_1552066453161{margin-top: 30px !important;}" el_class="ttt_theme_1_model_1"]
      [vc_column width="1/3"]
         [vc_column_text el_class="product_block_title_details"]<h3 style="text-align: center;">Advice</h3>[/vc_column_text]
         [vc_column_text el_class="product_block_select_details"]<select><option>All Advices</option></select>[/vc_column_text]
         [vc_basic_grid post_type="post" max_items="1" element_width="12" item="336" grid_id="vc_gid:1552065967737-d140121d-8370-7" el_class="product_block_content_details"]
      [/vc_column]
      [vc_column width="1/3"]
         [vc_column_text el_class="product_block_title_details"]<h3 style="text-align: center;">FAQ</h3>[/vc_column_text]
         [vc_column_text el_class="product_block_select_details"]<select><option>All FAQ</option></select>[/vc_column_text]
         [vc_column_text el_class="product_block_content_details"][ultimate-faqs][/vc_column_text]
      [/vc_column]
      [vc_column width="1/3"]
         [vc_column_text el_class="product_block_title_details"]<h3 style="text-align: center;">Testimonials</h3>[/vc_column_text]
         [vc_column_text el_class="product_block_select_details"]<select><option>All Testimonials</option></select>[/vc_column_text]
         [vc_column_text el_class="product_block_content_details"][testimonial_view id="1"][/vc_column_text]
      [/vc_column]
   [/vc_row]';
   
   echo do_shortcode($var_shortcode_details_product); ?>



<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="/?page_id=168" class="pagination_product_details_all">All</a>
   <?php previous_post_link('%link', ''); ?>
</div>


<?php get_footer(); ?>