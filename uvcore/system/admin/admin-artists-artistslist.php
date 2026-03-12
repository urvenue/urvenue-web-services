<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
	@module: system/admin/box/artists-list
	@author: UrVenue - aa
	@version: 1.0
*/

// $uvsartistslistview = uvs_get_adminfieldhtml("artists->artist-listview");
$uvsartistslistview = urvenue_ws_adm_get_adminfieldhtml("artists->artist-listview"); // Axl UWS-7416
// $uvsartistsbuttonlabel = uvs_get_adminfieldhtml("artists->artist-buttonlabel");
$uvsartistsbuttonlabel = urvenue_ws_adm_get_adminfieldhtml("artists->artist-buttonlabel"); // Axl UWS-7416
?>
<?php // @Axl ?>
<?php /* old: <div id="uvs-admin-artists-list" class="uvs-admin-opt-section [echo $uvs_admin_optstabs_state[artists-list]]"> */ ?>
<div id="uvs-admin-artists-list" class="uvs-admin-opt-section <?php echo esc_attr( $uvs_admin_optstabs_state['artists-list'] ); ?>">
    <div class="uvs-admin-opt-title">Artist List</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">List View</div>
		<div class="uvsvalue">
			<?php /* Old: echo wp_kses( $uvsartistslistview, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvsartistslistview; */ echo wp_kses( $uvsartistslistview, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Button Label</div>
		<div class="uvsvalue">
			<?php /* Old: echo wp_kses( $uvsartistsbuttonlabel, uvs_allowed_admin_html() ); */ ?>
			<?php /* Old: echo $uvsartistsbuttonlabel; */ echo wp_kses( $uvsartistsbuttonlabel, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		</div>
    </div>
</div>
<?php // @Axl End ?>