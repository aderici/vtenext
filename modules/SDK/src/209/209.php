<?php
global $sdk_mode,$table_prefix;
switch($sdk_mode) {
	case 'detail':
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
		break;
	case 'edit':
		$editview_label[] = getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
		break;
	case 'relatedlist':
	case 'list':
		$value = '<a href="'.$sdk_value.'" target="_blank">'.$sdk_value.'</a>';
		break;
}