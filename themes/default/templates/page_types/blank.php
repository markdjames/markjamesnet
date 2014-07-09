<section>
    <div class='content'>
	    {custom_html}

        <?php
        if (!$page['hide_photo']) echo $image->outputImage($page['image'], $page['alt'], 400, 300, "width:100%");
        //if (!empty($page['title'])) echo $o->outputTitle($page);
        echo $p->addWidgets($page['content']);
		?>
		
        {module}
        {audio}
        {video}
        {gallery}
        {aggregation}
	    <div style='clear:both'></div>
    </div>
</section>
