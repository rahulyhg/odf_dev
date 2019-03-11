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
<div class="product-detail-2">
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

<?php //echo do_shortcode('[testimonial_view id="2"]'); ?>

<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="/?page_id=168" class="pagination_product_details_all">All</a>
   <?php previous_post_link('%link', ''); ?>
</div>


<?php get_footer(); ?>