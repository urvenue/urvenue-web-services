<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_otid = urvenue_ws_cleanup_request("otid");
$urvenue_ws_resattr = urvenue_ws_cleanup_request("resatt");
$urvenue_ws_date = urvenue_ws_cleanup_request("caldate");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests");

$urvenue_ws_otdata = "otid:" . $urvenue_ws_otid . "|resatt:" . $urvenue_ws_resattr;
$urvenue_ws_otargs = array(
    "otdata" => $urvenue_ws_otdata,
    "date" => $urvenue_ws_date,
    "guests" => $urvenue_ws_guests,
    "mastercode" => $urvenue_ws_mastercode,
);
$urvenue_ws_itemottimesel = urvenue_ws_get_itemotsel($urvenue_ws_otargs);

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itemottimesel,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
