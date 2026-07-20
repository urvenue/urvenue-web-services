<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_venueid = urvenue_ws_cleanup_request("venueid");
$urvenue_ws_minspend = urvenue_ws_cleanup_request("subtotalagree", 0);
$urvenue_ws_currencysymbol = urvenue_ws_cleanup_request("currencysymbol", "$");

$urvenue_ws_bottleargs = array(
    "venueid" => $urvenue_ws_venueid,
    "minspend" => $urvenue_ws_minspend,
    "currencysymbol" => $urvenue_ws_currencysymbol,
);
$urvenue_ws_itembottlesel = urvenue_ws_get_itembottlesel($urvenue_ws_bottleargs);

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itembottlesel["html"],
    "menubottles" => $urvenue_ws_itembottlesel["menubottles"],
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
