<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

//Add the thing to the cart
// $urvenue_ws_cartcode = uws_cleanup_request("cartcode");
$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $urvenue_ws_managementid = uws_cleanup_request("managementid");
$urvenue_ws_managementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_defaultmanageentid)) ? $urvenue_ws_defaultmanageentid : $urvenue_ws_managementid;
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_config_manageentid)) ? $urvenue_ws_config_manageentid : $urvenue_ws_managementid;

$urvenue_ws_eventdata = ($urvenue_ws_managementid) ? array("managementid" => $urvenue_ws_managementid) : "";

// $urvenue_ws_cart = uws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata);
$urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata); // Axl UWS-7416

$urvenue_ws_return = array(
    "cart" => $urvenue_ws_cart
);

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416