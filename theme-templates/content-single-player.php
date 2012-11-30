<?php
/**
 * The MSTW Team Rosters template for displaying content in the 
 * single-player.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
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

	$options = get_option( 'mstw_tr_options' );
	$sp_main_text_color = $options['sp_main_text_color'];
	$sp_main_bkgd_color = $options['sp_main_bkgd_color'];
	
	// Single Player Page title
	$player_teams = wp_get_object_terms($post->ID, 'teams');
	if( !empty( $player_teams ) ) {
		if( !is_wp_error( $player_teams ) ) {
			foreach( $player_teams as $team ) {
				echo '<h1 class="player-head-title" style="color:' . $sp_main_text_color . ';">' . $team->name. '</h1>'; 
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
				// image should be tied to the category with a cat-default name -->
				// if this file is not there, then load a default from the plugin -->
				echo( '<img src=' . plugins_url( ) . 
						'/mstw-team-rosters/images/default-photo.jpg alt="Default Player Photo" width="150px" height="150" />' );
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
				case "pro": //Note that 'year' is dual-purposed; it's age for pros
					echo $row_start . __('Age', 'mstw-loc-domain') . $new_cell . $year . $row_end;  
					echo $row_start . __('Experience', 'mstw-loc-domain') . $new_cell . $experience . $row_end;
					echo $row_start . __('Last School', 'mstw-loc-domain') . $new_cell . $last_school . $row_end;
					echo $row_start . __('Country', 'mstw-loc-domain') . $new_cell . $home_town . $row_end;
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
		$html .= '>Player Bio</h1>';
		
		echo $html;
		
		//add the bio content (format it as desired in the post)
		the_content(); ?>
		
		</div><!-- #player-bio -->
		
	<?php } // end of if ( get_the_content() ) ?>

</article><!-- #post-<?php the_ID(); ?> -->
