<?php
require_once '../../lib/bootstrap.php';

if (is_numeric($_POST['id'])) {
	$block = $b->getBlock($_POST['id'], 'aggregation', $_POST['type'], $_POST['content']);
	
	$page_types = $p->getPageTypes();
	?>
    <h4 style='clear:both'>Aggregation Block</h4>
    <label>Title<br />
    <input type="text" name="block[title]" value="<?=$block['title']; ?>" /></label>
    <label>Type<br />
    <select name='block[type]' onchange="if (this.value=='concerts') { $('#concert_settings').show(); $('#normal_settings').hide(); } else { $('#concert_settings').hide(); $('#normal_settings').show(); }">
    	<option value="">--select type--</option>
        <?php 
		foreach ($page_types as $type) {
			?>
            <option value="<?=$type;?>"<?=($type==$block['type']) ? " selected":""; 
			?>><?=ucwords(str_replace("_", " ", $type))?></option>
            <?php
		}
		?>
	</select></label>
    
    <div id="normal_settings" style='display:<?=($block['type']=='concerts')?"none":"block";?>'>
    <label>Scope <em>(where should the aggregator search for content?)</em><br />
    <select name='block[scope]'>
    	<option value="site"<?=($block['scope']=='site') ? " selected":"";?>>Entire site</option>
        <option value="path"<?=($block['scope']=='path') ? " selected":"";?>>Pages below this one</option>
	</select></label>
    
    <label>Order by<br />
    <select name='block[orderby]'>
    	<option value="">select order by column</option>
        <option value="id" <?=($block['orderby']=='id') ? " selected":""; ?>>Database ID</option>
        <option value="last_modified"<?=($block['orderby']=='last_modified') ? " selected":""; ?>>Last Modified</option>
        <option value="publish_date"<?=($block['orderby']=='publish_date') ? " selected":""; ?>>Date Published</option>
        <option value="title"<?=($block['orderby']=='title') ? " selected":""; ?>>Title</option>
        <option value="manual"<?=($block['orderby']=='manual') ? " selected":""; ?>>Manual</option>
	</select></label>
    
    <label>Order<br />
    <select name='block[order]'>
    	<option value="DESC"<?=($block['order']=='DESC') ? " selected":""; 
			?>>Descending</option>
        <option value="ASC"<?=($block['order']=='ASC') ? " selected":""; 
			?>>Ascending</option>
	</select></label>
    </div>
    <div id="concert_settings" style='display:<?=($block['type']=='concerts')?"block":"none";?>'>
    
    </div>
    
    <h4>Pagination Settings</h4>
    
    <label>Paginate results?<br />
    <select name='block[paginate]' onchange="(this.value=='1')?$('#pagination_options').fadeIn():$('#pagination_options').fadeOut();">
    	<option value='1'<?=($block['paginate']==1) ? " selected":""; ?>>Yes</option>
        <option value='0'<?=($block['paginate']==0) ? " selected":""; ?>>No</option>
    </select></label>
    
    <div id='pagination_options'<?=(empty($block['paginate'])) ? " style='display:none'":""; ?>>
    	<label>Results per page<br />
        <input type='text' value="<?=$block['per_page']; ?>" name="block[per_page]" /></label>
        
        <label>Pagination order (<em>will over-ride order settings above unless set as default</em>)<br />
        <select name='block[paginate_by]'>
            <option value="count">Default</option>
            <option value="alpha"<?=($block['paginate_by']=='alpha') ? " selected":""; ?>>Alphabetical</option>
            <option value="date"<?=($block['paginate_by']=='date') ? " selected":""; ?>>By Date</option>
        </select></label>
    </div>
    
    <input type="hidden" name="block_id" value="<?=$block['id']; ?>" />
    <input type="hidden" name="block_name" value="aggregation" />
    <input type="button" value="<?=(empty($block['id'])) ? "Add Block" : "Update Block"; ?>" onclick="checkForm('page_blocks')" />
     <?php 
	if (!empty($block['id'])) {
		?>
    	<input type="button" onclick="deleteRecord(<?=$block['id']; ?>, 'block_aggregation', {type:'blocks',category:'aggregation'})" value='Delete' />
		<?php
	}
}