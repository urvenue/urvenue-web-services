<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_events");

$urvenue_ws_fromdate = urvenue_ws_cleanup_request("date");
$urvenue_ws_todate = urvenue_ws_cleanup_request("enddate");
$urvenue_ws_venue = urvenue_ws_cleanup_request("venue");
$urvenue_ws_nopredates = urvenue_ws_cleanup_request("nopredates");
$urvenue_ws_buttonlabel = urvenue_ws_cleanup_request("btnlabel");
$urvenue_ws_views = (isset($_REQUEST["views"])) ? explode(",", urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["views"] ) ) )) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_events") above
$urvenue_ws_defaultview = (isset($_REQUEST["defaultview"]) && sanitize_text_field( wp_unslash( $_REQUEST["defaultview"] ) )) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["defaultview"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_events") above
//$uvnowrap = uws_cleanup_request("nowrap");

$urvenue_ws_args = array(
    "fromdate" => $urvenue_ws_fromdate,
    "todate" => $urvenue_ws_todate,
    //"venue" => $urvenue_ws_venue,
    "nopredates" => $urvenue_ws_nopredates,
    "nowrap" => true,
);

if (is_array($urvenue_ws_views) && count($urvenue_ws_views) > 0)
    $urvenue_ws_args["views"] = $urvenue_ws_views;

if($urvenue_ws_defaultview)
    $urvenue_ws_args["defaultview"] = $urvenue_ws_defaultview;

if($urvenue_ws_buttonlabel)
    $urvenue_ws_args["buttonlabel"] = $urvenue_ws_buttonlabel;

if(strpos($urvenue_ws_venue, 'VEN') !== false)//if venue is venuecodes and not venue internal uvcore key
    $urvenue_ws_args["venuecodes"] = $urvenue_ws_venue;
else
    $urvenue_ws_args["venue"] = $urvenue_ws_venue;


$urvenue_ws_eventsviews = urvenue_ws_events_views($urvenue_ws_args, true);
$urvenue_ws_eventsviews["nextloaddate"] = gmdate("Y-m-d", strtotime($urvenue_ws_eventsviews["todate"] . " +1 day"));

$urvenue_ws_returnjson = "";

if(is_array($urvenue_ws_eventsviews)){
    $urvenue_ws_returnjson = wp_json_encode($urvenue_ws_eventsviews);
}

header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
