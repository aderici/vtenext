{*/*+*************************************************************************************
* The contents of this file are subject to the VTECRM License Agreement
* ("licenza.txt"); You may not use this file except in compliance with the License
* The Original Code is: VTECRM
* The Initial Developer of the Original Code is VTECRM LTD.
* Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
* All Rights Reserved.
***************************************************************************************/*}

{* crmv@140887 *}

{if $LINEAR eq true}
	<div class="dataloader" data-loader="linear" id="{$LIID}" style="{$LIEXTRASTYLE}">
		<div class="wrap go">
			<div class="linearloader bar">
				<div></div>
			</div>
		</div>
	</div>
{else}
	<i class="dataloader" data-loader="circle" id="{$LIID}" style="vertical-align:middle;{$LIEXTRASTYLE}"></i>
{/if}
