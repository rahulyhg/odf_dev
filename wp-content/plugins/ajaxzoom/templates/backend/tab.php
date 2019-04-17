<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script type="text/javascript">
/*!
Plugin Name: AJAX-ZOOM
Author: AJAX-ZOOM
Author URI: http://www.ajax-zoom.com/
Plugin URI: http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce
License URI: http://www.ajax-zoom.com/index.php?cid=download
*/

var ajaxzoomData = {
	"ajaxUrl": '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>',
	"id_product": <?php echo $product_id; ?>,
	"uri": '<?php echo $uri; ?>',
	"image_path": '<?php echo $uri . '/ajaxzoom/assets/img/image_path.gif'; ?>'
};

jQuery('body').on('click', '.az_closeSelect', function() {
	jQuery(this).prev().val('').trigger('change');
} );

</script>

<?php
require 'tab360-settings.php';
require 'tab360-sets.php';
require 'tab360.php';
?>