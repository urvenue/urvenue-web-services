<?php
global $uws_path;

// @egt [UWS-7297]
uws_check_nonce("uwsitinerary");

include_once($uws_path . "/includes/itinerary-functions.php");

$uvittootipitems = uws_get_itinerarytooltips();

$uvitineraryinfo = array(
    "tooltips" => $uvittootipitems,
);

$uvreturnjson = json_encode($uvitineraryinfo);
header('Content-Type: application/json');
echo($uvreturnjson);