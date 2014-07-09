var email = {
	send: function(subject,message,address) {
		"use strict";
		if (email==null) {
			address = '';
		}
		$.ajax({
			type:'POST',
			url:BASE+'/lib/ajax/email/send.php',
			data:{
				subject:subject,
				message:message,
				email:address
			},
			success:function(response) {
				//console.log(response);
			}
		});
	}
};