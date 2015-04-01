<?php
/*----------------------------------------------------------------------------
 * mstw-tr-roster-table-settings.php
 *	All functions for the MSTW Team Rosters Plugin's roster table [shortcode] settings.
 *	Loaded in mstw-tr-settings.php 
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
		
	if( !function_exists( 'mstw_tr_roster_table_setup' ) ) {
		function mstw_tr_roster_table_setup( ) {
			//mstw_log_msg( 'in mstw_tr_roster_table_setup ...' );
			mstw_tr_table_structure_section_setup( );
			//mstw_tr_table_colors_section_setup( );
		}
	} //End: mstw_tr_roster_table_setup()
	
	if( !function_exists( 'mstw_tr_table_structure_section_setup' ) ) {
		function mstw_tr_table_structure_section_setup( ) {		
			// Roster Table data fields/columns -- show/hide and labels
			$display_on_page = 'mstw-tr-roster-table';
			$page_section = 'mstw_tr_table_structure_settings';
			
			$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
			
			//mstw_log_msg( 'in mstw_tr_table_structure_section_setup ...' );
			//mstw_log_msg( '$display_on_page= ' . $display_on_page );
			//mstw_log_msg( '$page_section= ' . $page_section );
			//mstw_log_msg( '$options= ' );
			//mstw_log_msg( $options );
			
			
			add_settings_section(
				$page_section,
				__( 'Roster Table Settings', 'mstw-team-rosters' ),
				'mstw_tr_roster_table_inst',
				$display_on_page
				);
			
			//return;

			$arguments = array(
				array( 	// Show/hide roster TITLE
					'type' => 'show-hide', 
					'id' => 'show_title',
					'name'	=> 'mstw_tr_options[show_title]',
					'value' => mstw_safe_ref( $options, 'show_title' ), 
					'title' => __( 'Show Roster Table Titles:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Titles will display as "Team Name Roster" (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Roster FORMAT
					'type' => 'select-option', 
					'id' => 'roster_type',
					'name'	=> 'mstw_tr_options[roster_type]',
					'value' => mstw_safe_ref( $options, 'roster_type' ),
					'options' => array(	__( 'Custom', 'mstw-team-rosters' )=> 'custom', 
											__( 'Pro', 'mstw-team-rosters' ) => 'pro', 
											__( 'College', 'mstw-team-rosters' ) => 'college',
											__( 'High School', 'mstw-team-rosters' ) => 'high-school',
											__( 'Pro Baseball', 'mstw-team-rosters' ) => 'baseball-pro', 
											__( 'College Baseball', 'mstw-team-rosters' ) => 'baseball-college',
											__( 'High School Baseball', 'mstw-team-rosters' ) => 'baseball-high-school',
											),
					'title'	=> __( 'Roster Table Format:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Default: Custom, which is what you want if you are customizing the columns in the Data Fields & Columns tab.', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// ADD LINKS TO PROFILES
					'type' => 'checkbox', 
					'id' => 'links_to_profiles',
					'name'	=> 'mstw_tr_options[links_to_profiles]',
					'value' => mstw_safe_ref( $options, 'links_to_profiles' ), 
					'title' => __( 'Add Links to Player Profiles:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Default: No Links (Unchecked)' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// SORT ORDER
					'type' => 'select-option', 
					'id' => 'sort_order',
					'name'	=> 'mstw_tr_options[sort_order]',
					'value' => mstw_safe_ref( $options, 'sort_order' ), 
					'options' => array(	__( 'Last Name', 'mstw-team-rosters' )=> 'alpha', 
										__( 'First Name', 'mstw-team-rosters' ) => 'alpha-first', 
										__( 'Number', 'mstw-team-rosters' ) => 'numeric'		
										),
					'title' => __( 'Sort Roster By:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Default: Last Name', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// DISPLAY FORMAT for Player Names
					'type' => 'select-option', 
					'id' => 'name_format',
					'name'	=> 'mstw_tr_options[name_format]',
					'value' => mstw_safe_ref( $options, 'name_format' ), 
					'options' => array(	__( 'Last, First', 'mstw-team-rosters' )=> 'last-first', 
										__( 'First Last', 'mstw-team-rosters' ) => 'first-last', 
										__( 'First Name Only', 'mstw-team-rosters' ) => 'first-only',
										__( 'Last Name Only', 'mstw-team-rosters' ) => 'last-only'		
										),
					'title' => __( 'Display Players By:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Default: Last, First', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			
				array( 	// Player PHOTO WIDTH
					'type' => 'text', 
					'id' => 'table_photo_width',
					'name'	=> 'mstw_tr_options[table_photo_width]',
					'value' => mstw_safe_ref( $options, 'table_photo_width' ), 
					'title' => __( 'Table Photo Width:', 'mstw-team-rosters' ),
					'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 64px out of the box.)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Player PHOTO HEIGHT
					'type' => 'text', 
					'id' => 'table_photo_height',
					'name'	=> 'mstw_tr_options[table_photo_height]',
					'value' => mstw_safe_ref( $options, 'table_photo_height' ), 
					'title' => __( 'Table Photo Height:', 'mstw-team-rosters' ),
					'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 64px out of the box.)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			);
			
			mstw_build_settings_screen( $arguments );
			
		} //End: mstw_tr_data_fields_columns_setup()
	} //End: mstw_tr_data_fields_section_setup()
	
	//-----------------------------------------------------------------	
	// 	Roster table settings section instructions	
	//	
	if( !function_exists( 'mstw_tr_roster_table_inst' ) ) {
		function mstw_tr_roster_table_inst( ) {
			echo '<p>' . __( 'Note that these settings will apply to all the [shortcode] roster tables, overriding the settings and style defaults, as applicable. These settings can be overridden by more specific stylesheet rules for specific teams. See the plugin documentation for details.', 'mstw-team-rosters' ) .'</p>';
		} //End: mstw_tr_roster_table_inst()
	}
?>