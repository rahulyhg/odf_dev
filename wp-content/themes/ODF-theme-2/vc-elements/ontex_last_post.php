<?php 
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcOntexLastPost extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'vc_infobox_ontex_last_post', array( $this, 'vc_infobox_html' ) );
    }
     
    // Element Mapping
    public function vc_infobox_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
         
        // Map the block with vc_map()
        vc_map( 
            array(
                'name' => __('Ontex last post', 'text-domain'),
                'base' => 'vc_infobox_ontex_last_post',
                'description' => __('Show last post', 'text-domain'), 
                'category' => __('Ontex Elements', 'text-domain'),   
                'icon' => get_template_directory_uri().'/vc-elements/img/Ontex4You.png',            
                'params' => array(   

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Category', 'text-domain' ),
                        'param_name' => 'cat',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Title', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),
                        
                ),
            )
        );                                
        
    }
     
     
    // Element HTML
    public function vc_infobox_html( $atts ) {
        global $wpdb;

        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'cat'   => '',
                ), 
                $atts
            )
        );
         
        // Fill $html var with data

        $args = array(
            'posts_per_page'    => 1,
            'numberposts'       => 1,
            'offset'            => 0,
            'post_type'         => 'post',
            'orderby'           => 'post_date',
            'order'             => 'DESC',
            'post_status'       => 'publish'
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query -> have_posts()) : $query -> the_post();
                ob_start();
                ?>
                <div class="content-box">
                    <div class="row">
                        
                        <div class="col-md-6 content-box-left ">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                        <div class="col-md-6 content-box-right bg-cl-site">
                            <div class="box-text-content">
                                <h2><?php the_title() ?></h2>
                                <br>
                                    <?php the_excerpt() ?>                  
                                <a href="<?php the_permalink(); ?>" class="btn-go"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        endif;
        return ob_get_clean();
         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcOntexLastPost(); 