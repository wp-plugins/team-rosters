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
// Render the display settings page
//
if( !function_exists( 'mstw_tr_settings_page' ) ) {
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
		<!-- The settings screen main form; includes all tabs -->
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php echo __( 'Team Rosters Plugin Settings', 'mstw-team-rosters') ?></h2>
			
			<?php 
			//Get or set the current tab - default to first/main settings tab
			$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'data-fields-columns-tab' );

			//Set-up the tabs; set the current tab as active
			mstw_tr_admin_tabs( $current_tab );  
			?>
			
			<form action="options.php" method="post" id="target">
			
			<?php 
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
		</div> <!-- <div .wrap> -->
	<?php
	} //End: mstw_tr_settings_page( )
}

//-------------------------------------------------------------------------------
// Create admin page tabs
//
if( !function_exists( 'mstw_tr_admin_tabs' ) ) {
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
	} //End: mstw_tr_admin_tabs( )
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
		
		//return;
		
		if ( 'post' === $screen->base && 'mstw-ss-player' !== $screen->post_type ) return;

		$sidebar = '<p><strong>' . __( 'For more information:', 'mstw-team-rosters' ) . '</strong></p>' .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/" target="_blank">' . __( 'MSTW Team Rosters Admin Users Manual', 'mstw-team-rosters' ) . '</a></p>' .
			'<p><a href="http://dev.shoalsummitsolutions.com" target="_blank">' . __( 'See MSTW Team Rosters in Action', 'mstw-team-rosters' ) . '</a></p>' .
			'<p><a href="http://wordpress.org/plugins/team-rosters/" target="_blank">' . __( 'MSTW Team Rosters on WordPress.org', 'mstw-team-rosters' ) . '</a></p>';
		
		$tabs = array(
			array(
				'title'    => __( 'Data Fields & Columns', 'mstw-team-rosters' ),
				'id'       => 'data-fields-columns-help',
				'callback'  => 'mstw_tr_data_fields_columns_help'
				),
			array(
				'title'    => __( 'Roster Tables', 'mstw-team-rosters' ),
				'id'       => 'roster-tables-help',
				'callback'  => 'mstw_tr_roster_tables_help'
				),
			array(
				'title'		=> __( 'Roster Table Colors', 'mstw-team-rosters' ),
				'id'		=> 'roster-table-colors-help',
				'callback'	=> 'mstw_tr_roster_table_colors_help'
				),
			array(
				'title'		=> __( 'Player Profiles & Galleries', 'mstw-team-rosters' ),
				'id'		=> 'player-profiles-galleries-help',
				'callback'	=> 'mstw_tr_player_profiles_galleries_help'
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
if( !function_exists( 'mstw_tr_data_fields_columns_help' ) ) { 
	function mstw_tr_data_fields_columns_help( ) {
		$help = '<h3><strong>' . __( 'Data Fields & Columns Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen controls the visibility of data fields and the corresponding columns, the field/column labels, and some format elements of the schedule tables, schedule sliders, and countdown timers. ', 'mstw-team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, and timers, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End:mstw_tr_data_fields_columns_help( )
}

if( !function_exists( 'mstw_tr_roster_tables_help' ) ) { 
	function mstw_tr_roster_tables_help( ) {
		$help = '<h3><strong>' . __( 'Roster Tables Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen controls the default colors for the schedule tables, sliders, and countdown timers.  These global defaults are very useful for setting the colors of all displays, especially if your site is for a single team or organization. Note that these settings apply to ALL schedule tables and sliders on the website.', 'mstw-team-rosters' ) . "</p>\n" .
				'<p>' . __('Unique CSS tags are provided for each team, allowing control of individual tables, sliders, and countdown timers. Using these tags, different color schemes can be applied to different. To do so, the plugin\'s stylesheet - mstw-gs-styles.css - must be edited. An admin with a knowledge of CSS can simply inspect the HTML elements in a browser and style them as desired. Those not experienced with CSS, may find some of the tutorials and code snippets on <a href="http://shoalsummitsolutions.com" target="_blank">ShoalSummitSolutions.com</a> may be of use. <br/> <a href="http://dev.shoalsummitsolutions.com/schedule-test/" target="_blank">Examples are provided on the MSTW plugin development site.</a>', 'mstw-team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End: mstw_tr_roster_tables_help( )
}

if( !function_exists( 'mstw_tr_roster_table_colors_help' ) ) { 	
	function mstw_tr_roster_table_colors_help( ) {
		$help = '<h3><strong>' . __( 'Roster Table Color Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen controls the date time formats for the schedule tables, sliders, and countdown timers, as well as the admin screens. There are a number of built-in formats and the capability to provide any custom format. ', 'mstw-team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, or timers, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End: mstw_tr_roster_table_colors_help( )
}

if( !function_exists( 'mstw_tr_player_profiles_galleries_help' ) ) { 	
	function mstw_tr_player_profiles_galleries_help( ) {
		$help = '<h3><strong>' . __( 'Player Profiles & Galleries Settings:', 'mstw-team-rosters' ) . '</strong></h3>' .
				'<p>' . __('This screen controls the visibility of columns, their labels, and some format elements of the Venue tables. ', 'mstw-team-rosters' ) . "</p>\n" .
				'<p>' . __('Note that these settings apply to ALL venue tables on the site. To control individual tables, set the corresponding arguments in the shortcodes.', 'mstw-team-rosters' ) . "</p>\n" .
				'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-team-rosters' ) . "</a></p>\n";
		echo $help;
	} //End: mstw_tr_player_profiles_galleries_help( )
}
	
//-------------------------------------------------------------------------------
//
// VALIDATION FUNCTIONS
//
//-------------------------------------------------------------------------------
// Validate the user data entries in Display (fields/data) tab
//
if( !function_exists( 'mstw_tr_validate_settings' ) ) { 
	function mstw_tr_validate_settings( $input ) {
		mstw_log_msg( 'in mstw_tr_validate_settings ...' );
		mstw_log_msg( $_POST );
		mstw_log_msg( '$input = ' );
		mstw_log_msg( $input );
		
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
			mstw_log_msg( 'OK, we are looking to reset defaults $current_tab = ' . $current_tab );
			if( $input['reset'] == 'Resetting Defaults' ) {
				// reset to defaults
				switch( $current_tab ) {
					case 'data-fields-columns-tab':
						mstw_log_msg( '$output(orig) = ' );
						mstw_log_msg( $output );
						mstw_log_msg( 'data_fields_columns_defaults = ' );
						mstw_log_msg( mstw_tr_get_data_fields_columns_defaults( ) );
						
						$output = array_merge( $output,mstw_tr_get_data_fields_columns_defaults( ) );
						
						mstw_log_msg( '$output(merged) = ' );
						mstw_log_msg( $output );
						
						$msg = __( 'Data fields & columns settings reset to defaults.', 'mstw-team-rosters');
						break;
					case 'roster-table-tab':
						$output = array_merge( $output, mstw_tr_get_roster_table_defaults( ) );
						$msg = __( 'Roster table settings reset to defaults.', 'mstw-team-rosters');
						break;
					case 'roster-colors-tab':
						$output = array_merge( $output, mstw_tr_get_roster_table_colors_defaults( ) );
						$msg = __( 'Roster table color settings reset to defaults.', 'mstw-team-rosters');
						break;
					case 'bio-gallery-tab':
						$output = array_merge( $output, mstw_tr_get_bio_gallery_defaults( ) );
						$msg = __( 'Player profile & gallery settings reset to defaults.', 'mstw-team-rosters');
						break;	
				}
				
				mstw_add_admin_notice( 'mstw_tr_admin_messages','updated', $msg );
			}
			else {
				// Don't change nuthin'
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
					
					$msg = __( 'Data fields & columns settings updated.', 'mstw-team-roster' );
					break;
					
				case 'roster-table-tab':
					// checkboxes are unique
					$output['links_to_profiles'] = isset( $input['links_to_profiles'] ) and $input['links_to_profiles'] == 1 ? 1 : 0;

					foreach( $input as $key => $value ) {
						switch( $key ) {
							case 'table_photo_width':
							case 'table_photo_height':
								// check numbers
						if( $input[$key] != '' and 
							( intval( $input[$key]) <= 0 or
							  (string)$input[$key] != (string)intval( $input[$key] ) or
							  $input[$key] != abs( $input[$key] ) 
							) ) {	
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
					
					$msg = __( 'Roster tables settings updated.', 'mstw-team-roster' );
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
					
					$msg = __( 'Roster table colors settings updated.', 'mstw-team-roster' );
					break;
					
				case 'bio-gallery-tab':
					mstw_log_msg( 'validating ... $current_tab= bio-gallery-tab' );
					foreach( $input as $key => $value ) {
						switch( $key ) {
							case 'sp_content_title':  // text settings
								$output[$key] = ( sanitize_text_field( $input[$key] ) == $input[$key] ) ? $input[$key] : $output[$key];
								break;
							
							case 'sp_image_width': // number settings
							case 'sp_image_height':
								if( $input[$key] != '' and 
									( intval( $input[$key]) <= 0 or
									(string)$input[$key] != (string)intval( $input[$key] ) or
									$input[$key] != abs( $input[$key] ) 
									) ) {	
									// set error message and don't change settings
									$msg = sprintf( __( 'Error with %s. Reset to previous value.', 'mstw-team-roster' ), $key );
									mstw_add_admin_notice( 'mstw_tr_admin_messages', 'error', $msg );
								} else {
									$output[$key] = abs( intval( $input[$key] ) );
								}
								break;
								
							default: //color settings
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
								break;
						
						} //End: switch( $key )
					} //foreach( $input as $key
					
					$msg = __( 'Player profiles & galleries settings updated.', 'mstw-team-roster' );
					break;
					
			} // End: switch( $current_tab )
			
			// set updated message
			
			mstw_add_admin_notice( 'mstw_tr_admin_messages', 'updated', $msg );
			
		} // End: else validate options, not reset
		
		return apply_filters( 'mstw_tr_sanitize_options', $output, $input );	
		
	} //End: mstw_tr_validate_settings( )
}

?>