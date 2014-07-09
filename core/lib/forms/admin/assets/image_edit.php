<?php
require_once '../../../../lib/bootstrap.php';
?>
<script src="<?=BASE?>/core/js/libs/jquery.Jcrop.min.js"></script>
<link href="<?=BASE?>/core/js/libs/jcrop_css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" media="screen" />
<h3>Edit Image Crop</h3>
<form onsubmit="return false;" class="coords" id='image_coords'>
    <input type="hidden" id="x1" 	name="coords[x1]" />
    <input type="hidden" id="y1" 	name="coords[y1]" />
    <input type="hidden" id="x2" 	name="coords[x2]" />
    <input type="hidden" id="y2" 	name="coords[y2]" />
    <input type="hidden" id="w" 	name="coords[w]" />
    <input type="hidden" id="h" 	name="coords[h]" />
    <input type="hidden" id="dir" 	name="dir" />
    <input type="hidden" id="file" 	name="file" />
    <input type="hidden" id="path" 	name="path" />
</form>
<div id='edit_image' style='width:300px; margin:auto;'></div>
<script>
assets.images.view('edit_image', '<?=$_POST['src']?>', '<?=$_POST['path']?>');
var crop_image_width = '<?=$_POST['width'];?>';
var crop_image_height = '<?=$_POST['height'];?>';
</script>
