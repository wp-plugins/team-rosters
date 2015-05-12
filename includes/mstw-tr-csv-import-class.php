<?php
/* ------------------------------------------------------------------------
 * 	MSTW Team Rosters CSV Importer Class
 *		- Modified from CSVImporter by Denis Kobozev (d.v.kobozev@gmail.com)
 *		- All rights flow through under GNU GPL.
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *--------------------------------------------------------------------------*/
 
 if( !class_exists( 'MSTW_TR_ImporterPlugin' ) ) {
	class MSTW_TR_ImporterPlugin {
		var $defaults = array(
			'csv_post_title'      => null,
			'csv_post_post'       => null,
			'csv_post_type'       => null,
			'csv_post_excerpt'    => null,
			'csv_post_date'       => null,
			'csv_post_tags'       => null,
			'csv_post_categories' => null,
			'csv_post_author'     => null,
			'csv_post_slug'       => null,
			'csv_post_parent'     => 0,
		);

		var $log = array();

		/**
		 * Determine value of option $name from database, $default value or $params,
		 * save it to the db if needed and return it.
		 */
		function process_option( $name, $default, $params ) {
			//mstw_log_msg( 'in process_option ... ' );
			//mstw_log_msg( '$name= ' . $name );
			//mstw_log_msg( '$default= ' . $default );
			//mstw_log_msg( '$params= ' );
			//mstw_log_msg( $params );
			
			if ( array_key_exists( $name, $params ) ) {
				$value = stripslashes( $params[$name] );
			} elseif ( array_key_exists( '_'.$name, $params ) ) {
				// unchecked checkbox value
				$value = stripslashes( $params['_'.$name] );
			} else {
				$value = null;
			}
			
			$stored_value = get_option( $name );
			if ( $value == null ) {
				if ( $stored_value === false ) {
					if (is_callable($default) &&
						method_exists($default[0], $default[1])) {
						$value = call_user_func($default);
					} else {
						$value = $default;
					}
					add_option($name, $value);
				} else {
					$value = $stored_value;
				}
			} else {
				if ( $stored_value === false ) {
					add_option( $name, $value );
				} elseif ( $stored_value != $value ) {
					update_option( $name, $value );
				}
			}
			return $value;
		} //End function process_option()

		/**
		 * Plugin's admin user interface
		 *
		 */
		function form( ) {
			//mstw_log_msg( 'In MSTW_TR_ImporterPlugin form method ...' );
			//mstw_log_msg( '$_POST:' );
			//mstw_log_msg( $_POST );
			//mstw_log_msg( '$_REQUEST:' );
			//mstw_log_msg( $_REQUEST );
			
			//
			// THIS NEEDS STRAIGHTENING OUT
			//
			$submit_value = $this->process_option( 'submit', 0, $_POST );
			$import_team = $this->process_option( 'csv_import_team', 0, $_POST );
			$csv_teams_import = $this->process_option( 'csv_teams_import', 0, $_POST );
			$move_photos = $this->process_option( 'csv_move_photos', 0, $_POST );
			
			//mstw_log_msg( '$_SERVER[\'REQUEST_METHOD\']=' . $_SERVER['REQUEST_METHOD'] );
			
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				//$this->post(compact('opt_draft', 'import_team'));
				//mstw_log_msg( 'compact= ' );
				//mstw_log_msg( compact( 'submit_value', 'import_team', 'photo_dir' ) );
				$this->post( compact( 'submit_value', 'import_team', 'move_photos' ) );
			}

			// start form HTML {{{
	?>

			<div class="wrap">
				<?php //echo get_screen_icon(); ?>
				<h2><?php _e( 'Import CSV Files', 'mstw-team-rosters' ) ?></h2>

				<!-- TEAMS import form -->
				<form class="add:the-list: validate" method="post" enctype="multipart/form-data" action="">
					
					<table class='form-table'>
					<thead><tr><th><?php echo __( 'Teams', 'mstw-team-rosters' ) ?></th></tr></thead>
						
						<tr>  <!-- CSV file selection field -->
							<td><label for="csv_teams_import"><?php _e( 'Teams CSV file:', 'mstw-team-rosters' ); ?></label></td>
							<td><input name="csv_teams_import" id="csv_teams_import" type="file" value="" aria-required="true" />
							<br/>
							<span class='description' >Select the CSV teams file to import.</span></td>
						</tr>
						
						<tr> <!-- Submit button -->
						<td colspan="2" class="submit"><input type="submit" class="button" name="submit" value="<?php _e( 'Import Teams', 'mstw-team-rosters' ); ?>"/></td>
						</tr>
					
					</table>
				</form> <!--End: Teams import form -->
				
				
				<!-- PLAYERS import form -->
				<?php $args = array(	
								'show_option_all'    => 'Select a team ...',
								'show_option_none'   => '',
								'orderby'            => 'name', 
								'order'              => 'ASC',
								'show_count'         => 0,
								'hide_empty'         => 0, 
								'child_of'           => 0,
								'exclude'            => '',
								'echo'               => 1,
								'selected'           => $import_team,
								'hierarchical'       => 0, 
								'name'               => 'csv_import_team',
								'id'                 => 'csv_import_team',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'mstw_tr_team',
								'hide_if_empty'      => false
								); ?>				
				
				<form class="add:the-list: validate" method="post" enctype="multipart/form-data">

					<table class='form-table'>
						<thead>
							<tr><th colspan=2>
								<?php echo __( 'Players', 'mstw-team-rosters' ) ?>
								<br/>
								<span class='description' style='font-weight: normal'><?php printf( __( 'The importer will use the "player-slug" column in the CSV file to assign teams to a player if that column is not empty.%s Otherwise, the player will be assigned to the team selected in the "Select Team to Import" dropdown. %sOtherwise, the player will be imported but will not be assigned to a team.', 'mstw-team-rosters' ), '<br/>', '<br/>' ) ?></span>
							</th></tr>
						</thead>	
									
						<tbody>
							<tr>  <!-- Team (to import) selection field -->
								<td><label for="csv_import_team"><?php _e( 'Select Team to Import:', 'mstw-team-rosters' ) ?></label></td>
								<td><?php wp_dropdown_categories( $args ) ?>
								<br/>
								<span class='description' >This team will be used as the default if there is no entry for a player in the player_team column.</span>
								</td>
							</tr>
							<tr>
								<td><label for="csv_move_photos"><?php _e( 'Move Player Photos:', 'mstw-team-rosters') ?></label></td>
								<td><input name="csv_move_photos" id="csv_move_photos" type="checkbox" value="1" />
								<br/>
								<span class='description' >If checked, photo files will be imported from their current locations to the media library.If unchecked, photo files will remain in their current locations.</span>
								</td>
							</tr>
							<tr> <!-- CSV file selection field -->
								<td><label for="csv_players_import"><?php _e( 'Players CSV file:', 'mstw-team-rosters') ?></label></td>
								<td><input name="csv_players_import" id="csv_players_import" type="file" value="" aria-required="true" />
								<br/>
								<span class='description' >Select the CSV players file to import.</span>
								</td>
							</tr>
							<tr> <!-- Submit button -->
								<td colspan="2" class="submit"><input type="submit" class="button" name="submit" value="Import Players" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div><!-- end wrap -->

			<?php
			// end form HTML }}}

		} //End of function form( )

		function print_messages() {
			if ( !empty( $this->log ) ) { ?>

				<div class="wrap">
				
				<?php if (!empty($this->log['error'])): ?>
					<div class="error">
						<?php foreach ($this->log['error'] as $error): ?>
							<p><?php echo $error; ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($this->log['notice'])): ?>
					<div class="updated fade">
						<?php foreach ($this->log['notice'] as $notice): ?>
							<p><?php echo $notice; ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				
				</div><!-- end wrap -->

				<?php
				$this->log = array();
				
			} //End: if ( !empty( $this->log ) )
		} //End print_messages( )

		/**
		 * Handle POST submission
		 *
		 * @param array $options
		 * @return void
		 */
		function post( $options ) {
			//mstw_log_msg( 'In post method ... ' );
			//mstw_log_msg( '$options: ' );
			//mstw_log_msg( $options );
			//mstw_log_msg( $_POST );
			//mstw_log_msg( $_POST );
			
			if ( !$options ) {
				mstw_log_msg( 'Houston, we have a problem ... no $options' );
				return;
			}
			
			switch( $options['submit_value'] ) {
				case __( 'Import Teams', 'mstw-team-rosters' ):
					//mstw_log_msg( 'In post( ) method: Importing Teams ...' );
					$file_id = 'csv_teams_import';
					//$msg_str is only used in summary messages
					$msg_str = array( __( 'team', 'mstw-team-rosters' ), __( 'teams', 'mstw-team-rosters' ) );
					break;
				case __( 'Import Players', 'mstw-team-rosters' ):
					//mstw_log_msg( 'In post() method: Importing Players ...' );
					$file_id = 'csv_players_import';
					//$msg_str is only used in summary messages
					$msg_str = array( __( 'player', 'mstw-team-rosters' ), __( 'players', 'mstw-team-rosters' ) );
					break;
				default:
					mstw_log_msg( 'Error encountered in post() method. $submit_value = ' . $submit_value . '. Exiting' );
					return;
					break;
			}
			
			if ( !class_exists( 'MSTW_CSV_DataSource' ) ) {
				require_once 'MSTWDataSource.php';
			}
			
			$time_start = microtime(true);
			$csv = new MSTW_CSV_DataSource;
			
			$file = $_FILES[$file_id]['tmp_name'];
			//mstw_log_msg( "CSV File: $file" );
			$this->stripBOM($file);

			if (!$csv->load($file)) {
				$this->log['error'][] = __( 'Failed to load file, aborting.', 'mstw-team-rosters' );
				$this->print_messages();
				return;
			}

			// pad shorter rows with empty values
			$csv->symmetrize();

			$skipped = 0;
			$imported = 0;
			$comments = 0;
			foreach ( $csv->connect( ) as $csv_data ) {
				//$total = $skipped + $imported;
				//mstw_log_msg( 'Displaying CSV Data ... ' . $total );
				//mstw_log_msg( $csv_data );
	
				if ( empty( $csv_data ) or !$csv_data ) {
					mstw_log_msg( 'No CSV data. $csv_data is empty.' );
				}
				
				//Insert the custom fields, which is most everything
				switch( $file_id ) {
					case 'csv_teams_import': 
						if ( $this->create_team_taxonomy_term( $csv_data, $options, $imported+1 ) ) {
							//mstw_log_msg( 'created team ' . $csv_data['team_name'] );
							$imported++;
						}
						else {
							$skipped++;
						}
						break;
						
					case 'csv_players_import':
						// First try to create the post from the row
						// IS $IMPORTED+1 RIGHT HERE? OR JUST $IMPORTED?
						if ( $post_id = $this->create_post( $csv_data, $options, $imported+1 ) ) {
							$imported++;
							$this->create_player_fields( $post_id, $csv_data, $options );
						} 
						else {
							$skipped++;
						}
						break;
						
					default:
						mstw_log_msg( 'Oops, something went wrong with file ID: ' . $file_id );
						break;
				}
			}

			if (file_exists($file)) {
				@unlink($file);
			}

			$exec_time = microtime(true) - $time_start;
			
			//add notice if any records were skipped
			if ( $skipped ) {
				$format = _n( 'Skipped %1$s %2$s.', 'Skipped %1$s %3$s', $skipped, 'mstw-team-rosters' );
				$admin_notice = sprintf( $format, $skipped, $msg_str[0], $msg_str[1] );
				$this->log['error'][] = "<b>{$admin_notice}</b>";
				//$this->log['error'][] = '<b>' . sprintf( _n( 'Skipped %s %s. See man page for possible causes.', 'mstw-team-rosters' ), $skipped, $term_msg ) . '</b>';
				//$sample = sprintf( _n('You have %d taco.', 'You have %d tacos.', $number, 'plugin-domain'), $number );
			}
			
			//always add notice for records imported and elapsed time
			$format = _n( 'Imported %1$s %2$s in %4$.2f seconds.', 'Imported %1$s %3$s in %4$.2f seconds.', $imported, 'mstw-team-rosters' );
			$admin_notice = sprintf( $format, $imported, $msg_str[0], $msg_str[1], $exec_time );
			$this->log['notice'][] = "<b>{$admin_notice}</b>";
			//sprintf( __('You have %d tacos', 'plugin-domain'), $number );
			
			$this->print_messages();
		} //End: post( )

		function create_post( $data, $options ) {
			//mstw_log_msg( 'in create_post ...' );
			//mstw_log_msg( $options );
			
			//extract( $options );
			
			$data = array_merge( $this->defaults, $data );
			
			// figure out what custom post type we're importing
			switch ( $options[ 'submit_value'] ) {
				case __( 'Import Players', 'mstw-team-rosters' ) :
					//mstw_log_msg( ' We are importing players ... ' );
					$type = 'mstw_tr_player';
					//this is used to add_action/remove_action below
					$save_suffix = 'player_meta';
					
					// need a player title to proceed
					if ( isset( $data['player_title'] ) && !empty( $data['player_title'] ) ) {
						$temp_title = $data['player_title'];
						//mstw_log_msg( 'title: ' . $temp_title );
						
					} else { 
						//no title in CSV, figure it out from first & last names
						$temp_title = '';
						$temp_first_name = '';
						$temp_last_name = '';
						
						if( isset( $data['player_first_name'] ) and $data['player_first_name'] != '' ) {
							$temp_title = $data['player_first_name'];
						}
						if( isset( $data['player_last_name'] ) and $data['player_last_name'] != '' ) {
							if( $temp_title ) {
								$temp_title .= ' ' . $data['player_last_name'];
							}
						}
						
						$temp_title = ( $temp_title ) ? $temp_title :  __( 'No first or last name.', 'mstw-team-rosters' );

					}
			
					
					// slug should come from CSV file; else will default to sanitize_title()
					$temp_slug = ( isset( $data['player_slug'] ) && !empty( $data['player_slug'] ) ) ? $data['player_slug'] : sanitize_title( $temp_title, __( 'No title imported', 'mstw-team-rosters' ) );

					break;
					
				default:
					mstw_log_msg( 'Whoa horsie ... submit_value = ' . $options[ 'submit_value'] );
					$this->log['error']["type-{$type}"] = sprintf(
						__( 'Unknown import type "%s".', 'mstw-team-rosters' ), $type );
					return false;
					break;
					
			}
			
			$new_post = array(
				'post_title'   => convert_chars( $temp_title ),
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => $type,
				'post_name'    => $temp_slug,
			);
			
			//
			// create the post
			//
			remove_action( 'save_post_' . $type, 'mstw_tr_save_' . $save_suffix, 20, 2 );
			$post_id = wp_insert_post( $new_post );
			add_action( 'save_post_' . $type, 'mstw_tr_save_' . $save_suffix, 20, 2 );
			
			
			/*
			 * What the hell is this??
			 *
			if ( 'page' !== $type && !$post_id ) {
				// cleanup new categories on failure
				foreach ($cats['cleanup'] as $c) {
					wp_delete_term( $c, 'category' );
				}
			}
			 */
			 
			return $post_id;
		} //End function create_post()
		
		/*-------------------------------------------------------------
		 *	Add the fields from a row of CSV player data to a newly created post
		 *-----------------------------------------------------------*/
		function create_player_fields( $post_id, $data, $options ) {
			//mstw_log_msg( 'in create_player_fields ... ' );
			//mstw_log_msg( '$data[player_teams]' );
			//mstw_log_msg( $data['player_teams'] );
			//mstw_log_msg( '$options' );
			//mstw_log_msg( $options );
			
			$bats_list = array(  __( '----', 'mstw-team-rosters' )  => 0, 
								 __( 'R', 'mstw-team-rosters' ) 	=> 1,
								 __( 'r', 'mstw-team-rosters' ) 	=> 1,
								 __( 'L', 'mstw-team-rosters' ) 	=> 2,
								 __( 'l', 'mstw-team-rosters' ) 	=> 2,
								 __( 'B', 'mstw-team-rosters' ) 	=> 3,
								 __( 'b', 'mstw-team-rosters' ) 	=> 3,
								);
							
			$throws_list = array( __( '----', 'mstw-team-rosters' ) => 0, 
								  __( 'R', 'mstw-team-rosters' ) 	=> 1,
								  __( 'r', 'mstw-team-rosters' ) 	=> 1,
								  __( 'L', 'mstw-team-rosters' ) 	=> 2, 
								  __( 'l', 'mstw-team-rosters' ) 	=> 2,
								);
			
			foreach ( $data as $k => $v ) {
				//if ( $k == strtolower( 'player_teams' ) ) {
				//	mstw_log_msg( 'Found player_teams = ' . $v );
				//}
				switch ( strtolower( $k ) ) {
					case 'player_title':
					case 'player_slug':
						//added in create_post(); nothing else to do here
						break;
						
					case 'player_bio':
						$player_bio = ( isset( $data['player_bio'] ) ) ? $data['player_bio'] : '';
						//post content is set to '' when post is created
						//	so do nothing is $data['player_bio'] is blank
						if( $player_bio ) {
							$player_bio_update = array( 'ID' => $post_id,
														'post_content' => wpautop( convert_chars( $player_bio ) ),
													  );
							wp_update_post( $player_bio_update );
						}
						break;
						
					case 'player_throws':
						//Need to switch indices
						$throws = ( array_key_exists( $v, $throws_list ) and $throws_list[ $v ] ) ? $throws_list[ $v ] : 0 ;
						$k = strtolower( $k );
						$ret = update_post_meta( $post_id, $k, $throws );
						break;
					case 'player_bats':
						//Need to switch indices
						$bats = ( array_key_exists( $v, $bats_list ) and $bats_list[ $v ] ) ? $bats_list[ $v ] : 0 ;
						$k = strtolower( $k );
						$ret = update_post_meta( $post_id, $k, $bats );
						break;
						
					// "NORMAL" player data
					case 'player_first_name':
					case 'player_last_name':
					case 'player_number':
					case 'player_position': 
					case 'player_height':	
					case 'player_weight':
					case 'player_year':						
					case 'player_experience':
					case 'player_age':
					case 'player_home_town':
					case 'player_last_school':
					case 'player_country':
					case 'player_other':
						$k = strtolower( $k );
						$ret = update_post_meta( $post_id, $k, $v );
						break;
					case 'player_teams':
						//mstw_log_msg( "player_teams value= $v" );
						//mstw_log_msg( "is $v empty? " . empty( $v ) );
						if( !empty( $v ) ) {
							//build team(s) from the player_teams column
							//mstw_log_msg( 'CSV Player Teams string: ' . $v );
							
							//array_filter() removes empty strings from array
							//	created by str_getcsv()
							$teams_array = array_filter( str_getcsv( $v, ';', '"' ) );
							
							//mstw_log_msg( 'Player: ' . $temp_title . ' Teams: ' );
							//mstw_log_msg( $teams_array );
							
							wp_set_object_terms( $post_id, $teams_array, 'mstw_tr_team' );
				
						} else if ( array_key_exists( 'import_team', $options ) && $options['import_team'] ) { 
							//GOTTA SET UP import_team (from $options?? )
							//use the team selected in UI
							$term = get_term_by( 'id', $options['import_team'], 'mstw_tr_team' );
							wp_set_object_terms( $post_id, $term->slug, 'mstw_tr_team');
							
						}
						
						break;
						
					case 'player_photo':
						if( !empty( $v ) ) {
							if( array_key_exists( 'move_photos', $options ) and $options['move_photos'] ) {
								// Going to move photos from another server
								
								//Try to download player photo
								//mstw_log_msg( "player_photo = $v" );
								$temp_photo = download_url( $v );
								
								//Check for errors downloading
								if( is_wp_error( $temp_photo ) ) {
									mstw_log_msg( "Error downloading: $v" );
									mstw_log_msg( $temp_photo );
								}
								else {
									//Sucessfully downloaded file
									//mstw_log_msg( "Downloaded file $v to:" );
									//mstw_log_msg( $temp_photo );
									$file_array = array( 'name' => basename( $v ),
														'tmp_name' => $temp_photo,
													  );
									//Try to add file to media library & attach to player (CPT)
									$id = media_handle_sideload( $file_array, 0 );
									
									//Check for sideload errors
									if( is_wp_error( $id ) ) {
										mstw_log_msg( "Error loading file to media library: $temp_photo" );
										mstw_log_msg( $id );	
									} 
									else {
										//Success
										//mstw_log_msg( "Attachment ID: $id" );
										$post_meta_id = set_post_thumbnail( $post_id, $id );
										
										if( $post_meta_id === false ) {
											mstw_log_msg( "Failed to set thumbnail for post $post_id" );	
										}
									}
									
								}
							}
							else {
								// Going to use photos already on this server
								$thumbnail_id = $this->find_attachment_id_from_url( $v );
								if( $thumbnail_id and $thumbnail_id != -1 ) {
									//mstw_log_msg( 'thumbnail ID= ' . $thumbnail_id );
									if( set_post_thumbnail( $post_id, $thumbnail_id ) === false ) {
										mstw_log_msg( 'Failed to set_post_thumbnail. Post= ' . $post_id . ' thumbnail= ' . $thumbnail_id );
									}
								}
								else {
									mstw_log_msg( 'No file found in the media library: ' . $thumbnail_id );
								}	
							}
						} 
						break;
						
					default:
						// bad column header
						mstw_log_msg( 'Unrecognized game data field: ' . $k );
						break;
						
				}
			}
		} //End of function create_player_fields()
		
		/*-------------------------------------------------------------
		 *	find_attachment_id_from_url - returns an attachment ID given it's URL
		 *
		 *	ARGUMENTS:
		 *		$url - a file URL
		 *
		 *	RETURN: attachment ID if one is found, -1 otherwise
		 *
		 *-----------------------------------------------------------*/
		function find_attachment_id_from_url( $url ) {
			//mstw_log_msg( 'in find_attachment_id_from_url ...' );
			//mstw_log_msg( '$url= ' . $url );
			
			// Split the $url into two pars with the wp-content directory as the separator
			$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_HOST ), $url );
			
			//mstw_log_msg( 'WP_CONTENT_URL: ' . WP_CONTENT_URL );
			//mstw_log_msg( 'PHP_URL_HOST: ' . PHP_URL_HOST );
			//mstw_log_msg( '$parsed_url: ' );
			//mstw_log_msg( $parsed_url );
			
			// Get the host of the current site and the host of the $url, ignoring www
			$this_host = str_ireplace( 'www.', '', parse_url( home_url( ), PHP_URL_HOST ) );
			$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
			
			//mstw_log_msg( '$this_host: ' . $this_host );
			//mstw_log_msg( '$file_host: ' . $file_host );

			// Return nothing if there aren't any $url parts or if the current host and $url host do not match
			if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
				$retval = -1;
			}
			else {
				// Now we're going to quickly search the DB for any attachment GUID with a partial path match
				// Example: /uploads/2013/05/test-image.jpg
				global $wpdb;

				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
				
				//mstw_log_msg( '$attachment:' );
				//mstw_log_msg( $attachment );
		
				// Returns -1 if no attachment is found
				$retval = ( isset( $attachment) ) ? $attachment[0] : -1;
			}
			
			return $retval;
			
			
		} //End: find_attachment_id_from_url( )
		
		function create_team_taxonomy_term( $data, $options, $imported ) {
			//mstw_log_msg( 'in create_team_taxonomy_term ... ' );
			//mstw_log_msg( 'CSV Data:' );
			//mstw_log_msg( $data );
			//mstw_log_msg( '$options' );
			//mstw_log_msg( $options );
			
			$retval = 0;
			
			$team_name = ( array_key_exists( 'team_name', $data ) ) ? $data['team_name'] : '' ;
			$team_slug = ( array_key_exists( 'team_slug', $data ) ) ? $data['team_slug'] : '' ;
			$team_description = ( array_key_exists( 'team_description', $data ) ) ? $data['team_description'] : '' ;		
			//ADD SS team link info - will be for 4.0+
			$team_link = ( array_key_exists( 'team_link', $data ) ) ? $data['team_link'] : '' ;
			
			//check if team name & team slug is specified
			if( $team_slug != '' && $team_name != '' ) {
				//mstw_log_msg( 'team name & slug both exist ... creating term for ' . $team_name );
				//sanitize slug - JIC
				$team_slug = sanitize_title( $team_slug );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  );
				$result = wp_insert_term( $data['team_name'], 'mstw_tr_team', $args );	
			}
			
			//team slug not specified, try to create it from team name
			else if ( $team_name != '' ) {
				//mstw_log_msg( 'team name exists ... creating term for ' . $team_name );
				//create slug
				$team_slug = sanitize_title( $team_name );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  );
						  
				$result = wp_insert_term( $team_name, 'mstw_tr_team', $args );
			}
			
			else if ( $team_slug != '' ) {
				//mstw_log_msg( 'team slug exists ... creating term for ' . $team_slug );
				//sanitize slug - JIC
				$team_slug = sanitize_title( $team_slug );
				
				$args = array( 'description' => $team_description,
							   'slug'		 => $team_slug,
							  );
						  
				$result = wp_insert_term( $team_slug, 'mstw_tr_team', $args );			
			}
			
			//no slug and no name so bag it
			else {
				//mstw_log_msg('No team name or slug ... bag it' );
				$result = new WP_Error( 'oops', __( 'No team name or slug found.', 'mstw-team-rosters' ) );
				
			}
			
			if ( is_wp_error( $result ) ) {
				mstw_log_msg( 'Error inserting term ... ' );
				mstw_log_msg( $result );
				$retval = 0;
			}
			else {
				//mstw_log_msg( 'Term inserted ... ID= ' . $result['term_id'] );
				//If it exists, add the SS team link meta data to term
				if( $team_link != '' ) {
					// Need to do this because slug might change (if duplicate)
					$term = get_term( $result['term_id'], 'mstw_tr_team' );
					$team_slug = $term->slug;
					
					//$team_slug is the team taxomony term, $team_link is the S&S team slug
					$link_pair = array( $team_slug => $team_link ); 
		
					//mstw_log_msg ( '$link_pair = ' );
					//mstw_log_msg ( $link_pair );
					
					$team_links = get_option( 'mstw_tr_ss_team_links' );
				
					$new_links = ( $team_links ) ? array_merge( $team_links, $link_pair ) : $link_pair;
					
					//mstw_log_msg ( '$new_links = ' );
					//mstw_log_msg ( $new_links );
					
					update_option( 'mstw_tr_ss_team_links', $new_links );
				}
				$retval = 1;
			}
			
			return $retval;
			
		} //End: create_team_taxonomy_term( )

		/**
		 * Delete BOM from UTF-8 file.
		 *
		 * @param string $fname
		 * @return void
		 */
		function stripBOM($fname) {
			$res = fopen($fname, 'rb');
			if (false !== $res) {
				$bytes = fread($res, 3);
				if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
					$this->log['notice'][] = 'Getting rid of byte order mark...';
					fclose($res);

					$contents = file_get_contents($fname);
					if (false === $contents) {
						trigger_error('Failed to get file contents.', E_USER_WARNING);
					}
					$contents = substr($contents, 3);
					$success = file_put_contents($fname, $contents);
					if (false === $success) {
						trigger_error('Failed to put file contents.', E_USER_WARNING);
					}
				} else {
					fclose($res);
				}
			} else {
				$this->log['error'][] = 'Failed to open file, aborting.';
			}
		}
	} //End: class MSTW_TR_ImporterPlugin
 }
 ?>