<?php
class CustomHtmlBlock extends BlockController {
	
	public $id = '';
	
	public function display($block=NULL) {
			
		if ($block==NULL) {
			$this->build($this->block);
		} else {
			$this->b = $block;
		}
		
		return $this->b['content'];
		
	}

}

