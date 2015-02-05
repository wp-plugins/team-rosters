<?php
/*----------------------------------------------------------------------------
 * mstw-tr-player-profiles-galleries-settings.php
 *	All functions for the MSTW Team Rosters Plugin's player profiles and
 *  player gallery settings.
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
	if( !function_exists( 'mstw_tr_bio_gallery_setup' ) ) {
		function mstw_tr_bio_gallery_setup( ) {
			mstw_tr_bio_gallery_section_setup( );
		}
	} //End: mstw_tr_bio_gallery_setup()
	
	if( !function_exists( 'mstw_tr_bio_gallery_section_setup' ) ) {
		function mstw_tr_bio_gallery_section_setup( ) {		
			// Roster Table data fields/columns -- show/hide and labels
			$display_on_page = 'mstw-tr-bio-gallery';
			$page_section = 'mstw-tr-bio-gallery_settings';
			
			$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_bio_gallery_defaults( ) );
			
			mstw_log_msg( 'in mstw_tr_bio_gallery_section_setup ... ' );
			mstw_log_msg( $options );
			//mstw_log_msg( 'in mstw_tr_data_fields_section_setup ...' );
			//mstw_log_msg( '$display_on_page= ' . $display_on_page );
			//mstw_log_msg( '$page_section= ' . $page_section );
			//mstw_log_msg( '$options= ' );
			//mstw_log_msg( $options );
			
			
			add_settings_section(
				$page_section,
				__( 'Player Profile & Gallery Settings', 'mstw-team-rosters' ),
				'mstw_tr_bio_gallery_inst',
				$display_on_page
				);
			
			//return;

			$arguments = array(
				array( 	// Title for BIO CONTENT
					'type' => 'text', 
					'id' => 'sp_content_title',
					'name'	=> 'mstw_tr_options[sp_content_title]',
					'value' => mstw_safe_ref( $options, 'sp_content_title' ),
					'title'	=> __( 'Player Profile (Bio) Title:', 'mstw-team-rosters' ),
					'desc'	=> __( 'Label content in the player "Bio". (Default: Bio)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// PLAYER PHOTO WIDTH
					'type' => 'text', 
					'id' => 'sp_image_width',
					'name'	=> 'mstw_tr_options[sp_image_width]',
					'value' => mstw_safe_ref( $options, 'sp_image_width' ),
					'title'	=> __( 'Player Photo Width:', 'mstw-team-rosters' ),
					'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 150px out of the box.)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// PLAYER PHOTO HEIGHT
					'type' => 'text', 
					'id' => 'sp_image_height',
					'name'	=> 'mstw_tr_options[sp_image_height]',
					'value' => mstw_safe_ref( $options, 'sp_image_height' ),
					'title'	=> __( 'Player Photo Height:', 'mstw-team-rosters' ),
					'desc'	=> __( 'In pixels. (Defaults to blank, which means the stylesheet setting will be used; 150px out of the box.)', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// MAIN BOX BACKGROUND COLOR
					'type' => 'text', 
					'id' => 'sp_main_bkgd_color',
					'name'	=> 'mstw_tr_options[sp_main_bkgd_color]',
					'value' => mstw_safe_ref( $options, 'sp_main_bkgd_color' ), 
					'title' => __( 'Main Box Background Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// MAIN BOX TEXT COLOR
					'type' => 'text', 
					'id' => 'sp_main_text_color',
					'name'	=> 'mstw_tr_options[sp_main_text_color]',
					'value' => mstw_safe_ref( $options, 'sp_main_text_color' ),
					'title'	=> __( 'Main Box Text Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// PROFILE BORDER COLOR
					'type' => 'text', 
					'id' => 'sp_bio_border_color',
					'name'	=> 'mstw_tr_options[sp_bio_border_color]',
					'value' => mstw_safe_ref( $options, 'sp_bio_border_color' ), 
					'title' => __( 'Player Profile(Bio) Border Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// HEADER COLOR OF PLAYER PROFILE BOX
					'type' => 'text', 
					'id' => 'sp_bio_header_color',
					'name'	=> 'mstw_tr_options[sp_bio_header_color]',
					'value' => mstw_safe_ref( $options, 'sp_bio_header_color' ),
					'title'	=> __( 'Player Bio Header Text Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// TEXT COLOR OF PLAYER PROFILE BOX
					'type' => 'text', 
					'id' => 'sp_bio_text_color',
					'name'	=> 'mstw_tr_options[sp_bio_text_color]',
					'value' => mstw_safe_ref( $options, 'sp_bio_text_color' ), 
					'title' => __( 'Player Bio Text Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// BACKGROUND COLOR OF PLAYER PROFILE BOX
					'type' => 'text', 
					'id' => 'sp_bio_bkgd_color',
					'name'	=> 'mstw_tr_options[sp_bio_bkgd_color]',
					'value' => mstw_safe_ref( $options, 'sp_bio_bkgd_color' ),
					'title'	=> __( 'Player Bio Background Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// GALLERY LINKS COLOR
					'type' => 'text', 
					'id' => 'gallery_links_color',
					'name'	=> 'mstw_tr_options[gallery_links_color]',
					'value' => mstw_safe_ref( $options, 'gallery_links_color' ), 
					'title' => __( 'Player Gallery Links Color:', 'mstw-team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			);
			
			mstw_build_settings_screen( $arguments );
			
		} //End: mstw_tr_bio_gallery_section_setup()
	} 
	
	//-----------------------------------------------------------------	
	// 	Player Bio and Gallery section instructions	
	//	
	if( !function_exists( 'mstw_tr_bio_gallery_inst' ) ) {
		function mstw_tr_bio_gallery_inst( ) {
			echo '<p>' . __( 'Unless otherwise noted, these settings will apply to both the Single Player Profile and Team Gallery pages. ', 'mstw-team-rosters' ) .'</p>';
		} //End: mstw_tr_bio_gallery_inst()
	}
?>