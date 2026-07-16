<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_uitheme = urvenue_ws_adm_get_adminfieldhtml("ui->uitheme");
$urvenue_ws_uiprimarycolor = urvenue_ws_adm_get_adminfieldhtml("ui->primarycolor");
$urvenue_ws_uisecondarycolor = urvenue_ws_adm_get_adminfieldhtml("ui->secondarycolor");
$urvenue_ws_uiaccentcolor = urvenue_ws_adm_get_adminfieldhtml("ui->accentcolor");
$urvenue_ws_uipoptheme = urvenue_ws_adm_get_adminfieldhtml("ui->uipoptheme");
$urvenue_ws_uipopaccentcolor = urvenue_ws_adm_get_adminfieldhtml("ui->popaccentcolor");
?>
<div id="uvs-admin-ui-color-palette" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['ui-color-palette'] ); ?>">
    <div class="uvs-admin-opt-title">UI Color Palette</div>
	<div class="uvs-admin-opt-subtitle">Control the color scheme of the UrVenue integrations</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Theme <small>Select light or dark depending on you site color</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_uitheme, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-title">Custom Colors</div>
    <div class="uvs-admin-opt-subtitle">The selected colors will replace the default color from the light/dark theme.</div>
	<!-- <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Primary Color</div>
		<div class="uvsvalue">
			<?php //echo $urvenue_ws_uiprimarycolor; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Secondary Color</div>
		<div class="uvsvalue">
			<?php //echo $urvenue_ws_uisecondarycolor; ?>
		</div>
	</div> -->
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Brand/Accent Color <small>Choose light color on dark theme and dark color on light theme.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_uiaccentcolor, urvenue_ws_adm_allowed_admin_html() ); ?>
			<div class="uv-loader-uvicon"></div>
		</div>
	</div>

	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-admin-opt-title">Popup Colors</div>
	<div class="uvs-admin-opt-subtitle">
		Control the color scheme of the UrVenue integrations popup.
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Theme <small>Select light or dark depending on you site color</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_uipoptheme, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
		<div class="uvsname">Brand/Accent Color <small>Choose light color on dark theme and dark color on light theme.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_uipopaccentcolor, urvenue_ws_adm_allowed_admin_html() ); ?>
			<div class="uv-loader-uvicon"></div>
		</div>
	</div>
</div>