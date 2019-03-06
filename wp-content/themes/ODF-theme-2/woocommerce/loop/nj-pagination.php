<?php

$link= $current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];;
if (parse_url($link, PHP_URL_QUERY))  
{
	$link.="&columns=";
}
else{
	$link.="?columns=";
}
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {  
	return;
}
?>
<nav class="woocommerce-pagination">
<div class="paginationc pagination-footer">
	<select class="Woo_view_model_change">
        <option>View</option>
        <option value="<?php echo $link."1" ?>">1</option> 
        <option value="<?php echo $link."2" ?>">2</option>
        <option value="<?php echo $link."3" ?>">3</option>
        <option value="<?php echo $link."4" ?>">4</option>  
      </select>
	  <div class="liens-pagination">
	  <?php    
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
			'base'         => $base,
			'format'       => $format,
			'add_args'     => false,
			'current'      => max( 1, $current ),
			'total'        => $total,
			'prev_text'    => 'Précédent',
			'next_text'    => 'Suivant',
			'type'         => 'plain',    
			'end_size'     => 3,
			'mid_size'     => 3,
		) ) );
	?>
	  </div>
</div>
	
</nav>
<script>
jQuery(function($){
	$('.Woo_view_model_change').on('change',function(){
		 window.location.replace(this.value);    
	})
})
</script>
