<?php
/*
Plugin Name: Team Rosters
Plugin URI: http://wordpress.org/extend/plugins/team-rosters/
Description: The Team Rosters Plugin defines a custom type - Player - for use in the MySportTeamWebite framework. It generates a roster table view and player bio view.
Version: 2.0
Author: Mark O'Donnell
Author URI: http://shoalsummitsolutions.com
*/

/*
Team Rosters (Wordpress Plugin)
Copyright (C) 2012 Mark O'Donnell
Contact me at http://shoalsummitsolutions.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* ------------------------------------------------------------------------
 * CHANGE LOG:
 * 20121208-MAO: 
 *	Added code to pre_get_posts() to sort alphabetically by last name or
 *	numerically	by number based on the admin setting.	
 * ------------------------------------------------------------------------*/

/* ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// 'mstw_tr_'  and 'mstw-tr-' derived from "mysportsteamwebsite team roster"
// -----------------------------------------------------------------------*/ 

// ----------------------------------------------------------------
// Set up global variables
	
// Debug messages - used during development	
	$mstw_tr_debug_str = '';
	
//	Who knows?	
	$mstw_tr_msg_str = '';
	
// ----------------------------------------------------------------
// If an admin, load the admin functions (once)

	if ( is_admin() ) {
		// we're in wp-admin
		require_once ( dirname( __FILE__ ) . '/includes/mstw-team-rosters-admin.php' );
    }
	
// ----------------------------------------------------------------
// Set up localization (internationalization)

	add_action( 'init', 'mstw_tr_load_localization' );
		
	function mstw_tr_load_localization( ) {
		
		load_plugin_textdomain( 'mstw-loc-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
	}

// ----------------------------------------------------------------
// Want to show player post type on category pages

	add_filter( 'pre_get_posts', 'my_get_posts' );

	function my_get_posts( $query ) {
		// Need to check the need for this first conditional ... someday
		if ( is_category() && $query->is_main_query() )
			$query->set( 'post_type', array( 'post', 'player' ) ); 
  
		if ( is_tax( 'teams' ) && $query->is_main_query() ) {
			// We are on the player gallery page ...
			// So set the sort order based on the admin settings
			$options = get_option( 'mstw_tr_options' );
			
			// Need the team slug to set query
			$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
			$team_slug = $uri_array[sizeof( $uri_array )-2];
			
			if ( $options['tr_pg_sort_order'] == 'numeric' ) {
				// sort by number ascending
				$query->set( 'post_type', 'player' );
				$query->set( 'teams' , $team_slug );
				$query->set( 'orderby', 'meta_value_num' );    
				$query->set( 'meta_key', '_mstw_tr_number' );     
				$query->set( 'order', 'ASC' );
			}
			else {   //$options['tr_pg_sort_order'] == 'alpha'
				// sort alphabetically by last name ascending 
				$query->set( 'post_type', 'player' );
				$query->set( 'teams' , $team_slug );
				$query->set( 'orderby', 'meta_value' );  
				$query->set( 'meta_key', '_mstw_tr_last_name' );   
				$query->set( 'order', 'ASC' );
			}  
		}		
	}  
	
// ----------------------------------------------------------------
// Add the custom Teams taxonomy ... will act like tags	

	add_action( 'init', 'mstw_create_taxonomy', 0 );

	function mstw_create_taxonomy() {
		register_taxonomy( 
				'teams', 'player', 
				array( 'hierarchical' => true, 
						'labels' => array('name' => __('Teams', 'mstw-loc-domain'), 
											'singular_name' => __('Team', 'mstw-loc-domain')),
						'query_var' => true, 
						'rewrite' => true ) 
				);
	}

// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right

	add_action( 'admin_init', 'mstw_tr_requires_wp_ver' );

	function mstw_tr_requires_wp_ver() {
		global $wp_version;
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "3.4.2", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'".$plugin_data['Name']."' requires WordPress 3.4.2 or higher, and has been deactivated! 
					Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
			}
		}
	}

