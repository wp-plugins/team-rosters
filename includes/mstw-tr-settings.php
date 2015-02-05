<?php
/*----------------------------------------------------------------------------
 * mstw-tr-settings.php
 *	All functions for the MSTW Team Rosters Plugin settings.
 *		Loaded conditioned on is_admin() in mstw-tr-admin.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *--------------------------------------------------------------------------*/

//-------------------------------------------------------------------------------
// Render the display settings page (3 tabs & help)
//
function mstw_tr_settings_page( ) {
	global $pagenow;
	
	include_once 'mstw-tr-data-fields-columns-settings.php';
	include_once 'mstw-tr-roster-table-settings.php';
	include_once 'mstw-tr-roster-color-settings.php';
	include_once 'mstw-tr-player-profiles-galleries-settings.php';
	
	mstw_tr_data_fields_columns_setup( );
	mstw_tr_roster_table_setup( );
	mstw_tr_roster_colors_setup( );
	mstw_tr_bio_gallery_setup( );
	
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php echo __( 'Team Rosters Plugin Settings', 'mstw-team-rosters') ?></h2>
		<?php //settings_errors(); ?> 
		
		<?php 
		//Get or set the current tab - default to first/main settings tab
		$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'data-fields-columns-tab' );
		
		//mstw_log_msg( 'in mstw_tr_settings_page ...' );
		//mstw_log_msg( '$current_tab= ' . $current_tab );
		
		//Display the tabs, showing the current tab
		mstw_tr_admin_tabs( $current_tab );  
		?>
		
		<form action="options.php" method="post" id="target">
		
		<?php 
		//echo '<h2>pagenow = ' . $pagenow . ' page = ' . $_GET['page'] . '</h2>';
		//WHY DO WE NEED THIS CONDITIONAL, REALLY?
		if ( $pagenow == 'edit.php' && $_GET['page'] == 'mstw-tr-settings' ) {
			switch ( $current_tab ) {
				case 'data-fields-columns-tab':
					//settings_fields() outputs nonce, action, and option_page fields for form
					settings_fields( 'mstw_tr_settings' );
					do_settings_sections( 'mstw-tr-data-fields-columns' );
					$options_name = 'mstw_tr_options[reset]';
					break;
				case 'roster-table-tab';
					settings_fields( 'mstw_tr_settings' );
					do_settings_sections( 'mstw-tr-roster-table' );
					$options_name = 'mstw_tr_options[reset]';
					break;
				case 'roster-colors-tab';
					settings_fields( 'mstw_tr_settings' );
					do_settings_sections( 'mstw-tr-roster-colors' );
					$options_name = 'mstw_tr_options[reset]';
					break;
				case 'bio-gallery-tab':
					settings_fields( 'mstw_tr_settings' );
					do_settings_sections( 'mstw-tr-bio-gallery' );
					$options_name = 'mstw_tr_options[reset]';
					break;
			}
			?>
			
			<table class="form-table">
			<!-- Add a spacer row -->
			<tr><td><input type='hidden' name='current_tab' id='current_tab' value='<?php echo $current_tab ?>' /></td></tr>
			<tr>
				<td>
					<?php //submit_button( __( 'Save Changes', 'mstw-team-rosters' ), 'primary', 'Submit', false, null ) ?>
					<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'mstw-team-rosters' ) ?>" />
				
					<?php //submit_button( __( 'Reset Defaults', 'mstw-team-rosters' ), 'secondary', $options_name, false, array( 'id' => 'reset_btn' ) ) ?>
					<input type="submit" class="button-secondary" id="reset_btn" name="<?php echo $options_name ?>" onclick="tr_confirm_reset_defaults()" value="<?php _e( 'Reset Defaults', 'mstw-team-rosters' ) ?>" />
				</td>
			</tr>
			</table>
		<?php
		} //End: if ( $pagenow == 'edit.php' && $_GET['page'] == 'mstw_tr_settings' )
		?>	
		</form>
	</div> <!-- <div class="wrap"> -->
<?php
}

//-------------------------------------------------------------------------------
// Create admin page tabs
//
function mstw_tr_admin_tabs( $current_tab = 'data-fields-columns-tab' ) {
	$tabs = array( 	'data-fields-columns-tab' => __( 'Data Fields & Columns', 'mstw-team-rosters' ),
					'roster-table-tab' => __( 'Roster Tables', 'mstw-team-rosters' ),
					'roster-colors-tab' => __( 'Roster Table Colors', 'mstw-team-rosters' ),
					'bio-gallery-tab' => __( 'Player Profiles & Galleries', 'mstw-team-rosters' ),
					);
					
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='edit.php?post_type=mstw_tr_player&page=mstw-tr-settings&tab=$tab'>$name</a>";	
	}
	echo '</h2>';
}

