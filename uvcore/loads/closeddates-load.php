<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path;

// @egt [UWS-7297]
// uws_check_nonce("uwsreservations");
urvenue_ws_check_nonce("uwsreservations"); // Axl UWS-7416

// $uvdate = uws_cleanup_request("date");
$uvdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvecozone = uws_cleanup_request("ecozone", "ECZ0");
$uvecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416

$uvreturn = array();

if($uvdate and $uvvenuecode and $uvecozone){
    $uvargs = array(
        "date" => $uvdate,
        "venuecode" => $uvvenuecode,
        "ecozone" => $uvecozone
    );
    // $uvmonthnoinventorydates = uws_get_month_closed_dates($uvargs);
    $uvmonthnoinventorydates = urvenue_ws_get_month_closed_dates($uvargs); // Axl UWS-7416
    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;
}
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);