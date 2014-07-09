<?php
// default page layout for dynamic page
?>
<div id='main_column'>
    {module}
    {audio}
    {video}
    {gallery}
    <div class='content'>
        <?php
        if (!empty($page['title'])) echo $o->outputTitle($page);
		echo $p->addWidgets($page['content']);
		?>
    </div>
    {aggregation}
</div>
<aside>
	{navigation}
    <?php
    if (!$page['hide_photo']) echo $image->outputImage($page['image'], $page['alt'], 300, 226);
    ?>
</aside>


