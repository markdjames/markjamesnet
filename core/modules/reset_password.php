<?php
if ((empty($_SESSION['userid']) && isset($_GET['uid'])) || $is_admin) {
	
	if (!isset($_GET['uid'])) $_GET['uid'] = "||";
	
	list($salt, $time, $uid) = explode("|", $_GET['uid']);
	
	if (is_numeric($uid) && $time>time()) {
		
		$user = $u->getUser($uid);
		
		if ($user['temporary']) {
			?>
			<form id='reset_password' action="/" method="post">
				<input name="uid" type="hidden" value="<?=$uid?>"  />	
			                
				<label>New Password<br />
				<input name="password" autocomplete='off' type="password" /></label>
				<label>Confirm New Password<br />
				<input name="confirm_password" autocomplete='off' type="password" /></label>
			
				<input type="hidden" name="function" id="function" value="reset_password" />
				<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
				<input type="button" value="Update" alt="update" onclick="checkForm('reset_password')" />
			</form>
			<?php
		} else {
			?>
			<p style='color:red'>It appears your password has already been reset, please <a href='<?=DIR?>/forgotten_password'>request another</a> reset link if you need it.</p>
			<?php
		}
	} else {
		?>
        <p style='color:red'>Sorry, your password reset link has expired, please <a href='<?=DIR?>/forgotten_password'>request another</a>.</p>
        <?php
	}
} else {
	?>
	<script>
	document.location.href='/';
	</script>
	<?php
}
?>
