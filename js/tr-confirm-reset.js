function tr_confirm_reset_defaults( ) {
	var answer = confirm("Reset the default settings?");
	if ( answer ){
		//alert( "Aye-aye, it shall be so. ");
		document.getElementById('reset_btn').value = "Resetting Defaults";
	}
	else{
		//alert("Nevermind");
		document.getElementById('reset_btn').value = "Cancelling Reset";
		//document.getElementById('target')Event.preventDefault();
	}
	
}