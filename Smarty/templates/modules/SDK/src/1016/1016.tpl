{* /*+*************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************/ *}

{* crmv@104567 *}
{* crmv@190827 *}

{literal}
<style>
	.signature-img-wrapper {
		background-color: #ffffff;
	}
	.signature-img {
		height: 250px;
	}
</style>
{/literal}
 
{if $sdk_mode eq 'detail'}
	<table width="100%">
		<tr>
			{if empty($keyval)}
				<td>{'NO_SIGNATURE_IMAGE'|getTranslatedString:'HelpDesk'}</td>
			{else}
				{assign var=now value=$smarty.now}
				<td align="center" valign="center" class="signature-img-wrapper">
					<img class="img-responsive signature-img" src="{$keyval}?t={$now}" id="{$keyfldname}" />
				</td>
			{/if}
		</tr>
	</table>
{elseif $sdk_mode eq 'edit'}
	<table width="100%">
		<tr>
			{if empty($keyval)}
				<td>{'NO_SIGNATURE_IMAGE'|getTranslatedString:'HelpDesk'}</td>
			{else}
				{assign var=now value=$smarty.now}
				<td align="center" valign="center" class="signature-img-wrapper">
					<img class="img-responsive signature-img" src="{$fldvalue}?t={$now}" id="{$fldname}" />
				</td>
			{/if}
		</tr>
	</table>
	<input type="hidden" name="{$fldname}" value="{$fldvalue}" />
{/if}
