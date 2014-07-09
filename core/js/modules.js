var modules = {

	installAll:function() {
		"use strict";
		$.ajax({
			type:'POST',
			url:BASE+'/core/lib/ajax/admin/modules/install_all.php',
			success:function(response) {
				if (response==="") {
					alert('All modules installed');
				}
			}
		});
	},
	
	install:function(id) {
		"use strict";
		$.ajax({
			type:'POST',
			url:BASE+'/core/lib/ajax/admin/modules/install_module.php',
			data: {
				id:id
			},
			success:function(response) {
				if (response==="") {
					alert('Module installed');
					window.location.href = window.location.href;
				}
			}
		});
	}
};