<?php
/**
 * The template for displaying Team Archive pages using the Team Rosters plugin.
 * This will create a 'gallery view' of the team.
 *
 * CHANGE LOG
 * 20130203-MAO:
 *	(1) Added photo/image sizes from admin settings
 *	(2) Added hide-weight from admin settings. Also hide if weight field is empty
 *	(3) Added formats for baseball ?format=format-string now works in URL.
 *
 * 20130621-MAO:
 *	(1) Added changes to support specific team formatting.
 *	(2) Added extensive changes to support custom display of data fields.
 *
 * 20130910-MAO:
 *	(1) Called new mstw_tr_build_gallery() utility. Gallery shortcode and page
 *		use the same code.
 *
 */
 
	if ( !function_exists( 'mstw_tr_set_fields_by_format' ) ) {
		//echo '<p> mstw_text_ctrl does not exist. </p>';
		//echo '<p> path:' . WP_CONTENT_DIR . '/plugins/team-rosters/includes/mstw-tr-utility-functions.php</p>';
		require_once  WP_CONTENT_DIR . '/plugins/team-rosters/includes/mstw-tr-utility-functions.php';
	};
 
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
	$term = get_term_by( 'slug', $team_slug, 'teams' );
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
							  'post_type' => 'player',
							  'teams' => $team_slug, 
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