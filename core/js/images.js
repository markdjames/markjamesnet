var images = {
	path : '',
	/***************
	* pass a class to switch all relevent images to editable
	***************/
	setForEdit: function(elem) {
		"use strict";
		if ($(elem).is(':visible')) {
			var w = elem.width();		
			var h = elem.height();	
			
			elem.css({
				width:w,
				height:'auto'
			});
			
			var marg = null;
			/*******************
			* check if image is wrapped in a link, if so wrap that, if not just wrap the image
			*******************/
			if (elem.parent().is('a')) {
				marg = $(this).parent().css('marginBottom');
				if (marg=='undefined') marg='1%';
				elem.parent().wrap("<div class='img_cropper' style='width:"+w+"px; height:auto; float:left; margin-bottom:"+marg+"' />");
				elem.parent().parent().append("<img src='"+BASE+"/images/icons/edit16.png' class='img_edit' />");
				
				elem.parent().parent().children('.img_edit').bind('click', function(e) {
					e.preventDefault();
					modal(0, 'admin/assets/image_edit', 'auto', {src:elem.data('loc'), path:images.path, width:elem.innerWidth(), height:elem.innerHeight()});
				});
			} else {
				marg = '1%'; //$(this).css('marginBottom');
				elem.wrap("<div class='img_cropper' style='width:"+w+"px; height:auto; float:left; margin-bottom:"+marg+"' />");
				
				elem.parent().append("<img src='"+BASE+"/images/icons/edit16.png' class='img_edit' />");
				
				elem.parent().children('.img_edit').bind('click', function(e) {
					e.preventDefault();
					modal(0, 'admin/assets/image_edit', 'auto', {src:elem.data('loc'),path:images.path, width:elem.innerWidth(), height:elem.innerHeight()});
				});
				
			}
		}
	}
};