<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode");
$urvenue_ws_itemcartcode = urvenue_ws_cleanup_request("itemcartcode");
$urvenue_ws_managementid = urvenue_ws_cleanup_request("managementid");
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_defaultmanageentid)) ? $urvenue_ws_defaultmanageentid : $urvenue_ws_managementid;
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_config_manageentid)) ? $urvenue_ws_config_manageentid : $urvenue_ws_managementid;
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_item = ($urvenue_ws_mastercode) ? urvenue_ws_get_invitem($urvenue_ws_mastercode) : "";

if($urvenue_ws_cartcode and $urvenue_ws_itemcartcode){
    $urvenue_ws_cartparams = "cartcode=$urvenue_ws_cartcode&itemcartcode=$urvenue_ws_itemcartcode";
    $urvenue_ws_eventdata = ($urvenue_ws_managementid) ? array("managementid" => $urvenue_ws_managementid) : "";
    $urvenue_ws_cartfeedresponse = urvenue_ws_get_apiwvar("cart-delete", $urvenue_ws_cartparams, $urvenue_ws_eventdata);
}

if(is_array($urvenue_ws_cartfeedresponse) and $urvenue_ws_cartfeedresponse["uv"]["success"]["status"] == "success"){
    $urvenue_ws_cartcode = $urvenue_ws_cartfeedresponse["uv"]["data"]["cartcode"];
    $urvenue_ws_cartcode = ($urvenue_ws_cartfeedresponse["uv"]["data"]["cart"]) ? $urvenue_ws_cartcode : "";
    $urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_vendata, $urvenue_ws_cartfeedresponse);
}

$urvenue_ws_return = array(
    "cart" => $urvenue_ws_cart,
    "cartcode" => $urvenue_ws_cartcode,
    "item" => $urvenue_ws_item
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
