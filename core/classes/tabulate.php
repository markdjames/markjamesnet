<?php
class Tabulate {
	
	public $cols = 4;
	
	public $highlight_column;
	public $highlight_value;
	public $highlight_color = '#ffcccc';
	
	public $type = 'count';
	public $paginate = true;
	public $per_page = 10;
	
	public $orderby = ''; // choose which column to order by when using alphabetical sorting
		
	/**********
	* create a data table
	* 
	* $data:Array - each element = string of one cell
	* ********/
	function createTable($data) {
		
		$row_count = ceil(count($data)/$this->cols);
		$cell_count = $row_count*$this->cols;
		
		for ($j=0; $j<$row_count; $j++) {
			$rows .= "<tr>";
			for ($i=0; $i<$this->cols; $i++) {
				$rows .= "<td style='width:".round(100/$this->cols)."%%'>%s</td>";
			}
			$rows .= "</tr>";
		}
		
		$table = "<table><tbody>";
		$table .= $rows;
		$table .= "</tbody></table>";
		
		while (count($data)<$cell_count) $data[] = '';

		$output = vsprintf($table, $data);
		
		return $output;
	}
	
	
	/********************************************
	* Create a pagninated, orderable visualisation of a data set
	*  
	* @data:Array
	* @ignore:Array - columns to ignore
	* @additional:Array - additional columns to add to each row, indexed by record id
	* $dont_filter:Array (or Boolean value 'true' to stop all filtering)
	*********************************************/
	function createDataTable ($data, $ignore=array(), $additional=array(), $dont_filter=array(), $module=NULL) {	
	
		$filter_count=0;

		if (count($data)) {
			// loop through URL vars and filter out any rows that dont match
			foreach($_GET as $key=>$filter) {
				if (strpos($key, 'filter_')!==false && !empty($filter)) {
					foreach ($data as $id=>$row) {
						foreach ($row as $col=>$val) {
							$k = str_replace('filter_', '', $key);
							$filter = strip_tags(str_replace("__", ".", $filter));
							$val = strip_tags($val);
							if (!in_array($col, $ignore) && $col==$k && $val!==$filter) {
								unset($data[$id]);
							}
						}
					}
					$filter_count++;
				}
			}
		} else {
			return "<p>no data supplied</p>";
		}
		
		// if user is filtering then force non-alphabetical ordering
		if ($filter_count) $this->type = 'count'; $this->per_page = 50;
		
		if (count($data)) {
			$col_count = (count(end($data)) - count($ignore));
			if ($col_count==0) {
				exit();
			}
			$col_width = round(90 / $col_count);
		
			// column headers
			$output = "<table class='tabulate'><tr style='border:0;'>";
			foreach ($data as $row) {
				foreach ($row as $col=>$val) {
					if (!in_array($col, $ignore)) {
						$output .=  "<th style='width:".$col_width."%'>";
						$output .= ucwords(str_replace("_", " ", $col));
						
						if (empty($_GET['export']) && $dont_filter!=true && !in_array($col, $dont_filter)) $output .= "<br /><a class='order_arw' href='".PATH."&".buildQuery(array('sort','order'))."&sort=".$col."&order=ASC'><img src='".BASE."/core/images/icons/arw_up.png' alt='sort'></a> <a class='order_arw' href='".PATH."&".buildQuery(array('sort','order'))."&sort=".$col."&order=DESC'><img src='".BASE."/core/images/icons/arw_dwn.png' alt='sort'></a>";
		  
						$output .= "</th>";	
					}
				}
				break;
			}
			$output .= "</tr>";
			
			// filters 
			$filters = array();
			if (is_array($dont_filter) || $dont_filter!=true) {
				foreach ($data as $row) {
					foreach ($row as $col=>$val) {
						$filters[$col] = (isset($filters[$col]) && is_array($filters[$col])) ? $filters[$col] : array();
						if (!in_array($col, $ignore) && 		//not in ignored columns
							!in_array($col, $dont_filter) && 	//not in ignored filters array
							!in_array($val, $filters[$col]) && 	//not already in array
							!is_array(@unserialize($val)) &&		//not a serialized array
							strlen($val)<50 &&					//not more than 16 chars
							!empty($val)) {						//not empty
								$filters[$col][] = $val; //str_replace("#", "", $val);
						}
					}
				}
			
				if (empty($_GET['export'])) {
					$output .= "<tr style='height:25px;' class='filters'>";
					foreach ($data as $row) {
						foreach ($row as $col=>$val) {
							if (!in_array($col, $ignore)) {
								$output .= "<td style='width:".$col_width."%; padding:5px;'>";
								if (count($filters[$col])) { // check if any filters for column
									$output .= "<select class='data_filter' onchange=\"window.location.href='".PATH."&".buildQuery('filter_'.$col)."&filter_".$col."='+this.value\">";
									$output .= "<option value=''>".str_replace("_", " ", $col)."</option>";
									natsort($filters[$col]);
									foreach ($filters[$col] as $filter) {
										$filter = strip_tags($filter);
										$output .= "<option value='".urlencode(str_replace(".", "__", $filter))."'";
										$output .= (isset($_GET['filter_'.$col]) && $_GET['filter_'.$col]==str_replace(".", "__", $filter)) ? " selected":"";
										$output .= ">".$filter."</option>";
									}
									$output .= "</select>";
								}
								$output .= "</td>";	
							}
						}
						break;
					}
					$output .= "<td class='blank_cell' colspan='2' style='width:10%; padding:5px;'><span style='font-size:11px; padding-left:5px;'><a href='".PATH."'>clear all</a></span></td>";
					$output .= "</tr>";
				}
			}
			
			$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
			
			// show 'all' page if not many records
			$show_all = (count($data) < $this->per_page) ? true : false;

			if ($this->type == 'alpha') {
				$this->paginate = false;
				$this->orderby = (!empty($this->orderby)) ? $this->orderby : key(reset($data));
				foreach ($data as $d) {
					$k = strtoupper($d[$this->orderby][0]);
					$k = (empty($k)) ? 'Numeric' : $k;
					$new_data[$k][] = $d;
				}	
				ksort($new_data);
				$i = 0;

				$output .= "<tbody id='page".key($new_data)."' class='tablebody'>";
				foreach ($new_data as $az=>$data) {
					if ($i>0) {
						$output .= "</tbody>";
						$output .= "<tbody id='page".$az."' ";
						$output .= ($_GET['print']!=1 && !$show_all) ? "style='display:none' ":"";
						$output .= "class='tablebody'>";
					}

					foreach ($data as $row) {
						$output .= "<tr id='".$row['id']."'";
						$output .= (!empty($this->highlight_column) && $row[$this->highlight_column]==$this->highlight_value) ? " style='background-color:".$this->highlight_color."'" : "";
						$output .= ">";
						foreach ($row as $col=>$val) {
							if (!in_array($col, $ignore)) {
								$output .=  "<td style='width:".$col_width."%' data-name='".$col."'>";
								// check if serialized, if so display properly
								if ($this->is_serial($val) && count(unserialize($val))) {
									
									foreach (unserialize($val) as $c1=>$v1) {
										if (is_array($v1)) {
											foreach ($v1 as $c2=>$v2) {
												$output .= "<p>";
												$output .= (!is_numeric($c2)) ? "<strong>".ucwords(str_replace("_", " ", $c2))."</strong> " : "";
												$output .= $v2."</p>";
											}
										} else {
											$output .= "<p>";
											$output .= (!is_numeric($c1)) ? "<strong>".ucwords(str_replace("_", " ", $c1))."</strong> " : "";
											$output .= $v1."</p>";
										}
									}
									
								// check if date value
								} elseif (date('Y-m-d H:i:s', strtotime($val)) == $val || date('Y-m-d', strtotime($val)) == $val) {
									$output .=  date('F j Y', strtotime($val));
								
								// check if time value
								} elseif (strpos($val, ":")!==false && date('H:i:s', strtotime('2000-01-01 '.$val)) == $val) {
									$output .=  date('H:i', strtotime('2000-01-01 '.$val));
									
								} elseif (!empty($val)) {
									// check for two hashes - if so its a repertoire that needs formatting
									if (substr_count($val, '#')==2) {
										$output .= substr($val, 0, strpos($val,"#"))."<em>".substr($val, strpos($rep_title_1,"#")+1, strrpos($val,"#")-(strpos($val,"#")+1))."</em>".substr($val, strrpos($val,"#")+1);	
										
									// check if showing an email, if so link it up
									} elseif (filter_var($val, FILTER_VALIDATE_EMAIL)) {
										$output .= "<a href='mailto:".$val."'>".$val."</a>";
										
									// otherwise just out put!
									} else {
										$output .= $val;	
									}
								}
								$output .=  "</td>";
							}
						}
						if (count($additional)) {
							foreach($additional[$row['id']] as $add) {
								$output .= '<td width="25" class="clear_cell" style="border:none;">'.$add.'</td>';
							}
						}
						$output .= "</tr>";	
					}
					$i++;
				}		
				
			} else {

				$i = 1;
				$page_count = 1;
				$output .= "<tbody id='page".$i."' class='tablebody'>";
				foreach ($data as $row) {
					if ($this->type=='count' && $this->paginate==true && $i%$this->per_page==0) {
						$output .= "</tbody>";
						$output .= "<tbody id='page".(($i/$this->per_page)+1)."' ";
						$output .= "style='display:none' ";
						$output .= "class='tablebody'>";
						$page_count++;
					} 
					
					$output .= "<tr id='".$row['id']."'";
					$output .= (!empty($this->highlight_column) && $row[$this->highlight_column]==$this->highlight_value) ? " style='background-color:".$this->highlight_color."'" : "";
					$output .= ">";
					foreach ($row as $col=>$val) {
						if (!in_array($col, $ignore)) {
							$output .=  "<td style='width:".$col_width."%' data-name='".$col."'>";
							
							// check if serialized, if so display properly
							if ($this->is_serial($val) && count(unserialize($val))) {
								
								foreach (unserialize($val) as $c1=>$v1) {
									if (is_array($v1)) {
										foreach ($v1 as $c2=>$v2) {
											$output .= "<p>";
											$output .= (!is_numeric($c2)) ? "<strong>".ucwords(str_replace("_", " ", $c2))."</strong> " : "";
											$output .= $v2."</p>";
										}
									} else {
										$output .= "<p>";
										$output .= (!is_numeric($c1)) ? "<strong>".ucwords(str_replace("_", " ", $c1))."</strong> " : "";
										$output .= $v1."</p>";
									}
								}
								
							
							// check if date value
							} elseif (date('Y-m-d H:i:s', strtotime($val)) == $val || date('Y-m-d', strtotime($val)) == $val) {
								$output .=  date('F j Y', strtotime($val));
							
							// check if time value
							} elseif (strpos($val, ":")!==false && date('H:i:s', strtotime('2000-01-01 '.$val)) == $val) {
								$output .=  date('H:i', strtotime('2000-01-01 '.$val));
								
							} elseif (!empty($val)) {
								// check for two hashes - if so its a repertoire that needs formatting
								if (substr_count($val, '#')==2) {
									$output .= substr($val, 0, strpos($val,"#"))."<em>".substr($val, strpos($rep_title_1,"#")+1, strrpos($val,"#")-(strpos($val,"#")+1))."</em>".substr($val, strrpos($val,"#")+1);	
									
								// check if showing an email, if so link it up
								} elseif (filter_var($val, FILTER_VALIDATE_EMAIL)) {
									$output .= "<a href='mailto:".$val."'>".$val."</a>";
									
								// otherwise just out put!
								} else {
									$output .= $val;	
								}
							}
							$output .=  "</td>";
						}
					}
					if (count($additional)) {
						foreach($additional[$row['id']] as $add) {
							$output .= '<td width="25" class="clear_cell" style="border:none;">'.$add.'</td>';
						}
					}
					$output .= "</tr>";
					$i++;
				}
			}
			$output .= "</tbody></table>";
			if (!empty($_GET['p'])) {
				ob_start();
				?>
				<script type='text/javascript'>
				$(document).ready(function() { paginate('<?=$_GET['p']?>'); });
				</script>            
				<?php
				$output .= ob_get_clean();
			}
			
			$pagination = "";
			if ($page_count>1 || $this->type=='alpha') {
				$pagination .= "<div class='pagination'><p>";
				if ($page_count>1) {
					for ($i=1; $i<=$page_count; $i++) {
						$pagination .= "<a onclick='paginate(".$i.")'>".$i."</a> | ";
					}
				} elseif ($this->type=='alpha') {
					$pagination .= "<a onclick=\"paginate('Numeric')\">#</a> | ";
					foreach ($alpha as $az) {
						$pagination .= "<a onclick=\"paginate('".$az."'";
						$pagination .= ($module) ? ", '".$module."'":"";
						$pagination .= ")\">".$az."</a> | ";
					}	
				}
				$pagination .= "<a onclick=\"paginate('all')\">All</a>";
				$pagination = $pagination."</p></div>";
			}
			
			return (empty($_GET['export']) && isset($pagination)) ? $pagination.$output.$pagination : $output;
		} else {
			return "<p><em>no matching records</em> <span style='font-size:11px'><a href='".PATH."'>clear all filters</a></span></p>";
		}
	}
	
	/**
	 * Check if a string is serialized
	 * @param string $string
	 */
	function is_serial($string) {
		return (@unserialize($string) !== false);
	}
}

$table = new Tabulate();
?>