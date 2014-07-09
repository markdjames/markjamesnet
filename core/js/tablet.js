var tablet = {
	
	chooseStyle: function() {
		if ($(window).innerWidth() < $(window).innerHeight()) {
			$('#tablet_css').attr('href', BASE+'/themes/'+THEME+'/css/tablet.css');

		} else if ($(window).innerWidth() > $(window).innerHeight()) {
			$('#tablet_css').attr('href', BASE+'/themes/'+THEME+'/css/tablet_horizontal.css');
			
		}
	}
	
}