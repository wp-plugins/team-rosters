<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-roster-gallery.php
 *		Code for the mstw-roster-gallery shortcode
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2015 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *-------------------------------------------------------------------------*/
// --------------------------------------------------------------------------------------
// Add the gallery shortcode handler, which will create the a Team Gallery on the user side.
// Handles the shortcode parameters, if there were any, 
// then calls mstw_tr_build_gallery( ) to create the output
// --------------------------------------------------------------------------------------
//add_shortcode( 'mstw_roster_gallery', 'mstw_tr_roster_gallery_handler' );

 if( !function_exists( 'mstw_tr_roster_gallery_handler' ) ) {
	function mstw_tr_roster_gallery_handler( $atts ) {
		
		//mstw_log_msg( 'in mstw_tr_roster_gallery_handler ... ' );

		// get the options set in the admin screen
		$options = get_option( 'mstw_tr_options' );
		
		// Remove all keys with empty values
		/*
		foreach ( $options as $k=>$v ) {
			if( $v == '' ) {
				unset( $options[$k] );
			}
		}
		*/
		
		// and merge them with the defaults
		$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		
		// then merge the parameters passed to the shortcode with the result									
		$attribs = shortcode_atts( $args, $atts );
		
		$format_settings = mstw_tr_set_fields_by_format( $attribs['roster_type'] );
		$test_attribs = wp_parse_args( $attribs, $format_settings );
		//mstw_log_msg( '$test_attribs:' );
		//mstw_log_msg( $test_attribs );
		
		$attribs = mstw_tr_set_fields( $attribs['roster_type'], $attribs );
		mstw_log_msg( '$test_attribs vs. $attribs:' );
		mstw_log_msg( array_diff( $attribs, $test_attribs ) );
		
		//get the team slug
		if ( $attribs['team'] == 'no-team-specified' )
			return '<h3>No Team Specified </h3>';
		else
			$team_slug = $attribs['team'];
			
		// Set the sort order	
		switch ( $attribs['sort_order'] ) {
			case'numeric':
				$sort_key = 'player_number';
				$order_by = 'meta_value_num';
				break;
			case 'alpha-first':
				$sort_key = 'player_first_name';
				$order_by = 'meta_value';
				break;
			default: // alpha by last
				$sort_key = 'player_last_name';
				$order_by = 'meta_value';
				break;
		}
		
		// Get the posts		
		$posts = get_posts(array( 'numberposts' => -1,
								  'post_type' => 'mstw_tr_player',
								  'mstw_tr_team' => $team_slug, 
								  'orderby' => $order_by, 
								  'meta_key' => $sort_key,
								  'order' => 'ASC' 
								));		
		
		//Now gotta grab the posts
		
		$mstw_tr_gallery = mstw_tr_build_gallery( $team_slug, $posts, $attribs, $attribs['roster_type'] );
		
		return $mstw_tr_gallery;
	}
 }
?>