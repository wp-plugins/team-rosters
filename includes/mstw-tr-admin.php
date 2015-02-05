<?php
/*
 *	This is the admin portion of the MSTW Team Rosters Plugin
 *	It is loaded conditioned on is_admin() 
 */

/*-----------------------------------------------------------------------------------
Copyright 2012-13  Mark O'Donnell  (email : mark@shoalsummitsolutions.com)

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
		// initialize the admin UI. MOVE ALL THE OTHER ACTIONS TO mstw_tr_admin_init ??
		add_action( 'admin_init', 'mstw_tr_admin_init' );
		add_action( 'admin_notices', 'mstw_tr_admin_notices' );
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
	} else {
		die( __( 'You is no admin. You a cheater!', 'mstw-team-rosters' ) );
	}
	
	// ----------------------------------------------------------------
	// Register and define the settings
	// ----------------------------------------------------------------
	if( !function_exists( 'mstw_tr_admin_init' ) ) {
		function mstw_tr_admin_init( ){
		
			//THIS INCLUDE SHOULD GO AWAY! Use mstw-utility-functions.php instead.
			require_once  'mstw-admin-utils.php';
			
			include_once 'mstw-tr-settings.php';
			
			if ( false == get_option( 'mstw_tr_options' ) ) {
				add_option( 'mstw_tr_options' );
			}
	
			// Settings for the fields and columns display and label controls.
			register_setting(
				'mstw_tr_settings',
				'mstw_tr_options',
				'mstw_tr_validate_settings'
			);
		
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
				//echo '
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
				//';					
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
		
		//$pre_time = microtime( true );
		
		if ( $hook_suffix == 'team-rosters_page_mstw-tr-settings' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'mstw-tr-color-picker', plugins_url( 'team-rosters/js/tr-color-settings.js' ), array( 'wp-color-picker' ), false, true ); 
			wp_enqueue_script( 'mstw-tr-confirm-reset', plugins_url( 'team-rosters/js/tr-confirm-reset.js' ), array( 'wp-color-picker' ), false, true ); 
		}
		
		//$load_time = microtime( true ) - $pre_time;
		//mstw_log_msg( 'load time: ' . $load_time );

	} //End: mstw_tr_admin_enqueue_scripts()
	
	// ----------------------------------------------------------------
	// Add the custom MSTW icon to CPT pages
	add_action('admin_head', 'mstw_tr_custom_css');
	
	function mstw_tr_custom_css() { ?>
		<style type="text/css">
			#icon-mstw-tr-main-menu.icon32 {
				background: url(<?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' ); ?>) transparent no-repeat;
			}
			#icon-player.icon32 {
				background: url(<?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' ); ?>) transparent no-repeat;
			}
			#icon-edit.icon32-posts-player {
				background: url(<?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' );?>) transparent no-repeat;
			}
			#menu-posts-player .wp-menu-image {
				background-image: url(<?php echo plugins_url( '/team-rosters/images/mstw-admin-menu-icon.png', 'team-rosters' );?>) no-repeat 6px -17px !important;
			}
			
		</style>
	<?php }
	
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
	
	add_action( 'request', 'mstw_tr_request' );
	function mstw_tr_request( $request ) {
		//mstw_log_msg( 'in ... mstw_tr_request' );
		//mstw_log_msg( $request );
		
		if ( is_admin( ) && $GLOBALS['PHP_SELF'] == '/wp-admin/edit.php' && isset( $request['post_type'] ) && $request['post_type']=='mstw_tr_player' ) {
			$request['term'] = get_term( $request['mstw_tr_player'], 'mstw_tr_team', OBJECT )->name;
		}
		return $request;
	}
	
	// ----------------------------------------------------------------
	// Create the meta box for the Team Roster custom post type
	add_action( 'add_meta_boxes', 'mstw_tr_add_meta_box' );

	function mstw_tr_add_meta_box () {	
		add_meta_box(	'mstw-tr-meta', 
						__('Player', 'mstw-loc-domain'), 
						'mstw_tr_create_ui', 
						'mstw_tr_player', 
						'normal', 
						'high' );		
	}

	// ----------------------------------------------------------------------
	// Create the UI form for entering a Team Roster in the Admin page
	// Callback for: add_meta_box('mstw-tr-meta', ... )
	
	function mstw_tr_create_ui( $post ) {						  
		$bats_list = array( 	'', 
								__( 'R', 'mstw-loc-domain' ),
								__( 'L', 'mstw-loc-domain' ),
								__( 'B', 'mstw-loc-domain' ), 
							);
		$throws_list = array( 	'', 
								__( 'R', 'mstw-loc-domain' ),
								__( 'L', 'mstw-loc-domain' ), 
							);
		
		// Retrieve the metadata values if they exist
		// The first set are used in all formats
		$first_name = get_post_meta( $post->ID, '_mstw_tr_first_name', true );
		$last_name  = get_post_meta( $post->ID, '_mstw_tr_last_name', true );
		$number = get_post_meta( $post->ID, '_mstw_tr_number', true );
		$height = get_post_meta( $post->ID, '_mstw_tr_height', true );
		$weight = get_post_meta( $post->ID, '_mstw_tr_weight', true );
		$position = get_post_meta( $post->ID, '_mstw_tr_position', true );
		
		// year is used in the high-school and college formats
		$year = get_post_meta( $post->ID, '_mstw_tr_year', true );
		
		// experience is used in the college and pro formats
		$experience = get_post_meta( $post->ID, '_mstw_tr_experience', true );
		
		// age is used in the pro format only
		$age = get_post_meta( $post->ID, '_mstw_tr_age', true );
		
		// home_town is used in the college format only
		$home_town = get_post_meta( $post->ID, '_mstw_tr_home_town', true );
		
		// last_school is used in the college and pro formats
		$last_school = get_post_meta( $post->ID, '_mstw_tr_last_school', true );
		
		// country is used in the pro format only
		$country = get_post_meta( $post->ID, '_mstw_tr_country', true );
		
		// used in the baseball formats only
		$bats = get_post_meta( $post->ID, '_mstw_tr_bats', true );
		$throws = get_post_meta( $post->ID, '_mstw_tr_throws', true );
		
		// other info
		$other = get_post_meta( $post->ID, '_mstw_tr_other', true );
		   
		?>	
		
	   <table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_first_name" ><?php echo( __( 'First Name', 'mstw-loc-domain' ) . ':' ); ?> </label></th>
			<td><input maxlength="64" size="20" name="mstw_tr_first_name"
				value="<?php echo esc_attr( $first_name ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_last_name" ><?php echo( __( 'Last Name', 'mstw-loc-domain' ) . ':' ); ?> </label></th>
			<td><input maxlength="64" size="20" name="mstw_tr_last_name"
				value="<?php echo esc_attr( $last_name ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_number" >Number:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_number"
				value="<?php echo esc_attr( $number ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_position" >Position:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_position"
        	value="<?php echo esc_attr( $position ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_height" >Height:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_height" 
				value="<?php echo esc_attr( $height ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_weight" >Weight:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_weight" 
				value="<?php echo esc_attr( $weight ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_year" >Year:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_year"
        	value="<?php echo esc_attr( $year ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_experience" >Experience:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_experience"
        	value="<?php echo esc_attr( $experience ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_age" >Age:</label></th>
			<td><input maxlength="64" size="8" name="mstw_tr_age"
        	value="<?php echo esc_attr( $age ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_home_town" >Home Town:</label></th>
			<td><input maxlength="64" size="20" name="mstw_tr_home_town"
        	value="<?php echo esc_attr( $home_town ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_last_school" >Last School:</label></th>
			<td><input maxlength="64" size="20" name="mstw_tr_last_school"
        	value="<?php echo esc_attr( $last_school ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_country" >Country:</label></th>
			<td><input maxlength="64" size="20" name="mstw_tr_country"
        	value="<?php echo esc_attr( $country ); ?>"/></td>
		</tr>
		<tr valign="top">
    	<th scope="row"><label for="mstw_tr_bats" >Bats:</label></th>
        <td>
        <select name="mstw_tr_bats">    
			<?php foreach ( $bats_list as $label ) {  ?>
          			<option value="<?php echo $label; ?>" <?php selected( $bats, $label );?>>
          				<?php echo $label; ?>
                     </option>              
     		<?php } ?> 
        </select>   
        </td>
		</tr>
		<tr valign="top">
    	<th scope="row"><label for="mstw_tr_bats" >Throws:</label></th>
        <td>
        <select name="mstw_tr_throws">    
			<?php foreach ( $throws_list as $label ) {  ?>
          			<option value="<?php echo $label; ?>" <?php selected( $throws, $label );?>>
          				<?php echo $label; ?>
                     </option>              
     		<?php } ?> 
        </select>   
        </td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_other" >Other Info:</label></th>
			<td><input maxlength="256" size="20" name="mstw_tr_other"
        	value="<?php echo esc_attr( $other ); ?>"/></td>
		</tr>
		
    </table>
    
<?php        	
}

// ----------------------------------------------------------------------
// Save the Team Roster Meta Data
	add_action( 'save_post', 'mstw_tr_save_meta' );

	function mstw_tr_save_meta( $post_id ) {
		//
		//Just return on an autosave
		//
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
			
		//	
		// check that the post type is 'mstw_tr_player', if so, process the data
		//
		if( isset( $_POST['post_type'] ) ) {		
			if ( $_POST['post_type'] == 'mstw_tr_player' ) {
				update_post_meta( $post_id, '_mstw_tr_first_name', 
						strip_tags( $_POST['mstw_tr_first_name'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_last_name', 
						strip_tags( $_POST['mstw_tr_last_name'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_number', 
						strip_tags( $_POST['mstw_tr_number'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_position', 
						strip_tags( $_POST['mstw_tr_position'] ) );		
						
				update_post_meta( $post_id, '_mstw_tr_height', 
						strip_tags( $_POST['mstw_tr_height'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_weight',  
						strip_tags( $_POST['mstw_tr_weight'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_year',  
						strip_tags( $_POST['mstw_tr_year'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_experience',
						strip_tags( $_POST['mstw_tr_experience'] ) );
				
				update_post_meta( $post_id, '_mstw_tr_age', 
						strip_tags( $_POST['mstw_tr_age'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_home_town',
						strip_tags( $_POST['mstw_tr_home_town'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_last_school',
						strip_tags( $_POST['mstw_tr_last_school'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_country',
						strip_tags( $_POST['mstw_tr_country'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_bats',
						strip_tags( $_POST['mstw_tr_bats'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_throws',
						strip_tags( $_POST['mstw_tr_throws'] ) );
						
				update_post_meta( $post_id, '_mstw_tr_other',
						strip_tags( $_POST['mstw_tr_other'] ) );
			} //End: if ( $_POST['post_type'] == 'mstw_tr_player' )
		} //End: if( isset( $_POST['post_type'] ) )
	} //End: function mstw_tr_save_meta

	// ----------------------------------------------------------------
	// Set up the Team Roster 'view all' columns

	add_filter( 'manage_edit-mstw_tr_player_columns', 'mstw_tr_edit_columns' ) ;

	function mstw_tr_edit_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'mstw-loc-domain' ),
			'team' => __( 'Team', 'mstw-loc-domain' ),
			'first-name' => __( 'First Name', 'mstw-loc-domain' ),
			'last-name' => __( 'Last Name', 'mstw-loc-domain' ),
			'number' => __( 'Number', 'mstw-loc-domain' ),
			'position' => __( 'Position', 'mstw-loc-domain' ),
			'height' => __( 'Height', 'mstw-loc-domain' ),
			'weight' => __( 'Weight', 'mstw-loc-domain' ),
			'year' => __( 'Year', 'mstw-loc-domain' ),
			'experience' => __( 'Experience', 'mstw-loc-domain' )
		);

		return $columns;
	}

	// ----------------------------------------------------------------
	// Display the Team Roster 'view all' columns

	add_action( 'manage_mstw_tr_player_posts_custom_column', 'mstw_tr_manage_columns', 10, 2 );

	function mstw_tr_manage_columns( $column, $post_id ) {
		global $post;
		
		/* echo 'column: ' . $column . " Post ID: " . $post_id; */

		switch( $column ) {
			case 'team' :
				$taxonomy = 'mstw_tr_team';
				
				$teams = get_the_terms( $post_id, $taxonomy );
				if ( is_array( $teams) ) {
					foreach( $teams as $key => $team ) {
						$teams[$key] =  $team->name;
					}
						echo implode( ' | ', $teams );
				}
				break;
				
			case 'first-name' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_first_name', true ) );
				break;
				
			case 'last-name' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_last_name', true ) );
				break;
			
			case 'number' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_number', true ) );
				break;
					
			case 'position' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_position', true ) );
				break;

			case 'height' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_height', true ) );
				break;
				
			case 'weight' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_weight', true ) );
				break;

			case 'year' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_year', true ) );
				break;
				
			case 'experience' :
				printf( '%s', get_post_meta( $post_id, '_mstw_tr_experience', true ) );
				break;
				
			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}

	
	// Add a menu item for the Admin pages
	add_action('admin_menu', 'mstw_tr_register_menu_pages');

	function mstw_tr_register_menu_pages( ) {
	

		//Top Level Menu
		
		add_menu_page( __( 'Team Rosters', 'mstw-team-rosters' ), //$page_title, 
					   __( 'Team Rosters', 'mstw-team-rosters' ), //$menu_title, 
					   'read', //$capability, 
					   'edit.php?post_type=mstw_tr_player', 
					   null, 
					   plugins_url( 'images/mstw-admin-menu-icon.png', dirname( __FILE__ ) ), //$menu_icon
					   "58.75" //menu order
					 );
		//Players			 
		add_submenu_page( 	'edit.php?post_type=mstw_tr_player', 
								__( 'Players', 'mstw-team-rosters' ), //page title
								__( 'Players', 'mstw-team-rosters' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_tr_player', // Slug name to refer to this menu
								null							
						); // Callback to output content
						
		// Settings
		$settings_page = add_submenu_page( 	'edit.php?post_type=mstw_tr_player', 				//parent slug
							__( 'Settings', 'mstw-team-rosters' ), 	//page title
							__( 'Settings', 'mstw-team-rosters' ),	//menu title
							'read', 									//user capability required to access
							'mstw-tr-settings', 							//unique menu slug
							'mstw_tr_settings_page' );					//callback to display page
							
		//
		// Load the settings help pages
		//
		add_action( "load-$settings_page", 'mstw_tr_settings_help' );
		
							

		//require_once ABSPATH . '/wp-admin/admin.php'; - not needed?
		$plugin = new MSTW_TR_ImporterPlugin;
		
		add_submenu_page(	'edit.php?post_type=mstw_tr_player',
							'Import Roster from CSV File',			//page title
							'CSV Roster Import',					//menu title
							'manage_options',
							'mstw_tr_csv_import',
							array( $plugin, 'form' )
						);
							
		// Now also add action to load java scripts ONLY when you're on this page
	}



function mstw_tr_fields_columns_text( ) {
	echo '<p>' . __( 'Enter the default settings for Rosters Table columns, as well as the Single Player and Player Gallery data fields. These settings will apply to the [shortcode] roster tables, where they can be overridden by [shortcode] arguments, as well as the single player and player gallery pages.', 'mstw-loc-domain' ) .  '</p><p>' . __('IF YOU WANT THESE SETTINGS TO APPLY, THE SPECIFIED FORMAT MUST BE "CUSTOM" OR BLANK. IF A SPECIFIC FORMAT, SUCH AS "HIGH-SCHOOL" IS SPECIFIED, IT WILL OVERRIDE THESE SETTINGS.', 'mstw-loc-domain' ) .  '</p>';
}

	//------------------------------------------------------------------
	// Add admin_notices action - need to look at this more someday
	//
	//add_action( 'admin_notices', 'mstw_tr_admin_notices' );
	
	function mstw_tr_admin_notices( ) {
		mstw_admin_notice( 'mstw_tr_admin_messages' );
	}

// ------------------------------------------------------------------------
// Setup the UI
// ------------------------------------------------------------------------	

	

	
	// Roster Table Colors section instructions
	function mstw_tr_roster_table_colors_text( ) {
		echo '<p>' . __( 'Enter the default team roster table settings. Note that these settings will apply to all the [shortcode] roster tables, overriding the default styles. However they can be overridden by more specific stylesheet rules for specific teams.', 'mstw-loc-domain' ) . '</p>';
	}
	
	// setup the single player bio page
	function mstw_tr_single_player_bio_setup( ) {
		$display_on_page = 'mstw_tr_fields_settings';
		$page_section = 'mstw_tr_single_settings';
		
		$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
		
		//mstw_log_msg( 'in mstw_tr_single_player_bio_setup ... ' );
		//mstw_log_msg( $options );
		
		/* Player Bio Page (single player page) settings */
		add_settings_section(
			$page_section,  					//id attribute of tags
			'Player Bio Page & Player Gallery Page Settings',	//title of the section
			'mstw_tr_bio_gallery_text',		//callback to fill section with desired output - should echo
			$display_on_page					//menu page slug on which to display
		);
		
		// Title for the content (E.g., "Player Bio")
		$args = array( 	'id' => 'sp_content_title',
						'name' => 'mstw_tr_options[sp_content_title]',
						'value' => $options['sp_content_title'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_content_title',
			__( 'Player Profile(Bio) Title:', 'mstw-loc-domain' ),
			'mstw_tr_text_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		/// Player Photo (thumbnail) width
		$args = array( 	'id' => 'sp_image_width',
						'name' => 'mstw_tr_options[sp_image_width]',
						'value' => $options['sp_image_width'],
						'label' => 'in pixels. Defaults to 150px.'
					 );
					 
		add_settings_field(
			'sp_image_width',
			__( 'Player Photo Width:', 'mstw-loc-domain' ),
			'mstw_tr_text_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Player Photo (thumbnail) height
		$args = array( 	'id' => 'sp_image_height',
						'name' => 'mstw_tr_options[sp_image_height]',
						'value' => $options['sp_image_height'],
						'label' => 'in pixels. Defaults to 150px.'
					 );
					 
		add_settings_field(
			'sp_image_height',
			__( 'Player Photo Height:', 'mstw-loc-domain' ),
			'mstw_tr_text_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
	
		
		// Background color of main box
		$args = array( 	'id' => 'sp_main_bkgd_color',
						'name' => 'mstw_tr_options[sp_main_bkgd_color]',
						'value' => mstw_safe_ref( $options, 'sp_main_bkgd_color'), //$options['sp_main_bkgd_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_main_bkgd_color',
			__( 'Main Box Background Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Text color of main box
		$args = array( 	'id' => 'sp_main_text_color',
						'name' => 'mstw_tr_options[sp_main_text_color]',
						'value' => mstw_safe_ref( $options, 'sp_main_text_color'), //$options['sp_main_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_main_text_color',
			__( 'Main Box Text Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Border color of player profile box
		$args = array( 	'id' => 'sp_bio_border_color',
						'name' => 'mstw_tr_options[sp_bio_border_color]',
						'value' => mstw_safe_ref( $options, 'sp_bio_border_color'), //$options['sp_bio_border_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_border_color',
			__( 'Player Profile(Bio) Border Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Header text color of player bio box
		$args = array( 	'id' => 'sp_bio_header_color',
						'name' => 'mstw_tr_options[sp_bio_header_color]',
						'value' => mstw_safe_ref( $options, 'sp_bio_header_color'), //$options['sp_bio_header_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_header_color',
			__( 'Player Bio Header Text Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Text color of player bio box
		$args = array( 	'id' => 'sp_bio_text_color',
						'name' => 'mstw_tr_options[sp_bio_text_color]',
						'value' => mstw_safe_ref( $options, 'sp_bio_text_color'), //$options['sp_bio_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_text_color',
			__( 'Player Bio Text Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Background color of player bio box
		$args = array( 	'id' => 'sp_bio_bkgd_color',
						'name' => 'mstw_tr_options[sp_bio_bkgd_color]',
						'value' => mstw_safe_ref( $options, 'sp_bio_bkgd_color'), //$options['sp_bio_bkgd_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_bkgd_color',
			__( 'Player Bio Background Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Gallery links color
		$args = array( 	'id' => 'gallery_links_color',
						'name' => 'mstw_tr_options[gallery_links_color]',
						'value' => mstw_safe_ref( $options, 'gallery_links_color'), //$options['gallery_links_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'gallery_links_color',
			__( 'Player Gallery Links Color:', 'mstw-loc-domain' ),
			'mstw_tr_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);		
	}
	
	// Single player section instructions
	function mstw_tr_bio_gallery_text( ) {
		echo '<p>' . __( 'Enter your single player and player gallery page settings. Unless otherwise noted, these settings will apply to both the pages.', 'mstw-loc-domain' ) . '</p>';
		//echo '<p>' . WP_PLUGIN_DIR . '/team-rosters/js/mstw-theme-color.js' .'</p>';
	}

// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
// CSV Importer Class
// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
class MSTW_TR_ImporterPlugin {
    var $defaults = array(
        'csv_post_title'      => null,
        'csv_post_post'       => null,
        'csv_post_type'       => null,
        'csv_post_excerpt'    => null,
        'csv_post_date'       => null,
        'csv_post_tags'       => null,
        'csv_post_categories' => null,
        'csv_post_author'     => null,
        'csv_post_slug'       => null,
        'csv_post_parent'     => 0,
    );

    var $log = array();

    /**
     * Determine value of option $name from database, $default value or $params,
     * save it to the db if needed and return it.
     */
    function process_option( $name, $default, $params ) {
        if ( array_key_exists( $name, $params ) ) {
            $value = stripslashes( $params[$name] );
        } elseif ( array_key_exists( '_'.$name, $params ) ) {
            // unchecked checkbox value
            $value = stripslashes( $params['_'.$name] );
        } else {
            $value = null;
        }
        $stored_value = get_option( $name );
        if ( $value == null ) {
            if ($stored_value === false) {
                if (is_callable($default) &&
                    method_exists($default[0], $default[1])) {
                    $value = call_user_func($default);
                } else {
                    $value = $default;
                }
                add_option($name, $value);
            } else {
                $value = $stored_value;
            }
        } else {
            if ($stored_value === false) {
                add_option($name, $value);
            } elseif ($stored_value != $value) {
                update_option($name, $value);
            }
        }
        return $value;
    } //End function process_option()

    /**
     * Plugin's admin user interface
     *
     */
    function form( ) {
        
        $opt_cat = $this->process_option( 'csv_importer_cat', 0, $_POST );

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->post(compact('opt_draft', 'opt_cat'));
        }

        // form HTML {{{
?>

<div class="wrap">
    <?php echo get_screen_icon(); ?>
	<h2>Import CSV</h2>
    <form class="add:the-list: validate" method="post" enctype="multipart/form-data">
	
		<!-- Team taxonomy -->
		<?php $args = array(	'show_option_all'    => 'Select a team ...',
								'show_option_none'   => '',
								'orderby'            => 'name', 
								'order'              => 'ASC',
								'show_count'         => 0,
								'hide_empty'         => 0, 
								'child_of'           => 0,
								'exclude'            => '',
								'echo'               => 1,
								'selected'           => $opt_cat,
								'hierarchical'       => 0, 
								'name'               => 'csv_importer_cat',
								'id'                 => '',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'mstw_tr_team',
								'hide_if_empty'      => false
							); ?>
							
        <p>Select Team to Import:  <?php wp_dropdown_categories( $args );?><br/>
        </p>

        <!-- File input -->
        <p><label for="csv_import">Upload file:</label><br/>
            <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
        <p class="submit"><input type="submit" class="button" name="submit" value="Import" /></p>
    </form>
</div><!-- end wrap -->

<?php
        // end form HTML }}}

    } //End of function form()

    function print_messages() {
        if (!empty($this->log)) {

        // messages HTML {{{
?>

<div class="wrap">
    <?php if (!empty($this->log['error'])): ?>

    <div class="error">

        <?php foreach ($this->log['error'] as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>

    <?php if (!empty($this->log['notice'])): ?>

    <div class="updated fade">

        <?php foreach ($this->log['notice'] as $notice): ?>
            <p><?php echo $notice; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>
</div><!-- end wrap -->

<?php
        // end messages HTML }}}

            $this->log = array();
        }
    } //End function print_messages()

    /**
     * Handle POST submission
     *
     * @param array $options
     * @return void
     */
    function post( $options ) {
	
		extract( $options );
		
		// Check that a team has been selected
		echo '<p>$opt_cat(ID): ' . $opt_cat;
		$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
		echo ' Team slug: ' . $term->slug . '</p>';
		if ( $term ) {
			echo 'Team slug: ' . $term->slug . '</p>';
		}
		else {
			$this->log['error'][] = 'Please select a team. Exiting.';
            $this->print_messages();
            return;
		}
		// Check that a file has been uploaded
        if ( empty($_FILES['csv_import']['tmp_name']) ) {
            $this->log['error'][] = 'Please select a file. Exiting.';
            $this->print_messages();
            return;
        }

        echo '<p> Loading DataSource ... </p>';
		if ( !class_exists( 'File_CSV_DataSource' ) ) {
			require_once 'DataSource.php';
			echo '<p> Done. </p>';
		} else {
			echo '<p> Alrady loaded. </p>';
		}

        $time_start = microtime(true);
        $csv = new File_CSV_DataSource;
        $file = $_FILES['csv_import']['tmp_name'];
        $this->stripBOM($file);

        if (!$csv->load($file)) {
            $this->log['error'][] = 'Failed to load file, aborting.';
            $this->print_messages();
            return;
        }

        // pad shorter rows with empty values
        $csv->symmetrize();

        // WordPress sets the correct timezone for date functions somewhere
        // in the bowels of wp_insert_post(). We need strtotime() to return
        // correct time before the call to wp_insert_post().
        $tz = get_option('timezone_string');
        if ($tz && function_exists('date_default_timezone_set')) {
            date_default_timezone_set($tz);
        }

        $skipped = 0;
        $imported = 0;
        $comments = 0;
        foreach ($csv->connect() as $csv_data) {
			// First try to create the post from the row
            if ($post_id = $this->create_post( $csv_data, $options )) {
                $imported++;
				//Insert the custom fields, which is most everything
                $this->create_custom_fields( $post_id, $csv_data );
            } else {
                $skipped++;
            }
        }

        if (file_exists($file)) {
            @unlink($file);
        }

        $exec_time = microtime(true) - $time_start;

        if ($skipped) {
            $this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, body and excerpt).</b>";
        }
        $this->log['notice'][] = sprintf("<b>Imported {$imported} posts to {$term->slug} in %.2f seconds.</b>", $exec_time);
        $this->print_messages();
    }

    function create_post( $data, $options ) {
        extract( $options );

        $data = array_merge( $this->defaults, $data );

		// The post type is hardwired for this plugin's custom post type
		$type = 'mstw_tr_player';
		
        $valid_type = ( function_exists( 'post_type_exists' ) &&
            post_type_exists( $type )) || in_array( $type, array('post', 'page' ));

        if ( !$valid_type ) {
            $this->log['error']["type-{$type}"] = sprintf(
                'Unknown post type "%s".', $type);
        }
		
		$temp_title = '';
		echo '<p>First Name: ' . $data['First Name'] . '</p>';
		if ( $data['First Name'] != '' ) {
			$temp_title .= $data['First Name'];
		} else if ( $data['First'] != '' ) {
			$temp_title .= $data['First'];
		}
		echo '<p>Last Name: ' . $data['Last Name'] . '</p>';
		if ( $data['Last Name'] != '' ) {
			if ( $temp_title != '' ) //add a space between the names
				$temp_title .= ' ';
			$temp_title .= $data['Last Name'];
		} else if ( $data['Last'] != '' ) {
			if ( $temp_title != '' ) 
				$temp_title .= ' ';  //add a space between the names
			$temp_title .= $data['Last'];
		}
		
		if ( $temp_title == '' )
			$temp_title = __( 'No first or last name', 'mstw-loc-domain' );
		else
			$temp_slug = sanitize_title( $temp_title );  
		
		echo '<p>Title: ' . $temp_title . ' Slug: ' . $temp_slug . '</p>';
		echo '<p>$opt_cat(ID): ' . $opt_cat . '</p>';
		$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );

        $new_post = array(
            'post_title'   => convert_chars( $temp_title ),
            'post_content' => wpautop(convert_chars($data['Bio'])),
            'post_status'  => 'publish',
            'post_type'    => $type,
            'post_name'    => $temp_slug,
        );
        
        // create!
        $id = wp_insert_post( $new_post );
		
		if ( $id ) {
			$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
			wp_set_object_terms( $id, $term->slug, 'mstw_tr_team');
		}

        if ('page' !== $type && !$id) {
            // cleanup new categories on failure
            foreach ($cats['cleanup'] as $c) {
                wp_delete_term($c, 'category');
            }
        }
        return $id;
    } //End function create_post()

    function create_custom_fields( $post_id, $data ) {
        foreach ( $data as $k => $v ) {
            // anything that doesn't start with csv_ is a custom field
            if (!preg_match('/^csv_/', $k) && $v != '') {
				switch ( strtolower( $k ) ) {
					case __( 'first name', 'mstw-loc-domain' ):
					case __( 'first', 'mstw-loc-domain' ):
						$k = '_mstw_tr_first_name';
						break;
					case __( 'last name', 'mstw-loc-domain' ):
					case __( 'last', 'mstw-loc-domain' ):
						$k = '_mstw_tr_last_name';
						break;
					case __( 'position', 'mstw-loc-domain' ):
					case __( 'pos', 'mstw-loc-domain' ):
						$k = '_mstw_tr_position';
						break;
					case __( 'number', 'mstw-loc-domain' ):
					case __( 'nbr', 'mstw-loc-domain' ):
					case __( '#', 'mstw-loc-domain' ):
						$k = '_mstw_tr_number';
						break;
					case __( 'weight', 'mstw-loc-domain' ):
					case __( 'wt', 'mstw-loc-domain' ):
						$k = '_mstw_tr_weight';
						break;
					case __( 'height', 'mstw-loc-domain' ):
					case __( 'ht', 'mstw-loc-domain' ):
						$k = '_mstw_tr_height';
						break;
					case __( 'age', 'mstw-loc-domain' ):
						$k = '_mstw_tr_age';
						break;
					case __( 'year', 'mstw-loc-domain' ):
					case __( 'yr', 'mstw-loc-domain' ):
						$k = '_mstw_tr_year';
						break;
					case __( 'experience', 'mstw-loc-domain' ):
					case __( 'exp', 'mstw-loc-domain' ):
						$k = '_mstw_tr_experience';
						break;
					case __( 'home town', 'mstw-loc-domain' ):
						$k = '_mstw_tr_home_town';
						break;
					case __( 'country', 'mstw-loc-domain' ):
						$k = '_mstw_tr_country';
						break;
					case __( 'last school', 'mstw-loc-domain' ):
						$k = '_mstw_tr_last_school';
						break;
					case __( 'bats', 'mstw-loc-domain' ):
					case __( 'bat', 'mstw-loc-domain' ):
						$k = '_mstw_tr_bats';
						break;
					case __( 'throws', 'mstw-loc-domain' ):
					case __( 'throw', 'mstw-loc-domain' ):
					case __( 'thw', 'mstw-loc-domain' ):
						$k = '_mstw_tr_throws';
						break;
					case __( 'other', 'mstw-loc-domain' ):
						$k = '_mstw_tr_other';
						break;
				}
					
				$ret = update_post_meta( $post_id, $k, $v );	
				
				echo '<p>retval = '. $ret . ' ID: ' . $post_id . ' K: ' . $k . ' V: ' . $v . '</p>';
            }
        }
    }

    /**
     * Delete BOM from UTF-8 file.
     *
     * @param string $fname
     * @return void
     */
    function stripBOM($fname) {
        $res = fopen($fname, 'rb');
        if (false !== $res) {
            $bytes = fread($res, 3);
            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
                $this->log['notice'][] = 'Getting rid of byte order mark...';
                fclose($res);

                $contents = file_get_contents($fname);
                if (false === $contents) {
                    trigger_error('Failed to get file contents.', E_USER_WARNING);
                }
                $contents = substr($contents, 3);
                $success = file_put_contents($fname, $contents);
                if (false === $success) {
                    trigger_error('Failed to put file contents.', E_USER_WARNING);
                }
            } else {
                fclose($res);
            }
        } else {
            $this->log['error'][] = 'Failed to open file, aborting.';
        }
    }
}
?>