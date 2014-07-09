<?php
require_once '../../lib/bootstrap.php';

if (is_numeric($_POST['id'])) {
	$block = $b->getBlock($_POST['id'], 'custom_html', $_POST['type'], $_POST['content']);
	?>
    <h4 style='clear:both'>Custom HTML Block</h4>
    <label>Content<br />
    <textarea name="block[content]" style="height:300px;"><?=$block['content']?></textarea></label>
    
    <input type="hidden" name="block_id" value="<?=$block['id']?>" />
    <input type="hidden" name="block_name" value="custom_html" />
    <input type="button" style='float:right' onclick="checkForm('page_blocks')" value="<?=(empty($block['id'])) ? "Add Block" : "Update Block"?>" />
   	<?php 
	if (!empty($block['id'])) {
		?>
    	<input type="button" onclick="deleteRecord(<?=$block['id']?>, 'block_custom_html', {type:'blocks',category:'custom_html'})" value='Delete' />
		<?php
	}
}