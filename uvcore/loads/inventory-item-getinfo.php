<?php

global $uws_proxies_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmastercode = uws_cleanup_request("mastercode");
$uvitem = uws_get_invitem($uvmastercode);

$uvreturn = array(
    "item" => $uvitem,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);