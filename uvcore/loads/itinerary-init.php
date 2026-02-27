<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path;

// @egt [UWS-7297]
uws_check_nonce("uwsitinerary");

include_once($uws_path . "/includes/itinerary-functions.php");

$uvittootipitems = uws_get_itinerarytooltips();

$uvitineraryinfo = array(
    "tooltips" => $uvittootipitems,
);

// @Axl
// $uvreturnjson = json_encode($uvitineraryinfo);
$uvreturnjson = wp_json_encode($uvitineraryinfo);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);