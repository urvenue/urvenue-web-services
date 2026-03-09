<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uveventcode = uws_cleanup_request("homeeventcode");
$uveventcode = urvenue_ws_cleanup_request("homeeventcode"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvshowdatepicker = uws_cleanup_request("showdatepicker", 0);
$uvshowdatepicker = urvenue_ws_cleanup_request("showdatepicker", 0); // Axl UWS-7416
// $uvshowinventorybuttons = uws_cleanup_request("showinventorybuttons", 1);
$uvshowinventorybuttons = urvenue_ws_cleanup_request("showinventorybuttons", 1); // Axl UWS-7416
// $uvglobaltype = uws_cleanup_request("globaltype");
$uvglobaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $uvbooktypename = uws_cleanup_request("booktypename");
$uvbooktypename = urvenue_ws_cleanup_request("booktypename"); // Axl UWS-7416
// $uvdate = uws_cleanup_request("date");
$uvdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $uvenddate = uws_cleanup_request("enddate");
$uvenddate = urvenue_ws_cleanup_request("enddate"); // Axl UWS-7416
// $uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
// $uvmixecozones = uws_cleanup_request("mixecozones", 0);
$uvmixecozones = urvenue_ws_cleanup_request("mixecozones", 0); // Axl UWS-7416

$uvhidedatepicker = 1;
$uvshowinventorybuttons = 1;

$uvargs = array(
    "venuecode" => $uvvenuecode,
    "eventcode" => $uveventcode,
    "date" => $uvdate,
    "onlyweekdays" => "All",
    "managementid" => $uvmanagementid,
    "globaltype" => $uvglobaltype,
    "booktypename" => $uvbooktypename,
    "errortitle" => "There are no items available",
    "errorcontent" => "We could not find any available experience.",
    "hidedatepicker" => $uvhidedatepicker,
    "showinventorybuttons" => $uvshowinventorybuttons,
);
// $uvinventorywidget = uws_get_inventorywidget($uvargs);
$uvinventorywidget = urvenue_ws_get_inventorywidget($uvargs); // Axl UWS-7416

$uvreturn = array(
    "markup" => $uvinventorywidget,
);
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);