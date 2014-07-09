<?php
switch ($frag_query) {
	case 'gold':
		$feed = file_get_contents('http://www.buzzfeed.com/badge/gold-star.xml');
		$title = 'Gold Star';
		break;
	case 'tech':
		$feed = file_get_contents('http://www.buzzfeed.com/tech.xml');
		$title = 'Tech';
		break;
	case 'geeky':
		$feed = file_get_contents('http://www.buzzfeed.com/geeky.xml');
		$title = 'Geeky';
		break;
	case 'animals':
		$feed = file_get_contents('http://www.buzzfeed.com/animals.xml');
		$title = 'Animals';
		break;
	case 'nsfw':
		$feed = file_get_contents('http://www.buzzfeed.com/nsfw.xml');
		$title = 'NSFW';
		break;
	case 'raw':
		$feed = file_get_contents('http://www.buzzfeed.com/community/justlaunched.xml');
		$title = 'Raw Feed';
		break;
	case 'sfw':
		$feed = file_get_contents('http://www.buzzfeed.com/index-tame.xml');
		$title = 'Homepage (SFW)';
		break;
	default:
		$feed = file_get_contents('http://www.buzzfeed.com/index.xml');
		$title = 'Homepage';
		break;
}
?>
<h2>Buzzfeed <?php echo $title; ?></h2>
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