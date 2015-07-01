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
 *	MSTW-TR-UTILITY-FUNCTIONS
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
 * 6. mstw_tr_set_fields_by_format: returns wp_parse_args( $settings, $defaults )
 *		NEED TO RECONCILE THIS WITH #6 above
 * 7. mstw_tr_build_gallery() - Builds the player gallery on the front end.
 * 8. mstw_tr_build_player_photo - Constructs the html for the player profile 
 *		player photo.
 * 9. mstw_tr_build_player_name: constructs a player name based
 *		on first, last, and $options['name_format']
 * 10. mstw_tr_build_profile_logo - Constructs the html for the player profile
 *		logo.
 * 11. mstw_tr_build_bats_throws - constructs the html for the 
 *		bats-throws field ('B/T') in all displays 
 * 13. mstw_tr_admin_notice: displays team rosters admin notices 
 * 14. mstw_tr_build_colors_html - builds the HTML for team colors
 *		when the 'use team colors' display option is set 
 * 15. mstw_tr_build_hidden_fields: builds the hidden fields for the 
 *		JavaScript to use when using the teams DB colors.
 * 16. mstw_tr_find_team_in_ss - Determines if a team is linked to a team 
 *		in the Schedules & Scoreboards plugin DB
 * 17. mstw_tr_build_team_logo: Builds the HTML for a team logo 
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
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'mstw-team-rosters' ),
				//always show the name
				'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
				'show_photos'			=> 0,
				'photo_label'			=>  __( 'Photo', 'mstw-team-rosters' ),
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
				'sort_asc_desc'			=> 'asc',
				'name_format'			=> 'last-first',
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
				
				'sp_use_team_colors'	=> 0,
				
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


 if ( !function_exists( 'mstw_tr_set_fields_by_format' ) ) {
	function mstw_tr_set_fields_by_format( $format ) {
		
		return mstw_tr_get_fields_by_roster_type( $format );
		
	} //End: mstw_tr_set_fields_by_format()
 }

 //-----------------------------------------------------------
 // 6. mstw_tr_get_fields_by_roster_type - Sets the show/hide fields 
 //		based on the 
 //		roster_type argument: custom, high-school, college, pro, 
 //		baseball-high-school, baseball-college, or baseball-pro. 
 //		"custom" causes the Settings admin page defaults to be used
 //
 if ( !function_exists( 'mstw_tr_get_fields_by_roster_type' ) ) {
	function mstw_tr_get_fields_by_roster_type( $roster_type ) {
		
		$show_bats_throws = ( false === strpos( $roster_type, 'baseball' ) ) ? 0 : 1;

		switch ( $roster_type ) {
			case 'baseball-high-school':
			case 'high-school':
				$settings = array(					
					'roster_type'			=> $roster_type,					
					'show_number'			=> 1,					
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 1,
					'show_experience'		=> 0,
					'show_age'				=> 0,
					'show_home_town'		=> 0,
					'show_last_school'		=> 0,
					'show_country'			=> 0,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);
				break;
				
			case 'baseball-college':
			case 'college':
				$settings = array(	
					'roster_type'			=> $roster_type,
					'show_number'			=> 1,
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 1,
					'show_experience'		=> 1,
					'show_age'				=> 0,
					'show_home_town'		=> 1,
					//this is shown in Home Town(Last School) column
					'show_last_school'		=> 1,
					'show_country'			=> 0,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);		
				break;
			
			case 'pro':
			case 'baseball-pro':
				$settings = array(	
					'roster_type'			=> $roster_type,
					'show_number'			=> 1,
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 0,
					'show_experience'		=> 1,
					'show_age'				=> 1,
					'show_home_town'		=> 0,
					'show_last_school'		=> 1,
					//show the country as part of the last_school(country) column
					//so don't need to set here
					'show_country'			=> 1,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);
				break;
				
			default:  // custom roster type
				$settings = get_option( 'mstw_tr_options' );
				break;
		}
		return $settings;
	} //End: mstw_tr_get_fields_by_roster_type()
}

 //-----------------------------------------------------------
 //	7. mstw_tr_build_gallery: Builds the player gallery on the front end.
 //		Called by both the gallery shortcode and the team taxonomy 
 //		page template.
 //
 if ( !function_exists( 'mstw_tr_build_gallery' ) ) {
	function mstw_tr_build_gallery( $team_slug, $roster_type, $options ) {
		//mstw_log_msg( 'in mstw_tr_build_gallery ... ' );
		//mstw_log_msg( $options );

		// Set the sort field	
		switch ( $options['sort_order'] ) {
			case 'numeric':
				$sort_key = 'player_number';
				$order_by = 'meta_value_num';
				break;
			case 'alpha-first':
				$sort_key = 'player_first_name';
				$order_by = 'meta_value';
				break;
			default: // alpha by last
				$sort_key = 'player_last_name';
				$order_by = 'meta_value';
				break;
		}
		
		// Set sort order
		switch ( $options['sort_asc_desc'] ) {
			case 'desc':
				$sort_order = 'DESC';
				break;
			default:
				$sort_order = 'ASC';
				break;	
		}
			
		// Get the team roster		
		$posts = get_posts( array( 'numberposts' => -1,
								   'post_type' => 'mstw_tr_player',
								   'mstw_tr_team' => $team_slug, 
								   'orderby' => $order_by, 
								   'meta_key' => $sort_key,
								   'order' => $sort_order 
								));	
	
		if( $posts ) {
			// Set up the hidden fields for jScript CSS 
			$output = mstw_tr_build_team_colors_html( $team_slug, $options, 'gallery' );
			
			foreach( $posts as $post ) { // ( have_posts( ) ) : the_post();
				
				$output .= "<div class='player-tile player-tile_" . $team_slug . "'>\n";
		
				$output .= "<div class = 'player-photo' >\n";
					$output .= mstw_tr_build_player_photo( $post, $team_slug, $options, 'gallery' );
				$output .= "</div> <!-- .player-photo -->\n";
				
				$output .= "<div class = 'player-info-container'>\n";
					$output .= "<div class='player-name-number player-name-number_$team_slug'>\n"; 
						if ( $options['show_number'] ) {
							$player_number = get_post_meta($post->ID, 'player_number', true );
							$output .= "<div class='player-number'>$player_number</div>";
						}
						$player_name = mstw_tr_build_player_name( $post, $options, 'gallery' );
						$output .= "<div class='player-name'>$player_name</div>";
					$output .= "</div> <!-- .player-name-number -->\n";
					
					$output .= "<table class='player-info player-info_$team_slug'>\n";
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
						
						//OTHER INFO
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
			$output = sprintf( __( "%sNo players found on team: '%s'%s", 'mstw-team-rosters' ), '<h1>', $team_slug, '</h1>' );
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
		//2. Else use the team logo from the teams DB, if available,
		//3. Else use the team logo in the theme's /mstw-team-rosters-images/ dir
		//3. Else use the default-photo-team-slug.png from the plugin images dir
		//4. Else use the default-photo.png (mystery player) from the plugin images dir
		
		//mstw_log_msg( 'in mstw_tr_build_player_photo ... ' );
		//mstw_log_msg( '$team_slug= ' );
		//mstw_log_msg( $team_slug );
		
		
		// This is the default if nothing else can be found
		$photo_file_url = '';
		$photo_html = '';
		$logo_html = '';

		if ( has_post_thumbnail( $player->ID ) ) { 
			// Use the player's thumbnail (featured image) if available
			$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $player->ID ) );
			$first_name = get_post_meta($player->ID, 'player_first_name', true );
			$last_name = get_post_meta($player->ID, 'player_last_name', true );
			$alt = "$first_name $last_name";
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
			
		} else {
			// Try to build a team logo
			$photo_html = mstw_tr_build_team_logo( $team_slug );
		
		}
		//mstw_log_msg( '$photo_html = ' );
		//mstw_log_msg( $photo_html );
		
		if( !$photo_html ) {
			// Give up and use the "mystery man"
			//$default_img_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'images/default-images/';
			//mstw_log_msg( '$default_img_dir = ' . $default_img_dir );
			//$default_img_url = plugins_url( ) . '/team-rosters/images/default-images/';
			$photo_file_url = plugins_url( ) . '/team-rosters/images/default-images/default-photo.png';
			$alt = __( 'No player photo found.', 'mstw-team-rosters' );
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
		}
			
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
	function mstw_tr_build_profile_logo( $team_slug ) {
		//mstw_log_msg( 'in mstw_tr_build_profile_logo ...' );
		
		//this is the default return
		$logo_html = '';
		
		//mstw_log_msg( 'calling mstw_tr_build_team_logo( $team_slug )' );
		//mstw_log_msg( $team_slug );
		
		$logo_html = mstw_tr_build_team_logo( $team_slug );
		//mstw_log_msg( '$logo_html: ' );
		//mstw_log_msg( $logo_html );
		
		if( !$logo_html ) {
			$logo_file_url = plugins_url( ) . "/team-rosters/images/default-images/default-logo.png";
			//mstw_log_msg( '$logo_file_url = ' . $logo_file_url );
			
			$logo_html = "<img src='$logo_file_url' alt='No team logo found.' />";
		}
		
		return $logo_html;
		
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
 
 //-----------------------------------------------------------
 //	12. mstw_tr_is_valid_roster_type - checks if $roster_type  
 //			is valid. Returns true or false
 //
 if( !function_exists( 'mstw_tr_is_valid_roster_type' ) ) {
	function mstw_tr_is_valid_roster_type( $roster_type ) {
		$valid_types = array( 'high-school',
							  'baseball-high-school',
							  'college',
							  'baseball-college',
							  'pro',
							  'baseball-pro',
							  'custom',
							  );
		
		return in_array( $roster_type, $valid_types );
 
	 } //End: mstw_tr_is_valid_roster_type( )
 }
 
 //-----------------------------------------------------------
 //	13. mstw_tr_admin_notice: displays team rosters admin notices
 //		Callback for admin_notices hook. Convenience function to 
 //			call mstw_admin_notice with the right transient 
 //			(so the right notices are displayed)
 //
 if( !function_exists( 'mstw_tr_admin_notice' ) ) {
	function mstw_tr_admin_notice( ) {
		//mstw_log_msg( 'in mstw_tr_admin_notice ...' );
		mstw_admin_notice( 'mstw-tr-admin-notice' );
	} //End: mstw_tr_admin_notice( )
 }
 
 //-----------------------------------------------------------
 //	14. mstw_tr_build_team_colors_html: builds the HTML for team colors
 //			when the 'use team colors' display option is set. Returns
 //			non-empty HTML if the $team is linked to a team in the 
 //			the Schedules & Scoreboards DB.
 //	
 if ( !function_exists( 'mstw_tr_build_team_colors_html' ) ) {
	function mstw_tr_build_team_colors_html( $team = null, $options = null, $type = 'table' ) {
		//mstw_log_msg( 'in mstw_tr_build_team_colors_html ...' );
		//mstw_log_msg( 'mstw_ss_team post type exists: ' . post_type_exists( 'mstw_ss_team' ) );
		//mstw_log_msg( '$team = ' . $team );
		
		$html = ''; // default return string
		
		// return if $team is not specified or mstw_ss_team doesn't exist
		if( $team && post_type_exists( 'mstw_ss_team' ) ) {
			
			//Check that $team is linked to a team in the MSTW S&S DB
			if( $team_obj = mstw_tr_find_team_in_ss( $team ) ) {
				//mstw_log_msg( 'found $team_obj ...' );
				//mstw_log_msg( 'ID= ' . $team_obj->ID );
				
				// check that 'use_team_colors' is set for tables or
				//	'sp_use_team_colors' is set for profiles & galleries
				if ( isset( $options ) ) {
					if ( 'table' == $type ) {
						if( array_key_exists( 'use_team_colors', $options ) && $options['use_team_colors'] ) {
						  $html .= mstw_tr_build_hidden_fields( $team, $team_obj );
						}
					} 
					else {
						if( array_key_exists( 'sp_use_team_colors', $options ) && $options['sp_use_team_colors'] ) {
						   $html .= mstw_tr_build_hidden_fields( $team, $team_obj );
						}
					}

				} //End: if ( isset( $options ) )
				
			} //End: if( $team_obj = mstw_tr_find_team_in_ss( $team ) )
				
		} //End: if( $team && post_type_exists( 'mstw_ss_team' ) )
				
		return $html;
		
	} //End: mstw_tr_build_team_colors_html()
 }

 //-----------------------------------------------------------
 //	15. mstw_tr_build_hidden_fields: builds the hidden fields for the 
 //			JavaScript to use when using the teams DB colors. Called 
 //			by mstw_tr_build_team_colors_html()
 //	
 if ( !function_exists( 'mstw_tr_build_hidden_fields' ) ) {
	function mstw_tr_build_hidden_fields( $team, $team_obj ) {
		//mstw_log_msg( 'in mstw_tr_build_hidden_fields ...' ); 
					
		// jQuery looks first for this element
		$html .= "<mstw-team-colors class='$team' id='$team' style='display: none'>\n";
		
		$bkgd_color = get_post_meta( $team_obj->ID, 'team_primary_bkgd_color', true );
		if( $bkgd_color ) {
			$html .= "<team-color id='bkgd-color' >$bkgd_color</team-color>\n";
		}
		
		$text_color = get_post_meta( $team_obj->ID, 'team_primary_text_color', true );
		if( $text_color ) {
			$html .= "<team-color id='text-color' >$text_color</team-color>\n";
		}
		
		$accent_1 = get_post_meta( $team_obj->ID, 'team_accent_color_1', true );
		if( $accent_1 ) {
			$html .= "<team-color id='accent-1' >$accent_1</team-color>\n";
		}
		
		$accent_2 = get_post_meta( $team_obj->ID, 'team_accent_color_2', true );
		if( $accent_2 ) {
			$html .= "<team-color id='accent-2' >$accent_2</team-color>\n";
		}
		
		$html .= "</mstw-team-colors>\n";
				
		return $html;
		
	} //End: mstw_tr_build_hidden_fields()
 }

 //-----------------------------------------------------------
 //	16. mstw_tr_find_team_in_ss: Determines if the $team is linked 
 //		to a team in the Schedules & Scoreboards plugin DB. 
 //		ARGUMENTS:
 //			$team = TEAM ROSTERS team slug
 //		RETURNS:
 //			null if team is not linked
 //			team object FROM SCHEDULES & SCOREBOARDS if linked
 //	
 if ( !function_exists( 'mstw_tr_find_team_in_ss' ) ) {
	function mstw_tr_find_team_in_ss( $team = null ) {
		//mstw_log_msg( 'in  mstw_tr_find_team_in_ss ... ' );
		//mstw_log_msg( '$team = ' . $team );
		
		$retval = null;
		
		//Check if $team is linked to a team in the MSTW S&S DB
		$team_links = get_option( 'mstw_tr_ss_team_links' );
		
		if ( array_key_exists( $team, $team_links ) && $team_links[ $team ] != -1 ) {
			$link = $team_links[ $team ];
			//mstw_log_msg( "link exists: $team ===> $link " );
					
			$retval = get_page_by_path( $team_links[ $team ], OBJECT, 'mstw_ss_team' );
			
		}
		
		return $retval;	
	} //End: mstw_tr_find_team_in_ss( )
 }
 
 //-----------------------------------------------------------
 //	17. mstw_tr_build_team_logo: Builds the HTML for a team logo 
 //		ARGUMENTS:
 //			$team_slug - $slug for the team IN THE TR DB
 //		RETURNS:
 //			null if logo can't be found/built
 //			logo html with alt, and with link to team site, if available
 //	
 if ( !function_exists( 'mstw_tr_build_team_logo' ) ) {
	function mstw_tr_build_team_logo( $team_slug = null, $type='player' ) {
		//1. Use the team logo from the teams DB, if available,
		//2. Else use the team logo in the theme's /mstw-team-rosters-images/ dir
		//3. Else use the default-logo-team-slug.png from the plugin images dir
		//4. Else use the default-logo.png (mystery player) from the plugin images dir

		//mstw_log_msg( 'in  mstw_tr_build_team_logo ... ' );
		//mstw_log_msg( '$team = ' );
		//mstw_log_msg( $team_slug );
		
		if( null === $team_slug ) {
			return null; 
		}
		
		// These are the defaults if nothing else can be found
		$logo_html = '';
		$alt = '';
		
		if( $team_obj = mstw_tr_find_team_in_ss( $team_slug ) ) { 
			// Look for team logo in Schedules & Scoreboards team DB
			$team_logo = get_post_meta( $team_obj->ID, 'team_alt_logo', true );
			
			if( $team_logo ) {
				$logo_url = $team_logo;
				$alt = get_the_title( $team_obj ) ;
				$logo_html = "<img src='$team_logo' alt='$alt' />";
				$team_site_link = get_post_meta( $team_obj->ID, 'team_link', true );
				if ( $team_site_link ) {
					$logo_html = "<a href=$team_site_link target='_blank'> $logo_html </a>";
				}
			}
		}
		
		if ( empty( $logo_html ) ) {
			// Struck out on the S&S DB, so look for plugin's custom images
			$theme_image = get_stylesheet_directory( ) . '/mstw-team-rosters-images/default-logo-' . $team_slug . '.png';
			//mstw_log_msg( '$theme_image = ' . $theme_image );
			
			// First in the theme/mstw-team-rosters-images directory
			if ( file_exists( $theme_image ) ) {
				// First look in /mstw-team-rosters-images/ directory in the 
				// theme (or child theme) main directory
				$logo_html = dirname( get_stylesheet_uri( ) ) . "/mstw-team-rosters-images/default-logo-$team_slug.png";
				//mstw_log_msg( '$logo_html = ' . $logo_html );
					
			} else {
				// Then in the plugin's /images/default-images/ directory
				$default_img_file = plugin_dir_path( dirname( __FILE__ ) ) . "images/default-images/default-logo-$team_slug.png";
				//mstw_log_msg( '$default_img_dir = ' . $default_img_dir );
				 
				if ( file_exists( $default_img_file ) ) {
					// Then look in the plugin's /images/default-images/ directory
					$logo_html = plugins_url( ) . "/team-rosters/images/default-images/default-logo-$team_slug.png";	
				}
			}
			
			if( !empty( $logo_html ) ) {
				// If an image is found, try to add an alt
				$term = get_term_by( 'slug', $team_slug, 'mstw_tr_team' );
				if( $term ) {
					// Lets alt disappear if there's no term
					$alt = 'alt="'. $term->name . '"';
				}
				$logo_html = "<img src='$logo_html' $alt />";
			}
		} 
		
		return $logo_html;
		
	} //End: mstw_tr_build_team_logo( )
 }
