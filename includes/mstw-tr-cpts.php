<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-cpts.php
 *		Registers the custom post type & taxonomy for MSTW Team Rosters
 *		mstw_tr_player, mstw_tr_team
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 
// ----------------------------------------------------------------
// Register the MSTW Team Rosters Custom Post Types & Taxonomies
// 		mstw_tr_player, mstw_tr_team
//
function mstw_tr_register_cpts( ) {
	
	//$menu_icon_url = plugin_dir_path( dirname( __FILE__ ) ) .  'images/mstw-admin-menu-icon.png';
	//mstw_log_msg( 'in mstw_tr_register_cpts ... menu_icon_url = ' . $menu_icon_url );
	$menu_icon_url = plugins_url( 'images/mstw-admin-menu-icon.png', dirname( __FILE__ ) );
	//mstw_log_msg( 'in mstw_tr_register_cpts ... menu_icon_url = ' . $menu_icon_url );
	//mstw_log_msg( '$menu_icon_url= ' . $menu_icon_url );
	
	//Gotta figure out the capability stuff
	
	// show ui (or not) based on user's capability
		
	// filter default capability so developers can modify
	//$capability = apply_filters( 'mstw-tr-user_capability', 
	//							 'edit_others_posts', 
	//							 'game_schedules_menu' 
	//							);
	
	$capability = 'read';
		
	// if filter returns the empty string, someone screwed up; 
	// use edit_others_posts as default (editor role)

	//if ( $capability == '' )
	//	$capability = 'read';
		
	// set show_ui based on user and capability
	//$show_ui = ( current_user_can( $capability ) == true ? true : false );
	
	

	//-----------------------------------------------------------------------
	// register mstw_tr_player custom post type
	//
	$args = array(
		'label'				=> __( 'Players', 'mstw-team-rosters' ),
		'description'		=> __( 'CPT for Players in MSTW Team Rosters Plugin', 'mstw-team-rosters' ),
		
		'public' 			=> true,
		'exclude_from_search'	=> true, //default is opposite value of public
		'publicly_queryable'	=> true, //default is value of public
		'show_ui'			=> true, //default is value of public
		'show_in_nav_menus'	=> false, //default is value of public
		//going to build own admin menu
		'show_in_menu'		=> false, //default is value of show_ui
		'show_in_admin_bar' => false, //default is value of show_in_menu
		//only applies if show_in_menu is true
		//'menu_position'		=> 25, //25 is below comments, which is the default
		'menu_icon'     	=> null, //$menu_icon_url,
		
		//'capability_type'	=> 'post' //post is the default
		//'capabilities'		=> null, //array default is constructed from capability_type
		//'map_meta_cap'	=> null, //null is the default
		
		//'hierarchical'	=> false, //false is the default
		
		'rewrite' 			=> array(
			'slug' 			=> 'player',
			'with_front' 	=> false,
		),
		
		'supports' 			=> array( 'title', 'editor', 'thumbnail' ),
		
		//post is the default capability type
		//'capability_type'	=> array( 'player', 'players' ), 
		
		//'map_meta_cap' 		=> true,
									
		//'register_meta_box_cb'	=> no default for this one
		
		'taxonomies' => 	array( 'mstw_tr_team' ),
		
		// Note that is interacts with exclude_from_search
		//'has_archive'		=> false, //false is the default
		
		'query_var' 		=> true, //post_type is default mstw_tr_player
		'can_export'		=> true, //default is true
		
		'labels' 			=> array(
									'name' => __( 'Players', 'mstw-team-rosters' ),
									'singular_name' => __( 'Player', 'mstw-team-rosters' ),
									'all_items' => __( 'Players', 'mstw-team-rosters' ),
									'add_new' => __( 'Add New Player', 'mstw-team-rosters' ),
									'add_new_item' => __( 'Add Player', 'mstw-team-rosters' ),
									'edit_item' => __( 'Edit Player', 'mstw-team-rosters' ),
									'new_item' => __( 'New Player', 'mstw-team-rosters' ),
									//'View Player' needs a custom page template that is of no value. ???
									'view_item' => __( 'View Player', 'mstw-team-rosters' ),
									'search_items' => __( 'Search Players', 'mstw-team-rosters' ),
									'not_found' => __( 'No Players Found', 'mstw-team-rosters' ),
									'not_found_in_trash' => __( 'No Players Found In Trash', 'mstw-team-rosters' ),
									)
		);
		
	register_post_type( 'mstw_tr_player', $args);
	
	//
	// Register the team taxonomy ... acts like a tag
	//
	$labels = array( 
				'name' => __( 'MSTW Teams', 'mstw-team-rosters' ),
				'singular_name' =>  __( 'Team', 'mstw-team-rosters' ),
				'search_items' => __( 'Search Teams', 'mstw-team-rosters' ),
				'popular_items' => null, //removes tagcloud __( 'Popular Teams', 'mstw-team-rosters' ),
				'all_items' => __( 'All Teams', 'mstw-team-rosters' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Team', 'mstw-team-rosters' ), 
				'update_item' => __( 'Update Team', 'mstw-team-rosters' ),
				'add_new_item' => __( 'Add New Team', 'mstw-team-rosters' ),
				'new_item_name' => __( 'New Team Name', 'mstw-team-rosters' ),
				'separate_items_with_commas' => __( 'Add game to one or more scoreboards (separate scoreboards with commas).', 'mstw-team-rosters' ),
				'add_or_remove_items' => __( 'Add or Remove Teams', 'mstw-team-rosters' ),
				'choose_from_most_used' => __( 'Choose from the most used scoreboards', 'mstw-team-rosters' ),
				'not_found' => __( 'No Teams Found', 'mstw-team-rosters' ),
				'menu_name'  => __( 'Teams', 'mstw-team-rosters' ),
			  );
			  
	$args = array( 
			//'label'				=> 'MSTW Teams', //overridden by $labels->name
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_tagcloud'			=> false,
			//'meta_box_cb'			=> null, provide callback fcn for meta box display
			'show_admin_column'		=> true, //allow automatic creation of taxonomy column in associated post-types table.
			'hierarchical' 			=> false, //behave like tags
			//'update_count_callback'	=> '',
			'query_var' 			=> true, 
			'rewrite' 				=> true,
			//'capabilities'			=> array( ),
			//'sort'					=> null,
		);
		
	register_taxonomy( 'mstw_tr_team', 'mstw_tr_player', $args );
	register_taxonomy_for_object_type( 'mstw_tr_team', 'mstw_tr_player' );
	

} //End: mstw_tr_register_cpts( )
?>