<?php
require_once '../../lib/bootstrap.php';

if (is_numeric($_POST['id'])) {
	$block = $b->getBlock($_POST['id'], 'audio', $_POST['type'], $_POST['content']);

	$files = NULL;
	if ($block) {
		$files = json_decode($block['files'], true);
	}
	?>
    <h4 style='clear:both'>Audio Block</h4>
    <label>Title<br />
    <input type="text" class='require_onced' name="block[title]" value="<?=$block['title']?>" /></label>
    
    <div class='upload_form'>
    	<input style='float:right' type="button" onclick="($('#image_file').val()!='')?uploadFile('assets/audio/<?=$_POST['id']?>', addFiles()):alert('please select a file to upload');" value="Upload" />
        <label for="fileToUpload">Select a File to Upload<br />
        <input type="file" name="files_file" id="files_file" onchange="uploadFileSelected(this.id, new Array('mp3'));"/></label>
        <div style='clear:both' id='upload_details'>
            <p class='upload_status' id="progressNumber" style='float:right; font-size:30px'></p>
            <p class='upload_status' id="fileName"></p>
            <p class='upload_status' id="fileSize"></p>
            <p class='upload_status' id="fileType"></p>
        </div>     
        <div style='clear:both'></div>   
	</div>
    <input type='button' value='Edit Order' style='float:right' onclick='editGalleryOrder()' />
    <p style='clear:both'>File(s)</p>
    <div id='gallery_images'>
    	<?php
		if (count($files)) {
		    foreach($files as $k=>$file) {
				?>
                <div class='gallery_image'>
                    <label><input type="checkbox" checked name="block[files][<?=$k?>][src]" value="<?=$file['src']?>" /> <?=basename($file['src'])?></label>
                    <label style='clear:left;'>Caption<br />
                    <input type="text" name="block[files][<?=$k?>][caption]" value="<?=$file['caption']?>" /></label>
                    <label>Description<br />
                    <input type="text" name="block[files][<?=$k?>][description]" value="<?=$file['description']?>" /></label>
                    <div style='clear:both'></div>
                </div>
                <?php				
			}
		}
		?>
    </div>
    
    <input type="hidden" name="block_id" value="<?=$block['id']?>" />
    <input type="hidden" name="block_name" value="audio" />
    <input type="button" style='float:right' onclick="checkForm('page_blocks')" value="<?=(empty($block['id'])) ? "Add Block" : "Update Block"?>" />
   	<?php 
	if (!empty($block['id'])) {
		?>
    	<input type="button" style='float:left' onclick="deleteRecord(<?=$block['id']?>, 'block_audio', {type:'blocks',category:'audio'})" value='Delete' />
		<?php
	}
	?>
    <script type="text/javascript">
		var current_files_count = <?=(count($files))?count($files):0;?>;
		function addFiles() {
			var file = $('#files_file').val().split('\\');
			var name = jsPhpBridge('urlify', file[file.length-1], function(data) {
				$('#gallery_images').append('<div class="gallery_image"><label><input type="checkbox" checked name="block[files]['+current_files_count+'][src]" value="'+data+'" /> '+data+'</label><label style="clear:left">Caption<br /><input type="text" name="block[files]['+current_files_count+'][caption]" value="" /></label><label >Description<br /><input type="text" name="block[files]['+current_files_count+'][description]" value="" /></label><div style="clear:both"></div></div>');
			});

			current_files_count++;
			
			setTimeout(function() {
				$('.upload_status').html('');
				$('#files_file').val('');
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