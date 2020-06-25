{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
*}

<button type="submit" name="next" class="crmbutton edit" onclick="return ImportJs.uploadAndParse();">
	{'LBL_NEXT_BUTTON_LABEL'|@getTranslatedString:$MODULE}
</button>

<button type="button" name="cancel" class="crmbutton cancel" onclick="location.href='index.php?module={$FOR_MODULE}&action=index'">
	{'LBL_CANCEL_BUTTON_LABEL'|@getTranslatedString:$MODULE}
</button>
