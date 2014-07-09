<?php
class User {
	
	public $order = 'email ASC';
	
	// pass a user id
	public function getUser($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$user = $db->select("SELECT u.*, c.name AS country, c.code AS country_code, cd.code AS delivery_country_code, cd.name AS delivery_country
								FROM users AS u
								LEFT JOIN z_data_iso3166_countries AS c ON u.country = c.code
								LEFT JOIN z_data_iso3166_countries AS cd ON u.delivery_country = cd.code
								WHERE id=:id");
		
		return (count($user)) ? $user[0] : false ; 
	}
	
	// get all users
	public function getUsers($sql='') {
		global $db;
		$db->type = 'site';
		$users = $db->select("SELECT *
								FROM users
								".$sql." 
								ORDER BY ".$this->order);

		return (count($users)) ? $users : false ; 
	}
	
	// pass a user id
	public function checkIsAdmin($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$user = $db->select("SELECT *
								FROM users AS u 
								WHERE u.id=:id");
		
		return ($user[0]['level']>2) ? true : false ; 
	}
	
	
	// pass a username
	public function getUserByUsername($id) {
		global $db;
		$db->type = 'site';
		$db->vars['username'] = $id;
		$user = $db->select("SELECT *
								FROM users  
								WHERE username=:username");
		
		return (count($user)) ? $user[0] : false ; 
	}
	
	// pass a email
	public function getUserByEmail($id) {
		global $db;
		$db->type = 'site';
		$db->vars['email'] = $id;
		$user = $db->select("SELECT *
								FROM users  
								WHERE email=:email");
		
		return (count($user)) ? $user[0] : false ; 
	}
	
	// pass a twitter id
	public function getUserByTwitterId($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$user = $db->select("SELECT *
								FROM users  
								WHERE twitter_id=:id");
		
		return (count($user)) ? $user[0] : false ; 
	}
	// pass a facebook id
	public function getUserByFacebookId($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$user = $db->select("SELECT *
								FROM users  
								WHERE facebook_id=:id");
		
		return (count($user)) ? $user[0] : false ; 
	}
	
	public function login($data, $token='') {
		global $db;

		$db->type = "site";

		unset($_SESSION['permissions']);
		
		$data['username'] 		= (empty($data['username'])) ? $data['email'] : $data['username'];
		$_SESSION['username'] 	= $data['username'];
		
		if (!empty($data['username']) && (!empty($data['password']) || !empty($data['password_hash']) || !empty($token))) {
			
			$user =	$this->getUserByUsername($data['username']);
			if (!$user) $user =	$this->getUserByEmail($data['username']);

			if ($user) {
				$salt = (!empty($user['salt'])) ? $user['salt'] : $user['password'];

				if (crypt($data['password'], $salt) == $user['password']) {
					$_SESSION['userid'] = $user['id'];
					
					$userid['id'] = $db->sqlify($user['id']);
					$userfields['last_login'] = $db->sqlify(date('Y-m-d H:i:s'));
					$db->update("users", $userid, $userfields);
					$db->doCommit();
				}
				
			} else {
				$_SESSION['error'] = "Username not found";
				header("Location: ".DIR."/login");
				exit();
			}
		} else {
			$_SESSION['error'] = "You must enter a username/email and password to login";
			header("Location: ".DIR."/login");
			exit();
		}
	}
	
	
	public function logout() {

		unset($_SESSION['userid']);
		session_destroy();
		
		session_start();
		$_SESSION['token'] = uniqid();
		$_SESSION['mobile'] = false;		
	}
	
	/********************
	* Pass user array to add to user db
	* must include 'password' (un-encoded) and 'email'
	********************/
	public function addUser($data) {
		global $db;
		$db->type = 'site';
		
		foreach ($data as $key=>$val) {
			if ($key != 'password') {
				$values[$key] = $db->sqlify($val);
			} else {
				$values[$key] = $db->sqlify(crypt($val)); 
			}
		}
		$values['date_created'] = $db->sqlify(date('Y-m-d H:i:s')); 
		
		$check = false;
		if (!empty($data['email'])) {
			$check = $this->getUserByEmail($data['email']);
		} elseif (!empty($data['twitter_id'])) {
			$check = $this->getUserByTwitterId($data['twitter_id']);
		}
		
		if (!$check) {
			$db->insert('users', $values);
			$db->doCommit();
		}
	}
	
	public function forgottenPassword($email) {
		global $db;
		
		$check = $this->getUserByEmail($email);
		if ($check) {
			
			$field_array['id'] 			= $check['id'];
			$values_array['temporary'] 	= 1;
			$db->update("users", $field_array, $values_array);
			$db->doCommit();
			
			$reset_date = strtotime('+1 hour');
			
			$mail    = new PHPMailer();
			$message = "<p><strong>Forgotten Password</strong></p><p>Please use the link below to reset your password. This link is valid until ".date('j F Y, H:i', $reset_date).", after which you will need to re-apply for a password reset email.</p><p><a href='http://www.".DOMAIN."/reset_password?uid=".urlencode($check['salt'])."|".$reset_date."|".$check['id']."'>http://www.".DOMAIN."/reset_password?uid=".urlencode($check['salt'])."|".$reset_date."|".$check['id']."</a></p>";
			
			$mail->AddReplyTo("no-reply@".DOMAIN,"no-reply@".DOMAIN);
			$mail->SetFrom("no-reply@".DOMAIN, "no-reply@".DOMAIN);
				
			$address = $check['email'];
			$mail->AddAddress($address, $address);
			
			$mail->Subject    = "Forgotten Password";
			
			$mail->MsgHTML($message);
			
			if(!$mail->Send()) {
				$_SESSION['error'] = "Error: email not sent.";
			} else {
				$_SESSION['error'] = "Your password has been sent to you. Please check your inbox - it may take up to 5 minutes for the email to arrive. Please check your junk email folder if it does not arrive after this time.";
				header('Location: /login');
				exit();
			}
		} else {
			$_SESSION['error'] = "Sorry, we can't find your email address in our system - please register first.";
		}
		return false;
	}
	
	/**
	 * Used to reset user passwords
	 *
	 * @param	uid			int		User ID
	 * @param	password	string	New Password choice	
	 * @param	confirm		string	Confirm new Password choice	
	 */
	public function resetPassword($uid, $password, $confirm) {
		global $db;
		$user = $this->getUser($uid);
		
		if ($user['temporary']) {
			if ($password === $confirm) {
				
				$salt = uniqid(mt_rand(), true);
				
				$field_array['id'] 			= $uid;
				$user_array['password']		= crypt($password, $salt);
				$user_array['salt']			= $salt;
				$user_array['temporary'] 	= 0;
				$db->update("users", $field_array, $user_array);
				$db->doCommit();
				
				$user['password'] = $password;
				$this->login($user);
				
			} else {
				$_SESSION['error'] = "Your passwords did not match, please try again.";
			}
		} 
	}
	
	public function createRandomPassword() {

		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
	
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

}
$u = new User();
?>