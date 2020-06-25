<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/
 
/* crmv@151474 */

// TODO: move here all the stuff in SDK/LangUtils.php


/**
 * Class to handle language related operations
 */
class LanguageUtils extends SDKExtendableClass {

	protected $languageStack = array();
		
	/**
	 * Change the current language with the one provided and save the old one in a stack.
	 * Then you should call restoreCurrentLanguage to pop the old language from the stack
	 * and restore it
	 */
	public function changeCurrentLanguage($newLanguage) {
		global $app_strings, $mod_strings;
		global $currentModule, $current_language;
		
		array_push($this->languageStack, $newLanguage);
		if ($newLanguage != $current_language) {
			$current_language = $newLanguage;
			$app_strings = return_application_language($current_language);
			$mod_strings = return_module_language($current_language, $currentModule);
		}		
	}
	
	/**
	 * Restore a previously changed language
	 */
	public function restoreCurrentLanguage() {
		global $app_strings, $mod_strings;
		global $currentModule, $current_language;
		
		$oldLanguage = array_pop($this->languageStack);
		if ($oldLanguage && $oldLanguage != $current_language) {
			$current_language = $oldLanguage;
			$app_strings = return_application_language($current_language);
			$mod_strings = return_module_language($current_language, $currentModule);
		}
	}
	
}
