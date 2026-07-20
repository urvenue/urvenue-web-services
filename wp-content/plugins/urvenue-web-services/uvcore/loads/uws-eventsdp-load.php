<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_events");

$urvenue_ws_fromdate = urvenue_ws_cleanup_request("date");
$urvenue_ws_todate = urvenue_ws_cleanup_request("enddate");
$urvenue_ws_venue = urvenue_ws_cleanup_request("venue");

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

$urvenue_ws_args = array(
    "fromdate" => $urvenue_ws_fromdate,
    "todate" => $urvenue_ws_todate,
    "venuecodes" => $urvenue_ws_venue,
);

if(strpos($urvenue_ws_venue, 'VEN') !== false)//if venue is venuecodes and not venue internal uvcore key
    $urvenue_ws_args["venuecodes"] = $urvenue_ws_venue;
else
    $urvenue_ws_args["venue"] = $urvenue_ws_venue;

$urvenue_ws_events = urvenue_ws_get_events($urvenue_ws_args);
$urvenue_ws_dates = array();

if (is_array($urvenue_ws_events) && count($urvenue_ws_events) > 0) {
    foreach ($urvenue_ws_events as $urvenue_ws_event) {
        if (
            isset($urvenue_ws_event["date"]) &&
            isset($urvenue_ws_event["eventcode"]) &&
            isset($urvenue_ws_event["maineventcode"]) &&
            $urvenue_ws_event["eventcode"] === $urvenue_ws_event["maineventcode"]
        ) {
            $urvenue_ws_dstarttime = ($urvenue_ws_event["dstarttime"]) ? $urvenue_ws_event["dstarttime"] : "";
            $urvenue_ws_dstarttimediv = ($urvenue_ws_dstarttime) ? "<div class='uwsdtime'>" . $urvenue_ws_dstarttime . "</div>" : "";

            $urvenue_ws_usedate = $urvenue_ws_event["date"];
            $urvenue_ws_ddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($urvenue_ws_usedate));

            if (!isset($urvenue_ws_dates[$urvenue_ws_usedate])) {
                $urvenue_ws_dates[$urvenue_ws_usedate] = array();
            }
            $urvenue_ws_dates[$urvenue_ws_usedate][] = array(
                "eventcode" => $urvenue_ws_event["eventcode"],
                "eventurl" => isset($urvenue_ws_event["event-page-url"]) ? $urvenue_ws_event["event-page-url"] : "",
                "eventname" => isset($urvenue_ws_event["name"]) ? $urvenue_ws_event["name"] : "",
                "eventdate" => $urvenue_ws_usedate,
                "eventddate" => urvenue_ws_lang_date($urvenue_ws_ddate),
                "eventstarttime" => $urvenue_ws_dstarttimediv,
                "eventflyer" => isset($urvenue_ws_event["flyers"]["eventpage"]["full"]) ? $urvenue_ws_event["flyers"]["eventpage"]["full"] : "",
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

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_response);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
