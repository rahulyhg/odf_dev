<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital_Factory
 */
/**/
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<div class="row">
		<div class="col-md-12">
			<?php digital_factory_post_thumbnail(); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<header class="entry-header">
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;

				if ( 'post' === get_post_type() ) :
					?>
					<div class="entry-meta">
						<?php
						digital_factory_posted_on();
						digital_factory_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<footer class="entry-footer">
				<?php digital_factory_entry_footer(); ?>
			</footer><!-- .entry-footer -->

			<div class="entry-content <?php if(is_front_page() || is_home()){ echo "no-margin-top";}?>">
				<?php
				the_content( sprintf(
					wp_kses(
						// translators: %s: Name of current post. Only visible to screen readers 
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'digital-factory' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'digital-factory' ),
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->

