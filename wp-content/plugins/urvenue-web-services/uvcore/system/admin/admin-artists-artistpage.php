<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_artistspageurl = urvenue_ws_adm_get_adminfieldhtml("artists->artist-url");
$urvenue_ws_artistsimagetype = urvenue_ws_adm_get_adminfieldhtml("artists->artist-imagetype");
$urvenue_ws_artistsimageratio = urvenue_ws_adm_get_adminfieldhtml("artists->artist-imageratio");

?>
<div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['artists-artistpage'] ); ?>">
    <div class="uvs-admin-opt-title">Artist Page</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Artist URL</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_artistspageurl, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-admin-opt-subtitle">Artist Image</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Type</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_artistsimagetype, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Ratio</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_artistsimageratio, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
</div>
