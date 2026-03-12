<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvvenueid = uws_cleanup_request("venueid");
$uvvenueid = urvenue_ws_cleanup_request("venueid"); // Axl UWS-7416
// $uvminspend = uws_cleanup_request("subtotalagree", 0);
$uvminspend = urvenue_ws_cleanup_request("subtotalagree", 0); // Axl UWS-7416
// $uvcurrencysymbol = uws_cleanup_request("currencysymbol", "$");
$uvcurrencysymbol = urvenue_ws_cleanup_request("currencysymbol", "$"); // Axl UWS-7416

$uvbottleargs = array(
    "venueid" => $uvvenueid,
    "minspend" => $uvminspend,
    "currencysymbol" => $uvcurrencysymbol,
);
// $uvitembottlesel = uws_get_itembottlesel($uvbottleargs);
$uvitembottlesel = urvenue_ws_get_itembottlesel($uvbottleargs); // Axl UWS-7416

$uvreturn = array(
    "html" => $uvitembottlesel["html"],
    "menubottles" => $uvitembottlesel["menubottles"],
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416