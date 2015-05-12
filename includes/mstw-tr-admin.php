<?php
/*
 *	This is the admin portion of the MSTW Team Rosters Plugin
 *	It is loaded conditioned on is_admin() 
 */

/*-----------------------------------------------------------------------------------
Copyright 2012-15  Mark O'Donnell  (email : mark@shoalsummitsolutions.com)

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

Code from the CSV Importer plugin was modified under that plugin's 
GPLv2 (or later) license from Smackcoders. 

Code from the File_CSV_DataSource class was re-used unchanged under
that class's MIT license & copyright (2008) from Kazuyoshi Tlacaelel. 
-----------------------------------------------------------------------------------*/

// --------------------------------------------------------------------------------------
// Set-up Action and Filter Hooks for the Settings on the admin side
// --------------------------------------------------------------------------------------
//register_uninstall_hook(__FILE__, 'mstw_tr_delete_plugin_options');

// --------------------------------------------------------------------------------------
// Callback for: register_uninstall_hook(__FILE__, 'mstw_tr_delete_plugin_options')
// --------------------------------------------------------------------------------------
// It runs when the user deactivates AND DELETES the plugin. 
// It deletes the plugin options DB entry, which is an array storing all the plugin options
// --------------------------------------------------------------------------------------
//function mstw_tr_delete_plugin_options() {
//	delete_option('mstw_tr_options');
//

	// ----------------------------------------------------------------
	// Load the stuff admin needs
	// This is called from the init hook in mstw-team-rosters.php
	//
	if ( is_admin( ) ) {
		// Add a menu item for the Admin pages
		add_action('admin_menu', 'mstw_tr_register_menu_pages');
	
		// initialize the admin UI. MOVE ALL THE OTHER ACTIONS TO mstw_tr_admin_init ??
		add_action( 'admin_init', 'mstw_tr_admin_init' );
		add_action( 'admin_notices', 'mstw_tr_admin_notice' );
		//
		// Hide the publishing actions on the edit and new CPT screens
		//
		add_action( 'admin_head-post.php', 'mstw_tr_hide_publishing_actions' );
		add_action( 'admin_head-post-new.php', 'mstw_tr_hide_publishing_actions' );
		//
		// Hide the list icons on the CPT edit (all) screens
		//
		add_action( 'admin_head-edit.php', 'mstw_tr_hide_list_icons' );	
		// 
		// Remove Quick Edit Menu
		//
		add_filter( 'post_row_actions', 'mstw_tr_remove_quick_edit', 10, 2 );
		// 
		// Remove the Bulk Actions pull-down
		//
		add_filter( 'bulk_actions-edit-mstw_tr_player', 'mstw_tr_bulk_actions' );
		//
		// Add custom admin messages for CPTs (Adding/editting CPTs
		//
		add_filter('post_updated_messages', 'mstw_tr_updated_messages');
		//
		// Add custom admin bulk messages for CPTs (deleting & restoring CPTs)
		//
		add_filter( 'bulk_post_updated_messages', 'mstw_tr_bulk_post_updated_messages', 10, 2 );
		//
		// Add custom admin messages for adding/editting custom taxonomy terms
		//
		add_filter( 'term_updated_messages', 'mstw_tr_updated_term_messages');
		
	} else {
		die( __( 'You is no admin. You a cheater!', 'mstw-team-rosters' ) );
	}
	
	// ----------------------------------------------------------------
	// Register and define the settings
	// ----------------------------------------------------------------
	if( !function_exists( 'mstw_tr_admin_init' ) ) {
		function mstw_tr_admin_init( ){
		
			include_once 'mstw-tr-player-cpt-admin.php';
			
			include_once 'mstw-tr-team-tax-admin.php';
			
			include_once 'mstw-tr-settings.php';
			
			// Settings for the fields and columns display and label controls.
			if ( false == get_option( 'mstw_tr_options' ) ) {
				add_option( 'mstw_tr_options' );
			}
			
			register_setting(
				'mstw_tr_settings',
				'mstw_tr_options',
				'mstw_tr_validate_settings'
			);
			
			// Storage for the TR team to SS team links.
			if ( false == get_option( 'mstw_tr_ss_team_links' ) ) {
				add_option( 'mstw_tr_ss_team_links' );
			}
			
			wp_register_style( 'players-screen-styles', plugins_url( 'mstw-tr-admin-styles.css', __FILE__ ) );
			//mstw_log_msg( plugins_url( 'mstw-tr-admin-styles.css', __FILE__ ) );
			mstw_tr_load_admin_styles( );
			
			ob_start();
		
			
		} //End: mstw_tr_admin_init()
	}
	
	//----------------------------------------------------------------
	// Hide the publishing actions on the edit and new CPT screens
	// Callback for admin_head-post.php & admin_head-post-new.php actions
	//
	if ( !function_exists( 'mstw_tr_hide_publishing_actions' ) ) {
		function mstw_tr_hide_publishing_actions( ) {

			$post_type = mstw_get_current_post_type( );
			
			//mstw_log_msg( 'in ... mstw_tr_hide_publishing_actions' );
			//mstw_log_msg( $post_type );
			
			if( $post_type == 'mstw_tr_player' ) {	
				?>
					<style type="text/css">
						#misc-publishing-actions,
						#minor-publishing-actions{
							display:none;
						}
						div.view-switch {
							display: none;
						
						}
						div.tablenav-pages.one-page {
							display: none;
						}
						
					</style>
				<?php					
			}
		} //End: mstw_tr_hide_publishing_actions( )
	}
	
	//----------------------------------------------------------------
	// Hide the list icons on the CPT edit (all) screens
	// Callback for admin_head-edit action
	if ( !function_exists( 'mstw_tr_hide_list_icons' ) ) {
		function mstw_tr_hide_list_icons( ) {

			$post_type = mstw_get_current_post_type( );
			//mstw_log_msg( 'in ... mstw_tr_hide_list_icons' );
			//mstw_log_msg( $post_type );
			
			if( $post_type == 'mstw_tr_player' ) {
				//echo '
				?>
					<style type="text/css">
						select#filter-by-date,
						div.view-switch {
							display: none;
						}
						
					</style>
				<?php
				//';
			}
		} //End: mstw_tr_hide_list_icons( )
	}
	
	// ----------------------------------------------------------------	
	// Add admin scripts: color picker, media manager, reset confirm dialog
	//
	add_action( 'admin_enqueue_scripts', 'mstw_tr_admin_enqueue_scripts' );
	
	function mstw_tr_admin_enqueue_scripts( $hook_suffix ) {
		// enqueue the color-picker script & stylesheet
		// enqueue settings reset confirm script
		// only if it's the settings page
		
		//mstw_log_msg( 'in mstw_tr_admin_enqueue_scripts ... ' );
		//mstw_log_msg( '$hook_suffix = '. $hook_suffix );
		//mstw_log_msg( 'confirm java script = ' . plugins_url( 'team-rosters/js/tr-color-settings.js' ) );
		
		// This function loads in the required media files for the media manager.
		//wp_enqueue_media();
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery');
		
		wp_enqueue_media();
		
		//mstw_log_msg( ' enqueing script: ' . plugins_url( 'team-rosters/js/tr-another-media.js' ) );
		wp_enqueue_script( 'another-media', plugins_url( 'team-rosters/js/tr-another-media.js' ), null, false, true );
		
		wp_enqueue_style('thickbox');
		
		if ( $hook_suffix == 'team-rosters_page_mstw-tr-settings' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'mstw-tr-color-picker', plugins_url( 'team-rosters/js/tr-color-settings.js' ), array( 'wp-color-picker' ), false, true ); 
			wp_enqueue_script( 'mstw-tr-confirm-reset', plugins_url( 'team-rosters/js/tr-confirm-reset.js' ), array( 'wp-color-picker' ), false, true ); 
		}

	} //End: mstw_tr_admin_enqueue_scripts()
	

	// ----------------------------------------------------------------
	// Remove Quick Edit Menu	
	//
	if( !function_exists( 'mstw_tr_remove_quick_edit' ) ) {
		function mstw_tr_remove_quick_edit( $actions, $post ) {
			if( $post->post_type == 'mstw_tr_player' ) {
				//unset( $actions['inline hide-if-no-js'] );
			}
			return $actions;
		} //End: mstw_tr_remove_quick_edit()
	}
	
	// ----------------------------------------------------------------
	// Remove the Bulk Actions pull-down
	//
	if( !function_exists( 'mstw_tr_bulk_actions' ) ) {	
		function mstw_tr_bulk_actions( $actions ){
			unset( $actions['edit'] );
			return $actions;
		} //End: mstw_tr_bulk_actions()
	}
		
	// ----------------------------------------------------------------
	// Add a filter the All Teams screen based on the Leagues Taxonomy
	//add_action('restrict_manage_posts','mstw_tr_restrict_manage_posts');
	
	function mstw_tr_restrict_manage_posts( ) {
		global $typenow;

		if ( $typenow=='mstw_tr_player' ){
			// Trying to find current selection
			$selected = isset( $_REQUEST[$mstw_tr_team] ) ? $_REQUEST[$mstw_tr_team] : '';
				
			$args = array(
						'show_option_all' => 'All Teams',
						'taxonomy' => 'mstw_tr_team',
						'name' => 'mstw_tr_team',
						'orderby' => 'name',
						'selected' => $_GET['mstw_tr_team'],
						'show_count' => true,
						'hide_empty' => true,
						);
			wp_dropdown_categories( $args );
		}
	}
	
	//add_action( 'request', 'mstw_tr_request' );
	function mstw_tr_request( $request ) {
		//mstw_log_msg( 'in ... mstw_tr_request' );
		//mstw_log_msg( $request );
		
		if ( is_admin( ) && $GLOBALS['PHP_SELF'] == '/wp-admin/edit.php' && isset( $request['post_type'] ) && $request['post_type']=='mstw_tr_player' ) {
			$request['term'] = get_term( $request['mstw_tr_player'], 'mstw_tr_team', OBJECT )->name;
		}
		return $request;
	}

	function mstw_tr_register_menu_pages( ) {
		//mstw_log_msg( 'including mstw-tr-import-class' );
		include_once 'mstw-tr-csv-import-class.php';
		//include_once 'mstw-tr-import-class.php';
		
		//Top Level Menu
		
		$rosters_page = add_menu_page( __( 'Team Rosters', 'mstw-team-rosters' ), //$page_title, 
					   __( 'Team Rosters', 'mstw-team-rosters' ), //$menu_title, 
					   'read', //$capability, 
					   'edit.php?post_type=mstw_tr_player', 
					   null, 
					   plugins_url( 'images/mstw-admin-menu-icon.png', dirname( __FILE__ ) ), //$menu_icon
					   "58.75" //menu order
					 );
		//mstw_log_msg( 'admin_print_styles-' . $rosters_page );
		
		//Players			 
		$players_page = add_submenu_page( 
							'edit.php?post_type=mstw_tr_player', 
							__( 'Players', 'mstw-team-rosters' ), //page title
							__( 'Players', 'mstw-team-rosters' ), //menu title
							'read', // Capability required to see this option.
							'edit.php?post_type=mstw_tr_player', // Slug name to refer to this menu
							null							
							); // Callback to output content
						
		add_action( 'admin_print_styles-' . $players_page, 'mstw_tr_load_admin_styles');
		
		/*
		//TESTING SS TEAMS
		//
		$test_page = add_submenu_page( 
							'edit.php?post_type=mstw_tr_player', 
							__( 'SS Teams', 'mstw-team-rosters' ), //page title
							__( 'SS Teams', 'mstw-team-rosters' ), //menu title
							'read', // Capability required to see this option.
							'my-test-slug', //'edit.php?post_type=mstw_ss_team', // Slug name to refer to this menu
							'test_callback' //'edit.php?post_type=mstw_ss_team' //					
							); // Callback to output content
							
		add_action( "load-$test_page", 'test_redirect' );
		*/
		
		//mstw_log_msg( 'admin_print_styles-' . $players_page );
		
		//Teams (taxonomy)			 
		$teams_page = add_submenu_page( 
							'edit.php?post_type=mstw_tr_player', //parent page
							__( 'Teams', 'mstw-team-rosters' ), //page title
							__( 'Teams', 'mstw-team-rosters' ), //menu title
							'read', // Capability required to see this option.
							'edit-tags.php?taxonomy=mstw_tr_team&post_type=mstw_tr_player', // Slug name to refer to this menu
							null							
							); // Callback to output content
		
		
						
		// Settings
		$settings_page = add_submenu_page( 	
							'edit.php?post_type=mstw_tr_player',  //parent slug
							__( 'Settings', 'mstw-team-rosters' ),   //page title
							__( 'Settings', 'mstw-team-rosters' ),  //menu title
							'read',  //user capability required to access
							'mstw-tr-settings',  //unique menu slug
							'mstw_tr_settings_page'  //callback to display page
							);					
							
		//
		// Load the settings help pages
		//
		add_action( "load-$settings_page", 'mstw_tr_settings_help' );
		
		/*
		// Data Migration (from 3.1.2)
		//
		if ( true ) {
		//if( post_type_exists( 'player' ) and get_option( 'mstw_team_rosters_activated' ) ) {
		$migration_page = add_submenu_page (
							'edit.php?post_type=mstw_tr_player',  //parent slug
							__( 'Migrate Data from Version 3.1.2', 'mstw-team-rosters' ),   //page title
							__( '3.1.2 Data Migration', 'mstw-team-rosters' ),  //menu title
							'read',  //user capability required to access
							'mstw-tr-data-migration',  //unique menu slug
							'mstw_tr_data_migration_page'  //callback to display page
							);
		}
		*/

		//
		// CSV Import
		$plugin = new MSTW_TR_ImporterPlugin;
		
		add_submenu_page(	'edit.php?post_type=mstw_tr_player',
							'Import Roster from CSV File',			//page title
							'CSV Roster Import',					//menu title
							'manage_options',
							'mstw-tr-csv-import',
							array( $plugin, 'form' )
						);
							
		// Now also add action to load java scripts ONLY when you're on this page
	}
	
	/*
	function test_callback( ) {
		return;
		echo '<h1>Test Callback</h1>';
		//ob_start();
		//wp_redirect( 'http://mstw.dev/wp-admin/edit.php?post_type=mstw_tr_player', 302 );
		//exit;
	}
	
	function test_redirect( ) {
		wp_redirect( 'http://mstw.dev/wp-admin/edit.php?post_type=mstw_ss_team', 302 );
		exit;
	}
	*/
	
 //-----------------------------------------------------------------------
 // Enqueue admin styles - only if on players admin page
 //
 if( !function_exists( 'mstw_tr_load_admin_styles' ) ) {
	function mstw_tr_load_admin_styles( ) {
		//mstw_log_msg( ' loading players screen styles' );
		wp_enqueue_style( 'players-screen-styles' );
	} //End: mstw_tr_load_admin_styles( )
 }
 
 
 //-----------------------------------------------------------------------
 // Add custom admin messages for CPTs (Adding/editting CPTs
 //
 if( !function_exists( 'mstw_tr_updated_messages' ) ) {
	function mstw_tr_updated_messages( $messages ) {

		$messages['mstw_tr_player'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Player updated.', 'mstw-team-rosters' ),
			2 => __( 'Custom field updated.', 'mstw-team-rosters'),
			3 => __( 'Custom field deleted.', 'mstw-team-rosters' ),
			4 => __( 'Player updated.', 'mstw-team-rosters' ),
			5 => __( 'Player restored to revision', 'mstw-team-rosters' ),
			6 => __( 'Player published.', 'mstw-team-rosters' ),
			7 => __( 'Player saved.', 'mstw-team-rosters' ),
			8 => __( 'Player submitted.', 'mstw-team-rosters' ),
			9 => __( 'Player scheduled for publication.', 'mstw-team-rosters' ),
			10 => __( 'Player draft updated.', 'mstw-team-rosters' ),
		);
		
		return $messages;
		
	} //End: mstw_tr_updated_messages( )
 }
 
 //-----------------------------------------------------------------------
 // Add custom admin bulk messages for CPTs (deleting & restoring CPTs)
 //
 if( !function_exists( 'mstw_tr_bulk_post_updated_messages' ) ) {
	function mstw_tr_bulk_post_updated_messages( $messages, $bulk_counts ) {

		$messages['mstw_tr_player'] = array(
			'updated'   => _n( '%s player updated.', '%s players updated.', $bulk_counts['updated'], 'mstw-team-rosters' ),
			'locked'    => _n( '%s player not updated, somebody is editing it.', '%s players not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-team-rosters' ),
			'deleted'   => _n( '%s player permanently deleted.', '%s players permanently deleted.', $bulk_counts['deleted'], 'mstw-team-rosters' ),
			'trashed'   => _n( '%s player moved to the Trash.', '%s players moved to the Trash.', $bulk_counts['trashed'], 'mstw-team-rosters' ),
			'untrashed' => _n( '%s player restored from the Trash.', '%s players restored from the Trash.', $bulk_counts['untrashed'], 'mstw-team-rosters' ),
		);
		
		return $messages;
		
	} //End: mstw_tr_bulk_post_updated_messages( )
 }
 
 //-----------------------------------------------------------------------
 // Add custom admin messages for adding/editting custom taxonomy terms
 //
 if( !function_exists( 'mstw_tr_updated_term_messages' ) ) {
	function mstw_tr_updated_term_messages( $messages ) {
		//mstw_log_msg( 'in mstw_tr_updated_term_messages ... ' );
		//mstw_log_msg( $messages );
		
		$messages['mstw_tr_team'] = array(
					0 => '',
					1 => __( 'Team added.', 'mstw-team-rosters' ),
					2 => __( 'Team deleted.', 'mstw-team-rosters' ),
					3 => __( 'Team updated.', 'mstw-team-rosters' ),
					4 => __( 'Team not added.', 'mstw-team-rosters' ),
					5 => __( 'Team not updated.', 'mstw-team-rosters' ),
					6 => __( 'Teams deleted.', 'mstw-team-rosters' ),
				);
									
		//mstw_log_msg( $messages );
		
		return $messages;
		
	} //End: mstw_tr_updated_term_messages( )
 }
 
?>