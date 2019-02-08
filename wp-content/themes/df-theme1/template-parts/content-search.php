<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Digital_Factory
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row row_blog">
		<div class="col-md-6 left_side"
			<?php if ( has_post_thumbnail() && !empty(get_the_post_thumbnail_url()) ) { ?>
				style="	background-image: url(<?php echo get_the_post_thumbnail_url(); ?>);background-repeat: no-repeat;background-position: center;background-size: contain;background-color: rgb(255, 255, 255);"
			<?php }else{ ?>
				style="	background-image: url(<?php echo get_template_directory_uri(); ?>/images/default_post_thumbnail.png);background-position-y: 50%;background-color: rgb(255, 255, 255);"
			<?php } ?> >
			<?php // digital_factory_post_thumbnail(); ?>
		</div>

		<div class="col-md-6 right_side">
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
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
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		</div>
	</div>



</article><!-- #post-<?php the_ID(); ?> -->
