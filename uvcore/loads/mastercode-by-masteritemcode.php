<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwspackages");
urvenue_ws_check_nonce("uwspackages"); // Axl UWS-7416

// $urvenue_ws_masteritemcode = uws_cleanup_request("masteritemcode");
$urvenue_ws_masteritemcode = urvenue_ws_cleanup_request("masteritemcode"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_date = uws_cleanup_request("date");
$urvenue_ws_date = urvenue_ws_cleanup_request("date"); // Axl UWS-7416

$urvenue_ws_masteritemcodeinfo = array(
    "masteritemcode" => $urvenue_ws_masteritemcode,
    "venuecode" => $urvenue_ws_venuecode,
    "date" => $urvenue_ws_date
);

// $urvenue_ws_mastercode = uws_get_mastercode_by_masteritemcode($urvenue_ws_masteritemcodeinfo);
$urvenue_ws_mastercode = urvenue_ws_get_mastercode_by_masteritemcode($urvenue_ws_masteritemcodeinfo); // Axl UWS-7416

//test no mastercode found
//$urvenue_ws_mastercode = "";

$urvenue_ws_return = array(
    "mastercode" => $urvenue_ws_mastercode,
);
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416