<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path;

$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvecozone = (isset($_REQUEST["ecozone"])) ? uws_cleanup_var($_REQUEST["ecozone"]) : "ECZ0";
$uvglobaltype = (isset($_REQUEST["globaltype"])) ? uws_cleanup_var($_REQUEST["globaltype"]) : "";
$uvmixecozones = (isset($_REQUEST["mixecozones"])) ? uws_cleanup_var($_REQUEST["mixecozones"]) : "";

$uvreturn = array();

if($uvdate and $uvvenuecode and $uvecozone){
    $uvargs = array(
        "date" => $uvdate,
        "venuecode" => $uvvenuecode,
        "ecozone" => $uvecozone,
        "globaltype" => $uvglobaltype,
        "mixecozones" => $uvmixecozones,
    );
    $uvmonthnoinventorydates = uws_get_month_noinventory_dates($uvargs);
    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;
}
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);