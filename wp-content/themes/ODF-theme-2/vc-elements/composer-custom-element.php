<?php 
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcInfoBox extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'vc_infobox', array( $this, 'vc_infobox_html' ) );
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
                'name' => __('Counter', 'text-domain'),
                'base' => 'vc_infobox',
                'description' => __('Counter circle chart', 'text-domain'), 
                'category' => __('My Custom Elements', 'text-domain'),   
                'icon' => get_template_directory_uri().'/vc-elements/img/icons8-galerie-64.png',            
                'params' => array(   
                         
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Id', 'text-domain' ),
                        'param_name' => 'id',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Title', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),  

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Pourcentage', 'text-domain' ),
                        'param_name' => 'percent',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Title', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),  
                     
                    array(
                        'type' => 'textarea',
                        'holder' => 'div',
                        'class' => 'text-class',
                        'heading' => __( 'Titre', 'text-domain' ),
                        'param_name' => 'title',
                        'value' => __( '', 'text-domain' ),
                        //'description' => __( 'Box Text', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Custom Group',
                    ),
                    array(
                        'type' => 'textarea',
                        'holder' => 'div',
                        'class' => 'text-class',
                        'heading' => __( 'Tooltip Détail', 'text-domain' ),
                        'param_name' => 'tooltip_detail',
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
         
        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'id'   => '',
                    'percent' => '',
                    'title' => '',
                    'tooltip_detail' => '',
                ), 
                $atts
            )
        );
         
        // Fill $html var with data
        $html = '
            <div class="col-counter">
            <div id="' . $id . '" class="my-progress-bar">
                <div class="first-canvas"></div>
                <div class="outer-canvas"></div>
                <div class="inner-canvas"></div>
            </div>
            <div style="display:none" class="percent-counter">' . $percent . '</div>'; 
        if(trim($tooltip_detail)==""){
            $html .= '<div class="title-counter">' . $title . '</div>';
        }else{
            $html .= '<div class="title-counter"><span class="info">' . $title . '<span class="more-info"><u>' . $title . ' :</u> ' . $tooltip_detail . '</span></span></div>';
        }
        $html .= '</div>';  
        
           
         
        return $html;
         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcInfoBox(); 