<?php
class AggregationBlock extends BlockController {
	
	public $width = 1000;
	public $height = 0;
	public $display_date = false;
	public $manual_order = false;
	
	public function display() {
		
		global $db;
		global $is_admin;
		
		$this->build($this->block);
		
		if (isset($this->b['orderby']) && $this->b['orderby']=='manual') {
			$this->manual_order = true;
			$this->b['orderby'] = 'id';
		}
		
		
		// get current page details (i.e. page aggregator block sits on)
		$p = new Page();
		$b = new Block();
		$current_page_id = $b->getPageId($this->b['id'], 'aggregation');
		$current_page = $p->getPage($current_page_id);
		
		// get aggregator type
		$sql = ($is_admin) ? "" : "AND published=1 "; // if admin get all pages, published or not
		$sql .= ($this->b['scope']=='path') ? "AND path LIKE '".$current_page['path']."%' AND path !='".$current_page['path']."' " : "";  // check scope
		$db->vars['template'] = $this->b['type'];
		$pages = $db->select("SELECT * FROM pages WHERE template=:template AND archived=0 ".$sql."ORDER BY ".$this->b['orderby']." ".$this->b['order']);
		
		// switch off pagination if sorting
		if (isset($_GET['admin_sort']) && $_GET['admin_sort']==1 && $is_admin) {
			$this->b['paginate'] = 0;
		}	
			
		if (is_array($pages)) {
			$pages = $this->manualOrder($pages);
			$elements = array();
			$i=0;

			foreach($pages as $page) {
				
				$i = ($this->b['paginate_by']=='date') ? $page['publish_date'] : $i+1;
				if (isset($_GET['admin_sort']) && $_GET['admin_sort']==1 && $is_admin) {
					// if in sort mode show only title in list
					$elements[$i] = "<li id='order_".$page['pid']."' class='aggregation_item_sort'>";
					$elements[$i] .= $page['title'];
					$elements[$i] .= "</li>";
				} else {
					$elements[$i] = "<div class='aggregation_item'><h3>";
					$elements[$i] .= "<a href='".BASE."/".$page['path']."'>".$page['title']."</a>";
					$elements[$i] .= ($page['published']==0) ? " <span style='color:red'>unpublished</span>":"";
					$elements[$i] .= "</h3>";
					$elements[$i] .= ($this->display_date) ? "<p>".date('d F Y, H:i', strtotime($page['publish_date']))."</p>" : "" ; 
					$elements[$i] .= ($this->b['paginate_by']=='date') ? "<p class='date'>".date('d F Y', strtotime($page['publish_date'])) : "" ;
					$elements[$i] .= truncateText($page['content'], 250)." <p class='read_more'><a href='".BASE."/".$page['path']."'>read more</a></p>";
					
					$elements[$i] .= "</div>";
				}
			}
		}
	
		return (isset($elements)) ? $this->output($elements) : false;
	}
		
	function output($elements) {
		global $is_admin;
		global $paginator;
		
		$output = "";
		
		if (!empty($this->b['title'])) {
			$output .= "<h2>".$this->b['title']."</h2><div style='clear:both'></div>";
		}

		if ($this->b['paginate']==1) {
			$paginator->type	=$this->b['paginate_by'];
			$paginator->per_page=$this->b['per_page'];
			$output .= $paginator->createPagination($elements);
		} else {
			$output .= (isset($_GET['admin_sort']) && $_GET['admin_sort']==1 && $is_admin) ? "<ul id='sortable'>" : "" ;
			$output .= (count($elements)) ? implode("\n",$elements) : "<p><em>Sorry, there were no matches</em></p>";
			$output .= (isset($_GET['admin_sort']) && $_GET['admin_sort']==1 && $is_admin) ? "</ul>" : "" ;
		}
		return "<div class='aggregation_block'>".$output."</div>";
	}
	
	/*****************************
	* check if elements should be sorted by manual order
	*****************************/
	function manualOrder($items) {
		if ($this->manual_order && !empty($this->b['settings'])) {
			$order_a = json_decode($this->b['settings'], true);
			$order = $order_a['order'];
			foreach($items as $page) {
				if (array_search($page['pid'], $order)!==false) {
					$reordered[array_search($page['pid'], $order)] = $page; 
				} else {
					$unsorted[] = $page;
				}
			}
			ksort($reordered);
			$pages = $reordered;
			if (isset($unsorted) && count($unsorted)) foreach ($unsorted as $u) array_push($pages, $u);
			return $pages;
		} else {
			return $items;
		}		
	}
}

$aggregationblock = new AggregationBlock();
if (isset($block)) {
	$aggregationblock->block = $block;
}

