<?php

global $uws_proxies_lib;

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitem = uws_get_invitem($uvmastercode);

$uvreturn = array(
    "item" => $uvitem,
);
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);