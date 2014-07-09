<?php
class FileBlock extends BlockController {
	
	public $id = '';
	
	public function display($block=NULL) {
		global $page;
		global $mod;
		global $image;

		$p = new Page();
		
		if ($block==NULL) {
			$this->build($this->block);
		} else {
			$this->b = $block;
		}
		
		$id = (empty($page['pid'])) ? $mod['id'] : $page['pid'];
		
		$output = "<div class='files_block'>";		
		$output .= (empty($this->b['title'])) ? "<p class='files_block_title'>".$this->b['title']."</p>" : "";
		
		$files = json_decode($this->b['files'], true);
		
		$i=0;
		$output .= "<ul class='file_list'>";
		foreach($files as $k=>$file) {
			$file_parts = pathinfo($file['src']);
			$ext = strtolower($file_parts['extension']);
			if (empty($file['caption'])) $file['caption'] = basename($file['src']);

			$output .= "<li id='file_".$k."' class='".$ext."_file'><a href='".BASE."/assets/files/".$id."/".$file['src']."' target='_blank'>".$file['caption']."</a>";
			if ($ext=='jpg' || $ext=='png') $output .= "<br /><img style='margin-bottom:20px' src='".BASE."/assets/files/".$id."/".$file['src']."' />";
			$output .= "</li>";
		}
		$output .= "</ul>";
		
		$output .= "</div>";
		
		
		return $output;
		
	}

}

$fileblock = new FileBlock();

