<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

// @egt [UWS-7297]
// $urvenue_ws_nonceaction = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : ''; // Axl UWS-7418
// $urvenue_ws_nonceaction = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : ''; // Axl UWS-7418
$urvenue_ws_nonceaction = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading action to determine which nonce to verify in the switch below // Axl UWS-7416
switch ($urvenue_ws_nonceaction) {
    case 'urvenue_ws_inventory':
        // uws_check_nonce("urvenue_ws_inventory");
        urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416
        break;
    case 'urvenue_ws_map':
        // uws_check_nonce("urvenue_ws_map");
        urvenue_ws_check_nonce("urvenue_ws_map"); // Axl UWS-7416
        break;
    case 'urvenue_ws_packages':
        // uws_check_nonce("urvenue_ws_packages");
        urvenue_ws_check_nonce("urvenue_ws_packages"); // Axl UWS-7416
        break;
    default:
        wp_send_json_error(['message' => 'Invalid action'], 400);
}

// $urvenue_ws_date = uws_cleanup_request("date");
$urvenue_ws_date = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_ecozone = uws_cleanup_request("ecozone", "ECZ0");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416
// $urvenue_ws_globaltype = uws_cleanup_request("globaltype");
$urvenue_ws_globaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $urvenue_ws_mixecozones = uws_cleanup_request("mixecozones");
$urvenue_ws_mixecozones = urvenue_ws_cleanup_request("mixecozones"); // Axl UWS-7416

$urvenue_ws_return = array();

if($urvenue_ws_date and $urvenue_ws_venuecode and $urvenue_ws_ecozone){
    $urvenue_ws_args = array(
        "date" => $urvenue_ws_date,
        "venuecode" => $urvenue_ws_venuecode,
        "ecozone" => $urvenue_ws_ecozone,
        "globaltype" => $urvenue_ws_globaltype,
        "mixecozones" => $urvenue_ws_mixecozones,
    );
    // $urvenue_ws_monthnoinventorydates = uws_get_month_noinventory_dates($urvenue_ws_args);
    $urvenue_ws_monthnoinventorydates = urvenue_ws_get_month_noinventory_dates($urvenue_ws_args); // Axl UWS-7416
    $urvenue_ws_return["availabilityinfo"] = $urvenue_ws_monthnoinventorydates;
}
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416