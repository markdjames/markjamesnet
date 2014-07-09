var navigation = {
	orderSetup: function() {
		"use strict";
		$(function() {
			$( "#section_navigation" ).sortable({
				stop:function(event, ui) {
					$.ajax({
						type:'POST',
						url:BASE+'/core/lib/ajax/admin/navigation/order_navigation.php',
						data:{
							order:$( "#section_navigation").sortable( "serialize" )
						},
						success:function(data) {
							//console.log(data);
						}
					});
				}
			});
			$( ".section_navigation ul li ul" ).disableSelection();
		});
	},
	
	mobile: {
		touchX: null,
		touchY: null,
		opening:false,
		
		position: function() {
			var h = $(window).innerHeight();
			$('.navtab').css({
				paddingTop:(((h/100)*31)/3)+'px',
				height:((((h/100)*31)/3)*2)+'px'
			});
			$('#navtabs').css({
				height:h
			});
			
			if (MOBILE) {
				$('#container, #mobile_nav').hammer().on("drag swipe", function(event) {
					if(event.type=='drag' && (event.gesture.direction=='left' || event.gesture.direction=='right')) {
						event.gesture.preventDefault();
						console.log(event);
					}
					if(event.type=='swipe' && event.gesture.direction=='left') {
						event.gesture.preventDefault();
						navigation.mobile.openNavigation();
					} else if(event.type=='swipe' && event.gesture.direction=='right') {
						event.gesture.preventDefault();
						navigation.mobile.closeNavigation();
					} else {
						return;
					}
	
				});
			}
			
		},
		toggleNavigation: function() {
			if ($('#mobile_nav').css('right')=='0px') {
				navigation.mobile.closeNavigation();
			} else {
				navigation.mobile.openNavigation();
				
			}	
		},
		
		i: 0,
		fadeTabs: function() {
			$('#navtab'+navigation.mobile.i).fadeIn();
			navigation.mobile.i++;
			if (navigation.mobile.i<6) {
				setTimeout(function() {
					navigation.mobile.fadeTabs();
				}, 200);
			}
		},
		openNavigation: function() {
			if (navigation.mobile.opening == false) {
				console.log("opening");
				navigation.mobile.opening = true;
				$('#mobile_nav').animate({
					right:'0px'
				}, function() {
					navigation.mobile.opening = false;
				});
				$('#main').bind('click', function() {
					navigation.mobile.closeNavigation();
				});
			} 
		},
		closeNavigation: function() {
			if (navigation.mobile.opening == false) {
				console.log("closing");
				navigation.mobile.opening = true;
				$('#mobile_nav').animate({
					right:'-1800px'
				}, function() {
					navigation.mobile.opening = false;
				});
				$('#main').unbind('click');
			}
		}
	}
};

$(document).ready(function() {
	$('#navtabs').hide();
	navigation.mobile.position();
	$('.navtab').bind('click', function() { navigation.mobile.closeNavigation() });
});
$(window).resize(function() {
	navigation.mobile.position()
});