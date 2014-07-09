<?php
if (empty($_SESSION['userid']) || $is_admin) {
	?>
	<form method='POST' action=''>
		<label>Enter your email address<br />
		<input type='email' placeholder='enter your email address here' value='<?=(isset($_SESSION['email']))?$_SESSION['email']:(isset($_SESSION['register_email']))?$_SESSION['register_email']:"";?>' name='forgotten_email' /></label>
		
		<input type='hidden' value='forgotten_password' name='function' />
		<input type='hidden' value='<?=$_SESSION['token']?>' name='token' />
		
		<input type='submit' value='Send Password Reset Link' />
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
