<?php 
if (isset($_POST['function']) && $_POST['function'] == "user_register") {
	
	// SET SESSION DATA TO RE-FILL FORM IF ANYTHING GOES WRONG
	foreach ($_POST as $key=>$val) {
		if (strpos($key, 'password')===false) {
			$_SESSION[$key] =$val;
		}
	}

	// IF TWO SUPPLIED PASSWORDS MATCH, GO FORWARD
	if ($_POST['register_password'] == $_POST['register_password_confirm']) {

		// IF EMAIL FIELD HAS BEEN FILLED IN, GO FORWARD
		if (!empty($_POST['register_email']) && filter_var($_POST['register_email'], FILTER_VALIDATE_EMAIL)) {
			
			// CHECK DATABASE TO SEE IF USERNAME ALREADY EXISTS
			$db->type = 'site';
			$db->vars['email'] = $_POST['register_email'];
			$email_check = $db->select("SELECT * FROM users WHERE email=:email");

			// IF BOTH USERNAME AND EMAIL ARE UNIQUE, CREATE USER RECORD
			if (!count($email_check)) {
				
				$salt = uniqid(mt_rand(), true);
				
				$db->type = 'site';
				$user_array['firstname'] 		= $_POST['firstname'];
				$user_array['surname'] 			= $_POST['surname'];
				$user_array['email'] 			= $_POST['register_email'];
				$user_array['password']			= crypt($_POST['register_password'], $salt);
				$user_array['salt']				= $salt;
				
				$user_array['last_login'] 		= date('Y-m-d H:i:s');
				$user_array['date_created'] 	= date('Y-m-d H:i:s');
				
				$user_array['mailinglist']		= (isset($_POST['mailinglist']))		? 1:0;
				$user_array['post_mailinglist']	= (isset($_POST['post_mailinglist']))	? 1:0;
				$user_array['phone_mailinglist']= (isset($_POST['phone_mailinglist']))	? 1:0;
				$user_array['share_data']		= (isset($_POST['share_data']))			? 1:0;
				$user_array['gift_aid']			= (isset($_POST['gift_aid']))			? 1:0;
				$user_array['permissions'] 	= "1";
				$db->insert("users", $user_array);
				$db->doCommit();
				$new_id = $db->lastId;
							
				// get user record
				$_SESSION['userid'] = $new_id;
				$is_logged_in = true;
				$user = $u->getUser($new_id);	
				
				/**
				 * Email webmaster account to alert of new user
				 */
				require_once resolve('tools/phpmailer/class.phpmailer.php');
				$mail = new PHPMailer(); 

				$body = "<p style='font-size:14px;'><a href='".BASE."profile/".$new_id."'>".$_POST['register_email']."</a> registered with your website</p>";
				
				$mail->AddReplyTo("webmaster@".DOMAIN,"webmaster@".DOMAIN);
				$mail->SetFrom("webmaster@".DOMAIN, $db->checkSettings('site-name'));
				$mail->AddAddress("webmaster@".DOMAIN, $db->checkSettings('site-name'));
				$mail->Subject    = $db->checkSettings('site-name')." User Signup";
				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mail->MsgHTML($body);
				
				$mail->Send();
									
				/***
				 * Email new user with account details
				 */
				$user_mail = new PHPMailer(); // defaults to using php "mail()"
				
				$body = "
				<p style='font-size:16px; font-family:Georgia;'>Thank you for registering with <a href='".URL."'>".$db->checkSettings('site-name')."</a></p>
				<p style='font-size:14px; font-family:Georgia;'>Your registered email address is: <strong>".$_POST['register_email']."</strong></p>
				
				<p style=\"font-size:14px; font-family:Georgia;\">We hope you enjoy browsing our site, if you have any feedback or queries please don't hesitate to <a href='mailto:webmaster@".DOMAIN."'>get in touch</a>.</p>";
					
				$user_mail->AddReplyTo("webmaster@".DOMAIN,"webmaster@".DOMAIN);
				$user_mail->SetFrom("webmaster@".DOMAIN, $db->checkSettings('site-name'));
				$user_mail->AddAddress($_POST['register_email'], $_POST['register_email']);
				$user_mail->Subject = $db->checkSettings('site-name')." Registration";
				$user_mail->MsgHTML($body);
				
				$user_mail->Send();	
			
				header('Location: '.DIR.'/');
				exit();					
				
			} else {
				if (count($email_check)) {
					
					$_SESSION['error'] = "That email address is already registered, you can retrieve your login details below.";
					header('Location: '.DIR."/forgotten_password");
					exit();

				} else {
					$_SESSION['error'] = "Invalid details, please try again.";
				}
			}
		} else {
			$_SESSION['error'] = "You must enter a valid email address.";
		}
		
	} else {
		$_SESSION['error'] = "Passwords did not match. Please try again.";
		
	}
	
	
}	
?>