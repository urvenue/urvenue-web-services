<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path, $urvenue_ws_today;

urvenue_ws_check_nonce("urvenue_ws_experiences");

include_once($urvenue_ws_path . "/includes/experiences-functions.php");

$urvenue_ws_date = urvenue_ws_cleanup_request("date", $urvenue_ws_today);

$urvenue_ws_experiences = urvenue_ws_get_dummyapi("experiences");
$urvenue_ws_invitems = urvenue_ws_inventory_microcode_items(array("date" => $urvenue_ws_date));
//Add real items to dummy filters api --- shound connect the filters latter
$urvenue_ws_experiences["items"] = $urvenue_ws_invitems;
$urvenue_ws_experienceslist = urvenue_ws_get_experiences_list($urvenue_ws_experiences);

$urvenue_ws_return = array(
    "list" => $urvenue_ws_experienceslist
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
