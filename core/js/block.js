var block = {
	/**********************
	* load relevent block form 
	* @pageid:Int - id of page
	* @block:String - name of block, must be equivilent to file name of block form
	* @type:String - 'page' or 'module'
	**********************/
	getBlockForm:function (pageid, block, type, content) {
		"use strict";
		content = (content==null) ? false : content;

		$.ajax({
			type:'post',
			data:{
				id:pageid,
				type:type,
				content:content
			},
			url:BASE+'/blocks/'+block+'/form.php',
			success:function(data) {
				$('#block_container').html(data);
			},
			error:function() {
				$('#block_container').html("<p><em>block type not found</em></p>");
			}
		});
	}
}