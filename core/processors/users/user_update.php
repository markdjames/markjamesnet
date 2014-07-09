<?php 
if (isset($_POST['function']) && $_POST['function'] == "update_profile") {

	
	$user = $u->getUser($_SESSION['userid']);
	
	// IF TWO SUPPLIED PASSWORDS MATCH, GO FORWARD
	if ($_POST['password'] == $_POST['confirm_password']) {
		if ($_POST['password'] != "") {
			$user_array['password'] = $db->sqlify(crypt($_POST['password']), "text");
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
				$user_array['title']	= $db->sqlify($_POST['title']);
				$user_array['firstname']= $db->sqlify(ucwords(strtolower($_POST['firstname'])));
				$user_array['surname'] 	= $db->sqlify(ucwords($_POST['surname']));
				$user_array['email'] 	= $db->sqlify($_POST['email']);
				$user_array['address'] 	= $db->sqlify($_POST['address']);
				$user_array['city'] 	= $db->sqlify(ucwords(strtolower($_POST['city'])));
				$user_array['postcode'] = $db->sqlify(strtoupper($_POST['postcode']));
				$user_array['country'] 	= $db->sqlify($_POST['country']);
				$user_array['delivery_address'] 	= $db->sqlify($_POST['delivery_address']);
				$user_array['delivery_city'] 		= $db->sqlify($_POST['delivery_city']);
				$user_array['delivery_postcode'] 	= $db->sqlify($_POST['delivery_postcode']);
				$user_array['delivery_country'] 	= $db->sqlify($_POST['delivery_country']);
				$user_array['telephone']		= $db->sqlify($_POST['telephone']);
				$user_array['mailinglist']		= (isset($_POST['mailinglist']))?1:0;
				$user_array['post_mailinglist']	= (isset($_POST['post_mailinglist']))?1:0;
				$user_array['phone_mailinglist']= (isset($_POST['phone_mailinglist']))?1:0;
				$user_array['share_data']		= (isset($_POST['share_data']))?1:0;
				$user_array['gift_aid']			= (isset($_POST['gift_aid']))?1:0;
			
				$db->update("users", $field_array, $user_array);
				$db->doCommit();
				
				$_SESSION['error'] = "Your details have been updated.";

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