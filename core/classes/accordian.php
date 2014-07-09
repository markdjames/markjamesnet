<?php
class Accordian {
	
	public $width = 940;
	public $height = 100;
	
	/* ********
	* create a rotating carousel
	* 
	* $date:Array - array of elements to add to the accordian each element must have a ['head'] and ['body']
	* ********/
	function createAccordian($data) {

		if (is_array($data)) {
			foreach ($data as $key=>$elm) {
				$unq = uniqid();
				$output .= "<div class='accordian' id='accordian".$unq."'>";
					$output .= "<div class='accordian_top' onclick=\"$('#accordian".$unq." .accordian_inner').slideToggle(); $(this).parent().toggleClass('accordian_open')\" style='height:".$this->height."px'>";
						$output .= $elm['head'];
					$output .= "</div>";
					$output .= "<div class='accordian_inner' style='display:none'>";
						$output .= $elm['body'];	
					$output .= "<div style='clear:both'></div></div>";
				$output .= "</div>";
			}
		} else {
			$output = "<p><em>please supply at least one element to create carousel</em></p>";
		}
		
		return $output;
	}
}

$accordian = new Accordian();
?>