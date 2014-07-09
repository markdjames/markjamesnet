<?php
if ((empty($_SESSION['location']['country']) || isset($_GET['lang'])) && strpos($_SERVER['REMOTE_ADDR'], "194.54")!==0) {
	$ip_array = explode(".", $_SERVER['REMOTE_ADDR']);
	
	$ip  = $ip_array[0]*(256*256*256);
	$ip += $ip_array[1]*(256*256);
	$ip += $ip_array[2]*256;
	$ip += $ip_array[3];
	
	$location = $db->select("SELECT * FROM z_data_ip_locations WHERE ip_from<=".$ip." AND ip_to>=".$ip);

	if (isset($_GET['lang'])) {
		$db->vars['code'] = $_GET['lang'];
		$location = $db->select("SELECT * FROM `z_data_iso3166_countries` WHERE code=:code");	
		if (count($location))  $location[0]['country'] = $location[0]['name'];
	}
	
	$_SESSION['location']['code'] 		= $location[0]['code'];
	$_SESSION['location']['country'] 	= $location[0]['country'];
} else {
	$_SESSION['location']['code'] 		= 'GB';
	$_SESSION['location']['country'] 	= 'United Kingdom';
}

if (isset($_GET['lang'])) { 
	$lang = $_GET['lang'];
} elseif (!empty($_SESSION['lang_code'])) {
	$lang = $_SESSION['lang_code'];
} else {
	$lang = $_SESSION['location']['code'];
}

switch ($lang) {
	case 'DE':
	case 'German':
		$_SESSION['lang_code'] = 'DE';
		$_SESSION['lang'] = 'German';
		break;
	case 'PL':
	case 'Polish':
		$_SESSION['lang_code'] = 'PL';
		$_SESSION['lang'] = 'Polish';
		break;
	case 'FR':
	case 'French':
		$_SESSION['lang_code'] = 'FR';
		$_SESSION['lang'] = 'French';
		break;
	case 'IT':
	case 'Italian':
		$_SESSION['lang_code'] = 'IT';
		$_SESSION['lang'] = 'Italian';
		break;
	case 'ES':
	case 'UY':
	case 'AR':
	case 'Spanish':
		$_SESSION['lang_code'] = 'ES';
		$_SESSION['lang'] = 'Spanish';
		break;
	case 'KR':
	case 'KP':
	case 'Korean':
		$_SESSION['lang_code'] = 'KR';
		$_SESSION['lang'] = 'Korean';
		break;
	case 'JP':
	case 'Japanese':
		$_SESSION['lang_code'] = 'JP';
		$_SESSION['lang'] = 'Japanese';
		break;
	case 'CN':
	case 'CN_simp':
	case 'Chinese_Simplified':
		$_SESSION['lang_code'] = 'CN_simp';
		$_SESSION['lang'] = 'Chinese_Simplified';
		break;
	case 'HK':
	case 'TW':
	case 'CN_trad':
	case 'Chinese_Traditional':
		$_SESSION['lang_code'] = 'CN_trad';
		$_SESSION['lang'] = 'Chinese_Traditional';
		break;
	default:
		$_SESSION['lang_code'] = 'GB';
		$_SESSION['lang'] = 'English';
		break;
}
