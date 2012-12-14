<?php
/**
 * The MSTW Team Rosters template for displaying content in the 
 * single-player.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
/*----------------------------------------------------------------------
 * CHANGE LOG
 *----------------------------------------------------------------------
 * 20121211-MAO:
 *	(1) Added option to change content title (e.g., "Player Bio") based on
 *		the admin setting.
 *	(2) Updated pro and college player info display.
 *
 -----------------------------------------------------------------------*/
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	$first_name = get_post_meta($post->ID, '_mstw_tr_first_name', true );
	$last_name = get_post_meta($post->ID, '_mstw_tr_last_name', true );
	$number = get_post_meta($post->ID, '_mstw_tr_number', true );
	$position = get_post_meta($post->ID, '_mstw_tr_position', true );
	$height = get_post_meta($post->ID, '_mstw_tr_height', true );
	$weight = get_post_meta($post->ID, '_mstw_tr_weight', true );
	$year = get_post_meta($post->ID, '_mstw_tr_year', true );
	$experience = get_post_meta($post->ID, '_mstw_tr_experience', true );
	$last_school = get_post_meta($post->ID, '_mstw_tr_last_school', true );
	$home_town = get_post_meta($post->ID, '_mstw_tr_home_town', true );
	$country = get_post_meta($post->ID, '_mstw_tr_country', true );
	$age = get_post_meta($post->ID, '_mstw_tr_age', true );

	$options = get_option( 'mstw_tr_options' );
	$sp_main_text_color = $options['sp_main_text_color'];
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	$sp_content_title = $options['sp_content_title'];
	
	// Single Player Page title
	$player_teams = wp_get_object_terms($post->ID, 'teams');
	if( !empty( $player_teams ) ) {
		if( !is_wp_error( $player_teams ) ) {
			foreach( $player_teams as $team ) {
				$team_name = $team->name;
				$team_slug = $team->slug;
				echo '<h1 class="player-head-title" style="color:' . $sp_main_text_color . ';">' . $team_name. '</h1>'; 
			}
		}
	}
	?>
	
	<header id="player-header" style="color:<?php echo $sp_main_text_color . ';'?> background-color:<?php echo $sp_main_bkgd_color . ';'?> ">
		<div id = "player-photo">
			<?php 
			// check if the post has a Post Thumbnail assigned to it.
			 if ( has_post_thumbnail() ) { 
				the_post_thumbnail( 'full' );
			} else {
				// Default image is tied to the team taxonomy. Try to load default-photo-team-slug.jpg -->
				// If it does not exst, then load default-photo.jpg from the plugin -->
				$photo_file = WP_PLUGIN_DIR . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
				//echo '<h3>' . plugin_dir_path( 'team-rosters' ) . '</h3>';
				if ( file_exists( $photo_file ) ) {
					$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
				}
				else {
					$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '.jpg';
				}
				echo( '<img src=' . $photo_file_url . ' alt="Default Player Photo" width="150px" height="150" />' );
			}
			?>
		</div> <!-- #player-photo -->
		
		<div id = "player-name-nbr">
			<div id="number"> <?php echo $number; ?> </div>
			<div id="player-name"><?php echo $first_name . '<br/>' . $last_name; ?></div>
		
		<!-- <div id = "player-info"> -->
		<table id= "player-info">
		<tbody>
		<?php 
			$row_start = '<tr><td class="lf-col">';
			$new_cell = ':</td><td class="rt-col">';
			$row_end = '</td></tr>';
			
			// the first two rows are the same in all formats
			echo $row_start . __('Position', 'mstw-loc-domain') . $new_cell .  $position . $row_end;
			echo $row_start . __('Ht', 'mstw-loc-domain') . '/' .  __('Wt', 'mstw-loc-domain') . $new_cell .  $height . '/' . $weight . $row_end;	
			
			switch ( $_GET["format"] ) { 
				case "college": 
					echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell .  $year . $row_end;
					echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
					echo $row_start . __('Hometown', 'mstw-loc-domain') . $new_cell . $home_town . $row_end;
					echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
					break;
				case "pro": 
					echo $row_start . __('Age', 'mstw-loc-domain') . $new_cell . $age . $row_end;  
					echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
					echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
					echo $row_start . __('Country', 'mstw-loc-domain') . $new_cell . $country . $row_end;
					break;
			default: // default to high-school, the lowest common demononator
				echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell . $year . $row_end;
				break;
			} ?>
		</tbody>
		</table>
		<!-- </div><!-- #player-info --> 
		
		</div><!-- #player-name-nbr -->
		
	</header><!-- #player-header -->
	
	<?php if( get_the_content( ) != "" ) { 
		// create the player-bio div
		$html = '<div id="player-bio" ';
		// if color is set in admin settings, add it to the element style
		if ( $sp_main_bkgd_color != '' ) {
			//add the style attribute
			$html .= 'style="border-color:' . $sp_main_bkgd_color . ';" ';
		}
		$html .= '>';
		
		echo $html;
		
		// great the header for the bio
		$html = '<h1 ';
		
		if ( $sp_main_text_color != '' ) {
			//add the style attribute
			$html .= 'style="color:' . $sp_main_text_color . ';" ';
		}
		if ( $sp_content_title == '' ) {
			$sp_content_title = 'Player Bio';
		}
		
		$html .= '>' . $sp_content_title . '</h1>';
		echo $html;
		/*echo '<h3>' . $photo_file . '</h3>';
		echo '<h3>' . $photo_file_url . '</h3>';
		echo '<h3>' . 'ABSPATH: ' . ABSPATH . '</h3>';
		echo '<h3>' . 'WP_CONTENT_DIR: ' . WP_CONTENT_DIR . '</h3>';
		echo '<h3>' . 'WP_PLUGIN_DIR: ' . WP_PLUGIN_DIR . '</h3>';*/

		
		//add the bio content (format it as desired in the post)
		the_content(); ?>
		
		</div><!-- #player-bio -->
		
	<?php } // end of if ( get_the_content() ) ?>

</article><!-- #post-<?php the_ID(); ?> -->
