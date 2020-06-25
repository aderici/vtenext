{*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************}
{* crmv@188364 *}
<div class="history_line">
	<div class="history_line_img">
		<i class="vteicon">{$line.img}</i>
	</div>
	<div class="history_line_info">
		<div class="history_line_title">
			<div>
				<div class="history_line_user_img">
					<img src="{$line.userimg}" alt="" title="{$line.username}" class="userAvatar">
				</div>
				<div class="history_line_date">
					{if isset($line.interval)}
						<span style="color: gray; text-decoration: none;">{$line.interval} ({$line.fulldate})</span>
					{else}
						<span style="color: gray; text-decoration: none;">{$line.fulldate}</span>
					{/if}
				</div>
				<div class="history_line_user_name">
					{if $line.userlink}
						<a href="{$line.userlink}">{$line.userfullname}</a>
					{else}
						{$line.userfullname}
					{/if}
				</div>
				<div class="history_line_text">
					{$line.text}
				</div>
			</div>
			<div class="history_line_details">
				{include file="modules/Processes/HistoryDetails.tpl"}
			</div>
		</div>
		<div class="history_line_date">
			{if $line.duration !== false}
				<span style="color: gray;">{'Duration'|getTranslatedString:'Calendar'}: {$line.duration}</span>
			{/if}
		</div>
	</div>
</div>