//-------------------------------------------------------------------------------
// HELP SCREENS
//
// Add help to settings screen
// callback for load-$settings_page action
//	
if( !function_exists( 'mstw_tr_settings_help' ) ) {
	function mstw_tr_settings_help( ) {
		
		$screen = get_current_screen( );
		
		//mstw_log_msg( "mstw_tr_settings_help:" );
		//mstw_log_msg( '$screen->base: ' . $screen->base );
		//mstw_log_msg( '$screen->base: ' . $screen->post_type );
		//mstw_log_msg( $screen );
		
		return;
		
		if ( 'post' === $screen->base && 'mstw-ss-game' !== $screen->post_type ) return;

		$sidebar = '<p><strong>' . __( 'For more information:', 'mstw-team-rosters' ) . '</strong></p>' .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/" target="_blank">' . __( 'MSTW Schedules & Scoreboards Admin Users Manual', 'mstw-team-rosters' ) . '</a></p>' .
			'<p><a href="http://dev.shoalsummitsolutions.com" target="_blank">' . __( 'See MSTW Schedules in Action', 'mstw-team-rosters' ) . '</a></p>' .
			'<p><a href="http://wordpress.org/plugins/team-rosters/" target="_blank">' . __( 'MSTW Schedules & Scoreboards on WordPress.org', 'mstw-team-rosters' ) . '</a></p>';
		
		$tabs = array(
			array(
				'title'    => __( 'Data Fields & Columns', 'mstw-team-rosters' ),
				'id'       => 'fields-columns-help',
				'callback'  => 'mstw_tr_fields_columns_options_help'
				),
			array(
				'title'    => __( 'Date/Time Formats', 'mstw-team-rosters' ),
				'id'       => 'date-time-help',
				'callback'  => 'mstw_tr_date_time_options_help'
				),
			array(
				'title'		=> __( 'Colors', 'mstw-team-rosters' ),
				'id'		=> 'colors-help',
				'callback'	=> 'mstw_tr_color_options_help'
				),
			array(
				'title'		=> __( 'Venue Settings', 'mstw-team-rosters' ),
				'id'		=> 'venues-help',
				'callback'	=> 'mstw_tr_venues_options_help'
				),
		);

		foreach( $tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}
			
		$screen->set_help_sidebar( $sidebar );

	} //End: mstw_tr_settings_help()
}

