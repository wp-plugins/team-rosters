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
 * 20130130-MAO:
 *	(1) Added support for image size settings 
 * 20130202-MAO:
 *	(1) Added support for baseball formats 
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
	$bats = get_post_meta($post->ID, '_mstw_tr_bats', true );
	$throws = get_post_meta($post->ID, '_mstw_tr_throws', true );

	$options = get_option( 'mstw_tr_options' );
	$sp_main_text_color = $options['sp_main_text_color'];
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	$sp_content_title = $options['sp_content_title'];
	$sp_image_width = $options['sp_image_width'];
	$sp_image_height = $options['sp_image_height'];
	
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
			// Check the settings for the height and width of the photo
			// Default is 150 x 150
			if ( $sp_image_width == '' ) {  // no setting provided
				$img_width = '150';
			} else {
				$img_width = $sp_image_width;
			}
			
			if ( $sp_image_height == '' ) {  // no setting provided
				$img_height = '150';
			} else {
				$img_height = $sp_image_height;
			}
			
			// check if the post has a Post Thumbnail assigned to it.
			
			if ( has_post_thumbnail( ) ) { 
				echo get_the_post_thumbnail( get_the_ID(), array($img_width, $img_height) );
			} else {
				// Default image is tied to the team taxonomy. 
				// Try to load default-photo-team-slug.jpg, If it does not exst,
				// Then load default-photo.jpg from the plugin -->
				$photo_file = WP_PLUGIN_DIR . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
				if ( file_exists( $photo_file ) ) {
					$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '-' . $team_slug . '.jpg';
				}
				else {
					$photo_file_url = plugins_url() . '/team-rosters/images/default-photo' . '.jpg';
				}
				
				echo( '<img src=' . $photo_file_url . ' alt="Default Player Photo" width="' . $img_width . '" height="' . $img_height . '" />' );
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
			
			// Set the roster table format.  
			if ( $_GET['format'] == '' ) {
				$format = $options['tr_table_default_format'];
			} else {
				$format = $_GET['format'];
			}
			
			// the first two rows are (now almost) the same in all formats
			echo $row_start . __('Position', 'mstw-loc-domain') . $new_cell .  $position . $row_end;
			if ( strpos( $format, 'baseball' ) !== false ) { //baseball format add bats/throws
				echo $row_start . __('Bat', 'mstw-loc-domain') . '/' .  __('Thw', 'mstw-loc-domain') . $new_cell .  $bats . '/' . $throws . $row_end;
			}
			
			if ( $weight == '' ) { // don't show the weight
				echo $row_start . __('Ht', 'mstw-loc-domain') . $new_cell .  $height . $row_end;
			} 
			else { //show the weight
				echo $row_start . __('Ht', 'mstw-loc-domain') . '/' .  __('Wt', 'mstw-loc-domain') . $new_cell .  $height . '/' . $weight . $row_end;
			}
				
			
			switch ( $format ) { 
				case "college":
				case "baseball-college":
					echo $row_start . __('Year', 'mstw-loc-domain') . $new_cell .  $year . $row_end;
					echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
					echo $row_start . __('Hometown', 'mstw-loc-domain') . $new_cell . $home_town . $row_end;
					echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
					break;
				case "pro": 
				case "baseball-pro":
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

		//add the bio content (format it as desired in the post)
		the_content(); ?>
		
		</div><!-- #player-bio -->
		
	<?php } // end of if ( get_the_content() ) ?>

</article><!-- #post-<?php the_ID(); ?> -->
