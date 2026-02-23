<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmastercode = uws_cleanup_request("mastercode");
$uvotid = uws_cleanup_request("otid");
$uvresattr = uws_cleanup_request("resatt");
$uvdate = uws_cleanup_request("caldate");
$uvguests = uws_cleanup_request("guests");

$uvotdata = "otid:" . $uvotid . "|resatt:" . $uvresattr;
$uvotargs = array(
    "otdata" => $uvotdata,
    "date" => $uvdate,
    "guests" => $uvguests,
    "mastercode" => $uvmastercode,
);
$uvitemottimesel = uws_get_itemotsel($uvotargs);

$uvreturn = array(
    "html" => $uvitemottimesel,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);