<?php
/*
Plugin Name: Homepage Settings
Plugin URI: http://www.inboundnow.com/
Description: Adds additional functionality to the big bang theme.
Author: David Wells
Author URI: http://www.inboundnow.com
*/
// Specify Hooks/Filters
register_activation_hook(__FILE__, 'add_defaults_fn');
add_action('admin_init', 'sampleoptions_init_fn' );
add_action('admin_menu', 'sampleoptions_add_page_fn');
// Define default option settings
function add_defaults_fn() {
	$tmp = get_option('plugin_options');
    if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
		$arr = array("dropdown1"=>"Orange", "text_area" => "Space to put a lot of information here!", "text_string" => "Some sample text", "pass_string" => "123456", "chkbox1" => "", "chkbox2" => "on", "option_set1" => "Triangle");
		update_option('plugin_options', $arr);
	}
}
// Register our settings. Add the settings section, and settings fields
function sampleoptions_init_fn(){
	register_setting('plugin_options', 'plugin_options', 'plugin_options_validate' );
	add_settings_section('main_section', 'Main Settings', 'section_text_fn', __FILE__);
	add_settings_field('plugin_text_string', 'Text Input', 'setting_string_fn', __FILE__, 'main_section');
	add_settings_field('plugin_text_pass', 'Password Text Input', 'setting_pass_fn', __FILE__, 'main_section');
	add_settings_field('plugin_textarea_string', 'Large Textbox!', 'setting_textarea_fn', __FILE__, 'main_section');
	add_settings_field('plugin_chk2', 'A Checkbox', 'setting_chk2_fn', __FILE__, 'main_section');
	add_settings_field('radio_buttons', 'Select Shape', 'setting_radio_fn', __FILE__, 'main_section');
	add_settings_field('radio_buttons', 'Home Page Boxes', 'setting_visual_fn', __FILE__, 'main_section');
	add_settings_field('drop_down1', 'Select Color', 'setting_dropdown_fn', __FILE__, 'main_section');
	add_settings_field(
		'plugin_chk1', 
		'Restore Defaults Upon Reactivation?', 
		'setting_chk1_fn', 
		__FILE__, 
		'main_section'
	);

	/*add_settings_field( // Option 1
        'header_background', // Option ID
        'Logo URL', // Label
        'logo_fn', // !important - This is where the args go!
        __FILE__, // Page it will be displayed (General Settings)
        'main_section', // Name of our section
        array( // The $args
            'header_background' // Should match Option ID
        )
    );
	add_settings_field( // Option 1
        'favicon_url', // Option ID
        'Favicon URL', // Label
        'favicon_fn', // !important - This is where the args go!
        __FILE__, // Page it will be displayed (General Settings)
        'main_section', // Name of our section
        array( // The $args
            'favicon_url' // Should match Option ID
        )
    );

    register_setting('general', 'logo_url', 'esc_attr');
    register_setting('general', 'favicon_url', 'esc_attr');*/
}
// Add sub page to the Settings Menu
function sampleoptions_add_page_fn() {
	// add optiont to main settings panel
	add_options_page('Extra Settings', 'Theme options', 'administrator', __FILE__, 'options_page_fn');
}
// ************************************************************************************************************
// Callback functions
// Init plugin options to white list our options
// Section HTML, displayed before the first option





function logo_fn($args)
{
    $option = get_option($args[0]);
    echo '<input type="text" class="settings_input_media_url" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
}

function favicon_fn($args)
{
    $option = get_option($args[0]);
    echo '<input type="text" class="settings_input_media_url" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
}












