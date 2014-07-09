var cookies = {
	set: function(action) {
		$.ajax({
			type:'POST',
			url:BASE+'/lib/ajax/cookies.php',
			data:{
				action:action
			},
			success:function(response) {
				if (action=='block') window.location.href=window.location.href;
				
				if ($('#cookie_warning').length>0) {
					$('#cookie_warning').animate({
						bottom:'-210px'
					}, function() {
						$('#cookie_warning').remove();
					});
				} else {
					window.location.href=window.location.href;
				}
			}
		});
	}
}