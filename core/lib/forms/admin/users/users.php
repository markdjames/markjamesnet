<?php
require_once '../../../bootstrap.php';
?>
<h3>Edit User Profile</h3>
<?php 
if (!empty($_POST['id'])) { 
	$user = $u->getUser($_POST['id']);
}
$countries = $db->select("SELECT * FROM z_data_iso3166_countries ORDER BY name ASC");

?>   
<form id='update_user' action="" method="post">
	<div class='form_left'>
		<label>Firsname<br />
		<input name="firstname" type="text" class='required' value="<?=$user['firstname']?>"  /></label>
		<label>Surname<br />
		<input name="surname" type="text" class='required' value="<?=$user['surname']?>"  /></label>
		<label>Email<br />
		<input name="email" type="text" class='required' value="<?=$user['email']?>"  /></label>
		<label>Address<br />
		<input name="address" type="text" value="<?=$user['address']?>"  /></label>
		<label>City<br />
		<input name="city" type="text" value="<?=$user['city']?>"  /></label>
		<label>Postcode<br />
		<input name="postcode" type="text" value="<?=$user['postcode']?>"  /></label>
		<label>Country<br />
		<select  name="country">
			<option value="">--select country--</option>
			<?php 
			foreach ($countries as $country) {
				echo "<option value=\"".$country['code']."\"";
				echo ($country['code']==$user['country'])?" selected":"";
				echo ">".$country['name']."</option>";
			} ?>
		</select></label>
		<label>Telephone No.<br />
		<input name="telephone" type="text" value="<?=$user['telephone']?>"  /></label>
		
		<label>Change Password<br />
		<input name="password" type="password" /></label>
		<label>Confirm New Password<br />
		<input name="confirm_password" type="password" /></label>
		
		<label><input name="mailinglist" type="checkbox" value='1' <?=($user['mailinglist']==1)?"checked ":""?>/> Send monthly e-bulletins</label>
	</div>
	<div class='form_right'>
		<h4 style='margin-top:0'>Permissions</h4>
        <label style='margin:20px 0'><input type='checkbox' name='admin' value='1' <?=($user['permissions']>1)?" checked":"";?> /> Make user Admin?</label>
		<?php
		if ($db->checkPermissions('edit_user_permissions', $_SESSION['userid'])) {	
			$db->type = 'site';
			$db->vars['id'] = $user['id'];
			$permissions = $db->select("SELECT p.* FROM permissions AS p LEFT JOIN permissions_bridge AS pb ON pb.permission_id=p.id WHERE pb.user_id = :id ORDER BY p.type ASC");
			
			$user_permissions = array();
			if (count($permissions)) foreach ($permissions as $per) $user_permissions[] = $per['id'];
			
			$all_permissions = $db->select("SELECT * FROM permissions ORDER BY type ASC");
			
			foreach ($all_permissions as $permission) {
				?>
				<label><input type='checkbox' name='permissions[<?=$permission['type']?>]' value='<?=$permission['id']?>' <?=(in_array($permission['id'], $user_permissions))?" checked":"";?> /> <?=ucwords(str_replace("_", " ", $permission['type']))?></label>
				<?php
			}

		} else {
			?>
			<p><em>Sorry, you do not have permission to edit this users permissions.</em></p>
			<?php
		}
		?>
	</div>
	<input type="hidden" name="userid" id="userid" value="<?=$user['id']?>" />
	<input type="hidden" name="function" id="function" value="update_user" />
	<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
	<input type="button" value="Update" alt="update" style='clear:both' onclick="checkForm('update_user')" />
</form>