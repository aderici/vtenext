{*********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * All Rights Reserved.
 *********************************************************************************}
 
{include file="SmallHeader.tpl" HEADER_Z_INDEX="1" PAGE_TITLE=$MOD.LBL_AUDIT_TRAIL BODY_EXTRA_CLASS="popup-audit-trails"}
{include file='CachedValues.tpl'}

<form action="index.php" method="post" id="form" onsubmit="VtigerJS_DialogBox.block();">
	<input type='hidden' name='module' value='Settings'>
	<input type='hidden' id='userid' name='userid' value='{$USERID}'>
    <input type="hidden" name="__csrf_token" value="{$CSRF_TOKEN}"> {* crmv@171581 *}
	<div id="AuditTrailContents">
		{include file="ShowAuditTrailContents.tpl"}
	</div>
</form>

<script type="text/javascript" src="{"modules/Settings/resources/AuditTrail.js"|resourcever}"></script>

{literal}
<script type="text/javascript">
	function getListViewEntries_js(module, url) {
		VTE.Settings.AuditTrail.getListViewEntries_js(module, url);
	}
</script>
{/literal}

</body>
</html>
