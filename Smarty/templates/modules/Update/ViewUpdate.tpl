{*/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/*}
 
{* crmv@182073 *}
<script type="text/javascript" src="{"modules/Update/Update.js"|resourcever}"></script>

{include file='Buttons_List1.tpl'}

<div class="container-fluid text-center">
	<br>
	<h3>{$TITLE}</h3>
	<br><br>
	<div style="width:300px;margin:auto">
		<button type="button" class="btn btn-info btn-block btn-raised btn-round primary" onclick="VTE.Update.scheduleUpdate()">{$MOD.LBL_SCHEDULE_UPDATE} <i class="vteicon md-sm" style="color:white">update</i></button><br>
		<div class="dropdown">
			<button type="button" class="btn btn-info btn-block btn-raised btn-round primary dropdown-toggle" data-toggle="dropdown">{$MOD.LBL_REMIND} <span class="caret"></span></button>
			<ul class="dropdown-menu">
				{foreach key="VALUE" item="LABEL" from=$REMINDER_OPTIONS}
				<li><a href="javascript:void(0);" onclick="VTE.Update.remindUpdate('{$VALUE}', true)">{$LABEL}</a></li>
				{/foreach}
			</ul>
		</div>
		<br>
		{* ignore button is disabled!! *}
		{* <button type="button" class="btn delete btn-block btn-raised btn-round" onclick="VTE.Update.ignoreUpdate(true)">{$MOD.LBL_IGNORE_UPDATE} <i class="vteicon md-sm" style="color:white">cancel</i></button> *}
		<button type="button" class="btn delete btn-block btn-raised btn-round" onclick="location.href='index.php'">{$APP.LBL_CANCEL_BUTTON_LABEL} <i class="vteicon md-sm" style="color:white">cancel</i></button>
	</div>
	
</div>
