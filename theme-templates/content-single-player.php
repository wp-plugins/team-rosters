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
 * 20130422-MAO:
 *	(1) Changed the thumbnail support to 'full'. Actual size needs to be set
 *		in WordPress->General Settings->Media
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
	
	//Set the options based on the roster format
	// Set the roster table format.  
	if ( $_GET['format'] == '' ) {
		$format = $options['roster_type'];
	} else {
		$format = $_GET['format'];
	}
			
	//echo '<p>format: ' . $format . '</p>';
		
	switch ( $format ) {
	case 'baseball-high-school':
	case 'baseball-college':
	case 'baseball-pro':
		$show_bats_throws = 1;
		break;
	default:
		$show_bats_throws = 0;
		break;
	}
	
	//echo '<h1>format: ' . $format . '  show_bats_throws: ' . $show_bats_throws . '</h1>';
		
	switch ( $format ) {
		case 'baseball-high-school':
		case 'high-school':
			$settings = array(	
				//'team'					=> 'no-team-specified',
				'roster_type'			=> $format,
				//'show_title'			=> 1,
				//'sort_order'			=> 'alpha',
				//'name_format'			=> 'last-first',
				//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
				'show_number'			=> 1,
				//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
				'show_position'			=> 1,
				'show_height'			=> 1,
				//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
				//'show_weight'			=> 1,
				//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
				'show_year'				=> 1,
				//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
				'show_experience'		=> 0,
				//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
				'show_age'				=> 0,
				//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
				'show_home_town'		=> 0,
				//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
				'show_last_school'		=> 0,
				//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
				'show_country'			=> 0,
				//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
				'show_bats_throws'		=> $show_bats_throws,
				//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
				'show_other_info'		=> 0,
				//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
			);
			break;
			
		case 'baseball-college':
		case 'college':
			$settings = array(	
				//'team'					=> 'no-team-specified',
				'roster_type'			=> $format,
				//'show_title'			=> 1,
				//'sort_order'			=> 'alpha',
				//'name_format'			=> 'last-first',
				//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
				'show_number'			=> 1,
				//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
				'show_position'			=> 1,
				'show_height'			=> 1,
				//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
				//'show_weight'			=> 1,
				//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
				'show_year'				=> 1,
				//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
				'show_experience'		=> 1,
				//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
				'show_age'				=> 0,
				//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
				'show_home_town'		=> 1,
				//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
				'show_last_school'		=> 1,
				//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
				'show_country'			=> 0,
				//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
				'show_bats_throws'		=> $show_bats_throws,
				//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
				'show_other_info'		=> 0,
				//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
			);		
			break;
		
		case 'pro':
		case 'baseball-pro':
			$settings = array(	
				//'team'					=> 'no-team-specified',
				'roster_type'			=> $format,
				//'show_title'			=> 1,
				//'sort_order'			=> 'alpha',
				//'name_format'			=> 'last-first',
				//'name_label'			=> __( 'Name', 'mstw-loc-domain' ),
				'show_number'			=> 1,
				//'number_label'			=> __( 'Number', 'mstw-loc-domain' ),
				'show_position'			=> 1,
				'show_height'			=> 1,
				//'height_label'			=> __( 'Height', 'mstw-loc-domain' ),
				//'show_weight'			=> 1,
				//'weight_label'			=> __( 'Weight', 'mstw-loc-domain' ),
				'show_year'				=> 0,
				//'year_label'			=> __( 'Year', 'mstw-loc-domain' ),
				'show_experience'		=> 1,
				//'experience_label'		=> __( 'Exp', 'mstw-loc-domain' ),
				'show_age'				=> 1,
				//'age_label'				=> __( 'Age', 'mstw-loc-domain' ),
				'show_home_town'		=> 0,
				//'home_town_label'		=> __( 'Home Town', 'mstw-loc-domain' ),
				'show_last_school'		=> 1,
				//'last_school_label'		=> __( 'Last School', 'mstw-loc-domain' ),
				'show_country'			=> 1,
				//'country_label'			=> __( 'Country', 'mstw-loc-domain' ),
				'show_bats_throws'		=> $show_bats_throws,
				//'bats_throws_label'		=> __( 'Bat/Thw', 'mstw-loc-domain' ),
				'show_other_info'		=> 0,
				//'other_info_label'		=> __( 'Other', 'mstw-loc-domain' ),
			);
			break;
			
		default:  // custom roster format
			$settings = $options;
			break;
	}
	
	//echo '<h2>ORIG OPTIONS</h2>';
	//print_r( $settings );
			
	//$options = mstw_tr_set_fields( $format, $options );
	//echo '<h2>FORMAT: ' . $format . ' OPTIONS</h2>';
	//print_r( $settings );
				
	//echo '<h2>REVISED OPTIONS</h2>';
	$options = wp_parse_args( $settings, $options );
	//print_r( $options );
	
	//$sp_main_text_color = $options['sp_main_text_color'];
	//$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	$sp_content_title = $options['sp_content_title'];
	$sp_image_width = $options['sp_image_width'];
	$sp_image_height = $options['sp_image_height'];
	
	// Single Player Page title
	$html = '<h1 class="player-head-title ';
	$player_teams = wp_get_object_terms($post->ID, 'teams');
	if( !empty( $player_teams ) ) {
		if( !is_wp_error( $player_teams ) ) {
			foreach( $player_teams as $team ) {
				$team_name = $team->name;
				$team_slug = $team->slug;
				$html .=  'player-head-title-' . $team_slug . ' ';
				//echo '<h1 class="player-head-title" style="color:' . $sp_main_text_color . ';">' . $team_name. '</h1>'; 
			}
			$html .= '">';
		}
	}
	$html .= $team_name . '</h1>';
	
	echo $html;
	?>
	
	<header class="player-header player-header-<?php echo( $team_slug ) ?>">
		<!-- First, figure out the player's photo -->
		<div id = "player-photo">
			<?php 
			// Check the settings for the height and width of the photo
			// Default is 150 x 150
			$img_width = ( $sp_image_width == '' ) ? 150 : $sp_image_width;
			$img_height = ( $sp_image_height == '' ) ? 150 : $sp_image_height;
			
			// check if the post has a Post Thumbnail assigned to it.
			if ( has_post_thumbnail( ) ) { 
				//echo get_the_post_thumbnail( get_the_ID(), 'full' );
				$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
				$alt = 'Photo of ' . $first_name . ' ' . $last_name;
				//echo '<p>Photo File: ' . $photo_file_url . '</p>';
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
				$alt = 'Default Player Photo';
				
				//echo( '<img src=' . $photo_file_url . ' alt="Default Player Photo" width="' . $img_width . '" height="' . $img_height . '" />' );
			}
			
			echo( '<img src="' . $photo_file_url . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" />' );
			?>
		</div> <!-- #player-photo -->
		
		<!-- Figure out the player name and number -->
		<div id="player-name-nbr">
			<?php if ( $options['show_number'] ) { ?>
				<div id="number"> 
					<?php echo $number; ?> 
				</div><!-- #number -->
			<?php } ?>
			<div id="player-name">
				<?php 
				switch ( $options['name_format'] ) { 
					case 'first-last':
						echo $first_name . '<br/>' . $last_name;
						break; 
					case 'first-only':
						echo $first_name;
						break;
					case 'last-only':
						echo $first_name;
						break;
					default: 
						echo $last_name . '<br/>' . $first_name; 
						break; 
				} 
				?>
			</div><!-- #player-name -->
		
			<table id= "player-info">
			<tbody>
			<?php 
				$row_start = '<tr><td class="lf-col">';
				$new_cell = ':</td><td class="rt-col">';
				$row_end = '</td></tr>';
				
				// the first two rows are (now almost) the same in all formats
				if ( $options['show_position'] ) {
					//echo $row_start . __('Position', 'mstw-loc-domain') . $new_cell .  $position . $row_end;
					echo $row_start . $options['position_label'] . $new_cell .  $position . $row_end;
				}
				
				if ( $options['show_bats_throws'] ) {
					echo $row_start . $options['bats_throws_label']. $new_cell .  $bats . '/' . $throws . $row_end;
				}
				
				// If showing both height and weight show them as height/weight
				// Otherwise show just one or the other
				if ( $options['show_weight'] and $options['show_weight'] ) {
					echo $row_start . $options['height_label'] . '/' . $options['weight_label'] . $new_cell .  $height . '/' . $weight . $row_end;
				} 
				else  if ( $options['show_weight'] ) {
						echo $row_start . $options['weight_label'] . $new_cell .  $weight . $row_end;
				} 
				else if ( $options['show_height'] ) {
						echo $row_start . $options['height_label'] . $new_cell .  $weight . $row_end;
				}		
				
				//Year
				if ( $options['show_year'] ) {
					echo $row_start . $options['year_label'] . $new_cell . $year . $row_end;
				}
				//Age
				if ( $options['show_age'] ) {
					echo $row_start . $options['age_label'] . $new_cell . $age . $row_end;
				}
				//Experience
				if ( $options['show_experience'] ) {
					echo $row_start . $options['experience_label'] . $new_cell . $experience . $row_end;
				}
				//Hometown
				if ( $options['show_home_town'] ) {
					echo $row_start . $options['home_town_label'] . $new_cell . $home_town . $row_end;
				}
				//Last School
				if ( $options['show_last_school'] ) {
					echo $row_start . $options['last_school_label'] . $new_cell . $last_school . $row_end;
				}
				//Country
				if ( $options['show_country'] ) {
					echo $row_start . $options['country_label'] . $new_cell . $country . $row_end;
				}
				?>
			</tbody>
			</table>
			<!-- </div><!-- #player-info --> 
		
		</div><!-- #player-name-nbr -->
		
	</header><!-- #player-header -->
	
	<?php if( get_the_content( ) != "" ) { ?>
		
		<div class="player-bio player-bio-<?php echo $team_slug; ?> ">
		
			<?php $sp_content_title = ($sp_content_title == '' ) ? __( 'Player Bio', 'mstw-loc-domain' ) : $sp_content_title; ?>
			
			<h1><?php echo $sp_content_title ?></h1>

			<!--add the bio content (format it as desired in the post)-->
			<?php the_content(); ?>
		
		</div><!-- #player-bio -->
		
	<?php } // end of if ( get_the_content() ) ?>

</article><!-- #post-<?php the_ID(); ?> -->
