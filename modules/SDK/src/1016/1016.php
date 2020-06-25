<?php
/* +*************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 * ************************************************************************************* */

// crmv@104567

global $sdk_mode, $table_prefix;

switch ($sdk_mode) {
	case 'detail':
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$signaturePath = $col_fields[$fieldname];
		if (!empty($signaturePath) && file_exists($signaturePath)) {
			$value = $signaturePath;
		} else {
			$value = null;
		}
		$label_fld[] = $value;
		break;
	case 'edit':
		$editview_label[] = getTranslatedString($fieldlabel, $module_name);
		$signaturePath = $col_fields[$fieldname];
		if (!empty($signaturePath) && file_exists($signaturePath)) {
			$value = $signaturePath;
		} else {
			$value = null;
		}
		$fieldvalue[] = $value;
		break;
	case 'relatedlist':
	case 'list':
		$value = $sdk_value;
		break;
	case 'pdfmaker':
		if (!empty($value) && file_exists($value)) {
			$value = '<img src="' . $value . '" style="height:2.5cm;" />';
		} else {
			$value = '';
		}
		break;
}
