<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvcartcode = uws_cleanup_request("cartcode");
$uvitemcartcode = uws_cleanup_request("itemcartcode");
$uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;
$uvmastercode = uws_cleanup_request("mastercode");
$uvitem = ($uvmastercode) ? uws_get_invitem($uvmastercode) : "";

if($uvcartcode and $uvitemcartcode){
    $uvcartparams = "cartcode=$uvcartcode&itemcartcode=$uvitemcartcode";
    $uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";
    $uvcartfeedresponse = uws_get_apiwvar("cart-delete", $uvcartparams, $uveventdata);
}

if(is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
    $uvcartcode = $uvcartfeedresponse["uv"]["data"]["cartcode"];
    $uvcartcode = ($uvcartfeedresponse["uv"]["data"]["cart"]) ? $uvcartcode : "";
    $uvcart = uws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse);
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