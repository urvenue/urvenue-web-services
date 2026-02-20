<?php
$uvsinpenable = uvs_get_adminfieldhtml("notifications->enable");
$uvsinpwebhook = uvs_get_adminfieldhtml("notifications->webhook");
$uvsinpminevents = uvs_get_adminfieldhtml("notifications->minevents");
?>
<div id="uvs-admin-notifications" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['notifications']; */ echo esc_attr( $uvs_admin_optstabs_state['notifications'] ); ?>">
	<div class="uvs-admin-opt-title">Events Notifications</div>
	<div class="uvs-admin-opt-subtitle">Configure events alert notifications.</div>
	<div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Notifications
			<small>Turn on automated alerts when event-related errors occur.</small>
		</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsinpenable; */ ?>
			<?php echo wp_kses( $uvsinpenable, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Webhook URL
			<small>Enter the Slack Incoming Webhook URL where notifications will be sent.</small>
		</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsinpwebhook; */ ?>
			<?php echo wp_kses( $uvsinpwebhook, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>

	<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
		<div class="uvsname">Minimum Events
			<small>Minimum number of events to trigger notification.</small>
		</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsinpminevents; */ ?>
			<?php echo wp_kses( $uvsinpminevents, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>
