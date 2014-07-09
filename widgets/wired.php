<?php
switch ($frag_query) {
	case 'webmonkey':
		$feed = file_get_contents('http://www.webmonkey.com/feed/');
		$title = 'WebMonkey';
		break;
	case 'design':
		$feed = file_get_contents('http://www.wired.com/design/feed/');
		$title = 'Design';
		break;
	case 'underwire':
		$feed = file_get_contents('http://www.wired.com/underwire/feed/');
		$title = 'Underwire';
		break;
	case 'innovation':
		$feed = file_get_contents('http://www.wired.com/insights/feed/');
		$title = 'Innovation';
		break;
	case 'danger':
		$feed = file_get_contents('http://www.wired.com/dangerroom/feed/');
		$title = 'Danger';
		break;
	case 'games':
		$feed = file_get_contents('http://www.wired.com/gamelife/feed/');
		$title = 'Games';
		break;
	case 'gadgets':
		$feed = file_get_contents('http://www.wired.com/gadgetlab/feed/');
		$title = 'Gadgets';
		break;
	case 'how-to':
		$feed = file_get_contents('http://feeds.wired.com/howtowiki');
		$title = 'How-To';
		break;
	case 'reviews':
		$feed = file_get_contents('http://www.wired.com/reviews/feed/');
		$title = 'Reviews';
		break;
	default:
		$feed = file_get_contents('http://feeds.wired.com/wired/index');
		$title = 'Top Stories';
		break;
}
?>
<h2>Wired <?php echo $title; ?></h2>
<?php

$xml = new SimpleXMLElement($feed);
$links = array();

$i=0;
foreach ($xml->channel->item as $item) {
	ob_start();
	?>
	<div class='item'>
		<p><a href='<?php echo $item->link; ?>' target="_blank"><?php echo $item->title; ?></a></p>
		<?php echo $item->description; ?>
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