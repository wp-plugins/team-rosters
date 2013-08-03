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
 */
 
	if ( !function_exists( 'mstw_tr_set_fields_by_format' ) ) {
		//echo '<p> mstw_text_ctrl does not exist. </p>';
		//echo '<p> path:' . WP_CONTENT_DIR . '/plugins/team-rosters/includes/mstw-tr-utility-functions.php</p>';
		require_once  WP_CONTENT_DIR . '/plugins/team-rosters/includes/mstw-tr-utility-functions.php';
	};
 
	get_header(); 
	
	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	//$sp_main_text_color = $options['sp_main_text_color'];
	//$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	//$hide_weight = $options['tr_hide_weight'];
	
	// Set the roster table format.  
	if ( $_GET['format'] == '' ) {
		$format = $options['tr_table_default_format'];
	} else {
		$format = $_GET['format'];
	}
	
	// Get the right settings for the format
	$settings = mstw_tr_set_fields_by_format( $format );
	
	//echo '<h2>REVISED OPTIONS</h2>';
	$options = wp_parse_args( $settings, $options );
	//print_r( $options );
	
	//$show_title = 1; /* this will come from a setting */
	
	$use_player_links = $options['pg_use_player_links'];
	
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
	
	while ( have_posts() ) : the_post(); 

		$first_name = get_post_meta($post->ID, '_mstw_tr_first_name', true );
		$last_name = get_post_meta($post->ID, '_mstw_tr_last_name', true );
		$number = get_post_meta($post->ID, '_mstw_tr_number', true );
		$position = get_post_meta($post->ID, '_mstw_tr_position', true );
		$height = get_post_meta($post->ID, '_mstw_tr_height', true );
		$weight = get_post_meta($post->ID, '_mstw_tr_weight', true );
		$year = get_post_meta($post->ID, '_mstw_tr_year', true );
		$experience = get_post_meta($post->ID, '_mstw_tr_experience', true );
		$age = get_post_meta($post->ID, '_mstw_tr_age', true );
		$last_school = get_post_meta($post->ID, '_mstw_tr_last_school', true );
		$home_town = get_post_meta($post->ID, '_mstw_tr_home_town', true );
		$country = get_post_meta($post->ID, '_mstw_tr_country', true );
		$bats = get_post_meta($post->ID, '_mstw_tr_bats', true );
		$throws = get_post_meta($post->ID, '_mstw_tr_throws', true );
		?> 
		
		<div class="player-tile player-tile-<?php echo( $team_slug ) ?>">
		
			<div class = "player-photo">
				<?php 
				
				// check if the post has a Post Thumbnail assigned to it.
				 if ( has_post_thumbnail( ) ) { 
					//echo get_the_post_thumbnail( get_the_ID(), 'full' );
					$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
					$alt = 'Photo of ' . $first_name . ' ' . $last_name;
					//echo '<p>Photo File: ' . $photo_file_url . '</p>';
				} else {
					// Default image is tied to the team taxonomy. 
					// Try to load default-photo-team-slug.jpg, If it does not exst,
					// Then load default-photo.jpg from the plugin -->
					$photo_file = WP_PLUGIN_DIR . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
					if ( file_exists( $photo_file ) ) {
						$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
					}
					else {
						$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '.jpg';
					}
				}
				if ( $options['use_gallery_links'] ) {
					echo( '<a href="' . get_permalink( $post->ID ) . '?format=' . $format . '">' . '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" /></a>' );
				}
				else {
					echo( '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" />' );
				}
				
				?>
			</div> <!-- .player-photo -->
			
			<div class = "player-info-container">
				<?php
					switch( $options['name_format'] ) {
						case 'last-first':
							$player_name = $last_name . ', ' . $first_name;
							break;
						case 'first-only':
							$player_name = $first_name;
							break;
						case 'last-only':
							$player_name = $last_name;
						break;
						default:  //first-last is default
							$player_name = $first_name . " " . 	$last_name;
							break;
					}
					
					
					if( $options['use_gallery_links'] ) {
						// add links from player name to player bio page 	
						$player_html = '<a href="' .  
										get_permalink($post->ID) . 
										'?format=' . $format . '" ';
			
						$player_html .= '>' . $player_name . '</a>';
					}
					else {
						$player_html = $player_name;
					}
				?>
				
				<div class="player-name-number"> <?php echo $number . '  ' . $player_html?></div>
				
				<!-- <div class="player-name-number"> <?php echo $number . '  ' . $first_name . ' ' . $last_name?> </div>-->
			
				<table class="player-info">
				<tbody>
					<?php 
					$row_start = '<tr><td class="lf-col">';
					$new_cell = ':</td><td class="rt-col">'; //colon is for the end of the title
					$row_end = '</td></tr>';
					
					// POSITION
					if( $options['show_position'] ) {
						echo $row_start . $options['position_label'] . $new_cell .  $position . $row_end;
					}
					
					// BATS/THROWS
					if( $options['show_bats_throws'] ) {
						echo $row_start . $options['bats_throws_label'] . $new_cell .  $bats . '/' . $throws . $row_end;
					}
					
					// HEIGHT/WEIGHT
					if ( $options['show_weight'] and $options['show_height'] ) {
						echo $row_start . $options['height_label'] . '/' .  $options['weight_label'] . $new_cell .  $height . '/' . $weight . $row_end;
					}
					else if ( $options['show_height'] ) {
						echo $row_start . $options['height_label'] . $new_cell .  $height . $row_end;
					}
					else if( $options['show_weight'] ) {
						echo $row_start . $options['weight_label'] . $new_cell .  $weight . $row_end;
					}

					//YEAR
					if( $options['show_year'] ) {
						echo $row_start . $options['year_label'] . $new_cell .  $year . $row_end;
					}
					
					//AGE
					if( $options['show_age'] ) {
						echo $row_start . $options['age_label'] . $new_cell .  $age . $row_end;
					}
					
					//EXPERIENCE
					if( $options['show_experience'] ) {
						echo $row_start . $options['experience_label'] . $new_cell .  $experience . $row_end;
					}
					
					//HOME TOWN
					if( $options['show_home_town'] ) {
						echo $row_start . $options['home_town_label'] . $new_cell .  $home_town . $row_end;
					}
					
					//LAST SCHOOL
					if( $options['show_last_school'] ) {
						echo $row_start . $options['last_school_label'] . $new_cell .  $last_school . $row_end;
					}
					
					//COUNTRY
					if( $options['show_country'] ) {
						echo $row_start . $options['country_label'] . $new_cell .  $country . $row_end;
					}
					
					?>
					
				</tbody>
				</table>
			</div><!-- .player-info-container --> 	
		</div><!-- .player-tile -->

	<?php endwhile; ?>

	</div><!-- #content -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>