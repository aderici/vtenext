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

<span class="small">{'LBL_USE_SAVED_MAPPING'|@getTranslatedString:$MODULE}</span>&nbsp;&nbsp;
<select name="saved_maps" id="saved_maps" class="detailedViewTextBox input-inline" onchange="ImportJs.loadSavedMap();">
	<option value="-1" selected>--{'LBL_SELECT'|@getTranslatedString:$MODULE}--</option> {* crmv@136181 *}
	{foreach key=_MAP_ID item=_MAP from=$SAVED_MAPS}
	<option value="{$_MAP_ID}">{$_MAP->getValue('name')}</option> {* crmv@136181 *}
	{/foreach}
</select>
<span id="delete_map_container" style="display:none;">
	<img valign="absmiddle" src="{'delete.gif'|resourcever}" style="cursor:pointer;"
		 onclick="ImportJs.deleteMap('{$FOR_MODULE}');" alt="{'LBL_DELETE'|@getTranslatedString:$FOR_MODULE}" />
</span>
{* crmv@136181 *}
<script>
	var savedMappings = {ldelim}{rdelim};
	{foreach key=_MAP_ID item=_MAP from=$SAVED_MAPS}
		savedMappings[{$_MAP_ID}] = {$_MAP->getJsData()};
	{/foreach}
</script>
{* crmv@136181e *}
