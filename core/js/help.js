var help = {
	init:function() {
		$('a.help').each(function() {
			$(this).bind('click', function(event) {
				help.load(event);
			});
		});
	},
	
	load:function(e) {
		var target = e.target.getAttribute("rel");
		var helpbox = document.createElement("div");
		$(helpbox).addClass('helpbox');
		$(helpbox).css({
			left:mouseX-100,
			top:mouseY+10
		});
		$('body').append(helpbox);
		console.log(help.text(target));
		$(helpbox).html("<div class='helparrow'></div><p>"+help.text(target)+"</p>");
		
		$(document).bind('mousedown', function(event) {
			help.close(event);
		});
	},
	
	close:function(e) {
		if (e.target.getAttribute('href')==null) {
			$('.helpbox').remove();
		}
	},
	
	text: function(target) {
		var output = {
			discount: 'When you buy a ticket to 3 or more concerts you will receive a 10% discount on the face-value of the tickets. The more tickets you buy the bigger the discount!<br /><br /><a href="'+BASE+'/concerts/booking/subscriptions">Find out more</a>',
			
			transaction_fee: '',
			
			reset_password: 'Your account has been flagged as needing a password confirmation. It may be that you have requested a forgotten password email reminder or you have not logged in to the site before.',
			
			telephone: "It's possible we may need to contact you urgently in the unlikely event that a change or cancellation affects any performances you have booked tickets for.",
			
			human_test: "In order to protect our website against <a href=http://simple.wikipedia.org/wiki/Spamming' target='_blank'>spammers</a> we need to check that this form is being completed by a human user. Apologies for the inconvenience.",
			
			data_protection: "Your privacy is important to us, we want to make sure we only use your data in ways you decide. To find out more please read our <a href='/privacy'>Privacy Policy</a>.",
			
			donation: "We have added a small donation to you basket which will provide invaluable support to the orchestra. This is completely optional. <a href='/support/donate'>Find out more.</a>"
		};
		return output[target];
	}
}

$(document).ready(function() {
	help.init();
});