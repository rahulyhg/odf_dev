<?php /* translators: In the view editor. */ ?>
<th>
	<?php esc_html_e( 'Behavior', 'strong-testimonials' ); ?>
</th>
<td>

	<div class="row">
		<div class="inline inline-middle">
			<input type="checkbox" id="view-auto_start" name="view[data][slideshow_settings][auto_start]" value="0" <?php checked( $view['slideshow_settings']['auto_start'] ); ?> class="checkbox">
			<label for="view-auto_start">
				<?php echo esc_html_x( 'Start automatically', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>
	</div>

	<div class="row">
		<div class="inline inline-middle">
			<input type="checkbox" id="view-auto_hover" name="view[data][slideshow_settings][auto_hover]" value="0" <?php checked( $view['slideshow_settings']['auto_hover'] ); ?> class="checkbox">
			<label for="view-auto_hover">
				<?php echo esc_html_x( 'Pause on hover', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>
	</div>

	<div class="row">
		<div class="inline inline-middle">
			<input type="checkbox" id="view-stop_auto_on_click"
				   name="view[data][slideshow_settings][stop_auto_on_click]" value="0"
				<?php checked( $view['slideshow_settings']['stop_auto_on_click'] ); ?> class="checkbox">
			<label for="view-stop_auto_on_click">
				<?php echo esc_html_x( 'Stop on interaction', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>
		<div class="inline inline-middle">
			<p class="description"><?php esc_html_e( 'Recommended if using navigation.', 'strong-testimonials' ); ?></p>
		</div>
	</div>

	<?php
	if ( $view['slideshow_settings']['adapt_height'] ) {
		$height = 'dynamic';
	} else {
		$height = 'static';
	}
	?>
	<div class="row">
		<div class="row-inner">

			<div class="inline">
				<label for="view-slideshow_height">
				<select id="view-slideshow_height" name="view[data][slideshow_settings][height]" class="if selectgroup">
					<?php foreach ( $view_options['slideshow_height'] as $key => $type ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php selected( $height, $key ); ?>>
							<?php echo esc_html( $type ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				</label>
			</div>

			<div class="inline then then_slideshow_height then_dynamic then_not_static" style="display: none;">
				<label for="view-adapt_height_speed">
					<?php esc_html_e( 'Duration', 'strong-testimonials' ); ?>
				</label>
				<input type="number" id="view-adapt_height_speed" class="input-incremental" name="view[data][slideshow_settings][adapt_height_speed]" min="0" step="0.1" value="<?php echo esc_attr( $view['slideshow_settings']['adapt_height_speed'] ); ?>" size="3"/>
				<?php echo esc_html_x( 'seconds', 'time setting', 'strong-testimonials' ); ?>
			</div>

			<div class="inline then then_slideshow_height then_not_dynamic then_static" style="display: none;">
				<input type="checkbox" id="view-stretch" name="view[data][slideshow_settings][stretch]" value="1" <?php checked( $view['slideshow_settings']['stretch'] ); ?> class="checkbox">
				<label for="view-stretch">
					<?php esc_html_e( 'Stretch slides vertically', 'strong-testimonials' ); ?>
				</label>

				<div class="inline description">
					<a href="#tab-panel-wpmtst-help-stretch" class="open-help-tab"><?php esc_html_e( 'Help' ); ?></a>
				</div>
			</div>

		</div>
	</div>

	<div class="row tall">
		<p class="description"><?php esc_html_e( 'The slideshow will pause if the browser window becomes inactive.', 'strong-testimonials' ); ?></p>
	</div>

</td>
