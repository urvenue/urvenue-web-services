<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsevents");
urvenue_ws_check_nonce("uwsevents"); // Axl UWS-7416

// $urvenue_ws_eventcodes = uws_cleanup_request("eventcodes");
$urvenue_ws_eventcodes = urvenue_ws_cleanup_request("eventcodes"); // Axl UWS-7416
// $urvenue_ws_templates = uws_cleanup_request("templates");
$urvenue_ws_templates = urvenue_ws_cleanup_request("templates"); // Axl UWS-7416

$urvenue_ws_eventhtml = "";
//support multiple events templates on the future
if($urvenue_ws_eventcodes and $urvenue_ws_templates){
    $urvenue_ws_args = array(
        "eventcode" => $urvenue_ws_eventcodes,
        "template" => $urvenue_ws_templates
    );

    ob_start();
    // uws_event($urvenue_ws_args);
    urvenue_ws_event($urvenue_ws_args); // Axl UWS-7416
    $urvenue_ws_eventhtml = ob_get_contents();
  	ob_end_clean();
}

$urvenue_ws_return = array(
    $urvenue_ws_eventcodes => array(
        $urvenue_ws_templates => array(
            "html" => $urvenue_ws_eventhtml
        )
    )
);
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416