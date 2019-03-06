<?php
class Texo_Term_Meta {

	public function __construct($texo_name) {

		if ( is_admin() ) {

			add_action( $texo_name.'_add_form_fields',  array( $this, 'create_screen_fields'), 10, 1 );
			add_action( $texo_name.'_edit_form_fields', array( $this, 'edit_screen_fields' ),  10, 2 );

			add_action( 'created_'.$texo_name, array( $this, 'save_data' ), 10, 1 );
			add_action( 'edited_'.$texo_name,  array( $this, 'save_data' ), 10, 1 );

			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
			add_action( 'admin_footer',          array( $this, 'add_admin_js' )        );

		}

	}

	public function create_screen_fields( $taxonomy ) {

		// Set default values.
		$product_model = '';


		echo '<div class="form-field term-product_model-wrap">';  
		echo '	<label for="product_model">' . __( 'Product Theme Model', 'text_domain' ) . '</label>';
		echo '	<select id="product_model" name="product_model">';
		// echo '		<option value="model-default" ' . selected( $product_model, 'model-default', false ) . '> ' . __( 'Default', 'text_domain' ) . '</option>';
		echo '		<option value="model-1" ' . selected( $product_model, 'model-1', false ) . '> ' . __( 'Model 1', 'text_domain' ) . '</option>';
		echo '		<option value="model-2" ' . selected( $product_model, 'model-2', false ) . '> ' . __( 'Model 2', 'text_domain' ) . '</option>';
		echo '	</select>';
		echo '	<p class="description">' . __( 'The HTML tag used to show the title.', 'text_domain' ) . '</p>';
		echo '</div>';

	
	}

	public function edit_screen_fields( $term, $taxonomy ) {

			$product_model = get_term_meta( $term->term_id, 'product_model', true );


		// Set default values.

		if( empty( $product_model ) ) $product_model = '';
  

		echo '<tr class="form-field term-product_model-wrap">';
		echo '<th scope="row">';
		echo '	<label for="product_model">' . __( 'Product Theme Model', 'text_domain' ) . '</label>';
		echo '</th>';  
		echo '<td>';
		echo '	<select id="product_model" name="product_model">';     
		// echo '		<option value="model-default" ' . selected( $product_model, 'model-default', false ) . '> ' . __( 'Default', 'text_domain' ) . '</option>';
		echo '		<option value="model-1" ' . selected( $product_model, 'model-1', false ) . '> ' . __( 'Model 1', 'text_domain' ) . '</option>';
		echo '		<option value="model-2" ' . selected( $product_model, 'model-2', false ) . '> ' . __( 'Model 2', 'text_domain' ) . '</option>';
		echo '	</select>';
		echo '	<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '</td>';
		echo '</tr>';


	}

	public function save_data( $term_id ) {

		// Sanitize user input.

		$product_model = isset( $_POST[ 'product_model' ] ) ? $_POST[ 'product_model' ] : '';
		
		// Update the meta field in the database.
		update_term_meta( $term_id, 'product_model', $product_model );

	}

	public function load_scripts_styles() {

		

	}

	public function add_admin_js() {

		// Print js only once per page
	

	}

}

new Texo_Term_Meta("product_cat");