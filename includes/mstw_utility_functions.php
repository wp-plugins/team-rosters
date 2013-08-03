<?php
/*
 *	MSTW Functions
 *	A set functions useful across the MSTW plugins that we want to require_once()
 *
 *
 */

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