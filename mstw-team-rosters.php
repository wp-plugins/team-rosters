<?php
/*
	Plugin Name: Team Rosters
	Plugin URI: http://wordpress.org/extend/plugins/team-rosters/
	Description: The Team Rosters Plugin defines a custom type - Player - for use in the MySportTeamWebite framework. It generates a roster table view and player bio view.
	Version: 3.1.2
	Author: Mark O'Donnell
	Author URI: http://shoalsummitsolutions.com
	Text Domain: mstw-team-rosters
	Domain Path: /lang
*/

/*---------------------------------------------------------------------------
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

//-----------------------------------------------------------------
// Set up global variables
//	
// NONE ??

//-----------------------------------------------------------------
// Initialize the plugin
//
	add_action( 'init', 'mstw_tr_init' );
	
	function mstw_tr_init( ) {
		//------------------------------------------------------------------------
		// "Helper functions" used throughout the MSTW plugin family
		//
		require_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-utility-functions.php' );
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-utility-functions loaded ' );
		
		//------------------------------------------------------------------------
		// Utility functions specific to Team Rosters [functions are wrapped]
		//
		require_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-utility-functions.php' );
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-utility-functions loaded ' );
		
		//--------------------------------------------------------------------------------
		// REGISTER THE MSTW TEAM ROSTERS CUSTOM POST TYPES & TAXONOMIES
		//	mstw_tr_player, mstw_tr_team
		//
		include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-cpts.php' );
		mstw_tr_register_cpts( );
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-cpts loaded ' );
		
		/*
		//------------------------------------------------------------------------
		// Functions for MSTW schedule table shortcode and widget
		//
		include_once( MSTW_SS_INCLUDES_DIR . '/mstw-tr-schedule-table.php' );
		
		//------------------------------------------------------------------------
		// Functions for MSTW venue table shortcode
		//
		include_once( MSTW_SS_INCLUDES_DIR . '/mstw-tr-venue-table.php' );
		
		//------------------------------------------------------------------------
		// Functions for MSTW countdown timer shortcode
		//
		include_once( MSTW_SS_INCLUDES_DIR . '/mstw-tr-countdown-timer.php' );
		
		//------------------------------------------------------------------------
		// Functions for MSTW schedule slider shortcode
		//
		include_once( MSTW_SS_INCLUDES_DIR . '/mstw-tr-schedule-slider.php' );
		
		//------------------------------------------------------------------------
		// Functions for MSTW scoreboard shortcode
		//
		include_once( MSTW_SS_INCLUDES_DIR . '/mstw-tr-scoreboard.php' );
		*/
		
		

		//------------------------------------------------------------------------
		// If an admin screen, load the admin functions (gotta have 'em)
		
		if ( is_admin( ) )
			include_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-admin.php' );
		
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-admin loaded ' );
			
			
		//mstw_log_msg( 'in mstw_tr_init ... taxonomies:' );
		//mstw_log_msg( get_taxonomies( ) );*/

}


// ----------------------------------------------------------------
// On activation, check the version of WP and set up the 'mstw_tr'
//		roles and capabilites
//
	register_activation_hook( __FILE__, 'mstw_tr_activate' );	

	function mstw_tr_activate( ) {
	
		mstw_tr_check_wp_version( '4.0' ); //tested - OK
		
		//THIS IS A MESS. NEED TO FIX
		//mstw_tr_add_user_roles( );
		
		/*$result = add_role( 'mstw_tr_admin', 'MSTW Team Rosters Admin', 
							array(	'read'						=> true,
									'edit_mstw_tr_player' 				=> true,
									'read_mstw_tr_player' 				=> true,
									'delete_mstw_tr_player' 			=> true,
									'edit_mstw_tr_players'				=> true,
									'edit_others_mstw_tr_players'		=> true,
									'edit_others_mstw_tr_players'		=> true,
									'publish_mstw_tr_players'			=> true,
									'read_private_mstw_tr_players'		=> true,
									'delete_mstw_tr_players'			=> true,
									'delete_private_mstw_tr_players'	=> true,
									'delete_published_mstw_tr_players'	=> true,
									'delete_others_mstw_tr_players'		=> true,
									'edit_private_mstw_tr_players'		=> true,
									'edit_published_mstw_tr_players'	=> true
									)
									
								);*/
	}
	
