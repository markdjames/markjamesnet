<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];

if (isset($_GET['desktop']) && $_GET['desktop']=="true") {
	define('MOBILE', false);
	$_SESSION['mobile']=0;
	define('TABLET', false);
	$_SESSION['tablet']=0;
	$_SESSION['force_desktop'] = true;
	
} else {
	
	if (isset($_GET['desktop']) && $_GET['desktop']=="false") {
		$_SESSION['force_desktop'] = true;
	}
	
	if (!isset($_SESSION['force_desktop']) || !$_SESSION['force_desktop']) {
		require_once $_SERVER['DOCUMENT_ROOT'].BASE.'/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		$scriptVersion = $detect->getScriptVersion();
		
		/*if (isset($_SESSION['userid']) && ($_SESSION['userid']==190 || $_SESSION['userid']==1)) {
			define('MOBILE', true);
			$_SESSION['mobile']=1;
			define('TABLET', false);
			$_SESSION['tablet']=0 ;
			$_SESSION['orientation']='vertical';
		} else {*/
			if ($deviceType=='phone') {
				define('MOBILE', true);
				$_SESSION['mobile']=1;
				define('TABLET', false);
				$_SESSION['tablet']=0;
			} else {
				define('MOBILE', false);
				$_SESSION['mobile']=0;
			
				if ($deviceType=='tablet') {
					define('TABLET', true);
					$_SESSION['tablet']=1;
				} else {
					define('TABLET', false);
					$_SESSION['tablet']=0;
				}
			}
		//}
	}
}