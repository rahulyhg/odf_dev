<?php
/**
 * The template for displaying search results pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<!-- <header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentysixteen' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
			</header> --><!-- .page-header -->

			<div data-vc-full-width="false" data-vc-full-width-init="false" data-vc-stretch-content="true" class="vc_row wpb_row vc_row-fluid">
			   <div class="wpb_column vc_column_container vc_col-sm-12">
			      <div class="vc_column-inner">
			         <div class="wpb_wrapper">
			            <div class="wpb_text_column wpb_content_element  vc_custom_1550230179944000">
			               <div class="wpb_wrapper">
			                  <h1 class="search-result-text" style="text-align: center;"><span style="color: #ffffff;">Search results</span></h1>
			               </div>
			            </div>
			         </div>
			      </div>
			   </div>
			</div>
			<br><br><br>
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
	<br><br><br>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
