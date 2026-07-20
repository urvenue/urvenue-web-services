<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
	@module: system/admin/box/events-list
	@author: UrVenue - aa
	@version: 1.0
*/

$urvenue_ws_eventslisttype = urvenue_ws_adm_get_adminfieldhtml("events->list-listtype");
$urvenue_ws_eventslistmaxevents = urvenue_ws_adm_get_adminfieldhtml("events->list-maxevents");
?>
<div id="uvs-admin-events-list" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['events-list'] ); ?>">
    <div class="uvs-admin-opt-title">Events List</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">List Type</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_eventslisttype, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Maximum Events <small>Maximum Number of events in the list</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_eventslistmaxevents, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
</div>