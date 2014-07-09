<?php
class Paginator {
	
	public $type = 'count';
	public $pagination_type = 'standard'; // standard or prev_next 
	public $per_page = 10;
	public $sort = 'name';  // key in array by which to sort by

	/***********************************
	* create paginated output, either by page or alphabetically
	*
	* @elements:Array - all elements to be paginated
	***********************************/
	function createPagination($elements) {

		$paginator_id = uniqid();
		$e=0; // element counter
		$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');	

		// get rid of any empty paragraphs
		foreach($elements as $k=>$elem) {
			$tester = str_replace("&nbsp;", "", preg_replace('/\s+/', '', strip_tags($elem)));
			if (!empty($tester)) {
				$new_elements[$k] = $elem;
			}
		}
		$elements = $new_elements;
		
		
		$pages = floor((count($elements)-1)/$this->per_page);
		
		$page_links = "<a href='javascript:void(0)' style='width:30px' onclick=\"$('.".$paginator_id."_pages').css('display','block');\" class='pagination_link pagination_all'>ALL</a>";
		
		if ($this->type == 'count') {
			
			if ($this->pagination_type == 'standard') {
				for($i=0; $i<=$pages; $i++) {
					$page_links .= "<a href='javascript:void(0)' class='pagination_link";
					$page_links .= ($i==0) ? " active":"";
					$page_links .= "' onclick=\"pagination('".$paginator_id."', {type:'numeric', letter:'".$i."'}, event);\" class='pagination_link'>".($i+1)."</a>";
				}
				$page_links = rtrim($page_links, " | ");
			} else {
				$page_links .= "<a href='javascript:void(0)' onclick=\"pagination('".$paginator_id."', {type:'numeric', direction:'prev'});\" class='pagination_link pagination_prev'><span>previous page</span></a>";
				$page_links .= "<a href='javascript:void(0)' onclick=\"pagination('".$paginator_id."', {type:'numeric', direction:'next'});\" class='pagination_link pagination_next'><span>next page</span></a>";
				$page_links = str_replace("| ", "", $page_links);
			}
			
			$output = "";
			for($i=0; $i<=$pages; $i++) {
				$output .= "<div class='".$paginator_id."_pages' id=\"page_".$i."_".$paginator_id."\"";
				$output .= ($i>0) ? " style='display:none;'>\n" : ">\n";
				
				for($j=0; $j<$this->per_page; $j++) {
					$output .= "<div id=\"".$paginator_id."_item_".$e."\">\n";
					$ele = ($j==0) ? current($elements) : next($elements);
				
					if (is_array($ele)) {
						foreach ($ele as $col) {
							$output .= $col;
						}
					} else {
						$output .= $ele;
					}
				
					$output .= "</div>\n";
					$e++;
				}
				$output .= "</div>\n";
			}
			
			$output .= ($pages>0) ? "<p class='pagination'>".$page_links."</p><div style='clear:both'></div>\n" : "";
			
		} elseif ($this->type=='alpha') {
			
			// create alphabetic links
			foreach ($alpha as $letter) {
				$page_links .= "<a href='javascript:void(0)' onclick=\"pagination('".$paginator_id."', {type:'alpha', letter:'".$letter."'});\">".$letter."</a>\n | ";
			}
			$page_links = rtrim($page_links, " | ");
			
			//$output .= "<p class='pagination'>".$page_links."</p>\n";
			
			foreach ($alpha as $letter) {
				$output .= "<div class='".$paginator_id."_pages' id=\"page_".$letter."_".$paginator_id."\"";
				$output .= ($letter!='A') ? " style='display:none;'>\n" : ">\n";
				
				foreach ($elements as $elem) {
					// if passed an array will look for relevent SORT key and use this to sort by
					if (is_array($elem)) {
						if (ucfirst(substr(trim(strip_tags($elem[$sort])),0,1))==$letter) { 
							$output .= "<div id=\"".$paginator_id."_item_".$e."\">\n";
							foreach ($elements[$e] as $col) {
								$output .= $col;
							}
							$output .= "</div>";
							$e++;
						}
					
					// otherwise strips all HTML and gets first letter
					} else {
						if (ucfirst(substr(trim(strip_tags($elem)), 0,1))==$letter) { 
							$output .= "<div id=\"".$paginator_id."_item_".$e."\">\n";
							$output .= $elem;
							$output .= "</div>";
							$e++;
						}					
					}				
				}
				$output .= "</div>\n";
			}
			
			$output .= "<p class='pagination'>".$page_links."</p>\n";
			
		} elseif ($this->type=='date') {
			// get date range
			foreach ($elements as $key=>$elem) {

				// check if is array, and if so if 'publish_date' is in array
				if (is_array($elem) && !empty($elem['publish_date'])) {
					$start = (strtotime($elem['publish_date'])<$start || empty($start)) ? strtotime($elem['publish_date']) : $start ;
					$end = (strtotime($elem['publish_date'])>$end || empty($end)) ? strtotime($elem['publish_date']) : $end ;
				
				// otherwise try and use array key as date
				} elseif (strtotime($key)) {
					if (strlen($key)==4) { // sort by year
						$by_year = true;
						$date_range[] = $key;
					} else {
						$start = (strtotime($key)<$start || empty($start)) ? strtotime($key) : $start ;
						$end = (strtotime($key)>$end || empty($end)) ? strtotime($key) : $end ;
					}
				} else {
					return 'Pagination error - no dates found for sorting';
				}
			}
			
			if ($by_year) {
				$years = array_unique($date_range);
				sort($years);
				
				$start = $years[0];
				$end = end($years);
				$y = $start;
				$y_end = $end;
			} else {
				$y = date('Y', $start);
				$y_end = date('Y', $end);
			}
			
			$caloutput .= "<div class='pagination'><ul>";
			// loop through each year between start and end dates
			do {	
				// output year part of pagination nav
				$caloutput .= "\n\t<li><a onclick=\"pagination('".$paginator_id."', 'date', {type:'date', year:'".$y."'})\">".$y."</a><ul>";
				// start outputting year container div
				$pagoutput .= "\n<div id=\"".$paginator_id."_".$y."\" class='paginator_year ".$paginator_id."_pages' rel='".$y."'";
				$pagoutput .= ($y==date('Y')) ? "" : " style='display:none;'";
				$pagoutput .= ">\n";
				$pagoutput .= "\n<h3>".$y."</h3>";
				
				if (!$by_year) {
					// loop through 12 months
					for ($m=1; $m<=12; $m++) {
						// first check if any elements are in this month
						$do_month = false;
						$month_output = "";
						foreach ($elements as $key=>$elem) {
							if ($y.$m==date('Yn', strtotime($key))) {
								$do_month = true;
								$month_output .= $elem;
							}
						}
						if ($do_month == true) {
							// output month parts of each pagination nav				
							$caloutput .= "\n\t\t<li><a onclick=\"pagination('".$paginator_id."', {type:'date', year:'".$y."',month:'".$m."'})\">".date('F', strtotime($y."-".$m."-01"))."</a></li>";
							// start outputting month divs
							$pagoutput .= "\n<div id=\"".$paginator_id."_".$y.$m."\" class='paginator_month ".$paginator_id."_pages' rel='".$m."'";
							$pagoutput .= ($y.$m==date('Yn')) ? "" : " style='display:none;'";
							$pagoutput .= ">\n";
							$pagoutput .= "\n<h4>".date('F', strtotime($y."-".$m."-01")).", ".$y."</h4>";
							
							$pagoutput .= $month_output;
							
							$pagoutput .= "</div>\n"; // end month container
						}
					}
				} else {
					foreach ($elements as $key=>$elems) {
						if ($y==$key) {
							foreach ($elems as $elem) {
								$pagoutput .= $elem;
							}
						}
					}
				}
				
				$pagoutput .= "</div>\n";  // end year container
				$caloutput .= "\n\t</ul></li>"; // end year menu item
				$y++;
			} while ($y <= $y_end);
			$caloutput .= "\n</ul><div style='clear:both'></div></div>";
			
			
			$output .= $caloutput . $pagoutput;
			
				
		} else {
			$output = 'Pagination error';
		}
		if (!empty($_GET['p'])) {
			ob_start();
			?>
			<script type='text/javascript'>
			$(document).ready(function() { pagination('<?=$paginator_id?>', {type:'<?=$this->type?>', letter:'<?=$_GET['p']?>'}); });
			</script>            
			<?php
			$output .= ob_get_clean();
		}
		if (!empty($_GET['date'])) {
			$date_split = explode("_", $_GET['date']);
			$settings = (count($date_split)==2) ? "year:'".$date_split[0]."', month:'".$date_split[1]."'":"year:'".$date_split[0]."'" ;
			ob_start();
			?>
			<script type='text/javascript'>
			$(document).ready(function() { pagination('<?=$paginator_id?>', {type:'<?=$this->type?>', <?=$settings?>}); });
			</script>            
			<?php
			$output .= ob_get_clean();
		}
		if (count($elements)) {
			return "<div class='pagination_block' id='".$paginator_id."''>".$output."</div>";
		} else {
			return "<p><em>no results</em></p>";
		}
	}
}
$paginator = new Paginator();
?>