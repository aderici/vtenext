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
	<section class="portfolio-block skills" style="padding-bottom:70px;padding-top:70px;">
		<div class="container">
			<div class="heading" style="margin-bottom:50px;">
				<h2>{'verify_sent_title'|_T}</h2>
			</div>
			<div class="row">
				<div class="col-md-5 mx-auto">
					<div class="card special-skill-item border-0">
						<div class="card-header bg-transparent border-0">
							<i class="icon ion-email-unread"></i>
						</div>
						<div class="card-body">
							<h3 class="card-title">{'verify_sent_subtitle'|_T}</h3>
							<p class="card-text" style="margin-bottom:40px;"></p>
							<p style="margin-bottom:10px;">{'verify_sent_email_not_received'|_T}</p>
							<a class="btn btn-primary btn-lg" href="index.php?action=verify&cid={$CONTACT_ID|urlencode}">{'verify_sent_retry_button'|_T}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

{include file="Footer.tpl"}
