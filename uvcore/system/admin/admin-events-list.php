<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
	@module: system/admin/box/events-list
	@author: UrVenue - aa
	@version: 1.0
*/

// $uvseventslisttype = uvs_get_adminfieldhtml("events->list-listtype");
// $uvseventslisttype = urvenue_ws_adm_get_adminfieldhtml("events->list-listtype"); // Axl UWS-7416
$urvenue_ws_eventslisttype = urvenue_ws_adm_get_adminfieldhtml("events->list-listtype"); // Axl UWS-7634
// $uvseventslistmaxevents = uvs_get_adminfieldhtml("events->list-maxevents");
// $uvseventslistmaxevents = urvenue_ws_adm_get_adminfieldhtml("events->list-maxevents"); // Axl UWS-7416
$urvenue_ws_eventslistmaxevents = urvenue_ws_adm_get_adminfieldhtml("events->list-maxevents"); // Axl UWS-7634
?>
<?php // @Axl ?>
<?php /* old: <div id="uvs-admin-events-list" class="uvs-admin-opt-section [echo $urvenue_ws_admin_optstabs_state[events-list]]"> */ ?>
<div id="uvs-admin-events-list" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['events-list'] ); ?>">
    <div class="uvs-admin-opt-title">Events List</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">List Type</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo wp_kses( $uvseventslisttype, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvseventslisttype; */ echo wp_kses( $urvenue_ws_eventslisttype, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7634 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Maximum Events <small>Maximum Number of events in the list</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo wp_kses( $uvseventslistmaxevents, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvseventslistmaxevents; */ echo wp_kses( $urvenue_ws_eventslistmaxevents, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7634 ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>
<?php // @Axl End ?>