function switchLanguage(lang) {
	"use strict";
	$.ajax({
		type:'POST',
		url:BASE+'/lib/ajax/switch_language.php',
		data:{
			lang:lang
		},
		success:function() {
			window.location.href = window.location.href;
		}
	});
}