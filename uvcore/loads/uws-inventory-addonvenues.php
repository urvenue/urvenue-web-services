<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uveventcode = uws_cleanup_request("homeeventcode");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvshowdatepicker = uws_cleanup_request("showdatepicker", 0);
$uvshowinventorybuttons = uws_cleanup_request("showinventorybuttons", 1);
$uvglobaltype = uws_cleanup_request("globaltype");
$uvbooktypename = uws_cleanup_request("booktypename");
$uvdate = uws_cleanup_request("date");
$uvenddate = uws_cleanup_request("enddate");
$uvmanagementid = uws_cleanup_request("managementid");
$uvmixecozones = uws_cleanup_request("mixecozones", 0);

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
$uvinventorywidget = uws_get_inventorywidget($uvargs);

$uvreturn = array(
    "markup" => $uvinventorywidget,
);
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);