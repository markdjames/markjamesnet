var feeds = {
	filters:{
		init:function() {
			$('#feed_filters input[type=checkbox]').bind('change', function() {
				feeds.filters.filter(this);
			});

			// run an initial filtering to get rid of all unchecked boxes
			$('#feed_filters input[type=checkbox]').each(function() {
				if (!$(this).hasClass('group-filter') && !$(this).prop('checked')) feeds.filters.filter(this);
			});
		},
		filter:function(target) {
			var id 	   = $(target).data('id');
			var parent = ($(target).data('parent')) ? '.'+$(target).data('parent') : '';

			var type   = ($('select[name=sort]').val()=='date') ? 'item' : 'feed' ;

			if ($(target).prop('checked')) {
				if (type=='feed') {
					$('.'+type+'.'+id+parent).each(function() {
						var feed = $(this).detach();
						$('#feeds').prepend(feed);
					});
					$('.'+id).fadeIn();
				} else {
					$('.'+type+'.'+id+parent).fadeIn();
				}
				
				if ($(target).hasClass('group-filter')) {
					$('.sub-filter-'+id).prop('checked', true);
				}
			} else {
				$('.'+type+'.'+id+parent).each(function() {
					$(this).fadeOut( function() {
						if (type=='feed') {
							var feed = $(this).detach();
							$('#hidden_feeds').prepend(feed);
						}
					});
				});
				if ($(target).hasClass('group-filter')) {
					$('.sub-filter-'+id).prop('checked', false);
				}
			}
		}
	}
}

window.onload = function() {
	feeds.filters.init();
}
