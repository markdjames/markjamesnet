<?php
// replace keywords with links
function linkify ($string) {
	/*************************
	* dont try this on strings that are too big
	*************************/
	if (strlen($string)<20000) { 
		/*****************************
		* First we deal with HTML characters and new line spaces
		* As we'll need to break the string up by spaces we need to make sure words right next to tags will be seperated out
		* we add in the % marks so we can easily undo this at the end
		*****************************/
		$string = str_replace(">", ">%%% ", str_replace("<", " %%%<", $string));
		$string = str_replace("\n", "\n ", $string);
		
		/***************************
		* Initialise $prev_word - this will store the previous word so we can analysis it to make sure it doesnt looks like a link tag...
		***************************/
		$prev_word = "";
		$emails = $urls = array();
		
		/*************************
		* Breakup input string and loops through
		**************************/
		foreach(explode(' ', $string) as $token) {
			
			/***********************
			* remove non-breaking spaces and trim un-needed chars
			***********************/
			$token = trim(str_replace("&nbsp;", "", $token), ',.;"\'\\/()');
			
			/***********************
			* check previous word isnt a link tag
			* if it is, skip this word
			***********************/
			if (strpos($prev_word, "href=")!==false || strpos($prev_word, "target=")!==false || strpos($prev_word, "http")!==false || strpos($token, '"')!==false || strpos($token, '>')!==false) {
				$prev_word = $token;
				continue;
			}
			
			/************************
			* Use PHP's built in filters to see if this looks like an email address
			************************/
			//$email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
			$email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
			if ($email !== false) {
				/***********************
				* If not in list of outputted emails then lets go ahead and add the link tag
				***********************/
				if (!in_array($email, $emails)) {
					$emails[] = $email;
					if (strpos($token, "mailto:")===false && strpos($token, "=")===false) {
						$string = preg_replace('%'.$email.'(?![^<]*</a>)%i', '<a href="mailto:'.$email.'">'.$token.'</a>', $string, 1);
					}
				}
			
			/************************
			* Not an email? Ok, let's check if it's a URL - again using PHP's built in filters
			************************/
			} else {
				$url = filter_var(filter_var($token, FILTER_SANITIZE_URL), FILTER_VALIDATE_URL);
				
				/*************************
				* One last check - if word contains www. then we add a http:// and check again
				*************************/
				$url = ($url==false && strpos($token, "www.")!==false && strpos($token, ">")===false) ? filter_var(filter_var("http://".$token, FILTER_SANITIZE_URL), FILTER_VALIDATE_URL) : $url; 
				if ($url !== false) {
					if (!in_array($url, $urls)) {
						$urls[] = $url;	
						if(strpos($token, "=")===false && strpos($token, "'")===false && strpos($token, "\"")===false  && strpos($token, "macromedia")===false && substr($token, -1)!=":") {
							$token = strip_tags($token, "<em><strong>");

							$pos = strpos($string,$token);
							if ($pos !== false) {
								$string = substr_replace($string,"<a href='".$url."' target='_blank'>".$token."</a>",$pos,strlen($token));
							}
						}
					}
				}
			}
			$prev_word = (!empty($token)) ? $token : $prev_word;
		}

		/**********************
		* tidy up
		*********************/
		$string = str_replace(">%%% ", ">", str_replace(" %%%<", "<", $string));
	}
    return $string;
}