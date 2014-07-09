<?php
if ($is_admin) {
	$modules = $m->getModules();
	$modules_array = $site->siteMap($modules, BASE, 'array');	
	
	foreach ($modules_array as $path) {
		$mod = explode("/", $path);
		$module = end($mod);
		?>
        <div class='admin_module'>
		<form action='' method='POST'>
			
            <p><strong><?=ucwords(str_replace("_", " ", $module));?></strong></p>
			
			
			<input type='hidden' value='<?=$_SESSION['token']?>' name='token' />
			<input type='hidden' value='update_module' name='function' />
            <input type='hidden' value='<?=$path?>' name='path' />
			
			<input type='submit' value='Update Module' />
			
		</form>
        </div>
    	<?php
	}
	
	?>
    <p><a href="javascript:void(0)" onclick="modules.installAll()">Install all modules</a></p>
    <?php
} else {
	?>
	<p style='color:red'><em>Access denied</em></p>
    <?php
}