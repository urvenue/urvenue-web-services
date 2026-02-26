<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uveventcodes = (isset($_REQUEST["eventcodes"])) ? uws_cleanup_var($_REQUEST["eventcodes"]) : "";
$uvtemplates = (isset($_REQUEST["templates"])) ? uws_cleanup_var($_REQUEST["templates"]) : "";

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