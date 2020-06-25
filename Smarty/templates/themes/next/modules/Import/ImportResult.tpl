{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
*}

<script type="text/javascript" src="modules/Import/resources/Import.js"></script>

{include file='Buttons_List1.tpl'}

<div class="container mainContainer pt-5">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="vte-card">
				<input type="hidden" name="module" value="{$FOR_MODULE}" />
				<div class="row">
					<div class="col-sm-12">
						<div class="dvInnerHeader mb-5">
							<div class="dvInnerHeaderTitle">
								{'LBL_IMPORT'|@getTranslatedString:$MODULE} {$FOR_MODULE|@getTranslatedString:$FOR_MODULE} - {'LBL_RESULT'|@getTranslatedString:$MODULE}
							</div>
						</div>
					</div>
				</div>
				{if $ERROR_MESSAGE neq ''}
					<div class="row">
						<div class="col-sm-12 style1">
							{$ERROR_MESSAGE}
						</div>
					</div>
				{/if}
				<div class="row">
					<div class="col-sm-12">
						{include file="modules/Import/Import_Result_Details.tpl"}
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center my-3">
						{include file='modules/Import/Import_Finish_Buttons.tpl'}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
