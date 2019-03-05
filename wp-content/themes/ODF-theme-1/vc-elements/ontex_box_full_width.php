<?php 
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcOntexBoxFullWidth extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'vc_infobox_ontex_box_full_width', array( $this, 'vc_infobox_html' ) );
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
                'name' => __('Ontex box full width', 'text-domain'),
                'base' => 'vc_infobox_ontex_box_full_width',
                'description' => __('Show box full width', 'text-domain'), 
                'category' => __('Ontex Elements', 'text-domain'),   
                'icon' => get_template_directory_uri().'/vc-elements/img/compass.png',            
                'params' => array(   

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Title', 'text-domain' ),
                        'param_name' => 'title',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Title', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),


                    array(
                        'type' => 'textarea',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Text', 'text-domain' ),
                        'param_name' => 'text',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Title', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),

                    array(
                        'type' => 'vc_link',
                        'holder' => 'div',
                        'class' => 'text-class',
                        'heading' => __( 'Url', 'text-domain' ),
                        'param_name' => 'url',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Text', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),

                    array(
                        'type' => 'attach_image',
                        'holder' => 'div',
                        'class' => 'text-class',
                        'heading' => __( 'Background image', 'text-domain' ),
                        'param_name' => 'background_image',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Text', 'text-domain' ),
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
        global $test_theme;

        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'title'   => '',
                    'text'   => '',
                    'url'   => '',
                    'background_image'   => '',
                ), 
                $atts
            )
        );
         
        // Fill $html var with data
        //Link construct 
        $url = ($url=='||') ? '' : $url;
        $url = vc_build_link( $url );        
        
        $a_link = $url['url'];
        $a_title = ($url['title'] == '') ? '' : 'title="'.$url['title'].'"';
        $a_target = ($url['target'] == '') ? '' : 'target="'.$url['target'].'"';
        //Anchor Tag
        $button = $a_link ? '<a class="btn btn-md btn-play" href="'.$a_link. '" '.$a_title.' '.$a_target.'></a>' : '';
        ?>
        <div class="row">
            <div class="box-full-width " style="background:url(<?php echo wp_get_attachment_url($background_image) ?>);background-repeat: no-repeat; background-size: cover;width: 100%;">
                <div class="content">
                    <h2><?php echo $title; ?></h2>
                    <br>
                    <p><?php echo $text; ?></p>                  
                    <?php //echo $button; ?>
                </div>
            </div>
        </div>
          <?php

         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcOntexBoxFullWidth(); 