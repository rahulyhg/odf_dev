<?php 
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcOntexSliderHomePage extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'vc_infobox_ontex_slider_home_page', array( $this, 'vc_infobox_html' ) );
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
                'name' => __('Ontex slider home page', 'text-domain'),
                'base' => 'vc_infobox_ontex_slider_home_page',
                'description' => __('Show the slider of home page', 'text-domain'), 
                'category' => __('Ontex Elements', 'text-domain'),   
                'icon' => get_template_directory_uri().'/vc-elements/img/Ontex_Hub_blue.png',            
                'params' => array(   

                    array(
                        'type' => 'dropdown',
                        'holder' => 'div',
                        'class' => 'title-class',
                        'heading' => __( 'Show Slider', 'text-domain' ),
                        'param_name' => 'show_show',
                        'value' => array(
                            __( 'Yes',  "my-text-domain"  ) => 'true',
                            __( 'No',  "my-text-domain"  ) => 'false',
                        ),
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
        global $test_theme;

        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'show_show'   => 'false',
                ), 
                $atts
            )
        );
         
        // Fill $html var with data

        ?>
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <div class="row top-box">
                <div class="col-md-6 bg-cl-site content-box-left"></div>
                <div class="col-md-6 carousel-content-buttons bg-cl-site">
                    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 bg-cl-site">
                    <div class="carousel-inner">
                        <?php foreach($test_theme['themeslider'] as $key=>$temp){ ?>
                            <div class="carousel-item <?php if($key==1) echo 'active' ?> bg-cl-site">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img class="img-carrousel" src="<?php echo $temp['url'] ?>" />
                                    </div>
                                    <div class="col-md-5 text-left content-carrousel">
                                      <h5><?php echo $temp['title'] ?></h5><br>
                                      <p><?php echo $temp['description'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 bg-cl-site box-bottom-radious-right"></div>
                <div class="col-md-6 bg-cl-site content-box-right"></div>
            </div>
          </div>
          <?php

         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcOntexSliderHomePage(); 