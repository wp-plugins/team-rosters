<?php
/*
 * mstw-admin-utilities
 */
 
 /*----------------------------------------------------------------	
 *	MSTW_COLOR_CTRL
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
	function mstw_color_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		$class = $args['class'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo $label;
		//echo "<input type='text' id='$id' name='$name' class='$class' value='$value' />";
		//echo "<label for='$id'>$label</label> \n";
		
		//echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		//echo "<label for='$id'>$label</label> \n";
		
	} //End: mstw_color_ctrl
 
 /*----------------------------------------------------------------	
 *	MSTW_TEXT_CTRL
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
	function mstw_text_ctrl( $args ) { 
		$id = $args['id'];
		$name = $args['name'];
		$value = $args['value'];
		$label = $args['label'];
		
		echo "<input type='text' id='$id' name='$name' value='$value' /> \n";
		echo "<label for='$id'>$label</label> \n";
		
	} //End: mstw_text_ctrl
	
/*----------------------------------------------------------------	
 *	MSTW_CHECKBOX_CTRL
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
	function mstw_checkbox_ctrl( $args ) { 
		$id = 		$args['id'];
		$name = 	$args['name'];
		$value = 	$args['value'];
		$label = 	$args['label'];
		
		echo "<input type='checkbox' id='$id' name='$name' value='1' " . 
				checked( '1', $value, false ) . "/> \n";  
		echo "<label for='$id'>$label</label> \n";
		
	}	//End: mstw_checkbox_ctrl
	
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
	/*function mstw_show_hide_ctrl( $args ) {
	
		$options = array( 'Show' => 1, 'Hide' => 0 );
		$name = $args['name'];
		$id = $args['id'];
		$curr_value = $args['value'];
		$label = $args['label'];
		
		echo "<select id='$id' name='$name' style='width: 160px' >";
		foreach( $options as $key=>$value ) {
			$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		echo "</select> \n";
		echo "<label for='$id'>$label</label> \n";
		
	}  //End: mstw_show_hide_ctrl
	*/
	
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
	function mstw_select_option_ctrl( $args ) {
		
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
		
	}  //End: mstw_select_option_ctrl


/*----------------------------------------------------------------	
 *	Sanitization Functions
 *---------------------------------------------------------------*/	
	function mstw_sanitize_hex_color( $color ) {
		// Check $color for proper hex color format (3 or 6 digits) or the empty string.
		// Returns corrected string if valid hex color, returns null otherwise
		
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;
	}

	function mstw_sanitize_number ( $number ) {

	}
?>
