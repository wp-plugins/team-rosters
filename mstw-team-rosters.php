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
		
		//------------------------------------------------------------------------
		// Functions for MSTW roster table shortcode
		//
		include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-roster-table.php' );
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-roster-table loaded ' );
		
		//------------------------------------------------------------------------
		// Functions for MSTW roster gallery shortcode
		//
		require_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-roster-gallery.php' );
		
		mstw_log_msg( 'in mstw_tr_init ...' );
		if (function_exists( 'mstw_tr_roster_gallery_handler' ) ) 
			mstw_log_msg( 'mstw_tr_roster_gallery_handler exists' );
		else
			mstw_log_msg( 'mstw_tr_roster_gallery_handler does not exist' );
		
		//add_shortcode( 'mstw_roster_gallery', 'mstw_tr_roster_gallery_handler' );
		
		/*
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
		// REGISTER THE MSTW TEAM ROSTERS CUSTOM POST TYPES & TAXONOMIES
		//	mstw_tr_player, mstw_tr_team
		//
		include_once( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-cpts.php' );
		mstw_tr_register_cpts( );
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-cpts loaded ' );
		
		//-----------------------------------------------------------------
		// find the single-mstw_tr_player template in the plugin's directory
		//
		add_filter( "single_template", "mstw_tr_single_player_template" );
 
		//------------------------------------------------------------------------
		// If an admin screen, load the admin functions (gotta have 'em)
		
		if ( is_admin( ) )
			include_once ( plugin_dir_path( __FILE__ ) . 'includes/mstw-tr-admin.php' );
		
		//mstw_log_msg( 'in mstw_tr_init  ... mstw-tr-admin loaded ' );
			
			
		//mstw_log_msg( 'in mstw_tr_init ... taxonomies:' );
		//mstw_log_msg( get_taxonomies( ) );*/

}

 //-----------------------------------------------------------------
 // add the shortcodes (priority 99 == last)
 //
 add_action( 'init', 'mstw_tr_add_shortcodes', 99 );	
	
 function mstw_tr_add_shortcodes( ) {
	 mstw_log_msg( 'in mstw_tr_add_shortcodes ...' );
	 
	
	remove_shortcode( 'mstw_roster_gallery' );
	add_shortcode( 'mstw_roster_gallery', 'mstw_tr_roster_gallery_handler' );
	
		  
	if (shortcode_exists( 'mstw_roster_gallery' ) ) 
		mstw_log_msg( 'mstw_roster_gallery shortcode exists' );
	else
		mstw_log_msg( 'mstw_roster_gallery shortcode does not exist' );
	
		
	if (function_exists( 'mstw_tr_roster_gallery_handler' ) ) 
		mstw_log_msg( 'mstw_tr_roster_gallery_handler exists' );
	else
		mstw_log_msg( 'mstw_tr_roster_gallery_handler does not exist' );
	
	remove_shortcode( 'mstw_roster_table' );
	add_shortcode( 'mstw_roster_table', 'mstw_tr_roster_table_handler' );
	
	if (shortcode_exists( 'mstw_roster_table' ) ) 
		mstw_log_msg( 'mstw_roster_table shortcode exists' );
	else
		mstw_log_msg( 'mstw_roster_table shortcode does not exist' );
	 
	/*remove_shortcode( 'mstw_roster_gallery' );
	add_shortcode( 'mstw_roster_gallery', 'mstw_tr_roster_table_handler' );
	
	if (shortcode_exists( 'mstw_roster_gallery' ) ) 
		mstw_log_msg( 'mstw_roster_gallery shortcode exists' );
	else
		mstw_log_msg( 'mstw_roster_gallery shortcode does not exist' );*/


 }


 // ----------------------------------------------------------------
 // On activation, check the version of WP and set up the 'mstw_tr'
 //		roles and capabilites
 //
 register_activation_hook( __FILE__, 'mstw_tr_activate' );	

 function mstw_tr_activate( ) {
	
	mstw_tr_check_wp_version( '4.0' ); //tested - OK
	
	update_option( 'mstw_team_rosters_activated', 1 );
	
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
 // filter the single_player template. first look for single-player.php 
 //	in the current theme directory, just in case a user wants to get fancy,
 // then look in the plugin's /theme-templates directory
 //
 // filter is now part of the init action - mstw_tr_init()
 // add_filter( "single_template", "mstw_tr_single_player_template", 11 );
 //
 
 function mstw_tr_single_player_template( $single_template ) {
	global $post;
		
	if ( $post->post_type == 'mstw_tr_player' ) {
		
		$custom_single_template = get_stylesheet_directory( ) . '/single-player.php';
		$plugin_single_template = dirname( __FILE__ ) . '/theme-templates/single-player.php';
		
		if ( file_exists( $custom_single_template ) ) {
			$single_template = $custom_single_template;
		}
		else if ( file_exists( $plugin_single_template ) ) {
			$single_template = $plugin_single_template;
		}
		
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
		//
		// Roster Table settings
		//
		echo "table.mstw-tr-table thead tr th { \n";
			echo mstw_build_css_rule( $options, 'table_head_text', 'color' );
			echo mstw_build_css_rule( $options, 'table_head_bkgd', 'background-color' );
		echo "} \n";
		
		echo "h1.team-head-title { \n";
			echo mstw_build_css_rule( $options, 'table_title_color', 'color' );		
		echo "} \n";
		
		echo '.mstw-tr-table tr:nth-child(odd) {';//'tr.mstw-tr-odd {';
			echo mstw_build_css_rule( $options, 'table_odd_row_text', 'color' );
			echo mstw_build_css_rule( $options, 'table_odd_row_bkgd', 'background-color' );
		echo '}';
		
		echo '.mstw-tr-table tr:nth-child(even) {' ; //'tr.mstw-tr-even {';
			echo mstw_build_css_rule( $options, 'table_even_row_text', 'color' );
			echo mstw_build_css_rule( $options, 'table_even_row_bkgd', 'background-color' );
		echo '}';
		
		echo ".mstw-tr-table tr:nth-child(even) td a, 
			  .mstw-tr-table tr:nth-child(odd) td a	{ \n";
			echo mstw_build_css_rule( $options, 'table_links_color', 'color' );
		echo "} \n";
		
		echo '.mstw-tr-table tr:nth-child(even) td,
			 .mstw-tr-table tr:nth-child(odd) td {';
			echo mstw_build_css_rule( $options, 'table_border_color', 'border-top-color' );
			echo mstw_build_css_rule( $options, 'table_border_color', 'border-bottom-color' );
		echo '}';
		
		echo "table.mstw-tr-table tbody tr td img { \n";
			echo mstw_build_css_rule( $options, 'table_photo_width', 'width', 'px' );
			echo mstw_build_css_rule( $options, 'table_photo_height', 'height', 'px' );
		echo "}\n";
		
		//
		// Player Profile Settings
		//
		echo "div.player-header { \n";
			echo mstw_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "#player-name-nbr { \n";
			echo mstw_build_css_rule( $options, 'sp_main_text_color', 'color' );
		echo "} \n";
		
		echo ".player-bio { \n";
			echo mstw_build_css_rule( $options, 'sp_bio_border_color', 'border-color' );
		echo '}';
		
		echo ".player-bio h1 { \n";
			echo mstw_build_css_rule( $options, 'sp_bio_header_color', 'color' );
		echo "}\n";
		
		echo ".player-bio { \n";
			echo mstw_build_css_rule( $options, 'sp_bio_text_color', 'color' );
		echo "}\n";
		
		echo ".player-bio { \n";
			echo mstw_build_css_rule( $options, 'sp_bio_bkgd_color', 'background-color' );
		echo "}\n";
		
		echo "h1.player-head-title { \n";
			echo mstw_build_css_rule( $options, 'tr_table_title_text_color', 'color' );
		echo "}\n";
		
		echo "h1.mstw_tr_roster_title { \n";
			echo mstw_build_css_rule( $options, 'tr_table_title_text_color', 'color' );
		echo "}\n";
		
		echo "div#player-photo img { \n";
			echo mstw_build_css_rule( $options, 'sp_image_width', 'width', 'px' );
			echo mstw_build_css_rule( $options, 'sp_image_height', 'height', 'px' );
		echo "}\n";
		
		//
		// Player Gallery Settings
		//
		echo ".player-tile { \n";
			echo mstw_build_css_rule( $options, 'sp_main_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo ".player-tile { \n";
			echo mstw_build_css_rule( $options, 'sp_main_text_color', 'color' );
		echo "} \n";
		
		echo ".player-tile img { \n";
			echo mstw_build_css_rule( $options, 'sp_image_width', 'width', 'px' );
			echo mstw_build_css_rule( $options, 'sp_image_height', 'height', 'px' );
		echo "} \n";
		
		echo ".player-name-number { \n";
			echo mstw_build_css_rule( $options, 'sp_main_text_color', 'color' );
			
		echo "} \n";
		
		echo ".player-name-number a { \n";
			echo mstw_build_css_rule( $options, 'gallery_links_color', 'color' );
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
//
// THIS THING IS NOT RIGHT!!
//
	add_filter( 'pre_get_posts', 'mstw_tr_get_posts' );

	function mstw_tr_get_posts( $query ) {
		//mstw_log_msg( 'in mstw_tr_get_posts' );
		//mstw_log_msg( 'input query: ' );
		//mstw_log_msg( $query );
		
		if( !is_admin( ) ) {
		// Need to check the need for this first conditional ... someday
		if ( is_category( ) && $query->is_main_query( ) )
			$query->set( 'post_type', array( 'post', 'mstw_tr_player' ) ); 
  
		if ( is_tax( 'mstw_tr_team' ) && $query->is_main_query( ) ) {
			// We are on the player gallery page ...
			// So set the sort order based on the admin settings
			$options = get_option( 'mstw_tr_options' );
			//mstw_log_msg( 'in mstw_tr_get_posts ... $options:' );
			//mstw_log_msg( $options );
			
			// Need the team slug to set query
			$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
			$team_slug = $uri_array[sizeof( $uri_array )-2];
			
			// sort alphabetically by last name ascending by default
			$query->set( 'post_type', 'mstw_tr_player' );
			$query->set( 'mstw_tr_team' , $team_slug );
			$query->set( 'orderby', 'meta_value' );  
			$query->set( 'meta_key', 'player_last_name' );   
			$query->set( 'order', 'ASC' );
			
			if ( array_key_exists( 'tr_pg_sort_order', (array)$options ) ) {
				if ( $options['tr_pg_sort_order'] == 'numeric' ) {
					// sort by number ascending
					$query->set( 'post_type', 'mstw_tr_player' );
					$query->set( 'mstw_tr_team' , $team_slug );
					$query->set( 'orderby', 'meta_value_num' );    
					$query->set( 'meta_key', 'player_number' );     
					$query->set( 'order', 'ASC' );
				}	 
			}
		}
		}
		//mstw_log_msg( 'output query: ' );
		//mstw_log_msg( $query );
	}  

// ----------------------------------------------------------------
// Deactivate, request upgrade, and exit if WP version is not right

	//add_action( 'admin_init', 'mstw_tr_requires_wp_ver' );

	function mstw_tr_requires_wp_ver() {
		global $wp_version;
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "4.0", "<" ) ) {
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
		// Find the full path to the plugin's CSS file
		$plugin_stylesheet = dirname( __FILE__ ) . '/css/mstw-tr-styles.css';

		// If stylesheet exists, which it should, enqueue the style
		if ( file_exists( $plugin_stylesheet ) ) {	
			$plugin_style_url = plugins_url( '/css/mstw-tr-styles.css', __FILE__ );
			wp_register_style( 'mstw_tr_style', $plugin_style_url );
			wp_enqueue_style( 'mstw_tr_style' );				
		}
		//else {
		//	mstw_log_msg( "enqueuing styles ... plugin stylesheet missing ... $plugin_stylesheet" );
		//}

		// Check if a custom stylesheet exists in the current theme's directory;
		// if so, enqueue it too. it MUST be named mstw-tr-custom-styles.css
		$custom_stylesheet = get_stylesheet_directory( ) . '/mstw-tr-custom-styles.css';
		
		if ( file_exists( $custom_stylesheet ) ) {
			$custom_stylesheet_url = get_stylesheet_directory_uri( ) . '/mstw-tr-custom-styles.css';
			//mstw_log_msg( 'custom stylesheet uri: ' . $custom_stylesheet_url );
			wp_register_style( 'mstw_tr_custom_style', $custom_stylesheet_url );
			wp_enqueue_style( 'mstw_tr_custom_style' );
		}
		//else {
		//	mstw_log_msg( 'custom stylesheet: ' . $mstw_tr_custom_stylesheet . ' does not exist.' );
		//}

	}


 // First want to make sure thumbnails are active in the theme before adding them via the 
 //	register_post_type call in the 'init' action
 //
 add_action( 'after_setup_theme', 'mstw_tr_add_feat_img' );
	
 function mstw_tr_add_feat_img( ) {
	add_theme_support( 'post-thumbnails', array( 'mstw_tr_player' ) );
 }



// Convenience function to determine whether or not to show a field
//
// USE _ste_fields_by_format in tr_utility_functions?
//
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