// ----------------------------------------------------------------
// Load the CSS

	add_action( 'wp_enqueue_scripts', 'mstw_tr_enqueue_styles' );

	function mstw_tr_enqueue_styles () {
		
		/* Find the full path to the css file */
		$mstw_tr_style_url = plugins_url( '/css/mstw-tr-style.css', __FILE__ );
		//$mstw_tr_style_file = WP_PLUGIN_DIR . '/mstw-team-rosters/css/mstw-tr-style.css';
		$mstw_tr_style_file = dirname( __FILE__ ) . '/css/mstw-tr-style.css';
		
		wp_register_style( 'mstw_tr_style', plugins_url( '/css/mstw-tr-style.css', __FILE__ ) );
		
		/* If stylesheet exists, enqueue the style */
		if ( file_exists( $mstw_tr_style_file ) ) {	
			wp_enqueue_style( 'mstw_tr_style' );				
		} 

	}

// --------------------------------------------------------------------------------------
// CUSTOM POST TYPE STUFF
// --------------------------------------------------------------------------------------
// Set-up Action Hooks & Filters for the Player custom post type
// ACTIONS
// 		'init'											mstw_tr_register_post_type
//		'add_metaboxes'									mstw_tr_add_meta
//		'save_posts'									mstw_tr_save_meta
//		'manage_game_schedule_posts_custom_column'		mstw_tr_manage_columns

// FILTERS
// 		'manage_edit-game_schedule_columns'				mstw_tr_edit_columns
//		'post_row_actions'								mstw_tr_remove_the_view
//		
// --------------------------------------------------------------------------------------

add_action( 'init', 'mstw_tr_register_post_types' );

function mstw_tr_register_post_types() {

	/* Set up the arguments for the game post type. */
	$args = array(
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => 'players',
		'query_var'           => 'true',
		'capability_type'     => 'post',
		//'map_meta_cap'        => true,

		/* By default, only 3 caps are needed: 'create_games', 'manage_games', and 'edit_games'. */
		/*'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_player',
			'read_post'              => 'read_player',
			'delete_post'            => 'delete_player',

			// primitive/meta caps
			'create_posts'           => 'create_players',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_players',
			'edit_others_posts'      => 'manage_players',
			'publish_posts'          => 'manage_players',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_players',
			'delete_private_posts'   => 'manage_players',
			'delete_published_posts' => 'manage_players',
			'delete_others_posts'    => 'manage_players',
			'edit_private_posts'     => 'edit_players',
			'edit_published_posts'   => 'edit_players'
		),
		*/
		/* The rewrite handles the URL structure. */
		'rewrite' => array(
			'slug'       => 'players',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		/* What features the post type supports. */
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
		),
		
		/* 'taxonomies' => array('category', 'post_tag'), */

		/* Labels used when displaying the posts. */
		'labels' => array(
			'name'               => __( 'Players',                  'mstw-loc-domain' ),
			'singular_name'      => __( 'Player',                   'mstw-loc-domain' ),
			'menu_name'          => __( 'Players',                  'mstw-loc-domain' ),
			'name_admin_bar'     => __( 'Players',                  'mstw-loc-domain' ),
			'add_new'            => __( 'Add New Player',           'mstw-loc-domain' ),
			'add_new_item'       => __( 'Add New Player',           'mstw-loc-domain' ),
			'edit_item'          => __( 'Edit Player',              'mstw-loc-domain' ),
			'new_item'           => __( 'New Player',               'mstw-loc-domain' ),
			'view_item'          => __( 'View Player',              'mstw-loc-domain' ),
			'search_items'       => __( 'Search Players',           'mstw-loc-domain' ),
			'not_found'          => __( 'No player found',          'mstw-loc-domain' ),
			'not_found_in_trash' => __( 'No player found in trash', 'mstw-loc-domain' ),
			'all_items'          => __( 'All Players',              'mstw-loc-domain' ),
		)
	);

	/* Register the player item post type. */
	register_post_type( 'player', $args );
}

