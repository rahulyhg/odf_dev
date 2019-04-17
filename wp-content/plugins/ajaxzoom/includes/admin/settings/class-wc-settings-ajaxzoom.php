<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Settings_Ajaxzoom' ) ) :

/**
 * WC_Settings_Ajaxzoom.
 */
class WC_Settings_Ajaxzoom extends WC_Settings_Page
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{

		$this->id = 'ajaxzoom';
		$this->label = __( 'AJAX-ZOOM', 'woocommerce' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections()
	{
		Ajaxzoom::settings_data();
		$sections = Ajaxzoom::$categories;
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Set sections desctiptions
	 * 
	 * @return array
	 */
	public function get_sections_title()
	{
		$descr_arr = array();
		$box_start = '<div class="updated inline" style="padding: 10px; border-left-color: #00a0d2">';
		$box_end = '</div>';

		$descr_arr['plugin_settings'] = '
			'.$box_start.'
				<ul class="az-ulsection-descr">
					<li>AJAX-ZOOM is a multipurpose library for displaying (high resolution) images and 360°/3D spins. <br>
						This WooCommerce module integrates only one particular implementation (example) from AJAX-ZOOM library into WooCommerce. <br>
						In raw form this example can be found here: <a href="http://www.ajax-zoom.com/examples/example32.php">http://www.ajax-zoom.com/examples/example32.php</a> <br>
						There you will also find some subtle details about the options which you can configure below. These options mainly refer to this one implementation / example. 
					</li>
					<li>
						However AJAX-ZOOM has many other options which can be set manually in /wp-content/plugins/ajaxzoom/axZm/zoomConfigCustom.inc.php after<br>
						<code>elseif ($_GET[\'example\'] == \'mouseOverExtension360Ver5\')</code>
						<br>"mouseOverExtension360Ver5" can be changed or adjusted depending on theme enabled in general settings (images360example, example, exampleFancyboxFullscreen options).
					</li>
					<li>Depending on the template used you might need to adjust options marked red - <i class="az-icon-hand"></i>
					</li>
					<li>Other useful / most common options are marked yellow - <i class="az-icon-gear"></i>
					</li>
				</ul>
			'.$box_end.'
		';

		$descr_arr['general_settings'] = '';
		$descr_arr['product_tour'] = $box_start.'Product tour can be optionally created for each 360 / 3D'.$box_end;
		$descr_arr['fullscreen_gallery'] = '';
		$descr_arr['mouseover'] = '';
		$descr_arr['video_settings'] = '';

		$descr_arr['license'] = '
			'.$box_start.'
			License is not needed to test AJAX-ZOOM. If you have an issue with getting AJAX-ZOOM work, please <a href="http://www.ajax-zoom.com/index.php?cid=contact">contact</a> AJAX-ZOOM support! 
			You will get the reply latest within 24 hours, usually much earlier. <br>
			By the way: if some day you decide to switch from WooCommerce to some other shop system like Prestashop or Magento, you will be able to take AJAX-ZOOM with you without additional costs. <br>
			License terms and price list can be found <a href="http://www.ajax-zoom.com/index.php?cid=download">here</a>.
			'.$box_end.'
		';

		$descr_arr['reset'] = '
			'.$box_start.'
			Perform several actions regarding AJAX-ZOOM plugin for WooCommerce
			'.$box_end.'
		';

		$sections = array();

		foreach ( $descr_arr as $k => $v ) {
			$sections[$k] = __( str_replace(array("\r", "\n"), '', $descr_arr[$k]), 'ajaxzoom' );
		}

		return $sections;
	}

	/**
	 * Output the settings.
	 */
	public function output()
	{
		global $current_section;
		$settings = '';

		if (!isset($_GET['section'])) {
			$current_section = 'plugin_settings';
		}

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		if (!isset($_GET['section'])) {
			$current_section = 'plugin_settings';
		}

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' )
	{
		$settings = apply_filters( "ajaxzoom_{$current_section}_settings", $this->get_data($current_section));
		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_data( $category )
	{
		$result = array();
		$data = $this->settings_data();
		$result[] = $this->section_start( $category );

		foreach ( $data as $key => $item ) {
			if ( !isset( $item['category'] ) ) {
				$item['category'] = '';
			}

			$item['key'] = $key;

			if ( $item['category'] == $category ) {
				$result[] = $this->format_data( $item );
			}
		}

		$result[] = $this->section_end( $category );

		return $result;
	}

	public function section_start( $category )
	{

		$sections = $this->get_sections();
		$dections_descr = $this->get_sections_title();

		return array(
			'title' => $sections[ $category ],
			'type' => 'title',
			'id' => 'ajaxzoom_' . $category . '_options',
			'desc' => isset( $dections_descr[ $category ] ) ? $dections_descr[ $category ] : ''
		);
	}

	public function section_end( $category )
	{
		return array(
			'type' => 'sectionend',
			'id' => 'ajaxzoom_' . $category . '_options'
		);
	}

	public function convert_options( $opt )
	{
		$ret = array();
		if ( is_array($opt) ) {
			foreach ($opt as $k => $v) {
				$ret[$v[0]] = $v[1];
			}
		}

		return $ret;
	}

	public function format_data( $item )
	{
		if ( empty( $item['type'] ) ) {
			$item['type'] = '';
		}

		if ( isset( $item['isJsObject'] ) && $item['isJsObject'] == true) {
			$item['comment'] .= '<br><span class="az-attention-js">' 
			. __( 'Attention: you are editing JavaScript object! Errors will lead to AJAX-ZOOM not working properly.', 'ajaxzoom' ) . '</span>';
		}

		if ( isset( $item['isJsArray'] ) && $item['isJsArray'] == true) {
			$item['comment'] .= '<br><span class="az-attention-js">' 
			. __( 'Attention: you are editing JavaScript array! Errors will lead to AJAX-ZOOM not working properly.', 'ajaxzoom' ) . '</span>';
		}

		switch ( $item['type'] ) {
			case 'ajaxzoom_licenses':
				$r = array(
					'type' => 'ajaxzoom_licenses',
				);
				break;

			case 'ajaxzoom_resetoptions':
				$r = array(
					'type' => 'ajaxzoom_resetoptions',
				);
				break;

			case 'ajaxzoom_themesettings':
				$r = array(
					'type' => 'ajaxzoom_themesettings',
				);
				break;

			case 'textarea':
				$r = array(
					'title' => $item['title'],
					'desc' => $item['comment'],
					'desc_tip' => false,
					'id' => $item['key'],
					'class' => 'ajaxzoom-textarea',
					'css' => 'width:100%; height: 165px;',
					'type' => 'textarea',
					'default' => $item['default']
				);
				break;

			case 'switch':
				$r = array(
					'title' => $item['title'],
					'desc' => $item['comment'],
					'id' => $item['key'],
					'class' => 'ajaxzoom-switch',
					'default' => $item['default'],
					'type' => 'select',
					'options' => array(
						'true' => __( 'Yes', 'ajaxzoom' ),
						'false' => __( 'No', 'ajaxzoom' ),
					),
					'autoload' => isset( $item['autoload'] ) ? $item['autoload'] : false,
					'desc_tip' => isset( $item['desc_tip'] ) ? $item['desc_tip'] : false
				);
				break;

			case 'select':
				$r = array(
					'title' => $item['title'],
					'desc' => $item['comment'],
					'id' => $item['key'],
					'class' => 'wc-enhanced-select ajaxzoom-select',
					'default' => $item['default'],
					'type' => 'select',
					'options' => $this->convert_options($item['values']),
					'autoload' => isset( $item['autoload'] ) ? $item['autoload'] : false,
					'desc_tip' => isset( $item['desc_tip'] ) ? $item['desc_tip'] : false
				);
				break;

			default:
				$r = array(
					'title' => $item['title'],
					'desc' => $item['comment'],
					'id' => $item['key'],
					'type' => 'text',
					'default' => $item['default'],
					'class' => 'ajaxzoom-text',
					'css' => 'width:100%;',
					'autoload' => isset( $item['autoload'] ) ? $item['autoload'] : false,
					'desc_tip' => isset( $item['desc_tip'] ) ? $item['desc_tip'] : false
				);
				break;
		}

		if ( isset($item['useful']) && $item['useful'] == true ){
			$r['class'] .= ' ajaxzoom-useful';
		}

		if ( isset($item['important']) && $item['important'] == true ){
			$r['class'] .= ' ajaxzoom-important';
		}

		return $r;
	}

	public function settings_data()
	{
		return Ajaxzoom::settings_data();
	}
}

endif;

return new WC_Settings_Ajaxzoom();
