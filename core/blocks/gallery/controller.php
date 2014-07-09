<?php
// relies on js/gallery.js
class GalleryBlock extends BlockController {
	
	public $id = '';
	
	public $main_width = 700;
	public $main_height = 394;
	
	public $sub_width = 217;
	public $sub_height = 112;
	
	public function display($block=NULL) {
		global $page;
		global $image;
		global $is_admin;
		global $is_module;

		$p = new Page();
		
		if ($block==NULL) {
			$this->build($this->block);
		} else {
			$this->b = $block;			
		}
		
		if (!$is_module) {
			$pagetype = "page";
		} else {
			$pagetype = "module";
		}

		$output = "<div class='gallery_block'>";		
		
		if (!empty($this->b['title'])) $output .= "<h2>".$this->b['title']."</h2>";
		
		$images = json_decode($this->b['images'], true);
		
		$i=0;
		$img_js = "";
		if (count($images)) {
			foreach($images as $img) {
				if ($i==0) {
					$main_img = "<img class='page_image' src='".$image->outputImage('/assets/images/galleries/'.$page['pid']."/".$img['src'], $img['caption'], $this->main_width, $this->main_height, NULL, NULL, true)."' data-loc='".str_replace("//", '/', '/assets/images/galleries/'.$page['pid']."/".$img['src'])."' />";
					if (!empty($img['credit'])) $main_img .= "<p class='credit'>&copy; ".$img['credit']."</p>";
					if (!empty($img['caption'])) $main_img .= "<p><strong>".$img['caption']."</strong></p>";
					if (!empty($img['description'])) $main_img .= "<p>".$img['description']."</p>";
					
					
				} 
				
				$sub_images[$i] = "<div onclick='galleryJump(".$i.")'>";
				$sub_images[$i] .= "<img src='".$image->outputImage('/assets/images/galleries/'.$page['pid']."/".$img['src'], $img['caption'], $this->sub_width, $this->sub_height, NULL, NULL, true)."' />";
				//$sub_images[$i] .= "<p><strong>".$img['caption']."</strong></p></div>";
				$sub_images[$i] .= "</div>";
				
				$img_js .= "\ngallery_images[".$i."] = \"".$image->outputImage('/assets/images/galleries/'.$page['pid']."/".$img['src'], $img['caption'], $this->main_width, $this->main_height);
				if (!empty($img['credit'])) $img_js .= "<p class='credit'>\&copy; ".addslashes($img['credit'])."</p>";
				if (!empty($img['caption'])) $img_js .= "<p><strong>".addslashes($img['caption'])."</strong></p>";
				if (!empty($img['description'])) $img_js .= "<p>".addslashes($img['description'])."</p>";
				$img_js .= "\"";
				$i++;
			}
			
			$carousel = new Carousel();
			if (isset($sub_images)) {
				$carousel->width = $this->main_width;
				$carousel->elm_width = $this->sub_width;
				$gallery_carousel = $carousel->createCarousel($sub_images);
			
				$unq = uniqid();
				$this->id = $unq;
				
				if ($_SESSION['mobile']) {
					$output .= "<div class='gallery_left' onclick=\"gallery('".$unq."', 'left', '".$carousel->id."')\"></div>
								<div class='gallery_right' onclick=\"gallery('".$unq."', 'right', '".$carousel->id."')\"></div>
								<div class='gallery_img'>".$main_img."</div>";
	
				} else {
					$output .= "<div id='main_image'>
									<div class='gallery_left' onclick=\"gallery('".$unq."', 'left', '".$carousel->id."')\"></div>
									<div class='gallery_img'>".$main_img."</div>
									<div class='gallery_right' onclick=\"gallery('".$unq."', 'right', '".$carousel->id."')\"></div>
									<div style='clear:both'></div>
								</div>";
				}
				
				$output .= $gallery_carousel;
				
				if (!empty($this->b['description'])) {
					$output .= "<div class='video_description' style='margin-bottom:15px; position:relative; top:8px;'>";
					if ($is_admin) {
						$output .= "<a onclick=\"modal(".$page['pid'].", 'admin/".$pagetype."/blocks', 'medium', {blocktype:'gallery'})\"><img src='".BASE."/core/images/icons/edit16.png' style='float:right' /></a>";
					}
				
					if (stripos($this->b['description'], "<p>")!==false) {
						$output .= $this->b['description'];
					} else {
						$output .= "<p>".nl2br($this->b['description'])."</p>";
					}
					$output .= "</div>";
				}
							
				$output .= "<div style='clear:both'></div></div>";	
				
				$output .= "\n<script type='text/javascript'>\nvar gallery_images = new Array();".$img_js."\n</script>";
				
				return $output;
			} else {
				return false;
			}
		} else {
			return false;
		}
				
	}

}

$galleryblock = new GalleryBlock();

