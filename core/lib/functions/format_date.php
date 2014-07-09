<?php
/*********************
* takes a date from a jQuery UI form and returns ready for database
*
* @d = date - formated as 'dd / mm / yy HH:ii'
*********************/
function formatDate($d) {
	$d = str_replace(" / ", " ", $d);
	$date_array = explode(" ", $d);
	if (count($date_array)>3) {
		list($day, $month, $year, $time) = $date_array;
		return $year."-".$month."-".$day." ".$time.":00";
	} else {
		list($day, $month, $year) = $date_array;
		return $year."-".$month."-".$day;
	}
	
	
}
	