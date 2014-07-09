<?php
require_once '../../../../lib/bootstrap.php';
require_once '../../../../tools/phpFileTree/php_file_tree.php';
?>
<!--<script src="<?=BASE?>/core/js/libs/modernizr-2.0.6.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>  
<script src="<?=resolve('/js/scripts.php', false)?>?uid=<?=date('dH');?>"></script>-->
<script src="<?=BASE?>/core/js/libs/jquery.Jcrop.min.js"></script>

<link href="<?=BASE?>/core/js/libs/jcrop_css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=BASE?>/tools/phpFileTree/styles/default/default.css" rel="stylesheet" type="text/css" media="screen" />
<h3>Image Assets</h3>
<form onsubmit="return false;" class="coords" id='image_coords'>
    <input type="hidden" id="x1" 	name="coords[x1]" />
    <input type="hidden" id="y1" 	name="coords[y1]" />
    <input type="hidden" id="x2" 	name="coords[x2]" />
    <input type="hidden" id="y2" 	name="coords[y2]" />
    <input type="hidden" id="w" 	name="coords[w]" />
    <input type="hidden" id="h" 	name="coords[h]" />
    <input type="hidden" id="dir" 	name="dir" />
    <input type="hidden" id="file" 	name="file" />
</form>

<?php
echo php_file_tree($_SERVER['DOCUMENT_ROOT'].BASE."/assets/images", "[link]", array('jpg','png','gif','jpeg'));
?>
<script type="text/javascript">
$(document).ready( function() {
	
	// Hide all subfolders at startup
	$(".php-file-tree").find("UL").hide();
	
	// Expand/collapse on click
	$(".pft-directory A").click( function() {
		$(this).parent().find("UL:first").slideToggle("medium");
		if( $(this).parent().attr('className') == "pft-directory" ) return false;
	});
	
	$('.pft-file > a').each(function() {
		$(this).bind('click', function (e) { assets.images.clickView(e) });
		//$(this).attr('href', 'javascript:void(0)');
	})

});
</script>