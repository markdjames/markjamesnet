<?php
require_once '../../../bootstrap.php';

$pages = $m->getModules();

// get all pages from the database and loop through, adding each to the array of pages
$pages_db = $db->select("SELECT * FROM pages WHERE archived=0");
foreach ($pages_db as $page) {
	$tmp_pages = buildArrayFromPath(explode("/", $page['path']));
	$pages = array_merge_recursive($pages, $tmp_pages);
}

ksort($pages);

$options = $site->siteMap($pages, BASE, 'select');

$page = $p->getPage($_POST['id']);
$related = (!empty($page['related'])) ? json_decode($page['related'], true) : array();
?>
<h3>Related Pages</h3>
<form id="page_related" method="POST" action="" enctype="multipart/form-data">
	<?php
	$i=1;
	if (count($related)) {
		foreach ($related as $rel) {
			if (isset($rel) && !empty($rel)) {
				?>
                <div>
				<label>Related Page <?=$i?> <em>(manual entry)</em><br />
				<input type="text" value="<?=$rel?>" name="related[]" id="related_<?=$i?>_manual" onchange="$(this).val($(this).val().replace('http://', '').replace('www.', '').replace('<?=DOMAIN?>', '').replace('<?=BASE?>', '')); ($(this).val()=='')?$('#related_<?=$i?>_auto').prop('disabled', false):$('#related_1_auto').prop('disabled', true);" /></label>
				
				<label>Related Page <?=$i?><br />
				<select name="related[]" id='related_<?=$i?>_auto' onchange="($(this).val()=='')?$('#related_<?=$i?>_manual').prop('disabled', false):$('#related_<?=$i?>_manual').prop('disabled', true);">
					<option value=''>--select--</option>
					<?=$options?>
				</select></label>
				
				<script>
				$("#related_<?=$i?>_auto option").each(function(){this.selected=(this.text == '<?=$rel?>');});
				</script>
                <p style='float:right'><a href='javascript:void(0)' onclick='$(this).parent().parent().remove()'>delete</a></p>
                <div style='clear:both'></div>
                </div>
				<?php
				$i++;
			}
		}
	}
	?>
    <div id='related_page_form'>
    <label>Related Page <em>(manual entry)</em><br />
    <input type="text" value="" name="related[]" id="related_<?=$i?>_manual" onchange="$(this).val($(this).val().replace('http://', '').replace('www.', '').replace('<?=DOMAIN?>', '').replace('<?=BASE?>', '')); ($(this).val()=='')?$('#related_<?=$i?>_auto').prop('disabled', false):$('#related_1_auto').prop('disabled', true);" /></label>
    
    <label>Related Page<br />
    <select name="related[]" id='related_<?=$i?>_auto' onchange="($(this).val()=='')?$('#related_<?=$i?>_manual').prop('disabled', false):$('#related_<?=$i?>_manual').prop('disabled', true);">
        <option value=''>--select--</option>
        <?=$options?>
    </select></label>
    
    <script>
    $("#related_<?=$i?>_auto option").each(function(){this.selected=(this.text == '');});
    </script>
    </div>
	<div id='more_related_pages'>
    </div>
    <p><a href='javascript:void(0)' onclick="addRelatedForm()">Add another...</a></p>
    		
	<input type="hidden" name="function" value="update_page_related" />
	<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
	<input type="hidden" name="page_id" value="<?=$page['id']?>" />
	<input type="hidden" name="page_type" value="<?=$_POST['type']?>" />
	<input type="submit" value="Update" />
</form>

<script>
var current_related = <?=$i?>;
function addRelatedForm() {
	var re = new RegExp('_'+current_related+'_',"gi");
	$('#more_related_pages').append($('#related_page_form').html().replace(re, current_related+1));
	current_related++;
}
</script>
<?php 
