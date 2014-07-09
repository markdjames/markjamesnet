<div class='content'>
	<?php
    if (!empty($page['title'])) echo $o->outputTitle($page);
    echo $p->addWidgets($page['content']);
    ?>
    {module}
    {audio}
    {video}
    {gallery}
    {aggregation}
    <div style='clear:both'></div>
</div> 

