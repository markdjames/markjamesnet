<?php
class BlockController {
	
	public $block = array();
	public $b = array();
	
	public function build ($data) {
		if (count($data)) {
			foreach ($data as $d) {
				foreach ($d as $key=>$val) {
					$this->b[$key] = $val;
				}
			}
		}		
	}
}