<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwsevents");

$uvfromdate = uws_cleanup_request("date");
$uvtodate = uws_cleanup_request("enddate");
$uvvenue = uws_cleanup_request("venue");
$uvnopredates = uws_cleanup_request("nopredates");
$uvbuttonlabel = uws_cleanup_request("btnlabel");
$uvviews = (isset($_REQUEST["views"])) ? explode(",", uws_cleanup_var($_REQUEST["views"])) : null;
$uvdefaultview = $_REQUEST["defaultview"] ? uws_cleanup_var($_REQUEST["defaultview"]) : "";
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


$uveventsviews = uws_events_views($uvargs, true);
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