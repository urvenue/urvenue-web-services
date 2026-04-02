<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsreservations");
urvenue_ws_check_nonce("uwsreservations"); // Axl UWS-7416

// $urvenue_ws_manageentid = uws_cleanup_request("manageentid");
$urvenue_ws_manageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
$urvenue_ws_venueid = str_replace("VEN", "", $urvenue_ws_venuecode);

$urvenue_ws_args = array(
    "manageentid" => $urvenue_ws_manageentid,
    "venueid" => $urvenue_ws_venueid,
);
// $urvenue_ws_leadtypeslist = uws_inquiry_get_leadtypes($urvenue_ws_args);
$urvenue_ws_leadtypeslist = urvenue_ws_inquiry_get_leadtypes($urvenue_ws_args); // Axl UWS-7416

$urvenue_ws_return = array(
    "venuecode" => $urvenue_ws_venuecode,
    "manageentid" => $urvenue_ws_manageentid,
    "leadtypes" => $urvenue_ws_leadtypeslist,
);
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416