<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
if(false){
?>
<li <?php wc_product_class(); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );
	

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	echo get_the_excerpt($product->get_id());

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
<?php } 
?>
<li <?php wc_product_class(); ?>>  
<div class="produit">
        <h3><?php echo $product->get_title(); ?></h3>  
        <?php woocommerce_template_loop_product_thumbnail() ?>
        <p><?php echo wp_trim_words( get_the_excerpt($product->get_id()), 20); ?></p>
        <div class="containeur-vote odf_display_rating">  
          <?php echo ns_product_rating_woocommerce_add_stars( "" ); ?>
        </div>    
        <div class="btn-produit">
          <div class="btn-produit-a odf_display_botton_buy">
            <!-- <a href="<?php echo get_home_url() ?>?post_type=product&add-to-cart=<?php echo $product->get_id() ?>" class="buy odf_display_botton_buy">Buy</a> -->
            <a href="<?php echo get_field('button_buy_url',$product->ID); ?>" class="buy odf_display_botton_buy" target="_blanc">Buy</a>
          </div>
          <div class="btn-produit-b <?php if(get_option( 'enable_button_buy' ) != 'on') { echo 'btn-produit-b-center'; } ?>">
            <a href="<?php echo get_post_permalink($product->get_id()) ?>">Détails</a>
          </div>
        </div>
      </div>

</li>

<!-- btn-produit-b-center -->