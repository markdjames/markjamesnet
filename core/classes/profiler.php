<?php
class Profiler {

	public function newProfile($sql, $vars=NULL) {

		$profiledb = new Database(false);
		$sql = preg_replace( '/\s+/', ' ', $sql);
		
		// check if profile exists
		$profiledb->type = "site";
		$profiledb->vars['sql'] = $sql;
		$check = $profiledb->select("SELECT * FROM profiling WHERE `sql`=:sql");

		if (!count($check)) {
			$values['sql'] = $profiledb->sqlify($sql);
			
			if (!empty($vars)) {
				$values['vars'] = $profiledb->sqlify(json_encode($vars));
			}
			$profiledb->insert("profiling", $values);
			$profiledb->doCommit();
		} 
	}
}
?>