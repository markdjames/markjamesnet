var maps = {
	
	geocoder:null,
	latlng:null,
	map:null,
	
	init: function(lat, long) {
		"use strict";
		maps.geocoder = new google.maps.Geocoder();
		if (lat!=null && long!=null) {
			maps.latlng = new google.maps.LatLng(lat,long);
		} else {
			maps.latlng = new google.maps.LatLng(51.505652,-0.116439);
		}
		var mapOptions = {
			center: maps.latlng,
			zoom: 14,
			disableDefaultUI: true,
			zoomControl: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        maps.map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	},
	
	geocode:function(address) {
		"use strict";
		maps.geocoder.geocode( { 'address': address}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				maps.map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: maps.map,
					position: results[0].geometry.location
				});
			} else {
				console.log("Geocode was not successful for the following reason: " + status);
			}
		});
	}
};
var venue_address = null;
var venue_lat = null;
var venue_long = null;
$(document).ready(function() {
	"use strict";
	if ($('#venue').length>0 && venue_address!==null) {
		maps.init();
		maps.geocode(venue_address);
		
	} else if ($('#venue').length>0 && venue_lat!==null) {
		// if longitude and latitude available
		maps.init(venue_lat, venue_long);
		maps.map.setCenter(maps.latlng);
		var marker = new google.maps.Marker({
			map: maps.map,
			position: maps.latlng
		});
	}	
});