// ----------------------------------------------------------------
// Create the mstw_tr_admin role on activation
//
	function mstw_tr_check_wp_version( $version = '4.0' ) {
		global $wp_version;
		
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare( $wp_version, $version, "<" ) ) {

			// plugin shouldn't be active, but just in case ...
			if( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
			}
				
			$die_msg = sprintf( __( '%s requires WordPress %s or higher, and has been deactivated! Please upgrade WordPress and try again.', 'mstw-team-rosters' ), $plugin_data['Name'], $version );
			
			die( $die_msg );

		}
	}
	
	//------------------------------------------------------------------------
	// Creates the MSTW Team Roster roles and adds the MSTW capabilities
	//		to those roles as well as the WP administrator and editor roles
	//
	// THIS IS A MESS!!!
	//
	function mstw_tr_add_user_roles( ) {

		//This allows a reset of capabilities for development
		remove_role( 'mstw_admin' );
		
		$result = 	add_role( 'mstw_admin', __( 'MSTW Admin', 'mstw-team-rosters' ),
							  array( 'manage_mstw_plugins'  => true,
									 'edit_posts' 			=> true
									 //true allows; use false to deny
									) 
							 );
							 
		if ( $result != null ) {
			$result->add_cap( 'view_mstw_menus' );
			mstw_tr_add_caps( $result, null, 'schedule', 'schedules' );
			mstw_tr_add_caps( $result, null, 'team', 'teams' );
			mstw_tr_add_caps( $result, null, 'game', 'games' );
			mstw_tr_add_caps( $result, null, 'sport', 'sports' );
			mstw_tr_add_caps( $result, null, 'venue', 'venues' );
		}
		else 
			mstw_log_msg( "Oops, failed to add MSTW Admin role. Already exists?" );
		
		//
		// mstw_tr_admin role - can do everything in Schedules & Scoreboards plugin
		//
		
		//This allows a reset of capabilities for development
		remove_role( 'mstw_tr_admin' );
		
		$result = 	add_role( 'mstw_tr_admin',
							  __( 'MSTW Schedules & Scoreboards Admin', 'mstw-schedules-scoreboards' ),
							  array( 'manage_mstw_schedules'  => true, 
									  'read' => true
									  //true allows; use false to deny
									) 
							 );
		
		if ( $result != null ) {
			$result->add_cap( 'view_mstw_tr_menus' );
			mstw_tr_add_caps( $result, null, 'schedule', 'schedules' );
			mstw_tr_add_caps( $result, null, 'team', 'teams' );
			mstw_tr_add_caps( $result, null, 'game', 'games' );
			mstw_tr_add_caps( $result, null, 'sport', 'sports' );
			mstw_tr_add_caps( $result, null, 'venue', 'venues' );
		}
		else {
			mstw_log_msg( "Oops, failed to add MSTW Schedules & Scoreboards Admin role. Already exists?" );
		}
	
		//
		// site admins can play freely
		//
		$role = get_role( 'administrator' );
		
		mstw_tr_add_caps( $role, null, 'schedule', 'schedules' );
		mstw_tr_add_caps( $role, null, 'team', 'teams' );
		mstw_tr_add_caps( $role, null, 'game', 'games' );
		mstw_tr_add_caps( $role, null, 'sport', 'sports' );
		mstw_tr_add_caps( $result, null, 'venue', 'venues' );
		
		//
		// site editors can play freely
		//
		$role = get_role( 'editor' );
		
		mstw_tr_add_caps( $role, null, 'schedule', 'schedules' );
		mstw_tr_add_caps( $role, null, 'team', 'teams' );
		mstw_tr_add_caps( $role, null, 'game', 'games' );
		mstw_tr_add_caps( $role, null, 'sport', 'sports' );
		mstw_tr_add_caps( $result, null, 'venue', 'venues' );
	
	} //End: mstw_tr_add_user_roles( )

