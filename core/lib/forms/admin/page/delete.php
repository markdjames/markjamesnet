<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$db->type='site';
	$page = $db->select("SELECT * FROM pages WHERE id=".$_POST['id']);
	?>
	<h3>Delete Page</h3>
	<form id="page_delete" method="POST" action="" enctype="multipart/form-data" style="clear:both">
    	
		<label><input type="checkbox" value="1" name='confirm_delete' onchange="(this.checked)?$('#delete').attr('disabled', false):$('#delete').attr('disabled', false);" /> Are you sure you wish to delete this page?</label>
        
        <p><em>Please note: this is irreversible! All associated content will also be deleted</em></p>
		<input type="hidden" name="function" value="delete_page" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $_POST['id']; ?>" />
        <input type="submit" value="Delete" id='delete' disabled />
	</form>
	<?php 
}