<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvitemcartcode = uws_cleanup_request("itemcartcode");
$uvitemcartcode = urvenue_ws_cleanup_request("itemcartcode"); // Axl UWS-7416
// $uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;
// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvitem = ($uvmastercode) ? uws_get_invitem($uvmastercode) : "";
$uvitem = ($uvmastercode) ? urvenue_ws_get_invitem($uvmastercode) : ""; // Axl UWS-7416

if($uvcartcode and $uvitemcartcode){
    $uvcartparams = "cartcode=$uvcartcode&itemcartcode=$uvitemcartcode";
    $uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";
    // $uvcartfeedresponse = uws_get_apiwvar("cart-delete", $uvcartparams, $uveventdata);
    $uvcartfeedresponse = urvenue_ws_get_apiwvar("cart-delete", $uvcartparams, $uveventdata); // Axl UWS-7416
}

if(is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
    $uvcartcode = $uvcartfeedresponse["uv"]["data"]["cartcode"];
    $uvcartcode = ($uvcartfeedresponse["uv"]["data"]["cart"]) ? $uvcartcode : "";
    // $uvcart = uws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse);
    $uvcart = urvenue_ws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse); // Axl UWS-7416
}

$uvreturn = array(
    "cart" => $uvcart,
    "cartcode" => $uvcartcode,
    "item" => $uvitem
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);