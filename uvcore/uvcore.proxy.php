<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$uv_isproxy = 1;
// $uvaction = isset($uvaction) ? $uvaction : $_REQUEST["uvaction"]; // Axl UWS-7418
$uvaction = isset($uvaction) ? $uvaction : ( isset( $_REQUEST["uvaction"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvaction"] ) ) : '' ); // Axl UWS-7418

if ($uvaction == "uvsp_checkproxyurl") {
	echo ("uv1");
	exit();
}

if (!$urvenue_ws_uvs_path) {
	include_once($urvenue_ws_corepath . "/system/uvs-admin-init.php");
}

//Check parameters to detect injection attacks
include_once($urvenue_ws_corepath . "/includes/security-functions.php");
// uws_security_check_params_injection();
urvenue_ws_security_check_params_injection(); // Axl UWS-7416

if ($urvenue_ws_uvs_path) {
	if ($uvaction == "uvsp_veaidinfo")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-veaidinfo-load.php");
	else if ($uvaction == "uvsp_adminsave")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-adminsave-pro.php");
	else if ($uvaction == "uvsp_checkapiconfig")
		include_once($urvenue_ws_uvs_path . "/loads/uvs-checkapiconfig-load.php");
	else if ($uvaction == "uwspx_loadevents")
		include_once($urvenue_ws_uvs_path . "/loads/uws-events-load.php");
	else if ($uvaction == "uwspx_inventoryinit")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-init.php");
	else if ($uvaction == "uwspx_inventoryglobaltype")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-globaltype.php");
	else if ($uvaction == "uwspx_inventoryaddonvenues")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventory-addonvenues.php");
	else if ($uvaction == "uwspx_inventoryitempop")
		include_once($urvenue_ws_uvs_path . "/loads/uws-inventoryitem-pop.php");
	else if ($uvaction == "uwspx_inventoryiteminfo")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getinfo.php");
	else if ($uvaction == "uwspx_itemgettimes")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-gettimes.php");
	else if($uvaction == "uwspx_itemgetbk4times")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getbk4times.php");
	else if ($uvaction == "uwspx_itemgetottimes")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getottimes.php");
	else if ($uvaction == "uwspx_itemgetbottles")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-getbottles.php");
	else if ($uvaction == "uwspx_cartadditem")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-cart-additem.php");
	else if ($uvaction == "uwspx_cartdeleteitem")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-cart-deleteitem.php");
	else if ($uvaction == "uwspx_cartdrop")
		include_once($urvenue_ws_uvs_path . "/loads/cart-drop.php");
	else if ($uvaction == "uwspx_itineraryinit")
		include_once($urvenue_ws_uvs_path . "/loads/itinerary-init.php");
	else if ($uvaction == "uwspx_map")
		include_once($urvenue_ws_uvs_path . "/loads/map-load.php");
	else if ($uvaction == "uwspx_noinventorydates")
		include_once($urvenue_ws_uvs_path . "/loads/noinventorydates-load.php");
	else if($uvaction == "uwspx_closeddates")
		include_once($urvenue_ws_uvs_path . "/loads/closeddates-load.php");
	else if ($uvaction == "uwspx_loadexperiences")
		include_once($urvenue_ws_uvs_path . "/loads/experiences-load.php");
	else if ($uvaction == "uwspx_iteminquireform")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-inquireform.php");
	else if ($uvaction == "uwspx_iteminquireformpro")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-item-inquireform-pro.php");
	else if ($uvaction == "uwspx_getinquiryleadtypes")
		include_once($urvenue_ws_uvs_path . "/loads/inquiry-getleadtypes.php");
	else if ($uvaction == "uwspx_sendinquiry")
		include_once($urvenue_ws_uvs_path . "/loads/inquiry-send.php");
	else if ($uvaction == "uwspx_loaddynamicevents")
		include_once($urvenue_ws_uvs_path . "/loads/dynamicevents-load.php");
	else if ($uvaction == "uwspx_loadeventsdp")
		include_once($urvenue_ws_uvs_path . "/loads/uws-eventsdp-load.php");
	else if ($uvaction == "uwspx_getcartbreakdown")
		include_once($urvenue_ws_uvs_path . "/loads/inventory-getcartbreakdown.php");
	else if ($uvaction == "uwspx_mastercodebymasteritemcode")
		include_once($urvenue_ws_uvs_path . "/loads/mastercode-by-masteritemcode.php");
	/*else
		   // uvs_uverror("UVError 01-001: Proxy action not found.<br>");*/
} else
	// uvs_uverror("UVError 01-001: Proxy action not found.<br>");
	urvenue_ws_adm_uverror("UVError 01-001: Proxy action not found.<br>"); // Axl UWS-7416
