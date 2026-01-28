<?php

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
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);