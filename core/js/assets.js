var assets = {
	
	images: {
		remove: function (src, target) {
			"use strict";
			deleteFile(src, target);
		},
		edit: function (src) {
			"use strict";
			alert(src);
		},
		makeDefault: function (dir) {
			"use strict";
			alert(dir);
		},
		clickView: function (e) {
			"use strict";
			e.preventDefault();
			
			var id = e.target.parentNode.id;
			var img = e.target.href.replace(URL,'').replace(BASE,'');

			assets.images.view(id, img);
		},
		view: function (id, img, path) {
			"use strict";
	
			function storeCoords(c) {
				$('#x1').val(c.x);
				$('#y1').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);
				$('#dir').val(dir);
				$('#file').val(file);
				$('#path').val(path);
			}
			function enableCrop(id) {
				if ($('#image_asset_'+id).is(':visible')) {
					var ratio = crop_image_width / crop_image_height;
					var aspect = (ratio<1.47) ? 4/3 : 16/9 ;
					
					$('#image_asset_'+id).Jcrop({
						onChange: storeCoords,
						onSelect: storeCoords,
						onRelease: storeCoords,
						aspectRatio: aspect
					});
					$('#modal').animate({
						height:($('#modal_inner').innerHeight())+'px'
					}, function() {
						position();
					});
				} else {
					setTimeout(function() {
						enableCrop(id);
					}, 200);
				}
			}
			
			var dir = img.replace(BASE,'').split('/');
			var file = dir.pop();
			dir = dir.join('/');
			
			if ($('#image_container_'+id).length) {
				$('#image_container_'+id).remove();
			} else {
				$('.image_container').remove();
				
				$('#'+id).append("<div id='image_container_"+id+"' class='image_container' style='margin-bottom:30px'><img width='300' style='display:block;' id='image_asset_"+id+"' src='"+BASE+dir+'/'+file+"' /><button onclick='assets.images.crop()'>Save</button></div>");
				
				setTimeout(function() {
					enableCrop(id);
				}, 200);
			}
		
		},
		
		crop: function() {
			"use strict";
			$.ajax({
				type:'POST',
				url:BASE+'/lib/ajax/assets/img_crop.php',
				data:$('#image_coords').serialize(),
				success:function() {
					$('.image_container').remove();
					exitModal();
				}
			});
		}
	}
};