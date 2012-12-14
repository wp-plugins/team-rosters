<?php
/**
 * MSTW Team Rosters Template for displaying all single player bios.
 *
 * @package Twenty_Eleven
 * @subpackage Team_Rosters
 * @since Team Rosters 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous">
						<?php $back =$_SERVER['HTTP_REFERER'];
						if( isset( $back ) && $back != '' ) { 
							echo '<a href="' . $back . '"><span class="meta-nav">&larr;</span>Return to roster</a>';
						}?>
							
							
						<!--
						<?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
						-->
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content', 'single-player' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>