<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_isproxy = 1;
$urvenue_ws_action = isset($urvenue_ws_action) ? $urvenue_ws_action : ( isset( $_REQUEST["uvaction"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvaction"] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Proxy dispatcher reads action to route request; nonce verified per action handler

if ($urvenue_ws_action == "uvsp_checkproxyurl") {
	echo ("uv1");
	exit();
}

if (!$urvenue_ws_uvs_path) {
	include_once($urvenue_ws_corepath . "/system/uvs-admin-init.php");
}

//Check parameters to detect injection attacks
include_once($urvenue_ws_corepath . "/includes/security-functions.php");
urvenue_ws_security_check_params_injection();

if ($urvenue_ws_uvs_path) {
	if ($urvenue_ws_action == "uvsp_veaidinfo")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-veaidinfo-load.php");
	else if ($urvenue_ws_action == "uvsp_adminsave")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-adminsave-pro.php");
	else if ($urvenue_ws_action == "uvsp_checkapiconfig")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-checkapiconfig-load.php");
	else if ($urvenue_ws_action == "uwspx_loadevents")
		include_once($urvenue_ws_uvs_path . "/loads/uws-events-load.php");
	else if ($urvenue_ws_action == "uwspx_inventoryinit")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-init.php");
	else if ($urvenue_ws_action == "uwspx_inventoryglobaltype")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-globaltype.php");
	else if ($urvenue_ws_action == "uwspx_inventoryaddonvenues")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-addonvenues.php");
	else if ($urvenue_ws_action == "uwspx_inventoryitempop")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventoryitem-pop.php");
	else if ($urvenue_ws_action == "uwspx_inventoryiteminfo")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getinfo.php");
	else if ($urvenue_ws_action == "uwspx_itemgettimes")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-gettimes.php");
	else if($urvenue_ws_action == "uwspx_itemgetbk4times")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getbk4times.php");
	else if ($urvenue_ws_action == "uwspx_itemgetottimes")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getottimes.php");
	else if ($urvenue_ws_action == "uwspx_itemgetbottles")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getbottles.php");
	else if ($urvenue_ws_action == "uwspx_cartadditem")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-cart-additem.php");
	else if ($urvenue_ws_action == "uwspx_cartdeleteitem")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-cart-deleteitem.php");
	else if ($urvenue_ws_action == "uwspx_cartdrop")
		include_once($urvenue_ws_uvs_path . "/loads/cart-drop.php");
	else if ($urvenue_ws_action == "uwspx_itineraryinit")
		include_once($urvenue_ws_uvs_path . "/loads/itinerary-init.php");
	else if ($urvenue_ws_action == "uwspx_map")
		include_once($urvenue_ws_uvs_path . "/loads/map-load.php");
	else if ($urvenue_ws_action == "uwspx_noinventorydates")
		include_once($urvenue_ws_uvs_path . "/loads/noinventorydates-load.php");
	else if($urvenue_ws_action == "uwspx_closeddates")
		include_once($urvenue_ws_uvs_path . "/loads/closeddates-load.php");
	else if ($urvenue_ws_action == "uwspx_loadexperiences")
		include_once($urvenue_ws_uvs_path . "/loads/experiences-load.php");
	else if ($urvenue_ws_action == "uwspx_iteminquireform")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-inquireform.php");
	else if ($urvenue_ws_action == "uwspx_iteminquireformpro")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-inquireform-pro.php");
	else if ($urvenue_ws_action == "uwspx_getinquiryleadtypes")
		include_once($urvenue_ws_uvs_path . "/loads/inquiry-getleadtypes.php");
	else if ($urvenue_ws_action == "uwspx_sendinquiry")
		include_once($urvenue_ws_uvs_path . "/loads/inquiry-send.php");
	else if ($urvenue_ws_action == "uwspx_loaddynamicevents")
		include_once($urvenue_ws_uvs_path . "/loads/dynamicevents-load.php");
	else if ($urvenue_ws_action == "uwspx_loadeventsdp")
		include_once($urvenue_ws_uvs_path . "/loads/uws-eventsdp-load.php");
	else if ($urvenue_ws_action == "uwspx_getcartbreakdown")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-getcartbreakdown.php");
	else if ($urvenue_ws_action == "uwspx_mastercodebymasteritemcode")
		include_once($urvenue_ws_uvs_path . "/loads/mastercode-by-masteritemcode.php");
	/*else
		   // uvs_uverror("UVError 01-001: Proxy action not found.<br>");*/
} else
	urvenue_ws_adm_uverror("UVError 01-001: Proxy action not found.<br>");
