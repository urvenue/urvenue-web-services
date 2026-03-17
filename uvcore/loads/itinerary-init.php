<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

// @egt [UWS-7297]
// uws_check_nonce("uwsitinerary");
urvenue_ws_check_nonce("uwsitinerary"); // Axl UWS-7416

include_once($urvenue_ws_path . "/includes/itinerary-functions.php");

// $uvittootipitems = uws_get_itinerarytooltips();
$uvittootipitems = urvenue_ws_get_itinerarytooltips(); // Axl UWS-7416

$uvitineraryinfo = array(
    "tooltips" => $uvittootipitems,
);

// @Axl
// $uvreturnjson = json_encode($uvitineraryinfo);
$uvreturnjson = wp_json_encode($uvitineraryinfo);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416