function switchTab(target) {
	"use strict";
	var t = target.id.replace('_tab', '');
	var rel = $(target).attr('rel');
	
	$('.'+rel+'_section').css('display', 'none');
	$('#'+t).fadeIn(1000);
	
	if (rel==='login') {
		$('.tab[rel='+rel+']').css('display', 'inline');
		$('#'+target.id).css('display', 'none');
	}
	
	$('#'+t+' .ckeditor').each(function() {
		CKEDITOR.instances[this.id].destroy(true); 
		CKEDITOR.replace(this.id, {
			toolbar:
				[
					['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'Source' ],
					['UIColor']
				]
		});		
	});
	resizeModal();
}