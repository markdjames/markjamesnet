<?php
require_once '../../lib/bootstrap.php';

if (is_numeric($_POST['id'])) {
	$block = $b->getBlock($_POST['id'], 'gallery', $_POST['type'], $_POST['content']);

	$images = ($block) ? json_decode($block['images'], true) : NULL;
	?>
    <h4 style='clear:both'>Gallery Block</h4>
    <label>Title<br />
    <input type="text" name="block[title]" value="<?=$block['title']?>" /></label>
    
    <label>Description<br />
    <textarea name="block[description]" class='ckeditor'><?=$block['description']?></textarea></label>
    
    <div class='upload_form'>
    	<input style='float:right' type="button" onclick="($('#image_file').val()!='')?uploadFile('assets/images/galleries/<?=$_POST['id']?>', addImageToGallery()):alert('please select a file to upload');" value="Upload" />
        <label for="fileToUpload">Select a File to Upload<br />
        <input type="file" name="image_file" id="image_file" onchange="uploadFileSelected(this.id, new Array('jpg','png','gif','jpeg'));"/></label>
        <div style='clear:both' id='upload_details'>
            <p class='upload_status' id="progressNumber" style='float:right; font-size:30px'></p>
            <p class='upload_status' id="fileName"></p>
            <p class='upload_status' id="fileSize"></p>
            <p class='upload_status' id="fileType"></p>
        </div>     
        <div style='clear:both'></div>   
	</div>
    
    <input type='button' value='Edit Order' style='float:right' onclick='editGalleryOrder()' />
    <p>Images File(s)</p>
    <div id='gallery_images'>
    	<?php
		if (count($images)) {
			$k=1;
		    foreach($images as $img) {
				?>
                <div class='gallery_image'>
                	<?php 
					$image->lazyload = false;
					echo $image->outputImage('/assets/images/galleries/'.$_POST['id']."/".$img['src'], '', 100, 56, 'float:left; margin-right:20px;');
					?>
                    <label style='float:left; clear:none;'><input type="checkbox" checked name="block[images][<?=$k?>][src]" value="<?=$img['src']?>" /> <?=basename($img['src'])?></label>
                    <div style='clear:both'></div>
                    <label style='float:left; margin-right:20px; width:50%'>Caption<br /><input type="text" name="block[images][<?=$k?>][caption]" value="<?=$img['caption']?>" /></label>
                    <label style='float:left; clear:none; width:45%'>Image Credit<br /><input type="text" name="block[images][<?=$k?>][credit]" value="<?=$img['credit']?>" /></label>
                    <div style='clear:both'></div>
                    <label >Description<br /><input type="text" name="block[images][<?=$k?>][description]" value="<?=$img['description']?>" /></label>
                </div>
                <?php
				$k++;				
			}
		}
		?>
    </div>
    
    <input type="hidden" name="block_id" value="<?=$block['id']?>" />
    <input type="hidden" name="block_name" value="gallery" />
    <input type="button" style='float:right' onclick="checkForm('page_blocks')" value="<?=(empty($block['id'])) ? "Add Block" : "Update Block"?>" />
   	<?php 
	if (!empty($block['id'])) {
		?>
    	<input type="button" style='clear:left; float:left;'onclick="deleteRecord(<?=$block['id']?>, 'block_gallery', {type:'blocks',category:'gallery'})" value='Delete' />
		<?php
	}
	?>
    <script type="text/javascript">
		var current_image_count = <?=(count($images))?count($images):0;?>;

		function addImageToGallery() {
			var file = $('#image_file').val().split('\\');
			var name = jsPhpBridge('urlify', file[file.length-1], function(data) {
				$('#gallery_images').append('<div class="gallery_image"><label><input type="checkbox" checked name="block[images]['+current_image_count+'][src]" value="'+data+'" /> '+data+'</label><label style="float:left; margin-right:20px; width:50%">Caption<br /><input type="text" name="block[images]['+current_image_count+'][caption]" value="" /></label><label style="float:left; clear:none; width:45%">Image Credit<br /><input type="text" name="block[images]['+current_image_count+'][credit]" value="" /></label><div style="clear:both"></div><label >Description<br /><input type="text" name="block[images]['+current_image_count+'][description]" value="" /></label></div>');
			});

			current_image_count++;

			setTimeout(function() {
				$('.upload_status').html('');
				$('#image_file').val('');
			}, 1000);
		}
		
		var gallery_sorting_mode = false;
		function editGalleryOrder() {
			if (gallery_sorting_mode == false) {
				$('.gallery_image').addClass('ordering_gallery');
				$( "#gallery_images" ).sortable();
				$( "#gallery_images" ).disableSelection();
				gallery_sorting_mode = true;
			} else {
				$('.gallery_image').removeClass('ordering_gallery');
				$( "#gallery_images" ).sortable('destroy');
				$( "#gallery_images" ).enableSelection();
				gallery_sorting_mode = false;
			}
		}
	</script>
    <?php
}