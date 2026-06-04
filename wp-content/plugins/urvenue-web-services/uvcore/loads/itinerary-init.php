<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_itinerary");
urvenue_ws_check_nonce("urvenue_ws_itinerary"); // Axl UWS-7416

include_once($urvenue_ws_path . "/includes/itinerary-functions.php");

// $urvenue_ws_ittootipitems = uws_get_itinerarytooltips();
$urvenue_ws_ittootipitems = urvenue_ws_get_itinerarytooltips(); // Axl UWS-7416

$urvenue_ws_itineraryinfo = array(
    "tooltips" => $urvenue_ws_ittootipitems,
);

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_itineraryinfo);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_itineraryinfo);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416