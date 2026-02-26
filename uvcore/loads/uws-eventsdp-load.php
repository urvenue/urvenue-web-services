<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uvfromdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
$uvtodate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
$uvvenue = (isset($_REQUEST["venue"])) ? uws_cleanup_var($_REQUEST["venue"]) : "";

$uvreturnjson = "";
$uvresponse = array(
    "uv" => array(
        "success" => array(
            "status" => "error",
        ),
        "message" => "No events found.",
        "data" => array(),
    ),
);

$uvargs = array(
    "fromdate" => $uvfromdate,
    "todate" => $uvtodate,
    "venuecodes" => $uvvenue,
);

if(strpos($uvvenue, 'VEN') !== false)//if venue is venuecodes and not venue internal uvcore key
    $uvargs["venuecodes"] = $uvvenue;
else
    $uvargs["venue"] = $uvvenue;

$uvevents = uws_get_events($uvargs);
$uvdates = array();

if (is_array($uvevents) && count($uvevents) > 0) {
    foreach ($uvevents as $uvevent) {
        if (
            isset($uvevent["date"]) &&
            isset($uvevent["eventcode"]) &&
            isset($uvevent["maineventcode"]) &&
            $uvevent["eventcode"] === $uvevent["maineventcode"]
        ) {
            $uvdstarttime = ($uvevent["dstarttime"]) ? $uvevent["dstarttime"] : "";
            $uvdstarttimediv = ($uvdstarttime) ? "<div class='uwsdtime'>" . $uvdstarttime . "</div>" : "";
            
            $uvusedate = $uvevent["date"];
            $uvddate = date($uws_core_lib["events"]["global-dateformat"], strtotime($uvusedate));
            
            if (!isset($uvdates[$uvusedate])) {
                $uvdates[$uvusedate] = array();
            }
            $uvdates[$uvusedate][] = array(
                "eventcode" => $uvevent["eventcode"],
                "eventurl" => isset($uvevent["event-page-url"]) ? $uvevent["event-page-url"] : "",
                "eventname" => isset($uvevent["name"]) ? $uvevent["name"] : "",
                "eventdate" => $uvusedate,
                "eventddate" => uws_lang_date($uvddate),
                "eventstarttime" => $uvdstarttimediv,
                "eventflyer" => isset($uvevent["flyers"]["eventpage"]["full"]) ? $uvevent["flyers"]["eventpage"]["full"] : "",
            );
        }
    }
}

if(is_array($uvevents) && count($uvevents) > 0){
    $uvresponse = array(
        "uv" => array(
            "success" => array(
                "status" => "success",
            ),
            "message" => "",
            "data" => array(
                "fromdate" => $uvfromdate,
                "todate" => $uvtodate,
                "venue" => $uvvenue,
                "inventory" => $uvevents,
                "events" => $uvdates,
            ),
        ),
    );
}

// @Axl
// $uvreturnjson = json_encode($uvresponse);
$uvreturnjson = wp_json_encode($uvresponse);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);