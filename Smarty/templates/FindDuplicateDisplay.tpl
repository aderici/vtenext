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

{* crmv@168103 *}
{* crmv@193710 *}

{include file='Buttons_List.tpl'}

<div id="duplicate_ajax">
	{include file='FindDuplicateAjax.tpl'}
</div>

<div id="current_action" style="display:none">{$smarty.request.action}</div>
