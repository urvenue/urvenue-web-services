<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_mapaddecos = urvenue_ws_adm_get_adminfieldhtml("map->mappage-showecomaps");
$urvenue_ws_mapviews = urvenue_ws_adm_get_adminfieldhtml("map->mappage-views");
$urvenue_ws_mapaddadmopt = urvenue_ws_adm_get_adminfieldhtml("map->mappage-addadmissionopt");

?>
<div id="uvs-admin-map" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['map'] ); ?>">
    <div class="uvs-admin-opt-title">Map</div>
    <div class="uvs-admin-opt-subtitle">Control global options for your map integration</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Default Map View <small>Select the default view of your map.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_mapviews, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix uvs-no-bb">
		<div class="uvsname">Show Admission Option on Map</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_mapaddadmopt, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix uvs-no-bb">
		<div class="uvsname">Add Ecomaps Dropdown <small>Adds the list of layouts on the different ecozones</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_mapaddecos, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    
</div>