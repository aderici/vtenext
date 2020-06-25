<?php
/* crmv@188277 */

class TouchGetProcessesCount extends TouchWSClass {

	function process(&$request) {
		global $adb, $table_prefix, $touchInst;

		if (in_array('Processes', $touchInst->excluded_modules)) return $this->error('Module not permitted');

		// very stupid!
		ob_start();
		include('modules/SDK/src/Notifications/plugins/ProcessesCheckChanges.php');
		$result = ob_get_clean();
		ob_end_clean();

		return $this->success(array('total'=>$result));
	}
}
