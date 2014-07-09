/*******************
* expose a Js string to a PHP function
*******************/
function jsPhpBridge(func, args, callback) {
	"use strict";
	$.ajax({
		type:'POST',
		url:BASE+'/lib/functions/php_function_call.php',
		data:{
			func:func,
			args:args
		},
		success:function(data) {
			callback(data);
		}
	});
}