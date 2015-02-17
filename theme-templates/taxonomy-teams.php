<?php
/**
 * The template for displaying Team Archive pages using the Team Rosters plugin.
 * This will create a 'gallery view' of the team.
 *
  *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *-------------------------------------------------------------------------*/
 
	get_header(); 
	
	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	// Set the roster table format.  
	if ( $_GET['format'] == '' ) {
		$format = $options['roster_type'];
		//echo '<p>format from options: ' . $format . '</p>';
	} else {
		$format = $_GET['format'];
		//echo '<p>format from url: ' . $format . '</p>';
	}
	
	// Get the right settings for the format
	$settings = mstw_tr_set_fields_by_format( $format );
	
	//echo '<p>format from set_fields_by_format: ' . $settings['roster_type'] . '</p>';
	
	
	//echo '<h2>REVISED OPTIONS</h2>';
	$options = wp_parse_args( $settings, $options );
	//print_r( $options );
	
	//echo '<p>revised format: ' . $options['roster_type'] . '</p>';
	//$roster_type = $options['roster_type'];
	
	// figure out the team name - for the title (if shown) and for team-based styles
	$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
	$team_slug = $uri_array[sizeof( $uri_array )-2];
	$term = get_term_by( 'slug', $team_slug, 'mstw_tr_team' );
	$team_name .= $term->name;
	?>
	
	<section id="primary">
	<div id="content-player-gallery" role="main" >

	<header class="page-header page-header-<?php echo $team_slug ?>">
		<?php echo '<h1 class="team-head-title team-head-title-' . $team_slug . '">' . $team_name . '</h1>'; ?>
	</header>

	<?php /* Start the Loop */ 
	// set the player photo size based on admin settings, if any
	$sp_image_width = $options['sp_image_width'];
	$sp_image_height = $options['sp_image_height'];
	
	$img_width = ( $sp_image_width == '' ) ? 150 : $sp_image_width;
	$img_height = ( $sp_image_height == '' ) ? 150 : $sp_image_height;
	
	// Set the sort order	
	switch ( $options['sort_order'] ) {
		case'numeric':
			$sort_key = '_mstw_tr_number';
			$order_by = 'meta_value_num';
			break;
		case 'alpha-first':
			$sort_key = '_mstw_tr_first_name';
			$order_by = 'meta_value';
			break;
		default: // alpha by last
			$sort_key = '_mstw_tr_last_name';
			$order_by = 'meta_value';
			break;
	}
	
	// Get the team roster		
	$posts = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'mstw_tr_player',
							  'mstw_tr_team' => $team_slug, 
							  'orderby' => $order_by, 
							  'meta_key' => $sort_key,
							  'order' => 'ASC' 
							));	
	
	$output = mstw_tr_build_gallery( $team_slug, $posts, $options, $format );
	
	echo $output;
	?>

	</div><!-- #content -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>