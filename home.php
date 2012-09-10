<?php
/**
 * The template for homepage.
 *
 * @package utms-base
 * @since utms-base 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title">Homepage</h1>
						</header><!-- .entry-header -->
					
						<div class="entry-content">
							<h1>This is the homepage Title</h1>
							<p>Homepage Content</p>
						</div><!-- .entry-content -->
					</article><!-- #post-<?php the_ID(); ?> -->

					<?php comments_template( '', true ); ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>