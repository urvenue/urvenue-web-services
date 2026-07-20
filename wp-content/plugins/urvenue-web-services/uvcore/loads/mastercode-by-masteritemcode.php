<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_packages");

$urvenue_ws_masteritemcode = urvenue_ws_cleanup_request("masteritemcode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_date = urvenue_ws_cleanup_request("date");

$urvenue_ws_masteritemcodeinfo = array(
    "masteritemcode" => $urvenue_ws_masteritemcode,
    "venuecode" => $urvenue_ws_venuecode,
    "date" => $urvenue_ws_date
);

$urvenue_ws_mastercode = urvenue_ws_get_mastercode_by_masteritemcode($urvenue_ws_masteritemcodeinfo);

//test no mastercode found
//$urvenue_ws_mastercode = "";

$urvenue_ws_return = array(
    "mastercode" => $urvenue_ws_mastercode,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
