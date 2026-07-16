<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_item = urvenue_ws_get_invitem($urvenue_ws_mastercode);

$urvenue_ws_return = array(
    "item" => $urvenue_ws_item,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
