<?php
$galleryblock = new GalleryBlock();
$galleryblock->block = $block;

$galleryblock->main_width = 400;
$galleryblock->main_height = 300;
$galleryblock->sub_width = 60;
$galleryblock->sub_height = 45;

echo $galleryblock->display();
echo "<div style='clear:both'></div>";