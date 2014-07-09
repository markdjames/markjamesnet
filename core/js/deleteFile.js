/**************************************
* universal ajax delete function
*
* @id:Int - record dB id
* @table:String - name of database table
* @settings:Object - additional settings
***************************************/
function deleteFile(path, target) {
	"use strict";
	if (confirm('Are you sure you wish to delete this file?')) {
		
		// append base dir 
		path = BASE+'/'+path;
		
		$.ajax({
			type:'POST',
			url:BASE+'/processors/admin/deleteFile.php',
			data:{
				path:path,
				'function':'delete_file'
			},
			success:function(data) {
				if (data==='fail') {
					alert("Sorry, you don't have permission to do that");
				} else {
					$('#'+target).fadeOut();
				}
			},
			error:function() {
				alert('Sorry, something went wrong...');
			}
		});
	}
}