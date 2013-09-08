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

/*-------------------------------------------------------------------------
 * CHANGE LOG:
 *
 * 20121202-MAO: 
 *	(1)	Added settings section for the taxonomy (player gallery) page
 * 20121211-MAO: 
 *	(1)	Added setting for title of content ("Player Bio") on single page
 * 
 * 20130125-MAO: 
 *	(1)	Added new admin page for CSV import - no page content/controls yet
 * 20130126-MAO: 
 *	(1)	Added content/controls for CSV import page 
 
 * 20130129-MAO: 
 *	(1)	Added theme support for thumbnails
 *
 * 20130202-MAO: 
 *	(1)	Added content/controls for baseball formats
 *
 * 20130615-MAO:
 *	Many changes to support:
 *	(1) New WordPress color selector
 *	(2)	Admin settings for show/hide columns and data fields
 *	(3) Admin settings for changing column and date field labels
 *	(4) Added Filter by Team button to View All Players screen
 *	(5) Removed Bulk Edit button from View All Players screen
 *
 * 20130803-MAO:
 *	(1) Corrections to eliminate debug warnings.
 *	(2) Corrections to prevent conflicts with including mstw-admin-utility-functions.php.
 *		Now requires mstw-tr-admin-utility-functions.php
 *
 * 20130822-MAO:
 *	(1) Re-enabled the bulk edit menu for delete only. 
 *		(really only needed quick actions menu removed)
 * 	(2) Removed link options. Taxonomy template and table 
 *		shortcode now detect the single-player.php 
 *		template and add the links automagically.
 * *-------------------------------------------------------------------------*/

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
	// Load the MSTW Admin Utility Functions if necessary
	if ( !function_exists( 'mstw_admin_utils_loaded' ) ) {
			require_once  plugin_dir_path( __FILE__ ) . 'mstw-admin-utils.php';
	}
	// ----------------------------------------------------------------	
	// Add styles and scripts for the color picker. 
	add_action( 'admin_enqueue_scripts', 'mstw_tr_enqueue_color_picker' );
	
	function mstw_tr_enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mstw-tr-color-picker', plugins_url( 'team-rosters/js/tr-color-settings.js' ), array( 'wp-color-picker' ), false, true ); 
	}
	
	// ----------------------------------------------------------------
	// Add the custom MSTW icon to CPT pages
	add_action('admin_head', 'mstw_tr_custom_css');
	
	function mstw_tr_custom_css() { ?>
		<style type="text/css">
			#icon-mstw-tr-main-menu.icon32 {
				background: url('<?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' ); ?>') transparent no-repeat;
			}
			#icon-player.icon32 {
				background: url( ' <?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' ); ?> ') transparent no-repeat;
			}
			#icon-edit.icon32-posts-player {
				background: url( ' <?php echo plugins_url( '/team-rosters/images/mstw-logo-32x32.png', 'team-rosters' );?> ') transparent no-repeat;
			}
			#menu-posts-player .wp-menu-image {
				background-image: url('<?php echo plugins_url( '/team-rosters/images/mstw-admin-menu-icon.png', 'team-rosters' );?> ') no-repeat 6px -17px !important;
			}
			
		</style>
	<?php }
	
	// ----------------------------------------------------------------
	// Remove Quick Edit Menu	
	add_filter( 'post_row_actions', 'mstw_tr_remove_quick_edit', 10, 2 );

	function mstw_tr_remove_quick_edit( $actions, $post ) {
		if( $post->post_type == 'player' ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	// ----------------------------------------------------------------
	// Remove the Bulk Actions pull-down
	// 20130822-MAO: No reason to remove this. Really just wanted
	// 		the quick edit menu removed.
	add_filter( 'bulk_actions-edit-player', 'mstw_tr_bulk_actions' );

    function mstw_tr_bulk_actions( $actions ){
        unset( $actions['edit'] );
        return $actions;
    }
		
	// ----------------------------------------------------------------
	// Add a filter the All Teams screen based on the Leagues Taxonomy
	add_action('restrict_manage_posts','mstw_tr_restrict_manage_posts');
	
	function mstw_tr_restrict_manage_posts( ) {
		global $typenow;

		if ( $typenow=='player' ){
			// Trying to find current selection
			$selected = isset( $_REQUEST[$teams] ) ? $_REQUEST[$teams] : '';
				
			$args = array(
						'show_option_all' => 'All Teams',
						'taxonomy' => 'teams',
						'name' => 'teams',
						'orderby' => 'name',
						'selected' => $_GET['teams'],
						'show_count' => true,
						'hide_empty' => true,
						);
			wp_dropdown_categories( $args );
		}
	}
	
	add_action( 'request', 'mstw_tr_request' );
	function mstw_tr_request( $request ) {
		if ( is_admin( ) && $GLOBALS['PHP_SELF'] == '/wp-admin/edit.php' && isset( $request['post_type'] ) && $request['post_type']=='player' ) {
			$request['term'] = get_term( $request['teams'], 'teams' )->name;
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
						'player', 
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
		global $mstw_tr_msg_str;
		global $mstw_tr_debug_str;
		
		//First verify the required metadata is set and valid. If not, set default or return error
				
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
		
	}

	// ----------------------------------------------------------------
	// Set up the Team Roster 'view all' columns

	add_filter( 'manage_edit-player_columns', 'mstw_tr_edit_columns' ) ;

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

	add_action( 'manage_player_posts_custom_column', 'mstw_tr_manage_columns', 10, 2 );

	function mstw_tr_manage_columns( $column, $post_id ) {
		global $post;
		
		/* echo 'column: ' . $column . " Post ID: " . $post_id; */

		switch( $column ) {
			case 'team' :
				$taxonomy = 'teams';
				
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
	
		// Add the columns/fields page to the Players menu
		$page = add_submenu_page( 	'edit.php?post_type=player', 				//parent slug
							__( 'Team Rosters Display Settings', 'mstw-loc-domain' ), 	//page title
							__( 'Display Settings', 'mstw-loc-domain' ),	//menu title
							'manage_options', 									//user capability required to access
							'mstw_tr_fields_settings', 							//unique menu slug
							'mstw_tr_fields_columns_page' );					//callback to display page
							

		//require_once ABSPATH . '/wp-admin/admin.php'; - not needed?
		$plugin = new MSTW_TR_ImporterPlugin;
		
		add_submenu_page(	'edit.php?post_type=player',
							'Import Roster from CSV File',			//page title
							'CSV Roster Import',					//menu title
							'manage_options',
							'mstw_tr_csv_import',
							array( $plugin, 'form' )
						);
							
		// Now also add action to load java scripts ONLY when you're on this page
	}
					 
					 
	// --------------------------------------------------------------------------------------
	// Add a menu for our option page
	//add_action('admin_menu', 'mstw_tr_add_pages');

	function mstw_tr_add_pages(  ) {
		//The next line adds the settings page to the Settings menu
		//add_options_page( 'Team Rosters Settings', 'Team Rosters Settings', 'manage_options', 'mstw_tr_settings', 'mstw_tr_option_page' );
							
		// Add the columns/fields page to the Players menu
		$page = add_submenu_page( 	'edit.php?post_type=player', 				//parent slug
							__( 'Team Rosters Settings', 'mstw-loc-domain' ), 	//page title
							__( 'Display Settings', 'mstw-loc-domain' ),	//menu title
							'manage_options', 									//user capability required to access
							'mstw_tr_fields_settings', 							//unique menu slug
							'mstw_tr_fields_columns_page' );					//callback to display page
							

		//require_once ABSPATH . '/wp-admin/admin.php'; - not needed?
		$plugin = new MSTW_TR_ImporterPlugin;
		
		add_submenu_page(	'edit.php?post_type=player',
							'Import Roster from CSV File',			//page title
							'CSV Roster Import',					//menu title
							'manage_options',
							'mstw_tr_csv_import',
							array( $plugin, 'form' )
						);
							
		// Now also add action to load java scripts ONLY when you're on this page
	}

	// ----------------------------------------------------------------
	// Render the fields/columns control page
	// ----------------------------------------------------------------
	function mstw_tr_fields_columns_page( ) {
		?>
		<div class="wrap">
			<?php screen_icon( ); ?>
			<h2>Team Rosters Settings</h2>
			<?php //settings_errors(); ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'mstw_tr_fields_options' ); ?>
				<?php do_settings_sections( 'mstw_tr_fields_settings' ); ?>
				<p>
				<input name="submit" type="submit" class="button-primary" value=<?php _e( "Save Changes", "mstw-loc-domain" ); ?> />
				
				<input type="submit" name="mstw_tr_options[reset]" value=<?php _e( "Reset Default Values", "mstw-loc-domain" ) ?> />
					<strong><?php _e( "WARNING! Reset Default Values will do so without further warning!", "mstw-loc-domain" ); ?></strong>
				</p>
			</form>
		</div>
		<?php
	}


	// ----------------------------------------------------------------
	// Register and define the settings
	// ----------------------------------------------------------------
	add_action('admin_init', 'mstw_tr_admin_init');

	function mstw_tr_admin_init(){
		$options = get_option( 'mstw_tr_options' );
		$options = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		//print_r ($options);
		
		// Settings for the fields and columns display and label controls.
		register_setting(
			'mstw_tr_fields_options',
			'mstw_tr_options',
			'mstw_tr_validate_fields_options'
		);
		
		/* Roster Table [shortcode] settings */
		mstw_tr_roster_data_fields_setup( );
		
		// setup the colors
		mstw_tr_roster_table_setup( );
		
		// setup the single player bio page
		mstw_tr_single_player_bio_setup( );
	
	}

function mstw_tr_fields_columns_text( ) {
	echo '<p>' . __( 'Enter the default settings for Rosters Table columns, as well as the Single Player and Player Gallery data fields. These settings will apply to the [shortcode] roster tables, where they can be overridden by [shortcode] arguments, as well as the single player and player gallery pages.', 'mstw-loc-domain' ) .  '</p><p>' . __('IF YOU WANT THESE SETTINGS TO APPLY, THE SPECIFIED FORMAT MUST BE "CUSTOM" OR BLANK. IF A SPECIFIC FORMAT, SUCH AS "HIGH-SCHOOL" IS SPECIFIED, IT WILL OVERRIDE THESE SETTINGS.', 'mstw-loc-domain' ) .  '</p>';
}

/*--------------------------------------------------------------
 *	Validate & sanitize user input
 */
 
function mstw_tr_validate_fields_options( $input ) {
	// Create our array for storing the validated options
	$output = array();
	// Pull the previous (good) options
	$options = get_option( 'mstw_tr_options' );
	
	if ( array_key_exists( 'reset', $input ) ) {
		if ( $input['reset'] ) {
				$output = mstw_tr_get_defaults( );
				return $output;
		}
	}
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
			switch ( $key ) {
				// add the hex colors
				case 'tr_table_head_text_color':
				case 'tr_table_head_bkgd_color':
				case 'tr_table_title_text_color':
				case 'tr_table_links_color':
				case 'tr_table_even_row_color':
				case 'tr_table_even_row_bkgd':
				case 'tr_table_odd_row_color':
				case 'tr_table_odd_row_bkgd':
				case 'sp_main_bkgd_color':
				case 'sp_main_text_color':
				case 'gallery_links_color':
					// validate the color for proper hex format
					$sanitized_color = mstw_utl_sanitize_hex_color( $input[$key] );
					
					// decide what to do - save new setting 
					// or display error & revert to last setting
					if ( isset( $sanitized_color ) ) {
						// blank input is valid
						$output[$key] = $sanitized_color;
					}
					else  {
						// there's an error. Reset to the last stored value
						$output[$key] = $options[$key];
						// add error message
						add_settings_error( 'mstw_tr_' . $key,
											'mstw_tr_hex_color_error',
											'Invalid hex color entered!',
											'error');
					}
					break;
					
				case 'sp_image_width':
				case 'sp_image_height':
				case 'table_photo_width':
				case 'table_photo_height':
					$output[$key] = round( $input[$key] );
					$output[$key] = ( $output[$key] == 0 ) ? '' : $output[$key];
					break;
					
				// 0-1 stuff
				case 'show_title':
				case 'show_photos':
					if ( $input[$key] == 1 ) {
						$output[$key] = 1;
					}
					else {
						$input[$key] = 0;
					}
					break;
					
				// Check all other settings
				default:
					$output[$key] = sanitize_text_field( $input[$key] );
					// There should not be user/accidental errors in these fields
					break;
				
			} // end switch
		} // end if
	} // end foreach
	return $output;
}

	//------------------------------------------------------------------
	// Add admin_notices action - need to look at this more someday
	
	add_action( 'admin_notices', 'mstw_tr_admin_notices' );
	
	function mstw_tr_admin_notices() {
		settings_errors( );
	}

// ------------------------------------------------------------------------
// Setup the UI
// ------------------------------------------------------------------------	
	function mstw_tr_roster_data_fields_setup( ) {
		// Roster Table data fields/columns -- show/hide and labels
		$display_on_page = 'mstw_tr_fields_settings';
		$page_section = 'mstw_tr_fields_columns_settings';
		
		$options = get_option( 'mstw_tr_options' );
		
		
		add_settings_section(
			$page_section,  	//id attribute of tags
			__( 'Data Field and Table Column Settings', 'mstw-loc-domain' ),	//title of the section
			'mstw_tr_fields_columns_text',			//callback to fill section with desired output - should echo
			$display_on_page						//menu page slug on which to display
		);
		
		// Show/hide NUMBER column
		$args = array( 	'id' => 'show_number',
						'name'	=> 'mstw_tr_options[show_number]',
						'value'	=> $options['show_number'],
						'label'	=> __( 'Show or hide the Number field/column. (Default: Show)', 'mstw-loc-domain' )
						//'label' => 'show_number: ' . $options['show_number'] . '::'
						);
						
		add_settings_field(
			'tr_show_number',
			__( 'Show Number Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);		
			
		// NUMBER column label
		$args = array( 	'id' => 'number_label',
						'name'	=> 'mstw_tr_options[number_label]',
						'value'	=> $options['number_label'],
						'label'	=> __( 'Set Heading for Number data field or column. (Default: "Number")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_number_label',
			__( 'Number Column Label:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
				
		
		// NAME column label
		$args = array( 	'id' => 'name_label',
						'name'	=> 'mstw_tr_options[name_label]',
						'value'	=> $options['name_label'],
						'label'	=> __( 'Set Heading for Name data field or column. (Default: "Name")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_name_label',
			__( 'Name Column Label:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide POSITION column
		$args = array( 	'id' => 'show_position',
						'name'	=> 'mstw_tr_options[show_position]',
						'value'	=> $options['show_position'],
						'label'	=> __( 'Show or hide the Position field/column. (Default: Show)', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'tr_show_position',
			__( 'Show Position Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);		
			
		// POSITION column label
		$args = array( 	'id' => 'position_label',
						'name'	=> 'mstw_tr_options[position_label]',
						'value'	=> $options['position_label'],
						'label'	=> __( 'Set Heading for Position data field or column. (Default: "Pos")', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'tr_position_label',
			'Position Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
			
		// Show/hide HEIGHT column
		$args = array( 	'id' => 'show_height',
						'name'	=> 'mstw_tr_options[show_height]',
						'value'	=> $options['show_height'],
						'label'	=> 'Show or hide the Height field/column. (Default: Show)'
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
		add_settings_field(
			'tr_show_height',
			'Show Height Column:',
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// HEIGHT column label
		$args = array( 	'id' => 'height_label',
						'name'	=> 'mstw_tr_options[height_label]',
						'value'	=> $options['height_label'],
						'label'	=> __( 'Set Heading for Height data field or column. (Default: "Height")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_height_label',
			'Height Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
			
		// Show/hide WEIGHT column
		$args = array( 	'id' => 'show_weight',
						'name'	=> 'mstw_tr_options[show_weight]',
						'value'	=> $options['show_weight'],
						'label'	=> 'Show or hide the Weight field/column. (Default: Show)'
						);
						
		add_settings_field(
			'tr_show_weight',
			'Show Weight Column:',
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// WEIGHT column label
		$args = array( 	'id' => 'weight_label',
						'name'	=> 'mstw_tr_options[weight_label]',
						'value'	=> $options['weight_label'],
						'label'	=> __( 'Set Heading for Weight data field or column. (Default: "Weight")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_weight_label',
			'Weight Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide YEAR column
		$args = array( 	'id' => 'show_year',
						'name'	=> 'mstw_tr_options[show_year]',
						'value'	=> $options['show_year'],
						'label'	=> __( 'Show or hide the Year field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_year',
			__( 'Show Year Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// YEAR column label
		$args = array( 	'id' => 'year_label',
						'name'	=> 'mstw_tr_options[year_label]',
						'value'	=> $options['year_label'],
						'label'	=> __( 'Set Heading for Year data field or column. (Default: "Year")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_year_label',
			'Year Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
			
		// Show/hide EXPERIENCE column
		$args = array( 	'id' => 'show_experience',
						'name'	=> 'mstw_tr_options[show_experience]',
						'value'	=> $options['show_experience'],
						'label'	=> __( 'Show or hide the Experience field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_experience',
			__( 'Show Experience Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// EXPERIENCE column label
		$args = array( 	'id' => 'experience_label',
						'name'	=> 'mstw_tr_options[experience_label]',
						'value'	=> $options['experience_label'],
						'label'	=> __( 'Set Heading for Experience data field or column. (Default: "Exp")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_experience_label',
			'Experience Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide AGE column
		$args = array( 	'id' => 'show_age',
						'name'	=> 'mstw_tr_options[show_age]',
						'value'	=> $options['show_age'],
						'label'	=> __( 'Show or hide the Age field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_age',
			__( 'Show Age Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// AGE column label
		$args = array( 	'id' => 'age_label',
						'name'	=> 'mstw_tr_options[age_label]',
						'value'	=> $options['age_label'],
						'label'	=> __( 'Set Heading for Age data field or column. (Default: "Age")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_age_label',
			'Age Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide HOME TOWN column
		$args = array( 	'id' => 'show_home_town',
						'name'	=> 'mstw_tr_options[show_home_town]',
						'value'	=> $options['show_home_town'],
						'label'	=> __( 'Show or hide the Home Town field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_home_town',
			__( 'Show Home Town Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// HOME TOWN column label
		$args = array( 	'id' => 'home_town_label',
						'name'	=> 'mstw_tr_options[home_town_label]',
						'value'	=> $options['home_town_label'],
						'label'	=> __( 'Set Heading for Home Town data field or column. (Default: "Home Town")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_home_town_label',
			'Home Town Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide LAST SCHOOL column
		$args = array( 	'id' => 'show_last_school',
						'name'	=> 'mstw_tr_options[show_last_school]',
						'value'	=> $options['show_last_school'],
						'label'	=> __( 'Show or hide the Last School field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_last_school',
			__( 'Show Last School Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// LAST SCHOOL column label
		$args = array( 	'id' => 'last_school_label',
						'name'	=> 'mstw_tr_options[last_school_label]',
						'value'	=> $options['last_school_label'],
						'label'	=> __( 'Set Heading for Last School data field or column. (Default: "Last School")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_last_school_label',
			'Last School Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide COUNTRY column
		$args = array( 	'id' => 'show_country',
						'name'	=> 'mstw_tr_options[show_country]',
						'value'	=> $options['show_country'],
						'label'	=> __( 'Show or hide the Country field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_country',
			__( 'Show Country Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// COUNTRY column label
		$args = array( 	'id' => 'country_label',
						'name'	=> 'mstw_tr_options[country_label]',
						'value'	=> $options['country_label'],
						'label'	=> __( 'Set Heading for Country data field or column. (Default: "Country")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_country_label',
			'Country Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide BATS/THROWS column
		$args = array( 	'id' => 'show_bats_throws',
						'name'	=> 'mstw_tr_options[show_bats_throws]',
						'value'	=> $options['show_bats_throws'],
						'label'	=> __( 'Show or hide the Bats/Throws field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_bats_throws',
			__( 'Show Bats/Throws Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// BATS/THROWS column label
		$args = array( 	'id' => 'bats_throws_label',
						'name'	=> 'mstw_tr_options[bats_throws_label]',
						'value'	=> $options['bats_throws_label'],
						'label'	=> __( 'Set Heading for Bats/Throws data field or column. (Default: "Bat/Thw")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_bats_throws_label',
			'Bats/Throws Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Show/hide OTHER column
		$args = array( 	'id' => 'show_other_info',
						'name'	=> 'mstw_tr_options[show_other_info]',
						'value'	=> $options['show_other_info'],
						'label'	=> __( 'Show or hide the Other field/column. (Default: Hide)', 'mstw-loc-domain' )
						//'label' => 'show_height: ' . $options['show_height'] . '::'
						);
						
		add_settings_field(
			'tr_show_other_info',
			__( 'Show Other Column:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// OTHER column label
		$args = array( 	'id' => 'other_info_label',
						'name'	=> 'mstw_tr_options[other_info_label]',
						'value'	=> $options['other_info_label'],
						'label'	=> __( 'Set Heading for Other data field or column. (Default: "Other")', 'mstw-loc-domain' )
						//'label' => 'number_label: ' . $options['number_label'] . '::'
						);
						
		add_settings_field(
			'tr_other_info_label',
			'Other Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);

	}
	
	function mstw_tr_roster_table_setup( ) {
		// Roster Table Colors Section
		$display_on_page = 'mstw_tr_fields_settings';
		$page_section = 'mstw_tr_roster_color_settings';
		
		$options = get_option( 'mstw_tr_options' );
		
		add_settings_section(
			$page_section, 						//'mstw_tr_roster_color_settings'
			'Roster Table/[shortcode] Settings',		//title of the section
			'mstw_tr_roster_table_colors_text',	//callback to fill section with desired output - should echo
			$display_on_page 					//'mstw_tr_fields_settings'
		);
		
		// Show Roster Table title
		$args = array(	//'options' => array(	__( 'Show Title', 'mstw-loc-domain' ) => 1, 
						//					__( 'Hide Title', 'mstw-loc-domain' ) => 0, 
						//					),
						'id' => 'show_title',
						'name' => 'mstw_tr_options[show_title]',
						'value' => $options['show_title'],
						'label' => __( 'Show Roster Table Titles (as "Team Name Roster")', 'mstw-loc-domain')
						);
						
		add_settings_field(
			'show_title',
			__( 'Show Roster Table Titles:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',						//Callback to display field
			$display_on_page,							//Page to display field
			$page_section,					//Page section to display field
			$args												//Callback arguments
			);
		
		// Roster Table Format - custom, pro, college, high-school, + baseball-xxx
		$args = array(	'options' => array(	__( 'Custom', 'mstw-loc-domain' )=> 'custom', 
											__( 'Pro', 'mstw-loc-domain' ) => 'pro', 
											__( 'College', 'mstw-loc-domain' ) => 'college',
											__( 'High School', 'mstw-loc-domain' ) => 'high-school',
											__( 'Pro Baseball', 'mstw-loc-domain' ) => 'baseball-pro', 
											__( 'College Baseball', 'mstw-loc-domain' ) => 'baseball-college',
											__( 'High School Baseball', 'mstw-loc-domain' ) => 'baseball-high-school',
											),
						'id' => 'roster_type',
						'name' => 'mstw_tr_options[roster_type]',
						'value' => $options['roster_type'],
						'label' => __( 'Roster Table format. (Default: Custom)', 'mstw-loc-domain')
						);
		
		add_settings_field(
			'roster_type',										//ID attribute of tags
			__('Roster Table Format:', 'mstw-loc-domain' ),		//Title of field
			'mstw_utl_select_option_ctrl',							//Callback to display field
			$display_on_page,							//Page to display field
			$page_section,					//Page section to display field
			$args												//Callback arguments
		);
			
		// Roster Table SORT ORDER
		$args = array(	'options' => array(	__( 'Sort by Last Name', 'mstw-loc-domain' )=> 'alpha', 
											__( 'Sort by First Name', 'mstw-loc-domain' ) => 'alpha-first', 
											__( 'Sort by Number', 'mstw-loc-domain' ) => 'numeric'		
											),
						'id' => 'sort_order',
						'name' => 'mstw_tr_options[sort_order]',
						'value' => $options['sort_order'],
						'label' => __( 'Roster table sort order. (Default: Last Name)', 'mstw-loc-domain')
						);
		add_settings_field( 
			'sort_order',									//ID attribute of tags
			__( 'Sort Roster by:', 'mstw-loc-domain' ), 	//Title of field
			'mstw_utl_select_option_ctrl',						//Callback to display field
			$display_on_page,						//Page to display field
			$page_section,				//Page section to display field
			$args											//Callback arguments
		);
		
		// DISPLAY FORMAT for Player Names
		$args = array(	'options' => array(	__( 'Last, First', 'mstw-loc-domain' )=> 'last-first', 
											__( 'First Last', 'mstw-loc-domain' ) => 'first-last', 
											__( 'First Name Only', 'mstw-loc-domain' ) => 'first-only',
											__( 'Last Name Only', 'mstw-loc-domain' ) => 'last-only'		
											),
						'id' => 'name_format',
						'name' => 'mstw_tr_options[name_format]',
						'value' => $options['name_format'],
						'label' => __( 'Select display format for Player Name. (Default: Last, First)', 'mstw-loc-domain')
						//'label' => 'name_format: ' . $options['name_format'] . '::'
						);
		add_settings_field( 
			'name_format',									//ID attribute of tags
			__( 'Display Players by:', 'mstw-loc-domain' ), 	//Title of field
			'mstw_utl_select_option_ctrl',						//Callback to display field
			$display_on_page,						//Page to display field
			$page_section,				//Page section to display field
			$args											//Callback arguments
		);
		
		// Show/hide PHOTO
		$args = array( 	'id' => 'show_photos',
						'name'	=> 'mstw_tr_options[show_photos]',
						'value'	=> $options['show_photos'],
						'label'	=> __( 'Shows photos in roster tables. (Default: Hide)', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'show_photos',
			__( 'Show Player Photos:', 'mstw-loc-domain' ),
			'mstw_utl_show_hide_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// PHOTO label
		$args = array( 	'id' => 'photo_label',
						'name'	=> 'mstw_tr_options[photo_label]',
						'value'	=> $options['photo_label'],
						'label'	=> __( '(Default: "Photo")', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'photo_label',
			'Photo Column Label:',
			'mstw_utl_text_ctrl',
			$display_on_page,
			$page_section,
			$args
		);
		
		// Players' PHOTOS WIDTH
		$args = array( 	'id' => 'table_photo_width',
						'name'	=> 'mstw_tr_options[table_photo_width]',
						'value'	=> $options['table_photo_width'],
						'label'	=> __( 'Set width in pixels for table photos, if shown. (Default: 64px)', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'table_photo_width',
			__( 'Table Photo Width:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,					//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Players' PHOTOS WIDTH
		$args = array( 	'id' => 'table_photo_height',
						'name'	=> 'mstw_tr_options[table_photo_height]',
						'value'	=> $options['table_photo_height'],
						'label'	=> __( 'Set height in pixels for table photos, if shown. (Default: 64px)', 'mstw-loc-domain' )
						);
						
		add_settings_field(
			'table_photo_height',
			__( 'Table Photo Height:', 'mstw-loc-domain' ),
			'mstw_utl_text_ctrl',
			$display_on_page,					//Page to display field
			$page_section, 					//Page section to display field
			$args
		);

		// Roster Table Title Color
		$args = array( 	'id' => 'tr_table_title_text_color',
						'name' => 'mstw_tr_options[tr_table_title_text_color]',
						'value' => $options['tr_table_title_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_title_text_color',
			__( 'Table Title Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Link text color. (Hover effect is underline by default. Use stylesheet to customize.)
		$args = array( 	'id' => 'tr_table_links_color',
						'name' => 'mstw_tr_options[tr_table_links_color]',
						'value' => $options['tr_table_links_color'],
						'label' => '(Hover effect is underline by default. Use stylesheet to customize.)'
					 );
					 
		add_settings_field(
			'tr_table_links_color',
			__( 'Table Links Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Roster Table Header Background Color
		$args = array( 	'id' => 'tr_table_head_bkgd_color',
						'name' => 'mstw_tr_options[tr_table_head_bkgd_color]',
						'value' => $options['tr_table_head_bkgd_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_head_bkgd_color',
			__( 'Table Header Background Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Roster Table Header Text Color
		$args = array( 	'id' => 'tr_table_head_text_color',
						'name' => 'mstw_tr_options[tr_table_head_text_color]',
						'value' => $options['tr_table_head_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_head_text_color',
			__( 'Table Header Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Roster Table Even Row Text Color
		$args = array( 	'id' => 'tr_table_even_row_color',
						'name' => 'mstw_tr_options[tr_table_even_row_color]',
						'value' => $options['tr_table_even_row_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_even_row_color',
			__( 'Table Even Row Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Roster Table Even Row Background Color
		$args = array( 	'id' => 'tr_table_even_row_bkgd',
						'name' => 'mstw_tr_options[tr_table_even_row_bkgd]',
						'value' => $options['tr_table_even_row_bkgd'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_even_row_bkgd',
			__( 'Table Even Row Background Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
		
		// Roster Table Odd Row Text Color
		$args = array( 	'id' => 'tr_table_odd_row_color',
						'name' => 'mstw_tr_options[tr_table_odd_row_color]',
						'value' => $options['tr_table_odd_row_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_odd_row_color',
			__( 'Table Odd Row Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
			
		// Roster Table Odd Row Background Color
		$args = array( 	'id' => 'tr_table_odd_row_bkgd',
						'name' => 'mstw_tr_options[tr_table_odd_row_bkgd]',
						'value' => $options['tr_table_odd_row_bkgd'],
						'label' => ''
					 );
					 
		add_settings_field(
			'tr_table_odd_row_bkgd',
			__( 'Table Odd Row Background Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
	}
	
	// Roster Table Colors section instructions
	function mstw_tr_roster_table_colors_text( ) {
		echo '<p>' . __( 'Enter the default team roster table settings. Note that these settings will apply to all the [shortcode] roster tables, overriding the default styles. However they can be overridden by more specific stylesheet rules for specific teams.', 'mstw-loc-domain' ) . '</p>';
	}
	
	// setup the single player bio page
	function mstw_tr_single_player_bio_setup( ) {
		$display_on_page = 'mstw_tr_fields_settings';
		$page_section = 'mstw_tr_single_settings';
		
		$options = get_option( 'mstw_tr_options' );
		
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
			'mstw_utl_text_ctrl',
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
			'mstw_utl_text_ctrl',
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
			'mstw_utl_text_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);
	
		
		// Background color of main box
		$args = array( 	'id' => 'sp_main_bkgd_color',
						'name' => 'mstw_tr_options[sp_main_bkgd_color]',
						'value' => $options['sp_main_bkgd_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_main_bkgd_color',
			__( 'Main Box Background Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Text color of main box
		$args = array( 	'id' => 'sp_main_text_color',
						'name' => 'mstw_tr_options[sp_main_text_color]',
						'value' => $options['sp_main_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_main_text_color',
			__( 'Main Box Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Border color of player profile box
		$args = array( 	'id' => 'sp_bio_border_color',
						'name' => 'mstw_tr_options[sp_bio_border_color]',
						'value' => $options['sp_bio_border_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_border_color',
			__( 'Player Profile(Bio) Border Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Header text color of player bio box
		$args = array( 	'id' => 'sp_bio_header_color',
						'name' => 'mstw_tr_options[sp_bio_header_color]',
						'value' => $options['sp_bio_header_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_header_color',
			__( 'Player Bio Header Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Text color of player bio box
		$args = array( 	'id' => 'sp_bio_text_color',
						'name' => 'mstw_tr_options[sp_bio_text_color]',
						'value' => $options['sp_bio_text_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_text_color',
			__( 'Player Bio Text Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Background color of player bio box
		$args = array( 	'id' => 'sp_bio_bkgd_color',
						'name' => 'mstw_tr_options[sp_bio_bkgd_color]',
						'value' => $options['sp_bio_bkgd_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'sp_bio_bkgd_color',
			__( 'Player Bio Background Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
			$display_on_page,				//Page to display field
			$page_section, 					//Page section to display field
			$args
		);	
		
		// Gallery links color
		$args = array( 	'id' => 'gallery_links_color',
						'name' => 'mstw_tr_options[gallery_links_color]',
						'value' => $options['gallery_links_color'],
						'label' => ''
					 );
					 
		add_settings_field(
			'gallery_links_color',
			__( 'Player Gallery Links Color:', 'mstw-loc-domain' ),
			'mstw_utl_color_ctrl',
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
								'taxonomy'           => 'teams',
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
		$term = get_term_by( 'id', $opt_cat, 'teams' );
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
		$type = 'player';
		
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
		$term = get_term_by( 'id', $opt_cat, 'teams' );

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
			$term = get_term_by( 'id', $opt_cat, 'teams' );
			wp_set_object_terms( $id, $term->slug, 'teams');
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
					case __( "first name", "mstw-loc-domain" ):
					case __( "first", "mstw-loc-domain" ):
						$k = '_mstw_tr_first_name';
						break;
					case __( "last name", "mstw-loc-domain" ):
					case __( "last", "mstw-loc-domain" ):
						$k = '_mstw_tr_last_name';
						break;
					case __( "position", "mstw-loc-domain" ):
					case __( "pos", "mstw-loc-domain" ):
						$k = '_mstw_tr_position';
						break;
					case __( "number", "mstw-loc-domain" ):
					case __( "nbr", "mstw-loc-domain" ):
					case __( "#", "mstw-loc-domain" ):
						$k = '_mstw_tr_number';
						break;
					case __( "weight", "mstw-loc-domain" ):
					case __( "wt", "mstw-loc-domain" ):
						$k = '_mstw_tr_weight';
						break;
					case __( "height", "mstw-loc-domain" ):
					case __( "ht", "mstw-loc-domain" ):
						$k = '_mstw_tr_height';
						break;
					case __( "age", "mstw-loc-domain" ):
						$k = '_mstw_tr_age';
						break;
					case __( "year", "mstw-loc-domain" ):
					case __( "yr", "mstw-loc-domain" ):
						$k = '_mstw_tr_year';
						break;
					case __( "experience", "mstw-loc-domain" ):
					case __( "exp", "mstw-loc-domain" ):
						$k = '_mstw_tr_experience';
						break;
					case __( "home town", "mstw-loc-domain" ):
						$k = '_mstw_tr_home_town';
						break;
					case __( "country", "mstw-loc-domain" ):
						$k = '_mstw_tr_country';
						break;
					case __( "last school", "mstw-loc-domain" ):
						$k = '_mstw_tr_last_school';
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