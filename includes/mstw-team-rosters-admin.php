<?php
/*
 *	This is the admin portion of the MSTW Team Rosters Plugin
 *	It is loaded conditioned on is_admin() 
 */

/*  Copyright 2012  Mark O'Donnell  (email : mark@shoalsummitsolutions.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*-------------------------------------------------------------------------------------
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
 *-------------------------------------------------------------------------------------*/

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
// Load the mstw_utility_functions if necessary
	if ( !function_exists( 'mstw_sanitize_hex_color' ) ) {
		require_once 'mstw_utility_functions.php';
	}

// ----------------------------------------------------------------
/* Make sure the Feature Image meta is active for the player custom post type

	add_action( 'after_setup_theme', 'mstw_tr_add_feat_img' );
	
	function mstw_tr_add_feat_img( ) {
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails', array( 'player' ) );
		}
	}
*/

// ----------------------------------------------------------------
// Create the meta box for the Team Roster custom post type

	add_action( 'add_meta_boxes', 'mstw_tr_add_meta' );

	function mstw_tr_add_meta () {
		
		add_meta_box(	'mstw-tr-meta', 
						__('Player', 'mstw-loc-domain'), 
						'mstw_tr_create_ui', 
						'player', 
						'normal', 
						'high' );		
	}

// --------------------------------------------------------------------------------------
// Create the UI form for entering a Team Roster in the Admin page

	function mstw_tr_create_ui( $post ) {
									  
		$mstw_tr_bats_list = array( '', 'R', 'L', 'B' );
		$mstw_tr_throws_list = array( '', 'R', 'L' );
		
		// Retrieve the metadata values if they exist
		// The first set are used in all formats
		$mstw_tr_first_name = get_post_meta( $post->ID, '_mstw_tr_first_name', true );
		
		$mstw_tr_last_name  = get_post_meta( $post->ID, '_mstw_tr_last_name', true );
		
		$mstw_tr_number = get_post_meta( $post->ID, '_mstw_tr_number', true );
		
		$mstw_tr_height = get_post_meta( $post->ID, '_mstw_tr_height', true );
		
		$mstw_tr_weight = get_post_meta( $post->ID, '_mstw_tr_weight', true );
		
		$mstw_tr_position = get_post_meta( $post->ID, '_mstw_tr_position', true );
		
		// year is used in the high-school and college formats
		$mstw_tr_year = get_post_meta( $post->ID, '_mstw_tr_year', true );
		
		// experience is used in the college and pro formats
		$mstw_tr_experience = get_post_meta( $post->ID, '_mstw_tr_experience', true );
		
		// age is used in the pro format only
		$mstw_tr_age = get_post_meta( $post->ID, '_mstw_tr_age', true );
		
		// home_town is used in the college format only
		$mstw_tr_home_town = get_post_meta( $post->ID, '_mstw_tr_home_town', true );
		
		// last_school is used in the college and pro formats
		$mstw_tr_last_school = get_post_meta( $post->ID, '_mstw_tr_last_school', true );
		
		// country is used in the pro format only
		$mstw_tr_country = get_post_meta( $post->ID, '_mstw_tr_country', true );
		
		// bats is used in the baseball formats only
		$mstw_tr_bats = get_post_meta( $post->ID, '_mstw_tr_bats', true );
		
		// throws is used in the baseball formats only
		$mstw_tr_throws = get_post_meta( $post->ID, '_mstw_tr_throws', true );
		
		// other info is not currently used
		$mstw_tr_other_info = get_post_meta( $post->ID, '_mstw_tr_other_info', true );
		   
		?>	
		
	   <table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_first_name" ><?php echo( __( 'First Name', 'mstw-loc-domain' ) . ':' ); ?> </label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_first_name"
				value="<?php echo esc_attr( $mstw_tr_first_name ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_last_name" ><?php echo( __( 'Last Name', 'mstw-loc-domain' ) . ':' ); ?> </label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_last_name"
				value="<?php echo esc_attr( $mstw_tr_last_name ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_number" >Number:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_number"
				value="<?php echo esc_attr( $mstw_tr_number ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_position" >Position:</label></th>
			<td><input maxlength="32" size="8" name="mstw_tr_position"
        	value="<?php echo esc_attr( $mstw_tr_position ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_height" >Height:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_height" 
				value="<?php echo esc_attr( $mstw_tr_height ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_weight" >Weight:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_weight" 
				value="<?php echo esc_attr( $mstw_tr_weight ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_year" >Year:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_year"
        	value="<?php echo esc_attr( $mstw_tr_year ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_experience" >Experience:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_experience"
        	value="<?php echo esc_attr( $mstw_tr_experience ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_age" >Age:</label></th>
			<td><input maxlength="8" size="8" name="mstw_tr_age"
        	value="<?php echo esc_attr( $mstw_tr_age ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_home_town" >Home Town:</label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_home_town"
        	value="<?php echo esc_attr( $mstw_tr_home_town ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_last_school" >Last School:</label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_last_school"
        	value="<?php echo esc_attr( $mstw_tr_last_school ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_country" >Country:</label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_country"
        	value="<?php echo esc_attr( $mstw_tr_country ); ?>"/></td>
		</tr>
		<tr valign="top">
    	<th scope="row"><label for="mstw_tr_bats" >Bats:</label></th>
        <td>
        <select name="mstw_tr_bats">    
			<?php foreach ( $mstw_tr_bats_list as $label ) {  ?>
          			<option value="<?php echo $label; ?>" <?php selected( $mstw_tr_bats, $label );?>>
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
			<?php foreach ( $mstw_tr_throws_list as $label ) {  ?>
          			<option value="<?php echo $label; ?>" <?php selected( $mstw_tr_throws, $label );?>>
          				<?php echo $label; ?>
                     </option>              
     		<?php } ?> 
        </select>   
        </td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="mstw_tr_other_info" >Other Info:</label></th>
			<td><input maxlength="32" size="20" name="mstw_tr_other_info"
        	value="<?php echo esc_attr( $mstw_tr_other_info ); ?>"/></td>
		</tr>
		
    </table>
    
<?php        	
}

// --------------------------------------------------------------------------------------
// Save the Team Roster Meta Data

add_action( 'save_post', 'mstw_tr_save_meta' );

function mstw_tr_save_meta( $post_id ) {
	
	global $mstw_tr_msg_str;
	global $mstw_tr_debug_str;
	
	//First verify the required metadata is set and valid. If not, set default or return error
	
	// Need error checking here!!
	
	// Okay, we should be good to update the database
			
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
	
	$age_update = strip_tags( $_POST['mstw_tr_age'] );
	if ( $age_update != 'Deceased' ) { //age can be "Deceased" or 0<age<101
		$age_update = intval( $age_update );
		if ( $age_update <= 0 ) {
			$age_update = '';
		}
		else if ( $age_update > 101 ) {
			$age_update = 100;
		}
	}
	update_post_meta( $post_id, '_mstw_tr_age', $age_update );
			//strip_tags( $_POST['mstw_tr_age'] ) );
			
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
	
}

// --------------------------------------------------------------------------------------
// Set up the Team Roster 'view all' columns

add_filter( 'manage_edit-player_columns', 'mstw_tr_edit_columns' ) ;

function mstw_tr_edit_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'mstw-loc-domain' ),
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

