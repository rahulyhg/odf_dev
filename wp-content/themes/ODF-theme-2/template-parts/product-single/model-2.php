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

<div class="single-product">
   <h1><?php echo $product->get_title(); ?></h1>
   <div class="full-img-product">
      <img src="<?php echo get_the_post_thumbnail_url($product->get_id()); ?>" width="100%" />
   </div>
   <p class="product-description">
      <?php echo get_the_excerpt($product->get_id()); ?>
   </p>
   <div class="btns-single-product">
      <a href="#modal" class="request-sample"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn1-detail-product.png" /></span><span>Request sample</span></a>
      <a href="<?php echo get_field('button_buy_url',$product->get_id()); ?>" class="buy odf_display_botton_buy"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn3-detail-product.png" /></span><span>Buy</span></a>
      <a href="#" class="find-shop"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn2-detail-product.png" /></span><span>Find shop</span></a>
   </div>
   <br><br>
   <div class="product-details">
      <?php
      $products_caracteristics = get_field('product_caracteristics_multiple', $product->get_id());
      foreach ($products_caracteristics as $product_caracteristic) {
       ?>
         <div class="product-detail">
            <div class="detail-product-icon">
               <img src="<?php echo get_field("custom_icon_product", $product_caracteristic->ID) ?>" />
            </div>
            <div class="product-description">
               <p>
                  <?php echo $product_caracteristic->post_content; ?>
               </p>
            </div>
            <div class="detail-product-img">
               <img src="<?php echo get_the_post_thumbnail_url($product_caracteristic->ID) ?>" />
            </div>
         </div>
      <?php } ?>
   </div>
   <div class="demo-product">
      <h2>Demo product</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut commodo odio eros, eget placerat libero venenatis posuere. Mauris scelerisque dignissim lacus ac molestie. Sed a elit est. Donec in eros eu metus eleifend aliquet. Pellentesque non nibh turpis. Aenean eget dui interdum, facilisis justo sit amet, commodo lectus. Integer placerat, erat vitae auctor ornare, est nunc tempus leo, vel molestie lacus felis vitae tellus.</p>
      <h3 class="title-demo-product">Product size</h3>


      <div class="img-above-360">
         <?php echo '<img src="'.get_field('product_small_image', $product->get_id()).'" width="150" />'; ?>
         <?php echo '<img src="'.get_field('product_medium_image', $product->get_id()).'" width="300" />'; ?>
         <?php echo '<img src="'.get_field('product_large_image', $product->get_id()).'" width="500" />'; ?>
      </div>
      <div class="img-360">
         <?php echo do_shortcode('[wr360embed name="view01" width="100%" height="500px" config="/wp-content/plugins/webrotate-360-product-viewer/360_assets/sampleshoe/config.xml"]'); ?>
         <?php echo get_field('_wr360config', $product->get_id()); ?>
         <!-- <img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/demo-product.png" /> -->
      </div>
   </div>
   <div class="after-demo-product">
      <h2><?php echo get_field('title_middle_page', $product->get_id()); ?></h2>
      <!-- <span class="span-h2">simplement du faux texte</span> -->
      <span class="description">
         <?php echo get_field('description_middle_page', $product->get_id()); ?>
      </span>
      <img class="logo-id" src="<?php echo get_field('logo_middle_page', $product->get_id()); ?>" />
      <img class="canbebe-3-box" src="<?php echo get_field('image_middle_page', $product->get_id()); ?>" />
   </div>


<div class="feedback-carousel owl-carousel">
   <?php
      $wpm_testimonials = $wpdb->get_results("select * from {$wpdb->prefix}posts where post_status = 'publish' and post_type = 'wpm-testimonial' ");
      foreach ($wpm_testimonials as $wpm_testimonial) {
         ?>
         <div class="feedback">
            <div class="feedback-content">
               <div class="feedback-img">
                  <img src="<?php echo get_the_post_thumbnail_url($wpm_testimonial->ID); ?>" />
               </div>
               <div class="feedback-content-body">
                  <img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/feedback.png" />
                  <h4><?php echo $wpm_testimonial->post_title; ?></h4>
                  <p><?php echo get_the_excerpt($wpm_testimonial->ID); ?></p>
               </div>
            </div>
         </div> 

         <?php
      }
   ?>
</div>

</div>
<br><br><br>

<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="/?page_id=168" class="pagination_product_details_all">All</a>
   <?php previous_post_link('%link', ''); ?>
</div>


<?php echo wp_enqueue_style( 'carouselcss', get_template_directory_uri() . '/css/owl.carousel.min.css' ); ?>
<?php 
wp_register_script('carouseljs', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js');
    wp_enqueue_script('carouseljs');
    ?>
<script>
   jQuery(document).ready(function(){
        jQuery('.owl-carousel').owlCarousel({
          loop:true,
          margin:10,
          nav:true,
          responsive:{
              0:{
                  items:1
              },
              600:{
                  items:1
              },
              1000:{
                  items:1
              }
          }
      })
});
</script>

<?php get_footer(); ?>