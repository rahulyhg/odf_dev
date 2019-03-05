<?php 
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcBlockTextImage extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'vc_infobox_block_image_text', array( $this, 'vc_infobox_html' ) );
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
                'name' => __('Ontex block image text', 'text-domain'),
                'base' => 'vc_infobox_block_image_text',
                'description' => __('Insérer l\'image, le lien et le texte correspendant', 'text-domain'), 
                'category' => __('Ontex Elements', 'text-domain'),   
                'icon' => get_template_directory_uri().'/vc-elements/img/3.png',            
                'params' => array(   

                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Texte', 'text-domain' ),
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
                        'heading' => __( 'Image link', 'text-domain' ),
                        'param_name' => 'image_link',
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
        // global $wpdb;

        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'text'   => '',
                    'url' => '',
                    'image_link' => '',
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
        $button = $a_link ? '<a class="btn btn-md btn-black" href="'.$a_link. '" '.$a_title.' '.$a_target.'>'.$text.'</a>' : '';

        $html = '<div class="news news1">
                    <div class="news-box-full-width">
                        <div class="content">
                            <a href="#">
                            '.wp_get_attachment_image($image_link, 'large').'
                            </a>
                        </div>
                        <div class="footer">
                            <a href="'.$a_link. '" '.$a_title.' '.$a_target.'>'.$text.'</a>
                            <a class="footer_img_right" href="'.$a_link. '" '.$a_title.' '.$a_target.'">
                                <img class="footer_img_right" src="'.get_template_directory_uri().'/images/btn-theme.png">
                            </a>
                        </div>
                    </div>
                </div>
        ';
        
         
        return $html;
         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcBlockTextImage(); 