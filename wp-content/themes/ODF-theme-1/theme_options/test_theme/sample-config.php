<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "test_theme";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'test_theme/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    
    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Theme Options', 'redux-framework-demo' ),
        'page_title'           => __( 'Theme Options', 'redux-framework-demo' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => '',
        // 'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // 'menu_icon'            => 'dashicons-layout',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => false,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => false,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.reduxframework.com/',
        'title' => __( 'Documentation', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
        'title' => __( 'Support', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => 'reduxframework.com/extensions',
        'title' => __( 'Extensions', 'redux-framework-demo' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    /*$args['share_icons'][] = array(
        'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/reduxframework',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/redux-framework',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );*/

    // Panel Intro text -> before the form
    /*if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
    }*/

    // Add content after the form.
    /*$args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );*/

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

        // -> START Basic Fields
        /*Redux::setSection( $opt_name, array(
            'title'            => __( 'General', 'redux-framework-demo' ),
            'id'               => 'general',
            'desc'             => __( 'Thbis is general options', 'redux-framework-demo' ),
            'customizer_width' => '400px',
            'icon'             => 'el el-home',
            'fields'           => array(
                array(
                    'id'       => 'th_site_title',
                    'type'     => 'text',
                    'title'    => __('Site Title', 'redux-framework-demo'),
                    'default'  => 'Test Theme'
                ),
                array(
                    'id'       => 'logo-ontex',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Logo image', 'redux-framework-demo' ),
                    'compiler' => 'true',
                    //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                    // 'desc'     => __( 'Basic media uploader with disabled URL input field.', 'redux-framework-demo' ),
                    'subtitle' => __( 'Choose a logo to be displayed', 'redux-framework-demo' ),
                    'default'  => array( 'url' => 'https://s.wordpress.org/style/images/codeispoetry.png' ),
                    //'hint'      => array(
                    //    'title'     => 'Hint Title',
                    //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                    //)
                ),
                array(
                    'id'       => 'favicon-ontex',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Favicon Image', 'redux-framework-demo' ),
                    'compiler' => 'true',
                    //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                    // 'desc'     => __( 'Basic media uploader with disabled URL input field.', 'redux-framework-demo' ),
                    'subtitle' => __( 'Choose a favicon image to be displayed', 'redux-framework-demo' ),
                    'default'  => array( 'url' => 'https://s.wordpress.org/style/images/codeispoetry.png' ),
                    //'hint'      => array(
                    //    'title'     => 'Hint Title',
                    //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                    //)
                ),
                // array(
                //     'id' => 'ontex_color_scheme',
                //     'type' => 'select',
                //     'title' => __('Choose Color Scheme', 'redux-framework-demo'),
                //     'options' => array(
                //         'blue' => 'Blue',
                //         'green' => 'Green',
                //         'pink' => 'Pink',
                //         'yellow' => 'Yellow'
                //     ),
                //     'default' => 'yellow',
                // ),

                array(
                    'id'       => 'ontex-palette-color',
                    'type'     => 'palette',
                    'title'    => __( 'Web site color', 'redux-framework-demo' ),
                    'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                    'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                    'default'  => '#2799D6',
                    'palettes' => array(
                        '#2799D6'  => array(
                            '#Baby Care',
                            '#4fbcbf',
                        ),
                        '#e73182'  => array(
                            '#Feminine Care',
                            '#e73182',
                        ),
                        '#89bd23'  => array(
                            '#Mature Markets Retail',
                            '#89bd23',
                        ),
                        '#7b2584'  => array(
                            '#Healthcare',
                            '#7b2584',
                        ),
                        '#f18904'  => array(
                            '#Growth Markets',
                            '#f18904',
                        ),
                        '#009a83'  => array(
                            '#Americas Retail',
                            '#009a83',
                        ),
                        '#022169'  => array(
                            '#Dark blue',
                            '#022169',
                        ),
                        '#FFCD00'  => array(
                            '#Yellow',
                            '#FFCD00',
                        ),
                        '#17A5A2'  => array(
                            '#Teal',
                            '#17A5A2',
                        ),
                        

                        //     // correct color
                        //     "#2799D6" , "Baby Care",              
                        //     "#e73182" , "Feminine Care",          
                        //     "#89bd23" , "Mature Markets Retail",  
                        //     "#7b2584" , "Healthcare",              
                        //     "#f18904" , "Growth Markets",         
                        //     "#009a83" , "Americas Retail",        
                        //     "#022169" , "dark blue",              
                        //     "#FFCD00" , "yellow",                 
                        //     "#17A5A2" , "teal "                   
                        
                        // '#e74582'  => array(
                        //     'MENA',
                        //     '#e74582',
                        // ),'#7b2584'  => array(
                        //     'Incontinence Care',
                        //     '#7b2584',
                        // ),
                        // 'red'  => array(
                        //     '#ef9a9a',
                        //     '#f44336',
                        //     '#ff1744',
                        // ),
                        // 'pink' => array(
                        //     '#fce4ec',
                        //     '#f06292',
                        //     '#e91e63',
                        //     '#ad1457',
                        //     '#f50057',
                        // ),
                        // 'cyan' => array(
                        //     '#e0f7fa',
                        //     '#80deea',
                        //     '#26c6da',
                        //     '#0097a7',
                        //     '#00e5ff',
                        // ),
                    ),

                ),
                array(
                    'id' => 'ontex_show_breadcrubmbs',
                    'type' => 'switch',
                    'title' => __('Display Breadcrumbs', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id' => 'ontex-toggle-loader',
                    'type' => 'select',
                    'title' => __('Enable/Disable Loader', 'redux-framework-demo'),
                    'options' => array(
                        'enable' => __('Enable', 'redux-framework-demo'),
                        'disable' => __('Disable', 'redux-framework-demo'),
                    ),
                    'default' => 'enable',
                ),
                array(
                    'id' => 'ontex_loader_bg_color',
                    'type' => 'color',
                    'title' => __('Loader Background Color', 'ontex-helper'),
                    'subtitle' => __('Choose Page Loader Background Color.', 'ontex-helper'),
                    'default' => 'white',
                    'validate' => 'color'
                ),
                array(
                    'id' => 'ontex_loading_circle_color',
                    'type' => 'color',
                    'title' => __('Loading Circle Color', 'ontex-helper'),
                    'subtitle' => __('Choose Page Loading Circle Color with image, color, etc.', 'ontex-helper'),
                    'default' => '#000',
                    'validate' => 'color'
                ),
                array(
                    'id' => 'ontex_custom_css',
                    'type' => 'ace_editor',
                    'title' => __('Custom CSS Code', 'redux-framework-demo'),
                    'subtitle' => __('If you have any custom CSS you would like added to the site, please enter it here.', 'redux-framework-demo'),
                    'mode' => 'css',
                    'theme' => 'chrome',
                    'desc' => ''
                ),
                array(
                    'id' => 'ontex_custom_js',
                    'type' => 'ace_editor',
                    'title' => __('Custom JS', 'redux-framework-demo'),
                    'subtitle' => __('Paste your JS code here.', 'redux-framework-demo'),
                    'mode' => 'javascript',
                    'theme' => 'monokai',
                ),
            )
        ) );
        Redux::setSection( $opt_name, array(
            'title'            => __( 'Header', 'redux-framework-demo' ),
            'id'               => 'header_ontex',
            'subsection'       => false,
            'customizer_width' => '450px',
            'icon'             => 'el el-hand-up',
            'fields'           => array(
                array(
                    'id'       => 'header-background',
                    'type'     => 'background',
                    'output'   => array( 'header' ),
                    'compiler' => 'true',
                    'title'    => __( 'Header Background', 'redux-framework-demo' ),
                    'subtitle' => __( 'Header background with image, color, etc.', 'redux-framework-demo' ),
                    //'default'   => '#FFFFFF',
                ),
                array(
                    'id' => 'header-social-check',
                    'type' => 'switch',
                    'title' => __('Show social share in the header', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id'      => 'ontex-select-header',
                    'type'    => 'select_image',
                    'title'   => __( 'Header wave', 'redux-framework-demo' ),
                    'subtitle' => __( 'Select a header waver from the dropdown' ),
                    'options' => array(
                        array(
                            'alt' => 'Wave 1',
                            'img' =>  get_template_directory_uri() . '/images/wave1.png',
                        ),
                        array(
                            'alt' => 'Wave 2',
                            'img' =>  get_template_directory_uri() . '/images/wave2.png',
                        ),
                        array(
                            'alt' => 'Wave 3',
                            'img' =>  get_template_directory_uri() . '/images/wave3.png',
                        ),
                        array(
                            'alt' => 'Wave 4',
                            'img' =>  get_template_directory_uri() . '/images/wave4.png',
                        ),
                        array(
                            'alt' => 'Wave 5',
                            'img' =>  get_template_directory_uri() . '/images/wave5.png',
                        ),
                        array(
                            'alt' => 'Wave 6',
                            'img' =>  get_template_directory_uri() . '/images/wave6.png',
                        ),
                        array(
                            'alt' => 'Wave 7',
                            'img' =>  get_template_directory_uri() . '/images/wave7.png',
                        ),
                    ),
                    'default' =>  get_template_directory_uri() . '/images/wave1.png',
                ),
            )
        ) );
        Redux::setSection( $opt_name, array(
            'title'            => __( 'Footer', 'redux-framework-demo' ),
            'id'               => 'footer_ontex',
            'subsection'       => false,
            'customizer_width' => '450px',
            'icon'             => 'el el-hand-down',
            'fields'           => array(
                array(
                    'id'       => 'opt-media-ontex_footer',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Footer background', 'redux-framework-demo' ),
                    'compiler' => 'true',
                    'default'  => array( 'url' => 'https://s.wordpress.org/style/images/codeispoetry.png' ),
                ),
                array(
                    'id' => 'ontex-footer-logo',
                    'type' => 'media',
                    'url' => true,
                    'title' => __('Footer Logo', 'turbowp-helper'),
                    'compiler' => 'true',
                    'desc' => __('Upload custom logo.', 'turbowp-helper'),
                ),
                array(
                    'id' => 'footer-social-chack',
                    'type' => 'switch',
                    'title' => __('Show social share in the footer', 'redux-framework-demo'),
                    'default' => false,
                ),
            )
        ) );
        Redux::setSection($opt_name, array(
            'title' => __('Blog Settings', 'turbowp-helper'),
            'id' => 'ontex-turbowp-blog-settings',
            'desc' => __('All blog related options are listed here.', 'turbowp-helper'),
            'icon' => 'el el-list-alt',
            'fields' => array(
                array(
                    'id' => 'featured-img',
                    'type' => 'media',
                    'url' => true,
                    'title' => __('Blog Default Featured Image', 'turbowp-helper'),
                    'subtitle' => __('Default image choose for Blog Single page', 'turbowp-helper'),
                    'compiler' => 'true',
                    'desc' => __('Upload Blog Featured Image', 'turbowp-helper')
                ),
            ),
        ));
        Redux::setSection( $opt_name, array(
            'title'            => __( 'Slider', 'redux-framework-demo' ),
            'id'               => 'slider',
            'subsection'       => false,
            'customizer_width' => '450px',
            'icon'             => 'el el-lines',
            'fields'           => array(
                array(
                    'id'        => 'themeslider',
                    'type'      => 'slides',
                    'title'     => __('Add slider', 'redux-framework-demo'),                
                ),
            )
        ) );
        Redux::setSection( $opt_name, array(
            'title'            => __( 'Fonts', 'redux-framework-demo' ),
            'id'               => 'fonts',
            'subsection'       => false,
            'customizer_width' => '450px',
            'icon'             => 'el el-font',
            'fields'           => array(
                array(
                    'id'       => 'opt-typography-body-ontex',
                    'type'     => 'typography',
                    'title'    => __( 'Body Font', 'redux-framework-demo' ),
                    'subtitle' => __( 'Specify the body font properties.', 'redux-framework-demo' ),
                    'google'   => true,
                    'output' => array('h1, h2, h3, h4'),
                    'default'  => array(
                        'color'       => '#dd9933',
                        'font-size'   => '30px',
                        'font-family' => 'Arial,Helvetica,sans-serif',
                        'font-weight' => 'Normal',
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h1-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h1', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h2-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h2', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h3-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h3', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h4-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h4', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h5-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h5', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
                array(
                    'id'          => 'opt-typography-h6-ontex',
                    'type'        => 'typography',
                    'title'       => __( 'Typography h6', 'redux-framework-demo' ),
                    'font-backup' => true,
                    'all_styles'  => true,
                    'output'      => array( '.site-description' ),
                    'compiler'    => array( 'site-description-compiler' ),
                    'units'       => 'px',
                    'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                    'default'     => array(
                        'color'       => '#333',
                        'font-style'  => '700',
                        'font-family' => 'Abel',
                        'google'      => true,
                        'font-size'   => '33px',
                        'line-height' => '40px'
                    ),
                ),
            )
        ) );*/

        Redux::setSection( $opt_name, array(
            'title'            => __( 'Social', 'redux-framework-demo' ),
            'id'               => 'social',
            'subsection'       => false,
            'customizer_width' => '450px',
            'icon'             => 'el el-group-alt',
            'fields'           => array(            
                /*array(
                    'id'       => 'section-start-socials-header-ontex',
                    'type'     => 'section',
                    'title'    => __( 'Social shares section', 'redux-framework-demo' ),
                    // 'subtitle' => __( 'With the "section" field you can upload image header background.', 'redux-framework-demo' ),
                    'indent'   => true, // Indent all options below until the next 'section' option is set.
                ),*/
                // Facebook
                array(
                    'id'       => 'facebook-header-section-start',
                    'type'     => 'section',
                    'title'    => __( 'Facebook', 'redux-framework-demo' ),
                    'indent' => true,
                ),                    
                array(
                    'id' => 'facebook-check-button',
                    'type' => 'switch',
                    'title' => __('Enable Facebook share', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id'       => 'facebook-header-ontex',
                    'type'     => 'text',
                    'required' => array( 'facebook-check-button', '=', '1' ),
                    'title'    => __('Facebook Url', 'redux-framework-demo'),
                ),
                array(
                    'id'     => 'facebook-header-section-end',
                    'type'   => 'section',
                    'indent' => false, // Indent all options below until the next 'section' option is set.
                ),
                // Instagram
                array(
                    'id'       => 'instagram-header-section-start',
                    'type'     => 'section',
                    'title'    => __( 'Instagram', 'redux-framework-demo' ),
                    'indent' => true,
                ), 
                array(
                    'id' => 'instagram-check-button',
                    'type' => 'switch',
                    'title' => __('Enable Instagram share button', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id'       => 'instagram-header-ontex',
                    'type'     => 'text',
                    'required' => array( 'instagram-check-button', '=', '1' ),
                    'title'    => __('Instagram Url', 'redux-framework-demo'),
                ),
                array(
                    'id'     => 'instagram-header-section-end',
                    'type'   => 'section',
                    'indent' => false, // Indent all options below until the next 'section' option is set.
                ),
                // Twitter
                array(
                    'id'       => 'twitter-header-section-start',
                    'type'     => 'section',
                    'title'    => __( 'Twitter', 'redux-framework-demo' ),
                    'indent' => true,
                ), 
                array(
                    'id' => 'twitter-check-button',
                    'type' => 'switch',
                    'title' => __('Enable Twitter share button', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id'       => 'twitter-header-ontex',
                    'type'     => 'text',
                    'required' => array( 'twitter-check-button', '=', '1' ),
                    'title'    => __('Twitter Url', 'redux-framework-demo'),
                ),
                array(
                    'id'     => 'twitter-header-section-end',
                    'type'   => 'section',
                    'indent' => false, // Indent all options below until the next 'section' option is set.
                ),
                // LinkedIn
                array(
                    'id'       => 'linkedin-header-section-start',
                    'type'     => 'section',
                    'title'    => __( 'LinkedIn', 'redux-framework-demo' ),
                    'indent' => true,
                ),
                array(
                    'id' => 'linkedin-check-button',
                    'type' => 'switch',
                    'title' => __('Enable LinkedIn share button', 'redux-framework-demo'),
                    'default' => false,
                ),
                array(
                    'id'       => 'linkedin-header-ontex',
                    'type'     => 'text',
                    'required' => array( 'linkedin-check-button', '=', '1' ),
                    'title'    => __('LinkedIn Url', 'redux-framework-demo'),
                ),
                array(
                    'id'     => 'linkedin-header-section-end',
                    'type'   => 'section',
                    'indent' => false, // Indent all options below until the next 'section' option is set.
                ),
                 /*array(
                    'id'     => 'section-end-socials-footer-ontex',
                    'type'   => 'section',
                    'indent' => false, // Indent all options below until the next 'section' option is set.
                ),
               array(
                    'id'   => 'section-info-socials-footer-ontex',
                    'type' => 'info',
                    'desc' => __( 'And now you can add more fields below and outside of the indent.', 'redux-framework-demo' ),
                ),*/

            )
        ) );











        /*if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
            $section = array(
                'icon'   => 'el el-list-alt',
                'title'  => __( 'Documentation', 'redux-framework-demo' ),
                'fields' => array(
                    array(
                        'id'       => '17',
                        'type'     => 'raw',
                        'markdown' => true,
                        'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                        //'content' => 'Raw content here',
                    ),
                ),
            );
            Redux::setSection( $opt_name, $section );
        }*/

    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */ 
    if ( ! function_exists( 'remove_demo' ) ) {
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }

