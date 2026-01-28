<?php
$uvsuitheme = uvs_get_adminfieldhtml("ui->uitheme");
$uvsuiprimarycolor = uvs_get_adminfieldhtml("ui->primarycolor");
$uvsuisecondarycolor = uvs_get_adminfieldhtml("ui->secondarycolor");
$uvsuiaccentcolor = uvs_get_adminfieldhtml("ui->accentcolor");
$uvsuipoptheme = uvs_get_adminfieldhtml("ui->uipoptheme");
$uvsuipopaccentcolor = uvs_get_adminfieldhtml("ui->popaccentcolor");
?>
<div id="uvs-admin-ui-color-palette" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['ui-color-palette']; ?>">
    <div class="uvs-admin-opt-title">UI Color Palette</div>
	<div class="uvs-admin-opt-subtitle">Control the color scheme of the UrVenue integrations</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Theme <small>Select light or dark depending on you site color</small></div>
		<div class="uvsvalue">
			<?php echo $uvsuitheme; ?>
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
			<?php echo $uvsuiaccentcolor; ?>
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
			<?php echo $uvsuipoptheme; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
		<div class="uvsname">Brand/Accent Color <small>Choose light color on dark theme and dark color on light theme.</small></div>
		<div class="uvsvalue">
			<?php echo $uvsuipopaccentcolor; ?>
			<div class="uv-loader-uvicon"></div>
		</div>
	</div>
</div>