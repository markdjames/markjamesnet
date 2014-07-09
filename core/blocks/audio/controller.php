<?php
class AudioBlock extends BlockController {
	
	public function display($block=NULL) {
		global $page;
		global $mod;
		global $db;
		
		$page_id = (isset($page['pid'])) ? $page['pid'] : $mod['id'] ;
		
		if ($block==NULL) {
			$this->build($this->block);
		} else {
			$this->b = $block;
		}
		$files = json_decode($this->b['files'], true);

		$width = (isset($this->b['width'])) ? " width:".$this->b['width'].";" : "" ;

		if (count($files)) {
			$first_file = reset($files);
			$root = (isset($first_file['root'])) ? $first_file['root'] : $page_id ;
			ob_start();
			?>
			<div class='audio_block' style='margin:0 0 20px 0;<?=$width?>'>		
				<?=(!empty($this->b['title'])) ? "<h2>".$this->b['title']."</h2>" : "";?>
                <audio id="audio_player_<?=$this->b['id']?>" src="<?=BASE?>/assets/audio/<?=trim($root,"/")."/".$first_file['src']?>" type="audio/mp3" controls="controls"></audio>
                <div class="mejs-list">
                    <ul>
                    <?php
					$i=0;
                    foreach($files as $k=>$file) {
						
                        if (isset($file['src'])) {
							$root = (isset($file['root'])) ? $file['root'] : $page_id ;							
                            $file_parts = pathinfo($file['src']);
                            if (empty($file['caption'])) $file['caption'] = basename($file['src']);
							?>
							<li<?=($i==0)?" class='current'":"";?> data-src="<?=BASE?>/assets/audio/<?=trim($root,"/")."/".$file['src']?>"><?=$file['caption']?>

                            </li>                      
							<?php
							$i++;						
                        }
                    }
                    ?>
                    </ul>
                </div>
    
                <script>
                $(document).ready(
                    function() { 
                        $("#audio_player_<?=$this->b['id']?>").mediaelementplayer({
                            audioWidth:'100%',
                            audioHeight:30,
                            features: ['playpause','progress','current', 'volume'],
                            success: function (mediaElement, domObject) {
                                mediaElement.addEventListener('ended', function (e) {
                                    mejsPlayNext(e.target);
                                }, false);
                            },
                            keyActions: []
                        }); 
                        $('.mejs-list li').click(function() {
                            $(this).addClass('current').siblings().removeClass('current');
                            var audio_src = $(this).data('src');
                            $('audio#audio_player_<?=$this->b['id']?>:first').each(function(){
                                this.player.pause();
                                this.player.setSrc(audio_src);
                                this.player.play();
                            });
                        });
                    }
                );
                function mejsPlayNext(currentPlayer) {
                    if ($('.mejs-list li.current').length > 0) { 
                        var current_item = $('.mejs-list li.current:first'); 
                        var audio_src = $(current_item).next().data('src');
                        current_item.next().addClass('current').siblings().removeClass('current');
                    } else { 
                        var current_item = $('.mejs-list li:first'); 
                        var audio_src = $(current_item).next().data('src');
                        current_item.next().addClass('current').siblings().removeClass('current');
                    }
                
                    if (current_item.is(':last-child')) { 
                        current_item.removeClass('current');
                    } else {
                        currentPlayer.setSrc(audio_src);
                        currentPlayer.play();
                    }
                }
                </script>
                <div style='clear:both'></div>
			</div>
			<?php
			
			$output = ob_get_clean();
		}
		return $output;
	}

}

$audioblock = new AudioBlock();

