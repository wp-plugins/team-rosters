<?php
/**
 * The template for displaying Team Archive pages using the Team Rosters plugin.
 * This will create a 'gallery view' of the team.
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
 
	get_header( ); 
	
	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );
	
	// merge them with the defaults, so every setting has a value
	$options = wp_parse_args( $options, mstw_tr_get_defaults( ) );
				
	// Set the roster format based on the page args & plugin settings 
	$roster_type = ( isset( $_GET['roster_type'] ) && $_GET['roster_type'] != '' ) ? 
						$_GET['roster_type'] : 
						$options['roster_type'];
	
	// Get the settings for the roster format
	$settings = mstw_tr_get_fields_by_roster_type( $roster_type ); 
	
	// The roster type settings trump all other settings
	$options = wp_parse_args( $settings, $options );
	
	// figure out the team name - for the title (if shown) and for team-based styles
	$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
	$team_slug = $uri_array[sizeof( $uri_array )-2];
	$term = get_term_by( 'slug', $team_slug, 'mstw_tr_team' );
	$team_name .= $term->name;
	?>
	
	<section id="primary">
	<div id="content-player-gallery" role="main" >

	<header class="page-header page-header_<?php echo $team_slug ?>">
		<?php echo "<h1 class='team-head-title team-head-title_$team_slug'>$team_name</h1>"; ?>
	</header>

	<?php
	// Set up the hidden fields for jScript CSS 
	//$hidden_fields = mstw_tr_build_team_colors_html( $team_slug, $options );
	//mstw_log_msg( 'mstw_tr_build_team_colors_html( ) ... returned:' );
	//mstw_log_msg( $hidden_fields );
	//echo $hidden_fields;
		
	echo mstw_tr_build_gallery( $team_slug, $roster_type, $options );
	?>

	</div><!-- #content -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>