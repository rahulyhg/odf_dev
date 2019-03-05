<?php /* translators: In the view editor. */ ?>
<th>
	<?php esc_html_e( 'Transition', 'strong-testimonials' ); ?>
</th>
<td>
	<div class="row">

		<div class="inline inline-middle">
			<label for="view-pause">
				<?php echo esc_html_x( 'Show slides for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-pause" class="input-incremental" name="view[data][slideshow_settings][pause]" min=".1" step=".1" value="<?php echo esc_attr( $view['slideshow_settings']['pause'] ); ?>" size="3"/>
			<?php echo esc_html_x( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

		<div class="inline inline-middle then then_slider_type then_show_single then_not_show_multiple fast" style="display: none;">
			<label for="view-effect">
				<?php esc_html_e( 'then', 'strong-testimonials' ); ?>
			</label>
			<select id="view-effect" name="view[data][slideshow_settings][effect]" class="if selectnot">
				<?php foreach ( $view_options['slideshow_effect'] as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"
						<?php selected( $view['slideshow_settings']['effect'], $key ); ?>
						<?php echo 'none' == $key ? 'class="trip"' : ''; ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="inline inline-middle then then_slider_type then_not_show_single then_show_multiple fast" style="display: none;">
			<?php esc_html_e( 'then', 'strong-testimonials' ); ?> <?php echo esc_html_x( 'scroll horizontally', 'slideshow transition option', 'strong-testimonials' ); ?>
		</div>

		<div class="inline inline-middle then then_effect then_none">
			<label for="view-speed">
				<?php esc_html_e( 'for', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-speed" class="input-incremental" name="view[data][slideshow_settings][speed]" min=".1" step=".1" value="<?php echo esc_attr( $view['slideshow_settings']['speed'] ); ?>" size="3"/>
			<?php echo esc_html_x( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

	</div>
</td>
