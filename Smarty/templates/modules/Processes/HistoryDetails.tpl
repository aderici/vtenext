{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@188364 *}
{if !empty($INFO[$line.elementid])}
	<a style="text-decoration:none;" href="javascript:void(0);" onclick="ModNotificationsCommon.toggleChangeLog('{$line.id}');">
		<i class="vteicon" id="img_{$line.id}">keyboard_arrow_down</i><span style="position: relative; bottom: 7px;">{'LBL_DETAILS'|@getTranslatedString:'ModNotifications'}</span>
	</a>
	<div id="div_{$line.id}" style="display:block;">
		<table class="table">
			{foreach key=k item=v from=$INFO[$line.elementid]}
				<tr>
					<td with="100%">
						{include file="modules/Processes/HistoryDetail.tpl" type=$k info=$v elementid=$line.elementid}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
{else}
	<div style="height:28px">&nbsp;</div>
{/if}