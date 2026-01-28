<?php

global $uws_proxies_lib;

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitem = uws_get_invitem($uvmastercode);

$uvreturn = array(
    "item" => $uvitem,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);