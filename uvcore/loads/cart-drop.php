<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

//Add the thing to the cart
$uvcartcode = uws_cleanup_request("cartcode");
$uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;

$uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";

$uvcart = uws_get_cart($uvcartcode, $uveventdata);

$uvreturn = array(
    "cart" => $uvcart
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);