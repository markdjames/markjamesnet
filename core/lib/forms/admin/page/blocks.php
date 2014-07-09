<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$page = $p->getPage($_POST['id']);
	$template = file_get_contents(resolve("/templates/page_types/".$page['template'].".php"));
	
	$blocks = $b->getBlocks();
	?>
	<h3>Page Blocks</h3>
	<form id="page_blocks" method="POST" action="" enctype="multipart/form-data" style="margin:10px">
    	<?php if (empty($_POST['blocktype'])) {  // check if should be loading specific block type ?>
            <select name="block_type" onchange="block.getBlockForm(<?=$_POST['id']; ?>, this.value, 'page')" style='width:200px;'>
                <option value="">Select block type</option>
                <?php 
                foreach ($blocks as $b) { 
					if (strpos($template, "{".$b['name']."}")!==false) {
						?>
						<option value="<?=$b['name']; ?>"><?=ucwords(str_replace('_', ' ', $b['name'])); ?></option>
						<?php
					}
                }
                ?>
            </select>
        <?php } ?>
		<div id='block_container'></div>
		
		<input type="hidden" name="function" value="update_page_blocks" />
		<input type="hidden" name="token" value="<?=$_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?=$_POST['id']; ?>" />
        <input type="hidden" name="content_id" value="0" />
        <input type="hidden" name="page_type" value="page" />
        
	</form>
    <script type='text/javascript'>
		<?php if (!empty($_POST['blocktype'])) { // check if should be loading specific block type ?>
			block.getBlockForm(<?=$_POST['id']?>, '<?=$_POST['blocktype']?>', 'page');
		<?php } ?>
	</script>
	<?php 
}