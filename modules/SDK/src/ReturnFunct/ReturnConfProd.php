<?php
/* crmv@198024 */

global $default_charset,$adb,$table_prefix,$autocomplete_return_function;

$forfield = htmlspecialchars($_REQUEST['forfield'], ENT_QUOTES, $default_charset);
$list_result_count = $i-1;

$value = $this->getValue($ui_col_array,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"search",$focus->popup_type);
