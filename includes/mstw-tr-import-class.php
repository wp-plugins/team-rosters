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
				if ($stored_value === false) {
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
				if ($stored_value === false) {
					add_option($name, $value);
				} elseif ($stored_value != $value) {
					update_option($name, $value);
				}
			}
			return $value;
		} //End function process_option()

		/**
		 * Plugin's admin user interface
		 *
		 */
		function form( ) {
			
			$opt_cat = $this->process_option( 'csv_importer_cat', 0, $_POST );

			if ('POST' == $_SERVER['REQUEST_METHOD']) {
				$this->post(compact('opt_draft', 'opt_cat'));
			}

			// form HTML {{{
	?>

	<div class="wrap">
		<?php echo get_screen_icon(); ?>
		<h2><?php _e( 'Import CSV', 'mstw-team-rosters' ) ?></h2>
		<form class="add:the-list: validate" method="post" enctype="multipart/form-data">
		
			<!-- Team taxonomy -->
			<?php $args = array(	'show_option_all'    => 'Select a team ...',
									'show_option_none'   => '',
									'orderby'            => 'name', 
									'order'              => 'ASC',
									'show_count'         => 0,
									'hide_empty'         => 0, 
									'child_of'           => 0,
									'exclude'            => '',
									'echo'               => 1,
									'selected'           => $opt_cat,
									'hierarchical'       => 0, 
									'name'               => 'csv_importer_cat',
									'id'                 => '',
									'class'              => 'postform',
									'depth'              => 0,
									'tab_index'          => 0,
									'taxonomy'           => 'mstw_tr_team',
									'hide_if_empty'      => false
								); ?>
								
			<p><?php _e( 'Select Team to Import:', 'mstw-team-rosters' );    wp_dropdown_categories( $args );?><br/>
			</p>

			<!-- File input -->
			<p><label for="csv_import"><?php _e( 'Upload file:', 'mstw-team-rosters') ?></label><br/>
				<input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
			<p class="submit"><input type="submit" class="button" name="submit" value="Import" /></p>
		</form>
	</div><!-- end wrap -->

	<?php
			// end form HTML }}}

		} //End of function form()

		function print_messages() {
			if (!empty($this->log)) {

			// messages HTML {{{
	?>

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
			// end messages HTML }}}

				$this->log = array();
			}
		} //End function print_messages()

		/**
		 * Handle POST submission
		 *
		 * @param array $options
		 * @return void
		 */
		function post( $options ) {
		
			extract( $options );
			
			// Check that a team has been selected
			echo '<p>$opt_cat(ID): ' . $opt_cat;
			if ( $opt_cat >0 ) {
				$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
				echo ' Team slug: ' . $term->slug . '</p>';
				if ( $term ) {
					echo 'Team slug: ' . $term->slug . '</p>';
				}
			}
			else {
				$this->log['error'][] = __( 'No Team Selected. Using player_team column from CSV file.', 'mstw-team-rosters' );
				//$this->print_messages();
				//return;
			}
			// Check that a file has been uploaded
			if ( empty($_FILES['csv_import']['tmp_name']) ) {
				$this->log['error'][] = __( 'Please select a CSV file. Exiting.', 'mstw-team-rosters' );
				$this->print_messages();
				return;
			}

			echo '<p> Loading DataSource ... </p>';
			if ( !class_exists( 'File_CSV_DataSource' ) ) {
				require_once 'DataSource.php';
				echo '<p>' . __( 'Done.', 'mstw-team-rosters' ) . '</p>';
			} else {
				echo '<p>' . __( 'Already loaded.', 'mstw-team-rosters' ) . '</p>';
			}

			$time_start = microtime(true);
			$csv = new File_CSV_DataSource;
			$file = $_FILES['csv_import']['tmp_name'];
			$this->stripBOM($file);

			if (!$csv->load($file)) {
				$this->log['error'][] = 'Failed to load file, aborting.';
				$this->print_messages();
				return;
			}

			// pad shorter rows with empty values
			$csv->symmetrize();

			// WordPress sets the correct timezone for date functions somewhere
			// in the bowels of wp_insert_post(). We need strtotime() to return
			// correct time before the call to wp_insert_post().
			$tz = get_option('timezone_string');
			if ($tz && function_exists('date_default_timezone_set')) {
				date_default_timezone_set($tz);
			}

			$skipped = 0;
			$imported = 0;
			$comments = 0;
			foreach ($csv->connect() as $csv_data) {
				// First try to create the post from the row
				if ($post_id = $this->create_post( $csv_data, $options )) {
					$imported++;
					//Insert the custom fields, which is most everything
					$this->create_custom_fields( $post_id, $csv_data );
				} else {
					$skipped++;
				}
			}

			if (file_exists($file)) {
				@unlink($file);
			}

			$exec_time = microtime(true) - $time_start;

			if ($skipped) {
				$this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, body and excerpt).</b>";
			}
			
			$term_msg = ( isset( $term ) ) ? $term->slug : __( 'teams from CSV file', 'mstw-team-rosters' );
			
			$this->log['notice'][] = '<b>' . sprintf( __( 'Imported %s posts to %s in %.2f seconds.', 'mstw-team-rosters' ), $imported, $term_msg, $exec_time ) . '</b>';
			//sprintf( __('You have %d tacos', 'plugin-domain'), $number );
			$this->print_messages();
		}

		function create_post( $data, $options ) {
			//mstw_log_msg( 'in create_post ...' );
			//mstw_log_msg( $options );
			
			extract( $options );
			
			$data = array_merge( $this->defaults, $data );

			// The post type is hardwired for this plugin's custom post type
			$type = 'mstw_tr_player';
			
			$valid_type = ( function_exists( 'post_type_exists' ) &&
				post_type_exists( $type )) || in_array( $type, array('post', 'page' ));

			if ( !$valid_type ) {
				$this->log['error']["type-{$type}"] = sprintf(
					'Unknown post type "%s".', $type);
			}
			
			$temp_title = ( $data['player_title'] != '' ) ? $data['player_title'] : '';
			
			//$temp_title = '';
			//echo '<p>First Name: ' . $data['player_first'] . '</p>';
			//if ( $data['player_first'] != '' ) {
				///$temp_title .= $data['First Name'];
			//} 
			if ( $temp_title == '' ) {
				if( $data['player_first_name'] != '' && $data['player_last_name'] != '' ) {
					$temp_title = "{$data['player_first_name']} {$data['player_last_name']}";
				}
				else if( $data['player_first_name'] != '' ) {
					$temp_title = $data['player_first_name'];
				}
				else if( $data['player_last_name'] != '' ) {
					$temp_title = $data['player_last_name'];
				}
				else {
					$temp_title = __( 'No first or last name.', 'mstw-team-rosters' );
				}
			}
			
			$temp_slug = sanitize_title( $temp_title );  
	

			//mstw_log_msg( 'player bio: ' . $data['player_bio'] );
			//mstw_log_msg( 'converted player bio: ' . convert_chars( $data['player_bio'] ) );
			
			$new_post = array(
				'post_title'   => convert_chars( $temp_title ),
				'post_content' => wpautop(convert_chars($data['player_bio'])),
				'post_status'  => 'publish',
				'post_type'    => $type,
				'post_name'    => $temp_slug,
			);
			
			// create the player (post)
			$id = wp_insert_post( $new_post );
			
			//if the post was successfully created, set the team (taxonomy/term)
			if ( $id ) { 
				//echo '<p>Title: ' . $temp_title . ' Slug: ' . $temp_slug . '</p>';
				//echo '<p>$opt_cat(ID): ' . $opt_cat . '</p>';
				if ( isset( $opt_cat ) && $opt_cat > 0 ) { 
					//use the team selected in UI
					$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
					wp_set_object_terms( $id, $term->slug, 'mstw_tr_team');
				}
				else if ( array_key_exists( 'player_teams', $data ) && !empty( $data['player_teams'] ) ) { 
					//use the teams from the CSV
					//remove_action( 'create_mstw_tr_team', 'mstw_tr_save_team_meta' );
					
					//array_filter() should remove empty strings
					$teams_array = array_filter( explode( ';', $data['player_teams'] ) );
					mstw_log_msg( '$teams_array: ' );
					mstw_log_msg( $teams_array );
					
					wp_set_object_terms( $id, $teams_array, 'mstw_tr_team');
					
					
					//add_action( 'create_mstw_tr_team', 'mstw_tr_save_team_meta' );
				}
				else {
					//no team provided
					echo '<p>' . sprintf( __( 'No team provided for player: %s', 'mstw-team-rosters' ), $temp_title ) . '</p>';
				}
				
				
			}

			if ( 'page' !== $type && !$id ) {
				// cleanup new categories on failure
				foreach ($cats['cleanup'] as $c) {
					wp_delete_term( $c, 'category' );
				}
			}
			return $id;
		} //End function create_post()

		function create_custom_fields( $post_id, $data ) {
			foreach ( $data as $k => $v ) {
				// anything that doesn't start with csv_ is a custom field
				switch ( $k ) {
					case 'player_first_name':	
					case 'player_last_name':
					case 'player_position':
					case 'player_number':
					case 'player_weight':
					case 'player_height':
					case 'player_age':
					case 'player_year':
					case 'player_experience':
					case 'player_home_town':	
					case 'player_country':
					case 'player_last_school':
					case 'player_bats':
					case 'player_throws':
					case 'player_other':
						$ret = update_post_meta( $post_id, $k, sanitize_text_field( $v ) );	
						//echo '<p>retval = '. $ret . ' ID: ' . $post_id . ' Key: ' . $k . ' Value: ' . $v . '</p>';
						break;
					default:
						mstw_log_msg( 'Bad data column: ' . $k );
						break;
				} //End: switch				
			} //End: foreach
		} //End: create_custom_fields( )

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