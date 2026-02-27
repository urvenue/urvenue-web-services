<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_feeds_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsreservations");

$uvmanageentid = uws_cleanup_request("manageentid");
$uvvenuecode = uws_cleanup_request("venuecode");
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