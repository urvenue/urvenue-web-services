<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvdate = (isset($_REQUEST["caldate"])) ? uws_cleanup_var($_REQUEST["caldate"]) : "";
$uvguests = (isset($_REQUEST["guests"])) ? uws_cleanup_var($_REQUEST["guests"]) : "";
$uvextdatajson = (isset($_REQUEST["ext_datajson"])) ? uws_cleanup_var($_REQUEST["ext_datajson"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
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