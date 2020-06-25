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
 
{include file="NavbarOut.tpl"}

<main class="page">
	<section class="portfolio-block" style="padding-bottom:70px;padding-top:70px;">
		<div class="container">
			<div class="heading" style="margin-bottom:30px;">
				<h2>{'privacy_policy_title'|_T}</h2>
			</div>
			<form id="privacy-policy-form">
				<input type="hidden" name="cid" value="{$CONTACT_ID}" />
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-12">{$PRIVACY_POLICY}</div>
					</div>
				</div>
				<div style="margin-top:30px;text-align:center;">
					<button class="btn btn-primary btn-lg" id="save-settings-button" type="button" onclick="VTGDPR.process('sendPrivacyPolicy');">{'privacy_policy_send_email'|_T}</button>
				</div>
			</form>
		</div>
	</section>
</main>

{include file="Footer.tpl"}
