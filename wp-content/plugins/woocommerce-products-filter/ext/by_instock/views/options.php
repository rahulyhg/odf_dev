<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<li data-key="<?php echo $key ?>" class="woof_options_li">

    <?php
    $show = 0;
    if (isset($woof_settings[$key]['show']))
    {
        $show = (int) $woof_settings[$key]['show'];
    }
    ?>

    <a href="#" class="help_tip woof_drag_and_drope" data-tip="<?php _e("drag and drope", 'woocommerce-products-filter'); ?>"><img src="<?php echo WOOF_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-products-filter'); ?>" /></a>

    <strong style="display: inline-block; width: 176px;"><?php _e("In stock checkbox", 'woocommerce-products-filter'); ?>:</strong>

    <img class="help_tip" data-tip="<?php _e('Show In stock only checkbox inside woof search form', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

    <div class="select-wrap">
        <select name="woof_settings[<?php echo $key ?>][show]" class="woof_setting_select">
            <option value="0" <?php echo selected($show, 0) ?>><?php _e('No', 'woocommerce-products-filter') ?></option>
            <option value="1" <?php echo selected($show, 1) ?>><?php _e('Yes', 'woocommerce-products-filter') ?></option>
        </select>
    </div>


    <input type="button" value="<?php _e('additional options', 'woocommerce-products-filter') ?>" data-key="<?php echo $key ?>" data-name="<?php _e("Search by InStock", 'woocommerce-products-filter'); ?>" class="woof-button js_woof_options js_woof_options_<?php echo $key ?>" />



    <?php
    if (!isset($woof_settings[$key]['use_for']))
    {
        $woof_settings[$key]['use_for'] = 'simple';
    }
    ?>

    <input type="hidden" name="woof_settings[<?php echo $key ?>][use_for]" value="<?php echo $woof_settings[$key]['use_for'] ?>" />


    <div id="woof-modal-content-<?php echo $key ?>" style="display: none;">

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Search in variable produts', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Will the plugin look in each variable of variable products. Request for variables products creates more mysql queries in database ...', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <?php
                $use_for = array(
                    'simple' => __('Simple products only', 'woocommerce-products-filter'),
                    'both' => __('Search in products and their variations', 'woocommerce-products-filter')
                );
                ?>
                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="use_for">
                        <?php foreach ($use_for as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>



    </div>


</li>
