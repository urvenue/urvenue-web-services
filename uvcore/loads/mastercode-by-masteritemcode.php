<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwspackages");
urvenue_ws_check_nonce("uwspackages"); // Axl UWS-7416

// $uvmasteritemcode = uws_cleanup_request("masteritemcode");
$uvmasteritemcode = urvenue_ws_cleanup_request("masteritemcode"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvdate = uws_cleanup_request("date");
$uvdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416

$uvmasteritemcodeinfo = array(
    "masteritemcode" => $uvmasteritemcode,
    "venuecode" => $uvvenuecode,
    "date" => $uvdate
);

// $uvmastercode = uws_get_mastercode_by_masteritemcode($uvmasteritemcodeinfo);
$uvmastercode = urvenue_ws_get_mastercode_by_masteritemcode($uvmasteritemcodeinfo); // Axl UWS-7416

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