{***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@125629 *}
{if $smarty.request.file eq 'ListView'}&#&#&#&#&#&#{/if}
<!-- error page message (do not delete this comment!!!) -->
<table border="0" cellpadding="10" cellspacing="0" class="small" align="center" {* style="border: 1px solid rgb(204, 204, 204);"*}>
	<tr valign="top">
		<td width="75%">
			<span {if !empty($DESCR)}class="genHeaderSmall"{/if}>{$TITLE}</span>
		</td>
	</tr>
	{if !empty($DESCR)}
		<tr>
			<td>{$DESCR}</td>
		</tr>
	{/if}
</table>