<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

// @egt [UWS-7297]
// $nonceaction = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : ''; // Axl UWS-7418
$nonceaction = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : ''; // Axl UWS-7418
switch ($nonceaction) {
    case 'uwsinventory':
        // uws_check_nonce("uwsinventory");
        urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416
        break;
    case 'uwsmap':
        // uws_check_nonce("uwsmap");
        urvenue_ws_check_nonce("uwsmap"); // Axl UWS-7416
        break;
    case 'uwspackages':
        // uws_check_nonce("uwspackages");
        urvenue_ws_check_nonce("uwspackages"); // Axl UWS-7416
        break;
    default:
        wp_send_json_error(['message' => 'Invalid action'], 400);
}

// $uvdate = uws_cleanup_request("date");
$uvdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvecozone = uws_cleanup_request("ecozone", "ECZ0");
$uvecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416
// $uvglobaltype = uws_cleanup_request("globaltype");
$uvglobaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $uvmixecozones = uws_cleanup_request("mixecozones");
$uvmixecozones = urvenue_ws_cleanup_request("mixecozones"); // Axl UWS-7416

$uvreturn = array();

if($uvdate and $uvvenuecode and $uvecozone){
    $uvargs = array(
        "date" => $uvdate,
        "venuecode" => $uvvenuecode,
        "ecozone" => $uvecozone,
        "globaltype" => $uvglobaltype,
        "mixecozones" => $uvmixecozones,
    );
    // $uvmonthnoinventorydates = uws_get_month_noinventory_dates($uvargs);
    $uvmonthnoinventorydates = urvenue_ws_get_month_noinventory_dates($uvargs); // Axl UWS-7416
    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;
}
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416