<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uvmasteritemcode = (isset($_REQUEST["masteritemcode"])) ? uws_cleanup_var($_REQUEST["masteritemcode"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";

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
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);