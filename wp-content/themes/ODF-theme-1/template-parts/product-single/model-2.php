<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header();
global $product;

?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		Model 2
	</main>
</div>

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
      <a href="#" class="buy"><span class="icon-btn"><img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/btn3-detail-product.png" /></span><span>Buy</span></a>
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
      <div class="img-360">
         <img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/demo-product.png" />
      </div>
   </div>
   <div class="after-demo-product">
      <h2>Le Lorem Ipsum est</h2>
      <span class="span-h2">simplement du faux texte</span>
      <span class="description">
      Depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles,
      </span>
      <img class="logo-id" src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/logo-id.png" />
      <img class="canbebe-3-box" src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/canbebe-3-box.png" />
   </div>
   <div class="feedback">
      <a class="carrousel-prev" href="#"></a>
      <div class="feedback-content">
         <div class="feedback-img">
            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/img1-feedback.png" />
         </div>
         <div class="feedback-content-body">
            <img src="<?php echo get_stylesheet_directory_uri() ; ?>/template-parts/img/feedback.png" />
            <h4>Alexander havard</h4>
            <p>Depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles,</p>
         </div>
      </div>
      <a class="carrousel-next" href="#"></a>
   </div>
</div>
<br><br><br>

<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="/?page_id=168" class="pagination_product_details_all">All</a>
   <?php previous_post_link('%link', ''); ?>
</div>


<?php get_footer(); ?>