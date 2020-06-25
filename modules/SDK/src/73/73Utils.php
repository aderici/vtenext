<?php
/* crmv@128159 */
class UitypeTimeUtils extends SDKExtendableUniqueClass {
	
	var $format = 'H:i';
	
	function time2Seconds($value) {
		if (empty($value))
			return 0;
		elseif (is_numeric($value))
			return $value;
		else
			return strtotime("1970-01-01 $value UTC");
	}
	
	function seconds2Time($value) {
		if (!empty($value) && is_numeric($value))
			return gmdate($this->format, $value);
		else
			return '';
	}
}