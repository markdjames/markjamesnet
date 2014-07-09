/*****************************
* toggle expand / contract on expanders (see expander class)
*
* @id:Int - id of expander block
*****************************/
function expand(id) {
	"use strict";
	$('#expander'+id+' .expander_break').css('width', $('#expander'+id+' .expander_break').width()+'px');
	$('#expander'+id+' .expander_break').slideToggle(2000);
	
	if ($('#expander_toggle_'+id+' img').attr('src') === BASE+'/images/expander_close.png') {
		$('#expander_toggle_'+id+' img').attr('src', BASE+'/images/expander.png');
		$('html, body').animate({scrollTop: $("#expander"+id).offset().top-100}, 2000);
	} else {
		$('#expander_toggle_'+id+' img').attr('src', BASE+'/images/expander_close.png');
	}
}