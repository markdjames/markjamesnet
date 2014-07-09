<?php
require_once '../../bootstrap.php';
?>
<h3>Your Profile</h3>
<?php 
if (!empty($_SESSION['userid'])) { 
	$user = $u->getUser($_SESSION['userid']);
	?>   
    <form id='update_profile' action="" method="post">
        <label>Email<br />
        <input name="email" type="text" class='required' value="<?=$user['email']?>"  /></label>
        <label>Change Password<br />
        <input name="password" type="password" /></label>
        <label>Confirm New Password<br />
        <input name="confirm_password" type="password" /></label>
        
        <input type="hidden" name="function" id="function" value="update_profile" />
        <input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
        <input type="button" value="Update" alt="update" onclick="checkForm('update_profile')" />
    </form>
	<?php
}