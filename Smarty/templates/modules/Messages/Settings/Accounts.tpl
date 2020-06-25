{***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 ***************************************************************************************}
 
{* crmv@3082m crmv@114260 *}

<table class="vtetable" id="account_list">
	<thead>
		<tr>
			<th width="120">{'LBL_ACTIONS'|getTranslatedString}</th>
			<th width="15%">Account</th>
			<th>{'LBL_USERNAME'|getTranslatedString:'Settings'}</th>
			<th>{'LBL_DESCRIPTION'|getTranslatedString}</th>
			<th width="80">{'LBL_MAIN'|getTranslatedString:'Messages'}</th>
			<th width="80">{'LBL_SMTP_SERVER'|getTranslatedString:'Messages'}</th>
		</tr>
	</thead>
	<tbody>
		{foreach item=ACCOUNT from=$ACCOUNTS}
			{assign var=KEY value=$ACCOUNT.id}
			{include file='modules/Messages/Settings/Account.tpl' ACCOUNT=$ACCOUNT}
		{/foreach}
	</tbody>
</table>

<script type="text/javascript">
{if empty($ACCOUNTS)}
	addAccount();
{/if}
{literal}
function addAccount() {
	location.href='index.php?module=Messages&action=MessagesAjax&file=Settings/index&operation=EditAccount&id=';
}
{/literal}
</script>
