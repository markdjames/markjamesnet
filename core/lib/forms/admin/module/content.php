<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$db->type='site';
	$module = $m->getModule($_POST['id']);
	?>
	
  	<form id="module_content" method="POST" action="" enctype="multipart/form-data">
		<div style='float:right; width:300px; margin-top:60px;'>
			<?php	
			$image = new ImageOutput();
			$image->lazyload = false;
            echo $image->outputImage($module['image'], "", 300, 226);
            ?>
            <label><input type="checkbox" name='no_photo' value='true' /> Remove photo?</label>
        </div>
        
        <h3>Module Content</h3>
        <div style='float:left; margin-bottom:10px'>
            <label>Image File<br />
            <input type='file' name='image' /></label>
            <label>Image Title / Alt<br />
            <input type='text' value='<?php echo $module['alt']; ?>' name='alt' /></label>
            <label>Image Credit<br />
            <input type='text' value='<?php echo $module['credit']; ?>' name='credit' /></label>
            <label><input type="checkbox" value="1" name="hide_photo" <?=($module['hide_photo'])?"checked ":"";?>/> Hide Photo?</label>
        </div>
        <div style='clear:both'></div>
        <label>Page Title<br />
        <input name='title' type='text' value="<?=$module['title']; ?>" /></label>
                    
        <label>Content<br />
        <textarea id='content' name='content' class='ckeditor' style='height:50%'><?php echo $module['content']; ?></textarea></label>
        
        <?php
		/**
		 * Check site settings for any other languages, show other fields if nessecary
		 */
		$languages_json = $db->checkSettings('languages');
		if (!empty($languages_json)) {
			$languages = json_decode($languages_json, true);
			$locale = json_decode($module['locale'], true);
			
			if (is_array($languages) && count($languages)) {
				?>
				<p><a onclick="$('#languages_fields').fadeToggle()">Edit other languages</a></p>
				<div id='languages_fields'>
					<?php
					foreach ($languages as $language) {
						?>
						<div class='locale_fields' id='<?=$language?>'>
						<label><?=$language?> Page Title<br />
						<input id="title_<?=$language?>" name='locale[<?=$language?>][title]' type='text' value="<?=$locale[$language]['title']; ?>" /></label>
						
						<label><?=$language?> Content<br />
						<textarea id="content_<?=$language?>" name='locale[<?=$language?>][content]' class='ckeditor' style='height:50%'><?=$locale[$language]['content']; ?></textarea></label>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
		}
		?>
        
        <input type="hidden" name="function" value="update_module_content" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="module_id" value="<?php echo $module['id']; ?>" />
        <input type="submit" value="Update" />
	</form>
    <script type="text/javascript">
		$('.ckeditor').each(function() {
			addCKEditor(this.id);
		});		
	</script>
<?php
}