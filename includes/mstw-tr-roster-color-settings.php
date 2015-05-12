<?php
/*----------------------------------------------------------------------------
 * mstw-tr-roster-color-settings.php
 *	All functions for the MSTW Team Rosters Plugin's roster table [shortcode] color settings.
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
		
	if( !function_exists( 'mstw_tr_roster_colors_setup' ) ) {
		function mstw_tr_roster_colors_setup( ) {
			//mstw_log_msg( 'in mstw_tr_roster_colors_setup ...' );
			//mstw_tr_table_structure_section_setup( );
			mstw_tr_table_colors_section_setup( );
		}
	} //End: mstw_tr_roster_colors_setup()
	
	
	//-----------------------------------------------------------------	
	// 	Color controls section	
	//
	if( !function_exists( 'mstw_tr_table_colors_section_setup' ) ) {
		function mstw_tr_table_colors_section_setup( ) {		
			// Roster Table data fields/columns -- show/hide and labels
			$display_on_page = 'mstw-tr-roster-colors';
			$page_section = 'mstw_tr_table_color_settings';
			
			$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
			
			add_settings_section(
				$page_section,
				__( 'Table Color Settings', 'mstw-team-rosters' ),
				'mstw_tr_table_color_inst',
				$display_on_page
				);
				
			$arguments = array( 
				array( 	// USE TEAM COLORS
					'type' => 'checkbox', 
					'id' => 'use_team_colors',
					'name'	=> 'mstw_tr_options[use_team_colors]',
					'value' => mstw_safe_ref( $options, 'use_team_colors' ), 
					'title' => __( 'Use Team Colors:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
					'desc' => __( 'Use the colors from the Teams table in MSTW Schedules & Scoreboards. IGNORED if the MSTW Schedules & Scoreboards version 4.0 or hire is not installed/activated. This setting also applies to player profiles and galleries.', 'mstw-team-rosters' ),
				),
				array( 	// ROSTER TITLE COLOR
					'type' => 'text', 
					'id' => 'table_title_color',
					'name'	=> 'mstw_tr_options[table_title_color]',
					'value' => mstw_safe_ref( $options, 'table_title_color' ), 
					'title' => __( 'Roster Table Title:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// ROSTER TABLE LINKS COLOR
					'type' => 'text', 
					'id' => 'table_links_color',
					'name'	=> 'mstw_tr_options[table_links_color]',
					'value' => mstw_safe_ref( $options, 'table_links_color' ), 
					'title' => __( 'Roster Table Links:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// TABLE HEADER BACKGROUND
					'type' => 'text', 
					'id' => 'table_head_bkgd',
					'name'	=> 'mstw_tr_options[table_head_bkgd]',
					'value' => mstw_safe_ref( $options, 'table_head_bkgd' ), 
					'title' => __( 'Table Header Background:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// TABLE HEADER TEXT
					'type' => 'text', 
					'id' => 'table_head_text',
					'name'	=> 'mstw_tr_options[table_head_text]',
					'value' => mstw_safe_ref( $options, 'table_head_text' ), 
					'title' => __( 'Table Header Text:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// EVEN ROW BACKGROUND
					'type' => 'text', 
					'id' => 'table_even_row_bkgd',
					'name'	=> 'mstw_tr_options[table_even_row_bkgd]',
					'value' => mstw_safe_ref( $options, 'table_even_row_bkgd' ), 
					'title' => __( 'Even Row Background:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// EVEN ROW TEXT
					'type' => 'text', 
					'id' => 'table_even_row_text',
					'name'	=> 'mstw_tr_options[table_even_row_text]',
					'value' => mstw_safe_ref( $options, 'table_even_row_text' ), 
					'title' => __( 'Even Row Text:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// ODD ROW BACKGROUND
					'type' => 'text', 
					'id' => 'table_odd_row_bkgd',
					'name'	=> 'mstw_tr_options[table_odd_row_bkgd]',
					'value' => mstw_safe_ref( $options, 'table_odd_row_bkgd' ), 
					'title' => __( 'Odd Row Background:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// ODD ROW TEXT
					'type' => 'text', 
					'id' => 'table_odd_row_text',
					'name'	=> 'mstw_tr_options[table_odd_row_text]',
					'value' => mstw_safe_ref( $options, 'table_odd_row_text' ), 
					'title' => __( 'Odd Row Text:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// TABLE BORDERS
					'type' => 'text', 
					'id' => 'table_border_color',
					'name'	=> 'mstw_tr_options[table_border_color]',
					'value' => mstw_safe_ref( $options, 'table_border_color' ), 
					'title' => __( 'Table Borders:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			);
			
			mstw_build_settings_screen( $arguments );
				
		} //End: mstw_tr_table_colors_section_setup
	}	

	//-----------------------------------------------------------------	
	// 	Roster table colors section instructions	
	//	
	if( !function_exists( 'mstw_tr_table_color_inst' ) ) {
		function mstw_tr_table_color_inst( ) {
			echo '<p>' . __( 'Note that these settings will apply to ALL the [shortcode] roster tables, overriding the default styles. However they can be overridden by more specific stylesheet rules for specific teams. See the plugin documentation for more details.', 'mstw-team-rosters' ) . '</p>';
		} //End: mstw_tr_table_color_inst()
	}
?>