<?php
/*----------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS.PHP
 *	mstr_tr_set_options() - returns the default option settings
 *	mstw_tr_set_fields_by_format() - sets options based on specified format
 *	
 *---------------------------------------------------------*/

/*---------------------------------------------------------------------------------
 *	mstw_tr_set_options_by_format: Sets the options based on the specified format
 *		high-school, college, pro, baseball-high-school, 
 *		baseball-college, or baseball-pro. "custom" caused no change 
 *		to the admin options. The defaults specified on the admin page are used
 *-------------------------------------------------------------------------------*/

 function mstw_tr_set_fields_by_format( $format ) {
		switch ( $format) {
			case 'baseball-high-school':
			case 'baseball-college':
			case 'baseball-pro':
				$show_bats_throws = 1;
			break;
			default:
				$show_bats_throws = 0;
				break;
		}

		switch ( $format ) {
			case 'baseball-high-school':
			case 'high-school':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 0,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 0,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				break;
				
			case 'baseball-college':
			case 'college':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 1,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 0,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 1,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 0,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);		
				break;
			
			case 'pro':
			case 'baseball-pro':
				$settings = array(	
					//'team'					=> 'no-team-specified',
					'roster_type'			=> $format,
					//'show_title'			=> 1,
					//'sort_order'			=> 'alpha',
					//'name_format'			=> 'last-first',
					//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
					'show_number'			=> 1,
					//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
					'show_position'			=> 1,
					'show_height'			=> 1,
					//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
					//'show_weight'			=> 1,
					//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
					'show_year'				=> 0,
					//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
					'show_experience'		=> 1,
					//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
					'show_age'				=> 1,
					//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
					'show_home_town'		=> 0,
					//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
					'show_last_school'		=> 1,
					//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
					'show_country'			=> 1,
					//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
					'show_bats_throws'		=> $show_bats_throws,
					//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
					'show_other_info'		=> 0,
					//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				break;
				
			default:  // custom roster format
				$settings = get_option( 'mstw_tr_options' );
				break;
		}
		return $settings;
	}

/*---------------------------------------------------------------------------------
 *	mstw_tr_get_defaults: returns the array of option defaults
 *-------------------------------------------------------------------------------*/	
		function mstw_tr_get_defaults( ) {
		//Base defaults
		$defaults = array(	
				'team'					=> 'no-team-specified',
				'show_title'			=> 1,
				'roster_type'			=> 'custom',
				//'use_player_links'		=> 0, removed in 3.1
				//'use_gallery_links'		=> 0, removed in 3.1
				'use_pg_links'			=> 0,
				'sort_order'			=> 'alpha',
				'name_format'			=> 'last-first',
				'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'mstw-loc-domain' ),
				'show_position'			=> 1,
				'position_label'		=> __( 'Pos', 'mstw-loc-domain' ),
				'show_height'			=> 1,
				'height_label'			=> __( 'Hgt', 'mstw-loc-domain' ),
				'show_weight'			=> 1,
				'weight_label'			=> __( 'Wgt', 'mstw-loc-domain' ),
				'show_year'				=> 0,
				'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
				'show_experience'		=> 0,
				'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
				'show_age'				=> 0,
				'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
				'show_home_town'		=> 0,
				'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
				'show_last_school'		=> 0,
				'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
				'show_country'			=> 0,
				'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
				'show_bats_throws'		=> 0,
				'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
				'show_other_info'		=> 0,
				'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
				);
				
		return $defaults;
	}
	?>
