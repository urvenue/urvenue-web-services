<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
// uws_check_nonce("uwsevents");
urvenue_ws_check_nonce("uwsevents"); // Axl UWS-7416

// $urvenue_ws_fromdate = uws_cleanup_request("date");
$urvenue_ws_fromdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $urvenue_ws_todate = uws_cleanup_request("enddate");
$urvenue_ws_todate = urvenue_ws_cleanup_request("enddate"); // Axl UWS-7416
// $urvenue_ws_venue = uws_cleanup_request("venue");
$urvenue_ws_venue = urvenue_ws_cleanup_request("venue"); // Axl UWS-7416

$urvenue_ws_returnjson = "";
$urvenue_ws_response = array(
    "uv" => array(
        "success" => array(
            "status" => "error",
        ),
        "message" => "No events found.",
        "data" => array(),
    ),
);

// $uvargs = array(
$urvenue_ws_args = array( // Axl UWS-7634
    "fromdate" => $urvenue_ws_fromdate,
    "todate" => $urvenue_ws_todate,
    "venuecodes" => $urvenue_ws_venue,
);

if(strpos($urvenue_ws_venue, 'VEN') !== false)//if venue is venuecodes and not venue internal uvcore key
    $urvenue_ws_args["venuecodes"] = $urvenue_ws_venue; // Axl UWS-7634
else
    $urvenue_ws_args["venue"] = $urvenue_ws_venue; // Axl UWS-7634

// $urvenue_ws_events = uws_get_events($uvargs);
// $urvenue_ws_events = urvenue_ws_get_events($uvargs); // Axl UWS-7416
$urvenue_ws_events = urvenue_ws_get_events($urvenue_ws_args); // Axl UWS-7634
$urvenue_ws_dates = array();

if (is_array($urvenue_ws_events) && count($urvenue_ws_events) > 0) {
    // foreach ($urvenue_ws_events as $uvevent) {
    foreach ($urvenue_ws_events as $urvenue_ws_event) { // Axl UWS-7634
        if (
            // isset($uvevent["date"]) &&
            isset($urvenue_ws_event["date"]) && // Axl UWS-7634
            // isset($uvevent["eventcode"]) &&
            isset($urvenue_ws_event["eventcode"]) && // Axl UWS-7634
            // isset($uvevent["maineventcode"]) &&
            isset($urvenue_ws_event["maineventcode"]) && // Axl UWS-7634
            // $uvevent["eventcode"] === $uvevent["maineventcode"]
            $urvenue_ws_event["eventcode"] === $urvenue_ws_event["maineventcode"] // Axl UWS-7634
        ) {
            // $urvenue_ws_dstarttime = ($uvevent["dstarttime"]) ? $uvevent["dstarttime"] : "";
            $urvenue_ws_dstarttime = ($urvenue_ws_event["dstarttime"]) ? $urvenue_ws_event["dstarttime"] : ""; // Axl UWS-7634
            $urvenue_ws_dstarttimediv = ($urvenue_ws_dstarttime) ? "<div class='uwsdtime'>" . $urvenue_ws_dstarttime . "</div>" : "";

            // $urvenue_ws_usedate = $uvevent["date"];
            $urvenue_ws_usedate = $urvenue_ws_event["date"]; // Axl UWS-7634
            $urvenue_ws_ddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($urvenue_ws_usedate));

            if (!isset($urvenue_ws_dates[$urvenue_ws_usedate])) {
                $urvenue_ws_dates[$urvenue_ws_usedate] = array();
            }
            $urvenue_ws_dates[$urvenue_ws_usedate][] = array(
                // "eventcode" => $uvevent["eventcode"],
                "eventcode" => $urvenue_ws_event["eventcode"], // Axl UWS-7634
                // "eventurl" => isset($uvevent["event-page-url"]) ? $uvevent["event-page-url"] : "",
                "eventurl" => isset($urvenue_ws_event["event-page-url"]) ? $urvenue_ws_event["event-page-url"] : "", // Axl UWS-7634
                // "eventname" => isset($uvevent["name"]) ? $uvevent["name"] : "",
                "eventname" => isset($urvenue_ws_event["name"]) ? $urvenue_ws_event["name"] : "", // Axl UWS-7634
                "eventdate" => $urvenue_ws_usedate,
                // "eventddate" => uws_lang_date($urvenue_ws_ddate),
                "eventddate" => urvenue_ws_lang_date($urvenue_ws_ddate), // Axl UWS-7416
                "eventstarttime" => $urvenue_ws_dstarttimediv,
                // "eventflyer" => isset($uvevent["flyers"]["eventpage"]["full"]) ? $uvevent["flyers"]["eventpage"]["full"] : "",
                "eventflyer" => isset($urvenue_ws_event["flyers"]["eventpage"]["full"]) ? $urvenue_ws_event["flyers"]["eventpage"]["full"] : "", // Axl UWS-7634
            );
        }
    }
}

if(is_array($urvenue_ws_events) && count($urvenue_ws_events) > 0){
    $urvenue_ws_response = array(
        "uv" => array(
            "success" => array(
                "status" => "success",
            ),
            "message" => "",
            "data" => array(
                "fromdate" => $urvenue_ws_fromdate,
                "todate" => $urvenue_ws_todate,
                "venue" => $urvenue_ws_venue,
                "inventory" => $urvenue_ws_events,
                "events" => $urvenue_ws_dates,
            ),
        ),
    );
}

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_response);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_response);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416