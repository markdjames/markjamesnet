<style>
.item.reddit img {
	float:left;
	margin-right:10px;
}
</style>
<h2>Reddit</h2>
<?php

$feed = file_get_contents('http://www.reddit.com/.rss');
$xml = new SimpleXMLElement($feed);
$links = array();

$i=0;
foreach ($xml->channel->item as $item) {
	ob_start();
	?>
	<div class='item reddit'>
		<?php echo strip_tags(str_replace('[link]', '', $item->description), '<img><a>'); ?>
		<p><a href='<?php echo $item->link; ?>' target="_blank"><?php echo $item->title; ?></a></p>
		<div style='clear:both'></div>
	</div>
	<?php
	$links[] = ob_get_clean();
	$i++;
	if ($i==50) break;
}

$paginator = new Paginator();
$paginator->per_page = 5;
echo $paginator->createPagination($links);