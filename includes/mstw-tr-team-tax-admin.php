<?php
/*---------------------------------------------------------------------------
 *	mstw-tr-team-tax-admin.php
 *		Adds data fields to the default taxonomy window
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

 
 // ----------------------------------------------------------------
 // Remove the row actions
 //	
 add_filter( 'mstw_tr_team_row_actions', 'mstw_tr_team_row_actions' ); //, 10, 2 );

if( !function_exists( 'mstw_tr_team_row_actions' ) ) {
	function mstw_tr_team_row_actions( $actions ) { //, $post ) {
		//mstw_log_msg( 'in mstw_tr_team_row_actions( ) ... ' );
		
		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['view'] );
		//unset( $actions['delete'] );
		//unset( $actions['edit'] );
		
		return $actions;

	} //End: mstw_tr_team_row_actions( )
 }

 //----------------------------------------------------------------------
 // Add MSTW SS team link to team taxonomy add & edit screens
 // 
 add_action( 'mstw_tr_team_add_form_fields', 'mstw_tr_team_add_form', 10, 2 );
 add_action ( 'mstw_tr_team_edit_form_fields', 'mstw_tr_team_edit_form', 10, 2 );

 if( !function_exists( 'mstw_tr_team_add_form' ) ) {
	function mstw_tr_team_add_form( ) {
		//mstw_log_msg( 'in mstw_tr_team_add_form_fields( ) ... ' );
		
		if( !is_plugin_active( 'mstw-schedules-scoreboards/mstw-schedules-scoreboards.php' ) ) {
		?>
			<div class="form-field">
				<p class="plugin-not-installed"><?php _e( 'Install the MSTW Schedules & Scoreboards plugin to link team rosters to the Teams database in that plugin.' , 'mstw-team-rosters' )?></p>
			</div>
		<?php
		}
		else { 
			//build a list of S&S teams
			if( !function_exists( 'mstw_ss_build_teams_list' ) ) {
			?>
				<div class="form-field">
					<p class="plugin-not-installed"><?php _e( 'Install the lastest version of the MSTW Schedules & Scoreboards plugin to link team rosters to the Teams database in that plugin.' , 'mstw-team-rosters' )?></p>
				</div>
			<?php	
			}
			else {
				?>
				<div class="form-field">
				
				<label for="test"><?php _e( 'Team from MSTW Schedules & Scoreboards' , 'mstw-team-rosters' ) ?></label>
				<?php
				$id = 'tr-ss-team-link';
				$args = array(
							'type'       => 'select-option',
							'id'         => $id,
							'desc'       => __( 'Link team to a team from the MSTW Schedules & Scoreboards Teams DB.', 'mstw-team-rosters' ),
							'title'		 => 'Team from MSTW Schedules & Scoreboards',
							'curr_value' => '',
							'options'    => mstw_ss_build_teams_list( ),
							'label_for'  => $id,
							'class'      => $id,
							'name'		 => $id,
							);
				
				mstw_build_admin_edit_field( $args );
				
				?>
				</div> <!--form-field> -->

			<?php
			}
		}
	} //End: mstw_tr_team_add_form( )
 }
 
 if( !function_exists( 'mstw_tr_team_edit_form' ) ) {
	function mstw_tr_team_edit_form( $team_obj ) {
		//mstw_log_msg( 'in mstw_tr_team_edit_form( $team_obj ) ... ' );
		
		if( !is_plugin_active( 'mstw-schedules-scoreboards/mstw-schedules-scoreboards.php' ) ) {
		?>
			<div class="form-field">
				<p class="plugin-not-installed"><?php _e( 'Install the MSTW Schedules & Scoreboards plugin to link team rosters to the Teams database in that plugin.' , 'mstw-team-rosters' )?></p>
			</div>
		<?php
		}
		else { 
			//build a list of S&S teams
			if( !function_exists( 'mstw_ss_build_teams_list' ) ) {
			?>
				<tr class="form-field">
					<th scope="row">
					<?php _e( 'MSTW Schedules & Scoreboards' , 'mstw-team-rosters' )?>
					</th>
					<td><?php _e( 'Plugin found, but please update to the latest version.' , 'mstw-team-rosters' )?></td>
				</tr>
		<?php	
			}
			
			else {
				//find the current value
				$team_slug = $team_obj->slug; 
				//mstw_log_msg( '$team_slug= ' . $team_slug );
				
				$team_links = get_option( 'mstw_tr_ss_team_links' );
					
				$curr_link = ( $team_links && array_key_exists( $team_slug, $team_links ) ) ? $team_links[$team_slug] : -1;
				
				?>
				<tr class="form-field">
					<th scope="row">
						<label for="tr-ss-team-link"><?php _e( 'Team from MSTW Schedules & Scoreboards' , 'mstw-team-rosters' ) ?></label>
					</th>
					<td>
						<?php
						$id = 'tr-ss-team-link';
						$args = array(
									'type'       => 'select-option',
									'id'         => $id,
									'desc'       => __( 'Link team to a team from the MSTW Schedules & Scoreboards Teams DB.', 'mstw-team-rosters' ),
									'title'		 => '',
									'curr_value' => $curr_link,
									'options'    => mstw_ss_build_teams_list( ),
									'label_for'  => $id,
									'class'      => $id,
									'name'		 => $id,
									);
						
						mstw_build_admin_edit_field( $args );
						
						?>
					</td>
				</tr> <!-- .form-field> -->
			<?php
			}
		}
	} //End: mstw_tr_team_edit_form( )
 }
 
//----------------------------------------------------------------------
// Define the MSTW Team taxonomy custom columns
//
add_filter( 'manage_edit-mstw_tr_team_columns', 'mstw_tr_manage_team_columns');

if ( !function_exists( 'mstw_tr_manage_team_columns' ) ) { 
	function mstw_tr_manage_team_columns( $columns ) {
	$new_columns = array(
        'cb' 			=> '<input type="checkbox" />',
        'name' 			=> __( 'Team Name', 'mstw-team-rosters' ),
		'posts' 		=> __( 'Players', 'mstw-team-rosters' ),
		'slug' 			=> __( 'Slug', 'mstw-team-rosters' ),
        'tr-ss-team-link' => __( 'MSTW SS Linked Team', 'mstw-team-rosters' ),
//      'description' => __('Description', 'mstw-team-rosters' ),
        );
	
	return $new_columns;
	
	} //End: mstw_tr_manage_team_columns()
}

 //-----------------------------------------------------------------
 // Fill the data in the MSTW Sport taxonomy custom columns
 //
 add_filter( 'manage_mstw_tr_team_custom_column', 'mstw_tr_fill_team_custom_columns', 10, 3 );

 if ( !function_exists( 'mstw_tr_fill_team_custom_columns' ) ) { 
	function mstw_tr_fill_team_custom_columns( $out, $column_name, $team_id ) {
	
		//mstw_log_msg( 'in mstw_tr_fill_team_custom_columns ... ');
		//mstw_log_msg( '$team_id= ' . $team_id );
		//mstw_log_msg( '$column_name= ' . $column_name );
		
		// set the default return value
		$out = __( 'None', 'mstw-team-rosters' );
		
		// load team metadata
		$team_obj = get_term( $team_id, 'mstw_tr_team' );
		$team_slug = $team_obj->slug;
		
		// check for a link to an SS team
		$tr_ss_links = get_option( 'mstw_tr_ss_team_links' );
		
		if ( $tr_ss_links && array_key_exists( $team_slug, $tr_ss_links ) ) {
			$ss_slug = $tr_ss_links[$team_slug];
			$ss_team_obj = get_page_by_path( $ss_slug, OBJECT, 'mstw_ss_team' );
			$out = ( $ss_team_obj ) ? get_the_title( $ss_team_obj->ID ) : $out; 
		}
		
		return $out;    
	
	} //End: mstw_tr_fill_team_custom_columns()
}

 //-----------------------------------------------------------------
 // Save the TEAM taxonomy meta data elements
 //
 add_action( 'edited_mstw_tr_team', 'mstw_tr_save_team_meta');
 add_action( 'create_mstw_tr_team', 'mstw_tr_save_team_meta' );

 if ( !function_exists( 'mstw_tr_save_team_meta' ) ) { 
	function mstw_tr_save_team_meta( $term_id ) {
		//mstw_log_msg( 'in ... mstw_tr_save_team_meta' );
		//mstw_log_msg( '$term_id= ' . $term_id );
		//mstw_log_msg( $_POST );
		
		//this is here in case function gets called from CSV import
		if ( array_key_exists( 'slug', $_POST ) ) {
			$team = $_POST['slug'];
			
			//mstw_log_msg( '$sport= ' . $sport );
			
			// load existing sports metadata
			$team_links = get_option( 'mstw_tr_ss_team_links' );
			
			// sanitize the inputs
			if ( isset( $_POST['tr-ss-team-link'] ) ) {
				//build the team slug from either the Name or Slug entry
				$team_slug = ( isset( $_POST['slug'] ) && !empty( $_POST['slug'] ) ) ? sanitize_title( $_POST['slug'] ) : sanitize_title( $_POST['tag-name'] );
				
				// $team_slug is the team taxomony $_POST is the SS team ID
				$link_pair = array( $team_slug => $_POST['tr-ss-team-link'] ); 
		
				//mstw_log_msg ( '$link_pair = ' );
				//mstw_log_msg ( $link_pair );
				
				$new_links = ($team_links) ? array_merge( $team_links, $link_pair ) : $link_pair;
				
				//mstw_log_msg ( '$new_links = ' );
				//mstw_log_msg ( $new_links );
				
				update_option( 'mstw_tr_ss_team_links', $new_links );
					
			}
		}
	} //End: mstw_tr_save_team_meta()
 }

 
?>