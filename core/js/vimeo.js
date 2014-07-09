var vimeo = {
	/**
	 * Called once a vimeo player is loaded and ready to receive
	 * commands. You can add events and make api calls only after this
	 * function has been called.
	 */
	ready:function(player_id) {

		// Keep a reference to Froogaloop for this player
		var froogaloop = $f(player_id);

		/**
		 * Prepends log messages to the example console for you to see.
		 */
		function apiLog(message) {
			console.log(message);
		}
		
		froogaloop.addEvent('loadProgress', function(data) {
			
			/*apiLog('loadProgress event : ' + data.percent + ' : ' + data.bytesLoaded + ' : ' + data.bytesTotal + ' : ' + data.duration);
			
			var loaded = (data.duration/100)*(data.percent*100);
		
			$('.'+player_id).each(function() {				
				if ($(this).data('time') < loaded) {
					$(this).removeClass('vimeo_disabled');
				}
			});*/
								
		});

		/**
		 * Sets up the actions for the buttons that will perform simple
		 * api calls to Froogaloop (play, pause, etc.). These api methods
		 * are actions performed on the player that take no parameters and
		 * return no values.
		 */
		// Call play when play button clicked
		$('.play').bind('click', function() {
			froogaloop.api('play');
		});

		// Call pause when pause button clicked
		$('.pause').bind('click', function() {
			froogaloop.api('pause');
		});

		// Call unload when unload button clicked
		$('.unload').bind('click', function() {
			froogaloop.api('unload');
		});

		// Call seekTo when seek button clicked
		$('.seek').bind('click', function(e) {
			// Don't do anything if clicking on anything but the button (such as the input field)
			if (e.target != this) {
				return false;
			}

			// Grab the value in the input field
			var seekVal = this.querySelector('input').value;

			// Call the api via froogaloop
			froogaloop.api('seekTo', seekVal);
		});

		// Call setVolume when volume button clicked

		$('.volume').bind('click', function(e) {
			// Don't do anything if clicking on anything but the button (such as the input field)
			if (e.target != this) {
				return false;
			}

			// Grab the value in the input field
			var volumeVal = this.querySelector('input').value;

			// Call the api via froogaloop
			froogaloop.api('setVolume', volumeVal);
		});
	

		// Get the current time and log it to the API console when time button clicked
		$('.time').bind('click', function(e) {
			froogaloop.api('getCurrentTime', function (value, player_id) {
				apiLog('getCurrentTime : ' + value);
			});
		});

		// Get the duration and log it to the API console when time button clicked
		$('.duration').bind('click', function(e) {
			froogaloop.api('getDuration', function (value, player_id) {
				apiLog('getDuration : ' + value);
			});
		});

		// Get the video url and log it to the API console when time button clicked
		$('.url').bind('click', function(e) {
			froogaloop.api('getVideoUrl', function (value, player_id) {
				apiLog('getVideoUrl : ' + value);
			});
		});

		// Get the embed code and log it to the API console when time button clicked
		$('.embed').bind('click', function(e) {
			froogaloop.api('getVideoEmbedCode', function (value, player_id) {
				// Use html entities for less-than and greater-than signs
				value = value.replace(/</g, '&lt;').replace(/>/g, '&gt;');

				apiLog('getVideoEmbedCode : ' + value);
			});
		});

		// Get the paused state and log it to the API console when time button clicked
		$('.paused').bind('click', function(e) {
			froogaloop.api('paused', function (value, player_id) {
				apiLog('paused : ' + value);
			});
		});

		// Get the paused state and log it to the API console when time button clicked
		$('.getVolume').bind('click', function(e) {
			froogaloop.api('getVolume', function (value, player_id) {
				apiLog('volume : ' + value);
			});
		});

		// Get the paused state and log it to the API console when time button clicked
		$('.width').bind('click', function(e) {
			froogaloop.api('getVideoWidth', function (value, player_id) {
				apiLog('getVideoWidth : ' + value);
			});
		});

		// Get the paused state and log it to the API console when time button clicked
		$('.height').bind('click', function(e) {
			froogaloop.api('getVideoHeight', function (value, player_id) {
				apiLog('getVideoHeight : ' + value);
			});
		});
	
		var prev_elem = null;
		froogaloop.addEvent('playProgress', function(data) {
			//apiLog('playProgress event : ' + data.seconds + ' : ' + data.percent + ' : ' + data.duration);
			
			$('.'+player_id).each(function() {				
				if (parseFloat($(this).data('time'))-1 < parseFloat(data.seconds) && 
					(!$(this).hasClass('vimeo_active') && !$(this).hasClass('vimeo_past'))) 
					{
						$(this).addClass('vimeo_active');
						
						if (prev_elem!=null) {
							prev_elem.removeClass('vimeo_active');
							prev_elem.addClass('vimeo_past');
						}
						prev_elem = $(this);
				}
			});
		});
	
		froogaloop.addEvent('play', function(data) {
			//apiLog('play event');
		});
	
		froogaloop.addEvent('pause', function(data) {
			//apiLog('pause event');
		});
	
		froogaloop.addEvent('finish', function(data) {
			//apiLog('finish');
		});

		froogaloop.addEvent('seek', function(data) {
			//apiLog('seek event : ' + data.seconds + ' : ' + data.percent + ' : ' + data.duration);
		});
	},
	
	cue: function (val, cls) {
		$('.'+cls).each(function() {
			froogaloop = $f($(this).attr('id'));

			froogaloop.api('seekTo', val);
			froogaloop.api('play');
		});				
	},
	
	seek: function (val, id, percent) {
		froogaloop = $f(id);
		
		$('.vimeo_past').each(function() {				
			$(this).removeClass('vimeo_past');		
		});
		
		if (percent==null || !percent) {
			froogaloop.api('seekTo', val);
			froogaloop.api('play');
		} else {
			var duration = 0;
			
			froogaloop.api('getDuration', function (value, player_id) {
				duration = parseInt(value);
				
				seekpos = (duration/100) * val;
				console.log(duration, seekpos);
				// Call the api via froogaloop
				froogaloop.api('seekTo', seekpos);
				froogaloop.api('play');
			});
		}				
	}
};

$(document).ready(function() {	
	$('.vimeo_film').each(function() {
		$f($(this).attr('id')).addEvent('ready', vimeo.ready);
	});	
});