<?php
$fileblock = new FileBlock();
$fileblock->block = $block;

?>
<h2>Downloads</h2>
<?php
echo $fileblock->display();