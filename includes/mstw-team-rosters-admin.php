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
//}


// ----------------------------------------------------------------
// Create the meta box for the Team Roster custom post type

	add_action( 'add_meta_boxes', 'mstw_tr_add_meta' );

	function mstw_tr_add_meta () {
	
		/* This is an attempt to move the player meta box above the 
			content editor box
		global $_wp_post_type_features;
		if (isset($_wp_post_type_features['post']['editor']) && 
					$_wp_post_type_features['post']['editor']) {
			unset($_wp_post_type_features['post']['editor']);
		} */
		
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
			
	update_post_meta( $post_id, '_mstw_tr_age',
			strip_tags( $_POST['mstw_tr_age'] ) );
			
	update_post_meta( $post_id, '_mstw_tr_home_town',
			strip_tags( $_POST['mstw_tr_home_town'] ) );
			
	update_post_meta( $post_id, '_mstw_tr_last_school',
			strip_tags( $_POST['mstw_tr_last_school'] ) );
			
	update_post_meta( $post_id, '_mstw_tr_country',
			strip_tags( $_POST['mstw_tr_country'] ) );
			
	update_post_meta( $post_id, '_mstw_tr_other_info',
			strip_tags( $_POST['mstw_tr_other_info'] ) );
	
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

function mstw_tr_add_page() {
	//The next line adds the settings page to the Settings menu
	//add_options_page( 'Team Rosters Settings', 'Team Rosters Settings', 'manage_options', 'mstw_tr_settings', 'mstw_tr_option_page' );
	
	// But I decided to add the settings page to the Players menu
	$page = add_submenu_page( 	'edit.php?post_type=player', 
						'Team Rosters Settings', 
						'Settings', 
						'manage_options', 
						'mstw_tr_settings', 
						'mstw_tr_option_page' );
						
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

// --------------------------------------------------------------------------------------
// Render the option page
function mstw_tr_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Team Rosters Plugin Settings</h2>
		<?php //settings_errors(); ?>
		<form action="options.php" method="post">
			<?php settings_fields('mstw_tr_options'); ?>
			<?php do_settings_sections('mstw_tr_settings'); ?>
			<input name="Submit" type="submit" class="button-primary" value="Save Changes" />
		</form>
	</div>
	<?php
}

// --------------------------------------------------------------------------------------
// Register and define the settings
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
	
	// Title for the content (E.g., "Player Bio")
	add_settings_field(
		'mstw_tr_sp_content_title',
		'Player content title (defaults to "Player Bio"):',
		'mstw_tr_sp_content_title_input',
		'mstw_tr_settings',
		'mstw_tr_single_page_settings'
	);
	
	// Background color of main box
	add_settings_field(
		'mstw_tr_sp_main_bkgd_color',
		'Main box background color (hex):',
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
		'Main box text color (hex):',
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
		'Link text color (hex):',
		'mstw_tr_table_links_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Header Background Color
	add_settings_field(
		'mstw_tr_table_head_bkgd_color',
		'Table Header Background Color (hex):',
		'mstw_tr_table_head_bkgd_color_input',
		'mstw_tr_settings',
		'mstw_tr_roster_table_settings'
	);
	
	// Roster Table Header Text Color
	add_settings_field(
		'mstw_tr_table_head_text_color',
		'Table Header Text Color (hex):',
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
	
	// Sort player galleray numerically or alphabetically
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
 
 function mstw_tr_sp_content_title_input() {
	// get option 'sp_content_title' value from the database
	$options = get_option( 'mstw_tr_options' );
	$sp_content_title = $options['sp_content_title'];
	// echo the field
	echo "<input id='sp_content_title' name='mstw_tr_options[sp_content_title]' type='text' value='$sp_content_title' />";
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
	$tr_use_player_links = $options['tr_use_player_links'];
	
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
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'sandbox_theme_validate_input_examples', $output, $input );
}

function mstw_sanitize_hex_color( $color ) {
	// Check $color for proper hex color format (3 or 6 digits) or the empty string.
	// Returns corrected string if valid hex color, returns null otherwise
	
	if ( '' === $color )
		return '';

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}

function mstw_tr_admin_notices() {
    settings_errors( );
}
add_action( 'admin_notices', 'mstw_tr_admin_notices' );

// --------------------------------------------------------------------------------------
// Callback for: register_activation_hook(__FILE__, 'mstw_tr_set_defaults')
// --------------------------------------------------------------------------------------
// This function runs when the plugin is activated. If there are no options currently set, 
// or the user has selected the checkbox to reset the options to their defaults,
// then the options are set/reset. Otherwise the options remain unchanged.
// --------------------------------------------------------------------------------------
/* function mstw_tr_set_defaults() {
	$tmp = get_option('mstw_tr_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('mstw_tr_options'); // so we don't have to reset all the 'off' checkboxes too! 
		$arr = array(	"mstw_tr_hdr_bkgd" => "#000000",
						"mstw_tr_hdr_text" => "#FFFFFF",
						"mstw_tr_even_bkgd" => "#DBE5F1",
						"mstw_tr_even_text" => "#000000",
						"mstw_tr_odd_bkgd" => "#FFFFFF",
						"mstw_tr_odd_text" => "#000000",
						"mstw_tr_brdr_width" => "2",  //px
						"mstw_tr_brdr_color" => "#F481BD",
						"mstw_tr_default_opts" => "",
		);
		update_option('mstw_tr_options', $arr);
	}
}
*/