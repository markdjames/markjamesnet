<?php
require_once '../../lib/bootstrap.php';

if (is_numeric($_POST['id'])) {
	
	$block = $b->getBlock($_POST['id'], 'video', $_POST['type'], $_POST['content']);
	
	?>
    <h4 style='clear:both'>Video Block</h4>
    <label>Title<br />
    <input type="text" name="block[title]" value="<?php echo $block['title']; ?>" /></label>
    <div style='background-color:#eee; padding:10px; margin-top:10px;'>
    	<p style='margin:0; float:left;'>Either:</p>
        <label style='clear:none; width:45%; float:left; margin:0px 10px; padding-right:10px; border-right:1px solid #999; '>Vimeo URL<br />
        <input type="text" name="block[vimeo]" value="<?php echo $block['vimeo']; ?>" /></label>
        <label style='clear:none; width:44%; float:left; margin:0;'>YouTube URL<br />
        <input type="text" name="block[youtube]" value="<?php echo $block['youtube']; ?>" /></label>
        <div style='clear:both'></div>
    </div>
    <label>Description<br />
    <textarea id='description' name='block[description]' class='ckeditor' style='height:50%'><?php echo $block['description']; ?></textarea></label>
    
    <input type="hidden" value="<?=($block['featured'])?'0':'1'?>" name='block[featured]' />
    <label><input type="checkbox" value="1" name='block[featured]'<?=($block['featured'])?' checked':''?> /> Featured?</label>
        
    <input type="hidden" name="block_id" value="<?php echo $block['id']; ?>" />
    <input type="hidden" name="block_name" value="video" />
    
    <input type="submit" value="<?php echo (empty($block['id'])) ? "Add Block" : "Update Block"; ?>" />
   	<?php 
	if (!empty($block['id'])) {
		?>
    	<input type="button" onclick="deleteRecord(<?php echo $block['id']; ?>, 'block_video', {type:'blocks',category:'video'})" value='Delete' />
		<?php
	}
	?>
    <script type="text/javascript">
		$('.ckeditor').each(function() {
			addCKEditor(this.id);
		});		
	</script>
    <?php
}