// --------------------------------------------------------------------------------------
// Add the shortcode handler, which will create the a Team Roster table on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_tr_build_roster() to create the output
// --------------------------------------------------------------------------------------

add_shortcode( 'mstw-tr-roster', 'mstw_tr_shortcode_handler' );


function mstw_tr_shortcode_handler( $atts ){
	
	extract( shortcode_atts( array(	'team' => 'no-team', 
									'roster_type' => 'default',
									'show_title' => true,
									'sort_order' => '',
									'show_weight' => 'show'), 
									$atts ) );
		
	$mstw_tr_roster = mstw_tr_build_roster( $team, $roster_type, $show_title, $sort_order, $show_weight );
	
	return $mstw_tr_roster;
}

// --------------------------------------------------------------------------------------
// Called by:	mstw_tr_shortcode_handler
// Builds the Team Roster table as a string (to replace the [shortcode] in a page or post).
// Loops through the Player Custom posts in the "team" category and formats them 
// into a pretty table.
// --------------------------------------------------------------------------------------
function mstw_tr_build_roster( $team, $roster_type, $show_title, $sort_order, $show_weight ) {
	
	// These will come from plugin options someday 
	// Add the colors and stuff
	
	$output = "";
	
	// Settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	// show/hide player weight
	if ( $show_weight == 'hide' or $show_weight == 'hide-weight' ) {
		$hide_weight = 'hide-weight';
	}
	else {
		$hide_weight = $options['tr_hide_weight'];
	}
	
	// Set the roster table format. If default in [shortcode] atts, 
	// then use the default setting from admin page.
	if ($roster_type == 'default') 
		$roster_type = $options['tr_table_default_format'];
	
	$hdr_bkgd =  $options['tr_table_head_bkgd_color'];
	$hdr_color = $options['tr_table_head_text_color'];
	$odd_text = $options['tr_table_odd_row_color'];
	$odd_bkgd = $options['tr_table_odd_row_bkgd'];
	$even_text = $options['tr_table_even_row_color'];
	$even_bkgd = $options['tr_table_even_row_bkgd'];
	
	if ( $show_title == 1 ) {
		//Set the title color
		$title_color = $options['tr_table_title_text_color'];
		
		if ($title_color == "" )
			$title_h1 = '<h1 class="mstw_tr_roster_title">';
		else
			$title_h1 = '<h1 class="mstw_tr_roster_title" ' . 'style="color: ' . $title_color . ';" >';
		
		$term_array = get_term_by( 'slug', $team, 'teams' );
		
		$team_name = $term_array->name; 
		
		$output .= $title_h1 . $team_name . ' Roster' . '</h1>';
	}
	
	// Set the sort order. If an argument is passed through the [shortcode] handler,
	// use it. Otherwise, use the setting from the admin page.
	if ($sort_order == '') {
		if ( $options['tr_table_sort_order'] == 'numeric' ) {
			$sort_key = '_mstw_tr_number';
			$order_by = 'meta_value_num';
		}
		else {   //This is the default if no shortcode arg is passed in.   
			$sort_key = '_mstw_tr_last_name'; 
			$order_by = 'meta_value';
		}
	}
	else if ( $sort_order == 'numeric' ) {
		$sort_key = '_mstw_tr_number';
		$order_by = 'meta_value_num';
	}
	else { // This is the default is a shortcode arg is passed in.
		$sort_key = '_mstw_tr_last_name';
		$order_by = 'meta_value';
	}
	
	// Get the team roster		
	$posts = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'player',
							  'teams' => $team, 
							  'orderby' => $order_by, 
							  'meta_key' => $sort_key,
							  'order' => 'ASC' 
							));						
	
    if( $posts ) {
		// Make table of posts
		// Start with the table header
		// We need to switch based on a setting 'high-school', 'college', 'pro'

        $output .= '<table class="mstw-tr-table">';
		
		// leave this open and check on styles from the admin settings
		$thead = '<thead><tr class="mstw-tr-table-head" ';
		
		// see if we have any styles to add from the admin settings
		if ( $hdr_color != '' || $hdr_bkgd != '' ) {
			//open the style attribute
			$thead .= 'style = "';
			
			if ( $hdr_color != '' ) {
				$thead .= 'color: ' . $hdr_color . '; ';
			}
			
			if ( $hdr_bkgd != '' ) {
				$thead .= 'background-color: ' . $hdr_bkgd . '; ';
			}
			
			// close the style attribute
			$thead .= '"';
		}

		// add thead to the output and close the tr element
		$output .= $thead . '>';
			
		$th_temp = '<th class="mstw-tr-table-head" > ';
			
		$output .= $th_temp . __( 'Nbr', 'mstw-loc-domain' ) . '</th>';
		$output .= $th_temp . __( 'Name', 'mstw-loc-domain' ) . '</th>';
		$output .= $th_temp . __( 'Position', 'mstw-loc-domain' ) . '</th>';
		if ( strpos( $roster_type, 'baseball' ) !== false ) {
			$output .= $th_temp . __( 'Bat', 'mstw-loc-domain' ) . '/' .  __( 'Thw', 'mstw-loc-domain' ) . '</th>';	
		}
		$output .= $th_temp . __( 'Height', 'mstw-loc-domain' ) . '</th>';
		
		if ( $hide_weight != "hide-weight" ) {
			$output .= $th_temp . __( 'Weight', 'mstw-loc-domain' ) . '</th>';
		}
		
		// This is where roster-type specific columns will go
		switch( $roster_type ) {
		case 'high-school':
		case 'baseball-high-school':
			$output .= $th_temp . __( 'Year', 'mstw-loc-domain' ) . '</th>';
			break;
			
		case 'college':
		case 'baseball-college':
			$output .= $th_temp . __( 'Year', 'mstw-loc-domain' ) . '</th>';
			$output .= $th_temp . __( 'Exp', 'mstw-loc-domain' ) . '</th>';
			$output .= $th_temp . __( 'Hometown (Last School)', 'mstw-loc-domain' ) . '</th>';
			break;
			
		case 'pro':
		case 'baseball-pro':
			$output .= $th_temp . __( 'Age', 'mstw-loc-domain' ) . '</th>';
			$output .= $th_temp . __( 'Exp', 'mstw-loc-domain' ) . '</th>';
			$output .= $th_temp . __( 'Last School (Country)', 'mstw-loc-domain' ) . '</th>';
			break;
			
		}
		
        $output = $output . '</tr></thead>';
        
		// Keeps track of even and odd rows. Start with row 1 = odd.
		$even_and_odd = array('even', 'odd');
		$row_cnt = 1; 
		
		// Loop through the posts and make the rows
		foreach($posts as $post){
			// set up some housekeeping to make styling in the loop easier
			// NEEDS TO BE UPDATED
			$even_or_odd_row = $even_and_odd[$row_cnt]; 
			$row_class = 'mstw-tr-' . $even_or_odd_row;
			
			$row_tr = '<tr class="' . $row_class . '" '; 
			
			// if colors are set in the admin settings, they will override the stylesheet
			if ( $even_or_odd_row == 'odd' && ( $odd_text != '' || $odd_bkgd != '' ) ) {
				// do processing for odd row
			
				// open the style attribute
				$row_tr .= 'style="';
				
				if ( $odd_text != '' ) {
					// use the admin setting for odd row text color
					$row_tr .= 'color: ' . $odd_text . '; ';
				}
				
				if ( $odd_bkgd != '' ) {
					// add admin setting for odd row background color
					$row_tr .= 'background-color: ' . $odd_bkgd . '; ';
				} 
				
				// close the style attribute
				$row_tr .= '"';
			} 
			else {
				//do processing for even row
				if ( $even_or_odd_row == 'even' && ($even_text != '' || $even_bkgd != '' ) ) {
				// do processing for even row
			
					// open the style attribute
					$row_tr .= 'style="';
					
					if ( $even_text != '' ) {
						// use the admin setting for even row text color
						$row_tr .= 'color: ' . $even_text . '; ';
					}
					
					if ( $even_bkgd != '' ) {
						// add admin setting for even row background color
						$row_tr .= 'background-color: ' . $even_bkgd . '; ';
					} 
					
					// close the style attribute
					$row_tr .= '"';
				}
			}
			// close the row element
			$row_tr .=  '>';
			
			$row_td = '<td class="' . $row_class . '">'; 
			
			// create the row
			$row_string = $row_tr;			
			
			// column 1: Add the player's number
			$row_string = $row_string. $row_td . get_post_meta( $post->ID, '_mstw_tr_number', true ) . '</td>';
			
			// column 2: Add the player's name
			if ( $options['tr_player_name_format'] == "first-last" ) 
				$player_name = get_post_meta( $post->ID, '_mstw_tr_first_name', true ) . " " . 
				get_post_meta( $post->ID, '_mstw_tr_last_name', true );
			else
				$player_name = get_post_meta( $post->ID, '_mstw_tr_last_name', true ) . ', ' . 
				get_post_meta( $post->ID, '_mstw_tr_first_name', true );
			
			if ( $options['tr_use_player_links'] == "show-links" ) {
				$player_html = '<a href="' .  get_permalink($post->ID) . '?format=' . $roster_type . '" ';
				if ( $options['tr_table_links_color'] != '' ) {
					$player_html .= 'style="color:' . $options['tr_table_links_color'] . ';"';
				}
				$player_html .= '>' . $player_name . '</a>';
			}
			else {
				$player_html = $player_name;
			}
			$row_string =  $row_string . $row_td . $player_html . '</td>';
			
			// column 3: Add the player's postition
			$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_position', true ) . '</td>';
			
			// baseball only: Add bats/throws
			if ( strpos( $roster_type, 'baseball' ) !== false ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_bats', true ) . '/' . get_post_meta( $post->ID, '_mstw_tr_throws', true ) . '</td>';	
			}	
			
			// column 4: Add the player's height
			$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_height', true ) . '</td>';
			
			// column 5: Add the player's weight
			if ( $hide_weight != "hide-weight" ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_weight', true ) . '</td>';
			}
			
			switch( $roster_type ) {
			case 'high-school':
			case 'baseball-high-school':
				// column 5: Add the player's year in school
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_year', true ) . '</td>';
				break;
				
			case 'college':
			case 'baseball-college':
				// column 6: Add the player's year in school
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_year', true ) . '</td>';
				
				// column 7: Add the player's experience
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_experience', true ) . '</td>';
				
				// column 8: Add the player's hometown and school
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_home_town', true ) . 
					' (' . get_post_meta( $post->ID, '_mstw_tr_last_school', true ) . ') </td>';
				
				break;
				
			case 'pro':
			case 'baseball-pro':
				// column 6: Add the player's age
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_age', true ) . '</td>';
				
				// column 7: Add the player's experience
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_experience', true ) . '</td>';
				
				// column 8: Add the player's last school and country
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_last_school', true ) . 
					' (' . get_post_meta( $post->ID, '_mstw_tr_country', true ) . ') </td>';
				break;
				
			}
			
			$output = $output . $row_string;
			
			$row_cnt = 1- $row_cnt;  // Get the styles right
			
		} // end of foreach post or end of table content
		
		$output = $output . '</table>';
	}
	else { // No posts were found
	
		$output =  $output . '<h3>' . __( 'Sorry, No players found for team: ' . $team, 'mstw-loc-domain' ) . '</h3>';
		
	}
	
	return $output;
	
}
?>