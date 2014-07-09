<?php
if ($is_module || $mod || 
	($page && 										// check if page exists
	(($page['published']==1 && 						// check if published and within pub/exp dates
	  strtotime($page['publish_date'])<time() &&
	  strtotime($page['expiry_date'])>time()) || 
	$is_admin))										// or if user is admin
	) {

	$template = (empty($_page['template'])) ? "blank" : $_page['template'];
	$template = (isset($_mod['template']) && !empty($_mod['template'])) ? $_mod['template'] : $template;

	$module_output = "";
	
	if ($is_module) {
		/**
		 * First get Module - this may have template data in it.
		 */
		//$mod = $m->getModuleByPath($path);
		if (isset($mod) && !empty($mod['title'])) $page = $mod;
		ob_start();
		if (isset($_SESSION['lang_code']) && is_file($_SERVER['DOCUMENT_ROOT'].BASE."/modules/locale/".$_SESSION['lang_code']."/".$path.".php")) {
			require_once resolve("/modules/locale/".$_SESSION['lang_code']."/".$path.'.php');
		} else {
			require_once resolve('/modules/'.$path.'.php');
		}
		$module_output = ob_get_clean();
		$module_output = $p->addWidgets($module_output);
		/**
		 * if using default template and a template matching the module name exists then use that instead
		 */
		ob_start();
		if ($template=='blank' && !isset($overwrite_template) && is_file(resolve('/templates/page_types/'.$module.'.php'))) {
			require_once resolve('/templates/page_types/'.$module.'.php');
		} else {						
			require_once resolve('/templates/page_types/'.$template.'.php');
		}
		$template_output = ob_get_clean();

		/**
		 * if down admin path then show admin template
		 */
		if (strpos($path, "admin/")!==false) {
			ob_start();
			require_once resolve('/templates/page_types/admin.php');
			$template_output = ob_get_clean();
			ob_clean();
		}

	} else {
		ob_start();
		require_once resolve('/templates/page_types/'.$template.'.php');
		$template_output = ob_get_clean();
		
		$module_output = "";
	}
	$template_output = preg_replace('/{(module)}/i', $module_output, $template_output);
	
	$extra_output = (isset($extra_output)) ? $extra_output : "";
	$template_output = preg_replace('/{(extra)}/i', $extra_output, $template_output);
	
	/**
	 * Get section navigation
	 */	
	if (!MOBILE && empty($navigation_output)) {
		$p = new Page();
		$navigation_output = $_nav->getNavigation($_page);
	} 
	if (!isset($navigation_output)) $navigation_output = "";
	$template_output = preg_replace('/{(navigation)}/i', $navigation_output, $template_output);	
	
	echo $template_output;
	
} else {
	// so this is a 404 page - check to see if it should redirect
	$site->checkRedirect($path);
	
	header("HTTP/1.0 404 Not Found");
	require_once resolve('/templates/404.php');
}
