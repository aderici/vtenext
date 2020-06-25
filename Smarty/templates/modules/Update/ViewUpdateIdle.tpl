{*/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/*}
 
{* crmv@199352 *}

<script type="text/javascript" src="{"modules/Update/Update.js"|resourcever}"></script>

{include file='Buttons_List1.tpl'}

<div class="container-fluid text-center">
	<br>
	<h3>{$TITLE}</h3>
	<br>
	
	<h4>{'LBL_LAST_CHECK'|getTranslatedString:"Update"}<span title="{$LAST_CHECK}">{$LAST_CHECK_TEXT}</span></h4>
	<br><br>
	
	<div style="width:300px;margin:auto">
		<button type="button" class="btn btn-info btn-block btn-raised btn-round primary" onclick="VTE.Update.forceCheck()">{$MOD.LBL_CHECK_NOW} <i class="vteicon md-sm" style="color:white">refresh</i></button><br>
	</div>
</div>
