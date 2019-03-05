<?php
/**
 * Admin notices
 */


/**
 * Dismiss persistent notices.
 *
 * @since 2.29.0
 */
function wpmtst_dismiss_notice_ajax() {
	if ( ! isset( $_POST['key'] ) || ! $_POST['key'] ) {
		echo 0;
		wp_die();
	}

	check_ajax_referer( 'wpmtst-admin', 'nonce' );
	wpmtst_delete_admin_notice( $_POST['key'] );
	wp_die();
}

add_action( 'wp_ajax_wpmtst_dismiss_notice', 'wpmtst_dismiss_notice_ajax' );

/**
 * Print admin notices.
 *
 * @since 2.24.0
 */
function wpmtst_admin_notices() {
	$notices = get_option( 'wpmtst_admin_notices' );
	if ( ! $notices ) {
		return;
	}

	foreach ( $notices as $key => $notice ) {
		$message = apply_filters( 'wpmtst_admin_notice', '', $key );
		if ( $message ) {
			echo wp_kses_post( $message );
		}
		if ( ! $notice['persist'] ) {
			wpmtst_delete_admin_notice( $key );
		}
	}
}
add_action( 'admin_notices', 'wpmtst_admin_notices' );


/**
 * Return specific admin notice text.
 *
 * @since 2.28.5
 * @param string $html
 * @param $key
 *
 * @return string
 */
function wpmtst_admin_notice_text( $html = '', $key, $persist = false ) {

	switch ( $key ) {
		case 'defaults-restored':
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php echo esc_attr( $key ); ?>">
				<p>
					<?php esc_html_e( 'Defaults restored.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'fields-saved':
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php echo esc_attr( $key ); ?>">
				<p>
					<?php esc_html_e( 'Fields saved.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'changes-cancelled':
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php echo esc_attr( $key ); ?>">
				<p>
					<?php esc_html_e( 'Changes cancelled.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'captcha-options-changed':
			$tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
				),
			);
			//$settings_url  = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form#captcha-section' );
			$settings_url  = admin_url( '?action=captcha-options-changed' );
			$settings_link = sprintf( wp_kses( __( 'Please check your <a href="%1$s">%2$s</a>.', 'strong-testimonials' ), $tags ), esc_url( $settings_url ), __( 'settings', 'strong-testimonials' ) );

			ob_start();
			?>
			<div class="wpmtst notice notice-warning is-dismissible" data-key="<?php echo esc_attr( $key ); ?>">
				<p>
					<?php echo wp_kses_post( _e( 'Captcha options have changed in <strong>Strong Testimonials</strong>.', 'strong-testimonials' ) ); ?>
					<?php echo wp_kses_post( $settings_link ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		default:
			// nothing
	}

	return $html;
}
add_filter( 'wpmtst_admin_notice', 'wpmtst_admin_notice_text', 10, 2 );


/**
 * Add admin notice to queue.
 *
 * @since 2.24.0
 *
 * @param $key
 * @param $persist
 */
function wpmtst_add_admin_notice( $key, $persist = false ) {
	$notices         = get_option( 'wpmtst_admin_notices', array() );
	$notices[ $key ] = array( 'persist' => $persist );
	update_option( 'wpmtst_admin_notices', $notices );
}


/**
 * Delete admin notice from queue.
 *
 * @since 2.24.0
 *
 * @param $key
 */
function wpmtst_delete_admin_notice( $key ) {
	$notices = get_option( 'wpmtst_admin_notices', array() );
	unset( $notices[ $key ] );
	update_option( 'wpmtst_admin_notices', $notices );
}


/**
 * Automatically dismiss specific notices when settings are saved.
 *
 * @since 2.29.0
 * @param $option
 * @param $old_value
 * @param $value
 */
function wpmtst_auto_dismiss_notices( $option, $old_value, $value ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( $screen && 'options' == $screen->base ) {
		if ( 'wpmtst_form_options' == $option ) {
			$notices = get_option( 'wpmtst_admin_notices', array() );
			if ( isset( $notices['captcha-options-changed'] ) ) {
				unset( $notices['captcha-options-changed'] );
				update_option( 'wpmtst_admin_notices', $notices );
			}
		}
	}
}
add_action( 'update_option', 'wpmtst_auto_dismiss_notices', 10, 3 );


function wpmtst_admin_feedback_notice() {

	$screen = get_current_screen();
	if ( $screen->id !== 'edit-wpm-testimonial' ) {
		return;
	}

	?>
	<div class="notice wpmtst-notice">
		<img src="<?php echo esc_url( WPMTST_ADMIN_URL ); ?>/img/logo-long.svg" width="400"/>
		<p><?php esc_html_e( 'Do you enjoy using Strong Testimonials? Please take a minute to suggest a feature or tell us what you think.', 'strong-testimonials' ); ?></p>
		<a class="button" target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLScch0AchtnzxJsSrjUcW9ypcr1fZ9r-vyk3emEp8Sv47brb2g/viewform"><?php esc_html_e( 'Submit Feedback', 'strong-testimonials' ); ?></a>
	</div>
	<?php
}
add_action( 'admin_notices', 'wpmtst_admin_feedback_notice' );
