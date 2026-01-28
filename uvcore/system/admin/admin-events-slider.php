<?php

$uvsevetnsshowarrows = uvs_get_adminfieldhtml("events->slider-showarrows");
$uvsevetnsshowdots = uvs_get_adminfieldhtml("events->slider-showdots");
$uvsevetnsslideranimation = uvs_get_adminfieldhtml("events->slider-animation");
$uvseventsslidermaxevents = uvs_get_adminfieldhtml("events->slider-maxevents");
?>
<div id="uvs-admin-events-slider" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['events-slider']; ?>">
    <div class="uvs-admin-opt-title">Events Slider</div>
	<div class="uvs-admin-opt-subtitle">If there are no events with slider flyers the slider will be removed, check flyers configuration to allow different images type/ratio</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Arrows</div>
		<div class="uvsvalue">
			<?php echo $uvsevetnsshowarrows; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Pagination <small>Show dots to navigate between the slider elements</small></div>
		<div class="uvsvalue">
			<?php echo $uvsevetnsshowdots; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Transition Animation</div>
		<div class="uvsvalue">
			<?php echo $uvsevetnsslideranimation; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Maximum Events <small>Maximum Number of events in the slider</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsslidermaxevents; ?>
		</div>
	</div>
</div>