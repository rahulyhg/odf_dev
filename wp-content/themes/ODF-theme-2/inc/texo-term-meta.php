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


		echo '<style>';
		echo '  .taxonomy-product_cat .input-group {
				    position: relative;
				    display: -ms-flexbox;
				    display: flex;
				    -ms-flex-wrap: wrap;
				    flex-wrap: wrap;
				    -ms-flex-align: stretch;
				    align-items: stretch;
				    width: 100%;
				    text-align: center;
				}
				.taxonomy-product_cat .select-theme {
				    margin: 0 auto;
				    display: inline-block;
				}
				.taxonomy-product_cat .select-theme p{
					margin-top: 5px;
					margin-bottom: 5px;
				}
				.taxonomy-product_cat [type=radio]:checked + img {
				    outline: 2px solid #f00;
				    padding: 8px;
				}
				.taxonomy-product_cat [type=radio] + img {
				    cursor: pointer;
				    padding: 12px;
				}
				.taxonomy-product_cat [type=radio] {
				    position: absolute;
				    opacity: 0;
				    width: 0;
				    height: 0;
				}';
		echo '</style>'; 
		echo '<tr class="form-field term-product_model-wrap">';
		echo '<th scope="row">';
		echo '	<label for="product_model">' . __( 'Product Theme Model', 'text_domain' ) . '</label>';
		echo '</th>';  
		echo '<td>';
		// echo '	<select id="product_model" name="product_model">';
		// echo '		<option value="model-1-1" ' . selected( $product_model, 'model-1-1', false ) . '> ' . __( 'Theme 1 Model 1', 'text_domain' ) . '</option>';
		// echo '		<option value="model-1" ' . selected( $product_model, 'model-1', false ) . '> ' . __( 'Theme 2 Model 1', 'text_domain' ) . '</option>';
		// echo '		<option value="model-2" ' . checked( $product_model, 'model-2', false ) . '> ' . __( 'Theme 2 Model 2', 'text_domain' ) . '</option>';
		// echo '	</select>';

		echo ' <div class="input-group select-theme-container">';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 1 Model 1', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-1-1" ' . checked( $product_model, 'model-1-1', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_1_model_1.png" width="150">';
        echo '   </label>';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 2 Model 1', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-1" ' . checked( $product_model, 'model-1', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_2_model_1.png" width="150">';
        echo '   </label>';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 2 Model 2', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-2" ' . checked( $product_model, 'model-2', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_2_model_2.png" width="150">';
        echo '   </label>';
        echo ' </div>';

		echo '	<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '</td>';
		echo '</tr>';

	
	}

	public function edit_screen_fields( $term, $taxonomy ) {

			$product_model = get_term_meta( $term->term_id, 'product_model', true );


		// Set default values.

		if( empty( $product_model ) ) $product_model = '';
  

		echo '<style>';
		echo '  .taxonomy-product_cat .input-group {
				    position: relative;
				    display: -ms-flexbox;
				    display: flex;
				    -ms-flex-wrap: wrap;
				    flex-wrap: wrap;
				    -ms-flex-align: stretch;
				    align-items: stretch;
				    width: 100%;
				    text-align: center;
				}
				.taxonomy-product_cat .select-theme {
				    margin: 0 auto;
				    display: inline-block;
				}
				.taxonomy-product_cat .select-theme p{
					margin-top: 5px;
					margin-bottom: 5px;
				}
				.taxonomy-product_cat [type=radio]:checked + img {
				    outline: 2px solid #f00;
				    padding: 8px;
				}
				.taxonomy-product_cat [type=radio] + img {
				    cursor: pointer;
				    padding: 12px;
				}
				.taxonomy-product_cat [type=radio] {
				    position: absolute;
				    opacity: 0;
				    width: 0;
				    height: 0;
				}';
		echo '</style>'; 
		echo '<tr class="form-field term-product_model-wrap">';
		echo '<th scope="row">';
		echo '	<label for="product_model">' . __( 'Product Theme Model', 'text_domain' ) . '</label>';
		echo '</th>';  
		echo '<td>';
		// echo '	<select id="product_model" name="product_model">';
		// echo '		<option value="model-1-1" ' . selected( $product_model, 'model-1-1', false ) . '> ' . __( 'Theme 1 Model 1', 'text_domain' ) . '</option>';
		// echo '		<option value="model-1" ' . selected( $product_model, 'model-1', false ) . '> ' . __( 'Theme 2 Model 1', 'text_domain' ) . '</option>';
		// echo '		<option value="model-2" ' . checked( $product_model, 'model-2', false ) . '> ' . __( 'Theme 2 Model 2', 'text_domain' ) . '</option>';
		// echo '	</select>';

		echo ' <div class="input-group select-theme-container">';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 1 Model 1', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-1-1" ' . checked( $product_model, 'model-1-1', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_1_model_1.png" width="150">';
        echo '   </label>';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 2 Model 1', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-1" ' . checked( $product_model, 'model-1', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_2_model_1.png" width="150">';
        echo '   </label>';
        echo '   <label class="select-theme"> <p>' . __( 'Theme 2 Model 2', 'text_domain' ) . '</p>';
        echo '     <input type="radio" name="product_model" value="model-2" ' . checked( $product_model, 'model-2', false ) . '>';
        echo '     <img src="'.get_stylesheet_directory_uri() . '/images/product_sheet_the_2_model_2.png" width="150">';
        echo '   </label>';
        echo ' </div>';

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