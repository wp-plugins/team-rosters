<?php
/*
 * mstw-admin-utils.php
 * 	Utility functions for MSTW Plugin Admin
 *
 *	MSTW Wordpress Plugins
 *	Copyright (C) 2013 Mark O'Donnell
 *	Contact me at http://shoalsummitsolutions.com
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
 */
 
 /*----------------------------------------------------------------	
 *	MSTW_ADMIN_UTILS_LOADED - DO NOT REMOVE THIS FUNCTION!
 *		This function is used by the require_once statement to figure
 *		out whether or not to load the utils.
 *---------------------------------------------------------------*/
	function mstw_admin_utils_loaded( ) {
		return( true );
	}


 /*----------------------------------------------------------------	
 *	MSTW_UTL_BUILD_CSS_RULE
 *		Simple convenience function to build css rules from $options.
 *---------------------------------------------------------------*/	
	function mstw_utl_build_css_rule( $options_array, $option_name, $css_rule ) {
		if ( isset( $options_array[$option_name] ) and !empty( $options_array[$option_name] ) ) {
			return $css_rule . ":" . $options_array[$option_name] . "; \n";	
		} 
		else {
			return "";
		}
	}
 /*----------------------------------------------------------------	
 *	MSTW_UTL_COLOR_CTRL
 *	Builds color selector controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['class'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	return - none. Control is displayed.
 *---------------------------------------------------------------*/
	function mstw_utl_color_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		//$class = $args['class'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		//echo "<label for='$id'>$label</label> \n";

	} //End: mstw_utl_color_ctrl
 
 /*----------------------------------------------------------------	
 *	MSTW_UTL_TEXT_CTRL
 *	Builds text format controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	return - none. Control is displayed.
 *---------------------------------------------------------------*/
	function mstw_utl_text_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		echo "<label for='$id'>$label</label> \n";
		
	} //End: mstw_utl_text_ctrl
	
/*----------------------------------------------------------------	
 *	MSTW_UTL_CHECKBOX_CTRL
 *	Builds checkbox format controls for the admin UI
 *
 * 	Arguments:
 *		$args['id'] 	(string) ID of input field 
 *		$args['name'] 	(string) Name of input field
 *		$args['value'] 	(string) Current value of input field
 *		$args['label'] 	(string) Instructions displayed after the field
 *
 *	NOTE that the checked value is always '1'.
 *
 *	Return - none. Control is displayed.
 *---------------------------------------------------------------*/
	function mstw_utl_checkbox_ctrl( $args ) { 
		$id = 		$args['id'];
		$name = 	$args['name'];
		$value = 	$args['value'];
		$label = 	$args['label'];
		
		echo "<input type='checkbox' id='$id' name='$name' value='1' " . 
				checked( '1', $value, false ) . "/> \n";  
		echo "<label for='$id'>$label</label> \n";
		
	}	//End: mstw_utl_checkbox_ctrl
	
/*----------------------------------------------------------------	
 *	Shortcut to build 'Show-Hide' Select-Option controls for the admin UI
 *	Just like mstw_select_option_ctrl with hard-wired options - 
 *	Show => 1, Hide => 0
 *
 * 	Arguments: 
 *	 $args['id'] (string)		Setting name from option array
 *	 $args['name'] (string)		Name of input field
 *	 $args['value'] (string)	Current value of setting
 *	 $args['label'] (string)	Default to use of setting is blank
 *
 *	Return - none. Output is echoed.
 *---------------------------------------------------------------*/	
	function mstw_utl_show_hide_ctrl( $args ) {
	
		$new_args = array(	'options' => array(	__( 'Show', 'mstw-loc-domain' ) => 1, 
											__( 'Hide', 'mstw-loc-domain' ) => 0, 
											),
						'id' => $args['id'],
						'name' => $args['name'],
						'value' => $args['value'],
						'label' => $args['label']
						);
		
		mstw_utl_select_option_ctrl( $new_args );
		
	}  //End: mstw_utl_show_hide_ctrl
	
/*----------------------------------------------------------------	
 *	Builds Select-Option controls for the admin UI
 *
 * 	Arguments:
 *	 $args['options'] (array)	Key/value pairs for the options 
 *	 $args['id'] (string)		Setting name from option array
 *	 $args['name'] (string)		Name of input field
 *	 $args['value'] (string)	Current value of setting
 *	 $args['label'] (string)	Default to use of setting is blank
 *
 *	Return - none. Output is echoed.
 *---------------------------------------------------------------*/
	function mstw_utl_select_option_ctrl( $args ) {
		
		$options = $args['options'];
		$name = $args['name'];
		$id = $args['id'];
		$curr_value = $args['value'];
		$label = $args['label'];
		
		echo "<select id='$id' name='$name' style='width: 160px' >";
		foreach( $options as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		echo "</select> \n";
		//echo "<label for='$id'>". $label . "</label> \n";
		echo "<label for='$id'>$label</label> \n";
		
	}  //End: mstw_utl_select_option_ctrl


/*----------------------------------------------------------------	
 *	Sanitization Functions
 *---------------------------------------------------------------*/	
	function mstw_utl_sanitize_hex_color( $color ) {
		// Check $color for proper hex color format (3 or 6 digits) or the empty string.
		// Returns corrected string if valid hex color, returns null otherwise
		
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;
	}

	function mstw_utl_sanitize_number( $number ) {

	}
?>
