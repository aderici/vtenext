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

<table width="100%" border="0">
	<tr>
		<td align="left">
			{if $LIST_ENTRIES neq ''}
				{$RECORD_COUNTS}
			{/if}
		</td>
		{$NAVIGATION}
	</tr>
</table>

<table class="vtetable">
	<thead>
		<tr>
			{foreach item=header from=$LIST_HEADER}
				<th>{$header}</th>
			{/foreach}
		</tr>
	</thead>
	<tbody>
		{foreach item=entity key=entity_id from=$LIST_ENTRIES}
			<tr>
				{foreach item=data from=$entity}
					{if $data neq "0000-00-00 00:00:00"}
						<td>{$data}</td>
					{else}
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---</td>
					{/if}
				{/foreach}
			</tr>
		{foreachelse}
			<tr>
				<td colspan="{$LIST_HEADER|@count}" height="300px" align="center" class="genHeaderSmall">{$MOD.LBL_NO_DATA}</td>
			</tr>
		{/foreach}
	</tbody>
</table>
