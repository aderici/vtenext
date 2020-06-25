{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@161554 *}

<nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-white portfolio-navbar gradient">
	<div class="container">
		<a class="navbar-brand logo" href="#">
			<img class="img-responsive" src="{$WEBSITE_LOGO}">
		</a>
		<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
			<span class="sr-only">Toggle navigation</span>
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item" role="presentation" id="support-request">
					<a class="nav-link" href="#">{'navbar_support'|_T}</a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link {if $CURRENT_ACTION eq 'privacy'}active{/if}" href="index.php?action=privacy&cid={$CONTACT_ID|urlencode}" target="_blank">{'navbar_privacy_policy'|_T}</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
