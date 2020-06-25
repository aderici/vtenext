{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@161554 *}

{include file="Header.tpl"}
 
{include file="NavbarIn.tpl"}

<main class="page">
	<section class="portfolio-block" style="padding-bottom:70px;padding-top:70px;">
		<div class="container">
			<div class="heading" style="margin-bottom:50px;">
				<h2>{'detailview_title'|_T}</h2>
			</div>
			<form id="detailview-form" style="min-height:450px;">
				<input type="hidden" name="accesstoken" value="{$ACCESS_TOKEN}" />
			</form>
		</div>
	</section>
</main>

{capture name=JS_SCRIPT}
	<script type="text/javascript">
		VTGDPR.loadDetailBlock();
	</script>
{/capture}

{include file="Footer.tpl" JS_SCRIPT=$smarty.capture.JS_SCRIPT}
