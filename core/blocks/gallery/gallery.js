var disable_gallery = false;
var current_image = 0;

function gallery(id, dir, carousel_id) {
	"use strict";
	if (!disable_gallery) {
		disable_gallery = true;
		
		if (carousel_id!==null) {
			carousel(carousel_id, dir);
		}

		if (dir==='right' && current_image<(gallery_images.length-1)) {
			current_image++;
		} else if (dir==='left' && current_image>0) {
			current_image--; 
		} else if (dir==='left') {
			current_image=gallery_images.length-1; 
		} else if (dir==='right') {
			current_image=0; 
		} else {
			disable_gallery = false;
			return false;
		}
		
		$('.gallery_img').css('height', $('.gallery_img img').height());
		$('.gallery_img img.page_image').fadeOut(function() {
			$('.gallery_img').html(gallery_images[current_image]);
			setTimeout(function() {
				$('.gallery_img img.page_image').fadeIn(function() {
					disable_gallery = false;
					// reset all images for editing 
					if (typeof is_admin !== 'undefined'){
						images.setForEdit($(this));
					}
				});
			}, 500);
		});
		
		$(".page_image").lazyload({
			effect : "fadeIn"
		});
	}
}

function galleryJump(img) {
	"use strict";
	if (!disable_gallery) {
		disable_gallery = true;
		current_image = img;
		$('.gallery_img img').fadeOut(function() {
			$('.gallery_img').html(gallery_images[img]);
			setTimeout(function() {
				$('.gallery_img img').fadeIn(function() {
					disable_gallery = false;
				});
			}, 500);
		});
	}
	//$('html, body').animate({scrollTop: $(".gallery_img").offset().top}, 1000);
}