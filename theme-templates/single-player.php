<?php
/**
 * MSTW Team Rosters Template for displaying single player bios.
 *
 * NOTE: Plugin users will probably have to modify this template to fit their 
 * individual themes. This template has been tested in the WordPress 
 * Twenty Eleven Theme. 
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
 *-------------------------------------------------------------------------*/
 ?>

 <?php get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

		<?php //while ( have_posts() ) : the_post(); ?>

		<nav id="nav-single">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'mstw-team-rosters' ); ?></h3>
			<span class="nav-previous">
				<?php 
				$back = ( isset( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : '';
				if( isset( $back ) && $back != '' ) {
				?>	
					<a href="<?php echo $back ?>">
						<span class="meta-nav">&larr;</span>
						<?php _e( 'Return to roster', 'mstw-team-rosters' ) ?>
					</a>
				<?php
				}
				?>
			</span> <!-- .nav-previous -->	
		</nav><!-- #nav-single -->
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php
		mstw_log_msg( 'in single-player.php ... ' );
		
		//Handle the display settings (options) & roster_type (format)
		$options = get_option( 'mstw_tr_options' );
		
		mstw_log_msg( '$options = ' );
		mstw_log_msg( $options );
		
		
		$options = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		
		//mstw_log_msg( '$defaults= ' );
		//mstw_log_msg( mstw_tr_get_data_fields_columns_defaults( ) );
		
		mstw_log_msg( '$options merged with defaults = ' );
		mstw_log_msg( $options );
		
		//			
		// Set the roster format based on the page args & plugin settings
		//   
		$roster_type = ( isset( $_GET['roster_type'] ) && $_GET['roster_type'] != '' ) ? 
							$_GET['roster_type'] : 
							$options['roster_type'];
		
		$settings = mstw_tr_set_fields_by_format( $roster_type ); 
		
		mstw_log_msg( '$fields by format for ' . $roster_type . '] = ' );
		mstw_log_msg( $settings );
		
		$options = wp_parse_args( $settings, $options );
		
		mstw_log_msg( '$options merged with fields by format = ' );
		mstw_log_msg( $options );
		
		//$options = wp_parse_args( $options, mstw_tr_get_defaults( ) );
		
		//mstw_log_msg( 'in single_player.php ... ' );
		//mstw_log_msg( $options );
		
		// find the player's team slug ... 
		// use the first one in the list
		$player_teams = wp_get_object_terms($post->ID, 'mstw_tr_team');
		
		if( !empty( $player_teams ) and !is_wp_error( $player_teams ) ) {
			$team_name = $player_teams[0]->name;
			$team_slug = $player_teams[0]->slug;
		}
		else {
			$team_name = __( 'Unspecified', 'mstw-team-rosters' );
			$team_slug = __( 'unknown', 'mstw-team-rosters' );
		}

		// Build the single player page title
		if ( $options['sp_show_title'] ) {	
			echo "<h1 class='player-head-title player-head-title-$team_slug'>$team_name</h1>";
		}
		
		// Build the player profile header
		?>
		
		<div class="player-header player-header-<?php echo( $team_slug ) ?>">

			<?php /*First, figure out the player's photo 
					may want to include this div in mstw_tr_build_player_photo 
					depends on the other usage ... in galleries? ... in tables??>
				  */ ?>
			
			<div id = "player-photo">
				<?php 
				echo mstw_tr_build_player_photo( $post, $team_slug, $options, 'profile' );
				?>
			</div> <!-- #player-photo -->
			
			<?php //Figure out the player name and number ?>
			<div id="player-name-nbr">
				<?php if ( $options['show_number'] ) { ?>
					<div id="number"> 
						<?php echo get_post_meta($post->ID, 'player_number', true ); ?> 
					</div><!-- #number -->
				<?php } ?>
				
				<?php //Always show the player name ?>
				<div id="player-name">
					<?php 
					//Convert 'last, first' to 'first last'
					$options['name_format'] = ( $options['name_format'] == 'last-first' ) ? 'first-last' : $options['name_format'] ;
					echo mstw_tr_build_player_name( $post, $options, 'profile' );
					?>
				</div><!-- #player-name -->
			<!--</div> <!-- #player-name-nbr -->
			
			<table id= "player-info">
			<tbody>
			<?php 
				$row_start = '<tr><td class="lf-col">';
				$new_cell = ':</td><td class="rt-col">';
				$row_end = '</td></tr>';
				
				// the first two rows are (now almost) the same in all formats
				if ( $options['show_position'] ) {
					echo $row_start . $options['position_label'] . $new_cell .  get_post_meta($post->ID, 'player_position', true ) . $row_end;
				}
				
				if ( $options['show_bats_throws'] ) {
					$bats = get_post_meta($post->ID, 'player_bats', true );
					$bats = ( $bats == 0 ) ? '' : $bats ;
					$throws = get_post_meta($post->ID, 'player_throws', true );
					$throws = ( $throws == 0 ) ? '' : $throws ;
					echo $row_start . $options['bats_throws_label'] . $new_cell 
									.  mstw_tr_build_bats_throws( $post ) . $row_end;
				}
				
				// If showing both height and weight show them as height/weight
				// Otherwise show just one or the other
				if ( $options['show_height'] and $options['show_weight'] ) {
					echo $row_start . $options['height_label'] . '/' . $options['weight_label'] . $new_cell .  get_post_meta($post->ID, 'player_height', true ) . '/' . get_post_meta($post->ID, 'player_weight', true ) . $row_end;
				} 
				else  if ( $options['show_weight'] ) {
						echo $row_start . $options['weight_label'] . $new_cell .  get_post_meta($post->ID, 'player_weight', true ) . $row_end;
				} 
				else if ( $options['show_height'] ) {
						echo $row_start . $options['height_label'] . $new_cell .  get_post_meta($post->ID, 'player_height', true ) . $row_end;
				}		
				
				//Year
				if ( $options['show_year'] ) {
					echo $row_start . $options['year_label'] . $new_cell . get_post_meta( $post->ID, 'player_year', true ) . $row_end;
				}
				//Age
				if ( $options['show_age'] ) {
					echo $row_start . $options['age_label'] . $new_cell . get_post_meta( $post->ID, 'player_age', true ) . $row_end;
				}
				//Experience
				if ( $options['show_experience'] ) {
					echo $row_start . $options['experience_label'] . $new_cell . get_post_meta( $post->ID, 'player_experience', true ) . $row_end;
				}
				//Hometown
				if ( $options['show_home_town'] ) {
					echo $row_start . $options['home_town_label'] . $new_cell . get_post_meta( $post->ID, 'player_home_town', true ) . $row_end;
				}
				//Last School
				if ( $options['show_last_school'] ) {
					echo $row_start . $options['last_school_label'] . $new_cell . get_post_meta( $post->ID, 'player_last_school', true ) . $row_end;
				}
				//Country
				if ( $options['show_country'] ) {
					echo $row_start . $options['country_label'] . $new_cell . get_post_meta( $post->ID, 'player_country', true ) . $row_end;
				}
				
				//Other
				if ( $options['show_other_info'] ) {
					echo $row_start . $options['other_info_label'] . $new_cell . get_post_meta( $post->ID, 'player_other', true ) . $row_end;
				}
				?>
			</tbody>
			</table> <!-- #player-info-->
			</div> <!-- #player-name-nbr -->
			
			<div id='team-logo'>
				<?php
				if ( $options['sp_show_logo'] ) {
					echo mstw_tr_build_profile_logo( $post, $team_slug, $options, 'profile' );
				} 
				?>
			</div> <!-- #team-logo -->
		</div> <!-- .player-header -->
		
		<?php //Player Bio ?>
		
		<?php if( ( $bio = $post->post_content ) != '' ) {  ?>
		
			<div class="player-bio player-bio-<?php echo $team_slug; ?> ">
					
			<?php $sp_content_title = ( $options['sp_content_title'] == '' ) ? 
					__( 'Player Bio', 'mstw-loc-domain' ) : 
					$options['sp_content_title']; ?>
						
			<h1><?php echo $sp_content_title ?></h1>

			<!--add the bio content (format it as desired in the post)-->
			<?php echo apply_filters( 'the_content', $bio ); ?>
					
			</div><!-- .player-bio -->
					
		<?php } // end of if ( ( $bio = $post->post_content ) ?>
		
		</div> <!-- #content -->
	</div> <!-- #primary -->
	
 <?php get_footer();?>