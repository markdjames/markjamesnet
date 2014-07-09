<?php
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Europe/London');

session_start();
setcookie(session_name(),session_id(),strtotime('+1 day'), '/');

if (!isset($_SESSION['token'])) $_SESSION['token'] = uniqid();

$base = realpath(dirname(__FILE__) . '/..');
require $base."/../lib/config.php";

require $base."/lib/locale.php";
require $base."/lib/functions.php";
require $base."/lib/classes.php";


