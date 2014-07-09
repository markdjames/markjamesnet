<?php
require_once '../../../bootstrap.php';

if (is_numeric($_POST['id'])) {
	$page = $p->getPage($_POST['id']);
	$pagetypes = $p->getPageTypes();
	?>
	<h3>Page Settings</h3>
	<form id="page_settings" method="POST" action="" enctype="multipart/form-data">
		<label for="title">Page Title<br />
		<input type="text" value="<?=$page['title']?>" id="title" name="title" /></label>
        
        <label>Page Type<br />
        <select name="template">
            <?php
            foreach ($pagetypes as $type) {
                echo "<option value='".$type."'";
				echo ($page['template']==$type) ? " selected":"";
				echo ">".ucwords(str_replace("_", " ", $type))."</option>";
            }
            ?>
        </select></label>
        
        <label>Description<br />
		<textarea name="description"><?=$page['description']?></textarea></label>
        
        <label for="keywords">Keywords (for search optimisation, comma seperated)<br />
		<input type="text" value="<?=$page['keywords']?>" id="keywords" name="keywords" /></label>
		
		<label for="publish_date">Publication Date<br />
		<input type="text" class='datepicker' value="<?php echo date('d / m / Y H:i', strtotime($page['publish_date'])); ?>" id="publish_date" name="publish_date" /></label>
		
		<label for="expiry_date">Expiry Date<br />
		<input type="text" class='datepicker' value="<?php echo date('d / m / Y H:i', strtotime($page['expiry_date'])); ?>" id="expiry_date" name="expiry_date" /></label>
        
        <label>Sub-navigation Title<br />
    	<input type="text" value="<?=$page['navigation_title']?>" name="navigation_title" /></label>
    
        <label><input type="checkbox" value="1" <?php echo ($page['show_navigation']==1)?"checked":""; ?> name="show_navigation" /> Show Navigation?</label>
        
        <label><input type="checkbox" value="1" <?php echo ($page['published']==1)?"checked":""; ?> name="published" /> Publish?</label>
		
		<input type="hidden" name="function" value="update_page_settings" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
		<input type="hidden" name="page_id" value="<?php echo $page['id']; ?>" />
        <input type="submit" value="Update" />
	</form>
    <script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datetimepicker({ dateFormat: "dd / mm / yy" });
	});
	</script>
	<?php 
}