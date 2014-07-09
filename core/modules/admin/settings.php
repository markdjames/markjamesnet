<?php
if ($is_admin) {
	$db->type='site';
	$settings_raw = $db->select("SELECT * FROM settings");
	foreach ($settings_raw as $value) $settings[$value['name']] = $value['value'];
	?>
    <form id='site_settings' action='' method='POST'>
    	
    	<label>Site Name<br />
        <input type='text' value='<?=$settings['site-name']?>' name='site-name' /></label>
        
        <?php
		$path = $_SERVER['DOCUMENT_ROOT'].BASE."/themes";
		$results = scandir($path);
		
		foreach ($results as $result) {
			if ($result === '.' or $result === '..') continue;
			if (is_dir($path . '/' . $result)) {
				$themes[] = $result;
			}
		}
		?>
        <label>Theme<br />
        <select name='theme'>
        	<?php
			foreach ($themes as $theme) {
				?>
                <option value='<?=$theme?>'<?=($settings['theme']==$theme)?' selected':''?>><?=ucwords(str_replace("_", " ", $theme))?></option>
                <?php
			}
			?>
        </select></label>
       	
        <label><input type='checkbox' value='1' name='facebook' <?=($settings['facebook'])?"checked ":"";?>/> Enable Facebook plugins?</label>
        
        <label><input type='checkbox' value='1' name='twitter' <?=($settings['twitter'])?"checked ":"";?>/> Enable Twitter plugins?</label>
        
        <?php
        $languages_array = json_decode($settings['languages'], true);
		if (is_array($languages_array)) $languages = implode(", ", $languages_array);
		?>
        <label>Languages<br />
        <textarea name='languages'><?=$languages?></textarea></label>
        
        <input type='hidden' value='<?=$_SESSION['token']?>' name='token' />
        <input type='hidden' value='update_site_settings' name='function' />
        
        <input type='submit' value='Update Settings' />
        
    </form>
    <?php
} else {
	?>
	<p style='color:red'><em>Access denied</em></p>
    <?php
}