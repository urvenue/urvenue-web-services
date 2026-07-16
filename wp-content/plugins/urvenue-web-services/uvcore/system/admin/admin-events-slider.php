<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$urvenue_ws_evetnsshowarrows = urvenue_ws_adm_get_adminfieldhtml("events->slider-showarrows");
$urvenue_ws_evetnsshowdots = urvenue_ws_adm_get_adminfieldhtml("events->slider-showdots");
$urvenue_ws_evetnsslideranimation = urvenue_ws_adm_get_adminfieldhtml("events->slider-animation");
$urvenue_ws_eventsslidermaxevents = urvenue_ws_adm_get_adminfieldhtml("events->slider-maxevents");
?>
<div id="uvs-admin-events-slider" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['events-slider'] ); ?>">
    <div class="uvs-admin-opt-title">Events Slider</div>
	<div class="uvs-admin-opt-subtitle">If there are no events with slider flyers the slider will be removed, check flyers configuration to allow different images type/ratio</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Arrows</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_evetnsshowarrows, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Pagination <small>Show dots to navigate between the slider elements</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_evetnsshowdots, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Transition Animation</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_evetnsslideranimation, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Maximum Events <small>Maximum Number of events in the slider</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_eventsslidermaxevents, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
</div>