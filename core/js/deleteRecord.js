/**************************************
* universal ajax delete function
*
* @id:Int - record dB id
* @table:String - name of database table
* @settings:Object - additional settings
***************************************/
function deleteRecord(id, table, settings) {
	"use strict";
	if (settings!=null) {
		settings.site = (settings.site!=null) ? settings.site : 'site'
	} else {
		settings = new Object();
		settings.site = 'site';
	}
	
	// merge settings with other data to send
	var data = {
			id:id,
			table:table,
			'function':'delete_record'
		};
	if (settings!==null) {
		$.extend(data, settings);
	}
	
	$.ajax({
		type:'POST',
		url:BASE+'/processors/admin/delete.php',
		data:data,
		success:function(data) {
			//alert(data);
			if (data==='fail') {
				alert("Sorry, you don't have permission to do that");
			} else {
				window.location.href=window.location.href;
			}
		},
		error:function() {
			alert('Sorry, something went wrong...');
		}
	});
}