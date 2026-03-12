<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvitem = uws_get_invitem($uvmastercode);
$uvitem = urvenue_ws_get_invitem($uvmastercode); // Axl UWS-7416

$uvreturn = array(
    "item" => $uvitem,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416