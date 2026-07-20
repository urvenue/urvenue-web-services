<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path;

$urvenue_ws_nonceaction = isset($_REQUEST['action']) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading action to determine which nonce to verify in the switch below
switch ($urvenue_ws_nonceaction) {
    case 'urvenue_ws_inventory':
        urvenue_ws_check_nonce("urvenue_ws_inventory");
        break;
    case 'urvenue_ws_map':
        urvenue_ws_check_nonce("urvenue_ws_map");
        break;
    case 'urvenue_ws_packages':
        urvenue_ws_check_nonce("urvenue_ws_packages");
        break;
    default:
        wp_send_json_error(['message' => 'Invalid action'], 400);
}

$urvenue_ws_date = urvenue_ws_cleanup_request("date");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0");
$urvenue_ws_globaltype = urvenue_ws_cleanup_request("globaltype");
$urvenue_ws_mixecozones = urvenue_ws_cleanup_request("mixecozones");

$urvenue_ws_return = array();

if($urvenue_ws_date and $urvenue_ws_venuecode and $urvenue_ws_ecozone){
    $urvenue_ws_args = array(
        "date" => $urvenue_ws_date,
        "venuecode" => $urvenue_ws_venuecode,
        "ecozone" => $urvenue_ws_ecozone,
        "globaltype" => $urvenue_ws_globaltype,
        "mixecozones" => $urvenue_ws_mixecozones,
    );
    $urvenue_ws_monthnoinventorydates = urvenue_ws_get_month_noinventory_dates($urvenue_ws_args);
    $urvenue_ws_return["availabilityinfo"] = $urvenue_ws_monthnoinventorydates;
}

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
