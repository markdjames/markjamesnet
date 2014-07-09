<?php
/**
 * Extends the core Page class with custom methods for outputting navigations
 *
 * @package	Classes\Custom
 */
class Navigation extends Page {
	
	/**
	 * Pass array of elements, each with a 'link' and a 'text' value and optional 'click' to output navigation
	 *
	 * @param	array	$elements	Array of elements, each with a 'link' and a 'text' value and optional 'click' to output navigation
	 * @param	string	$title		Title of the navigation
	 *
	 * @return	string	HTML output
	 */
	public function customNav($elements, $title="In this section") {
		global $path;
		$parent = $this->getParent($path);
		
		ob_start();
		?>
        <style>
		.section_navigation li ul li { width:50%%; float:left; }
		</style>
        <div class='section_navigation dropdown'>
        	<ul>
            	<li><a href='javascript:void(0)'><?=(!empty($title))?$title:"In this section";?> &nbsp;<img src="<?=BASE?>/images/icons/nav_arrow.png" class='navigation_arrow' /></a>
                	<ul>
                    %s
                    </ul>
              	</li>
          	</ul>
        </div>
        <div style='clear:both; height:30px;'></div>
        <?php
		$template = ob_get_clean();
		
		if (count($elements)) {
			$links = "";
			foreach ($elements as $element) {
				$element['link'] = (empty($element['link'])) ? "javascript:void(0)" : $element['link'];
				$links .= "<li><a href='".$element['link']."'";
				$links .= (!empty($element['click'])) ? " onclick=\"".$element['click']."\"":"";
				$links .= ">".$element['text']."</a></li>";
			}
		}
		return sprintf($template, $links);
	}
	
	/**
	 * Pass record from pages table or module name to output section navigation (i.e. pages below this one)
	 *
	 * @param	mixed 	$page		Either a (array) table row from pages/modules table or (string) path to page
	 * @param	boolean	$as_array	Return results as array rather than HTML
	 *
	 * @return	mixed	Depending on input params outputs either HTML ot multi-dimensional array
	 */
	public function getNavigation($page, $as_array=false) {
		
		global $is_admin;
	
		$output = "";
			
		if (!is_array($page)) {
			$path = $page;
		} elseif (isset($page['path'])) {
			$path = $page['path'];
		}

		if (!empty($path) && $path!="/" && isset($page['show_navigation']) && $page['show_navigation']==1) {	

			$parent = $this->getParent($path);
			$children = $this->getChildren($path);
			$siblings = $this->getSiblings($path);

			if ($as_array==false) {

				if ($siblings || $children) {
					ob_start();
					$title = ($children) ? "In ".$page['title'] : "in ".$parent['title'];
					?>
					<div class='section_navigation<?=((
														($children && count($children)>6)
														|| 
														(!$children && count($siblings)>6)
														||
														$_SESSION['tablet']==1
													)
													&& !isset($_GET['order_navigation']))? " dropdown":"";?>'>
						<!--ul>
							<li><a href='javascript:void(0)'><?=(!empty($page['navigation_title']))?$page['navigation_title']:$title;?> &nbsp;<img src="<?=BASE?>/images/icons/nav_arrow.png" class='navigation_arrow' /></a-->
							<ul id='section_navigation'>
							<?php
															
							if ($children) {	
								$i=0;
								foreach ($children as $p) {
									$p['id'] = (isset($p['id'])) ? $p['id'] : $i ;
									echo "<li id='nav_".$p['id'];
									echo (isset($p['page_type']) && $p['page_type']=='module')?"typemodule":"typepage";
									echo "'>> <a href='".BASE."/".ltrim($p['path'],"/")."'>".$p['title']."</a></li>";
									$i++;
								}
	
							} elseif ($siblings) {
								foreach ($siblings as $p) {
									echo "<li id='nav_".$p['id'];
									echo (isset($p['page_type']) && $p['page_type']=='module')?"typemodule":"typepage";
									echo "'>> <a href='".BASE."/".ltrim($p['path'],"/")."'>".$p['title']."</a></li>";
								}	
							}	
							?>		
							
							<?php
							if ($parent && !isset($_GET['order_navigation'])) {
								?>
								<li style='width:100%; margin-bottom:10px; padding-top:10px;'>> <a style="margin:0; font-weight:bold;" href='<?=DIR?>/<?=ltrim($parent['path'], "/")?>'>Back to <?=$parent['title']?></a></li>
								
								<?php
							}
							?>
							</ul>
							<!--/li>
						</ul-->
					</div>
					<?php
					$output .= ob_get_clean();
					if ((($children && count($children)>5) || (!$children && count($siblings)>5)) && !isset($_GET['order_navigation'])) {
						?>
						<style>
						.section_navigation li ul li { width:50%; float:left; }
						</style>
						<?php
					}
					/********************************
					* Show edit order link to admins
					*********************************/
					if ($is_admin) {
						$output .= "<p style='text-align:right; font-size:12px; margin-top:0;'>";
						if (isset($_GET['order_navigation']) && $_GET['order_navigation']=='true') {
							$output .= "<a href='".DIR."/".$path."'>exit ordering mode</a>";
							$output .= "<script>$(document).ready(function() { navigation.orderSetup(); });</script>";
						} else {
							$output .= "<a href='?order_navigation=true'>edit order</a>";
						}
						$output .= "</p>";
					}
					$output .= "<div style='clear:both;'></div>";
					
					return $output;
				}
			} elseif ($as_array) {
	
				$output['parent'] = $parent;
				$output['children'] = $children;
				$output['siblings'] = $siblings;
				
				return $output;
				
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Gets the next and previous page from relevent siblings
	 *
	 * @param	array	$page	Row from pages table
	 *
	 * @return	array	Array with elements ['next'] and ['previous'] 
	 */
	public function getNextPrevious($page) {
		
		$nav = $this->getNavigation($page, true);
		if ($nav['siblings']) {
			foreach ($nav['siblings'] as $k=>$p) {
				if ($p['id'] == $page['pid']) {
					$current_k = $k;
					break;
				}
			}
			if (!empty($nav['siblings'][$current_k-1])) {
				$output['previous'] = $nav['siblings'][$current_k-1];
			}
			if (!empty($nav['siblings'][$current_k+1])) {
				$output['next'] = $nav['siblings'][$current_k+1];
			}
			
			return (isset($output)) ? $output : false;
		} else {
			return false;
		}
		
	}
}
$_nav = new Navigation();