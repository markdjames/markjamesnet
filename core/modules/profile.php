<?php
ob_start();
if (!empty($_SESSION['userid'])) { 
	$user = $u->getUser($_SESSION['userid']);

	$countries = $db->select("SELECT * FROM z_data_iso3166_countries ORDER BY name ASC");
	
	$page['title'] = 'Your Profile';
	
	?>   
	<form id='update_profile' action="" method="post" style='padding:2%;'>
		<label style='width:20%; float:left; clear:none; margin-right:1.5%;'>Title<br />
		<select name="title"> 
        	<option value="">--</option>
            <option value="Mr" 	<?=($user['title']=='Mr')		? "selected" : "" ;?>>Mr</option>
            <option value="Miss"<?=($user['title']=='Miss')		? "selected" : "" ;?>>Miss</option>
            <option value="Mrs" <?=($user['title']=='Mrs')		? "selected" : "" ;?>>Mrs</option>
            <option value="Ms" 	<?=($user['title']=='Ms')		? "selected" : "" ;?>>Ms</option>
            <option value="Dr" 	<?=($user['title']=='Dr')		? "selected" : "" ;?>>Dr</option>
            <option value="Prof"<?=($user['title']=='Prof')		? "selected" : "" ;?>>Prof</option>
            <option value="Rev" <?=($user['title']=='Reverend')	? "selected" : "" ;?>>Reverend</option>
        </select></label>
            
        <label style='width:38%; float:left; clear:none; margin-right:1%;'>Firstname<br />
		<input name="firstname" type="text" value="<?=$user['firstname']?>"  /></label>
		<label style='width:38%; float:left; clear:none;'>Surname<br />
		<input name="surname" type="text" value="<?=$user['surname']?>"  /></label>
		<label>Email<br />
		<input name="email" type="text" class='required' value="<?=$user['email']?>"  /></label>
        <label>Telephone No.<br />
		<input name="telephone" type="text" value="<?=$user['telephone']?>"  /></label>
        
        <div class='user_address' style='width:47%'>
        	<p><strong>Billing Address</strong></p>
            <label>House Name/Number and Street<br />
            <input name="address" id="address" type="text" value="<?=$user['address']?>"  /></label>
            <label>Town / City<br />
            <input name="city" id="city" type="text" value="<?=$user['city']?>"  /></label>
            <label>Postcode<br />
            <input name="postcode" id="postcode" type="text" value="<?=$user['postcode']?>"  /></label>
            <label>Country<br />
            <select name="country" id="country">
                <option value="">--select country--</option>
                <option value="">--------------------</option>
                <option value="GB">United Kingdom</option>
                <option value="">--------------------</option>
                <?php 
				foreach ($countries as $country) {
					echo "<option value=\"".$country['code']."\"";
					echo ($country['code']==$user['country_code'])?" selected":"";
					echo ">".$country['name']."</option>";
				}
             	?>
            </select></label>
		</div>
        <div class='user_address' style='width:47%'>
        	<p style='float:right; font-size:12px;'><a href='javascript:void(0)' onclick='basket.copyAddress()'>Same as Billing Address</a></p>
        	<p><strong>Delivery Address</strong></p>
            <label>House Name/Number and Street<br />
            <input name="delivery_address" id="delivery_address" type="text" value="<?=$user['delivery_address']?>"  /></label>
            <label>Town / City<br />
            <input name="delivery_city" id="delivery_city" type="text" value="<?=$user['delivery_city']?>"  /></label>
            <label>Postcode<br />
            <input name="delivery_postcode" id="delivery_postcode" type="text" value="<?=$user['delivery_postcode']?>"  /></label>
            <label>Country<br />
            <select name="delivery_country" id="delivery_country">
                <option value="">--select country--</option>
                <option value="">--------------------</option>
                <option value="GB">United Kingdom</option>
                <option value="">--------------------</option>
                <?php 
				foreach ($countries as $country) {
					echo "<option value=\"".$country['code']."\"";
					echo ($country['code']==$user['delivery_country_code'])?" selected":"";
					echo ">".$country['name']."</option>";
				}
				?>
            </select></label>
		</div>
                    
		<div style='clear:both; padding:20px 0'>
        	<p><strong>Data Protection</strong> <a style='font-size:13px;' rel='data_protection' class='help'>info</a></p>
            <p>Please let us know if you are happy for us to contact you in the following ways:</p>
            <label style='font-size:13px'><input name="mailinglist" type="checkbox" value='1' <?=($user['mailinglist']==1)?"checked ":""?>/> by Email</label>
            <label style='font-size:13px'><input name="post_mailinglist" type="checkbox" value='1' <?=($user['post_mailinglist']==1)?"checked ":""?>/> by Post</label>
            <label style='font-size:13px'><input name="phone_mailinglist" type="checkbox" value='1' <?=($user['phone_mailinglist']==1)?"checked ":""?>/> by Phone/SMS</label>
            <label style='font-size:13px; margin-top:20px;'><input name="share_data" type="checkbox" value='1' <?=($user['share_data']==1)?"checked ":""?>/> Are you happy for us to share your data with other like-minded arts organisations?</label>
            
            
		</div>
   
        
     	<input type="button" value="Update" alt="update" onclick="checkForm('update_profile')" />
        
        <div style="height:30px; clear:both"></div>
        <h4 style='margin:20px 0;'>Change Your Password</h4>
		<label>Current Password<br />
		<input name="current_password" autocomplete='off' type="password" /></label>
        <label>New Password<br />
		<input name="password" autocomplete='off' type="password" /></label>
		<label>Confirm New Password<br />
		<input name="confirm_password" autocomplete='off' type="password" /></label>
		
		<input type="hidden" name="uid" value="<?=$uid?>" />			
		<input type="hidden" name="function" id="function" value="update_profile" />
		<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
		<input type="button" value="Update" alt="update" onclick="checkForm('update_profile')" />
	</form>
	<?php
} else {
	?>
	<p><em>Please <a rel='modal'  href='<?=DIR?>/login' onclick="modal(0, 'login')">login</a> or <a href='<?=DIR?>/register'>register</a> to see this page</em></p>
	<?php
}
?>
<div style='clear:both'></div>
<?php
$page['content'] = ob_get_clean();
