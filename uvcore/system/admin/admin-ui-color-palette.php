<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $uvsuitheme = uvs_get_adminfieldhtml("ui->uitheme");
$uvsuitheme = urvenue_ws_adm_get_adminfieldhtml("ui->uitheme"); // Axl UWS-7416
// $uvsuiprimarycolor = uvs_get_adminfieldhtml("ui->primarycolor");
$uvsuiprimarycolor = urvenue_ws_adm_get_adminfieldhtml("ui->primarycolor"); // Axl UWS-7416
// $uvsuisecondarycolor = uvs_get_adminfieldhtml("ui->secondarycolor");
$uvsuisecondarycolor = urvenue_ws_adm_get_adminfieldhtml("ui->secondarycolor"); // Axl UWS-7416
// $uvsuiaccentcolor = uvs_get_adminfieldhtml("ui->accentcolor");
$uvsuiaccentcolor = urvenue_ws_adm_get_adminfieldhtml("ui->accentcolor"); // Axl UWS-7416
// $uvsuipoptheme = uvs_get_adminfieldhtml("ui->uipoptheme");
$uvsuipoptheme = urvenue_ws_adm_get_adminfieldhtml("ui->uipoptheme"); // Axl UWS-7416
// $uvsuipopaccentcolor = uvs_get_adminfieldhtml("ui->popaccentcolor");
$uvsuipopaccentcolor = urvenue_ws_adm_get_adminfieldhtml("ui->popaccentcolor"); // Axl UWS-7416
?>
<div id="uvs-admin-ui-color-palette" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['ui-color-palette']; */ echo esc_attr( $uvs_admin_optstabs_state['ui-color-palette'] ); ?>">
    <div class="uvs-admin-opt-title">UI Color Palette</div>
	<div class="uvs-admin-opt-subtitle">Control the color scheme of the UrVenue integrations</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Theme <small>Select light or dark depending on you site color</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsuitheme; */ ?>
			<?php /* Old: echo wp_kses( $uvsuitheme, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsuitheme, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-title">Custom Colors</div>
    <div class="uvs-admin-opt-subtitle">The selected colors will replace the default color from the light/dark theme.</div>
	<!-- <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Primary Color</div>
		<div class="uvsvalue">
			<?php //echo $uvsuiprimarycolor; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Secondary Color</div>
		<div class="uvsvalue">
			<?php //echo $uvsuisecondarycolor; ?>
		</div>
	</div> -->
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Brand/Accent Color <small>Choose light color on dark theme and dark color on light theme.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsuiaccentcolor; */ ?>
			<?php /* Old: echo wp_kses( $uvsuiaccentcolor, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsuiaccentcolor, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
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
			<?php // @Axl ?>
			<?php /* Old: echo $uvsuipoptheme; */ ?>
			<?php /* Old: echo wp_kses( $uvsuipoptheme, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsuipoptheme, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
		<div class="uvsname">Brand/Accent Color <small>Choose light color on dark theme and dark color on light theme.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsuipopaccentcolor; */ ?>
			<?php /* Old: echo wp_kses( $uvsuipopaccentcolor, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsuipopaccentcolor, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
			<div class="uv-loader-uvicon"></div>
		</div>
	</div>
</div>