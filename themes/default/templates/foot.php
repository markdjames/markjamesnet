</div> <!--! end of #container -->
<div style='height:30px; clear:both'></div>
<footer>
    <ul>
        <li><a href="<?=DIR?>/">Home</a></li>
        <li><a href="<?=DIR?>/sitemap">Sitemap</a></li>
    </ul>
    <div style='clear:both;'></div>
</footer>

<!-- IMPORTANT Used for Modal content (see modal.js) -->
<div id='screener'></div>
<div id='modal_wrap'><div id='modal'><div id='modal_inner'></div></div></div>
<div id='sub_modal_wrap'><div id='sub_modal'><div id='sub_modal_inner'></div></div></div>

<?php if ($is_admin) { ?>
<script src="<?=BASE?>/vendor/ckeditor/ckeditor.js"></script>
<?php } ?>

<script>
	/*var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));*/
</script>

</body>
</html>
