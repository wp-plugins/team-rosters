<?php
/**
 * The template for displaying Team Archive pages using the Team Rosters plugin.
 * This will create a 'gallery view' of the team.
 *
 */

	get_header(); 
	
	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	$sp_main_text_color = $options['sp_main_text_color'];
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	
	// Set the roster table format.  
	$roster_type = $options['tr_table_default_format'];
	
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

	<?php /* Start the Loop */ ?>
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
					 if ( has_post_thumbnail() ) { 
						the_post_thumbnail( 'full' );
					} 
					else {
						// Default image is tied to the team taxonomy. Try to load default-photo-team-slug.jpg -->
						// If it does not exst, then load default-photo.jpg from the plugin -->
						$photo_file = WP_PLUGIN_DIR . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
						//echo '<h3>' . plugin_dir_path( 'team-rosters' ) . '</h3>';
						if ( file_exists( $photo_file ) ) {
							$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
						}
						else {
							$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '.jpg';
						}
						echo( '<img src=' . $photo_file_url . ' alt="Default Player Photo" width="150px" height="150" />' );
					}
					
					?>
				</div> <!-- .player-photo -->
				
				<div class = "player-info-container">
					<?php
						$player_name = $first_name . " " . 	$last_name;
						
						if( $use_player_links == 'show-pg-links' ) {
							// add links from player name to player bio page 	
							$player_html = '<a href="' .  
											get_permalink($post->ID) . 
											'?format=' . $roster_type . '" ';
				
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
						echo $row_start . __('Ht', 'mstw-loc-domain') . '/' .  __('Wt', 'mstw-loc-domain') . $new_cell .  $height . '/' . $weight . $row_end;	
						
						switch ( $roster_type ) { 
							case "college": 
								echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell .  $year . $row_end;
								echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
								echo $row_start . __('Hometown', 'mstw-loc-domain') . $new_cell . $home_town . $row_end;
								echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
								break;
							case "pro":
								echo $row_start . __('Age', 'mstw-loc-domain') . $new_cell . $age . $row_end;  
								echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
								echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
								echo $row_start . __('Country', 'mstw-loc-domain') . $new_cell . $country . $row_end;
								break;
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
