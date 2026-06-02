<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $urvenue_ws_mastercode = uws_cleanup_request("mastercode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $urvenue_ws_date = uws_cleanup_request("caldate");
$urvenue_ws_date = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $urvenue_ws_guests = uws_cleanup_request("guests");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416
// $urvenue_ws_extdatajson = uws_cleanup_request("ext_datajson");
$urvenue_ws_extdatajson = urvenue_ws_cleanup_request("ext_datajson"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
$urvenue_ws_extdatajson = ($urvenue_ws_extdatajson) ? stripslashes(html_entity_decode($urvenue_ws_extdatajson)) : $urvenue_ws_extdatajson;

$urvenue_ws_bk4args = array(
    "date" => $urvenue_ws_date,
    "mastercode" => $urvenue_ws_mastercode,
    "venuecode" => $urvenue_ws_venuecode,
    "extdata" => $urvenue_ws_extdatajson,
);
// $urvenue_ws_itembk4timesel = uws_get_itembk4sel($urvenue_ws_bk4args);
$urvenue_ws_itembk4timesel = urvenue_ws_get_itembk4sel($urvenue_ws_bk4args); // Axl UWS-7416

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itembk4timesel,
);
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416