{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@194390 *}

{if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads'}
	<div id="locateMapModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{$APP.CHOOSE_ADDRESS_TO_VIEW}</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2">
							{if $MODULE eq 'Accounts'}
								<button class="btn btn-primary btn-block btn-lg" onclick="VTE.MapLocation.searchMapLocation('{$ID}', 'Main');">{$APP.LBL_BILLING_ADDRESS}</button>
								<button class="btn btn-primary btn-block btn-lg" onclick="VTE.MapLocation.searchMapLocation('{$ID}', 'Other');">{$APP.LBL_SHIPPING_ADDRESS}</button>
							{/if}
							{if $MODULE eq 'Contacts'}
								<button class="btn btn-primary btn-block btn-lg" onclick="VTE.MapLocation.searchMapLocation('{$ID}', 'Main');">{$APP.LBL_PRIMARY_ADDRESS}</button>
								<button class="btn btn-primary btn-block btn-lg" onclick="VTE.MapLocation.searchMapLocation('{$ID}', 'Other');">{$APP.LBL_ALTERNATE_ADDRESS}</button>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}
