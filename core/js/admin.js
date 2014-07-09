/****************
* called on page load
*****************/
$(document).ready(function() {
	"use strict";
	
	$('.editable').bind('click', function() {
		var val = $(this).text();
		$(this).hide();
		$(this).after("<form class='inline_edit_form'><input type='text' value=\""+val.replace(/\'/g, '"')+"\" name='"+$(this).data('col')+"' /><input type='hidden' value='"+$(this).data('id')+"' name='id' /><input type='hidden' value=\""+$(this).data('table')+"\" name='table' /><input type='hidden' value='"+$(this).data('db')+"' name='db' /><br /><a href='javascript:void(0)' onclick='admin.edit.save(this.parentNode);'>save</a> / <a href='javascript:void(0)' onclick='admin.edit.revert();'>exit</a></form>");
	});

});

var admin = {
	edit: {
		revert: function(update) {
			"use strict";
			if (update!==null && update===true) {
				$('.inline_edit_form').each(function() {
					$(this).prev('.editable').html($(this).children('input[type=text]').val());
					$(this).remove();
				});
			} else {
				$('.inline_edit_form').remove();
			}
			$('.editable').show();
			
		},
		save: function(form) {
			"use strict";
			$.ajax({
				type:'POST',
				url:BASE+'/core/lib/ajax/admin/inline_edit.php',
				data:$(form).serialize(),
				success:function() {
					admin.edit.revert(true);
				}
			});
		}
	}
};