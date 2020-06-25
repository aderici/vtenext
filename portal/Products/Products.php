<?php
/* crmv@173271 */


class ProductsModule extends PortalModule {

	public $list_function = 'get_product_list_values';
	
	protected function processListResult($result) {
		return getblock_fieldlistview_product($result,$this->moduleName);
	}
}
 
