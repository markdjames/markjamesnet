<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$page = $p->getPage($_POST['id']);
	?>
	<h3>Restore Previous Version</h3>
	<!--form id="page_snapshot" method="POST" action="" enctype="multipart/form-data">
    	
		<label><input type="checkbox" value="1" name='confirm_snapshot' onchange="(this.checked)?$('#snapshot').attr('disabled', false):$('#snapshot').attr('disabled', false);" /> Are you sure you wish to take a snapshot of this page?</label>
       
		<input type="hidden" name="function" value="snapshot_page" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $_POST['id']; ?>" />
        <input type="submit" value="Take Snapshot" id='snapshot' disabled />
	</form-->
    <?php

	$db->vars['pid'] = $page['pid'];
	$db->vars['id'] = $page['id'];
	$historic = $db->select("SELECT * FROM pages WHERE (pid=:pid) AND id!=:id");
	if (count($historic)) {
	?>
	<div style='clear:both; height:10px;'></div>
	<form id="restore_snapshot" method="POST" action="" enctype="multipart/form-data" style="clear:both;">
		<?php
		foreach ($historic as $old) {
			$editor = $u->getUser($old['modified_by']);
			?>
			<label><input type='radio' name='restore' value='<?php echo $old['id']; ?>' /> <?= date('d F Y (H:i)', strtotime($old['last_modified'])); ?> - updated by <?=$editor['firstname']." ".$editor['surname']?></label>
			<?php
		}
		?>
		<input type="hidden" name="function" value="restore_snapshot" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $page['id']; ?>" />
		<input type="submit" value="Restore" id='snapshot' />
	</form>
	<?php 
	}

}