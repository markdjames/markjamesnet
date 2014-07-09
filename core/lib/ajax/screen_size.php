<?php
session_start();
$_SESSION['screen']['width'] = $_POST['width'];
$_SESSION['screen']['height'] = $_POST['height'];