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
 */
 

	get_header(); 
	
	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	$sp_main_text_color = $options['sp_main_text_color'];
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	$hide_weight = $options['tr_hide_weight'];
	
	// Set the roster table format.  
	if ( $_GET['format'] == '' ) {
		$format = $options['tr_table_default_format'];
	} else {
		$format = $_GET['format'];
	}
	
	$show_title = 1; /* this will come from a setting */
	
	$use_player_links = $options['pg_use_player_links'];
	
	// figure out the team name - for the title (if shown) and for team-based styles
	$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
	$team_slug = $uri_array[sizeof( $uri_array )-2];
	$term = get_term_by( 'slug', $team_slug, 'teams' );
	$team_name .= $term->name;
	
	?>
	
	
	<section id="primary">
	<div id="content-player-gallery" role="main" >

	<header class="page-header">
		<?php
		// set the header tag
		
		if ( $show_title == 1 ) {
			//Set the title color
			$title_color = $options['tr_table_title_text_color'];
		
			if ($title_color == "" )
				$title_h1 = '<h1 class="mstw_tr_roster_title">';
			else
				$title_h1 = '<h1 class="mstw_tr_roster_title" ' . 'style="color: ' . $title_color . ';" >';
		
		
			$page_header = '<h1 id="team-head-title" ';
			if ( $sp_main_text_color != '' ) {
				$page_header .= 'style="color:' . $sp_main_text_color . '; "';
			}
		
			$page_header .= '>';
			
			$page_header .= $team_name;
			
			/*if ( is_tax( 'teams' ) )
				$page_header .= 'Tax Teams';*/
			
			echo $page_header .  '</h1>';
			/*
			echo '<h3>' . get_query_var( 'post_type' ) . '</h3>';
			echo '<h3>' . get_query_var( 'teams' ) . '</h3>';
			echo '<h3>' . get_query_var( 'orderby' ) . '</h3>';
			echo '<h3>' . get_query_var( 'meta_key' ) . '</h3>';
			echo '<h3>' . get_query_var( 'order' ) . '</h3>';
			*/
		}
		?>
	</header>

	<?php /* Start the Loop */ 
	// set the player photo size based on admin settings, if any
	$sp_image_width = $options['sp_image_width'];
	$sp_image_height = $options['sp_image_height'];
	
	$img_width = ( $sp_image_width == '' ) ? 150 : $sp_image_width;
	$img_height = ( $sp_image_height == '' ) ? 150 : $sp_image_height;
	
	?>
	<?php while ( have_posts() ) : the_post(); 

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
			
			<!--<div class="player-tile" style="color:<?php echo $sp_main_text_color . ';'?> background-color:<?php echo $sp_main_bkgd_color . ';'?> ">-->
			
			<?php 
			$html = '<div class="player-tile"';
			
			if ( $sp_main_text_color != '' || $sp_main_bkgd_color != '' ) {
				$html .= 'style="';
				if ( $sp_main_text_color != '' ) {
					$html .= 'color:' . $sp_main_text_color . '; ';
				}
				if ( $sp_main_bkgd_color != '' ) {
					$html .= 'background-color:' . $sp_main_bkgd_color . '; ';
				}
				$html .= '" ';
			}
			
			$html .= '>';
			
			$html .= '<div class=player-tile-' . $team_slug . '"> ';
			
			echo $html;
			
			?>
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
					
					echo( '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" />' );
					
					?>
				</div> <!-- .player-photo -->
				
				<div class = "player-info-container">
					<?php
						$player_name = $first_name . " " . 	$last_name;
						
						if( $use_player_links == 'show-pg-links' ) {
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
						$new_cell = ':</td><td class="rt-col">';
						$row_end = '</td></tr>';
						
						// the first two rows are the same in all formats
						echo $row_start . __('Position', 'mstw-loc-domain') . $new_cell .  $position . $row_end;
						
						// check for a baseball format
						if ( strpos( $format, 'baseball' ) !== false ) { //baseball format add bats/throws
							echo $row_start . __('Bat', 'mstw-loc-domain') . '/' .  __('Thw', 'mstw-loc-domain') . $new_cell .  $bats . '/' . $throws . $row_end;
						}
						
						// height and weight -- hide weight if empty
						if ( $weight == '' or $hide_weight == 'hide-weight' ) { // don't show the weight
							echo $row_start . __('Ht', 'mstw-loc-domain') . $new_cell .  $height . $row_end;
						} 
						else { //show the weight
							echo $row_start . __('Ht', 'mstw-loc-domain') . '/' .  __('Wt', 'mstw-loc-domain') . $new_cell .  $height . '/' . $weight . $row_end;
						}
						//echo $row_start . __('Ht', 'mstw-loc-domain') . '/' .  __('Wt', 'mstw-loc-domain') . $new_cell .  $height . '/' . $weight . $row_end;	
						
						switch ( $format ) { 
							case "college":
							case "baseball-college":
								echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell .  $year . $row_end;
								echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
								echo $row_start . __('Hometown', 'mstw-loc-domain') . $new_cell . $home_town . $row_end;
								echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
								break;
							case "pro":
							case "baseball-pro":
								echo $row_start . __('Age', 'mstw-loc-domain') . $new_cell . $age . $row_end;  
								echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
								echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
								echo $row_start . __('Country', 'mstw-loc-domain') . $new_cell . $country . $row_end;
								break;
							case "high-school":
							case "baseball-high-school":
							default: // default to high-school, the lowest common demononator
								echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell . $year . $row_end;
								break;
						} 
						?>
					</tbody>
					</table>
				</div><!-- .player-info-container --> 
			</div><!-- .player-tile-team-slug -->	
			</div><!-- .player-tile -->

	<?php endwhile; ?>

	</div><!-- #content -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
