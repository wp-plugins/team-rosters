<?php
/*-----------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS.PHP
 *		Utility or convenience functions used in both the front and back ends
 *
 *	1. mstw_tr_get_defaults() - returns the default option settings
 *	2. mstw_tr_set_fields_by_format() - sets options based on specified roster format
 *	3. mstw_tr_build_gallery: Builds the player gallery on the front end.
 *
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 
 //-----------------------------------------------------------
 //	mstw_tr_get_defaults: returns the array of ALL option defaults
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
 //	mstw_tr_get_data_fields_columns_defaults: returns the array 
 //		defaults for the data fields & columns options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_data_fields_columns_defaults' ) ) {
	function mstw_tr_get_data_fields_columns_defaults( ) {
		$defaults = array(	
				'team'					=> 'no-team-specified',
				'show_title'			=> 1,
				'roster_type'			=> 'custom',
				'sort_order'			=> 'alpha',
				'name_format'			=> 'last-first',
				'name_label'			=> __( 'Name', 'mstw-team-rosters' ),
				'show_photos'			=> 0,
				'photo_label'			=> __( 'Photo', 'mstw-team-rosters' ),
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'mstw-team-rosters' ),
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
 //	mstw_tr_get_data_fields_columns_defaults: returns the array 
 //		defaults for the data fields & columns options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_defaults' ) ) {
	function mstw_tr_get_roster_table_defaults( ) {
		$defaults = array(	
				'show_title'			=> 0,
				'roster_type'			=> 'custom',
				'links_to_profiles'		=> '',
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
 //	mstw_tr_get_roster_table_colors_defaults: returns the array 
 //		defaults for the data fields & columns options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_colors_defaults' ) ) {
	function mstw_tr_get_roster_table_colors_defaults( ) {
		$defaults = array(	
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
 //	mstw_tr_get_bio_gallery_defaults: returns the array 
 //		defaults for the player profile & team gallery (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_bio_gallery_defaults' ) ) {
	function mstw_tr_get_bio_gallery_defaults( ) {
		$defaults = array(
				'sp_content_title'		=> 'Bio',
				
				'sp_image_width'		=> '',
				'sp_image_height'		=> '',
		
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
//	mstw_tr_set_options_by_format: Sets the options based on the 
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

//---------------------------------------------------------------------------------
//	mstw_tr_build_gallery: Builds the player gallery on the front end. Called by both
//		the gallery shortcode and the player taxonomy page template.
//
if ( !function_exists( 'mstw_tr_build_gallery' ) ) {
	function mstw_tr_build_gallery( $team_slug, $posts, $options, $format ) {	
		
		//$output = "<div class='mstw_tr_gallery'>\n";
	
		if( !empty( $posts ) ) {
		
			// 	Do we really want to set the default here?
			//	Or let the stylesheet handle it? 
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
				//$single_player_template = get_stylesheet_directory( ) . '/single-player.php';
				$single_player_template = plugin_dir_path( __DIR__ ) . 'theme-templates/single-player.php';
				if ( !file_exists( $single_player_template ) ) {
					$single_player_template = get_stylesheet_directory( ) . '/single-player.php';
				}
				
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
			$so_sorry = sprintf( __( 'Sorry, no players were found for %s', 'mstw-team-rosters' ), $team_slug );
			$output .= "<h1>$so_sorry</h1>";
		}
		
		//$output .= "</div> <!--end .mstw_tr_gallery -->";
		return $output;
		
	} //End: mstw_tr_build_gallery()
}

//----------------------------------------------------------------
// 19. mstw_tr_admin_notice - Displays all admin notices; callback for admin_notices action
//
//	ARGUMENTS: 	None
//
//	RETURNS:	None. Displays all messages in the 'mstw_tr_admin_messages' transient
//
if ( !function_exists ( 'mstw_tr_admin_notice' ) ) {
	function mstw_tr_admin_notice( ) {
		//mstw_log_msg( 'in mstw_tr_admin_notice ... ' );
		if ( get_transient( 'mstw_tr_admin_messages' ) !== false ) {
			// get the types and messages
			$messages = get_transient( 'mstw_tr_admin_messages' );
			// display the messages
			foreach ( $messages as $message ) {
				$msg_type = $message['type'];
				$msg_notice = $message['notice'];
				
				// Kludge to get warning messages to appear after page title
				$msg_type = ( $msg_type == 'warning' ) ? $msg_type . ' updated' : $msg_type ;
			?>
				<div class="<?php echo $msg_type; ?>">
					<p><?php echo $msg_notice; ?></p>
				</div>
			
			<?php
			}
			//mstw_log_msg( 'deleting transient ... ' );
			delete_transient( 'mstw_tr_admin_messages' );
			
		} //End: if ( get_transient( sss_admin_messages ) )
	} //End: function sss_admin_notice( )
}

//----------------------------------------------------------------
// 20. mstw_tr_add_admin_notice - Adds admin notices to transient for display on admin_notices hook
//
//	ARGUMENTS: 	$type - type of notice [updated|error|update-nag|warning]
//				$notice - notice text
//
//	RETURNS:	None. Stores notice and type in transient for later display on admin_notices hook
//
if ( !function_exists ( 'mstw_tr_add_admin_notice' ) ) {
	function mstw_tr_add_admin_notice( $type = 'updated', $notice ) {
		//default type to 'updated'
		if ( !( $type == 'updated' or $type == 'error' or $type =='update-nag' or $type == 'warning' ) ) $type = 'updated';
		
		//set the admin message
		$new_msg = array( array(
							'type'	=> $type,
							'notice'	=> $notice
							)
						);

		//either create or add to the sss_admin transient
		$existing_msgs = get_transient( 'mstw_tr_admin_messages' );
		
		if ( $existing_msgs === false ) {
			// no transient exists, create it with the current message
			set_transient( 'mstw_tr_admin_messages', $new_msg, HOUR_IN_SECONDS );
		} 
		else {
			// transient exists, append current message to it
			$new_msgs = array_merge( $existing_msgs, $new_msg );
			set_transient ( 'mstw_tr_admin_messages', $new_msgs, HOUR_IN_SECONDS );
		}
	} //End: function sss_add_admin_notice( )
}
?>
