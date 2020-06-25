{*/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/*}

{* crmv@183486 *}

<script type="text/javascript" src="{"modules/Update/Update.js"|resourcever}"></script>

{include file='Buttons_List1.tpl'}

<div class="container-fluid text-center">
	<br>
	<h3>{"LBL_CANCEL_UPDATE_TITLE"|getTranslatedString:'Update'}</h3>
	<br><br>
	<h4>{$CANCEL_TEXT}</h4>
	<br>
	<h3>{"LBL_CANCEL_UPDATE_ASK"|getTranslatedString:'Update'}</h3>
	<h5>{"LBL_CANCEL_UPDATE_INFO"|getTranslatedString:'Update'}</h5>
	<br>
	
	<div style="width:500px;margin:auto">
		<div class="row">
			<div class="col-xs-6">
				<button type="button" class="btn btn-info btn-block btn-raised btn-round primary" onclick="location.href='index.php'">{$APP.LBL_NO}</button><br>
			</div>
			<div class="col-xs-6">
				<button type="button" class="btn delete btn-block btn-raised btn-round" onclick="VTE.Update.cancelUpdate(true)">{$APP.LBL_YES} </button>
			</div>
		</div>
	</div>
	
</div>
