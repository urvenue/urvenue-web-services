<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvdate = uws_cleanup_request("caldate");
$uvdate = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $uvguests = uws_cleanup_request("guests");
$uvguests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416
// $uvextdatajson = uws_cleanup_request("ext_datajson");
$uvextdatajson = urvenue_ws_cleanup_request("ext_datajson"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
$uvextdatajson = ($uvextdatajson) ? stripslashes(html_entity_decode($uvextdatajson)) : $uvextdatajson;

$uvbk4args = array(
    "date" => $uvdate,
    "mastercode" => $uvmastercode,
    "venuecode" => $uvvenuecode,
    "extdata" => $uvextdatajson,
);
// $uvitembk4timesel = uws_get_itembk4sel($uvbk4args);
$uvitembk4timesel = urvenue_ws_get_itembk4sel($uvbk4args); // Axl UWS-7416

$uvreturn = array(
    "html" => $uvitembk4timesel,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416