<?php
/*----------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS.PHP
 *	mstr_tr_set_options() - returns the default option settings
 *	mstw_tr_set_fields_by_format() - sets options based on specified format
 *
 * 20130912-MAO:
 *	(1) Changed call to get_template_directory() to get_stylesheet_directory() to
 *		fix bug with child themes.
 * 
 *---------------------------------------------------------*/
 
 /*---------------------------------------------------------------------------------
 *	mstw_tr_utility_fuctions_loaded: DO NOT DELETE
 *		It does nothing EXCEPT indicate whether or not the file is loaded!!
 *-------------------------------------------------------------------------------*/
 
 function mstw_tr_utility_functions_loaded( ) {
	return true;
 }

/*---------------------------------------------------------------------------------
 *	mstw_tr_set_options_by_format: Sets the options based on the specified format
 *		high-school, college, pro, baseball-high-school, 
 *		baseball-college, or baseball-pro. "custom" caused no change 
 *		to the admin options. The defaults specified on the admin page are used
 *-------------------------------------------------------------------------------*/

 function mstw_tr_set_fields_by_format( $format ) {
		switch ( $format) {
			case 'baseball-high-school':
			case 'baseball-college':
			case 'baseball-pro':
				$show_bats_throws = 1;
			break;
			default:
				$show_bats_throws = 0;
				break;
		}

		switch ( $format ) {
			case 'baseball-high-school':
			case 'high-school':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 0,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 0,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				break;
				
			case 'baseball-college':
			case 'college':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 1,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);		
				break;
			
			case 'pro':
			case 'baseball-pro':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 0,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 1,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 1,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				break;
				
			default:  // custom roster format
				$settings = get_option( 'mstw_tr_options' );
				break;
		}
		return $settings;
	}

