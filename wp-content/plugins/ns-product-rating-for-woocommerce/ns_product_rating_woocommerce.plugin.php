<?php
/*
Plugin Name: NS Product Rating for WooCommerce
Plugin URI: https://wordpress.org/plugins/ns-product-rating-for-woocommerce/
Description: Add rating to your woocommerce site
Version: 1.1.3
Author: NsThemes
Author URI: http://nsthemes.com
Text Domain: ns-product-rating-woocommerce
Domain Path: /languages
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


if ( ! defined( 'ABSPATH' ) ) {
    wp_die( __( 'This file cannot be called directly!', 'ns-product-rating-woocommerce' ) );
    exit;
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if ( ! class_exists( 'NS_ProductRatingWooCommerce' ) ) {
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT", dirname( __FILE__ ));
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT_INTERNAL", WP_PLUGIN_URL.'/ns-product-rating-for-woocommerce');
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_FORM", NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT . '/ns_product_rating_woocommerce.form.php');
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_SHOP_FORM", NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT . '/ns_product_rating_woocommerce.form.shop.php');
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_ADMIN", NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT . '/ns_product_rating_woocommerce.admin.php');
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_ADMIN_RESULT_TABLE", NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT . '/ns_product_rating_woocommerce.restable.php');
        define("NS_WP_PRODUCT_RATING_WC_PLUGIN_STYLE", NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT . '/ns_product_rating_woocommerce.style.php');

        require_once( plugin_dir_path( __FILE__ ).'ns-admin-options/ns-admin-options-setup.php');

        include_once (NS_WP_PRODUCT_RATING_WC_PLUGIN_STYLE);
        /**
         * Localisation
         **/
        // load_plugin_textdomain( 'ns_product_rating_woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );


        class NS_ProductRatingWooCommerce {
            public function __construct() {
                // called only after woocommerce has finished loading
                add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );

                // called after all plugins have loaded
                add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );

                // called just before the woocommerce template functions are included
                add_action( 'init', array( &$this, 'include_template_functions' ), 20 );

                // indicates we are running the admin
                if ( is_admin() ) {
                    // ...
                }

                // indicates we are being served over ssl
                if ( is_ssl() ) {
                    // ...
                }

                // take care of anything else that needs to be done immediately upon plugin instantiation, here in the constructor
            }

            /**
             * Take care of anything that needs woocommerce to be loaded.
             * For instance, if you need access to the $woocommerce global
             */
            public function woocommerce_loaded() {
                // ...
            }

            /**
             * Take care of anything that needs all plugins to be loaded
             */
            public function plugins_loaded() {
                // ...
            }

            /**
             * Override any of the template functions from woocommerce/woocommerce-template.php
             * with our own template functions file
             */
            public function include_template_functions() {
                include( 'ns_product_rating_woocommerce.template.php' );
            }
        }

        // finally instantiate plugin class and add it to the set of globals
        $GLOBALS['ns_product_rating_woocommerce'] = new NS_ProductRatingWooCommerce();
    }


    function ns_product_rating_woocommerce_add_stars( $content ) {
        global $post;
        // controllo anche se siamo all'interno di un post valido, controllando l'esistenza di un ID valido
        if ( ! isset( $post->ID ) ){
            return $content;
        }

        // recupero l'attuale votazione memorizzata all'interno del post
        $post_rate    = floatval( get_post_meta( $post->ID, '_ns_prw_post_rate', true ) );
        $rating_count = intval( get_post_meta( $post->ID, '_ns_prw_post_rate_count', true ) );

        // controllo per il post type
        if ( ! isset( $post->post_type ) || $post->post_type != 'product' ){
            return $content;
        } else{
            if(is_product()){
                include NS_WP_PRODUCT_RATING_WC_PLUGIN_FORM;
            }elseif(is_shop()){
                include NS_WP_PRODUCT_RATING_WC_PLUGIN_SHOP_FORM;
            }elseif(is_product_category()){
                include NS_WP_PRODUCT_RATING_WC_PLUGIN_SHOP_FORM;
            }
        }

        ?>
        <?php
        return $content;

    }
    add_action( 'woocommerce_after_shop_loop_item', 'ns_product_rating_woocommerce_add_stars', 40 );
    add_action( 'woocommerce_single_product_summary', 'ns_product_rating_woocommerce_add_stars', 15 );
    // add_action( 'woocommerce_after_single_product_summary', 'ns_product_rating_woocommerce_add_stars', 15 );

    function ns_product_rating_woocommerce_add_rate_ajax() {
        // controll0 se sono autorizzato ad eseguire questa chiamata AJAX
        // primo param: valore nonce creato durante l'invio della richiesta AJAX
        // secondo param: nome dell'azione che ha creato il valore nonce (creato con wp_create_nonce)
        if ( ! wp_verify_nonce( $_REQUEST['ns_product_rating_woocommerce_nonce'], 'ns_product_rating_woocommerce_rate' ) )
            die ( 'You are not authorized!');

        if ( ! isset( $_REQUEST['rate'] ) ) die();

        $user_rate = floatval( $_REQUEST['rate'] );
        $post_id   = intval( $_REQUEST['post_id'] );
        if (!isset($_COOKIE['ns_prw_vote_already_sent_'. $post_id])) {
            setcookie('ns_prw_vote_already_sent_'. $post_id, 1, time() + (86400), "/");


            // recupero l'attuale votazione memorizzata all'interno del post
            $post_rate    = floatval( get_post_meta( $post_id, '_ns_prw_post_rate', true ) );
            $rating_count = intval( get_post_meta( $post_id, '_ns_prw_post_rate_count', true ) );

            if ( $post_rate )  {
                $post_rate = ( $post_rate + $user_rate ) / 2;
                $rating_count++;
            }else {
                $post_rate = $user_rate;
                $rating_count = 1;
            }

            // aggiorno il valore nel database
            update_post_meta( $post_id, '_ns_prw_post_rate', $post_rate );
            update_post_meta( $post_id, '_ns_prw_post_rate_count', $rating_count );

            echo json_encode( array(
                'rate' => number_format( $post_rate, 1, ',', '.' ),
                'count' => $rating_count,
                'ns_call_failed' => false,
                'feedback' => __( 'Thanks for expressing your vote!', 'ns' ),
            ));

            die();
        }else{
        }
    }
    add_action( 'wp_ajax_add_post_rating', 'ns_product_rating_woocommerce_add_rate_ajax' );
    add_action( 'wp_ajax_nopriv_add_post_rating', 'ns_product_rating_woocommerce_add_rate_ajax' );

    /*function ns_productsearch_woocommerce_watermark_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <a href="http://www.nsthemes.com/product/woocommerce-product-rating/?utm_source=WooCommerce%20Product%20Rating%20Bannerone&utm_medium=Bannerone%20dashboard&utm_campaign=WooCommerce%20Product%20Rating%20Bannerone%20premium">
                    <img src="<?=NS_WP_PRODUCT_RATING_WC_PLUGIN_ROOT_INTERNAL  ?>/asset/img/bannerooone.png" style="width: 100%; height: auto;">
                </a>
            </p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'ns_productsearch_woocommerce_watermark_notice' );*/

    /* Attivo le opzioni di default */
    function ns_product_rating_woocommerce_activate_set_default_options()
    {
        add_option('ns_product_rating_woocommerce_symbol_color', '#FFED85');
    }
    register_activation_hook(__FILE__, 'ns_product_rating_woocommerce_activate_set_default_options');


    /* Registro le opzioni di default */
    function ns_product_rating_woocommerce_register_options_group()
    {
        register_setting('ns_product_rating_woocommerce_options_group', 'ns_product_rating_woocommerce_symbol_color');
    }
    add_action('admin_init', 'ns_product_rating_woocommerce_register_options_group');
    include_once NS_WP_PRODUCT_RATING_WC_PLUGIN_ADMIN;

    /* Attivo le opzioni della pagina dei risultati */
    function ns_product_rating_woocommerce_activate_set_default_options_resulttable()
    {
        /*add_option('ns_product_rating_woocommerce_symbol_color', '#FFD700');*/
    }

    register_activation_hook(__FILE__, 'ns_product_rating_woocommerce_activate_set_default_options_resulttable');

    /* Registro le opzioni della pagina dei risultati */
    function ns_product_rating_woocommerce_register_options_group_resulttable()
    {
        /*register_setting('ns_product_rating_woocommerce_options_group', 'ns_product_rating_woocommerce_symbol_color');*/
    }
    add_action('admin_init', 'ns_product_rating_woocommerce_register_options_group_resulttable');
    include_once NS_WP_PRODUCT_RATING_WC_PLUGIN_ADMIN_RESULT_TABLE;

    /*********************************************************
                INCLUSIONE text domain
    *********************************************************/
    function ns_product_rating_woocommerce_translate(){

        load_plugin_textdomain('ns-product-rating-woocommerce',false, basename( dirname( __FILE__ ) ) .'/languages/');
    }
    add_action('plugins_loaded','ns_product_rating_woocommerce_translate');

}


