<?php

// @egt [UWS-7297]
uws_check_nonce("uwspackages");

$uvmasteritemcode = uws_cleanup_request("masteritemcode");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvdate = uws_cleanup_request("date");

$uvmasteritemcodeinfo = array(
    "masteritemcode" => $uvmasteritemcode,
    "venuecode" => $uvvenuecode,
    "date" => $uvdate
);

$uvmastercode = uws_get_mastercode_by_masteritemcode($uvmasteritemcodeinfo);

//test no mastercode found
//$uvmastercode = "";

$uvreturn = array(
    "mastercode" => $uvmastercode,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);