// --------------------------------------------------------------------------------------
// Display the Team Roster 'view all' columns

add_action( 'manage_player_posts_custom_column', 'mstw_tr_manage_columns', 10, 2 );

function mstw_tr_manage_columns( $column, $post_id ) {
	global $post;
	
	/* echo 'column: ' . $column . " Post ID: " . $post_id; */

	switch( $column ) {
	
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

// ----------------------------------------------------------------
// Remove Quick Edit Menu	
add_filter( 'post_row_actions', 'mstw_tr_remove_quick_edit', 10, 2 );

function mstw_tr_remove_quick_edit( $actions, $post ) {
    if( $post->post_type == 'player' ) {
        unset( $actions['inline hide-if-no-js'] );
    }
    return $actions;
}

// --------------------------------------------------------------------------------------
// Add a menu for our option page
add_action('admin_menu', 'mstw_tr_add_page');

function mstw_tr_add_page( ) {
	//The next line adds the settings page to the Settings menu
	//add_options_page( 'Team Rosters Settings', 'Team Rosters Settings', 'manage_options', 'mstw_tr_settings', 'mstw_tr_option_page' );
	
	// But I decided to add the settings page to the Players menu
	$page = add_submenu_page( 	'edit.php?post_type=player', 
						'Team Rosters Settings', 		//page title
						'Display Settings', 			//menu title
						'manage_options', 
						'mstw_tr_settings', 
						'mstw_tr_option_page' );
						

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
	// add_action( 'admin_print_styles-' . $page, mstw_tr_load_scripts );
}

// --------------------------------------------------------------------------------------
// Load Java scripts for the color picker
function mstw_tr_load_scripts() {
	// js coming later
	wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
	//wp_enqueue_script ( handle
    wp_enqueue_script(	'mstw-theme-color', 
						WP_PLUGIN_DIR . '/team-rosters/js/mstw-theme-color.js', 
						array( 'farbtastic', 'jquery' ) 
						);
}

// ----------------------------------------------------------------
// Render the option page
// ----------------------------------------------------------------
function mstw_tr_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Team Rosters Plugin Settings</h2>
		<?php //settings_errors(); ?>
		<form action="options.php" method="post">
			<?php settings_fields( 'mstw_tr_options' ); ?>
			<?php do_settings_sections( 'mstw_tr_settings' ); ?>
			<p>
			<input name="Submit" type="submit" class="button-primary" value="Save Changes" />
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
	register_setting(
		'mstw_tr_options',
		'mstw_tr_options',
		'mstw_tr_validate_options'
	);
	
	// Single Player Section
	add_settings_section(
		'mstw_tr_single_page_settings',
		'Single Player Page Settings',
		'mstw_tr_single_player_text',
		'mstw_tr_settings'
	);
	
	// Show/hide player weight (for girls teams)
		add_settings_field(
		'mstw_tr_hide_weight',
		'Hide player weight:',
		'mstw_tr_hide_weight_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Title for the content (E.g., "Player Bio")
	add_settings_field(
		'mstw_tr_sp_content_title',
		'Player content title text:',
		'mstw_tr_sp_content_title_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Player Photo (thumbnail) width
	add_settings_field(
		'mstw_tr_sp_image_width',
		'Player photo width:',
		'mstw_tr_sp_image_width_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Player Photo (thumbnail) height
	add_settings_field(
		'mstw_tr_sp_image_height',
		'Player photo height:',
		'mstw_tr_sp_image_height_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Background color of main box
	add_settings_field(
		'mstw_tr_sp_main_bkgd_color',
		'Main box background color [#hex]:',
		'mstw_tr_sp_main_bkgd_color_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	/* Testing color widget
	add_settings_field(
		'mstw_tr_color_test',
		'Color Test: ',
		'mstw_color_test_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);*/
	
	// Text color of main box
	add_settings_field(
		'mstw_tr_sp_main_text_color',
		'Main box text color [#hex]:',
		'mstw_tr_sp_main_text_color_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Roster Table Section
	add_settings_section(
		'mstw_tr_roster_table_settings',
		'Roster Table Settings',
		'mstw_tr_roster_table_text',
		'mstw_tr_settings'
	);
	
	// Sort Roster Table numerically or alphabetically
	add_settings_field(
		'mstw_tr_table_sort_order',
		'Sort alphabetically or by number:',
		'mstw_tr_table_sort_order_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Title Color
	add_settings_field(
		'mstw_tr_table_title_text_color',
		'Table Title Text Color [#hex]:',
		'mstw_tr_table_title_text_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Player name format - "Last, First" or "First Last"
	add_settings_field(
		'mstw_tr_player_name_format',
		'Player Name Format:',
		'mstw_tr_player_name_format_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Table Headings Format - pro, college, high-school
	add_settings_field(
		'mstw_tr_table_default_format',
		'Default Table Format:',
		'mstw_tr_table_default_format_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Add the links from the roster to single player pages
	add_settings_field(
		'mstw_tr_table_player_links',
		'Add links to player bios:',
		'mstw_tr_table_player_links_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Link text color. (Hover effect is underline by default. Use stylesheet to customize.)
	add_settings_field(
		'mstw_tr_table_links_color',
		'Link text color [#hex]:',
		'mstw_tr_table_links_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Header Background Color
	add_settings_field(
		'mstw_tr_table_head_bkgd_color',
		'Table Header Background Color [#hex]:',
		'mstw_tr_table_head_bkgd_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Header Text Color
	add_settings_field(
		'mstw_tr_table_head_text_color',
		'Table Header Text Color [#hex]:',
		'mstw_tr_table_head_text_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Even Row Text Color
	add_settings_field(
		'mstw_tr_table_even_row_color',
		'Table Even Row Text Color [#hex]:',
		'mstw_tr_table_even_row_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Even Row Background Color
	add_settings_field(
		'mstw_tr_table_even_row_bkgd',
		'Table Even Row Background Color [#hex]:',
		'mstw_tr_table_even_row_bkgd_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Odd Row Text Color
	add_settings_field(
		'mstw_tr_table_odd_row_color',
		'Table Odd Row Text Color [#hex]:',
		'mstw_tr_table_odd_row_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Odd Row Background Color
	add_settings_field(
		'mstw_tr_table_odd_row_bkgd',
		'Table Odd Row Background Color [#hex]:',
		'mstw_tr_table_odd_row_bkgd_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Taxonomy/Player Gallery Section
	add_settings_section(
		'mstw_tr_gallery_page_settings',
		'Player Gallery Page Settings',
		'mstw_tr_gallery_page_text',
		'mstw_tr_settings'
	);
	
	// Sort player gallery numerically or alphabetically
	add_settings_field(
		'mstw_tr_pg_sort_order',
		'Sort alphabetically or by number:',
		'mstw_tr_pg_sort_order_input',
		'mstw_tr_settings',
		'mstw_tr_gallery_page_settings'
	);
	
	// Add the links from the player gallery to single player pages
	add_settings_field(
		'mstw_pg_use_player_links', 		/* "id" attribute of tags */
		'Add links to player bios:',		/* title of the field */
		'mstw_tr_pg_links_input',			/* callback that displays the field */
		'mstw_tr_settings',					/* page on which to display the field */
		'mstw_tr_gallery_page_settings'		/* section in which to show the field */
	);
	
}

// Single player section instructions
function mstw_tr_single_player_text() {
	echo '<p>' . __( 'Enter your single player page settings. ', 'mstw-loc-domain' ) . '<br/>' . __( 'All color values are in hex, starting with a hash(#), followed by either 3 or 6 hex digits. For example, #123abd or #1a2.', 'mstw-loc-domain' ) .  '</p>';
	//echo '<p>' . WP_PLUGIN_DIR . '/team-rosters/js/mstw-theme-color.js' .'</p>';
}

// Roster table section instructions
function mstw_tr_roster_table_text() {
	echo '<p>' . __( 'Enter your team roster table settings.', 'mstw-loc-domain' ) . '<br/>' . __( 'All color values are in hex, starting with a hash(#), followed by either 3 or 6 hex digits. For example, #123abd or #1a2.', 'mstw-loc-domain' ) .  '</p>';
}

// Roster table section instructions
function mstw_tr_gallery_page_text() {
	echo '<p>' . __( 'Enter your gallery page settings.', 'mstw-loc-domain' ) . '<br/>' . __( 'All color values are in hex, starting with a hash(#), followed by either 3 or 6 hex digits. For example, #123abd or #1a2.', 'mstw-loc-domain' ) .  '</p>';
}

/*--------------------------------------------------------------
 *	Input fields for single player page section
 */
 
function mstw_tr_hide_weight_input() {
	// get option 'tr_hide_weight' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_hide_weight = $options['tr_hide_weight'];
	
	// echo the field
	$html = '<input type="checkbox" 
					id="hide_weight" 
					name="mstw_tr_options[tr_hide_weight]" 
					value="hide-weight" ' . checked( "hide-weight", $options['tr_hide_weight'], false ) . '/>';  
    $html .= "<label for='hide_weight'> Check to hide player weight in ALL roster tables.</label></p>";
	
    echo $html;  
}   
 
function mstw_tr_sp_content_title_input() {
	// get option 'sp_content_title' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_content_title = $options['sp_content_title'];
	// echo the field
	echo "<input id='sp_content_title' name='mstw_tr_options[sp_content_title]' type='text' value='$sp_content_title' />  (defaults to \"Player Bio\")";
}

function mstw_tr_sp_image_width_input() {
	// get option 'sp_image_width' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_image_width = $options['sp_image_width'];
	// echo the field
	echo "<input id='sp_image_width' name='mstw_tr_options[sp_image_width]' type='text' value='$sp_image_width' />  (in px defaults to 150)";
}

function mstw_tr_sp_image_height_input() {
	// get option 'sp_image_height' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_image_height = $options['sp_image_height'];
	// echo the field
	echo "<input id='sp_image_height' name='mstw_tr_options[sp_image_height]' type='text' value='$sp_image_height' /> (in px defaults to 150)";
}
 
function mstw_tr_sp_main_bkgd_color_input() {
	// get option 'sp_main_bkgd_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	// echo the field
	echo "<input id='sp_main_bkgd_color' name='mstw_tr_options[sp_main_bkgd_color]' type='text' value='$sp_main_bkgd_color' />";
}

function mstw_tr_sp_main_text_color_input() {
	// get option 'sp_main_text_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_main_text_color = $options['sp_main_text_color'];
	// echo the field
	echo "<input id='sp_main_text_color' name='mstw_tr_options[sp_main_text_color]' type='text' value='$sp_main_text_color' />";
}

/*function mstw_color_test_input() {
	// get option 'sp_test_color' from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_test_color = $options['sp_test_color'];
	echo "<input type='text' name='mstw_tr_options[sp_test_color]' value='$sp_test_color' />";
}*/

/*--------------------------------------------------------------
 *	Input fields for roster table section
 */

function mstw_tr_player_name_format_input() {
	// get option 'tr_player_name_format' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_player_name_format = $options['tr_player_name_format'];
	
	// echo the field
	$html = '<input type="checkbox" 
					id="player-name-format" 
					name="mstw_tr_options[tr_player_name_format]" 
					value="first-last" ' . checked( "first-last", $options['tr_player_name_format'], false ) . '/>';  
    $html .= "<label for='player-name-format'> Check for 'FirstName LastName'</label></p>";
	
    echo $html;  
}  

function mstw_tr_table_player_links_input() {
	// get option 'tr_use_player_links' value from the database
	$options = get_option( 'mstw_tr_options' );
	
	// echo the field
	$html = '<input type="checkbox" 
					id="use-player-links" 
					name="mstw_tr_options[tr_use_player_links]" 
					value= "show-links" ' . checked( "show-links", $options['tr_use_player_links'], false ) . '/>';  
    $html .= "<label for='use-player-links'> Check to add links from player names to bio pages on shortcode roster table </label></p>";
	
    echo $html;  
}  

function mstw_tr_table_links_color_input() {
	// get option 'tr_table_links_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_links_color = $options['tr_table_links_color'];
	// echo the field
	echo "<input id='tr_table_links_color' name='mstw_tr_options[tr_table_links_color]' type='text' value='$tr_table_links_color' />";
}

function mstw_tr_table_sort_order_input() {
	// get option 'tr_table_sort_order' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_sort_order = $options['tr_table_sort_order'];
	
	// echo the field
    $html = "<p><input type='radio' id='sort-alpha' 
				name='mstw_tr_options[tr_table_sort_order]' value='alpha'" . 
				checked( "alpha", $options['tr_table_sort_order'], false ) . '/>';  
    $html .= "<label for='sort-alpha'> Sort alphabetically</label></p>";
	
    $html .= "<p><input type='radio' id='sort-numeric' 
				name='mstw_tr_options[tr_table_sort_order]' value='numeric'" . 
				checked( "numeric", $options['tr_table_sort_order'], false ) . '/>';  
    $html .= "<label for='sort-numeric'> Sort numerically</label></p>";
	
    echo $html;  
} 

 
function mstw_tr_table_default_format_input() {
	// get option 'tr_table_default_format' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_default_format = $options['tr_table_default_format'];
	
	// echo the field
    $html = "<p><input type='radio' id='high-school-format' 
				name='mstw_tr_options[tr_table_default_format]' value='high-school'" . 
				checked( "high-school", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='high-school-format'> High School Format</label></p>";
	
    $html .= "<p><input type='radio' id='college-format' 
				name='mstw_tr_options[tr_table_default_format]' value='college'" . 
				checked( "college", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='college-format'> College Format</label></p>";

	$html .= "<p><input type='radio' id='pro-format' 
				name='mstw_tr_options[tr_table_default_format]' value='pro'" . 
				checked( "pro", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='pro-format'> Pro Format</label></p>";

	$html .= "<p><input type='radio' id='hs-baseball-format' 
				name='mstw_tr_options[tr_table_default_format]' value='hs-baseball'" . 
				checked( "hs-baseball", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='hs-baseball-format'> High School Baseball Format</label></p>";
	
	$html .= "<p><input type='radio' id='coll-baseball-format' 
				name='mstw_tr_options[tr_table_default_format]' value='coll-baseball'" . 
				checked( "coll-baseball", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='coll-baseball-format'> College Baseball Format</label></p>";
	
	$html .= "<p><input type='radio' id='pro-baseball-format' 
				name='mstw_tr_options[tr_table_default_format]' value='pro-baseball'" . 
				checked( "pro-baseball", $options['tr_table_default_format'], false ) . '/>';  
    $html .= "<label for='pro-baseball-format'> Pro Baseball Format</label></p>";
	
    echo $html;  
} 
 
function mstw_tr_table_head_bkgd_color_input() {
	// get option 'tr_table_head_bkgd_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_head_bkgd_color = $options['tr_table_head_bkgd_color'];
	// echo the field
	echo "<input id='tr_table_head_bkgd_color' name='mstw_tr_options[tr_table_head_bkgd_color]' type='text' value='$tr_table_head_bkgd_color' />";
}

function mstw_tr_table_head_text_color_input() {
	// get option 'tr_table_head_text_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_head_text_color = $options['tr_table_head_text_color'];
	// echo the field
	echo "<input id='tr_table_head_text_color' name='mstw_tr_options[tr_table_head_text_color]' type='text' value='$tr_table_head_text_color' />";
}

function mstw_tr_table_title_text_color_input() {
	// get option 'tr_table_title_text_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_title_text_color = $options['tr_table_title_text_color'];
	// echo the field
	echo "<input id='tr_table_title_text_color' name='mstw_tr_options[tr_table_title_text_color]' type='text' value='$tr_table_title_text_color' />";
}

function mstw_tr_table_even_row_color_input() {
	// get option 'tr_table_even_row_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_even_row_color = $options['tr_table_even_row_color'];
	// echo the field
	echo "<input id='tr_table_even_row_color' name='mstw_tr_options[tr_table_even_row_color]' type='text' value='$tr_table_even_row_color' />";
}

function mstw_tr_table_even_row_bkgd_input() {
	// get option 'tr_table_even_row_bkgd' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_even_row_bkgd = $options['tr_table_even_row_bkgd'];
	// echo the field
	echo "<input id='tr_table_even_row_bkgd' name='mstw_tr_options[tr_table_even_row_bkgd]' type='text' value='$tr_table_even_row_bkgd' />";
}

function mstw_tr_table_odd_row_color_input() {
	// get option 'tr_table_odd_row_color' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_odd_row_color = $options['tr_table_odd_row_color'];
	// echo the field
	echo "<input id='tr_table_odd_row_color' name='mstw_tr_options[tr_table_odd_row_color]' type='text' value='$tr_table_odd_row_color' />";
}

function mstw_tr_table_odd_row_bkgd_input() {
	// get option 'tr_table_odd_row_bkgd' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_table_odd_row_bkgd = $options['tr_table_odd_row_bkgd'];
	// echo the field
	echo "<input id='tr_table_odd_row_bkgd' name='mstw_tr_options[tr_table_odd_row_bkgd]' type='text' value='$tr_table_odd_row_bkgd' />";
}

/*--------------------------------------------------------------
 *	Input fields for player gallery section
 */
 
function mstw_tr_pg_sort_order_input() {
	// get option 'tr_pg_sort_order' value from the database
	$options = get_option( 'mstw_tr_options' );
	$tr_pg_sort_order = $options['tr_pg_sort_order'];
	
	// echo the field
    $html = "<p><input type='radio' id='sort-alpha' 
				name='mstw_tr_options[tr_pg_sort_order]' value='alpha'" . 
				checked( "alpha", $options['tr_pg_sort_order'], false ) . '/>';  
    $html .= "<label for='sort-alpa'> Sort alphabetically</label></p>";
	
    $html .= "<p><input type='radio' id='sort-numeric' 
				name='mstw_tr_options[tr_pg_sort_order]' value='numeric'" . 
				checked( "numeric", $options['tr_pg_sort_order'], false ) . '/>';  
    $html .= "<label for='sort-numeric'> Sort numerically</label></p>";
	
    echo $html;  
} 
 
function mstw_tr_pg_links_input() {
	// get option 'pg_use_player_links' value from the database
	$options = get_option( 'mstw_tr_options' );
	$pg_use_player_links = $options['pg_use_player_links'];
	
	// echo the field
	$html = '<input type="checkbox" 
					id="use-pg-links" 
					name="mstw_tr_options[pg_use_player_links]" 
					value= "show-pg-links" ' . checked( "show-pg-links", $options['pg_use_player_links'], false ) . '/>';  
    $html .= "<label for='use-pg-links'> Check to add links from player names to bio pages on taxonomy page</label></p>";
	
    echo $html;  
}  

/*--------------------------------------------------------------
 *	Validate user input (we want text only)
 */
 
function mstw_tr_validate_options( $input ) {
	// Create our array for storing the validated options
	$output = array();
	// Pull the previous (good) options
	$options = get_option( 'mstw_tr_options' );
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
					
					// validate the color for proper hex format
					$sanitized_color = mstw_sanitize_hex_color( $input[$key] );
					
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
					
				// Check all other settings
				default:
					//case 'tr_hide_weight':
					//case 'tr_player_name_format':
					//case 'tr_use_player_links':
					//case 'tr_table_default_format':
					//case 'sp_content_title':
					$output[$key] = sanitize_text_field( $input[$key] );
					// There should not be user/accidental errors in these fields
					break;
				
			} // end switch
		} // end if
	} // end foreach
	
	// Store the .css file
	// first check to see if the user wants to use the settings

	/*$use_settings = false;
	if ( $use_settings ) {
		if ( $built = mstw_tr_build_styles( $output ) ) {
			//echo '<p> We built the stylesheet </p>';
			add_settings_error( 'mstw_tr_css',
								'mstw_tr_css_error',
								'Built stylesheet = ' . $built,
								'error' );
		}
	}*/
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'sandbox_theme_validate_input_examples', $output, $input );
	//return $output;
}

// Build and save the stylesheet from the settings
// $settings is the output array of valid theme settings
/*function mstw_tr_build_styles( $settings ) {
	//echo '<p> We be building the stylesheet </p>';
	$ss_dir = WP_PLUGIN_DIR . '/team-rosters/css'; //get_stylesheet_directory(); // Shorten code, save 1 call
	//echo '<p> Plugin Directory: ' . WP_PLUGIN_DIR . '/team-rosters/css ' . ' </p>';
	//die;
	ob_start(); // Capture all output (output buffering)
	require($ss_dir . '/dynamic.php'); // Generate CSS
	$css = ob_get_clean( ); // Get generated CSS (output buffering)
	//$css = 'go Bears!';
	file_put_contents($ss_dir . '/mstw-tr-style.css', $css, LOCK_EX); // Save it
	
	return true;
}*/

function mstw_tr_admin_notices() {
    settings_errors( );
}
add_action( 'admin_notices', 'mstw_tr_admin_notices' );

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
		/*if ( $term ) {
			echo ' Slug: ' . $term->slug . '</p>';
			//$tax_input = array( 'teams' => array( $term->slug ) );
			//$tax_input = '';
		}
		else {
			$this->log['error'][] = "Unknown team. Are you sure you selected one?";
		}*/

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