<?php
class Carousel {
	
	public $width = 940;
	public $elm_width = 217;
	public $id = '';
	
	/* ********
	* create a rotating carousel - relies upon js/carousel.js
	* 
	* $date:Array - array of elements to add to the carousel
	* ********/
	function createCarousel($data) {

		if (is_array($data)) {
			$unq = uniqid();
			$this->id = $unq;
			
			$output = "<div class='carousel' id='carousel".$unq."'";
			$output .= (!MOBILE) ? "  style='width:".$this->width."px'>" : ">";
				$output .= "<div class='carousel_left' onclick=\"carousel('".$unq."', 'left')\">";
				$output .= "</div>"; //<p><a onclick=\"carousel('".$unq."', 'first')\">Start</a></p>";
				$output .= "<div class='carousel_inner'";
				$output .= (!MOBILE) ? " style='width:".($this->width)."px'>" : ">";
					$output .= "<div class='carousel_slider'";
					$output .= (!MOBILE) ? " style='width:".(($this->elm_width+25)*count($data))."px'>" : ">";
					foreach ($data as $key=>$elm) {
						$output .= "<div class='carousel_element' style='max-width:".$this->elm_width."px'>";
						$output .= $elm;
						$output .= "</div>";
					}
					$output .= "</div>";
				$output .= "</div>";
				$output .= "<div class='carousel_right' onclick=\"carousel('".$unq."', 'right')\">";
				$output .= "</div>";
			//$output .= "</div><p style='float:right; font-size:11px;'><a onclick=\"carousel('".$unq."', 'last')\">Skip to end</a></p>";
			$output .= "<script type='text/javascript'>$(document).ready(function(){carousel_skip=".($this->elm_width+20)."});</script>";
			
		} else {
			$output = "<p><em>please supply at least one element to create carousel</em></p>";
		}
		
		return $output;
	}
}

$carousel = new Carousel();
?>