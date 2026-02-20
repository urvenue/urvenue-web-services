<?php

global $uws_feeds_lib;

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
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);