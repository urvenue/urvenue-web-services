<?php

global $uws_path;

// @egt [UWS-7297]
$nonceaction = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';
switch ($nonceaction) {
    case 'uwsinventory':
        uws_check_nonce("uwsinventory");
        break;
    case 'uwsmap':
        uws_check_nonce("uwsmap");
        break;
    case 'uwspackages':
        uws_check_nonce("uwspackages");
        break;
    default:
        wp_send_json_error(['message' => 'Invalid action'], 400);
}

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
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);