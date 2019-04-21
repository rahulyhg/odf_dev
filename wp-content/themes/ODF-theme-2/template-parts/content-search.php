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
		<?php if ( 'product_details' === get_post_type() ) :
			$class = 'entry-summary';
			$class = esc_attr( $class );
			if ( has_excerpt() || is_search() ) : ?>
				<div class="<?php echo $class; ?>">
					<p>
						<?php echo wp_trim_words(get_the_content(), 30, '...'); ?>

						<div class="entry-content">
					        <?php 
					        /*
					         *  Query posts for a relationship value.
					         *  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
					         */

					         $product_caracteristics_multiples = get_posts(array(
					                     'post_type' => 'product',
					                     'meta_query' => array(
					                      array(
					                            'key' => 'product_caracteristics_multiple', // name of custom field
					                            'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
					                            'compare' => 'LIKE'
					                                )
					                            )
					                        ));

					                        ?>
					        <?php if( $product_caracteristics_multiples ): ?>
					             <ul>
					             <?php foreach( $product_caracteristics_multiples as $pcm ): ?>
					                <li>
					                   <a href="<?php echo get_permalink( $pcm->ID ); ?>" target="_blanc">
					                     <?php echo get_the_title( $pcm->ID ); ?>
					                   </a>
					                </li>
					             <?php endforeach; ?>
					            </ul>
					      <?php endif; ?>

						</div>
					</p>

				</div><!-- .<?php echo $class; ?> -->
			<?php endif;

		else : ?>
			<?php twentysixteen_excerpt(); ?>
		<?php endif; ?>
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

