<?php
switch ($frag_query) {
	case 'world':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/world/rss.xml');
		$title = 'World';
		break;
	case 'uk':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/uk/rss.xml');
		$title = 'UK';
		break;
	case 'business':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/business/rss.xml');
		$title = 'Business';
		break;
	case 'politics':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/politics/rss.xml');
		$title = 'Politics';
		break;
	case 'health':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/health/rss.xml');
		$title = 'Health';
		break;
	case 'education':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/education/rss.xml');
		$title = 'Education';
		break;
	case 'science':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/science_and_environment/rss.xml');
		$title = 'Science';
		break;
	case 'technology':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/technology/rss.xml');
		$title = 'Technology';
		break;
	case 'entertainment':
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml');
		$title = 'Entertainment';
		break;
	default:
		$feed = file_get_contents('http://feeds.bbci.co.uk/news/rss.xml');
		$title = 'Top Stories';
		break;
}
?>
<h2>BBC <?php echo $title; ?></h2>
<?php

$xml = new SimpleXMLElement($feed);
$links = array();

$i=0;
foreach ($xml->channel->item as $item) {
	ob_start();
	?>
	<div class='item'>
		<p><a href='<?php echo $item->link; ?>' target="_blank"><?php echo $item->title; ?></a></p>
		<?php echo strip_tags(str_replace('[link]', '', $item->description), '<img><a>'); ?>
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