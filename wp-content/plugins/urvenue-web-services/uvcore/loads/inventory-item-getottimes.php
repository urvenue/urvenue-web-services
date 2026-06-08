<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

// $urvenue_ws_mastercode = uws_cleanup_request("mastercode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $urvenue_ws_otid = uws_cleanup_request("otid");
$urvenue_ws_otid = urvenue_ws_cleanup_request("otid"); // Axl UWS-7416
// $urvenue_ws_resattr = uws_cleanup_request("resatt");
$urvenue_ws_resattr = urvenue_ws_cleanup_request("resatt"); // Axl UWS-7416
// $urvenue_ws_date = uws_cleanup_request("caldate");
$urvenue_ws_date = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $urvenue_ws_guests = uws_cleanup_request("guests");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416

$urvenue_ws_otdata = "otid:" . $urvenue_ws_otid . "|resatt:" . $urvenue_ws_resattr;
$urvenue_ws_otargs = array(
    "otdata" => $urvenue_ws_otdata,
    "date" => $urvenue_ws_date,
    "guests" => $urvenue_ws_guests,
    "mastercode" => $urvenue_ws_mastercode,
);
// $urvenue_ws_itemottimesel = uws_get_itemotsel($urvenue_ws_otargs);
$urvenue_ws_itemottimesel = urvenue_ws_get_itemotsel($urvenue_ws_otargs); // Axl UWS-7416

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itemottimesel,
);
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416