<input type="text" id="search" name="q" value="" placeholder="Search..." onfocus="this.select();" />
<div id="search_tags"></div>

<div style="clear:both; height:25px;"></div>
<div class='content'>
    <div id='search_results'>
        
    </div>
</div>
<script type="text/javascript"><?php 
if (!empty($_GET['q'])) {
	if (!isset($_GET['cat'])) $_GET['cat']='all';
	?>search.doSearch("<?=addslashes(htmlentities(urldecode($_GET['q'])))?>", "<?=addslashes(htmlentities(urldecode($_GET['cat'])))?>");<?php
}
?></script>
<?php unset($_GET); ?>