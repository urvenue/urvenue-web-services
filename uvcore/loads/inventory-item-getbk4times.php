<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmastercode = uws_cleanup_request("mastercode");
$uvdate = uws_cleanup_request("caldate");
$uvguests = uws_cleanup_request("guests");
$uvextdatajson = uws_cleanup_request("ext_datajson");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvextdatajson = ($uvextdatajson) ? stripslashes(html_entity_decode($uvextdatajson)) : $uvextdatajson;

$uvbk4args = array(
    "date" => $uvdate,
    "mastercode" => $uvmastercode,
    "venuecode" => $uvvenuecode,
    "extdata" => $uvextdatajson,
);
$uvitembk4timesel = uws_get_itembk4sel($uvbk4args);

$uvreturn = array(
    "html" => $uvitembk4timesel,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);