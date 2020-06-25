{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@197445 *}

<br>
<form method="POST" action="index.php" enctype="multipart/form-data" onsubmit="VtigerJS_DialogBox.block();">
	<input type="hidden" name="module" value="Settings">
	<input type="hidden" name="action" value="SettingsAjax">
	<input type="hidden" name="file" value="{$RETURN_FILE}">
	<input type="hidden" name="mode" value="save">
	<input type="hidden" name="id" value="{$ID}">
	<table width="50%" align="center">
		<tr>
			<td class="dvtCellLabel" align="right" width="20%"><span>{$APP.LBL_MODULE}</span>&nbsp;&nbsp;</td>
			<td align="left" width="250">
				<div class="dvtCellInfo" style="float:left">
					<select name="moduleName" class="dvtCellInfo detailedViewTextBox" onchange="loadModule(this.value)">
						{foreach key=k item=i from=$moduleNames}
							<option value="{$k}" {$i.1}>{$i.0}</option>
						{/foreach}
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="dvtCellLabel" valign="top" align="right" width="20%"><span>{'LBL_FILTER'|getTranslatedString}</span>&nbsp;&nbsp;</td>
			<td align="left" width="250">
				<div class="dvtCellInfo" style="float:left">
					<select name="viewname" id="viewname" class="detailedViewTextBox">{$CUSTOMVIEW_OPTION}</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="dvtCellLabel" valign="top" align="right" width="20%"><span>{'LBL_TRAINING_COLUMNS'|getTranslatedString:'Settings'}</span>&nbsp;&nbsp;</td>
			<td align="left" width="250">
				<div class="dvtCellInfo" style="float:left">
					<select multiple name="training_columns[]" id="training_columns" class="detailedViewTextBox">
						{include file="Settings/KlondikeAI/KlondikeClassifier/FieldOptions.tpl" CHOOSECOLUMN=$CHOOSECOLUMN_TC}
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="dvtCellLabel" valign="top" align="right" width="20%"><span>{'LBL_TRAINING_TARGET'|getTranslatedString:'Settings'}</span>&nbsp;&nbsp;</td>
			<td align="left" width="250">
				<div class="dvtCellInfo" style="float:left">
					<select name="training_target" id="training_target" class="detailedViewTextBox">
						{include file="Settings/KlondikeAI/KlondikeClassifier/FieldOptions.tpl" CHOOSECOLUMN=$CHOOSECOLUMN_TT}
					</select>
				</div>
			</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td align="right" colspan="2">
			<input title="{'LBL_CREATE_NEW'|getTranslatedString:'Reports'}" class="crmButton small save" type="button" name="button" value="{'LBL_CREATE_NEW'|getTranslatedString:'Reports'}" onclick="if (validateForm(this.form)) {ldelim} VtigerJS_DialogBox.block(); this.form.submit(); {rdelim}">
			<input type="submit" onclick="this.form.action.value='{$RETURN_FILE}'; this.form.file.value=''; this.form.mode.value='';" class="crmbutton small cancel" value='{$MOD.LBL_CANCEL_BUTTON}' title='{$MOD.LBL_CANCEL_BUTTON}'>
		</td></tr>
	</table>
</form>

<script type="text/javascript">
{literal}
function validateForm(form) {
	if (jQuery('[name="moduleName"]',form).val() == '') {
		alert('Please select the module');
		return false;
	} else if (jQuery('[name="viewname"]',form).val() == '') {
		alert('Please select a filter');
		return false;
	} else if (jQuery('#training_columns',form).val() == null || jQuery('#training_columns',form).val().length == 0) {
		alert('Please select at least one training field');
		return false;
	} else if (jQuery('[name="training_target"]',form).val() == '') {
		alert('Please select the training target');
		return false;
	}
	return true;
}
function loadModule(module) {
	jQuery.ajax({
		'dataType': 'JSON',
		'url': 'index.php?module=Settings&action=SettingsAjax&file=KlondikeClassifier&mode=load&moduleName='+module,
		success: function(data) {
			jQuery('#viewname').html(data['view']);
			jQuery('#training_columns').html(data['training_columns']);
			jQuery('#training_target').html(data['training_target']);
		}
	});
}
{/literal}
</script>