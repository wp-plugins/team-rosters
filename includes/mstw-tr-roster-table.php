<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-roster-table.php
 *		Code for the mstw-roster-table shortcode
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2015 Mark O'Donnell (mark@shoalsummitsolutions.com)
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

 // --------------------------------------------------------------------------------------
 // Add the table shortcode handler, which will create the a Team Roster table on the user side.
 // Handles the displayshortcode parameters, and display settings if there were any, 
 // then calls mstw_tr_build_roster_table() to create the output
 // --------------------------------------------------------------------------------------
 //I have no idea why I needed to remove_shortcode before adding it, but I did
 //remove_shortcode( 'mstw_roster_table' );
 
 //add_shortcode( 'mstw_roster_table', 'mstw_tr_roster_table_handler' );
 
 if ( !function_exists( 'mstw_tr_roster_table_handler' ) ) {
	function mstw_tr_roster_table_handler( $atts ){
		
		mstw_log_msg( 'in mstw_tr_roster_table_handler ... ' );

		// get the options set in the admin screen
		$options = get_option( 'mstw_tr_options' );
		
		// Remove all keys with empty values
		//foreach ( $options as $k=>$v ) {
			//if( $v == '' ) {
				//unset( $options[$k] );
			//}
		//}

		// and merge them with the defaults
		$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		
		// then merge the parameters passed to the shortcode with the result									
		$attribs = shortcode_atts( $args, $atts );
		
		//mstw_log_msg( 'calling mstw_tr_build_roster_table() with $attribs:' );
		//mstw_log_msg( $atts );
		//mstw_log_msg( $attribs );
		
		$mstw_tr_roster = mstw_tr_build_roster_table( $attribs );
		
		return $mstw_tr_roster;
	}
 }
 
 // --------------------------------------------------------------------------------------
 // Called by:	mstw_tr_table_handler
 // Builds the Team Roster table as a string (to replace the [shortcode] in a page or post).
 // Loops through the Player Custom posts in the "team" category and formats them 
 // into a pretty table.
 // --------------------------------------------------------------------------------------
 if( !function_exists( 'mstw_tr_build_roster_table' ) ) {
	function mstw_tr_build_roster_table( $attribs ) {
		mstw_log_msg( 'in mstw_tr_build_roster_table ...' );
		
		// These will come from plugin options someday 
		// Add the colors and stuff
		
		//$output = '<pre>BEFORE:' . print_r( $attribs, true ) . '</pre>';
		//return $output;
		
		$attribs = mstw_tr_set_fields( $attribs['roster_type'], $attribs );
		
		//mstw_log_msg( '$attribs: ' );
		//mstw_log_msg( $attribs );
		
		extract( $attribs );
		
		//new attribute because single template isn't moved anymore - link_to_player_pages
		$link_to_player_pages = 1;
		
		if ( $team == 'no-team-specified' ) {
			$output = '<h3>No Team Specified </h3>';
			return $output;
		}
		
		$output = "";
			
		// Settings from the admin page
		// THIS IS OKAY ... ATTRIBS HAVE ALREADY BEEN EXTRACTED
		
		$options = get_option( 'mstw_tr_options' );
		
		// Set the roster table format. If default in [shortcode] atts, 
		// then use the default setting from admin page.
		if ( $roster_type == 'default' or $roster_type == '' ) 
			$roster_type = $options['roster_type'];
		
		if ( $show_title == 1 ) {
			//Set the title color
			
			$term_obj = get_term_by( 'slug', $team, 'mstw_tr_team', OBJECT );
			//mstw_log_msg( ' in mstw_tr_build_roster ... $team:' . $team );
			//mstw_log_msg( $term_obj );
			$team_name = ( $term_obj ) ? $term_obj->name : $team;
			
			$team_class = 'mstw_tr_roster_title mstw_tr_roster_title_' . $team;
			
			$title_h1 = '<h1 class="' . $team_class . '">'; 
			
			$output .= $title_h1 . $team_name . ' Roster' . '</h1>';
		}
		
		// Set the sort order	
		switch ( $sort_order ) {
			case'numeric':
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
		
		
		// Get the team roster		
		$posts = get_posts( array( 'numberposts' => -1,
								   'post_type' => 'mstw_tr_player',
								   'mstw_tr_team' => $team, 
								   'orderby' => $order_by, 
								   'meta_key' => $sort_key,
								   'order' => 'ASC' 
								   ) 
							);						
		
		if( $posts ) {
			// Make table of posts
			// Start with the table header

			$team_class = 'mstw-tr-table-' . $team;
			$output .= '<table class="mstw-tr-table ' . $team_class . '">';
			
			$output .= mstw_tr_build_roster_table_header( $attribs );
			
			
			
			// Keeps track of even and odd rows. Start with row 1 = odd.
			$even_and_odd = array('even', 'odd');
			$row_cnt = 1; 
			
			// Loop through the posts and make the rows
			foreach($posts as $post){
				// set up some housekeeping to make styling in the loop easier
				// WE DON'T DO THE CSS THIS WAY ANYMORE
				//$even_or_odd_row = $even_and_odd[$row_cnt]; 
				//$row_class = 'mstw-tr-' . $even_or_odd_row;
				
				//$row_tr = '<tr class="' . $row_class . '">'; 
				$row_tr = '<tr>';
				//$row_td = '<td class="' . $row_class . '">'; 
				$row_td = '<td>';
				
				// create the row
				$row_string = $row_tr;	

				// PHOTO COLUMN
				if ( $show_photos ) {
					$row_string .= '<td>' . mstw_tr_build_player_photo( $post, $team, $attribs, 'table' ) . '</td>';
				}
				
				// NUMBER COLUMN
				if ( $show_number ) {
					$row_string .= mstw_tr_add_player_number( $post, $row_td );
				}
				
				// PLAYER'S NAME COLUMN ... ALWAYS SHOW
				$row_string .= '<td>' . mstw_tr_build_player_name( $post, $attribs, 'table'  ) . '</td>';  
				
				// POSITION COLUMN
				if ( $show_position ) {
					$row_string .= $row_td . get_post_meta( $post->ID, 'player_position', true ) . '</td>';
				}
				
				// BATS/THROWS COLUMN (baseball)
				if ( $show_bats_throws ) {
					
					//$bats = get_post_meta( $post->ID, 'player_bats', true );
					//$throws = get_post_meta( $post->ID, 'player_throws', true );
					$row_string =  $row_string . $row_td . 
							mstw_tr_build_bats_throws( $post ) . '</td>';	
				}	
				
				// HEIGHT COLUMN
				if ( $show_height ) {
					$row_string .= $row_td . get_post_meta( $post->ID, 'player_height', true ) . '</td>';
				}
				
				// WEIGHT COLUMN
				if ( $show_weight ) {
					$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_weight', true ) . '</td>';
				}
				
				// YEAR (in school) COLUMN
				if ( $show_year ) {
					$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_year', true ) . '</td>';
				}
				
				// AGE column
				if ( $show_age ) {
					$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_age', true ) . '</td>';
				}
				
				// EXPERIENCE column
				if ( $show_experience ) {
					$row_string =  $row_string . $row_td . get_post_meta( $post->ID, 'player_experience', true ) . '</td>';
				}
				
				// HOMETOWN column
				if ( $show_home_town ) {
					if ( $roster_type == 'college' or $roster_type == 'baseball-college' ) {
						$row_string .=  $row_td . get_post_meta( $post->ID, 'player_home_town', true ) . 
						' (' . get_post_meta( $post->ID, 'player_last_school', true ) . ') </td>';
					}
					else if ( $roster_type == 'custom' ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_home_town', true )  . '</td>';
					}
				}
				
				// LAST SCHOOL column
				if ( $show_last_school ) {
					if ( $roster_type == 'pro' or $roster_type == 'baseball-pro' ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_last_school', true ) . 
						' (' . get_post_meta( $post->ID, 'player_country', true ) . ') </td>';
					}
					else if ( $roster_type == 'custom' ) {
						$row_string .= $row_td . get_post_meta( $post->ID, 'player_last_school', true )  . '</td>';
					}
				}
				
				// COUNTRY column
				if ( $show_country and $roster_type == 'custom' ) {
					$row_string .= $row_td . get_post_meta( $post->ID, 'player_country', true )  . '</td>';
				}
				
				// OTHER column
				if ( $show_other_info and $roster_type == 'custom' ) {
					$row_string .= $row_td . get_post_meta( $post->ID, 'player_other', true ) .'</td>';
				}
				
				$output = $output . $row_string;
				
				$row_cnt = 1- $row_cnt;  // Get the styles right
				
			} // end of foreach post or end of table content
			
			$output .= '</table>';
		}
		else { // No posts were found
		
			$output =  $output . '<h3>' . sprintf( __( 'Sorry, No players found for team: %s', 'mstw-team-rosters' ), $team ) . '</h3>';
			
		}
		
		return $output;
		
	}
 }
 
 if( !function_exists( 'mstw_tr_build_roster_table_header' ) ) {
	function mstw_tr_build_roster_table_header( $args ) {
		// leave this open and check on styles from the admin settings
			$ret_html = '<thead><tr>';
			
			$roster_type = $args['roster_type'];
			
			// Check the PHOTO Column
			if ( $args['show_photos'] ) {
				$ret_html .= '<th>' . $args['photo_label'] . '</th>';
			}
			
			if ( $args['show_number'] ) {	
				$ret_html .= '<th>' . $args['number_label'] . '</th>';
			}
			
			// Always show the NAME column
			$ret_html .= '<th>' . $args['name_label'] . '</th>';
			
			// POSITION column
			if ( $args['show_position'] ) {
				$ret_html .= '<th>' .$args['position_label'] . '</th>';
			}
			
			// BATS/THROWS column
			if ( $args['show_bats_throws'] ) {
				$ret_html .= '<th>' . $args['bats_throws_label'] . '</th>';
			}
			
			// HEIGHT column
			if ( $args['show_height'] ) {
				$ret_html .= '<th>' . $args['height_label'] . '</th>';
			}
			
			// WEIGHT column
			if ( $args['show_weight'] ) {
				$ret_html .= '<th>' . $args['weight_label'] . '</th>';
			}
			
			// YEAR column
			if ( $args['show_year'] ) {
				$ret_html .= '<th>' . $args['year_label'] . '</th>';
			}
			
			// AGE column
			if ( $args['show_age'] ) {
				$ret_html .= '<th>' . $args['age_label'] . '</th>';
			}
			
			// EXPERIENCE column
			if ( $args['show_experience'] ) {
				$ret_html .= '<th>' . $args['experience_label'] . '</th>';
			}
			
			// HOMETOWN column
			if ( $args['show_home_town'] ) {
				if ( $roster_type == 'college' or $roster_type == 'baseball-college' ) {
					$ret_html .= '<th>' . $args['home_town_label'] . ' ('. $args['last_school_label'] . ')' . '</th>';
				}
				else if ( $roster_type == 'custom' ) {
					$ret_html .= '<th>' . $args['home_town_label'] . '</th>';
				}
			}
			
			// LAST SCHOOL column
			if ( $args['show_last_school'] ) {
				if ( $roster_type == 'pro' or $roster_type == 'baseball-pro' ) {
					$ret_html .= '<th>' . $args['last_school_label'] . ' ('. $args['country_label'] . ')' . '</th>';
				}
				else if ( $roster_type == 'custom' ) {
					$ret_html .= '<th>' . $args['last_school_label'] . '</th>';
				}
			}
			
			// COUNTRY column
			if ( $args['show_country'] and $roster_type == 'custom' ) {
				$ret_html .= '<th>' . $args['country_label'] . '</th>';
			}
			
			// OTHER column
			if ( $args['show_other_info'] and $roster_type == 'custom' ) {
				$ret_html .= '<th>' . $args['other_info_label'] . '</th>';
			}
			
			$ret_html .= '</tr></thead>';
			
			return $ret_html;
		
	} //End: mstw_tr_build_roster_table_header()
 }
 
 if( !function_exists( 'mstw_tr_add_player_number' ) ) {
	 function mstw_tr_add_player_number( $post, $row_td ) {
		return $row_td . get_post_meta( $post->ID, 'player_number', true ) . '</td>';
	 } //End: mstw_tr_add_player_number()
 }
 
 if( !function_exists( 'mstw_tr_add_player_name' ) ) {
	function mstw_tr_add_player_name( $post, $args ) {
		
		switch( $args['name_format'] ) {
			case 'first-last':
				$player_name = get_post_meta( $post->ID, 'player_first_name', true ) . " " . 
				get_post_meta( $post->ID, 'player_last_name', true );
				break;
			case 'first-only':
				$player_name = get_post_meta( $post->ID, 'player_first_name', true );
				break;	
			case 'last-only':
				$player_name = get_post_meta( $post->ID, 'player_last_name', true );
				break;
			default: //It's going to be last, first
				$player_name = get_post_meta( $post->ID, 'player_last_name', true ) . ', ' . 
				get_post_meta( $post->ID, 'player_first_name', true );
				break;
		}
		
		//mstw_log_msg( 'in mstw_tr_add_player_name ... $args:' );
		//mstw_log_msg( $args );
		
		if ( $args['links_to_profiles'] ) {
			$player_html = '<a href="' .  get_permalink($post->ID) . '?roster_type=' . $args['roster_type'] . '" ';
			$player_html .= '>' . $player_name . '</a>';
		}
		else {
			$player_html = $player_name;
		}
		
		return '<td>' . $player_html . '</td>';
		
	 } //End: mstw_tr_add_player_name()
 }
?>