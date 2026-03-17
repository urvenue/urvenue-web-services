<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsreservations");
urvenue_ws_check_nonce("uwsreservations"); // Axl UWS-7416

// $uvmanageentid = uws_cleanup_request("manageentid");
$uvmanageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
$uvvenueid = str_replace("VEN", "", $uvvenuecode);

$uvargs = array(
    "manageentid" => $uvmanageentid,
    "venueid" => $uvvenueid,
);
// $uvleadtypeslist = uws_inquiry_get_leadtypes($uvargs);
$uvleadtypeslist = urvenue_ws_inquiry_get_leadtypes($uvargs); // Axl UWS-7416

$uvreturn = array(
    "venuecode" => $uvvenuecode,
    "manageentid" => $uvmanageentid,
    "leadtypes" => $uvleadtypeslist,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416