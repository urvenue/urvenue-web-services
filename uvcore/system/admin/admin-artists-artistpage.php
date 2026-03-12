<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $uvsartistspageurl = uvs_get_adminfieldhtml("artists->artist-url");
$uvsartistspageurl = urvenue_ws_adm_get_adminfieldhtml("artists->artist-url"); // Axl UWS-7416
// $uvsartistsimagetype = uvs_get_adminfieldhtml("artists->artist-imagetype");
$uvsartistsimagetype = urvenue_ws_adm_get_adminfieldhtml("artists->artist-imagetype"); // Axl UWS-7416
// $uvsartistsimageratio = uvs_get_adminfieldhtml("artists->artist-imageratio");
$uvsartistsimageratio = urvenue_ws_adm_get_adminfieldhtml("artists->artist-imageratio"); // Axl UWS-7416

?>
<?php // @Axl ?>
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
<?php /* old: <div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section [echo $uvs_admin_optstabs_state[artists-artistpage]]"> */ ?>
<div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section <?php echo esc_attr( $uvs_admin_optstabs_state['artists-artistpage'] ); ?>">
    <div class="uvs-admin-opt-title">Artist Page</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Artist URL</div>
		<div class="uvsvalue">
			<?php /* Old: echo wp_kses( $uvsartistspageurl, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvsartistspageurl; */ echo wp_kses( $uvsartistspageurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		</div>
    </div>
    <div class="uvs-admin-opt-subtitle">Artist Image</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Type</div>
		<div class="uvsvalue">
			<?php /* Old: echo wp_kses( $uvsartistsimagetype, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvsartistsimagetype; */ echo wp_kses( $uvsartistsimagetype, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Ratio</div>
		<div class="uvsvalue">
			<?php /* Old: echo wp_kses( $uvsartistsimageratio, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvsartistsimageratio; */ echo wp_kses( $uvsartistsimageratio, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		</div>
    </div>
</div>
<?php // @Axl End ?>
