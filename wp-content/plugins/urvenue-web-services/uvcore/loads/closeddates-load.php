<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_reservations");
urvenue_ws_check_nonce("urvenue_ws_reservations"); // Axl UWS-7416

// $urvenue_ws_date = uws_cleanup_request("date");
$urvenue_ws_date = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_ecozone = uws_cleanup_request("ecozone", "ECZ0");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416

$urvenue_ws_return = array();

if($urvenue_ws_date and $urvenue_ws_venuecode and $urvenue_ws_ecozone){
    $urvenue_ws_args = array(
        "date" => $urvenue_ws_date,
        "venuecode" => $urvenue_ws_venuecode,
        "ecozone" => $urvenue_ws_ecozone
    );
    // $urvenue_ws_monthnoinventorydates = uws_get_month_closed_dates($urvenue_ws_args);
    $urvenue_ws_monthnoinventorydates = urvenue_ws_get_month_closed_dates($urvenue_ws_args); // Axl UWS-7416
    $urvenue_ws_return["availabilityinfo"] = $urvenue_ws_monthnoinventorydates;
}
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416