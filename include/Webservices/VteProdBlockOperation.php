<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
 
/* crmv@195745 */

/**
 * Class to simulate operations on the ProductsBlock fake module
 */
class VteProdBlockOperation extends WebserviceEntityOperation {
	protected $tabId;
	protected $isEntity = true;
	
	protected $fakeMods;

	public function __construct($webserviceObject,$user,$adb,$log){
		parent::__construct($webserviceObject,$user,$adb,$log);
		$this->fakeMods = new FakeModules();
	}

	public function describe($elementType){
		$app_strings = VTWS_PreserveGlobal::getGlobal('app_strings');
		$current_user = vtws_preserveGlobal('current_user',$this->user);;

		$label = $this->fakeMods->getModuleLabel('ProductsBlock');
		
		$createable = true;
		$updateable = false;
		$deleteable = true;
		$retrieveable = true;
		$fields = $this->getModuleFields();
		return array(
			"label" => $label,
			"name" => $elementType,
			"createable" => $createable,
			"updateable" => $updateable,
			"deleteable" => $deleteable,
			"retrieveable" => $retrieveable,
			"fields" => $fields,
			"idPrefix" => 0, //$this->meta->getEntityId(),
			'isEntity' => true,
			//'labelFields'=>$this->meta->getNameFields()
		);
	}

	function getModuleFields(){
		$fields = array();
		
		$moduleFields = $this->fakeMods->getFields('ProductsBlock');
		foreach ($moduleFields as $fieldName=>$moduleField) {
			/*if(!$this->meta->show_hidden_fields && ((int)$webserviceField->getPresence()) == 1) {	//crmv@120039
				continue;
			}*/
			if ($fieldName == 'id' || $fieldName == 'total_notaxes') continue; // skip id field
			array_push($fields,$this->getDescribeFieldArray($moduleField));
		}

		return $fields;
	}

	protected function getDescribeFieldArray($fieldArr){

		$type = array('name' => $fieldArr['wstype']);
		if ($type['name'] == 'reference') {
			$type['refersTo'] = $fieldArr['relmodules'];
		}
		$wsField = array(
			'name' => $fieldArr['fieldname'],
			'label' => $fieldArr['label'],
			'mandatory'=> ($fieldArr['fieldname'] == 'productid'),
			'type' => $type,
			'nullable'=>true, // TODO
			'editable'=>true, // TODO
			// added properties
			'fieldid'=>$fieldArr['fieldid'],
			'uitype'=>$fieldArr['uitype'],
			'blockid'=>$fieldArr['block'],
			'panelid'=>0,
			'sequence'=>$fieldArr['sequence'],
		);
		
		return $wsField;
	}

	function getMeta(){
		return null;
	}
	
	protected function getMetaInstance() {
		return null;
	}

	function getField($fieldName){
		$moduleField = $this->fakeMods->getFieldInfo($fieldName, 'ProductsBlock');
		return $this->getDescribeFieldArray($moduleField);
	}

}
