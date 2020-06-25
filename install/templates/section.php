<?php
	$titleColSize = $bigTitle ? 8 : 6;
	$logoColSize = $bigTitle ? 4 : 6;
?>
<div class="col-xs-12 content-padding">	
	<div class="col-xs-<?php echo $titleColSize; ?> vcenter text-left">
		<h2><?php echo $sectionTitle; ?></h2>
	</div><!--
	--><div class="col-xs-<?php echo $logoColSize; ?> nopadding vcenter text-right">
		<a href="<?php echo $enterprise_website[0]; ?>" target="_blank">
			<img src="include/install/images/vtenext.png" />
		</a>
	</div>
</div>