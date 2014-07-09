<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$module = $m->getModule($_POST['id']);
	$pagetypes = $p->getPageTypes();
	?>
	<h3>Module Settings</h3>
	<form id="page_settings" method="POST" action="" enctype="multipart/form-data">
		<label for="title">Module Title<br />
		<input type="text" value="<?php echo $module['title']; ?>" id="title" name="title" /></label>
        
        <label>Page Type<br />
        <select name="template">
            <?php
            foreach ($pagetypes as $type) {
                echo "<option value='".$type."'";
				echo ($module['template']==$type) ? " selected":"";
				echo ">".ucwords(str_replace("_", " ", $type))."</option>";
            }
            ?>
        </select></label>
        
        <label>Description<br />
		<textarea name="description"><?=$module['description']?></textarea></label>
        
        <label for="keywords">Keywords (for search optimisation, comma seperated)<br />
		<input type="text" value="<?=$module['keywords']?>" id="keywords" name="keywords" /></label>
        
        <label>Sub-navigation Title<br />
    	<input type="text" value="<?=$module['navigation_title']?>" name="navigation_title" /></label>

        <label><input type="checkbox" value="1" <?php echo ($module['show_navigation']==1)?"checked":""; ?> name="show_navigation" /> Show Navigation?</label>
		
		<input type="hidden" name="function" value="update_module_settings" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="module_id" value="<?php echo $module['id']; ?>" />
        <input type="submit" value="Update" />
	</form>
    <script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datetimepicker({ dateFormat: "dd / mm / yy" });
	});
	</script>
	<?php 
}