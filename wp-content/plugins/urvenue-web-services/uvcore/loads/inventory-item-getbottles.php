<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $urvenue_ws_venueid = uws_cleanup_request("venueid");
$urvenue_ws_venueid = urvenue_ws_cleanup_request("venueid"); // Axl UWS-7416
// $urvenue_ws_minspend = uws_cleanup_request("subtotalagree", 0);
$urvenue_ws_minspend = urvenue_ws_cleanup_request("subtotalagree", 0); // Axl UWS-7416
// $urvenue_ws_currencysymbol = uws_cleanup_request("currencysymbol", "$");
$urvenue_ws_currencysymbol = urvenue_ws_cleanup_request("currencysymbol", "$"); // Axl UWS-7416

$urvenue_ws_bottleargs = array(
    "venueid" => $urvenue_ws_venueid,
    "minspend" => $urvenue_ws_minspend,
    "currencysymbol" => $urvenue_ws_currencysymbol,
);
// $urvenue_ws_itembottlesel = uws_get_itembottlesel($urvenue_ws_bottleargs);
$urvenue_ws_itembottlesel = urvenue_ws_get_itembottlesel($urvenue_ws_bottleargs); // Axl UWS-7416

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itembottlesel["html"],
    "menubottles" => $urvenue_ws_itembottlesel["menubottles"],
);
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416