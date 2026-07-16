<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_inpenable = urvenue_ws_adm_get_adminfieldhtml("notifications->enable");
$urvenue_ws_inpwebhook = urvenue_ws_adm_get_adminfieldhtml("notifications->webhook");
$urvenue_ws_inpminevents = urvenue_ws_adm_get_adminfieldhtml("notifications->minevents");
?>
<div id="uvs-admin-notifications" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['notifications'] ); ?>">
	<div class="uvs-admin-opt-title">Events Notifications</div>
	<div class="uvs-admin-opt-subtitle">Configure events alert notifications.</div>
	<div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Notifications
			<small>Turn on automated alerts when event-related errors occur.</small>
		</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_inpenable, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Webhook URL
			<small>Enter the Slack Incoming Webhook URL where notifications will be sent.</small>
		</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_inpwebhook, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>

	<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
		<div class="uvsname">Minimum Events
			<small>Minimum number of events to trigger notification.</small>
		</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_inpminevents, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
</div>
