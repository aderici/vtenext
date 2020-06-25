{* crmv@62394 *}
{* crmv@105588 *}

<div id="turbolift_tracker_cont">
	{if $TRACKER_FOR_COMPOSE}
		<span>{$APP.Tracking}</span>
		{include file="modules/SDK/src/CalendarTracking/TrackingButtonsCompose.tpl"}
	{else}
		<button type="button" class="crmbutton with-icon edit btn-block crmbutton-turbolift">
			{include file="modules/SDK/src/CalendarTracking/TrackingSmallButtons.tpl" ID=$RECORD}
			<span>{$APP.Tracking}</span>
		</button>
	{/if}
</div>
