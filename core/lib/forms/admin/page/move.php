<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$page = $p->getPage($_POST['id']);
	?>
	<h3>Move Page</h3>
	<form id="page_duplicate" method="POST" action="" enctype="multipart/form-data" style="margin:10px">
    	
		<label>New Path <em>(where you would like the page to live)</em><br />
        <input type="text" value="<?=$page['path']?>" name='path' /></label>
        
        <label>
        <input type='checkbox' value='1' name='move_subpages' /> Move all decendant pages as well?</label>
        
        <label>
        <input type='checkbox' value='1' name='create_redirects' /> Create re-directs from old URL(s)?</label>
        
		<input type="hidden" name="function" value="move_page" />
		<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
		<input type="hidden" name="page_id" value="<?=$_POST['id']?>" />
        <input type="hidden" name="current_path" value="<?=$page['path']?>" />
        <input type="submit" value="Move" />
	</form>
    <div style='clear:both; height:20px'></div>
	<?php 
}