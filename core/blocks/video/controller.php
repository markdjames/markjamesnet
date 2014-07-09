<?php
class VideoBlock extends BlockController {
	
	public $width = 1000;
	public $height = 0;
	public $mode = 'full';
	
	public function display($block=NULL) {
		$p = new Page();
		$v = new Video();
		
		if ($block==NULL) {
			$this->build($this->block);
		} else {
			$this->b = $block;
		}
		
		$output = "<div class='video_block'>";
		$v->width = $this->width;
		$output .= $v->outputVideo($this->b);
		
		if ($this->mode=='preview') {
			$path = $p->getPath($this->b['page_id'], $this->b['page_type']);		
			$output .= "<h2><a href='".BASE."/".$path."'>".$this->b['title']."</a></h2>";
			// if in preview mode on show first 250 characters
			$output .= (strlen($this->b['description'])>250 && strpos($this->b['description'], " ", 250)!==false) ? trim(substr($this->b['description'], 0, strpos($this->b['description'], " ", 250)))."...</p>" : $this->b['description'];
		} else {
			$output .= "<h2>".$this->b['title']."</h2>";
			$output .= $this->b['description'];
		}
		
		$output .= "<div style='clear:both'></div></div>";
		
		return $output;
	}
}

$videoblock = new VideoBlock();

