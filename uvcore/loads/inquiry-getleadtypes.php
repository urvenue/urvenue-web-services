<?php

global $uws_feeds_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsreservations");

$uvmanageentid = (isset($_REQUEST["manageentid"])) ? uws_cleanup_var($_REQUEST["manageentid"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvvenueid = str_replace("VEN", "", $uvvenuecode);

$uvargs = array(
    "manageentid" => $uvmanageentid,
    "venueid" => $uvvenueid,
);
$uvleadtypeslist = uws_inquiry_get_leadtypes($uvargs);

$uvreturn = array(
    "venuecode" => $uvvenuecode,
    "manageentid" => $uvmanageentid,
    "leadtypes" => $uvleadtypeslist,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);