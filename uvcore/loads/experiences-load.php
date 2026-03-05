<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path, $uws_today;

// @egt [UWS-7297]
// uws_check_nonce("uwsexperiences");
urvenue_ws_check_nonce("uwsexperiences"); // Axl UWS-7416

include_once($uws_path . "/includes/experiences-functions.php");

// $uvdate = uws_cleanup_request("date", $uws_today);
$uvdate = urvenue_ws_cleanup_request("date", $uws_today); // Axl UWS-7416

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
echo($uvreturnjson);