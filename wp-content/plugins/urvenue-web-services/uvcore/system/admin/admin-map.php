<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $urvenue_ws_mapaddecos = uvs_get_adminfieldhtml("map->mappage-showecomaps");
// $urvenue_ws_mapaddecos = urvenue_ws_adm_get_adminfieldhtml("map->mappage-showecomaps"); // Axl UWS-7416
$urvenue_ws_mapaddecos = urvenue_ws_adm_get_adminfieldhtml("map->mappage-showecomaps"); // Axl UWS-7634
// $urvenue_ws_mapviews = uvs_get_adminfieldhtml("map->mappage-views");
// $urvenue_ws_mapviews = urvenue_ws_adm_get_adminfieldhtml("map->mappage-views"); // Axl UWS-7416
$urvenue_ws_mapviews = urvenue_ws_adm_get_adminfieldhtml("map->mappage-views"); // Axl UWS-7634
// $urvenue_ws_mapaddadmopt = uvs_get_adminfieldhtml("map->mappage-addadmissionopt");
// $urvenue_ws_mapaddadmopt = urvenue_ws_adm_get_adminfieldhtml("map->mappage-addadmissionopt"); // Axl UWS-7416
$urvenue_ws_mapaddadmopt = urvenue_ws_adm_get_adminfieldhtml("map->mappage-addadmissionopt"); // Axl UWS-7634

?>
<div id="uvs-admin-map" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['map']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['map'] ); ?>">
    <div class="uvs-admin-opt-title">Map</div>
    <div class="uvs-admin-opt-subtitle">Control global options for your map integration</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Default Map View <small>Select the default view of your map.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_mapviews; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_mapviews, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_mapviews, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix uvs-no-bb">
		<div class="uvsname">Show Admission Option on Map</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_mapaddadmopt; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_mapaddadmopt, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_mapaddadmopt, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix uvs-no-bb">
		<div class="uvsname">Add Ecomaps Dropdown <small>Adds the list of layouts on the different ecozones</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_mapaddecos; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_mapaddecos, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_mapaddecos, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    
</div>