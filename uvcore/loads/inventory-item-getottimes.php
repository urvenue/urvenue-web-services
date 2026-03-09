<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvotid = uws_cleanup_request("otid");
$uvotid = urvenue_ws_cleanup_request("otid"); // Axl UWS-7416
// $uvresattr = uws_cleanup_request("resatt");
$uvresattr = urvenue_ws_cleanup_request("resatt"); // Axl UWS-7416
// $uvdate = uws_cleanup_request("caldate");
$uvdate = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $uvguests = uws_cleanup_request("guests");
$uvguests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416

$uvotdata = "otid:" . $uvotid . "|resatt:" . $uvresattr;
$uvotargs = array(
    "otdata" => $uvotdata,
    "date" => $uvdate,
    "guests" => $uvguests,
    "mastercode" => $uvmastercode,
);
// $uvitemottimesel = uws_get_itemotsel($uvotargs);
$uvitemottimesel = urvenue_ws_get_itemotsel($uvotargs); // Axl UWS-7416

$uvreturn = array(
    "html" => $uvitemottimesel,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);