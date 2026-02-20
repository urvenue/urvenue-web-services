<?php

global $uws_path;

$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvecozone = (isset($_REQUEST["ecozone"])) ? uws_cleanup_var($_REQUEST["ecozone"]) : "ECZ0";

$uvreturn = array();

if($uvdate and $uvvenuecode and $uvecozone){
    $uvargs = array(
        "date" => $uvdate,
        "venuecode" => $uvvenuecode,
        "ecozone" => $uvecozone
    );
    $uvmonthnoinventorydates = uws_get_month_closed_dates($uvargs);
    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;
}
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);