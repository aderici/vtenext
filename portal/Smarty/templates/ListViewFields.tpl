{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}

{* crmv@173153 *}

{foreach from=$ENTRIES key=ID item=ENTRY}
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default TickestList" onclick="window.location.href='{$LINKS.$ID}'">
				<div class="panel-body">
					{if $MODULE eq 'Contacts'}
						<div class="col-md-12">
							<h4>{$ENTRY.0}&nbsp;{$ENTRY.1}</h4>
						</div>
						<div class="col-md-12">
							{$ENTRY.3|getTranslatedString}, {$ENTRY.2}
						</div>
					{elseif $MODULE eq 'Documents'}
						<div class="col-md-12">
							<h4><i class="material-icons-download icon_file_download"></i> {$ENTRY.1}</h4>
						</div>
						<div class="col-md-12">
							{$ENTRY.0}, {$ENTRY.3|getTranslatedString}
						</div>
					{elseif $MODULE eq 'Assets'}
						<div class="col-md-12">
							<h4>{$ENTRY.1}</h4>
						</div>
						<div class="col-md-12">
							{$ENTRY.0}, {$ENTRY.3|getTranslatedString}
						</div>
					{else}
						<div class="col-md-12">{$MODULE}
							<h4>{$ENTRY.1}</h4>
						</div>
						<div class="col-md-12">
							{$ENTRY.0}, {$ENTRY.3|getTranslatedString}
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
{/foreach}
