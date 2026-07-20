<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_date = urvenue_ws_cleanup_request("caldate");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests");
$urvenue_ws_extdatajson = urvenue_ws_cleanup_request("ext_datajson");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_extdatajson = ($urvenue_ws_extdatajson) ? stripslashes(html_entity_decode($urvenue_ws_extdatajson)) : $urvenue_ws_extdatajson;

$urvenue_ws_bk4args = array(
    "date" => $urvenue_ws_date,
    "mastercode" => $urvenue_ws_mastercode,
    "venuecode" => $urvenue_ws_venuecode,
    "extdata" => $urvenue_ws_extdatajson,
);
$urvenue_ws_itembk4timesel = urvenue_ws_get_itembk4sel($urvenue_ws_bk4args);

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itembk4timesel,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