//------------------------------------------------------------------------
// Adds the MSTW capabilities to either the $role_obj or $role_name using
//		the custom post type names (from the capability_type arg in
//		register_post_type( )
//
//	ARGUMENTS:
//		$role_obj: a WP role object to which to add the MSTW capabilities. Will
//					be used of $role_name is none (the default)
//		$role_name: a WP role name to which to add the MSTW capabilities. Will
//					be used if present (not null)
//		$cpt: the custom post type for the capabilities 
//				( map_meta_cap is set in register_post_type() )
//		$cpt_s: the plural of the custom post type
//				( $cpt & $cpt_s must match the capability_type argument
//					in register_post_type( ) )
//	RETURN: none
//
	function mstw_tr_add_caps( $role_obj = null, $role_name = null, $cpt, $cpt_s ) {
		$cap = array( 'edit_', 'read_', 'delete_' );
		$caps = array( 'edit_', 'edit_others_', 'publish_', 'read_private_', 'delete_', 'delete_published_', 'delete_others_', 'edit_private_', 'edit_published_' );
		
		if ( $role_name != null ) {
			$role_obj = get_role( $role_name );
		}
		
		if( $role_obj != null ) {
			//'singular' capabilities
			foreach( $cap as $c ) {
				$role_obj -> add_cap( $c . $cpt );
			}
			
			//'plural' capabilities
			foreach ($caps as $c ) {
				$role_obj -> add_cap( $c . $cpt_s );
			}
			
			$role_obj -> add_cap( 'read' );
		}
		else {
			$role_name = ( $role_name == null ) ? 'null' : $role_name;
			mstw_log_msg( 'Bad args passed to mstw_tr_add_caps( ). $role_name = ' . $role_name . ' and $role_obj = null' );
		}
		
	} //End: mstw_tr_add_caps( )
	
//-----------------------------------------------------------------
// filter so single-mstw_tr_player template does  not need to be in the theme directory
//
	add_filter( "single_template", "mstw_tr_single_player_template" );
	
	function mstw_tr_single_player_template( $single_template ) {
		 global $post;

		 if ($post->post_type == 'mstw_tr_player') {
			  $single_template = dirname( __FILE__ ) . '/theme-templates/single-mstw_tr_player.php';
			  //echo '$single_template= ' . $single_template;
			  //die;
		 }
		 
		 return $single_template;
		 
	} //End: mstw_tr_single_player_template()	


	
