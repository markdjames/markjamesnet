<?php
require_once '../../../../lib/bootstrap.php';
require_once '../../../../tools/phpFileTree/php_file_tree.php';
?>
<link href="<?php echo BASE; ?>/tools/phpFileTree/styles/default/default.css" rel="stylesheet" type="text/css" media="screen" />
<h3>Audio Assets</h3>
<?php
echo php_file_tree("../../../../../assets/audio", "javascript:alert('You clicked on [link]');", array('mp3','wav','ogg'));
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

});
</script>