function  section_text_fn() {
	echo '<p>Below are some fields of different option controls.</p>';
}
// DROP-DOWN-BOX - Name: plugin_options[dropdown1]
function  setting_dropdown_fn() {
	$options = get_option('plugin_options');
	$items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");
	echo "<select id='drop_down1' name='plugin_options[dropdown1]'>";
	foreach($items as $item) {
		$selected = ($options['dropdown1']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}
// TEXTAREA - Name: plugin_options[text_area]
function setting_textarea_fn() {
	$options = get_option('plugin_options');
	echo "<textarea id='plugin_textarea_string' name='plugin_options[text_area]' rows='7' cols='50' type='textarea'>{$options['text_area']}</textarea>";
}
// TEXTBOX - Name: plugin_options[text_string]
function setting_string_fn() {
	$options = get_option('plugin_options');
	echo "<input id='plugin_text_string' name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}
// PASSWORD-TEXTBOX - Name: plugin_options[pass_string]
function setting_pass_fn() {
	$options = get_option('plugin_options');
	echo "<input id='plugin_text_pass' name='plugin_options[pass_string]' size='40' type='password' value='{$options['pass_string']}' />";
}
// CHECKBOX - Name: plugin_options[chkbox1]
function setting_chk1_fn() {
	$options = get_option('plugin_options');
	if($options['chkbox1']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk1' name='plugin_options[chkbox1]' type='checkbox' />";
}
// CHECKBOX - Name: plugin_options[chkbox2]
function setting_chk2_fn() {
	$options = get_option('plugin_options');
	if($options['chkbox2']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk2' name='plugin_options[chkbox2]' type='checkbox' />";
}
// RADIO-BUTTON - Name: plugin_options[option_set1]
function setting_radio_fn() {
	$options = get_option('plugin_options');
	$items = array("Square", "Triangle", "Circle");
	foreach($items as $item) {
		$checked = ($options['option_set1']==$item) ? ' checked="checked" ' : '';
		echo "<label><input ".$checked." value='$item' name='plugin_options[option_set1]' type='radio' /> $item</label><br />";
	}
}
// WYSIWYG Visual Editor - Name: plugin_options[textarea_one]
function setting_visual_fn() {
	$options = get_option('plugin_options');
	$args = array("textarea_name" => "plugin_options[textarea_one]");
	wp_editor( $options['textarea_one'], "plugin_options[textarea_one]", $args );
	
	// Add another text box
	$options = get_option('plugin_options');
	$args = array("textarea_name" => "plugin_options[textarea_two]");
	wp_editor( $options['textarea_two'], "plugin_options[textarea_two]", $args );				
}		
// Sanitize and validate input. Accepts an array, return a sanitized array.
function wpet_validate_options($input) {
	// Sanitize textarea input (strip html tags, and escape characters)
	//$input['textarea_one'] = wp_filter_nohtml_kses($input['textarea_one']);
	//$input['textarea_two'] = wp_filter_nohtml_kses($input['textarea_two']);
	//$input['textarea_three'] = wp_filter_nohtml_kses($input['textarea_three']);
	return $input;
}
// Display the admin options page
function options_page_fn() {
	wp_enqueue_media();
?>
	<script>
		/* background configuration via amdin*/
	        jQuery('#header_background').click(function(e) {
	            e.preventDefault();

	            var custom_uploader = wp.media({
	                title: 'Custom Image',
	                button: {
	                    text: 'Upload Image'
	                },
	                multiple: false  // Set this to true to allow multiple files to be selected
	            })
	            .on('select', function() {
	                var attachment = custom_uploader.state().get('selection').first().toJSON();
	                jQuery('#header_background').attr('value', attachment.url);
	                // $j('.header_logo_url').val(attachment.url);

	            })
	            .open();
	        });
	        jQuery('#favicon_url').click(function(e) {
	            e.preventDefault();

	            var custom_uploader_favicon = wp.media({
	                title: 'Custom Image',
	                button: {
	                    text: 'Upload Image'
	                },
	                multiple: false  // Set this to true to allow multiple files to be selected
	            })
	            .on('select', function() {
	                var attachment = custom_uploader_favicon.state().get('selection').first().toJSON();
	                jQuery('#favicon_url').attr('value', attachment.url);
	            })
	            .open();
	        });
	    /* background configuration via amdin*/
	</script>
	<style type="text/css" media="screen">
		.settings_input_media_url {
		    width: 50%;
		}
	</style>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Theme options</h2>
		<form action="options.php" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('plugin-name-action_' . "yep"); 
			?>
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
<?php
}
// Validate user data for some/all of your input fields
function plugin_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);	
	return $input; // return validated input
}





