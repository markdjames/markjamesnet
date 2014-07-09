<?php
require_once '../../../bootstrap.php';

$pagetypes = $p->getPageTypes();
$path_array = explode("/", $_POST['id']);
?>
<h3>Create Page</h3>
<form id="page_settings" method="POST" action="" enctype="multipart/form-data">
	<label for="title">Page Title<br />
	<input type="text" value="<?php echo clever_ucwords(str_replace("_", " ", end($path_array))); ?>" id="title" name="title" /></label>
    
    <label>Page Type<br />
    <select name="template">
    	<?php
		foreach ($pagetypes as $type) {
			echo "<option value='".$type."'";
			echo ($type=='default') ? " selected":"";
			echo ">".ucwords(str_replace("_", " ", $type))."</option>";
		}
		?>
    </select></label>
    
    <label>Description<br />
	<textarea name="description"></textarea></label>
    
	<label for="keywords">Keywords (for search optimisation, comma seperated)<br />
	<input type="text" value="" id="keywords" name="keywords" /></label>
    
	<label for="publish_date">Publication Date<br />
	<input type="text" class='datepicker' value="<?php echo date('d / m / Y H:i'); ?>" id="publish_date" name="publish_date" /></label>
	
	<label for="expiry_date">Expiry Date<br />
	<input type="text" class='datepicker' value="<?php echo date('d / m / Y H:i', strtotime('+10 years')); ?>" id="expiry_date" name="expiry_date" /></label>
    
    <label>Sub-navigation Title<br />
    <input type="text" value="" name="navigation_title" /></label>
    
    <label><input type="checkbox" value="1" name="show_navigation" checked /> Show Navigation?</label>
    <label><input type="checkbox" value="1" name="published" checked /> Publish?</label>
	
	<input type="hidden" name="function" value="create_page" />
	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
	<input type="hidden" name="path" value="<?php echo $_POST['id']; ?>" />
	<input type="submit" value="Create" />
</form>
<script type="text/javascript">
$(function() {
	$( ".datepicker" ).datetimepicker({ 
		dateFormat: "dd / mm / yy",
		timeFormat: "hh:mm tt" 
	});
});
</script>