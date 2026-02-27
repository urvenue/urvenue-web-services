<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path;

// @egt [UWS-7297]
uws_check_nonce("uwsreservations");

$uvdate = uws_cleanup_request("date");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvecozone = uws_cleanup_request("ecozone", "ECZ0");

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