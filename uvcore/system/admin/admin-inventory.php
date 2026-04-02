<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $urvenue_ws_inventorymanageentlock = uvs_get_adminfieldhtml("inventory->manageentlock");
// $urvenue_ws_inventorymanageentlock = urvenue_ws_adm_get_adminfieldhtml("inventory->manageentlock"); // Axl UWS-7416
$urvenue_ws_inventorymanageentlock = urvenue_ws_adm_get_adminfieldhtml("inventory->manageentlock"); // Axl UWS-7634
// $urvenue_ws_inventoryshowiteminfoinline = uvs_get_adminfieldhtml("inventory->showiteminfoinline");
// $urvenue_ws_inventoryshowiteminfoinline = urvenue_ws_adm_get_adminfieldhtml("inventory->showiteminfoinline"); // Axl UWS-7416
$urvenue_ws_inventoryshowiteminfoinline = urvenue_ws_adm_get_adminfieldhtml("inventory->showiteminfoinline"); // Axl UWS-7634

?>

<div id="uvs-admin-inventory" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['inventory']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['inventory'] ); ?>">
    <div class="uvs-admin-opt-title">Inventory</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Don't Allow Multiple Manageent ID <small>Allows the user to choose between continue with the current cart or create a new one</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_inventorymanageentlock; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_inventorymanageentlock, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_inventorymanageentlock, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Info Inline On Item Popup <small>Image, highlight and description will be show on the item inventory popup</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_inventoryshowiteminfoinline; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_inventoryshowiteminfoinline, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_inventoryshowiteminfoinline, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>