// ----------------------------------------------------------------
// Add the CSS code to the header
//

	add_filter( 'wp_head', 'mstw_tr_add_css');
		
	function mstw_tr_add_css( ) {
		//header("Content-type: text/css");
        //echo "/* mstw_tr_add_css was here */";
		
		$options = get_option( 'mstw_tr_options' );
		
		echo '<style type="text/css">';
		
		echo "tr.mstw-tr-table-head { \n";
			echo mstw_tr_build_css_rule( $options, 'tr_table_head_text_color', 'color' );
			echo mstw_tr_build_css_rule( $options, 'tr_table_head_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "h1.team-head-title { \n";
			echo mstw_tr_build_css_rule( $options, 'tr_table_title_text_color', 'color' );		
		echo "} \n";
		
		echo 'tr.mstw-tr-odd {';
			echo mstw_tr_build_css_rule( $options, 'tr_table_odd_row_color', 'color' );
			echo mstw_tr_build_css_rule( $options, 'tr_table_odd_row_bkgd', 'background-color' );
		echo '}';
		
		echo 'tr.mstw-tr-even {';
			echo mstw_tr_build_css_rule( $options, 'tr_table_even_row_color', 'color' );
			echo mstw_tr_build_css_rule( $options, 'tr_table_even_row_bkgd', 'background-color' );
		echo '}';
		
		echo "tr.mstw-tr-odd a, tr.mstw-tr-even a { \n";
			echo mstw_tr_build_css_rule( $options, 'tr_table_links_color', 'color' );
		echo "} \n";
		
		//Rules for single player
		echo "div.player-header { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "#player-name-nbr { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
		echo "} \n";
		
		echo ".player-bio { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_bio_border_color', 'border-color' );
		echo '}';
		
		echo ".player-bio h1 { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_bio_header_color', 'color' );
		echo "}\n";
		
		echo ".player-bio { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_bio_text_color', 'color' );
		echo "}\n";
		
		echo ".player-bio { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_bio_bkgd_color', 'background-color' );
		echo "}\n";
		
		echo "h1.player-head-title { \n";
			echo mstw_tr_build_css_rule( $options, 'tr_table_title_text_color', 'color' );
		echo "}\n";
		
		echo "h1.mstw_tr_roster_title { \n";
			echo mstw_tr_build_css_rule( $options, 'tr_table_title_text_color', 'color' );
		echo "}\n";
		
		// Rules for player galleries
		echo ".player-tile { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo ".player-tile { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
		echo "} \n";
		
		echo ".player-name-number { \n";
			echo mstw_tr_build_css_rule( $options, 'sp_main_text_color', 'color' );
			
		echo "} \n";
		
		echo ".player-name-number a { \n";
			echo mstw_tr_build_css_rule( $options, 'gallery_links_color', 'color' );
		echo "}\n";
		
		echo '</style>';
		
	}
	
	function mstw_tr_build_css_rule( $options_array, $option_name, $css_rule ) {
		if ( isset( $options_array[$option_name] ) and !empty( $options_array[$option_name] ) ) {
			return $css_rule . ":" . $options_array[$option_name] . "; \n";	
		} 
		else {
			return "";
		}
	}
	
// ----------------------------------------------------------------
// Set up localization (internationalization)

	add_action( 'init', 'mstw_tr_load_localization' );
		
	function mstw_tr_load_localization( ) {
		
		load_plugin_textdomain( 'mstw-loc-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		
	}

// ----------------------------------------------------------------
// Want to show player post type on category pages

	add_filter( 'pre_get_posts', 'mstw_tr_get_posts' );

	function mstw_tr_get_posts( $query ) {
		// Need to check the need for this first conditional ... someday
		if ( is_category() && $query->is_main_query() )
			$query->set( 'post_type', array( 'post', 'mstw_tr_player' ) ); 
  
		if ( is_tax( 'mstw_tr_team' ) && $query->is_main_query() ) {
			// We are on the player gallery page ...
			// So set the sort order based on the admin settings
			$options = get_option( 'mstw_tr_options' );
			
			// Need the team slug to set query
			$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
			$team_slug = $uri_array[sizeof( $uri_array )-2];
			
			// sort alphabetically by last name ascending by default
			$query->set( 'post_type', 'mstw_tr_player' );
			$query->set( 'mstw_tr_team' , $team_slug );
			$query->set( 'orderby', 'meta_value' );  
			$query->set( 'meta_key', '_mstw_tr_last_name' );   
			$query->set( 'order', 'ASC' );
			
			if ( array_key_exists( 'tr_pg_sort_order', $options ) ) {
				if ( $options['tr_pg_sort_order'] == 'numeric' ) {
					// sort by number ascending
					$query->set( 'post_type', 'mstw_tr_player' );
					$query->set( 'mstw_tr_team' , $team_slug );
					$query->set( 'orderby', 'meta_value_num' );    
					$query->set( 'meta_key', '_mstw_tr_number' );     
					$query->set( 'order', 'ASC' );
				}	 
			}
		}
	}  

// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right

	//add_action( 'admin_init', 'mstw_tr_requires_wp_ver' );

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

// First want to make sure thumbnails are active in the theme before adding them via the 
//	register_post_type call in the 'init' action

	add_action( 'after_setup_theme', 'mstw_tr_add_feat_img' );
	
	function mstw_tr_add_feat_img( ) {
		if ( function_exists( 'add_theme_support' ) and function_exists( 'get_theme_support' ) ) {
			if ( get_theme_support( 'post-thumbnails' ) === false ) {
				add_theme_support( 'post-thumbnails' );
			}
		}
	}

// --------------------------------------------------------------------------------------
// Add the table shortcode handler, which will create the a Team Roster table on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_tr_build_roster() to create the output
// --------------------------------------------------------------------------------------

add_shortcode( 'mstw-tr-roster', 'mstw_tr_table_handler' );

function mstw_tr_table_handler( $atts ){

	// get the options set in the admin screen
	$options = get_option( 'mstw_tr_options' );
	//$output = '<pre>OPTIONS:' . print_r( $options, true ) . '</pre>';
	
	// Remove all keys with empty values
	//foreach ( $options as $k=>$v ) {
		//if( $v == '' ) {
			//unset( $options[$k] );
		//}
	//}
	//$output .= '<pre>FILTERED OPTIONS:' . print_r( $options, true ) . '</pre>';
	
	// and merge them with the defaults
	$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
	//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
	
	// then merge the parameters passed to the shortcode with the result									
	$attribs = shortcode_atts( $args, $atts );
	//$output .= '<pre>ATTS:' . print_r( $atts, true ) . '</pre>';
	//$output .= '<pre>ATTRIBS:' . print_r( $attribs, true ) . '</pre>';
	
	$mstw_tr_roster = mstw_tr_build_roster( $attribs );
	
	return $mstw_tr_roster;
}

// --------------------------------------------------------------------------------------
// Add the gallery shortcode handler, which will create the a Team Gallery on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_tr_build_gallery( ) to create the output
// --------------------------------------------------------------------------------------
add_shortcode( 'mstw-tr-gallery', 'mstw_tr_gallery_handler' );

function mstw_tr_gallery_handler( $atts ){

	// get the options set in the admin screen
	$options = get_option( 'mstw_tr_options' );
	//$output = '<pre>OPTIONS:' . print_r( $options, true ) . '</pre>';
	
	// Remove all keys with empty values
	foreach ( $options as $k=>$v ) {
		if( $v == '' ) {
			unset( $options[$k] );
		}
	}
	
	// and merge them with the defaults
	$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
	//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
	
	// then merge the parameters passed to the shortcode with the result									
	$attribs = shortcode_atts( $args, $atts );
	//$output .= '<pre>ATTS:' . print_r( $atts, true ) . '</pre>';
	//$output .= '<pre>ATTRIBS:' . print_r( $attribs, true ) . '</pre>';
	
	$attribs = mstw_tr_set_fields( $attribs['roster_type'], $attribs );
	
	//get the team slug
	if ( $attribs['team'] == 'no-team-specified' )
		return '<h3>No Team Specified </h3>';
	else
		$team_slug = $attribs['team'];
		
	// Set the sort order	
	switch ( $attribs['sort_order'] ) {
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
	
	// Get the posts		
	$posts = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'mstw_tr_player',
							  'mstw_tr_team' => $team_slug, 
							  'orderby' => $order_by, 
							  'meta_key' => $sort_key,
							  'order' => 'ASC' 
							));		
	
	//Now gotta grab the posts
	
	$mstw_tr_gallery = mstw_tr_build_gallery( $team_slug, $posts, $attribs, $attribs['roster-type'] );
	
	return $mstw_tr_gallery;
}

// --------------------------------------------------------------------------------------
// Called by:	mstw_tr_table_handler
// Builds the Team Roster table as a string (to replace the [shortcode] in a page or post).
// Loops through the Player Custom posts in the "team" category and formats them 
// into a pretty table.
// --------------------------------------------------------------------------------------
function mstw_tr_build_roster( $attribs ) {
	
	// These will come from plugin options someday 
	// Add the colors and stuff
	
	//$output = '<pre>BEFORE:' . print_r( $attribs, true ) . '</pre>';
	//return $output;
	
	$attribs = mstw_tr_set_fields( $attribs['roster_type'], $attribs );
	
	//FOR INITIAL DEBUGGING 
	//$output . = '<p>Roster_type: ' . $attribs['roster_type'] . '</p>';
	//$output .= '<p>strpos( $roster_type, "baseball"): ' . strpos( $attribs['roster_type'], "baseball" ) . '</p>';
	//$output .= '<pre>ATTRIBS AFTER:' . print_r( $attribs, true ) . '</pre>';
	//return $output;
	
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
		//mstw_log_msg( '$term_ogj:' );
		//mstw_log_msg( $term_obj );
		$team_name = ( $term_obj ) ? $term_obj->name : $team;
		
		$team_class = 'mstw_tr_roster_title mstw_tr_roster_title_' . $team;
        
		$title_h1 = '<h1 class="' . $team_class . '">'; 
		
		$output .= $title_h1 . $team_name . ' Roster' . '</h1>';
	}
	
	// Set the sort order	
	switch ( $sort_order ) {
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
							  'post_type' => 'mstw_tr_player',
							  'mstw_tr_team' => $team, 
							  'orderby' => $order_by, 
							  'meta_key' => $sort_key,
							  'order' => 'ASC' 
							));						
	
    if( $posts ) {
		// Make table of posts
		// Start with the table header

		$team_class = 'mstw-tr-table-' . $team;
        $output .= '<table class="mstw-tr-table ' . $team_class . '">';
		
		// leave this open and check on styles from the admin settings
		$output .= '<thead><tr class="mstw-tr-table-head">';
	
		$th_temp = '<th class="mstw-tr-table-head" > ';
		
		// Check the PHOTO Column
		if ( $show_photos ) {
			$output .= $th_temp . $photo_label . '</th>';
		}
		
		if ( $show_number ) {	
			$output .= $th_temp . $number_label . '</th>';
		}
		
		// Always show the NAME column
		$output .= $th_temp . $name_label . '</th>';
		
		// POSITION column
		if ( $show_position ) {
			$output .= $th_temp . $position_label . '</th>';
		}
		
		// BATS/THROWS column
		if ( $show_bats_throws ) {
			$output .= $th_temp . $bats_throws_label . '</th>';
		}
		
		// HEIGHT column
		if ( $show_height ) {
			$output .= $th_temp . $height_label . '</th>';
		}
		
		// WEIGHT column
		if ( $show_weight ) {
			$output .= $th_temp . $weight_label . '</th>';
		}
		
		// YEAR column
		if ( $show_year ) {
			$output .= $th_temp . $year_label . '</th>';
		}
		
		// AGE column
		if ( $show_age ) {
			$output .= $th_temp . $age_label . '</th>';
		}
		
		// EXPERIENCE column
		if ( $show_experience ) {
			$output .= $th_temp . $experience_label . '</th>';
		}
		
		// HOMETOWN column
		if ( $show_home_town ) {
			if ( $roster_type == 'college' or $roster_type == 'baseball-college' ) {
				$output .= $th_temp . $home_town_label . ' ('. $last_school_label . ')' . '</th>';
			}
			else if ( $roster_type == 'custom' ) {
				$output .= $th_temp . $home_town_label . '</th>';
			}
		}
		
		// LAST SCHOOL column
		if ( $show_last_school ) {
			if ( $roster_type == 'pro' or $roster_type == 'baseball-pro' ) {
				$output .= $th_temp . $last_school_label . ' ('. $country_label . ')' . '</th>';
			}
			else if ( $roster_type == 'custom' ) {
				$output .= $th_temp . $last_school_label . '</th>';
			}
		}
		
		// COUNTRY column
		if ( $show_country and $roster_type == 'custom' ) {
			$output .= $th_temp . $country_label . '</th>';
		}
		
		// OTHER column
		if ( $show_other_info and $roster_type == 'custom' ) {
			$output .= $th_temp . $other_info_label . '</th>';
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
			
			$row_tr = '<tr class="' . $row_class . '">'; 
			$row_td = '<td class="' . $row_class . '">'; 
			
			// create the row
			$row_string = $row_tr;	

			// Add the player's photo	
			if ( $show_photos ) {
				$row_string .= $row_td;
				if ( has_post_thumbnail( $post->ID ) ) {
					if ( $link_to_player_pages ) {
						$row_string .= '<a href="' .  get_permalink( $post->ID ) . '">';
						$row_string .= get_the_post_thumbnail( $post->ID, array($table_photo_width, $table_photo_height) ) .  '</a></td>'; 
					}
					else {  //No profile to link to
						$row_string .= get_the_post_thumbnail( $post->ID, array($table_photo_width, $table_photo_height) ) .  '</td>';
					}	
				}
				else {
					$photo_file = plugin_dir_path( __FILE__ ) . 'images/default-photo-'. $team . '.jpg';
					if (file_exists( $photo_file ) ) {
						$photo_file_url = plugins_url() . '/team-rosters/images/default-photo-' . $team . '.jpg';
					}
					else {
						$photo_file_url = plugins_url() . '/team-rosters/images/default-photo.jpg';	
					}
					$row_string .=  '<img width="' . $table_photo_width . '" height="' . $table_photo_height . '" src="' . $photo_file_url . '" class="attachment-64x64 wp-post-image" alt="No photo available"/></td>';
				}
			}
			
			// column 1: Add the player's number
			if ( $show_number ) {
				$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_number', true ) . '</td>';
			}
			
			// column 2: Add the player's name
			switch( $name_format ) {
			case 'first-last':
				$player_name = get_post_meta( $post->ID, '_mstw_tr_first_name', true ) . " " . 
				get_post_meta( $post->ID, '_mstw_tr_last_name', true );
				break;
			case 'first-only':
				$player_name = get_post_meta( $post->ID, '_mstw_tr_first_name', true );
				break;
				
			case 'last-only':
				$player_name = get_post_meta( $post->ID, '_mstw_tr_last_name', true );
				break;
			
			default: //It's going to be last-first
				$player_name = get_post_meta( $post->ID, '_mstw_tr_last_name', true ) . ', ' . 
				get_post_meta( $post->ID, '_mstw_tr_first_name', true );
				break;
			}
			
			
			if ( file_exists( $link_to_player_pages ) ) {
				$player_html = '<a href="' .  get_permalink($post->ID) . '?format=' . $roster_type . '" ';
				/*if ( $options['tr_table_links_color'] != '' ) {
					$player_html .= 'style="color:' . $options['tr_table_links_color'] . ';"';
				}
				*/
				$player_html .= '>' . $player_name . '</a>';
			}
			else {
				$player_html = $player_name;
			}
			
			$row_string =  $row_string . $row_td . $player_html . '</td>';
			
			// column 3: Add the player's postition
			if ( $show_position ) {
				$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_position', true ) . '</td>';
			}
			
			// column 3a bats/throws (baseball)
			if ( $show_bats_throws ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_bats', true ) . '/' . get_post_meta( $post->ID, '_mstw_tr_throws', true ) . '</td>';	
			}	
			
			// column 4: Add the player's height
			if ( $show_height ) {
				$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_height', true ) . '</td>';
			}
			
			// column 5: Add the player's weight
			if ( $show_weight ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_weight', true ) . '</td>';
			}
			
			// column 6: Add the player's year (in school)
			if ( $show_year ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_year', true ) . '</td>';
			}
			
			// AGE column
			if ( $show_age ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_age', true ) . '</td>';
			}
			
			// EXPERIENCE column
			if ( $show_experience ) {
				$row_string =  $row_string . $row_td . get_post_meta( $post->ID, '_mstw_tr_experience', true ) . '</td>';
			}
			
			// HOMETOWN column
			if ( $show_home_town ) {
				if ( $roster_type == 'college' or $roster_type == 'baseball-college' ) {
					$row_string .=  $row_td . get_post_meta( $post->ID, '_mstw_tr_home_town', true ) . 
					' (' . get_post_meta( $post->ID, '_mstw_tr_last_school', true ) . ') </td>';
				}
				else if ( $roster_type == 'custom' ) {
					$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_home_town', true )  . '</td>';
				}
			}
			
			// LAST SCHOOL column
			if ( $show_last_school ) {
				if ( $roster_type == 'pro' or $roster_type == 'baseball-pro' ) {
					$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_last_school', true ) . 
					' (' . get_post_meta( $post->ID, '_mstw_tr_country', true ) . ') </td>';
				}
				else if ( $roster_type == 'custom' ) {
					$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_last_school', true )  . '</td>';
				}
			}
			
			// COUNTRY column
			if ( $show_country and $roster_type == 'custom' ) {
				$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_country', true )  . '</td>';
			}
			
			// OTHER column
			if ( $show_other_info and $roster_type == 'custom' ) {
				$row_string .= $row_td . get_post_meta( $post->ID, '_mstw_tr_other', true ) .'</td>';
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

// Convenience function to determine whether or not to show a field
	function mstw_tr_set_fields( $roster_format, $defaults ) {
		//$show_bats_throws = ( strpos( $roster_type, 'baseball' ) === false ) ? 0 : 1;
		switch ( $roster_format ) {
			case 'baseball-high-school':
			case 'baseball-college':
			case 'baseball-pro':
				$show_bats_throws = 1;
			break;
			default:
				$show_bats_throws = 0;
				break;
		}
			
		switch ( $roster_format ) {
			case 'baseball-high-school':
			case 'high-school':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					//'show_title'			=> '1',
					//'roster_type'			=> 'custom',
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
					'roster_type'			=> $roster_format,
					//'show_title'			=> 1,
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
					//'show_title'			=> 1,
					'roster_type'			=> $roster_format,
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
				// Do nada
				return $defaults;
				break;
		}
	
		$settings = wp_parse_args( $settings, $defaults );
		
		return $settings;
	
	}
?>