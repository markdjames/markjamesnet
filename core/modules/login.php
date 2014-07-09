<?php
if (empty($_SESSION['userid']) || $is_admin) {
	?>
	<form action="" method="post">
		<label>Email<br />
		<input name="username" type="text" id="username" tabindex='1' value="<?=(isset($_SESSION['username']))?$_SESSION['username']:"";?>" /></label>
		<span style='float:right; font-size:11px; margin-top:5px'><a style='margin:0' href="<?=DIR?>/forgotten_password">Forgotten Password</a></span>
		<label>Password<br />
		<input id="password" name="password" type="password" tabindex='2' /></label>
		<input type="hidden" name="function" id="function" value="login" />
		<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
		
		<input type="submit" value="Login" alt="login" tabindex='3' />
		<p style='padding-top:25px;'><a href="<?=BASE?>/register">Create new account</a></p>
	</form>
	<?php
} else {
	?>
	<script>
	document.location.href='/';
	</script>
	<?php
}
?>
