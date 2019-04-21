<?php /* translators: On the Views admin screen. */ ?>
<div class="template-description">
	<p>
		<?php
		if ( isset( $template['config']['description'] ) && $template['config']['description'] ) {
			echo wp_kses_post( $template['config']['description'] );
		} else {
			esc_html_e( 'no description', 'strong-testimonials' );
		}
		?>
	</p>
	<div class="options">
		<div>
			<?php if ( ! isset( $template['config']['options'] ) || ! is_array( $template['config']['options'] ) ) : ?>
				<span><?php esc_html_e( 'No options', 'strong-testimonials' ); ?></span>
			<?php else : ?>
				<span><?php esc_html_e( 'Options', 'strong-testimonials' ); ?></span>

				<?php foreach ( $template['config']['options'] as $option ) : ?>
					<span>
					<?php
					$name = sprintf( 'view[data][template_settings][%s][%s]', $key, $option->name );
					$id   = $key . '-' . $option->name;
					switch ( $option->type ) {
						case 'select':
							// Get default if not set
							if ( ! isset( $view['template_settings'][ $key ][ $option->name ] ) ) {
								$view['template_settings'][ $key ][ $option->name ] = $option->default;
							}

							if ( $option->label ) {
								printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $option->label ) );
							}

							printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );

							foreach ( $option->values as $value ) {
								$selected = selected( $value->value, $view['template_settings'][ $key ][ $option->name ], false );
								printf( '<option value="%s" %s>%s</option>', esc_attr( $value->value ), esc_attr( $selected ), esc_html( $value->description ) );
							}

							echo '</select>';
							break;

						case 'radio':
							if ( ! isset( $view['template_settings'][ $key ][ $option->name ] ) ) {
								$view['template_settings'][ $key ][ $option->name ] = $option->default;
							}

							foreach ( $option->values as $value ) {
								$checked = checked( $value->value, $view['template_settings'][ $key ][ $option->name ], false );
								printf( '<input type="radio" id="%s" name="%s" value="%s" %s>', esc_attr( $id ), esc_attr( $name ), esc_attr( $value->value ), esc_attr( $checked ) );
								printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $value->description ) );
							}
							break;

						case 'checkbox':
							/** This breaks checkboxes: */
							//if ( ! isset( $view['template_settings'][ $option->name ] ) ) {
							//   $view['template_settings'][ $option->name ] = $option->default;
							//}

							$checked = checked( true, $view['template_settings'][ $key ][ $option->name ], false );
							printf( '<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $id ), esc_attr( $name ), esc_attr( $checked ) );
							printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $option->label ) );
							break;

						default:
					}
					?>
					</span>
				<?php endforeach; ?>

			<?php endif; ?>
		</div>
	</div>
</div>
