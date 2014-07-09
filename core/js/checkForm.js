/*********************************
* validate forms - uses the css class 'required' to check
*
* @frm:String = id of relevent form
* @data:Object - OPTIONAL - ajax processing data
*							MUST INCLUDE	'processor' (path to processor script sitting below /processors/)
*											'func' (function name)
*							add debug:1 to get on-screen feedback without modifying database
* @msg:String = message to return on error OPTIONAL
*********************************/
function checkForm(frm, msg) {
	"use strict";
	
	var validated = true;
	
	$('#'+frm+' .required').each(function() {	
		// if is checkbox and not checked then not valid
		if ($(this).is('input[type=checkbox]')) {
			if (!$(this).attr('checked')) {
				validated = false;
				$(this).parent().css('color', '#f00');
				$(this).css('border-color', '#f00');
			} 
		
		// if value is empty and not a submit/button then not valid
		} else if (!$(this).val() && !$(this).is('input[type=submit]') && !$(this).is('input[type=button]')) {
			validated = false;
			$(this).parent().css('color', '#f00');
			$(this).css('border-color', '#f00');
		}
	});

	if (validated) {
		$('#'+frm).submit();

		return true;
		
	} else {
		if (!msg) { msg = 'Please fill in all required fields'; }
		alert(msg);
		return false ;
	}
	

}