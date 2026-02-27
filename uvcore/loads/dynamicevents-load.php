<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// @egt [UWS-7297]
uws_check_nonce("uwsevents");

$uveventcodes = uws_cleanup_request("eventcodes");
$uvtemplates = uws_cleanup_request("templates");

$uveventhtml = "";
//support multiple events templates on the future
if($uveventcodes and $uvtemplates){
    $uvargs = array(
        "eventcode" => $uveventcodes,
        "template" => $uvtemplates
    );

    ob_start();
    uws_event($uvargs);
    $uveventhtml = ob_get_contents();
  	ob_end_clean();
}

$uvreturn = array(
    $uveventcodes => array(
        $uvtemplates => array(
            "html" => $uveventhtml
        )
    )
);
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);