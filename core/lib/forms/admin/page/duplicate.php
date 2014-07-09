<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$db->type='site';
	$page = $db->select("SELECT * FROM pages WHERE id=".$_POST['id']);
	?>
	<h3>Duplicate Page</h3>
	<form id="page_duplicate" method="POST" action="" enctype="multipart/form-data" style="margin:10px">
    	
		<label>New Path <em>(where you would like the new page to live)</em><br />
        <input type="text" value="<?php echo $page[0]['path']; ?>" name='path' /></label>
        
		<input type="hidden" name="function" value="duplicate_page" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $_POST['id']; ?>" />
        <input type="submit" value="Duplicate" />
	</form>
	<?php 
}