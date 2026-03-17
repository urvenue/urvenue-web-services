<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path, $urvenue_ws_today;

// @egt [UWS-7297]
// uws_check_nonce("uwsexperiences");
urvenue_ws_check_nonce("uwsexperiences"); // Axl UWS-7416

include_once($urvenue_ws_path . "/includes/experiences-functions.php");

// $uvdate = uws_cleanup_request("date", $urvenue_ws_today);
$uvdate = urvenue_ws_cleanup_request("date", $urvenue_ws_today); // Axl UWS-7416

// $uvexperiences = uws_get_dummyapi("experiences");
$uvexperiences = urvenue_ws_get_dummyapi("experiences"); // Axl UWS-7416
// $uvinvitems = uws_inventory_microcode_items(array("date" => $uvdate));
$uvinvitems = urvenue_ws_inventory_microcode_items(array("date" => $uvdate)); // Axl UWS-7416
//Add real items to dummy filters api --- shound connect the filters latter
$uvexperiences["items"] = $uvinvitems;
// $uvexperienceslist = uws_get_experiences_list($uvexperiences);
$uvexperienceslist = urvenue_ws_get_experiences_list($uvexperiences); // Axl UWS-7416

$uvreturn = array(
    "list" => $uvexperienceslist
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416