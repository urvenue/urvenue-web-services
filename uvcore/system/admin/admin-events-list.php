<?php
/**
	@module: system/admin/box/events-list
	@author: UrVenue - aa
	@version: 1.0
*/

$uvseventslisttype = uvs_get_adminfieldhtml("events->list-listtype");
$uvseventslistmaxevents = uvs_get_adminfieldhtml("events->list-maxevents");
?>
<div id="uvs-admin-events-list" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['events-list']; ?>">
    <div class="uvs-admin-opt-title">Events List</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">List Type</div>
		<div class="uvsvalue">
			<?php echo $uvseventslisttype; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Maximum Events <small>Maximum Number of events in the list</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventslistmaxevents; ?>
		</div>
	</div>
</div>