<?php
class Video {
	
	public $width = '1000';
	public $height = '';
		
	// pass a block array 
	function outputVideo($block, $style='') {
		
		$this->height = (empty($this->height)) ? round(($this->width/16)*9) : $this->height;
		
		if (!empty($block['vimeo'])) {
			$vimeo_id = $this->sanitizeVimeo($block['vimeo']);
			$output = '<div class="video_wrapper"><iframe src="http://player.vimeo.com/video/'.$vimeo_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color=700017&amp;api=1&amp;player_id=vimeo_'.$vimeo_id.'" width="'.$this->width.'" height="'.$this->height.'" id="vimeo_'.$vimeo_id.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen class="vimeo_film" style="'.$style.'"></iframe></div>';
			
		} elseif (!empty($block['youtube'])) {
			$youtube_id = $this->sanitizeYouTube($block['youtube']);
			$output = '<div class="video_wrapper"><iframe width="'.$this->width.'" height="'.$this->height.'" src="http://www.youtube.com/embed/'.$youtube_id.'?rel=0" frameborder="0" allowfullscreen class="youtube_film" style="'.$style.'"></iframe></div>';
		}
		
		return $output;
	}
	
	function outputVimeoVideo($vid, $style="") {
		return '<div class="video_wrapper"><iframe src="http://player.vimeo.com/video/'.$vid.'?title=0&amp;byline=0&amp;portrait=0&amp;color=700017&amp;api=1&amp;player_id=vimeo_'.$vid.'" width="'.$this->width.'" height="'.$this->height.'" id="vimeo_'.$vid.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen class="vimeo_film" style="'.$style.'"></iframe></div>';
	}
	
	function sanitizeVimeo($url) {
		$output = str_replace("http://", "", str_replace("https://", "", $url));
		$output = str_replace("www.", "", str_replace("vimeo.com", "", $output));
		$output = str_replace("/", "", $output);
		return $output;		
	}
	
	function sanitizeYouTube($url) {
		$output = str_replace("http://", "", str_replace("https://", "", $url));
		$output = str_replace("www.", "", str_replace("youtube.com", "", $output));
		$output = str_replace("v=", "", str_replace("watch", "", $output));
		$output = str_replace("/", "", str_replace("embed", "", $output));
		$output = str_replace("?", "", $output);
		return $output;		
	}
}

$v = new Video();