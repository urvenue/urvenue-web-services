<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwspackages");

$uvmasteritemcode = uws_cleanup_request("masteritemcode");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvdate = uws_cleanup_request("date");

$uvmasteritemcodeinfo = array(
    "masteritemcode" => $uvmasteritemcode,
    "venuecode" => $uvvenuecode,
    "date" => $uvdate
);

$uvmastercode = uws_get_mastercode_by_masteritemcode($uvmasteritemcodeinfo);

//test no mastercode found
//$uvmastercode = "";

$uvreturn = array(
    "mastercode" => $uvmastercode,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);