<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_lib;

urvenue_ws_check_nonce("urvenue_ws_reservations");

$urvenue_ws_manageentid = urvenue_ws_cleanup_request("manageentid");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_venueid = str_replace("VEN", "", $urvenue_ws_venuecode);

$urvenue_ws_args = array(
    "manageentid" => $urvenue_ws_manageentid,
    "venueid" => $urvenue_ws_venueid,
);
$urvenue_ws_leadtypeslist = urvenue_ws_inquiry_get_leadtypes($urvenue_ws_args);

$urvenue_ws_return = array(
    "venuecode" => $urvenue_ws_venuecode,
    "manageentid" => $urvenue_ws_manageentid,
    "leadtypes" => $urvenue_ws_leadtypeslist,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
