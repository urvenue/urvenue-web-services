<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

urvenue_ws_check_nonce("urvenue_ws_itinerary");

include_once($urvenue_ws_path . "/includes/itinerary-functions.php");

$urvenue_ws_ittootipitems = urvenue_ws_get_itinerarytooltips();

$urvenue_ws_itineraryinfo = array(
    "tooltips" => $urvenue_ws_ittootipitems,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_itineraryinfo);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
