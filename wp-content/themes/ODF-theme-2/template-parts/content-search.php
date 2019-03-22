<?php
/**
 * The template part for displaying results in search pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article class="search-result" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	
	
	
	<span class="post-time"><?php the_time('j F Y') ?></span>
	<span class="post-author">By <?php the_author(); ?></span>
	
	
	
	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
	</header><!-- .entry-header -->

	<div class="vc_col-sm-8">
		<a href="<?php the_permalink() ?>"><?php twentysixteen_excerpt(); ?></a>
	</div>
	<div class="vc_col-sm-4">
		<?php twentysixteen_post_thumbnail(); ?>
	</div>

	<?php if ( 'post' === get_post_type() ) : ?>

		<footer class="entry-footer">
			<?php twentysixteen_entry_meta(); ?>
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->

	<?php elseif ( 'product' === get_post_type() ) : ?>

		<footer class="entry-footer">
			<?php twentysixteen_entry_meta(); ?>
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php else : ?>

		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<footer class="entry-footer"><span class="edit-link">',
				'</span></footer><!-- .entry-footer -->'
			);
		?>

	<?php endif; ?>
</article><!-- #post-## -->

