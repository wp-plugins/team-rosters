<?php
/*----------------------------------------------------------------------------
 * mstw-tr-data-fields-columns-settings.php
 *	All functions for the MSTW Team Rosters Plugin's data fields & columns settings.
 *	Loaded by mstw-tr-settings.php 
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

//-----------------------------------------------------------------	
// 	This first function is here for potential future page re-org	
//		
	if( !function_exists( 'mstw_tr_data_fields_columns_setup' ) ) {
		function mstw_tr_data_fields_columns_setup( ) {
			mstw_tr_data_fields_section_setup( );
		}
	} //End: mstw_tr_data_fields_columns_setup()
	
	if( !function_exists( 'mstw_tr_data_fields_section_setup' ) ) {
		function mstw_tr_data_fields_section_setup( ) {		
			// Roster Table data fields/columns -- show/hide and labels
			$display_on_page = 'mstw-tr-data-fields-columns';
			$page_section = 'mstw_tr_fields_columns_settings';
			
			$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
			
			//mstw_log_msg( 'in mstw_tr_roster_data_fields_setup ... ' );
			//mstw_log_msg( $options );
			//mstw_log_msg( 'in mstw_tr_data_fields_section_setup ...' );
			//mstw_log_msg( '$display_on_page= ' . $display_on_page );
			//mstw_log_msg( '$page_section= ' . $page_section );
			//mstw_log_msg( '$options= ' );
			//mstw_log_msg( $options );
			
			
			add_settings_section(
				$page_section,
				__( 'Data Fields & Columns', 'mstw-team-rosters' ),
				'mstw_tr_data_fields_inst',
				$display_on_page
				);
			
			//return;

			$arguments = array(
				array( 	// Show/hide NUMBER column
					'type' => 'show-hide', 
					'id' => 'show_number',
					'name'	=> 'mstw_tr_options[show_number]',
					'value' => mstw_safe_ref( $options, 'show_number' ), 
					'title' => __( 'Show Number:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Number field/column. (Default: Show)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// NUMBER column label
					'type' => 'text', 
					'id' => 'number_label',
					'name'	=> 'mstw_tr_options[number_label]',
					'value' => mstw_safe_ref( $options, 'number_label' ),
					'title'	=> __( 'Number Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Number field/column. (Default: Nbr)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// NAME column label
					'type' => 'text', 
					'id' => 'name_label',
					'name'	=> 'mstw_tr_options[name_label]',
					'value' => mstw_safe_ref( $options, 'name_label' ), 
					'title' => __( 'Name Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Name field/column cannot be hidden. (Default: Name)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide POSITION column
					'type' => 'show-hide', 
					'id' => 'show_position',
					'name'	=> 'mstw_tr_options[show_position]',
					'value' => mstw_safe_ref( $options, 'show_position' ), 
					'title' => __( 'Show Position:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Position field/column. (Default: Show)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// POSITION column label
					'type' => 'text', 
					'id' => 'position_label',
					'name'	=> 'mstw_tr_options[position_label]',
					'value' => mstw_safe_ref( $options, 'position_label' ),
					'title'	=> __( 'Position Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Position field/column. (Default: Pos)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide HEIGHT column
					'type' => 'show-hide', 
					'id' => 'show_height',
					'name'	=> 'mstw_tr_options[show_height]',
					'value' => mstw_safe_ref( $options, 'show_height' ), 
					'title' => __( 'Show Height:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Height field/column. (Default: Show)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// HEIGHT column label
					'type' => 'text', 
					'id' => 'height_label',
					'name'	=> 'mstw_tr_options[height_label]',
					'value' => mstw_safe_ref( $options, 'height_label' ),
					'title'	=> __( 'Height Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Height field/column. (Default: Ht)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide WEIGHT column
					'type' => 'show-hide', 
					'id' => 'show_weight',
					'name'	=> 'mstw_tr_options[show_weight]',
					'value' => mstw_safe_ref( $options, 'show_weight' ), 
					'title' => __( 'Show Weight:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Weight field/column. (Default: Show)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// WEIGHT column label
					'type' => 'text', 
					'id' => 'weight_label',
					'name'	=> 'mstw_tr_options[weight_label]',
					'value' => mstw_safe_ref( $options, 'weight_label' ),
					'title'	=> __( 'Weight Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Weight field/column. (Default: Wt)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide YEAR column
					'type' => 'show-hide', 
					'id' => 'show_year',
					'name'	=> 'mstw_tr_options[show_year]',
					'value' => mstw_safe_ref( $options, 'show_year' ), 
					'title' => __( 'Show Year:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Year field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// YEAR column label
					'type' => 'text', 
					'id' => 'year_label',
					'name'	=> 'mstw_tr_options[year_label]',
					'value' => mstw_safe_ref( $options, 'year_label' ),
					'title'	=> __( 'Year Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Year field/column. (Default: Year)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide EXPERIENCE column
					'type' => 'show-hide', 
					'id' => 'show_experience',
					'name'	=> 'mstw_tr_options[show_experience]',
					'value' => mstw_safe_ref( $options, 'show_experience' ), 
					'title' => __( 'Show Experience:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Experience field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// EXPERIENCE column label
					'type' => 'text', 
					'id' => 'experience_label',
					'name'	=> 'mstw_tr_options[experience_label]',
					'value' => mstw_safe_ref( $options, 'experience_label' ),
					'title'	=> __( 'Experience Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Experience field/column. (Default: Exp)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide AGE column
					'type' => 'show-hide', 
					'id' => 'show_age',
					'name'	=> 'mstw_tr_options[show_age]',
					'value' => mstw_safe_ref( $options, 'show_age' ), 
					'title' => __( 'Show Age:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Age field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// AGE column label
					'type' => 'text', 
					'id' => 'age_label',
					'name'	=> 'mstw_tr_options[age_label]',
					'value' => mstw_safe_ref( $options, 'age_label' ),
					'title'	=> __( 'Age Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Age field/column. (Default: Age)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide HOME TOWN column
					'type' => 'show-hide', 
					'id' => 'show_home_town',
					'name'	=> 'mstw_tr_options[show_home_town]',
					'value' => mstw_safe_ref( $options, 'show_home_town' ), 
					'title' => __( 'Show Home Town:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Home Town field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// HOME TOWN column label
					'type' => 'text', 
					'id' => 'home_town_label',
					'name'	=> 'mstw_tr_options[home_town_label]',
					'value' => mstw_safe_ref( $options, 'home_town_label' ),
					'title'	=> __( 'Home Town Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Home Town field/column. (Default: Home Town)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide LAST SCHOOL column
					'type' => 'show-hide', 
					'id' => 'show_last_school',
					'name'	=> 'mstw_tr_options[show_last_school]',
					'value' => mstw_safe_ref( $options, 'show_last_school' ), 
					'title' => __( 'Show Last School:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Last School field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// LAST SCHOOL column label
					'type' => 'text', 
					'id' => 'last_school_label',
					'name'	=> 'mstw_tr_options[last_school_label]',
					'value' => mstw_safe_ref( $options, 'last_school_label' ),
					'title'	=> __( 'Last School Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Last School field/column. (Default: Last School)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide COUNTRY column
					'type' => 'show-hide', 
					'id' => 'show_country',
					'name'	=> 'mstw_tr_options[show_country]',
					'value' => mstw_safe_ref( $options, 'show_country' ), 
					'title' => __( 'Show Country:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Country field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// COUNTRY column label
					'type' => 'text', 
					'id' => 'country_label',
					'name'	=> 'mstw_tr_options[country_label]',
					'value' => mstw_safe_ref( $options, 'country_label' ),
					'title'	=> __( 'Country Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Country field/column. (Default: Country)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide BATS/THROWS column
					'type' => 'show-hide', 
					'id' => 'show_bats_throws',
					'name'	=> 'mstw_tr_options[show_bats_throws]',
					'value' => mstw_safe_ref( $options, 'show_bats_throws' ), 
					'title' => __( 'Show Bats/Throws:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Bats/Throws field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// BATS/THROWS column label
					'type' => 'text', 
					'id' => 'bats_throws_label',
					'name'	=> 'mstw_tr_options[bats_throws_label]',
					'value' => mstw_safe_ref( $options, 'bats_throws_label' ),
					'title'	=> __( 'Bats/Throws Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Bats/Throws field/column. (Default: Bat/Thw)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide OTHER INFO column
					'type' => 'show-hide', 
					'id' => 'show_other_info',
					'name'	=> 'mstw_tr_options[show_other_info]',
					'value' => mstw_safe_ref( $options, 'show_other_info' ), 
					'title' => __( 'Show Other Info:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Show or hide the Other Info field/column. (Default: Hide)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// OTHER INFO column label
					'type' => 'text', 
					'id' => 'other_info_label',
					'name'	=> 'mstw_tr_options[other_info_label]',
					'value' => mstw_safe_ref( $options, 'other_info_label' ),
					'title'	=> __( 'Other Info Label:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label for the Other Info field/column. (Default: Other)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			);
			
			mstw_build_settings_screen( $arguments );
			
		} //End: mstw_tr_data_fields_columns_setup()
	} //End: mstw_tr_data_fields_section_setup()
	
	//-----------------------------------------------------------------	
// 	Colors table section instructions	
//	
	if( !function_exists( 'mstw_tr_data_fields_inst' ) ) {
		function mstw_tr_data_fields_inst( ) {
			echo '<p>' . __( 'Settings to control the visibility of data fields & table columns as well as to change their labels to "re-purpose" the fields. ', 'mstw-team-rosters' ) .'</p>';
		} //End: mstw_tr_data_fields_inst()
	}
	
//-----------------------------------------------------------------	
// 	Table (shortcode and widget) colors section setup	
// 
/*
function mstw_ss_table_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-table-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Schedule Table Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_table_inst',
		$display_on_page
		);

	$arguments = array(
		array( 	// TABLE HEADER BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_hdr_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_hdr_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_hdr_bkgd_color' ), //$options['ss_tbl_hdr_bkgd_color'], 
			'title'	=> __( 'Header Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),array( 	// TABLE HEADER TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_hdr_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_hdr_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_hdr_text_color' ), //$options['ss_tbl_hdr_text_color'],
			'title'	=> __( 'Header Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// TABLE BORDER COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_border_color',
			'name' => 'mstw_ss_color_options[ss_tbl_border_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_border_color' ), //$options['ss_tbl_border_color'],
			'title'	=> __( 'Table Border Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// ODD ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_odd_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_odd_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_odd_bkgd_color' ), //$options['ss_tbl_odd_bkgd_color'],
			'title'	=> __( 'Odd Row Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// ODD ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_odd_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_odd_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_odd_text_color' ), //$options['ss_tbl_odd_text_color'],
			'title'	=> __( 'Odd Row Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// EVEN ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_even_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_even_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_even_bkgd_color' ), //$options['ss_tbl_even_bkgd_color'],
			'title'	=> __( 'Even Row Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// EVEN ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_even_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_even_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_even_text_color' ), //$options['ss_tbl_even_text_color'],
			'title'	=> __( 'Even Row Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HOME GAME ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_home_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_home_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_home_bkgd_color' ), //$options['ss_tbl_home_bkgd_color'],
			'title'	=> __( 'Home Game (row) Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HOME GAME ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_home_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_home_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_home_text_color' ), //$options['ss_tbl_home_text_color'],
			'title'	=> __( 'Home Game (row) Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );
}

//-----------------------------------------------------------------	
// 	Colors table section instructions	
//	
function mstw_ss_colors_table_inst( ) {
	echo '<p>' . __( "Enter the default colors for the Schedule Table shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet." , 'mstw-schedules-scoreboards' ) . '</p>';
}
		
//-----------------------------------------------------------------	
// 	CDT (shortcode and widget) colors section setup	
//	
function mstw_ss_cdt_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-cdt-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Countdown Timer Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_cdt_inst',
		$display_on_page
	);
	
	$arguments = array(
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_game_time_color',
		'name' => 'mstw_ss_color_options[ss_cdt_game_time_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_game_time_color' ), //$options['ss_cdt_game_time_color'],
		'title'	=> __( 'Game Time Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_opponent_color',
		'name' => 'mstw_ss_color_options[ss_cdt_opponent_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_opponent_color' ), //$options['ss_cdt_opponent_color'],
		'title'	=> __( 'Opponent Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_location_color',
		'name' => 'mstw_ss_color_options[ss_cdt_location_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_location_color' ), //$options['ss_cdt_location_color'],
		'title'	=> __( 'Location Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color',
		'id' => 'ss_cdt_intro_color',
		'name' => 'mstw_ss_color_options[ss_cdt_intro_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_intro_color' ), //$options['ss_cdt_intro_color'],
		'title'	=> __( 'Intro Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_countdown_color',
		'name' => 'mstw_ss_color_options[ss_cdt_countdown_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_countdown_color' ), //$options['ss_cdt_countdown_color'],
		'title'	=> __( 'Countdown Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_countdown_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_cdt_countdown_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_countdown_bkgd_color' ), //$options['ss_cdt_countdown_bkgd_color'],
		'title'	=> __( 'Countdown Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );

} //End: mstw_ss_cdt_colors_section_setup()

//-----------------------------------------------------------------	
// 	Colors CDT section instructions	
//	
function mstw_ss_colors_cdt_inst( ) {
	echo '<p>' . __( "Enter the default colors for the countdown timer shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet.", 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//-----------------------------------------------------------------	
// 	Slider colors section setup	
//	
function mstw_ss_slider_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-slider-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Schedule Slider Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_slider_inst',
		$display_on_page
		);	
	
	$arguments = array(
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_bkgd_color' ), //$options['ss_sldr_hdr_bkgd_color'],
		'title'	=> __( 'Header Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_block_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_block_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_block_bkgd_color' ), //$options['ss_sldr_game_block_bkgd_color'],
		'title'	=> __( 'Game Block Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_text_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_text_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_text_color' ), //$options['ss_sldr_hdr_text_color'],
		'title'	=> __( 'Header Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_divider_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_divider_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_divider_color' ), //$options['ss_sldr_hdr_divider_color'],
		'title'	=> __( 'Header Divider (line) Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_date_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_date_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_date_color' ), //$options['ss_sldr_game_date_color'],
		'title'	=> __( 'Game Date Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_opponent_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_opponent_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_opponent_color' ), //$options['ss_sldr_game_opponent_color'],
		'title'	=> __( 'Opponent Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_location_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_location_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_location_color' ), //$options['ss_sldr_game_location_color'],
		'title'	=> __( 'Game Location Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_time_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_time_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_time_color' ), //$options['ss_sldr_game_time_color'],
		'title'	=> __( 'Game Time Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );
	
} //End: mstw_ss_slider_colors_section_setup()
	
// ----------------------------------------------------------------	
// 	Colors Slider section instructions	
// ----------------------------------------------------------------	
function mstw_ss_colors_slider_inst( ) {
	echo '<p>' . __( "Enter the default colors for the Schedule Slider shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet.", 'mstw-schedules-scoreboards' ) . '</p>';
}

// ----------------------------------------------------------------	
//	Validate user color settings input
// 
function mstw_ss_validate_colors( $input ) {
	// Create our array for storing the validated options
	$output = array();
	
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			$output = mstw_ss_get_dtg_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// cancel reset; return the previous (last good) options
			$output = get_option( 'mstw_ss_color_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else { // validate the user entries
	
		// Pull the previous (last good) options (used in case of errors)
		$options = get_option( 'mstw_ss_color_options' );
		
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				// validate the color for proper hex format
				// there should NEVER be a problem; js color selector should error check
				$sanitized_color = mstw_sanitize_hex_color( $input[$key] );
				
				// decide what to do - save new setting 
				// or display error & revert to last setting
				if ( isset( $sanitized_color ) ) {
					// blank input is valid
					$output[$key] = $sanitized_color;
				}
				else  {
					// there's an error. Reset to the last stored value ...
					$output[$key] = $options[$key];
					// and add error message
					$msg = sprintf( __( 'Error: %s reset to the default.', 'mstw-schedules-scoreboards' ), $key );
					mstw_ss_add_admin_notice( 'error', $msg );
				}
			} // end if
		} // end foreach
		
		mstw_ss_add_admin_notice( 'updated', 'Color settings updated.' );
		
	} // end else
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'mstw_ss_sanitize_color_options', $output, $input );
	
} //End: mstw_ss_validate_color_options()
	*/
?>