<?php
if (isset($_GET['orientation'])) {
	$_SESSION['orientation'] = $_GET['orientation'];
}

if ($module) {
	$title = $module;
	
} elseif (!empty($page['title'])) {
	$title = $o->output($page, 'title');
	
} else {
	if (is_array($url_vars) || is_array($path_array)) {
		$title = (count($url_vars)) ? end($url_vars) : end($path_array) ;
	}	
}

$title_split = explode(" - ", str_replace("_", " ", $title));
$page_title = clever_ucwords($title_split[0]);
$page_title .= (!empty($title_split[1]) && strpos($page_title, clever_ucwords($title_split[1]))===false) ? " - ".clever_ucwords($title_split[1]) : ""; // add second half of title if word not already in title
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title><?=(!empty($page_title) && $page_title!='Home') ? strip_tags($page_title)." :: ":"";?><?=$db->checkSettings('site-name')?></title>
    
    <?php if (!empty($page['description'])) { ?>
    <meta name="description" content="<?=$page['description'];?>" />
	<?php } elseif (!empty($page['content'])) { ?>
    <meta name="description" content="<?=truncateText(strip_tags($page['content']),200);?>" />
    <?php } ?>
	<meta name="keywords" content="<?=$page['keywords']?>" />

	<meta name="viewport" content="width=device-width,initial-scale=1" />
    
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="" />
    <meta property="og:app_id" content="" /> 
    <meta property="og:image" content="" />
    <meta property="og:title" content="<?=(!empty($page_title) && $page_title!='Home') ? strip_tags($page_title)." :: ":"";?><?=$db->checkSettings('site-name')?>" />
    <meta property="og:description" content="<?=$page['description']?>" />
    <meta property="og:url" content="<?=URL."/".ltrim($_GET['id'],"/")?>" />
    
	<link rel="stylesheet" href="<?=BASE?>/core/css/reset.css">
    <link rel="stylesheet" href="<?=BASE?>/core/css/universal.css">
    <link rel="stylesheet" href="<?=BASE?>/core/js/libs/jqueryui_css/custom-theme/jquery-ui-1.10.1.custom.min.css">
    
<?php if ($is_admin) { ?>
	<link rel="stylesheet" href="<?=resolve('/css/admin.css', false)?>?v=<?=date('dH')?>" />
<?php } ?>

	<link rel="stylesheet" media="screen" href="<?=resolve('/css/mobile.css', false)?>?v=<?=date('dH')?>" />
    <link rel="stylesheet" media="screen and (min-width: 600px)" href="<?=resolve('/css/tablet.css', false)?>?v=<?=date('dH')?>" />
    <link rel="stylesheet" media="screen and (min-width: 1040px)" href="<?=resolve('/css/desktop.css', false)?>?v=<?=date('dH')?>" />

	<script src="<?=BASE?>/core/js/libs/modernizr-2.0.6.min.js"></script>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>  
    <script src="<?=BASE?>/core/js/libs/jquery-ui-timepicker.js"></script>
    <script src="<?=BASE?>/core/js/libs/jquery.lazyload.min.js"></script>  
    
    <script src="<?=resolve('/js/scripts.php', false)?>?v=<?=date('dH');?>"></script>
    
    <link rel="shortcut icon" href="<?=BASE?>/favicon.ico" />
    
</head>
<body>