<?php
function debug($input, $exit=false) {
	//if ($_SESSION['userid']==1 || $_SESSION['userid']==190) {
		ob_start();
			echo "<pre>";
			print_r($input);
			echo "</pre>";
		$output = ob_get_clean();

		echo $output;
		if ($exit) exit();
		return $output;
	//}
}