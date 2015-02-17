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
		<h2>Import CSV</h2>
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
								
			<p>Select Team to Import:  <?php wp_dropdown_categories( $args );?><br/>
			</p>

			<!-- File input -->
			<p><label for="csv_import">Upload file:</label><br/>
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
			$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
			echo ' Team slug: ' . $term->slug . '</p>';
			if ( $term ) {
				echo 'Team slug: ' . $term->slug . '</p>';
			}
			else {
				$this->log['error'][] = 'Please select a team. Exiting.';
				$this->print_messages();
				return;
			}
			// Check that a file has been uploaded
			if ( empty($_FILES['csv_import']['tmp_name']) ) {
				$this->log['error'][] = 'Please select a file. Exiting.';
				$this->print_messages();
				return;
			}

			echo '<p> Loading DataSource ... </p>';
			if ( !class_exists( 'File_CSV_DataSource' ) ) {
				require_once 'DataSource.php';
				echo '<p> Done. </p>';
			} else {
				echo '<p> Alrady loaded. </p>';
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
			$this->log['notice'][] = sprintf("<b>Imported {$imported} posts to {$term->slug} in %.2f seconds.</b>", $exec_time);
			$this->print_messages();
		}

		function create_post( $data, $options ) {
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
			
			$temp_title = '';
			echo '<p>First Name: ' . $data['First Name'] . '</p>';
			if ( $data['First Name'] != '' ) {
				$temp_title .= $data['First Name'];
			} else if ( $data['First'] != '' ) {
				$temp_title .= $data['First'];
			}
			echo '<p>Last Name: ' . $data['Last Name'] . '</p>';
			if ( $data['Last Name'] != '' ) {
				if ( $temp_title != '' ) //add a space between the names
					$temp_title .= ' ';
				$temp_title .= $data['Last Name'];
			} else if ( $data['Last'] != '' ) {
				if ( $temp_title != '' ) 
					$temp_title .= ' ';  //add a space between the names
				$temp_title .= $data['Last'];
			}
			
			if ( $temp_title == '' )
				$temp_title = __( 'No first or last name', 'mstw-loc-domain' );
			else
				$temp_slug = sanitize_title( $temp_title );  
			
			echo '<p>Title: ' . $temp_title . ' Slug: ' . $temp_slug . '</p>';
			echo '<p>$opt_cat(ID): ' . $opt_cat . '</p>';
			$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );

			$new_post = array(
				'post_title'   => convert_chars( $temp_title ),
				'post_content' => wpautop(convert_chars($data['Bio'])),
				'post_status'  => 'publish',
				'post_type'    => $type,
				'post_name'    => $temp_slug,
			);
			
			// create!
			$id = wp_insert_post( $new_post );
			
			if ( $id ) {
				$term = get_term_by( 'id', $opt_cat, 'mstw_tr_team' );
				wp_set_object_terms( $id, $term->slug, 'mstw_tr_team');
			}

			if ('page' !== $type && !$id) {
				// cleanup new categories on failure
				foreach ($cats['cleanup'] as $c) {
					wp_delete_term($c, 'category');
				}
			}
			return $id;
		} //End function create_post()

		function create_custom_fields( $post_id, $data ) {
			foreach ( $data as $k => $v ) {
				// anything that doesn't start with csv_ is a custom field
				if (!preg_match('/^csv_/', $k) && $v != '') {
					switch ( strtolower( $k ) ) {
						case __( 'first name', 'mstw-loc-domain' ):
						case __( 'first', 'mstw-loc-domain' ):
							$k = '_mstw_tr_first_name';
							break;
						case __( 'last name', 'mstw-loc-domain' ):
						case __( 'last', 'mstw-loc-domain' ):
							$k = '_mstw_tr_last_name';
							break;
						case __( 'position', 'mstw-loc-domain' ):
						case __( 'pos', 'mstw-loc-domain' ):
							$k = '_mstw_tr_position';
							break;
						case __( 'number', 'mstw-loc-domain' ):
						case __( 'nbr', 'mstw-loc-domain' ):
						case __( '#', 'mstw-loc-domain' ):
							$k = '_mstw_tr_number';
							break;
						case __( 'weight', 'mstw-loc-domain' ):
						case __( 'wt', 'mstw-loc-domain' ):
							$k = '_mstw_tr_weight';
							break;
						case __( 'height', 'mstw-loc-domain' ):
						case __( 'ht', 'mstw-loc-domain' ):
							$k = '_mstw_tr_height';
							break;
						case __( 'age', 'mstw-loc-domain' ):
							$k = '_mstw_tr_age';
							break;
						case __( 'year', 'mstw-loc-domain' ):
						case __( 'yr', 'mstw-loc-domain' ):
							$k = '_mstw_tr_year';
							break;
						case __( 'experience', 'mstw-loc-domain' ):
						case __( 'exp', 'mstw-loc-domain' ):
							$k = '_mstw_tr_experience';
							break;
						case __( 'home town', 'mstw-loc-domain' ):
							$k = '_mstw_tr_home_town';
							break;
						case __( 'country', 'mstw-loc-domain' ):
							$k = '_mstw_tr_country';
							break;
						case __( 'last school', 'mstw-loc-domain' ):
							$k = '_mstw_tr_last_school';
							break;
						case __( 'bats', 'mstw-loc-domain' ):
						case __( 'bat', 'mstw-loc-domain' ):
							$k = '_mstw_tr_bats';
							break;
						case __( 'throws', 'mstw-loc-domain' ):
						case __( 'throw', 'mstw-loc-domain' ):
						case __( 'thw', 'mstw-loc-domain' ):
							$k = '_mstw_tr_throws';
							break;
						case __( 'other', 'mstw-loc-domain' ):
							$k = '_mstw_tr_other';
							break;
					}
						
					$ret = update_post_meta( $post_id, $k, $v );	
					
					echo '<p>retval = '. $ret . ' ID: ' . $post_id . ' K: ' . $k . ' V: ' . $v . '</p>';
				}
			}
		}

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