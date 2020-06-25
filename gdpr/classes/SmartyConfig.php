<?php 
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@161554

namespace GDPR;

defined('BASEPATH') OR exit('No direct script access allowed');

class SmartyConfig extends \Smarty {
	
	public function __construct() {
		global $CFG, $GPDRManager, $translations;
		
		parent::__construct();
		
		$this->template_dir = BASEPATH.'templates';
		$this->compile_dir = BASEPATH.'cache/Smarty/templates_c';
		$this->cache_dir = BASEPATH.'cache/Smarty/cache';
		
		$this->assign('CURRENT_ACTION', $GPDRManager->getCurrentAction());
		$this->assign('WEBSITE_LOGO', $CFG->website_logo);
		$this->assign('TRANSLATIONS', \Zend_Json::encode($translations));
	}
	
}
