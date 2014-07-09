<?php 
if (isset($_POST['function']) && $_POST['function'] == "update_user") {
	
	$user = $u->getUser($_POST['userid']);

	// IF TWO SUPPLIED PASSWORDS MATCH, GO FORWARD
	if ($_POST['password'] == $_POST['confirm_password'] && ($user || $_POST['password']!='')) {
		if ($_POST['password'] != "") {
			$passwordHash = crypt($_POST['password']);
			$user_array['password'] = $db->sqlify($passwordHash, "text");
		}
	
		// IF EMAIL FIELD HAS BEEN FILLED IN, GO FORWARD
		if (!empty($_POST['email']) && preg_match('/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})/i', $_POST['email'])) {
		
			// CHECK TO SEE IF EMAIL HAS ALREADY BEEN USED
			$db->type = 'site';
			$db->vars['email'] = $_POST['email'];
			$email_check = $db->select("SELECT * FROM users WHERE email=:email");
	
			// IF BOTH USERNAME AND EMAIL ARE UNIQUE, CREATE USER RECORD
			if (count($email_check) == 0 || $_POST['email'] == $user['email']) {
				
								
				$field_array['id'] 		= $db->sqlify($user['id']);
				$user_array['firstname']= $db->sqlify($_POST['firstname']);
				$user_array['surname'] 	= $db->sqlify($_POST['surname']);
				$user_array['email'] 	= $db->sqlify($_POST['email']);
				$user_array['address'] 	= $db->sqlify($_POST['address']);
				$user_array['city'] 	= $db->sqlify($_POST['city']);
				$user_array['postcode'] = $db->sqlify($_POST['postcode']);
				$user_array['country'] 	= $db->sqlify($_POST['country']);
				$user_array['telephone']= $db->sqlify($_POST['telephone']);
				$user_array['permissions']		=(isset($_POST['admin']))?3:0;
				$user_array['mailinglist']		= (isset($_POST['mailinglist']))?1:0;
				$user_array['post_mailinglist']	= (isset($_POST['post_mailinglist']))?1:0;
				$user_array['phone_mailinglist']= (isset($_POST['phone_mailinglist']))?1:0;
				$user_array['share_data']		= (isset($_POST['share_data']))?1:0;
			
				if ($user) {
					$db->update("users", $field_array, $user_array);
					$db->doCommit();
				} else {
					$db->insert("users", $user_array);
					$db->doCommit();
					$user['id'] = $db->lastId;
				}				
			
				if (isset($_POST['permissions'])) {
					$db->delete('permissions_bridge', 'user_id', $user['id']);
					$db->doCommit();
					foreach ($_POST['permissions'] as $per=>$val) {
						$db->insert('permissions_bridge', array('user_id'=>$user['id'], 'permission_id'=>$val));
					}
					$db->doCommit();
				}
				
				

			} else {
				if (count($email_check) > 0 && $_POST['email'] != $user['email']) {
					$_SESSION['error'] = "That email is already registered, please try again.";

				} else {
					$_SESSION['error'] = "Invalid details, please try again.";
				}
			}
		} else {
			$_SESSION['error'] = "You must enter a valid email address.";
		}
		
	} else {
		$_SESSION['error'] = "Passwords did not match - your details have not been updated.";
	}
	

}	
?>