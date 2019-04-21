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
</div>
<div>
<div class="single-product">
   <h1><?php echo $product->get_title(); ?></h1>
   <div class="full-img-product">
      <img src="<?php echo get_the_post_thumbnail_url($product->get_id()); ?>" width="100%" />
   </div>
   <p class="product-description">
      <?php echo get_the_excerpt($product->get_id()); ?>
   </p>
   <div class="btns-single-product">
      <a href="#modal" class="request-sample odf_display_none"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn1-detail-product.png" /></span><span>Request sample</span></a>
      <a href="<?php echo get_field('button_buy_url',$product->get_id()); ?>" class="buy odf_display_botton_buy"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn3-detail-product.png" /></span><span>Buy</span></a>
      <a href="#" class="find-shop odf_display_none"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn2-detail-product.png" /></span><span>Find shop</span></a>
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
      <h2 class="demo-product">
        <?php if(!empty(get_field('_product_3d_title', $product->get_id()))){
           echo get_field('_product_3d_title', $product->get_id());
        }else{ ?>
           DEMO PRODUCT
        <?php } ?>
      </h2>
      <p>
        <?php if(!empty(get_field('_product_3d_description', $product->get_id()))){
           echo get_field('_product_3d_description', $product->get_id());
        } ?>
      </p>
      <h3 class="title-demo-product">
        <?php if(!empty(get_field('product_size_text', $product->get_id()))){
           echo get_field('product_size_text', $product->get_id());
        }else{ ?>
            Product size
        <?php } ?>          
      </h3>

      <div class="img-above-360">
         <?php echo '<img src="'.get_field('product_small_image', $product->get_id()).'" width="150" />'; ?>
         <?php echo '<img src="'.get_field('product_medium_image', $product->get_id()).'" width="300" />'; ?>
         <?php echo '<img src="'.get_field('product_large_image', $product->get_id()).'" width="500" />'; ?>
      </div>
      <br>
      <div class="img-360">
         <?php echo do_shortcode('[wr360embed name="view01" width="100%" height="500px" config="'.get_field('_wr360config', $product->get_id()).'"]'); ?>
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


<!-- <div class="feedback-carousel owl-carousel">
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
          <?php echo do_shortcode('[testimonial_average_rating]{stars}[/testimonial_average_rating]'); ?>                  
                    <h4><?php echo $wpm_testimonial->post_title; ?></h4>
                    <p><?php echo get_the_excerpt($wpm_testimonial->ID); ?></p>
               </div>
            </div>
         </div> 

         <?php
      }
   ?>
</div> -->

<?php //echo do_shortcode('[testimonial_view id="2"]'); ?>

</div>
<br><br><br>

<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="<?php echo get_permalink( get_page_by_title( "Catalog" )->ID); ?>" class="pagination_product_details_all">All</a>
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