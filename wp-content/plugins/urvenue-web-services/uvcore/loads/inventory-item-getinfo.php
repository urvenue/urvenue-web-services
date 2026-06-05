<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

// $uvmastercode = uws_cleanup_request("mastercode");
// $uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7634
// $uvitem = uws_get_invitem($uvmastercode);
// $uvitem = urvenue_ws_get_invitem($uvmastercode); // Axl UWS-7416
$urvenue_ws_item = urvenue_ws_get_invitem($urvenue_ws_mastercode); // Axl UWS-7634

// $uvreturn = array(
$urvenue_ws_return = array( // Axl UWS-7634
    "item" => $urvenue_ws_item,
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
// $uvreturnjson = wp_json_encode($uvreturn);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return); // Axl UWS-7634
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
// echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7634