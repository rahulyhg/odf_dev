<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php esc_html_e( 'Custom Fields', 'strong-testimonials' ); ?>
</th>
<td colspan="2">

	<div id="client-section-table">

		<div id="custom-field-list2" class="fields">
			<?php
			if ( isset( $view['client_section'] ) ) {
				foreach ( $view['client_section'] as $key => $field ) {
					wpmtst_view_field_inputs( $key, $field );
				}
			}
			?>
		</div>

	</div>

	<div id="add-field-bar" class="is-below">
		<input id="add-field" type="button" name="add-field" value="<?php esc_attr_e( 'Add Field', 'strong-testimonials' ); ?>" class="button-secondary" />
	</div>

</td>
