<?php
global $uws_path;

include_once($uws_path . "/includes/itinerary-functions.php");

$uvittootipitems = uws_get_itinerarytooltips();

$uvitineraryinfo = array(
    "tooltips" => $uvittootipitems,
);

$uvreturnjson = json_encode($uvitineraryinfo);
header('Content-Type: application/json');
echo($uvreturnjson);