//----------------------------------------------------------------------------
// help tab content
//
function mstw_tr_fields_columns_options_help( ) {
	$help = '<h3><strong>' . __( 'Data Fields & Columns Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the visibility of data fields and the corresponding columns, the field/column labels, and some format elements of the schedule tables, schedule sliders, and countdown timers. ', 'mstw-team-rosters' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, and timers, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
	echo $help;
}

function mstw_tr_color_options_help( ) {
	$help = '<h3><strong>' . __( 'Color Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the default colors for the schedule tables, sliders, and countdown timers.  These global defaults are very useful for setting the colors of all displays, especially if your site is for a single team or organization. Note that these settings apply to ALL schedule tables and sliders on the website.', 'mstw-team-rosters' ) . "</p>\n" .
			'<p>' . __('Unique CSS tags are provided for each team, allowing control of individual tables, sliders, and countdown timers. Using these tags, different color schemes can be applied to different. To do so, the plugin\'s stylesheet - mstw-gs-styles.css - must be edited. An admin with a knowledge of CSS can simply inspect the HTML elements in a browser and style them as desired. Those not experienced with CSS, may find some of the tutorials and code snippets on <a href="http://shoalsummitsolutions.com" target="_blank">ShoalSummitSolutions.com</a> may be of use. <br/> <a href="http://dev.shoalsummitsolutions.com/schedule-test/" target="_blank">Examples are provided on the MSTW plugin development site.</a>', 'mstw-team-rosters' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
	echo $help;
}
	
function mstw_tr_date_time_options_help( ) {
	$help = '<h3><strong>' . __( 'Date/Time Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the date time formats for the schedule tables, sliders, and countdown timers, as well as the admin screens. There are a number of built-in formats and the capability to provide any custom format. ', 'mstw-team-rosters' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, or timers, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
	echo $help;
}

function mstw_tr_venues_options_help( ) {
	$help = '<h3><strong>' . __( 'Venues Table Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the visibility of columns, their labels, and some format elements of the Venue tables. ', 'mstw-team-rosters' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL venue tables on the site. To control individual tables, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
	echo $help;
}
	

// ----------------------------------------------------------------	
// 	Team Names & Logos section instructions	
//
function mstw_tr_name_logo_inst( ) {
	echo '<p>' . __( "Control the display of team names & logos. NOTE: THESE SETTINGS ONLY APPLY WHEN SELECTING OPPONENTS FROM THE MSTW TEAMS DATABASE.", 'mstw-team-rosters' ) . '</p>';
}	

//-------------------------------------------------------------------------------
//
// VALIDATION FUNCTIONS
//
//-------------------------------------------------------------------------------
// Validate the user data entries in Display (fields/data) tab
// 
function mstw_tr_validate_settings( $input ) {
	mstw_log_msg( 'in mstw_tr_validate_settings ...' );
	mstw_log_msg( $_POST );
	//mstw_log_msg( '$input = ' );
	//mstw_log_msg( $input );
	
	// only replace existing settings with valid $input values
	$output = get_option( 'mstw_tr_options' );
	
	// Get current tab so we know what fields to validate and save
	// Default to first/main settings tab
	$current_tab = ( isset( $_POST['current_tab'] ) ) ? $_POST['current_tab'] : 'data-fields-columns-tab';

	//
	// THIS IS NOT RIGHT ... HAVE TO GO BY TAB
	//
	//check if the reset button was pressed and confirmed
	//array_key_exists() returns true for null, isset does not
	if ( array_key_exists( 'reset', $input ) ) {
		mstw_log_msg( 'OK, we are looking to reset defaults ' );
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			//add_settings_error( 'mstw_tr_settings_main', esc_attr( 'settings-reset' ), 'Settings reset to defaults', 'updated' );
			//add_action('admin_notices', 'mstw_tr_print_errors' );
			$output = mstw_tr_get_defaults( );
			mstw_log_msg( 'Settings reset' );
			mstw_log_msg( $output );
			mstw_add_admin_notice( 'mstw_tr_admin_messages','updated', 'Settings reset to defaults.' );
		}
		else {
			// Don't change nuthin'
			$output = get_option( 'mstw_tr_options' );
			mstw_log_msg( 'Reset cancelled.' );
			mstw_add_admin_notice( 'mstw_tr_admin_messages', 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else {
		switch ( $current_tab ) {
			case 'data-fields-columns-tab':
				//mstw_log_msg( 'validating ... $current_tab= data-fields-columns-tab' );
				foreach( $input as $key => $value ) {
					$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
				}
				break;
				
			case 'roster-table-tab':
				// checkboxes are unique
				$output['links_to_profiles'] = isset( $input['links_to_profiles'] ) ? 1 : 0;

				foreach( $input as $key => $value ) {
					switch( $key ) {
						case 'table_photo_width':
						case 'table_photo_height':
							// check numbers
							if( intval( $input[$key]) <= 0 or
									(string)$input[$key] != (string)intval( $input[$key] ) or
									$input[$key] != abs( $input[$key] )
									) {	
								// set error message and don't change settings
								$msg = sprintf( __( 'Error with %s. Reset to previous value.', 'mstw-team-roster' ), $key );
								mstw_add_admin_notice( 'mstw_tr_admin_messages', 'error', $msg );
							} else {
								$output[$key] = abs( intval( $input[$key] ) );
							}
							break;
						default:
							$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
							break;
					}
				}
				break;
				
			case 'roster-colors-tab':
				mstw_log_msg( 'validating ... $current_tab= roster-colors-tab' );
				foreach( $input as $key => $value ) {
					$sanitized_color = mstw_sanitize_hex_color( $input[$key] );
					// decide what to do - save new setting 
					// or display error & revert to last setting
					if ( isset( $sanitized_color ) ) {
						// blank input is valid
						$output[$key] = $sanitized_color;
					}
					else  {
						// there's an error. Reset to the last stored value ...
						// don't need to do this but $output[$key] = $options[$key];
						// and add error message
						$msg = sprintf( __( 'Error: %s reset to the default.', 'mstw-team-roster' ), $key );
						mstw_ss_add_admin_notice( 'error', $msg );
					}
				}
				break;
			case 'bio-gallery-tab':
				mstw_log_msg( 'validating ... $current_tab= bio-gallery-tab' );
				break;
		
		}
		
		// set updated message
		$msg = __( 'Roster table settings updated.', 'mstw-team-roster' );
		mstw_add_admin_notice( 'mstw_tr_admin_messages', 'updated', $msg );
		
		return $output;
		/*
		// JIC ... special handling for the checkboxes
		$output['show_date'] = isset( $input['show_date'] ) ? 1 : 0;
		if ( isset( $input['show_date'] ) ) 
			unset( $input['show_date'] );
		*/
			
		// Create array for storing the validated options
		$output = array();
		// Pull the previous (good) options
		$options = get_option( 'mstw_tr_options' );
		
		
		// NEED TO CHECK THIS OUT WITH JS "ARE YOU SURE?"
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
						
					case 'sp_image_width':
					case 'sp_image_height':
					case 'table_photo_width':
					case 'table_photo_height':
						$output[$key] = round( $input[$key] );
						$output[$key] = ( $output[$key] == 0 ) ? '' : $output[$key];
						break;
						
					// 0-1 stuff
					/*
					case 'show_title':
					case 'show_photos':
						if ( $input[$key] == 1 ) {
							$output[$key] = 1;
						}
						else {
							$output[$key] = 0;
						}
						break;
					*/	
					// Check all other settings
					default:
						$output[$key] = sanitize_text_field( $input[$key] );
						// There should not be user/accidental errors in these fields
						break;
					
				} // end switch
			} // end if
		} // end foreach
		return $output;
			mstw_tr_add_admin_notice( 'updated', 'Display settings updated.' );
	}
	
	return apply_filters( 'mstw_tr_sanitize_options', $output, $input );
	
} //End: mstw_tr_validate_settings( )

function mstw_tr_print_errors( ) {
	?>
	<div class="updated">
        <p><?php _e( 'Settings reset to defaults', 'mstw-team-rosters' ); ?></p>
    </div>
	<?php
}
?>