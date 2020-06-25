{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

{* ---- buttons ---- *}
{if $TRACKER_DATA.enable_buttons eq true}
	{include file="modules/SDK/src/CalendarTracking/TrackingSmallButtons.tpl"}
{else}
	{include file="modules/SDK/src/CalendarTracking/TrackingSmallButtons.tpl"}
{/if}
&nbsp;&nbsp;
