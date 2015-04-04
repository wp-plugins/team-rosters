<?php
/*-----------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS.PHP
 *		Utility or convenience functions used in both the front and back ends
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
 
 /*------------------------------------------------------------------------
 *	MSTW-SS-UTILITY-FUNCTIONS
 *	These functions are included in both the front and back end.
 *
 * 1. mstw_tr_get_defaults() - returns the default mstw_tr_options[]
 * 2. mstw_tr_get_data_fields_columns_defaults() - returns the defaults 
 *		for the data fields & columns options (ONLY)
 * 3. mstw_tr_get_roster_table_defaults() - returns the defaults 
 *		the roster table options (ONLY)
 * 4. mstw_tr_get_roster_table_colors_defaults() -  returns the defaults 
 *		for the table colors options (ONLY)
 * 5. mstw_tr_get_bio_gallery_defaults() - returns the defaults 
 *		for the player profile & team gallery (ONLY)
 * 6. mstw_tr_set_options_by_format() - Sets the options based on one 
 *		of the six built-in roster type
 * 7. mstw_tr_build_gallery() - Builds the player gallery on the front end.
 * 8. mstw_tr_build_player_photo - Constructs the html for the player profile 
 *		player photo.
 * 9. mstw_tr_build_player_name: constructs a player name based
 *		on first, last, and $options['name_format']
 * 10. mstw_tr_build_profile_logo - Constructs the html for the player profile
 *		logo.
 * 11. mstw_tr_build_bats_throws - constructs the html for the 
 *		bats-throws field ('B/T') in all displays  
 *
 *--------------------------------------------------------------------------*/
 
 //-----------------------------------------------------------
 //	1. mstw_tr_get_defaults: returns the array of ALL option defaults
 //	
 if ( !function_exists( 'mstw_tr_get_defaults' ) ) {
	function mstw_tr_get_defaults( ) {
		//Base defaults
		$defaults = array_merge( mstw_tr_get_data_fields_columns_defaults( ),
								 mstw_tr_get_roster_table_defaults( ),
								 mstw_tr_get_roster_table_colors_defaults( ),
								 mstw_tr_get_bio_gallery_defaults( )
								 );
				
		return $defaults;
	} //End: mstw_tr_get_defaults()
}

 //-----------------------------------------------------------
 //	2. mstw_tr_get_data_fields_columns_defaults: returns the array 
 //		defaults for the data fields & columns options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_data_fields_columns_defaults' ) ) {
	function mstw_tr_get_data_fields_columns_defaults( ) {
		$defaults = array(	
				'team'					=> 'no-team-specified',
				//'show_title'			=> 1,
				//'roster_type'			=> 'custom',
				//'sort_order'			=> 'alpha',
				//'name_format'			=> 'last-first',
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'mstw-team-rosters' ),
				//always show the name
				'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
				//'show_photos'			=> 0,
				//'photo_label'			=> __( 'Photo', 'mstw-team-rosters' ),
				'show_position'			=> 1,
				'position_label'		=> __( 'Pos', 'mstw-team-rosters' ),
				'show_height'			=> 1,
				'height_label'			=> __( 'Ht', 'mstw-team-rosters' ),
				'show_weight'			=> 1,
				'weight_label'			=> __( 'Wt', 'mstw-team-rosters' ),
				'show_year'				=> 0,
				'year_label'			=> __( 'Year', 'mstw-team-rosters' ),
				'show_experience'		=> 0,
				'experience_label'		=> __( 'Exp', 'mstw-team-rosters' ),
				'show_age'				=> 0,
				'age_label'				=> __( 'Age', 'mstw-team-rosters' ),
				'show_home_town'		=> 0,
				'home_town_label'		=> __( 'Home Town', 'mstw-team-rosters' ),
				'show_last_school'		=> 0,
				'last_school_label'		=> __( 'Last School', 'mstw-team-rosters' ),
				'show_country'			=> 0,
				'country_label'			=> __( 'Country', 'mstw-team-rosters' ),
				'show_bats_throws'		=> 0,
				'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-team-rosters' ),
				'show_other_info'		=> 0,
				'other_info_label'		=> __( 'Other', 'mstw-team-rosters' ),
				);
				
		return $defaults;
	} //End: mstw_tr_get_data_fields_columns_defaults
 }
 
 //-----------------------------------------------------------
 //	3. mstw_tr_get_roster_table_defaults: returns the defaults for 
 //		the roster table options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_defaults' ) ) {
	function mstw_tr_get_roster_table_defaults( ) {
		$defaults = array(	
				'show_title'			=> 0,
				'roster_type'			=> 'custom',
				'links_to_profiles'		=> 1,
				'sort_order'			=> 'alpha', //sort by last name
				'name_format'			=> 'last-first',
				'show_photos'			=> 0,
				'photo_label'			=>  __( 'Photo', 'mstw-team-rosters' ),
				'table_photo_width'		=> '',
				'table_photo_height'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_roster_table_defaults
 }

 //-----------------------------------------------------------
 //	4. mstw_tr_get_roster_table_colors_defaults: returns the array 
 //		defaults for the table colors options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_colors_defaults' ) ) {
	function mstw_tr_get_roster_table_colors_defaults( ) {
		$defaults = array(	
				'use_team_colors'		=> 0,
				'table_title_color'		=> '',
				'table_links_color'		=> '',
				'table_head_bkgd'		=> '',
				'table_head_text'		=> '',
				'table_even_row_bkgd'	=> '',
				'table_even_row_text'	=> '',
				'table_odd_row_bkgd'	=> '',
				'table_odd_row_text'	=> '',
				'table_border_color'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_roster_table_colors_defaults
 }
 
 
 //-----------------------------------------------------------
 //	5. mstw_tr_get_bio_gallery_defaults: returns the array 
 //		defaults for the player profile & team gallery (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_bio_gallery_defaults' ) ) {
	function mstw_tr_get_bio_gallery_defaults( ) {
		$defaults = array(
				'sp_show_title'			=> 1,
				'sp_show_logo'			=> 0,
				'sp_content_title'		=> __( 'Player Bio', 'mstw-team-rosters' ),
				
				'sp_image_width'		=> 150,
				'sp_image_height'		=> 150,
		
				'sp_main_bkgd_color'	=> '',
				'sp_main_text_color'	=> '',
				'sp_bio_border_color'	=> '',
				'sp_bio_header_color'	=> '',
				'sp_bio_bkgd_color'		=> '',
				'sp_bio_text_color'		=> '',
				'gallery_links_color'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_bio_gallery_defaults( )
 }

 //-----------------------------------------------------------
 // 6. mstw_tr_set_fields_by_format: Sets the options based on the 
 //		format argument: custom, high-school, college, pro, 
 //		baseball-high-school, baseball-college, or baseball-pro. 
 //		"custom" causes the Settings admin page defaults to be used
 //
 if ( !function_exists( 'mstw_tr_set_fields_by_format' ) ) {
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
					//'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-team-rosters' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-team-rosters' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-team-rosters' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-team-rosters' ),
					'show_experience'		=> 0,
					//'experience_label'		=> __( 'Exp', 'mstw-team-rosters' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-team-rosters' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-team-rosters' ),
					'show_last_school'		=> 0,
					//'last_school_label'		=> __( 'Last School', 'mstw-team-rosters' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-team-rosters' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-team-rosters' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-team-rosters' ),
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
					//'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-team-rosters' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-team-rosters' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-team-rosters' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-team-rosters' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-team-rosters' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-team-rosters' ),
					'show_home_town'		=> 1,
					//'home_town_label'		=> __( 'Home Town', 'mstw-team-rosters' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-team-rosters' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-team-rosters' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-team-rosters' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-team-rosters' ),
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
					//'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-team-rosters' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-team-rosters' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-team-rosters' ),
					'show_year'				=> 0,
					//'year_label'			=> __( 'Year', 'mstw-team-rosters' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-team-rosters' ),
					'show_age'				=> 1,
					//'age_label'				=> __( 'Age', 'mstw-team-rosters' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-team-rosters' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-team-rosters' ),
					'show_country'			=> 1,
					//'country_label'			=> __( 'Country', 'mstw-team-rosters' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-team-rosters' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-team-rosters' ),
				);
				break;
				
			default:  // custom roster format
				$settings = get_option( 'mstw_tr_options' );
				break;
		}
		return $settings;
	} //End: mstw_tr_set_fields_by_format()
}

 //-----------------------------------------------------------
 //	7. mstw_tr_build_gallery: Builds the player gallery on the front end.
 //		Called by both the gallery shortcode and the team taxonomy 
 //		page template.
 //
 if ( !function_exists( 'mstw_tr_build_gallery' ) ) {
	function mstw_tr_build_gallery( $team_slug, $posts, $options, $format ) {	
		if( !empty( $posts ) ) {
			
			$output = '';
			
			foreach( $posts as $post ) { // ( have_posts( ) ) : the_post();
				
				$output .= "<div class='player-tile player-tile-" . $team_slug . "'>\n";
		
				$output .= "<div class = 'player-photo' >\n";
					$output .= mstw_tr_build_player_photo( $post, $team_slug, $options, 'gallery' );
				$output .= "</div> <!-- .player-photo -->\n";
				
				$output .= "<div class = 'player-info-container'>\n";
					$output .= "<div class='player-name-number'>\n"; 
						if ( $options['show_number'] ) {
							$player_number = get_post_meta($post->ID, 'player_number', true );
							$output .= "<div class='player-number'>$player_number</div>";
						}
						$player_name = mstw_tr_build_player_name( $post, $options, 'gallery' );
						$output .= "<div class='player-name'>$player_name</div>";
					$output .= "</div> <!-- .player-name-number -->\n";
					
					$output .= "<table class='player-info'>\n";
					  $output .= "<tbody>\n";
						$row_start = '<tr><td class="lf-col">';
						$new_cell = ':</td><td class="rt-col">'; //colon is for the end of the title
						$row_end = '</td></tr>';
						
						// POSITION
						if( $options['show_position'] ) {
							$output .= $row_start . $options['position_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_position', true ) . $row_end;
						}

						// BATS/THROWS
						if( $options['show_bats_throws'] ) {
							$output .= $row_start . $options['bats_throws_label'] . $new_cell   
												  . mstw_tr_build_bats_throws( $post ) . $row_end;
						}
						
						// HEIGHT/WEIGHT
						if ( $options['show_weight'] and $options['show_height'] ) {
							$output .= $row_start . $options['height_label'] . '/' 
												  . $options['weight_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_height', true ) . '/' 
												  . get_post_meta($post->ID, 'player_weight', true ) . $row_end;
						}
						else if ( $options['show_height'] ) {
							$output .= $row_start . $options['height_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_height', true ) . $row_end;
						}
						else if( $options['show_weight'] ) {
							$output .= $row_start . $options['weight_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_weight', true ) . $row_end;
						}

						//YEAR
						if( $options['show_year'] ) {
							$output .= $row_start . $options['year_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_year', true ) . $row_end;
						}
						
						//AGE
						if( $options['show_age'] ) {
							$output .= $row_start . $options['age_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_age', true ) . $row_end;
						}
						
						//EXPERIENCE
						if( $options['show_experience'] ) {
							$output .= $row_start . $options['experience_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_experience', true ) . $row_end;
						}
						
						//HOME TOWN
						if( $options['show_home_town'] ) {
							$output .= $row_start . $options['home_town_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_home_town', true ) . $row_end;
						}
						
						//LAST SCHOOL
						if( $options['show_last_school'] ) {
							$output .= $row_start . $options['last_school_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_last_school', true ) . $row_end;
						}
						
						//COUNTRY
						if( $options['show_country'] ) {
							$output .= $row_start . $options['country_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_country', true ) . $row_end;
						}
						
						//OTHER
						if( $options['show_other_info'] ) {
							$output .= $row_start . $options['other_info_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_other', true ) . $row_end;
						}		
				 $output .= "</tbody>\n";
				$output .= "</table>\n";
				
				$output .= "</div> <!-- .player-info-container -->\n";
				
				$output .= "</div> <!-- .player-tile -->\n";
			} //end foreach( $posts as $post )
		} //end if( have_posts( ) )
		else {
			$so_sorry = sprintf( __( 'Sorry, no players were found for %s', 'mstw-team-rosters' ), $team_slug );
			$output .= "<h1>$so_sorry</h1>";
		}

		return $output;
		
	} //End: mstw_tr_build_gallery()
 }

 //-----------------------------------------------------------
 //	8. mstw_tr_build_player_photo: constructs the html for the 
 //		player photo all front-end displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if ( !function_exists( 'mstw_tr_build_player_photo' ) ) {
	function mstw_tr_build_player_photo( $player, $team_slug, $options, $display = 'profile' ) {
           
		//1. Use the player photo (thumbnail) if available
		//2. Else use the default-photo-team-slug.png from the plugin images dir
		//3. Else use the team logo from the teams DB, if available,
		//4. Else load the default-photo.png (mystery player) from the plugin images dir
		
		// $display - profile, gallery, table
		
		$default_img_dir = WP_PLUGIN_DIR . '/team-rosters/images/default-images/';
		$default_img_url = plugins_url( ) . '/team-rosters/images/default-images/';
		
		//if( $thumbnail = get_the_post_thumbnail( $player->ID, 'full' ) ) {
		if ( has_post_thumbnail( $player->ID ) ) { 
			$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $player->ID ) );
			$first_name = get_post_meta($player->ID, 'player_first_name', true );
			$last_name = get_post_meta($player->ID, 'player_last_name', true );
			$alt = "$first_name $last_name";
		} 
		else {
			// Default image can be tied to the team taxonomy 
			// Try to load default-photo-team-slug.png, if it does not exist,
			// load default-photo.png
			$photo_file = $default_img_dir . 'default-photo-' . $team_slug . '.png';
			if ( file_exists( $photo_file ) ) {
				$photo_file_url = $default_img_url . 'default-photo-' . $team_slug . '.png';
			}
			else {
				$photo_file_url = $default_img_url . 'default-photo.png';
			}
			$alt = 'No photo available';
		}
		
		$photo_html = "<img src='$photo_file_url' alt='$alt' />";
		
		//
		// add the link to the player profile, if appropriate
		//
		if ( $display != 'profile' ) {
			if ( isset( $options['links_to_profiles'] ) and $options['links_to_profiles'] ) {
				$photo_html = '<a href="' .  get_permalink( $player->ID ) . '">' . $photo_html . '</a>';
			}
		}
			
		return $photo_html;
			
	} //End: mstw_tr_build_player_photo()
 }
 
 //-----------------------------------------------------------
 //	9. mstw_tr_build_player_name: constructs a player name based
 //		on first, last, and $options['name_format']
 //		Link to player profile is based on $display, profiles don't have links to themselves,
 //		and $options['links_to_profiles']
 //
 if ( !function_exists( 'mstw_tr_build_player_name' ) ) {
	function mstw_tr_build_player_name( $player, $options, $display = 'profile' ) {
		
		$first_name = get_post_meta($player->ID, 'player_first_name', true );
		$last_name = get_post_meta($player->ID, 'player_last_name', true );
		
		switch ( $options['name_format'] ) { 
			case 'first-last':
				$player_name = ( $display == 'profile' ) ? $first_name . '<br/>' . $last_name : "$first_name $last_name";
				break; 
			case 'first-only':
				$player_name = $first_name;
				break;
			case 'last-only':
				$player_name = $first_name;
				break;
			case 'last-first':
			default:
				$player_name = ( $display == 'profile' ) ? $last_name . ',<br/>' . $first_name : "$last_name, $first_name";
				break; 
		} 
		
		$player_html = $player_name;
		
		if( $display != 'profile' ) {
			if ( $options['links_to_profiles'] ) {
				$player_html = '<a href="' .  get_permalink( $player->ID ) . '?roster_type=' . $options['roster_type'] . '" ';
				$player_html .= '>' . $player_name . '</a>';
			}
		}
		
		return $player_html;
		
	} //End: mstw_tr_build_player_name()
 }
 
 //-----------------------------------------------------------
 //	10. mstw_tr_build_profile_logo - constructs the html for the 
 //		player photo all front-end displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if ( !function_exists( 'mstw_tr_build_profile_logo' ) ) {
	function mstw_tr_build_profile_logo( $post, $team_slug, $options, $type ) {
		$default_img_dir = WP_PLUGIN_DIR . '/team-rosters/images/default-images/';
		$default_img_url = plugins_url( ) . '/team-rosters/images/default-images/';

		$photo_file = $default_img_dir . 'default-photo-' . $team_slug . '.png';
		$photo_file_url = $default_img_url . 'default-photo-cal-bears.png';
		
		//this is the default return
		$photo_html = "<img src='$photo_file_url' alt='No team logo found.' />";
		
		$team_links = get_option( 'mstw_tr_ss_team_links' );
		
		mstw_log_msg( 'team links: ' );
		mstw_log_msg( $team_links );
		
		if ( array_key_exists( $team_slug, $team_links ) && $team_links[ $team_slug ] != -1 ) {
			$link = $team_links[ $team_slug ];
			mstw_log_msg( "link exists: $team_slug ===> $link " );
			if( post_type_exists( 'mstw_ss_team' ) ) {
				mstw_log_msg( 'mstw_ss_team exists' );
			} else {
				mstw_log_msg( 'mstw_ss_team does not exist' );
			}
			$team_obj = get_page_by_path( $team_links[ $team_slug ], OBJECT, 'mstw_ss_team' );
			if( $team_obj ) {
				//mstw_log_msg( 'found $team_obj ...' );
				//mstw_log_msg( 'ID= ' . $team_obj->ID );
				$team_logo = get_post_meta( $team_obj->ID, 'team_alt_logo', true );
				//mstw_log_msg( '$team_logo= ' . $team_logo );
				if( $team_logo ) {
					$alt = get_the_title( $team_obj->ID );
					$photo_html = "<img src='$team_logo' alt=$alt />";
					$team_site_link = get_post_meta( $team_obj->ID, 'team_link', true );
					if ( $team_site_link ) {
						$photo_html = "<a href=$team_site_link target='_blank'> $photo_html </a>";
					}
				}						
				//mstw_log_msg( '$team_site= ' . $team_site_link );
			} else {
				mstw_log_msg( "Problem with link for $team_slug " );
			}
		} else {
			mstw_log_msg( 'No link found for team slug = ' . $team_slug );
			
		}
		
		return $photo_html;
		
	} //End: mstw_tr_build_profile_logo( )	 
 }
 
 
 //-----------------------------------------------------------
 //	11. mstw_tr_build_bats_throws - constructs the html for the 
 //		bats-throws field in all displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if( !function_exists( 'mstw_tr_build_bats_throws' ) ) {
	function mstw_tr_build_bats_throws( $player ) {
	
		//return variable
		$html = ''; 
		
		$bats_throws = array( get_post_meta( $player->ID, 'player_bats', true ),
							  get_post_meta( $player->ID, 'player_throws', true ),
							);
	
		for ( $i = 0; $i < sizeof( $bats_throws ); $i++ ) {
			if ( 1 == $i ) {
				$html .= '/';
			}
			switch ( $bats_throws[ $i ] ) {
				case 1:
				case __( 'R', 'mstw-team-rosters' ):
					$html .= __( 'R', 'mstw-team-rosters' );
					break;
				case 2:
				case __( 'L', 'mstw-team-rosters' ):
					$html .= __( 'L', 'mstw-team-rosters' );
					break;
				case 2:
				case __( 'B', 'mstw-team-rosters' ):
					$html .= __( 'B', 'mstw-team-rosters' );
					break;
				case 0:
				case '':
				default: 
					// no value specified, so do nothing
					break;
			}
		}
		
		return $html;
		
	} //End: mstw_tr_build_bats_throws( ) 
 }
