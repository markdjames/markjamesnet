<?php
$feed = file_get_contents('http://feeds.feedburner.com/readwriteweb');
?>
<h2>Read / Write / Web</h2>
<?php

$xml = new SimpleXMLElement($feed);
$links = array();

$i=0;
foreach ($xml->channel->item as $item) {
	ob_start();
	?>
	<div class='item'>
		<?php //echo $item->description; ?>
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