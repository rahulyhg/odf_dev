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
<div class="product-detail-1-1">
	<div class="product-single-header">
		<div class="left">
			<img class="product-signle-product1" src="wp-content/themes/ODF-theme-1/images/product-single-model1.png" />
		</div>
		<div class="right">
			<h3>ACCESS CATALOG</h3>
			<hr>
         <?php 
         $recommanded_products = $wpdb->get_results("select * from {$wpdb->prefix}posts where post_type = 'product' and post_status = 'publish' order by post_date desc limit 3");
         foreach ($recommanded_products as $recommanded_product) {
            
            echo '<pre>';
            print_r($recommanded_product);
            echo '</pre>';
            
         }

         ?>
			<label class="level1"><a href="#">Recommanded products</a></label>
			<label class="level2"><a href="#">Product1</a></label>
			<label class="level2"><a href="#">Product2</a></label>
			<label class="level2"><a href="#">Product3</a></label>
			<label class="level1"><a href="#">Other products</a></label>
			<label class="level2"><a href="#">Product4</a></label>
			<label class="level2"><a href="#">Product5</a></label>
			<label class="level2"><a href="#">Product6</a></label>

         <div class="vc_btn3-container vc_btn3-center">
            <a onmouseleave="this.style.borderColor='#ffffff'; this.style.backgroundColor='transparent'; this.style.color='#ffffff'" onmouseenter="this.style.borderColor='#04a8de'; this.style.backgroundColor='#04a8de'; this.style.color='#ffffff';" style="border-color: rgb(255, 255, 255); color: rgb(255, 255, 255); background-color: transparent; margin-top: 60px;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-outline-custom" a="" href="<?php echo get_permalink( get_page_by_title( "Catalog" )->ID); ?>" title="Catalog access">Full catalog</a>
         </div>

		</div>
	</div>

   <div class="product-single-caracteristics">
      <div class="left-side">
         <h2>CARACTERISTICS</h2>
         <p>
            <?php if(!empty(get_field('_product_3d_description', $product->get_id()))){
               echo get_field('_product_3d_description', $product->get_id());
            } ?>            
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
      <div class="right-side">
         <div class="detail-product-img-1-1">
            <img src="<?php echo get_the_post_thumbnail_url($product->get_id()) ?>" />
         </div>
         <div class="product_details_mini_buttons">
            <button class="row_grid_product_button row_grid_product_button_3 odf_display_none">Find Shop</button>
            <button class="row_grid_product_button row_grid_product_button_2 odf_display_botton_buy">Buy</button>
            <button class="row_grid_product_button row_grid_product_button_1 sg-popup-id-1 odf_display_none">Request sample</button>
         </div>
      </div>
   </div>


   <div class="clear"></div>
   <h2 class="demo-product">
      <?php if(!empty(get_field('_product_3d_title', $product->get_id()))){
         echo get_field('_product_3d_title', $product->get_id());
      }else{ ?>
         DEMO PRODUCT
      <?php } ?>
   </h2>
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

</div>


<div class="pagination_product_details">
   <?php next_post_link('%link', ''); ?>  
   <a href="<?php echo get_permalink( get_page_by_title( "Catalog" )->ID); ?>" class="pagination_product_details_all">All</a>
   <?php previous_post_link('%link', ''); ?>
</div>


<?php get_footer(); ?>