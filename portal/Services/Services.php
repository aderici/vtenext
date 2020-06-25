<?php
/* crmv@173271 */


class ServicesModule extends PortalModule {

	public $list_function = 'get_service_list_values';
	
	protected function processListResult($result) {
		return getblock_fieldlistview_product($result,$this->moduleName);
	}
}
 
