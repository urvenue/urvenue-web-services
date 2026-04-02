<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $urvenue_ws_eventcode = uws_cleanup_request("homeeventcode");
$urvenue_ws_eventcode = urvenue_ws_cleanup_request("homeeventcode"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_showdatepicker = uws_cleanup_request("showdatepicker", 0);
$urvenue_ws_showdatepicker = urvenue_ws_cleanup_request("showdatepicker", 0); // Axl UWS-7416
// $urvenue_ws_showinventorybuttons = uws_cleanup_request("showinventorybuttons", 1);
$urvenue_ws_showinventorybuttons = urvenue_ws_cleanup_request("showinventorybuttons", 1); // Axl UWS-7416
// $urvenue_ws_globaltype = uws_cleanup_request("globaltype");
$urvenue_ws_globaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $urvenue_ws_booktypename = uws_cleanup_request("booktypename");
$urvenue_ws_booktypename = urvenue_ws_cleanup_request("booktypename"); // Axl UWS-7416
// $urvenue_ws_date = uws_cleanup_request("date");
$urvenue_ws_date = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $urvenue_ws_enddate = uws_cleanup_request("enddate");
$urvenue_ws_enddate = urvenue_ws_cleanup_request("enddate"); // Axl UWS-7416
// $urvenue_ws_managementid = uws_cleanup_request("managementid");
$urvenue_ws_managementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
// $urvenue_ws_mixecozones = uws_cleanup_request("mixecozones", 0);
$urvenue_ws_mixecozones = urvenue_ws_cleanup_request("mixecozones", 0); // Axl UWS-7416

$urvenue_ws_hidedatepicker = 1;
$urvenue_ws_showinventorybuttons = 1;

$urvenue_ws_args = array(
    "venuecode" => $urvenue_ws_venuecode,
    "eventcode" => $urvenue_ws_eventcode,
    "date" => $urvenue_ws_date,
    "onlyweekdays" => "All",
    "managementid" => $urvenue_ws_managementid,
    "globaltype" => $urvenue_ws_globaltype,
    "booktypename" => $urvenue_ws_booktypename,
    "errortitle" => "There are no items available",
    "errorcontent" => "We could not find any available experience.",
    "hidedatepicker" => $urvenue_ws_hidedatepicker,
    "showinventorybuttons" => $urvenue_ws_showinventorybuttons,
);
// $urvenue_ws_inventorywidget = uws_get_inventorywidget($urvenue_ws_args);
$urvenue_ws_inventorywidget = urvenue_ws_get_inventorywidget($urvenue_ws_args); // Axl UWS-7416

$urvenue_ws_return = array(
    "markup" => $urvenue_ws_inventorywidget,
);
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416