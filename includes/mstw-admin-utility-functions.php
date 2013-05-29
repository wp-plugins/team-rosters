<?php
/*
 * mstw-admin-utilities
 */
 
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
 *	return - none. Control is displayed.
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
 *	Builds Select-Option controls for the admin UI
 *
 * 	Arguments:
 *	$options (array) :	key/value pairs for the options 
 *	$args['id'] (string):			setting name  from option array
 *	$args['name']
 *	$args['value'] (string):	current value of setting
 *	$args['label'] (string):	default to use of setting is blank
 *
 *	return - none. Output is echoed.
 *---------------------------------------------------------------*/
	function mstw_select_option_ctrl( $args ) {
		
		$options = $args['options'];
		$name = $args['name'];
		$id = $args['id'];
		$curr_value = $args['value'];
		
		echo "<select id='$id' name='$name' style='width: 160px' >";
		foreach( $options as $key=>$value ) {
			//echo '<p> key: ' . $key . ' value: ' . $value .'</p>';
			$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$key</option>";
		}
		echo "</select> \n";
		echo "<label for='$id'>$label</label> \n";
		
	}  //End: mstw_select_option_ctrl
?>
