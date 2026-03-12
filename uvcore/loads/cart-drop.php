<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

//Add the thing to the cart
// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;

$uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";

// $uvcart = uws_get_cart($uvcartcode, $uveventdata);
$uvcart = urvenue_ws_get_cart($uvcartcode, $uveventdata); // Axl UWS-7416

$uvreturn = array(
    "cart" => $uvcart
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416