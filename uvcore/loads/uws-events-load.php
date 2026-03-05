<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsevents");
urvenue_ws_check_nonce("uwsevents"); // Axl UWS-7416

// $uvfromdate = uws_cleanup_request("date");
$uvfromdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $uvtodate = uws_cleanup_request("enddate");
$uvtodate = urvenue_ws_cleanup_request("enddate"); // Axl UWS-7416
// $uvvenue = uws_cleanup_request("venue");
$uvvenue = urvenue_ws_cleanup_request("venue"); // Axl UWS-7416
// $uvnopredates = uws_cleanup_request("nopredates");
$uvnopredates = urvenue_ws_cleanup_request("nopredates"); // Axl UWS-7416
// $uvbuttonlabel = uws_cleanup_request("btnlabel");
$uvbuttonlabel = urvenue_ws_cleanup_request("btnlabel"); // Axl UWS-7416
// $uvviews = (isset($_REQUEST["views"])) ? explode(",", uws_cleanup_var($_REQUEST["views"])) : null;
$uvviews = (isset($_REQUEST["views"])) ? explode(",", urvenue_ws_cleanup_var($_REQUEST["views"])) : null; // Axl UWS-7416
// $uvdefaultview = $_REQUEST["defaultview"] ? uws_cleanup_var($_REQUEST["defaultview"]) : "";
$uvdefaultview = $_REQUEST["defaultview"] ? urvenue_ws_cleanup_var($_REQUEST["defaultview"]) : ""; // Axl UWS-7416
//$uvnowrap = uws_cleanup_request("nowrap");

$uvargs = array(
    "fromdate" => $uvfromdate,
    "todate" => $uvtodate,
    //"venue" => $uvvenue,
    "nopredates" => $uvnopredates,
    "nowrap" => true,
);

if (is_array($uvviews) && count($uvviews) > 0)
    $uvargs["views"] = $uvviews;

if($uvdefaultview)
    $uvargs["defaultview"] = $uvdefaultview;

if($uvbuttonlabel)
    $uvargs["buttonlabel"] = $uvbuttonlabel;

if(strpos($uvvenue, 'VEN') !== false)//if venue is venuecodes and not venue internal uvcore key
    $uvargs["venuecodes"] = $uvvenue;
else
    $uvargs["venue"] = $uvvenue;


// $uveventsviews = uws_events_views($uvargs, true);
$uveventsviews = urvenue_ws_events_views($uvargs, true); // Axl UWS-7416
$uveventsviews["nextloaddate"] = date("Y-m-d", strtotime($uveventsviews["todate"] . " +1 day"));

$uvreturnjson = "";

if(is_array($uveventsviews)){
    // @Axl
    // $uvreturnjson = json_encode($uveventsviews);
    $uvreturnjson = wp_json_encode($uveventsviews);
    // @Axl End
}

header('Content-Type: application/json');
echo($uvreturnjson);