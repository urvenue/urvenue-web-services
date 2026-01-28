<?php

$uveventcode = (isset($_REQUEST["homeeventcode"])) ? uws_cleanup_var($_REQUEST["homeeventcode"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvshowdatepicker = (isset($_REQUEST["showdatepicker"])) ? uws_cleanup_var($_REQUEST["showdatepicker"]) : 0;
$uvshowinventorybuttons = (isset($_REQUEST["showinventorybuttons"])) ? uws_cleanup_var($_REQUEST["showinventorybuttons"]) : 1;
$uvglobaltype = (isset($_REQUEST["globaltype"])) ? uws_cleanup_var($_REQUEST["globaltype"]) : "";
$uvbooktypename = (isset($_REQUEST["booktypename"])) ? uws_cleanup_var($_REQUEST["booktypename"]) : "";
$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
$uvenddate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
$uvmanagementid = (isset($_REQUEST["managementid"])) ? uws_cleanup_var($_REQUEST["managementid"]) : "";
$uvmixecozones = (isset($_REQUEST["mixecozones"])) ? uws_cleanup_var($_REQUEST["mixecozones"]) : 0;

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
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);