<?php
/*----------------------------------------------------------------------------
 * mstw-tr-player-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_tr_player custom post type.
 *	It is loaded conditioned on admin_init hook in mstw-tr-admin.php 
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
 *--------------------------------------------------------------------------*/
 
 //-----------------------------------------------------------------
 // Move the meta box ahead of the content (player bio)
 //
 add_action('edit_form_after_title', 'mstw_tr_build_player_screen' );

 if( !function_exists( 'mstw_tr_build_player_screen' ) ) {
	function mstw_tr_build_player_screen( ) {
		global $post, $wp_meta_boxes;
		
		// first make sure we're on the right screen ...
		if( get_post_type( $post ) == 'mstw_tr_player' ) {
			do_meta_boxes(get_current_screen( ), 'advanced', $post);
			unset( $wp_meta_boxes[get_post_type($post)]['advanced'] );
			echo "<p class='player-bio-admin-head'>" . __( 'Player Bio:', 'mstw-team-rosters' ) . "</p>";
		}
	}  //End: mstw_tr_build_player_screen
 }
 
 add_action( 'do_meta_boxes', 'mstw_tr_change_featured_image_box');
 
 if( !function_exists( 'mstw_tr_change_featured_image_box' ) ) {
	 function mstw_tr_change_featured_image_box( ) {
		remove_meta_box( 'postimagediv', 'mstw_tr_player', 'side' );
		add_meta_box( 'postimagediv', __( 'Player Photo', 'mstw-team-rosters' ), 'post_thumbnail_meta_box', 'mstw_tr_player', 'side', 'default' );
	 } //End: mstw_tr_change_featured_image_box( ) 
 }
 
 add_action( 'admin_head-post-new.php', 'mstw_tr_set_featured_image_text_filter' );
 add_action( 'admin_head-post.php', 'mstw_tr_set_featured_image_text_filter' );
 
 if( !function_exists( 'mstw_tr_set_featured_image_text_filter' ) ) {
	function mstw_tr_set_featured_image_text_filter( ) {
		$screen = get_current_screen( );
		
		//if( isset( $GLOBALS['post_type'] ) && $GLOBALS['post_type'] == 'mstw_tr_player' ) {
			add_filter( 'admin_post_thumbnail_html', 'mstw_tr_change_featured_image_link' );
		//}
		 
	} //End: mstw_tr_set_featured_image_text_filter( )
 }
 
 //add_filter( 'admin_post_thumbnail_html', 'mstw_tr_change_featured_image_link' );
 
  if( !function_exists( 'mstw_tr_change_featured_image_link' ) ) {
	 function mstw_tr_change_featured_image_link( $content ) {
		if ( get_post_type( ) == 'mstw_tr_player' ) {
			$content = str_replace( __( 'Set featured image' ), __( 'Set Player Photo', 'mstw-team-rosters' ), $content );
			
			$content = str_replace( __( 'Remove featured image' ), __( 'Remove Player Photo', 'mstw-team-rosters' ), $content );
		}
		return $content;
		
	 } //End: mstw_tr_change_featured_image_link( ) 
 }
 
 //-----------------------------------------------------------------
 // Add the meta box for the mstw_tr_player custom post type
 //
 add_action( 'add_meta_boxes_mstw_tr_player', 'mstw_tr_player_metaboxes' );
 if( !function_exists( 'mstw_tr_player_metaboxes' ) ) {
	function mstw_tr_player_metaboxes( ) {
			
		add_meta_box(	'mstw-tr-player-meta', 
						__('Player Data', 'mstw-team-rosters'), 
						'mstw_tr_create_player_screen', 
						'mstw_tr_player', 
						'advanced', 
						'high' );	
						
	} //End: mstw_tr_player_metaboxes( )
 }
 
 //-----------------------------------------------------------------
 // Build the meta box (controls) for the MSTW_TR_PLAYER CPT
 //
 if( !function_exists( 'mstw_tr_create_player_screen' ) ) {
	function mstw_tr_create_player_screen( $post ) {
		
		$std_length = 128; //max length of text fields
		$std_size = 32;    //size of text box on edit screen
		
		// Want the settings for the field labels, which may be changed
		$options = get_option( 'mstw_tr_options' );
	
		wp_nonce_field( plugins_url(__FILE__), 'mstw_tr_player_nonce' );
		
		$bats_list = array( 	__( '----', 'mstw-team-rosters' ) => 0, 
								__( 'R', 'mstw-team-rosters' ) 	=> 1,
								__( 'L', 'mstw-team-rosters' ) 	=> 2,
								__( 'B', 'mstw-team-rosters' ) 	=> 3, 
							);
							
		$throws_list = array( 	__( '----', 'mstw-team-rosters' ) => 0, 
								__( 'R', 'mstw-team-rosters' ) 	=> 1,
								__( 'L', 'mstw-team-rosters' ) 	=> 2, 
							);
		
		// Retrieve the metadata values if they exist
		// The first set are used in all formats
		//$first_name = get_post_meta( $post->ID, 'player_first_name', true );
		//$last_name  = get_post_meta( $post->ID, 'player_last_name', true );
		$number = get_post_meta( $post->ID, 'player_number', true );
		$height = get_post_meta( $post->ID, 'player_height', true );
		$weight = get_post_meta( $post->ID, 'player_weight', true );
		$position = get_post_meta( $post->ID, 'player_position', true );
		
		// year is used in the high-school and college formats
		$year = get_post_meta( $post->ID, 'player_year', true );
		
		// experience is used in the college and pro formats
		$experience = get_post_meta( $post->ID, 'player_experience', true );
		
		// age is used in the pro format only
		$age = get_post_meta( $post->ID, 'player_age', true );
		
		// home_town is used in the college format only
		$home_town = get_post_meta( $post->ID, 'player_home_town', true );
		
		// last_school is used in the college and pro formats
		$last_school = get_post_meta( $post->ID, 'player_last_school', true );
		
		// country is used in the pro format only
		$country = get_post_meta( $post->ID, 'player_country', true );
		
		// used in the baseball formats only
		$bats = get_post_meta( $post->ID, 'player_bats', true );
		$throws = get_post_meta( $post->ID, 'player_throws', true );
		
		// other info - this is a free-for-all spare
		$other = get_post_meta( $post->ID, 'player_other', true );
		
		// player photo - can upload from media library
		$player_photo = get_post_meta( $post->ID, 'player_photo', true );
		?>
		
		<table class="form-table">
		
		<?php
		$admin_fields = array ( 
						'player_first_name' => array (
							'type' 			=> 'text',
							'curr_value' 	=> get_post_meta( $post->ID, 'player_first_name', true ), //$first_name,
							'label' 		=> __( 'First Name:', 'mstw-team-rosters' ),
							'maxlength' 	=> $std_length,
							'size' 			=> $std_size,
							'desc' 			=> __( '', 'mstw-team-rosters' ),
							),
						'player_last_name' => array (
							'type' => 'text',
							'curr_value' => get_post_meta( $post->ID, 'player_last_name', true ),
							'label' =>  __( 'Last Name:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),
						'player_number' => array (
							'type' => 'text',
							'curr_value' => $number,
							'label' =>  __( 'Number:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),	
						'player_position' => array (
							'type' => 'text',
							'curr_value' => $position,
							'label' =>  __( 'Position:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),
						'player_height' => array (
							'type' => 'text',
							'curr_value' => $height,
							'label' =>  __( 'Height:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),
						'player_weight' => array (
							'type' => 'text',
							'curr_value' => $weight,
							'label' =>  __( 'Weight:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),
						'player_year' => array (
							'type' => 'text',
							'curr_value' => $year,
							'label' =>  __( 'Year:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended for the player\'s year in school, for example, "Freshman", "10" (10th grade), or "Redshirt Soph". It can be changed by changing the Year Label in the settings screen.', 'mstw-team-rosters' ),
							),
						'player_experience' => array (
							'type' => 'text',
							'curr_value' => $experience,
							'label' =>  __( 'Experience:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended to be the player\'s experience, for example, "3 years" or "2V" (2 varsity seasons). It can be changed by changing the Experience Label in the settings screen.', 'mstw-team-rosters' ),
							),
						'player_age' => array (
							'type' => 'text',
							'curr_value' => $age,
							'label' =>  __( 'Age:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( '', 'mstw-team-rosters' ),
							),
						'player_home_town' => array (
							'type' => 'text',
							'curr_value' => $home_town,
							'label' =>  __( 'Home Town:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is typically combined with the last school in US college and high school rosters.', 'mstw-team-rosters' ),
							),
						'player_last_school' => array (
							'type' => 'text',
							'curr_value' => $last_school,
							'label' =>  __( 'Last School:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is typically combined with the home town in US college and high school rosters. It could be changed to "last team" in international/pro rosters.', 'mstw-team-rosters' ),
							),
						'player_country' => array (
							'type' => 'text',
							'curr_value' => $country,
							'label' =>  __( 'Country:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is intended for international teams, but it could changed to "State" for US college teams.', 'mstw-team-rosters' ),
							),
						'player_bats' => array (
							'type' => 'select-option',
							'options' => $bats_list,
							'curr_value' => $bats,
							'label' =>  __( 'Bats:', 'mstw-team-rosters' ),
							//'maxlength' => $std_length,
							//'size' => $std_size,
							'desc' => __( 'This is a baseball specific field, but it could be used for cricket, say. It is combined with the "Throws" field in baseball specific formats.', 'mstw-team-rosters' ),
							),
						'player_throws' => array (
							'type' => 'select-option',
							'options' => $throws_list,
							'curr_value' => $throws,
							'label' =>  __( 'Throws:', 'mstw-team-rosters' ),
							//'maxlength' => $std_length,
							//'size' => $std_size,
							'desc' => __( 'This is a baseball specific field. It is combined with the "Bats" field in baseball specific formats.', 'mstw-team-rosters' ),
							),
						'player_other' => array (
							'type' => 'text',
							'curr_value' => $other,
							'label' =>  __( 'Other Info:', 'mstw-team-rosters' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							'desc' => __( 'This field is a spare. It is intended to be re-purposed by site admins.', 'mstw-team-rosters' ),
							),
						/*
						'player_photo' => array (
								'type'	=> 'media-uploader',
								//'type' => 'text',
								'curr_value' => $player_photo,
								'label' => __( 'Player Photo:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'Enter the full path to any file, or click the button to access the media library. Recommended size 41x28px.', 'mstw-schedules-scoreboards' ),
								'btn_label' => __( 'Upload from Media Library', 'mstw-schedules-scoreboards' ),
								'img_width' => 41,
								),
							*/

						);
			mstw_build_admin_edit_screen( $admin_fields );
		?>
		</table>
		
		<?php
	} //End: mstw_tr_create_player_screen()
 }

 //-----------------------------------------------------------------
 // SAVE THE MSTW_TR_PLAYER CPT META DATA
 //
 add_action( 'save_post_mstw_tr_player', 'mstw_tr_save_player_meta', 20, 2 );
 
 if( !function_exists( 'mstw_tr_save_player_meta' ) ) {
	function mstw_tr_save_player_meta( $post_id ) {
		
		//mstw_log_msg( 'in mstw_tr_save_player_meta ...' );
		
		//
		//Just return on an autosave
		//
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
			
		//	
		// check that the post type is 'mstw_tr_player', if so, process the data
		//
		if( isset( $_POST['post_type'] ) ) {		
			if ( $_POST['post_type'] == 'mstw_tr_player' ) {
				update_post_meta( $post_id, 'player_first_name', 
						strip_tags( $_POST['player_first_name'] ) );
						
				update_post_meta( $post_id, 'player_last_name', 
						strip_tags( $_POST['player_last_name'] ) );
						
				update_post_meta( $post_id, 'player_number', 
						strip_tags( $_POST['player_number'] ) );
						
				update_post_meta( $post_id, 'player_position', 
						strip_tags( $_POST['player_position'] ) );		
						
				update_post_meta( $post_id, 'player_height', 
						strip_tags( $_POST['player_height'] ) );
						
				update_post_meta( $post_id, 'player_weight',  
						strip_tags( $_POST['player_weight'] ) );
						
				update_post_meta( $post_id, 'player_year',  
						strip_tags( $_POST['player_year'] ) );
						
				update_post_meta( $post_id, 'player_experience',
						strip_tags( $_POST['player_experience'] ) );
				
				update_post_meta( $post_id, 'player_age', 
						strip_tags( $_POST['player_age'] ) );
						
				update_post_meta( $post_id, 'player_home_town',
						strip_tags( $_POST['player_home_town'] ) );
						
				update_post_meta( $post_id, 'player_last_school',
						strip_tags( $_POST['player_last_school'] ) );
						
				update_post_meta( $post_id, 'player_country',
						strip_tags( $_POST['player_country'] ) );
						
				update_post_meta( $post_id, 'player_bats',
						strip_tags( $_POST['player_bats'] ) );
						
				update_post_meta( $post_id, 'player_throws',
						strip_tags( $_POST['player_throws'] ) );
						
				update_post_meta( $post_id, 'player_other',
						strip_tags( $_POST['player_other'] ) );
						
			} //End: if ( $_POST['post_type'] == 'mstw_tr_player' )
		} //End: if( isset( $_POST['post_type'] ) )
	} //End: function mstw_tr_save_player_meta
 }
 
 // ----------------------------------------------------------------
 // Set up the Team Roster 'view all' columns
 //
 add_filter( 'manage_edit-mstw_tr_player_columns',
			 'mstw_tr_edit_player_columns' ) ;
 
 if( !function_exists( 'mstw_tr_edit_player_columns' ) ) {
	function mstw_tr_edit_player_columns( $columns ) {
		
		$options = wp_parse_args( (array)get_option( 'mstw_tr_options' ), mstw_tr_get_data_fields_columns_defaults( ) );

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => $options['name_label'], //__( 'Player', 'mstw-team-rosters' ),
			
			'first-name' => __( 'First Name', 'mstw-team-rosters' ),
			'last-name' => __( 'Last Name', 'mstw-team-rosters' ),
			'number' => $options['number_label'], //__( 'Number', 'mstw-team-rosters' ),
			'position' => $options['position_label'], //__( 'Position', 'mstw-team-rosters' ),
			'height' => $options['height_label'], //__( 'Height', 'mstw-team-rosters' ),
			'weight' => $options['weight_label'], //__( 'Weight', 'mstw-team-rosters' ),
			'year' => $options['year_label'], //__( 'Year', 'mstw-team-rosters' ),
			'experience' => $options['experience_label'], //__( 'Experience', 'mstw-team-rosters' )
			'team' => __( 'Team(s)', 'mstw-team-rosters' ),
		);

		return $columns;
	} //End: mstw_tr_edit_player_columns( )
 }

 // ----------------------------------------------------------------
 // Display the View All Players table columns
 //
 add_action( 'manage_mstw_tr_player_posts_custom_column', 
			 'mstw_tr_manage_player_columns', 10, 2 );
 
 if( !function_exists( 'mstw_tr_manage_player_columns' ) ) {
	function mstw_tr_manage_player_columns( $column, $post_id ) {
		global $post;
		
		//mstw_log_msg( 'column: ' . $column . " Post ID: " . $post_id );

		switch( $column ) {
			case 'team' :
				$taxonomy = 'mstw_tr_team';
				
				$edit_link = site_url( '/wp-admin/', null ) . 'edit-tags.php?taxonomy=mstw_tr_team&post_type=mstw_tr_player';
				
				$teams = get_the_terms( $post_id, $taxonomy );
				if ( is_array( $teams) ) {
					foreach( $teams as $key => $team ) {
						$teams[$key] =  '<a href="' . $edit_link . '">' . $team->name . '</a>';
					}
						echo implode( ' | ', $teams );
				}
				break;
				
			case 'first-name' :
				//printf( '%s', get_post_meta( $post_id, 'player_first_name', true ) );
				echo( get_post_meta( $post_id, 'player_first_name', true ) );
				break;
				
			case 'last-name' :
				printf( '%s', get_post_meta( $post_id, 'player_last_name', true ) );
				break;
			
			case 'number' :
				printf( '%s', get_post_meta( $post_id, 'player_number', true ) );
				break;
					
			case 'position' :
				printf( '%s', get_post_meta( $post_id, 'player_position', true ) );
				break;

			case 'height' :
				printf( '%s', get_post_meta( $post_id, 'player_height', true ) );
				break;
				
			case 'weight' :
				printf( '%s', get_post_meta( $post_id, 'player_weight', true ) );
				break;

			case 'year' :
				printf( '%s', get_post_meta( $post_id, 'player_year', true ) );
				break;
				
			case 'experience' :
				printf( '%s', get_post_meta( $post_id, 'player_experience', true ) );
				break;
				
			/* Just break out of the switch statement for everything else. */
			default :
				break;
				
		} 
	} //End: mstw_tr_manage_player_columns( )
 }
	
 // ----------------------------------------------------------------
 // Sort the all players table on first name, last name, number, team(s)
 //
 add_filter( 'manage_edit-mstw_tr_player_sortable_columns', 
			 'mstw_tr_players_columns_sort');

 if( !function_exists( 'mstw_tr_players_columns_sort' ) ) {
	function mstw_tr_players_columns_sort( $columns ) {
		
		$custom = array(
			'first-name' => 'player_first_name',
			'last-name' 	=> 'player_last_name',
			'number' 	=> 'player_number',
		);
		
		return wp_parse_args( $custom, $columns );
		
	} //End: mstw_tr_players_columns_sort( )
 } 
 
 // ----------------------------------------------------------------
 // Filter the All Players screen based on Team (mstw_tr_team) taxonomy
 //
 add_action( 'restrict_manage_posts','mstw_tr_restrict_players_by_team' );

 if( !function_exists( 'mstw_tr_restrict_players_by_team' ) ) {
	function mstw_tr_restrict_players_by_team( ) {
		global $typenow;
		//global $wp_query;
		
		if( $typenow == 'mstw_tr_player' ) {
			
			$taxonomy_slugs = array( 'mstw_tr_team' );
			
			foreach ( $taxonomy_slugs as $tax_slug ) {
				//retrieve the taxonomy object for the tax_slug
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				
				$terms = get_terms( $tax_slug );
					
				//output the html for the drop down menu
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>" . __( 'Show All Teams', 'mstw-team-rosters') . "</option>";
				
				//output each select option line
				foreach ($terms as $term) {
					//check against the last $_GET to show the current selection
					if ( array_key_exists( $tax_slug, $_GET ) ) {
						$selected = ( $_GET[$tax_slug] == $term->slug ) ? ' selected="selected"' : '';
					}
					else {
						$selected = '';
					}
					echo '<option value=' . $term->slug . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo '</select>';
			}	
		}
	} //End: mstw_tr_restrict_players_by_team( )
 }
 
 //-----------------------------------------------------------------
 // Sort show all players by columns. See:
 // http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
 //
 add_filter( 'request', 'mstw_ss_players_column_order' );

 if( !function_exists( 'mstw_ss_players_column_order' ) ) {
	function mstw_ss_players_column_order( $vars ) {
		if ( isset( $vars['orderby'] ) ) {
			mstw_log_msg( 'in ... mstw_ss_players_column_order' . $vars['orderby'] );
			$custom = array();
			switch( $vars['orderby'] ) {
				case'player_number':
					$custom = array( 'meta_key' => 'player_number',
									 'orderby' => 'meta_value_num',
									 );
					//$vars = array_merge( $vars, $custom );
					break;
				case 'player_first_name':
					$custom = array( 'meta_key' => 'player_first_name',
										 //'orderby' => 'meta_value_num', // does not work
										 'orderby' => 'meta_value'
										 //'order' => 'asc' // don't use this; blocks toggle UI
										);
					//$vars = array_merge( $vars, $custom );
					break;
				case 'player_last_name':
					$custom = array( 'meta_key' => 'player_last_name',
										 //'orderby' => 'meta_value_num', // does not work
										 'orderby' => 'meta_value'
										 //'order' => 'asc' // don't use this; blocks toggle UI
										);
					//$vars = array_merge( $vars, $custom );
					break;
			}
			if( $custom ) 
				$vars = array_merge( $vars, $custom );
		}
		
		return $vars;
		
	} //End mstw_ss_players_column_order( )
 }
 ?>