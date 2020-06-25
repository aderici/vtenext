{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@126184 *}

{assign var="STATICRECORD" value="1"}
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>{include file="Settings/ProcessMaker/actions/RelateRecord.tpl" RECORDPICK=$RECORDPICK1 ENTITY="1"}</tr>
	<tr id="record2_container">
	{if !empty($RECORDPICK2)}
		{include file="Settings/ProcessMaker/actions/RelateRecord.tpl" RECORDPICK=$RECORDPICK2 ENTITY="2"}
	{/if}
	</tr>
	<tr>
		<td></td>
		<td id="record3_container">
			{if $SELRECORDS}
			{include file="Settings/ProcessMaker/actions/RelatedRecordList.tpl"}
			{/if}
		</td>
		<td></td>
	</tr>
</table>
<br>
<select id='task-fieldnames' class="notdropdown" style="display:none;">
	<option value="">{'LBL_SELECT_OPTION_DOTDOTDOT'|getTranslatedString:'com_vtiger_workflow'}</option>
</select>