<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_lib;

$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode");
$urvenue_ws_itemcartcode = urvenue_ws_cleanup_request("itemcartcode");
$urvenue_ws_managementid = urvenue_ws_cleanup_request("managementid");
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_defaultmanageentid)) ? $urvenue_ws_defaultmanageentid : $urvenue_ws_managementid;
$urvenue_ws_managementid = (!$urvenue_ws_managementid and isset($urvenue_ws_config_manageentid)) ? $urvenue_ws_config_manageentid : $urvenue_ws_managementid;
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_item = ($urvenue_ws_mastercode) ? urvenue_ws_get_invitem($urvenue_ws_mastercode) : "";
$urvenue_ws_cartcount = 1;

if($urvenue_ws_cartcode and $urvenue_ws_itemcartcode){

    $urvenue_ws_deletecartendpoint = $urvenue_ws_feeds_lib["cartv2-delete"]["url"];
    $urvenue_ws_itemdata = array(
        "cartcode" => $urvenue_ws_cartcode,
        "itemcartcode" => $urvenue_ws_itemcartcode
    );

    $urvenue_ws_deletecartendpoint = $urvenue_ws_deletecartendpoint . "&" . http_build_query($urvenue_ws_itemdata);

    $urvenue_ws_response = wp_remote_request($urvenue_ws_deletecartendpoint, array(
        'method' => 'DELETE',
        'timeout' => 60,
    ));
    $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

    $urvenue_ws_result = json_decode($urvenue_ws_resultraw, true);

    if(is_array($urvenue_ws_result) and $urvenue_ws_result["success"]){
        $urvenue_ws_cartcount = $urvenue_ws_result["data"]["cartcount"];
    }
}

$urvenue_ws_return = array();

if($urvenue_ws_cartcode and $urvenue_ws_itemcartcode){
    $urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode);
    $urvenue_ws_return = array(
        "cart" => $urvenue_ws_cart,
        "cartcode" => $urvenue_ws_cartcode,
        "item" => $urvenue_ws_item
    );
}

$urvenue_ws_return["cartcount"] = $urvenue_ws_cartcount;

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
