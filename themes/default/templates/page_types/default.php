<section>
	<div class='content'>
        {custom_html}
        <?php 
        $page = (empty($page)) ? $mod : $page ;     
        if (!empty($page['content'])) {
			//if (!empty($page['title'])) echo $o->outputTitle($page);
            echo $p->addWidgets($page['content']);
            ?>
            <div style='clear:both'></div>
            <?php
        }
        ?>
        {audio}
        {module}
        {aggregation}
    </div> 
</section>
<div id='column'>
	<?php
    if (!$page['hide_photo']) echo $image->outputImage($page['image'], $page['alt'], 400, 300, "width:100%");
    ?>
    {gallery}
    {video}
</div>