/*---------------------------------------------------------------------------------
 *	mstw_tr_get_defaults: returns the array of option defaults
 *-------------------------------------------------------------------------------*/	
		function mstw_tr_get_defaults( ) {
		//Base defaults
		$defaults = array(	
				'team'					=> 'no-team-specified',
				'show_title'			=> 1,
				'roster_type'			=> 'custom',
				//'use_player_links'		=> 0, removed in 3.1
				//'use_gallery_links'		=> 0, removed in 3.1
				//'use_pg_links'			=> 0, removed in 3.1
				'sort_order'			=> 'alpha',
				'name_format'			=> 'last-first',
				'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
				'show_photos'			=> 0,
				'photo_label'			=> __( 'Photo', 'mstw-loc-domain' ),
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'mstw-loc-domain' ),
				'show_position'			=> 1,
				'position_label'		=> __( 'Pos', 'mstw-loc-domain' ),
				'show_height'			=> 1,
				'height_label'			=> __( 'Hgt', 'mstw-loc-domain' ),
				'show_weight'			=> 1,
				'weight_label'			=> __( 'Wgt', 'mstw-loc-domain' ),
				'show_year'				=> 0,
				'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
				'show_experience'		=> 0,
				'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
				'show_age'				=> 0,
				'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
				'show_home_town'		=> 0,
				'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
				'show_last_school'		=> 0,
				'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
				'show_country'			=> 0,
				'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
				'show_bats_throws'		=> 0,
				'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
				'show_other_info'		=> 0,
				'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				
		return $defaults;
	}
	
	function mstw_tr_build_gallery( $team_slug, $posts, $options, $format ) {	
		
		//$output = "<div class='mstw_tr_gallery'>\n";
	
		if( !empty( $posts ) ) {
		
			/* 	Do we really want to set the default here?
				Or let the stylesheet handle it? */
			$img_width = ( $options['sp_image_width'] == '' ) ? 150 : $options['sp_image_width'];
			$img_height = ( $options['sp_image_height'] == '' ) ? 150 : $options['sp_image_height'];
			
			foreach( $posts as $post ) { // ( have_posts( ) ) : the_post();
				// Get the post data
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
				$other = get_post_meta($post->ID, '_mstw_tr_other', true );
	
				$output .= "<div class='player-tile player-tile-" . $team_slug . "'>\n";
		
				$output .= "<div class = 'player-photo' >\n";
				
				// check if the post has a Post Thumbnail assigned to it.
				 if ( has_post_thumbnail( $post->ID ) ) { 
					//echo get_the_post_thumbnail( get_the_ID(), 'full' );
					$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post->ID ) );
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
				$single_player_template = get_stylesheet_directory( ) . '/single-player.php';
				if ( file_exists( $single_player_template ) ) {
					$output .= '<a href="' . get_permalink( $post->ID ) . '?format=' . $format . '">' . '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" /></a>';
				}
				else {
					$output .= '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" />';
				}
				
				$output .= "</div> <!-- .player-photo -->\n";
				//End of .player-photo
				
				$output .= "<div class = 'player-info-container'>\n";
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
					
					$single_player_template = get_template_directory( ) . '/single-player.php';
					
					if ( file_exists( $single_player_template ) ) {
						// add links from player name to player bio page 	
						$player_html = '<a href="' .  
										get_permalink($post->ID) . 
										'?format=' . $format . '" ';
			
						$player_html .= '>' . $player_name . '</a>';
					}
					else {
						$player_html = $player_name;
					}
				
					$output .= "<div class='player-name-number'>\n"; 
					$output .= $number . '  ' . $player_html;
					$output .= "</div> <!-- .player-name-number -->\n";
					
					$output .= "<table class='player-info'>\n";
					$output .= "<tbody>\n";
					 
					$row_start = '<tr><td class="lf-col">';
					$new_cell = ':</td><td class="rt-col">'; //colon is for the end of the title
					$row_end = '</td></tr>';
					
					// POSITION
					if( $options['show_position'] ) {
						$output .= $row_start . $options['position_label'] . $new_cell .  $position . $row_end;
					}
					
					
					// BATS/THROWS
					if( $options['show_bats_throws'] ) {
						$output .= $row_start . $options['bats_throws_label'] . $new_cell .  $bats . '/' . $throws . $row_end;
					}
					
					// HEIGHT/WEIGHT
					if ( $options['show_weight'] and $options['show_height'] ) {
						$output .= $row_start . $options['height_label'] . '/' .  $options['weight_label'] . $new_cell .  $height . '/' . $weight . $row_end;
					}
					else if ( $options['show_height'] ) {
						$output .= $row_start . $options['height_label'] . $new_cell .  $height . $row_end;
					}
					else if( $options['show_weight'] ) {
						$output .= $row_start . $options['weight_label'] . $new_cell .  $weight . $row_end;
					}

					//YEAR
					if( $options['show_year'] ) {
						$output .= $row_start . $options['year_label'] . $new_cell .  $year . $row_end;
					}
					
					//AGE
					if( $options['show_age'] ) {
						$output .= $row_start . $options['age_label'] . $new_cell .  $age . $row_end;
					}
					
					//EXPERIENCE
					if( $options['show_experience'] ) {
						$output .= $row_start . $options['experience_label'] . $new_cell .  $experience . $row_end;
					}
					
					//HOME TOWN
					if( $options['show_home_town'] ) {
						$output .= $row_start . $options['home_town_label'] . $new_cell .  $home_town . $row_end;
					}
					
					//LAST SCHOOL
					if( $options['show_last_school'] ) {
						$output .= $row_start . $options['last_school_label'] . $new_cell .  $last_school . $row_end;
					}
					
					//COUNTRY
					if( $options['show_country'] ) {
						$output .= $row_start . $options['country_label'] . $new_cell .  $country . $row_end;
					}
					
					//OTHER
					if( $options['show_other_info'] ) {
						$output .= $row_start . $options['other_info_label'] . $new_cell .  $other . $row_end;
					}
					
					
				$output .= "</tbody>\n";
				$output .= "</table>\n";
				
				$output .= "</div> <!-- .player-info-container -->\n";
				
				$output .= "</div> <!-- .player-tile -->\n";
			} //end foreach( $posts as $post )
		} //end if( have_posts( ) )
		else {
			$output .= "<h1>Sorry, no players were found for " . $team_slug . ".</h1>";
		}
		
		//$output .= "</div> <!--end .mstw_tr_gallery -->";
		return $output;
	}
	?>
