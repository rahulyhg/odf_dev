<?php
/**
 * Digital Factory functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Digital_Factory
 */

add_shortcode('jcarousel_slider','jcarousel_slider');
function jcarousel_slider(){


    echo '
        <div class="div_jcarousel">
            <div class="jcarousel">
                <ul>
                    <li style="background-image: url('.get_template_directory_uri().'/images/news3.png)">
                    <h3>Titre</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </li>
                    <li><img src="'.get_template_directory_uri().'/images/news3.png" width="600" height="400" alt=""></li>
                    <li><img src="'.get_template_directory_uri().'/images/news3.png" width="600" height="400" alt=""></li>
                    <li><img src="'.get_template_directory_uri().'/images/news3.png" width="600" height="400" alt=""></li>
                    <li><img src="'.get_template_directory_uri().'/images/news3.png" width="600" height="400" alt=""></li>
                    <li><img src="'.get_template_directory_uri().'/images/news3.png" width="600" height="400" alt=""></li>
                </ul>
            </div>
            
            <p class="jcarousel-pagination">
                
            </p>
        </div>
    ';
}


if ( ! file_exists( get_template_directory() . '/class/class-wp-bootstrap-navwalker.php' ) ) {
	// file does not exist... return an error.
	return new WP_Error( 'class-wp-bootstrap-navwalker-missing', __( 'It appears the class-wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
} else {
	// file exists... require it.
	require_once get_template_directory() . '/class/class-wp-bootstrap-navwalker.php';
}

if ( ! function_exists( 'digital_factory_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function digital_factory_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Digital Factory, use a find and replace
		 * to change 'digital-factory' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'df-theme1', get_template_directory_uri() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'top-menu' => esc_html__( 'Primary', 'digital-factory' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'digital_factory_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'digital_factory_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function digital_factory_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'digital_factory_content_width', 640 );
}
add_action( 'after_setup_theme', 'digital_factory_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function digital_factory_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'digital-factory' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'digital-factory' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'digital_factory_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function digital_factory_scripts() {
	wp_enqueue_style( 'digital-factory-style', get_stylesheet_uri() );

	wp_enqueue_script( 'digital-factory-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'digital-factory-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'digital_factory_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/theme_options/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/theme_options/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/theme_options/test_theme/sample-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/theme_options/test_theme/sample-config.php' );
}

/**
 * 
 *
 */


//show block last post
function ontex_post($atts){
    $news_atts = shortcode_atts( array(
    'tag' => 'quality',
    'template' =>'1'
    ), $atts );
    $tag = str_replace(' ','-',$news_atts['tag']);
    $args = array(
    'posts_per_page'=>1,
    'numberposts'     => 1,
    'offset'          => 0,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_status'     => 'publish'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query -> have_posts()) : $query -> the_post();
            ob_start();
			?>
			<div class="content-box">
				<div class="row">
					<div class="col-md-6 content-box-left ">
						<!-- <img class="image-box" src="<?php echo get_template_directory_uri() ?>/images/bebe.png" /> -->
						<?php the_post_thumbnail('full'); ?>
					</div>
					<div class="col-md-6 content-box-right bg-cl-site">
						<div class="box-text-content">
							<h2><?php the_title() ?></h2>
							<br>
							<?php the_excerpt() ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="btn-go"></a>
						</div>
					</div>
				</div>
			</div>
			<?php
        endwhile;
    endif;
    return ob_get_clean();
}

function ontex_post_sans_bordure($atts){
    $news_atts = shortcode_atts( array(
    'tag' => 'quality',
    'template' =>'1'
    ), $atts );
    $tag = str_replace(' ','-',$news_atts['tag']);
    $args = array(
    'posts_per_page'=>1,
    'numberposts'     => 1,
    'offset'          => 0,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_status'     => 'publish'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query -> have_posts()) : $query -> the_post();
            ob_start();
			?>
			<div class="content-box">
				<h2><?php the_title() ?></h2>
				<div class="row">
					<div class="col-md-7">
						<?php the_excerpt() ?>
					</div>
					<div class="col-md-5">
						<img src="<?php the_post_thumbnail('full'); ?>" />
					</div>
				</div>
			</div>
			<?php
        endwhile;
    endif;
    return ob_get_clean();
}
function register_my_shortcodes(){
    add_shortcode('ontex_post', 'ontex_post'); //show last post
    add_shortcode('ontex_post_sans_bordure', 'ontex_post_sans_bordure'); //show last post
}
add_action('init', 'register_my_shortcodes');




// Before VC Init
add_action( 'vc_before_init', 'vc_before_init_actions' );
function vc_before_init_actions() {
    // Require new custom Element
    require_once( get_template_directory() . '/includes_vc_elements.php' ); 
     
}


//show latest 4 news
add_shortcode('ontex_latest_4_news', 'ontex_latest_4_news'); //show latest 4 news
function ontex_latest_4_news($atts)
{

    $news_atts = shortcode_atts(array(
        'template' => '1'
    ), $atts);

    $args = array(
        'posts_per_page' => 4,
        'numberposts' => 4,
        'offset' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC',
        //'include'         => ,
        //'exclude'         => ,
        //'meta_key'        => ,
        //'meta_value'      => ,
        'post_type' => 'post',
        //'post_mime_type'  => ,
        //'post_parent'     => ,
        'post_status' => 'publish',
        'suppress_filters' => false
    );
    $query = new WP_Query($args);


    if ($query->have_posts()) :

        $i = 0;

        ob_start();

        while ($query->have_posts() && $i < 4) : $query->the_post();
            if ( $i % 2 == 0 ) {
                get_template_part('template-parts/post', 'part1');
            }

            get_template_part('template-parts/post', 'part2');

            $ontextype = get_post_meta(get_the_ID(), 'ontex-type', true);
            switch ($ontextype) {
                case '1' :
                    get_template_part('template-parts/post', 'model1');
                    break;
                case '2' :
                    get_template_part('template-parts/post', 'model2');
                    break;
                case '3' :
                    get_template_part('template-parts/post', 'model3');
                    break;
                case '4' :
                    get_template_part('template-parts/post', 'model4');
                    break;

                default :
                    get_template_part('template-parts/post', 'model1');
                    break;
            }

            get_template_part('template-parts/post', 'part3');

            if ($i % 2 != 0) {
                get_template_part('template-parts/post', 'part4');
            }

            $i++;


        endwhile;
    endif;
    return ob_get_clean();
}


//show ontex price on homepage
add_shortcode('ontex_price', 'ontex_price');//[ontex-price params]
function ontex_price()
{

    $service_url = 'http://ir.euroinvestor.com/ServiceEngine/api/xml/reply/RequestStockDataBundle?ApiVersion=1&CustomerKey=ontex&SolutionID=2260&instrumentTypes=Listing';
    // $xml = simplexml_load_file($service_url) or die("feed not loading");
    $xml = simplexml_load_file($service_url);
    $priceData = $xml->Data->InstrumentEntry;
    $output = '<div class="stock-content">
            <div class="stock-value">€' . number_format((float)$priceData->LastTradePrice, 2, '.', '') . '</div>
            <div class="stock-date">' . $priceData->Change . ' (' . number_format((float)$priceData->ChangePercent, 2, '.', '') . '%) ' . date('H:i', strtotime($priceData->Timestamp)) . ' PM CEST</div>';
    if (explode('/', get_site_url())[3] == 'mx') {
        $html_text = 'Información accionistas';
    } elseif (explode('/', get_site_url())[3] == 'br') {
        $html_text = 'Informação do acionista';
    } else {
        $html_text = 'Investors information';
    }
    $output .= '<a href="http://www.ontexglobal.com/investors" class="stakeholder-link">' . $html_text . '</a></div>';
    return $output;
}

/* backend text colored with custom colors in wysiwyg editor */
    function my_mce4_options($init) {

        $custom_colours = '
            "000000", "Black",
            "FFFFFF", "White",
            "2799D6" , "Baby Care",
            "e73182" , "Feminine Care",
            "89bd23" , "Mature Markets Retail",
            "7b2584" , "Healthcare",
            "f18904" , "Growth Markets",
            "009a83" , "Americas Retail",
            "022169" , "dark blue",
            "FFCD00" , "yellow",
            "17A5A2" , "teal "
        ';

        // build colour grid default+custom colors
        $init['textcolor_map'] = '['.$custom_colours.']';

        // change the number of rows in the grid if the number of colors changes
        // 8 swatches per row
        $init['textcolor_rows'] = 5;
        $init['textcolor_cols'] = 5;

        return $init;
    }
    add_filter('tiny_mce_before_init', 'my_mce4_options');
/* backend text colored with custom colors in wysiwyg editor */


// Register Custom Post Type
function custom_post_type() {

    // Advices
    $labels = array(
        'name'                  => _x( 'Advices', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Advice', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Advices', 'text_domain' ),
        'name_admin_bar'        => __( 'Advices', 'text_domain' ),
        'archives'              => __( 'Advice Archives', 'text_domain' ),
        'attributes'            => __( 'Advice Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Advice:', 'text_domain' ),
        'all_items'             => __( 'All Advices', 'text_domain' ),
        'add_new_item'          => __( 'Add New Advice', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Advice', 'text_domain' ),
        'edit_item'             => __( 'Edit Advice', 'text_domain' ),
        'update_item'           => __( 'Update Advice', 'text_domain' ),
        'view_item'             => __( 'View Advice', 'text_domain' ),
        'view_items'            => __( 'View Advices', 'text_domain' ),
        'search_items'          => __( 'Search Advice', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into advice', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this advice', 'text_domain' ),
        'items_list'            => __( 'Advices list', 'text_domain' ),
        'items_list_navigation' => __( 'Advices list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter advices list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Advice', 'text_domain' ),
        'description'           => __( 'Advice Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies' => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'advice', $args );
    register_taxonomy( 'advice_category', // register custom taxonomy - quote category
        'advices',
        array( 'hierarchical' => true,
            'label' => __( 'advices categories' )
        )
    );
    register_taxonomy( 'advice_tag', // register custom taxonomy - quote tag
        'advices',
        array( 'hierarchical' => false,
            'label' => __( 'advices tags' )
        )
    );



    // products details
    $labels_product_details = array(
        'name'                  => _x( 'Products Details', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Product Details', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Products Details', 'text_domain' ),
        'name_admin_bar'        => __( 'Products Details', 'text_domain' ),
        'archives'              => __( 'Product Details Archives', 'text_domain' ),
        'attributes'            => __( 'Product Details Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Product Details:', 'text_domain' ),
        'all_items'             => __( 'All Products Details', 'text_domain' ),
        'add_new_item'          => __( 'Add New Product Details', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Product Details', 'text_domain' ),
        'edit_item'             => __( 'Edit Product Details', 'text_domain' ),
        'update_item'           => __( 'Update Product Details', 'text_domain' ),
        'view_item'             => __( 'View Product Details', 'text_domain' ),
        'view_items'            => __( 'View Products Details', 'text_domain' ),
        'search_items'          => __( 'Search Product Details', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into product details', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this product details', 'text_domain' ),
        'items_list'            => __( 'Products Details list', 'text_domain' ),
        'items_list_navigation' => __( 'Products Details list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter products details list', 'text_domain' ),
    );
    $args_product_details = array(
        'label'                 => __( 'Product Details', 'text_domain' ),
        'description'           => __( 'Product Details Description', 'text_domain' ),
        'labels'                => $labels_product_details,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies' => array( 'product_details_category', 'product_details_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'product_details', $args_product_details );
    register_taxonomy( 'product_details_category', // register custom taxonomy - quote category
        'products_details',
        array( 'hierarchical' => true,
            'label' => __( 'products_details categories' )
        )
    );
    register_taxonomy( 'product_details_tag', // register custom taxonomy - quote tag
        'products_details',
        array( 'hierarchical' => false,
            'label' => __( 'products_details tags' )
        )
    );

    // Our brands
    $labels_brand = array(
        'name'                  => _x( 'Brands', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Brand', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Brands', 'text_domain' ),
        'name_admin_bar'        => __( 'Brands', 'text_domain' ),
        'archives'              => __( 'Brand Archives', 'text_domain' ),
        'attributes'            => __( 'Brand Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Brand:', 'text_domain' ),
        'all_items'             => __( 'All Brands', 'text_domain' ),
        'add_new_item'          => __( 'Add New Brand', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Brand', 'text_domain' ),
        'edit_item'             => __( 'Edit Brand', 'text_domain' ),
        'update_item'           => __( 'Update Brand', 'text_domain' ),
        'view_item'             => __( 'View Brand', 'text_domain' ),
        'view_items'            => __( 'View Brands', 'text_domain' ),
        'search_items'          => __( 'Search Brand', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into brand', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this brand', 'text_domain' ),
        'items_list'            => __( 'Brands list', 'text_domain' ),
        'items_list_navigation' => __( 'Brands list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter brands list', 'text_domain' ),
    );
    $args_brand = array(
        'label'                 => __( 'Brand', 'text_domain' ),
        'description'           => __( 'Brand Description', 'text_domain' ),
        'labels'                => $labels_brand,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies' => array( 'brand_category', 'brand_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'brand', $args_brand );
    register_taxonomy( 'brand_category', // register custom taxonomy - quote category
        'brands',
        array( 'hierarchical' => true,
            'label' => __( 'brands categories' )
        )
    );
    register_taxonomy( 'brand_tag', // register custom taxonomy - quote tag
        'brands',
        array( 'hierarchical' => false,
            'label' => __( 'brands tags' )
        )
    );

    // Our applications
    $labels_application = array(
        'name'                  => _x( 'Applications', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Application', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Applications', 'text_domain' ),
        'name_admin_bar'        => __( 'Applications', 'text_domain' ),
        'archives'              => __( 'Application Archives', 'text_domain' ),
        'attributes'            => __( 'Application Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Application:', 'text_domain' ),
        'all_items'             => __( 'All Applications', 'text_domain' ),
        'add_new_item'          => __( 'Add New Application', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Application', 'text_domain' ),
        'edit_item'             => __( 'Edit Application', 'text_domain' ),
        'update_item'           => __( 'Update Application', 'text_domain' ),
        'view_item'             => __( 'View Application', 'text_domain' ),
        'view_items'            => __( 'View Applications', 'text_domain' ),
        'search_items'          => __( 'Search Application', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into application', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this application', 'text_domain' ),
        'items_list'            => __( 'Applications list', 'text_domain' ),
        'items_list_navigation' => __( 'Applications list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter applications list', 'text_domain' ),
    );
    $args_application = array(
        'label'                 => __( 'Application', 'text_domain' ),
        'description'           => __( 'Application Description', 'text_domain' ),
        'labels'                => $labels_application,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies' => array( 'application_category', 'application_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'application', $args_application );
    register_taxonomy( 'application_category', // register custom taxonomy - quote category
        'applications',
        array( 'hierarchical' => true,
            'label' => __( 'applications categories' )
        )
    );
    register_taxonomy( 'application_tag', // register custom taxonomy - quote tag
        'applications',
        array( 'hierarchical' => false,
            'label' => __( 'applications tags' )
        )
    );


}
add_action( 'init', 'custom_post_type', 0 );

 

