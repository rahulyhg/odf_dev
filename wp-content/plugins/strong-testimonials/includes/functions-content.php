<?php
/**
 * Content functions.
 */

/**
 * Based on the_content().
 *
 * @param null $more_link_text
 * @param bool $strip_teaser
 *
 * @return string
 */
function wpmtst_the_content_filtered( $more_link_text = null, $strip_teaser = false ) {
	$content = get_the_content( $more_link_text, $strip_teaser );
	$content = apply_filters( 'wpmtst_the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}

/**
 * Based on the_excerpt().
 *
 * @since 2.26.0
 */
function wpmtst_the_excerpt_filtered() {
	return apply_filters( 'wpmtst_the_excerpt', wpmtst_get_the_excerpt() );
}

/**
 * Based on get_the_excerpt().
 *
 * @since 2.26.0
 * @param null $post
 *
 * @return string
 */
function wpmtst_get_the_excerpt( $post = null ) {
	$post = get_post( $post );
	if ( empty( $post ) ) {
		return '';
	}

	if ( post_password_required( $post ) ) {
		return __( 'There is no excerpt because this is a protected post.' );
	}

	/**
	 * Filters the retrieved post excerpt.
	 *
	 * @param string $post_excerpt The post excerpt.
	 * @param WP_Post $post Post object.
	 */
	return apply_filters( 'wpmtst_get_the_excerpt', $post->post_excerpt, $post );
}

/**
 * Force bypass of the manual excerpt.
 *
 * @since 2.26.0
 * @param $text
 *
 * @return string
 */
function wpmtst_bypass_excerpt( $text ) {
	return '';
}

/**
 * Based on wp_trim_excerpt(). On wpmtst_get_the_excerpt hook.
 *
 * @since 2.26.0
 * @param string $excerpt The manual excerpt.
 *
 * @return string
 */
function wpmtst_trim_excerpt( $excerpt = '' ) {
	$raw_excerpt = $excerpt;

	/**
	 * Filter hybrid value here to allow individual overrides.
	 */
	$hybrid = apply_filters( 'wpmtst_is_hybrid_content', false );

	if ( '' == $excerpt ) {

		$text = wpmtst_get_the_prepared_text();

		// Create excerpt if post has no manual excerpt.
		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$excerpt_more   = apply_filters( 'excerpt_more', ' [&hellip;]' );
		$excerpt        = wpmtst_trim_words( $text, $excerpt_length, $excerpt_more, $hybrid );

	} elseif ( $hybrid ) {

		$text = wpmtst_get_the_prepared_text( true );

		// Append hybrid content as hidden span to the manual excerpt.
		$excerpt .= wpmtst_trim_words( $text, 0, '', true );

	}

	/**
	 * Filters the trimmed excerpt string.
	 *
	 * @param string $text        The trimmed text.
	 * @param string $raw_excerpt The text prior to trimming.
	 */
	return apply_filters( 'wpmtst_trim_excerpt', $excerpt, $raw_excerpt );
}

/**
 * Prepare the post content.
 *
 * @param bool $hybrid
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_get_the_prepared_text( $hybrid = false ) {
	$text = get_the_content( '' );
	if ( ! $hybrid ) {
		$text = strip_shortcodes( $text );
	}
	$text = apply_filters( 'wpmtst_the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );

	return $text;
}

/**
 * Construct the "Read more" link (both automatic and manual).
 *
 * @since 2.27.0 Filters on URL and full link.
 *
 * @return string
 */
function wpmtst_get_excerpt_more_link() {
	$url = apply_filters( 'wpmtst_read_more_post_url', get_permalink(), WPMST()->atts() );

	$link_text = sprintf(
		'%s<span class="screen-reader-text"> "%s"</span>',
		apply_filters( 'wpmtst_read_more_post_link_text', WPMST()->atts( 'more_post_text' ), WPMST()->atts() ),
		get_the_title()
	);

	$link_class = apply_filters( 'wpmtst_read_more_post_link_class', 'readmore' );

	if ( apply_filters( 'wpmtst_is_hybrid_content', false ) ) {
		// no href
		$link = sprintf(
			'<a aria-expanded="false" aria-controls="more-%1$d" class="%2s readmore-toggle"><span class="readmore-text" data-more-text="%4$s" data-less-text="%5$s">%3$s</span></a>',
			get_the_ID(), // 1
			$link_class,  // 2
			$link_text,   // 3
			WPMST()->atts( 'more_post_text' ), // 4
			WPMST()->atts( 'less_post_text' )  // 5
		);
	} else {
		$link = sprintf( '<a href="%s" class="%s">%s</a>', esc_url( $url ), $link_class, $link_text );
	}

	return apply_filters( 'wpmtst_read_more_post_link', $link );
}

/**
 * Based on wp_trim_words().
 *
 * @param $text
 * @param int $num_words
 * @param null $more
 * @param bool $hybrid
 *
 * @return string
 */
function wpmtst_trim_words( $text, $num_words = 55, $more = null, $hybrid = false ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	$text = wp_strip_all_tags( $text );

	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep         = '';
	} else {
		$offset      = $hybrid ? 0 : $num_words + 1;
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $offset, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		if ( $hybrid ) {
			$text = wpmtst_assemble_hybrid( $words_array, $num_words, $sep, $more );
		} else {
			$text = wpmtst_assemble_excerpt( $words_array, $sep, $more );
		}
	} else {
		$text = implode( $sep, $words_array );
	}

	return $text;
}

/**
 * Assemble excerpt from trimmed array.
 *
 * @param $words_array
 * @param $sep
 * @param $more
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_assemble_excerpt( $words_array, $sep, $more ) {
	array_pop( $words_array );
	$text = implode( $sep, $words_array );

	return $text . $more;
}

/**
 * Assemble excerpt + rest of content in hidden span.
 *
 * @param $words_array
 * @param $num_words
 * @param $sep
 * @param $more
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_assemble_hybrid( $words_array, $num_words, $sep, $more ) {
	$ellipsis = wpmtst_ellipsis();
	if ( $ellipsis ) {
		$ellipsis = '<span class="ellipsis">' . $ellipsis . ' </span>';
		/* ! This space is important:                        ^       */
	}

	$first_half  = implode( $sep, array_slice( $words_array, 0, $num_words ) );
	$second_half = implode( $sep, array_slice( $words_array, $num_words ) );

	$wrap_open  = '<span class="readmore-content animated" id="more-' . get_the_ID() . '" hidden> ';
	$wrap_close = ' </span>';

	return $first_half . $ellipsis . ' ' . $wrap_open . $second_half . $wrap_close . $more;
	/* ! This space is important:     ^                                                  */
}
