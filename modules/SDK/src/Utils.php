<?php

if (!function_exists('getModuleList201')) {
	function getModuleList201() {
		global $adb,$table_prefix;
		$query = "select name from ".$table_prefix."_tab where presence = 0 and name not in ('Emails','Events','Fax') and (isentitytype = 1 or name in ('Home','Dashboard','Rss','Reports','RecycleBin')) order by name";
		return $adb->query($query);
	}
}
