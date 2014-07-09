<?php
class Expander {
	
	public $paras = 1;
	
	/* ********
	* create an expanding paragraph block
	* 
	* $date:String - the text to add to the expander
	* ********/
	function createExpander($data) {
		
		$data = str_replace("<p></p>", "", str_replace("<p>&nbsp;</p>", "", $data));
		$data = trim($data,'<p>');
	    $data = trim($data,'</p>');
		$data = trim($data,'<br />');
    	$data = preg_replace('#(?:<br\s*/?>\s*?){2,}#','</p><p>',$data);
    	$data = '<p>'.$data.'</p>';
		$expander_id = uniqid();
		
		$paras = explode("</p>", $data);
		
		$output = "<div id='expander".$expander_id."' class='expander'>";
		$i=0;
		if (count($paras)>1) {
			foreach ($paras as $p) {
				$t = trim(str_replace("<p>", "", $p));
				if (!empty($t)) {
					if ($i==$this->paras) {
						$break = true;
						$output .= "<div class='expander_break'>".$p."</p>";
					} else {
						$output .= $p."</p>";
					}
					$i++;
				}
			}
			if ($break) $output .= "</div>";
			if ((count($paras)-1)>$this->paras) {
				$output .= "<div class='expander_toggle'><a id='expander_toggle_".$expander_id."' onclick=\"expand('".$expander_id."');\"><img src='".BASE."/images/expander.png' style='width:100%' /></a></div>";
			} 
		} else {
			$output .= $p;
		}
		$output .= "</div>";
		
		return $output;
	}
}

$expander = new Expander();
?>