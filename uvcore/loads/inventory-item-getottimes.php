<?php

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvotid = (isset($_REQUEST["otid"])) ? uws_cleanup_var($_REQUEST["otid"]) : "";
$uvresattr = (isset($_REQUEST["resatt"])) ? uws_cleanup_var($_REQUEST["resatt"]) : "";
$uvdate = (isset($_REQUEST["caldate"])) ? uws_cleanup_var($_REQUEST["caldate"]) : "";
$uvguests = (isset($_REQUEST["guests"])) ? uws_cleanup_var($_REQUEST["guests"]) : "";

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